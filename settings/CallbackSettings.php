<?php /**
* Класс с настройками по умолчанию установка переменных для работы виджета из коробки
* 
*/
class CallbackSettings extends yupe\widgets\YWidget
{
	const CONSTRUCT_VIEW = '_construct';

	protected $_buttonOptions = [];
	protected $_modalOptions = [];
	protected $_formOptions = [];
    protected $_templateOptions = [];      
    protected $_mailOptions = [];
	protected $_title; 						

	public $type 							= 	'block';
	public $title							=	'';
	public $id								=  	'';
	public $template 						= 	'{name}{email}{phone}{text}{button}{message}';
	public $successMessage 					= 	'';
	public $errorMessage 					= 	'';
	public $buttonOptions 					= 	[];
	public $modalOptions 					= 	[];
    public $formOptions 					= 	[];
    public $templateOptions 				= 	[
    												'default'	=>	[]
    											];      
    public $mailOptions 					= 	[]; 
	

    public function setDefault(){
    	$this->id 				= empty($this->id) ? $this->getId() : $this->id;
		$this->title 			= Yii::t('CallbackModule.callback','Заявка на обратный звонок');
		$this->successMessage 	= Yii::t('CallbackModule.callback','Мы вскоре свяжемся с Вами!');
		$this->errorMessage 	= Yii::t('CallbackModule.callback','Произошла ошибка отправки, попробуйте еще раз');
		$this->_modalOptions 	= [
									'id'			=>	$this->id.'_modal',
									'class'			=>	'row',
									'closeText'		=>	'<i class="fa fa-remove"></i>',
									'title'			=>	$this->title,
								];
		$this->_buttonOptions 	= [
									'context'		=>	'info',
									'buttonType'	=>	'link',
									'label'			=>	Yii::t('CallbackModule.callback','Оставить заявку'),
									'url'			=>	CHtml::normalizeUrl(['/callback']),
									'htmlOptions'	=>	[
															'data-toggle'	=>	'modal',
													        'data-target'	=>	'#'.$this->_modalOptions['id'],
														]
								];
		$this->_formOptions		= [
									'type'						=>	'horizontal',		
									'id'						=>	$this->id.'_form',		
									'action'					=>	CHtml::normalizeUrl(['/callback/send']),			
									'ajax'						=>	false,		
									'enableClientValidation'	=>	true,							
									'resetOptions'				=>	[
																		'resetForm'		    =>	true,
																		'resetCaptcha'		=>	true,
																		'closeModal'		=>	true,
																		'clearMessage'		=>	true,
																		'timeout'		    =>	4000,		
																	],		
									'htmlOptions'				=>	[
																		'class'		        =>	'callback-mixer col-sm-12'
																	],
									'prevText'					=>	'',			
									'title'						=>	$this->title,						
									'prevBodyText'				=>	'',			
									'afterBodyText'				=>	'',			
									'afterText'					=>	'',
									'showFormAfterSend'			=> 	false,		
								];
		$this->_templateOptions = [
									'default'		=>	[
														'groupOptions'	    =>	[
																				'class'		=>	'col-xs-12'
																				],
														'widgetOptions'		=>	[
																				'htmlOptions'	=>	[
																									'class'		=>	'col-xs-12'
																									]
																				]
														],
									'button'		=>	[
														'context'		=>	'info',
														'buttonType'	=>	'submit',
										                'label'			=>	Yii::t('CallbackModule.callback','Send'),
										                'htmlOptions'	=>	[
										                						'class'	=> 'col-sm-2'
										                					]
														],
									'phoneMasked'	=>	[
														'widgetOptions'	    => [
																				'model' 	    => $model,
																				'attribute' 	=> 'phoneMasked',
																				'mask' 	        => '8(999)999-99-99',
																				'htmlOptions'	=> [
																									'placeholder'=>'8(___)___-__-__'
																									]
																				]
														],
									'message'		=>	[
														'id'			=>	'success',
														'class'			=>	'alert',
														],

									'verifyCode'	=>	[
														'controller' 			        => 	Yii::app()->createController("callback/callback/index")[0],
														'wrapperHtmlOptions'	        =>	[
																								'class'		=>	'col-xs-9 wp-wm'
																							],
														'groupOptions'			        =>	[
																								'class'		=> 	'col-xs-12'
																							],
														'widgetOptions'			        =>	[
																							'textFieldOptions'          => [
																															'labelOptions'	        =>	[
																																							'class'	  	=> 'hidden'
																																						],
																															'groupOptions'          =>	[
																																							'class'		=> 	'col-xs-7'
																																						],
																															'wrapperHtmlOptions'	=>	[
																																							'class'		=>	'col-xs-12',
																																						],
																							],
																							'verifyCodeOptions'         =>	[
																															'wrapperHtmlOptions'	=>	[
																																'class'		        =>	'col-xs-5 wp-wm text-right'
																															],
																															'widgetOptions'	        =>	[
																																						'showRefreshButton'	=>	true,
																																						'clickableImage' 	=> true,
																																	                    'imageOptions'     	=>	[
																																							                        'width'		=> '150',
																																							                    ],
																																						'captchaAction'		=> 'captcha'.$this->id,
																											                							'buttonOptions'     =>	[
																																								                        'class'   	=> 'btn btn-default',
																																								                    ],
																											                							'buttonLabel'       => '<i class="glyphicon glyphicon-repeat"></i>',
																															]
																							]
														]	
									]
								];
		$this->_mailOptions		= [
									'view'		=>	'_text',
									'from'		=>	Yii::app()->getModule('callback')->getAddress(1),
									'to'		=>	Yii::app()->getModule('callback')->emailsRecipients,
									'title'		=>	Yii::t('CallbackModule.callback','Оповещение об обратном звонке'),
								];
    }
    
} ?>