<?php
/**
* Изменяемая модель для валидации созданных форм
*
**/
class CallbackModel extends CFormModel{

	private $rules = [['name, phone, phoneMasked, email, text, service, qaptcha, verifyCode','safe']];

	//Attributes
	public $name;
	public $email;
	public $phone;
	public $phoneMasked;
	public $text;
	public $service;
	public $qaptcha;
	public $verifyCode;

	/*your var@attributes*/


	public function rules(){	
		return $this->rules;
	}

	public function attributeLabels(){
		return [
			'name'=>'Ваше имя',
			'email'=>'Ваш E-mail',
			'phone'=>'Ваш телефон',
			'phoneMasked'=>'Ваш телефон',
			'text'=>'Комментарий к заявке',
			'service'=>'Выберите услугу',
			'verifyCode'=>'Проверочный код',
			'qaptcha' => 'Проведите ползунок вправо для отправки формы'
		];
	}

	public function qaptchaVerify($attribute){
		$qaptcha = $_COOKIE['qaptcha_key'];
		if($attribute != $qaptcha){
			$this->addError('qaptcha','Если Вы хотите отправить заявку проведите штучку дрючку');
		}
	}

	public function setRules($template){
		preg_replace_callback("/{(\w+)}/",array($this,'addRule'),$template);
	}

	protected function addRule($matches){
		$method='addRule'.$matches[1];
		if(method_exists($this,$method)){
			foreach ($this->$method() as $rule) {
				array_unshift($this->rules,$rule);
			}
		}
	}

	public function addRuleName(){
		return [
				['name', 'required']
			];
	}

	public function addRulePhone(){
		return [
				['phone','required'],
				['phone','numerical']
			];
	}

	public function addRulePhoneMasked(){
		return $_COOKIE['js']?
			[
				['phoneMasked', 'match', 'pattern'=>'/^8\(\d{3}\)\d{3}\-\d{2}\-\d{2}$/', 'message'=>'Неверно набран номер'],
				['phoneMasked', 'required'],
			]: 
			[
				['phone','required'],
				['phone','numerical']
			];
	}

	public function addRuleEmail(){
		return [
				['email','email','message'=>'Неверный формат E-mail'],
			];
	}

	public function addRuleText(){
		return [
				['text','safe'],
			];
	}

	public function addRuleService(){
		return [
				['service','required'],
			];
	}

	public function addRuleCaptcha(){
		return [
				['verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()],
			];
	}

	public function renderQaptcha(){
		return [
				['qaptcha','qaptchaVerify'],
			];
	}

	/*Добавляйте правила валидации для своих templatов ниже
		public function renderTemplName(){
			return [
					rules for templ
				];
		}
	*/

}

?>