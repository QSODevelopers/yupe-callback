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
Yii::import('application.modules.callback.models.CallbackModel');
Yii::import('application.modules.callback.CallbackModule');
Yii::import('application.modules.callback.components.SiteHelper');
Yii::import('application.modules.callback.components.FormTemplater');

class CallbackWidget extends yupe\widgets\YWidget{

	public $view = '_construct';

	private $_buttonOptions = [];
	private $_modalOptions = [];
	private $_formOptions = [];
    private $_templateOptions = [];      
    private $_mailOptions = [];
	private $_title; 						

	private $body; //widget body

	public $type 							= 	'modal';
	public $title							=	'Заявка на обратный звонок';
	public $id								=  	'';
	public $template 						= 	'{name}{email}{phone}{text}';
	public $renderAfterSuccess 				= 	true;
	public $successMessage 					= 	'Мы вскоре свяжемся с Вами!';
	public $errorMessage 					= 	'Произашла ошибка отправки, попробуйте еще раз';
	public $buttonOptions 					= 	[];
	public $modalOptions 					= 	[];
    public $formOptions 					= 	[];
    public $templateOptions 				= 	[
    												'default'	=>	[]
    											];      
    public $mailOptions 					= 	[];     

    public function setDefault(){
    	$this->id 				= $this->getId();
		$this->_title 			= Yii::t('CallbackModule.callback',$this->title);
		$this->successMessage 	= Yii::t('CallbackModule.callback',$this->successMessage);
		$this->errorMessage 	= Yii::t('CallbackModule.callback',$this->errorMessage);
		$this->_modalOptions 	= [
									'id'			=>	$this->getId(),
									'class'			=>	'row',
									'closeText'		=>	'<i class="fa fa-remove"></i>',
									'title'			=>	$this->_title,
								];
		$this->_buttonOptions 	= [
									'context'		=>	'link',
									'label'			=>	Yii::t('CallbackModule.callback','Оставить заявку'),
									'url'			=>	CHtml::normalizeUrl(['/callback/send']),
									'htmlOptions'	=>	[
											'data-toggle'	=>	'modal',
									        'data-target'	=>	'#'.$this->_modalOptions['id'],
										]
								];
		$this->_formOptions		= [
									'type'						=>	'horizontal',		
									'id'						=>	$this->getId(),		
									'action'					=>	CHtml::normalizeUrl(['/callback/send']),			
									'actionCaptcha'				=>	CHtml::normalizeUrl(['/callback/captcha']),				
									'ajax'						=>	true,		
									'enableClientValidation'	=>	true,							
									'resetOptions'				=>	[
																		'enableReset'		=>	true,
																		'timeout'			=>	4000,		
																	],		
									'htmlOptions'				=>	[],
									'prevText'					=>	'',			
									'title'						=>	$this->_title,						
									'prevTextBody'				=>	'',			
									'afterTextBody'				=>	'',			
									'afterText'					=>	'',
									'showFormAfterSend'		=> 	false,		
								];
		$this->_templateOptions = [
									'default'	=>	[],
									'button'	=>	[
											'context'		=>	'info',
											'buttonType'	=>	'submit',
							                'label'			=>	Yii::t('CallbackModule.callback','Send'),
										],
									'message'	=>	[
											'id'			=>	'success',
											'class'			=>	'alert',
										],
									'service'	=>	[
											'list'			=>	[]
									]
								];
		$this->_mailOptions		= [
									'view'		=>	'_text',
									'from'		=>	Yii::app()->getModule('callback')->getAddress(1),
									'to'		=>	Yii::app()->getModule('callback')->emailsRecipients,
									'title'		=>	Yii::t('CallbackModule.callback','Оповещение об обратном звонке'),
								];
    }

    /**
    * Инициализаия виджета, установка переменных по умолчанию
    * return void
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
    **/
	public function createBody(){
		preg_replace_callback("/{(\w+)}/",array($this,'renderSection'),$this->template);
	}

	/**
    * Вызов методов добавления кода templatов
    * 
    **/
	protected function renderSection($matches){
		$method='render'.$matches[1];
		if(method_exists(FormTemplater,$method))
			$this->body .= FormTemplater::$method();
		else
			throw new CException(Yii::t('CallbackModule.callback', 'Нет такого атрибута {temp}. Это тебе не шахтерский ребус, не надо тут угадывать. Загляни в исходный код, или допиши свое.', ['{temp}' => $matches[1])]));
	}

	/**
    * Запуск виджета
    * 
    **/
	public function run(){
		$model = new CallbackModel;
		$model->setRules($this->template);

		//Если отключен JS и пришел не ajax виджет сам обрабатывает свой массив POST
		if(!Yii::app()->request->isAjaxrequest && $_POST['formId'] == $this->formOptions['id'])
			Processing::validate($model);

		$this->createBody();
		$this->render($this->view,['model'=>$model,'body'=>$this->body]);
	}
}