<?php
namespace application\modules\callback\components\validators;

use CCaptchaValidator;
use Yii; 
use CJSON;

class UnCaptchaValidator extends CCaptchaValidator
{
	public function clientValidateAttribute($object,$attribute)
	{
		$captcha=$this->getCaptchaAction();
		$message=$this->message!==null ? $this->message : Yii::t('yii','The verification code is incorrect.');
		$message=strtr($message, array(
			'{attribute}'=>$object->getAttributeLabel($attribute),
		));
		$code=$captcha->getVerifyCode(false);
		$hash=$captcha->generateValidationHash($this->caseSensitive ? $code : strtolower($code));
		$js="

		var hash = jQuery('#'+attribute.inputID).parents('form').find('img').attr('data-hash');

		if (hash == null)
			hash = $hash;
		else{
			hash = hash.split(',');
			hash = hash[".($this->caseSensitive ? 0 : 1)."];
		}
		for(var i=value.length-1, h=0; i >= 0; --i) h+=value.".($this->caseSensitive ? '' : 'toLowerCase().')."charCodeAt(i);
		if(h != hash) {
			messages.push(".CJSON::encode($message).");
		}
		";

		if($this->allowEmpty)
		{
		$js="
		if(jQuery.trim(value)!='') {
			$js
		}
		";
		}

		return $js;
	}
}

?>