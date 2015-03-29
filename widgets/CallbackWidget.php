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
Yii::import('application.modules.callback.forms.CallbackForm');
Yii::import('application.modules.callback.CallbackModule');
Yii::import('application.modules.callback.components.FormTemplater');
Yii::import('application.modules.callback.settings.CallbackSettings');

class CallbackWidget extends CallbackSettings{ 

	private $body; //widget body

	public function getConstructView(){
		return $this->view;
	}
    /**
    * Инициализаия виджета, установка переменных по умолчанию
    * @return void
    **/
	public function init(){
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
    * Запуск виджета
    * 
    * @return void
    **/
	public function run(){
		$model = new CallbackForm;
		$model->setUnicName($this->id);
		$model->setRules($this->template);

		//Если отключен JS и пришел не ajax виджет сам обрабатывает свой массив POST
		if(!Yii::app()->request->isAjaxrequest && $_POST['widgetId'] == $this->id)
			Processing::validate($model);
		
		$this->createBody();
		$this->render(self::CONSTRUCT_VIEW,[
						'model'	=>	$model,
						'body'	=>	$this->body
					]);
	}
}