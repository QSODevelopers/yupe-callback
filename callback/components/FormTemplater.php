<?php
/**
* Компонент для сборки кода формы
*
**/

class FormTemplater {

	/**
     * Функция возвращает массив доступных temlatов
     *
     * @return array
     */
	public static function getTemplates(){
		return [
			'name',
			'phone',
			'email',
			'text',
		];
	}
	/**
     * Функция возвращает массив temlatов для которых применяются дефолтные настройки массива templateOptions
     *
     * @return array
     */
	public static function getTempDefaultForSetting(){
		return [
			'name',
			'phone',
			'phoneMasked',
			'email',
			'service',
			'text',
		];
	}

	//Поле для ввода имени
	public static function renderName(){
		return 'echo $form->textFieldGroup($model,"name",$this->templateOptions["name"]);';
	}

	//Поле для ввода телефона
	public static function renderPhone(){
		return 'echo $form->telFieldGroup($model,"phone",$this->templateOptions["phone"]);';
	}

	//Заготовленое поле для рендера формы с маской телефона, кстати если JS отсутствует то срендерится обычное поле
	public static function renderPhoneMasked(){
		return 'echo "<div class=\'hide-non-js\' style=\'display:none\'>".$form->maskedTextFieldGroup($model,"phoneMasked",$this->templateOptions["phoneMasked"])."</div>";
				echo "
					<script>$(\'.hide-non-js\').fadeIn();</script>
					<noscript>
   						<div class=\'row-fluid\'>".$form->textFieldGroup($model,"phone")."</div>
  					</noscript>";';
	}
	//Поле для ввода email
	public static function renderEmail(){
		return 'echo $form->emailFieldGroup($model,"email",$this->templateOptions["email"]);';
	}
	//Поле для ввода коментария
	public static function renderText(){
		return 'echo $form->textAreaGroup($model,"text",$this->templateOptions["text"]);';
	}
	//Поле для выбора опции из списка
	public static function renderService(){
		return 'echo $form->dropDownListGroup($model,"service",$this->templateOptions["service"]);';
	}
	//Поле для ввода капчей-хачей
	public static function renderVerifyCode(){
		return 'if (Yii::app()->user->getState("badLoginCount", 0) >= $this->templateOptions["verifyCode"]["badLoginCount"] && CCaptcha::checkRequirements("gd")) { 
				    echo $form->captchaGroup(
				                $model,
				                "verifyCode",
				                $this->templateOptions["verifyCode"]
				            );
		 		}';
	}
	//TODO проверить данный темпл
	// public static function renderQaptcha(){
 	// 		return 'echo "<div class=\'row-fliud\' style=\'position:relative\'><div class=\'QapTcha\'></div></div>";
	// 	echo $form->hiddenField($model,"qaptcha");';
	// }

	
	public static function renderButton(){
		return '$this->widget("bootstrap.widgets.TbButton", $this->templateOptions["button"]);';
	}
	public static function renderErrors(){
		return 'echo $form->errorSummary($model,null,null,$this->templateOptions["errors"]);';
	}
	public static function renderMessage(){
		return 'echo Chtml::tag("div",$this->templateOptions["message"])."</div>";
				echo Yii::app()->user->hasFlash($this->formOptions["id"]) ? "<div class=\'alert alert-success\'>".Yii::app()->user->getFlash($this->formOptions["id"])."</div>":"";';
	}
	

	//Дописывать свои templatы ниже
	//
	// public static function renderTemplName(){
	// 	return {php for render};
	// }

}
?>