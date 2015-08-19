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

    public $id								=  	'';
    public $status							=  	Callback::TURN_ON;
	public $type 							= 	'block';
	public $title							=	'';
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
		$this->title 			= empty($this->title) ? Yii::t('CallbackModule.callback','Заявка на обратный звонок') : $this->title;;
		$this->successMessage 	= empty($this->successMessage) ? Yii::t('CallbackModule.callback','Мы вскоре свяжемся с Вами!') : $this->successMessage;;
		$this->errorMessage 	= empty($this->errorMessage) ? Yii::t('CallbackModule.callback','Произошла ошибка отправки, попробуйте еще раз') : $this->errorMessage;;
		$this->_modalOptions 	= [
									'widgetOptions' =>	[
															'htmlOptions'	=>	[
																					'id'			=>	$this->id.'_modal',
																					'class'			=>	'row',
																				],
														],
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
													        'data-target'	=>	'#'.$this->id.'_modal',
														]
								];
		$this->_formOptions		= [
									'type'						=>	'horizontal',		
									'id'						=>	$this->id.'_form',		
									'action'					=>	CHtml::normalizeUrl(['/callback/send']),			
									'ajax'						=>	false,		
									'clientValidation'			=>	true,							
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
																				],
														'errorOptions'		=>	[
																					'class'		=>	'help-block col-xs-12'
																				]
														],
									'button'		=>	[
														'wrapperHtmlOptions'=>	[
																					'class'	=>	'col-xs-12'
																				],
														'widgetOptions'		=>	[
																					'context'			=>	'info',
																					'buttonType'		=>	'submit',
																	                'label'				=>	Yii::t('CallbackModule.callback','Send'),
																	                'htmlOptions'		=>	[
																		                						'class'	=> 'col-xs-4'
																		                					]
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
									'calldate'		=>	[
														'widgetOptions' 	=> [
															                    'options' =>	[
																		                        	'format' => '',
																		                   		],
											                					],
														],
									'message'		=>	[
														'id'			=>	'success',
														'class'			=>	'col-xs-12',
														],
									'verifyCode'	=>	[
														'controller' 			        => 	$this->controller instanceof yupe\components\controllers\BackController ? $this->controller : Yii::app()->createController("callback/callback/index")[0],
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
									'from'		=>	':unnamed',
									'to'		=>	':admin',
									'title'		=>	Yii::t('CallbackModule.callback','Оповещение об обратном звонке'),
								];
    }
    
} ?>