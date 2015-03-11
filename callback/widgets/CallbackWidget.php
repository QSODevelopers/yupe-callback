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

	private $body; //widget body

	public $type 							= 'modal';
	public $id								=  '';
	public $title 							= 'Заявка на обратный звонок';
	public $template 						= '{name}{email}{phone}{text}';
	public $renderAfterSuccess 				= true;
	public $successMessage 					= 'Мы вскоре свяжемся с Вами!';
	public $errorMessage 					= 'Произашла ошибка отправки, попробуйте еще раз';
	public $buttonOptions 					= [];
	public $modalOptions 					= [];
    public $formOptions 					= [];
    public $templateOptions 				= [];      
    public $mailOptions 					= [];     

    public function setDefault(){
    	$this->id 				= $this->getId();
		$this->title 			= Yii::t('CallbackModule.callback',$this->title);
		$this->successMessage 	= Yii::t('CallbackModule.callback',$this->successMessage);
		$this->errorMessage 	= Yii::t('CallbackModule.callback',$this->errorMessage);
		$this->_buttonOptions 	= [
									'context'		=>	'link',
									'label'			=>	'Оставить заявку',
									'url'			=>	CHtml::normalizeUrl(['/callback/send']),
									'htmlOptions'	=>	[
											'data-toggle'	=>	'modal',
									        'data-target'	=>	'#'.$this->id,
										]
								];
		$this->_modalOptions 	= [
									'id'			=>	$this->getId(),
									'class'			=>	'row',
									'closeText'		=>	'<i class="fa fa-remove"></i>',
									'title'			=>	$this->title,
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
									'title'						=>	Yii::t('CallbackModule.callback','Заявка на обратный звонок'),							
									'prevTextBody'				=>	'',			
									'afterTextBody'				=>	'',			
									'renderAfterSuccess'		=> 	false,		
									'afterText'					=>	'',
								];
		$this->_templateOptions = [
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

	public function init(){
		$this->setDefault();
		$this->buttonOptions = CMap::mergeArray($this->_buttonOptions,$this->buttonOptions);
		$this->modalOptions = CMap::mergeArray($this->_modalOptions,$this->modalOptions);
		$this->formOptions = CMap::mergeArray($this->_formOptions,$this->formOptions);
		$this->templateOptions = CMap::mergeArray($this->_templateOptions,$this->templateOptions);
		$this->mailOptions = CMap::mergeArray($this->_mailOptions,$this->mailOptions);

		return parent::init();
	}

	public function createBody(){
		preg_replace_callback("/{(\w+)}/",array($this,'renderSection'),$this->template);
	}

	protected function renderSection($matches){
		$method='render'.$matches[1];
		if(method_exists(FormTemplater,$method))
			$this->body .= FormTemplater::$method();
		else
			throw new CException(Yii::t('CallbackModule.callback', 'Нет такого атрибута. Это тебе не шахтерский ребус, не надо тут угадывать. Загляни в исходный код, или допиши свое.'));
	}

	public function run(){
		$model = new CallbackModel;
		$model->setRules($this->template); 

		$this->createBody();
		$this->render($this->view,['model'=>$model,'body'=>$this->body]);
	}
}