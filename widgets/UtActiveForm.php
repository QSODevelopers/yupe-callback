<?php /**
* 
*/
Yii::import('bootstrap.widgets.TbActiveForm');
class UtActiveForm extends TbActiveForm
{
	/** 
	* See TbActiveform captchaGroup
	*/
	public function captchaGroup($model, $attribute, $options = array()) {

		$this->initOptions($options);
		$widgetOptions = $options['widgetOptions'];
		$controller = $options['controller'];

		$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');

		//TODO Ошибка капчи в админке после этой строчки
		$fieldData = $this->textFieldGroup($model, $attribute, $widgetOptions['textFieldOptions']);
		
		unset($widgetOptions['widgetOptions']);
		
		$captchaOptions = isset($widgetOptions['verifyCodeOptions']) ? $widgetOptions['verifyCodeOptions']: array(); // array('class' => 'form-group');
		self::addCssClass($captchaOptions['wrapperHtmlOptions'], 'captcha');
		$fieldData .= CHtml::openTag('div', $captchaOptions['wrapperHtmlOptions']);

		$fieldData .= $controller->widget('application.modules.callback.widgets.UtCaptcha', $captchaOptions['widgetOptions'], true);
		$fieldData .= '</div>';

		return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
	}

	/** 
	* See TbActiveform errorSummary
	*/
	public function errorSummary($models, $header=null, $footer= null, $options = array()) {
		$this->initOptions($options);

		$htmlOptions = $options['htmlOptions'];
		if (!isset($htmlOptions['class'])) {
			$htmlOptions['class'] = 'alert alert-block alert-danger';
		}else{
			$this->addCssClass($htmlOptions['htmlOptions'], 'alert alert-block alert-danger');
		}
		$header = $options['header'];
		$footer = $options['footer'];
		return parent::errorSummary($models, $header, $footer, $htmlOptions);
	}

	/** 
	* See TbActiveform customFieldGroupInternal
	*/
	protected function customFieldGroupInternal(&$fieldData, &$model, &$attribute, &$options) {
		
		$this->setDefaultId($fieldData);
		return parent::customFieldGroupInternal($fieldData, $model, $attribute, $options);
	}

	/**
	 * Sets default id value in case of CModel attribute depending on attribute name and unic widget name
	 *  
	 * @param array|string $fieldData Pre-rendered field as string or array of arguments for call_user_func_array() function.
	 */
	protected function setDefaultId(&$fieldData) {
		
		if(!is_array($fieldData) 
			|| empty($fieldData[0][1]) /* 'textField' */
			|| !is_array($fieldData[1]) /* ($model, $attribute, $htmlOptions) */
		)
			return;
			
		$model = $fieldData[1][0];
		if(!$model instanceof CModel)
			return;
		
		$attribute = $fieldData[1][1];
		if(!empty($fieldData[1][3]) && is_array($fieldData[1][3])) {
			/* ($model, $attribute, $data, $htmlOptions) */
			$htmlOptions = &$fieldData[1][3];
		} else {
			/* ($model, $attribute, $htmlOptions) */
			$htmlOptions = &$fieldData[1][2];
		}
		if (!isset($htmlOptions['id'])) {
			$htmlOptions['id'] = $model->getIdAttribute($attribute);
		}
		
	}
	/** 
	* See CHtml::error
	*/
	public function error($model,$attribute,$htmlOptions=array(),$enableAjaxValidation=true,$enableClientValidation=true){
		if(!$this->enableAjaxValidation)
			$enableAjaxValidation=false;
		if(!$this->enableClientValidation)
			$enableClientValidation=false;

		if(!isset($htmlOptions['class']))
			$htmlOptions['class']=$this->errorMessageCssClass;

		if(!$enableAjaxValidation && !$enableClientValidation)
			return CHtml::error($model,$attribute,$htmlOptions);

		if (!isset($htmlOptions['id'])) {
			$htmlOptions['id'] = $model->getIdAttribute($attribute);
		}

		$id=$htmlOptions['id'];
		$inputID=isset($htmlOptions['inputID']) ? $htmlOptions['inputID'] : $id;
		unset($htmlOptions['inputID']);
		//if(!isset($htmlOptions['id']))
			$htmlOptions['id']=$inputID.'_em_';

		$option=array(
			'id'=>$id,
			'inputID'=>$inputID,
			'errorID'=>$htmlOptions['id'],
			'model'=>get_class($model),
			'name'=>$attribute,
			'enableAjaxValidation'=>$enableAjaxValidation,
		);

		$optionNames=array(
			'validationDelay',
			'validateOnChange',
			'validateOnType',
			'hideErrorMessage',
			'inputContainer',
			'errorCssClass',
			'successCssClass',
			'validatingCssClass',
			'beforeValidateAttribute',
			'afterValidateAttribute',
		);
		foreach($optionNames as $name)
		{
			if(isset($htmlOptions[$name]))
			{
				$option[$name]=$htmlOptions[$name];
				unset($htmlOptions[$name]);
			}
		}
		if($model instanceof CActiveRecord && !$model->isNewRecord)
			$option['status']=1;

		if($enableClientValidation)
		{
			$validators=isset($htmlOptions['clientValidation']) ? array($htmlOptions['clientValidation']) : array();
			unset($htmlOptions['clientValidation']);

			$attributeName = $attribute;
			if(($pos=strrpos($attribute,']'))!==false && $pos!==strlen($attribute)-1) // e.g. [a]name
			{
				$attributeName=substr($attribute,$pos+1);
			}

			foreach($model->getValidators($attributeName) as $validator)
			{
				if($validator->enableClientValidation)
				{
					if(($js=$validator->clientValidateAttribute($model,$attributeName))!='')
						$validators[]=$js;
				}
			}
			if($validators!==array())
				$option['clientValidation']=new CJavaScriptExpression("function(value, messages, attribute) {\n".implode("\n",$validators)."\n}");
		}

		if(empty($option['hideErrorMessage']) && empty($this->clientOptions['hideErrorMessage']))
			$html=CHtml::error($model,$attribute,$htmlOptions);
		else
			$html='';
		if($html==='')
		{
			if(isset($htmlOptions['style']))
				$htmlOptions['style']=rtrim($htmlOptions['style'],';').';display:none';
			else
				$htmlOptions['style']='display:none';
			$html=CHtml::tag(CHtml::$errorContainerTag,$htmlOptions,'');
		}

		$this->attributes[$inputID]=$option;
		return $html;
	}

	public static function validate($models, $attributes=null, $loadInput=true){
		$result=array();
		if(!is_array($models))
			$models=array($models);
		foreach($models as $model)
		{
			$modelName=CHtml::modelName($model);
			if($loadInput && isset($_POST[$modelName]))
				$model->attributes=$_POST[$modelName];
			$model->validate($attributes);
			foreach($model->getErrors() as $attribute=>$errors)
				$result[$model->getIdAttribute($attribute)]=$errors;
		}
		return function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);
	}
} ?>