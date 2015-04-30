<?php
/**
 * Виждет для вывода сконструированной формы
 *
 * @author UnnamedTeam
 * @link http://none.shit
 * @copyright 2015 UnnamedTeam & Project Yupe!Flavoring 
 * @package yupe.modules.callback.install
 * @license  BSD
 *
 **/
//Yii::import('application.modules.callback.forms.CallbackForm');
Yii::import('application.modules.callback.CallbackModule');
Yii::import('application.modules.callback.components.FormTemplater');
Yii::import('application.modules.callback.settings.CallbackSettings');

class CallbackWidget extends CallbackSettings{ 

	private $body; //widget body
	private $afterValidateJs;

	public function getConstructView(){
		return $this->view;
	}
    /**
    * Инициализаия виджета, установка переменных по умолчанию
    * @return void
    **/
	public function init(){
		if($this->status == Callback::TURN_OFF)
			return false;
		$this->setDefault();

		$this->buttonOptions = CMap::mergeArray($this->_buttonOptions,$this->buttonOptions);
		$this->modalOptions = CMap::mergeArray($this->_modalOptions,$this->modalOptions);
		$this->formOptions = CMap::mergeArray($this->_formOptions,$this->formOptions);

		if(isset($this->templateOptions['default']))
			$this->_templateOptions['default'] = CMap::mergeArray($this->_templateOptions['default'], $this->templateOptions['default']);

		foreach (FormTemplater::getTempDefaultForSetting() as $temp) {
			$this->_templateOptions[$temp] = CMap::mergeArray($this->_templateOptions[$temp],$this->_templateOptions['default']);
		}
		
		$this->templateOptions = CMap::mergeArray($this->_templateOptions,$this->templateOptions);
		$this->mailOptions = CMap::mergeArray($this->_mailOptions,$this->mailOptions);

		return parent::init();
	}

	/**
    * Вызов метода разбора templates по функциям
    * 
    * @return void
    **/
	public function createBody(){
		preg_replace_callback("/{(\w+)}/",array($this,'renderSection'),$this->template);
	}

	/**
    * Вызов методов добавления кода templatов
    * 
    * @return void
    **/
	protected function renderSection($matches){
		$method='render'.$matches[1];
		if(method_exists(FormTemplater,$method))
			$this->body .= FormTemplater::$method();
		else
			throw new CException(Yii::t('CallbackModule.callback', 'Нет такого атрибута {temp}. Это тебе не шахтерский ребус, не надо тут угадывать. Загляни в исходный код, или допиши свое.', ['{temp}' => $matches[1]]));
	}

	/**
    * Вызов метода сборки js для виджета
    * 
    * @return void
    **/
	public function createJs(){
		//Сброс виджета
		$resetJs = 'setTimeout(function(){';
		$resetJs .= !$this->formOptions['resetOptions']['resetForm']?'':	'$("#'.$this->formOptions['id'].'").trigger("reset");
							 												 $("button",form).removeAttr("disabled");
							 												 form.removeClass("succes error");';
		$resetJs .= !$this->formOptions['resetOptions']['resetCaptcha']?'': '$(".captcha>a,.captcha>button",form).click();';					 
		$resetJs .= !$this->formOptions['resetOptions']['closeModal']?'': 	'$("#close-modal").click();';
		$resetJs .= !$this->formOptions['resetOptions']['clearMessage']?'': 'var $success = $("#'.$this->templateOptions['message']['id'].'",form);
							 												 $success.removeClass("alert-success alert-danger show").html("");';
		$resetJs .='},'.$this->formOptions['resetOptions']['timeout'].');';

		//Вывод консоли ответа от сервака, при продакшене не отображаеться, если мешает и умеете пользоваться Netwokr просто закоментировать
		$debug = !YII_DEBUG ? '' :';form.append("<pre id=\'consoleLog\'><h2>Server answer: <strong>"+data.statusText+"</strong></h2>"+data.responseText+"</pre>");';

		//Подпись формы после валидации для ajax						
		$ajaxSuck = $this->formOptions['ajax']?'+"&formValid="+getHash(form.serialize())':'';						
								
		//Переменная накапливающая скрипт действий после валидации
		$this->afterValidateJs = 'js:function(form,data,hasError){
			function getHash(formData){
				hashString = "";
				found = decodeURI(formData).match(/=[\w]+/g);
				found.forEach (function(item, i, arr) {
					hashString += /[\w]+/.exec(item)[0];
				})
				for(var i=hashString.length-1, hash=0; i >= 0; --i) hash+=hashString.charCodeAt(i);
				return hash;
			}
			if(!hasError)
			{
				$.ajax(
				{
					"type"		: 	"POST",
					"url"		: 	"'.$this->formOptions['action'].'",
					"data"		: 	form.serialize()'.$ajaxSuck.',
					"beforeSend": 	function(){
										$("button",form).attr("disabled","disabled");
										$("#consoleLog",form).remove();
									},
					"success"	:	function(data){
										data = $.parseJSON(data);
										var $success = $("#'.$this->templateOptions['message']['id'].'",form);
										if(data.result){
												$success.addClass("alert-success show").html("'.$this->successMessage.'");
												form.addClass("success");
										}else{
												$success.addClass("alert-danger show").html("'.$this->errorMessage.'");
												form.addClass("error");
										}
									},
					"complete"  :	function(data){
										'.$resetJs.'
										'.$debug.'
									},
				});
			}
		}';
	}

	/**
    * Запуск виджета
    * 
    * @return void
    **/
	public function run(){
		
		if($this->status == Callback::TURN_OFF)
			return false;
		$model = new CallbackForm;
		$model->setUnicName($this->id);
		$model->setRules($this->template);
		
		//Если отключен JS и пришел не ajax виджет сам обрабатывает свой массив POST
		if(!Yii::app()->request->isAjaxrequest && $_POST['widgetId'] == $this->id)
			Processing::validate($model);
		
		$this->createBody();
		$this->createJs();
		$this->render(self::CONSTRUCT_VIEW,[
						'model'				=>	$model,
						'body'				=>	$this->body,
						'afterValidateJs'	=>	$this->afterValidateJs
					]);
	}
}