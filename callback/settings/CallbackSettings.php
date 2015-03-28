<?php /**
* 
*/
class CallbackSettings extends yupe\widgets\YWidget
{
	public $view = '_construct';

	public $_buttonOptions = [];
	public $_modalOptions = [];
	public $_formOptions = [];
    public $_templateOptions = [];      
    public $_mailOptions = [];
	public $_title; 						

	public $body; //widget body

	public $type 							= 	'block';
	public $title							=	'Заявка на обратный звонок';
	public $id								=  	'';
	public $template 						= 	'{name}{email}{phone}{text}{button}{message}';
	public $renderAfterSuccess 				= 	true;
	public $successMessage 					= 	'Мы вскоре свяжемся с Вами!';
	public $errorMessage 					= 	'Произошла ошибка отправки, попробуйте еще раз';
	public $buttonOptions 					= 	[];
	public $modalOptions 					= 	[];
    public $formOptions 					= 	[];
    public $templateOptions 				= 	[
    												'default'	=>	[]
    											];      
    public $mailOptions 					= 	[]; 
	

    public function setDefault(){
    	$this->id 				= empty($this->id) ? $this->getId() : $this->id;
		$this->_title 			= Yii::t('CallbackModule.callback',$this->title);
		$this->successMessage 	= Yii::t('CallbackModule.callback',$this->successMessage);
		$this->errorMessage 	= Yii::t('CallbackModule.callback',$this->errorMessage);
		$this->_modalOptions 	= [
									'id'			=>	$this->id.'_modal',
									'class'			=>	'row',
									'closeText'		=>	'<i class="fa fa-remove"></i>',
									'title'			=>	$this->_title,
								];
		$this->_buttonOptions 	= [
									'context'		=>	'info',
									'label'			=>	Yii::t('CallbackModule.callback','Оставить заявку'),
									'url'			=>	CHtml::normalizeUrl(['/callback/send']),
									'htmlOptions'	=>	[
															'data-toggle'	=>	'modal',
													        'data-target'	=>	'#'.$this->_modalOptions['id'],
														]
								];
		$this->_formOptions		= [
									'type'						=>	'horizontal',		
									'id'						=>	$this->id.'_form',		
									'action'					=>	CHtml::normalizeUrl(['/callback/send']),			
									'actionCaptcha'				=>	CHtml::normalizeUrl(['/callback/captcha']),				
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
									'title'						=>	$this->_title,						
									'prevBodyText'				=>	'',			
									'afterBodyText'				=>	'',			
									'afterText'					=>	'',
									'showFormAfterSend'		=> 	false,		
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
														'badLoginCount' 		        => 	0,
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