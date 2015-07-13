<?php
/**
* Изменяемая модель для валидации созданных форм
*
**/
class CallbackForm extends CFormModel{

	private $rules = [['name, phone, phoneMasked, email, text, service, verifyCode, hiddenInfo, calldate','safe']];
	protected $unicName = 'Callback';

	//Attributes
	public $name;
	public $email;
	public $phone;
	public $hiddenInfo;
	public $phoneMasked;
	public $calldate;
	public $text;
	public $service;
	public $verifyCode;

	/*your var@attributes*/


	public function rules(){	
		return $this->rules;
	}

	public function setUnicName($name){
		if(!is_null($name))
			$this->unicName = $name;
		else
			throw new CException(Yii::t('CallbackModule.callback', 'Unic model name can\' be empty'));
	}

	public function getUnicName(){
		return $this->unicName;
	}

	public function getIdAttribute($attribute){
		return $this->getUnicName().'_'.$attribute;
	}

	public function attributeLabels(){
		return [
			'name'			=>	'Ваше имя',
			'email'			=>	'Ваш E-mail',
			'phone'			=>	'Ваш телефон',
			'phoneMasked'	=>	'Ваш телефон',
			'calldate'		=>	'Удобное время для звонка',
			'text'			=>	'Ваш вопрос',
			'service'		=>	'Выберите услугу',
			'verifyCode'	=>	'Проверочный код',
		];
	}

	/**
	 * Разделение templates и вызов функций добавления правил
	 * @param string $template - набор шаблонов
	 *
	 * @return void
	 */
	public function setRules($template){
		preg_replace_callback("/{(\w+)}/",array($this,'addRule'),$template);
	}

	/**
	 * Вызов методов добавления правил для каждого шаблона
	 * @param array $matches - шаблон, наденое совпадение в наборе
	 *
	 * @return void
	 */
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
				['email','required'],
				['email','email','message'=>'Неверный формат E-mail'],
			];
	}

	public function addRuleText(){
		return [
			];
	}

	public function addRuleService(){
		return [
				['service','required'],
			];
	}

	public function addRuleVerifyCode(){
		return [
	            [
	                'verifyCode',
	                'application\modules\callback\components\validators\UtCaptchaValidator',
	                'captchaAction'	=> 'callback/callback/captcha'.$this->getUnicName(),
	                'allowEmpty' => !CCaptcha::checkRequirements(),
	            ],
	            ['verifyCode', 'emptyOnInvalid']
			];

	}

	public function addHiddenInfo(){
		return [
			];
	}

	public function addCalldate(){
		return [
			];
	}

	/*Добавляйте правила валидации для своих templatов ниже
		public function addRuleTemplName(){
			return [
					rules for templ
				];
		}
	*/

	/**
     * Обнуляем введённое значение капчи, если оно введено неверно:
     *
     * @param string $attribute - имя атрибута
     * @param mixed $params - параметры
     *
     * @return void
     **/
    public function emptyOnInvalid($attribute, $params)
    {
        if ($this->hasErrors()) {
            $this->verifyCode = null;
        }
    }

}

?>