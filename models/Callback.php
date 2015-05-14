<?php

/**
 * This is the model class for table "{{callback}}".
 *
 * The followings are the available columns in table '{{callback}}':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property string $template
 * @property string $button_options
 * @property string $modal_options
 * @property string $form_options
 * @property string $template_options
 * @property string $mail_options
 * @property string $title
 * @property string $success_message
 * @property string $error_message
 */
class Callback extends yupe\models\YModel
{
	const MODAL = 'modal';
    const FORM = 'block';
    const BUTTON = 'buttonModal';
    const PAGE = 'toPage';

    const TURN_OFF = 0;
    const TURN_ON = 1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{callback}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			['name, code, template, type, button_options, modal_options, form_options, template_options, mail_options, title, success_message, error_message, description', 'filter', 'filter' => 'trim'],
			['name, code, button_options, modal_options, form_options, template_options, mail_options', 'filter', 'filter' => [new CHtmlPurifier(), 'purify']],
			['name, code, type', 'required'],
			['name, code', 'length', 'max'=>50],
			['type', 'length', 'max'=>25],
			['status', 'numerical', 'integerOnly' => true],
			['type', 'in', 'range' => array_keys($this->types)],
			['template', 'length', 'max'=>255],
			[
                'code',
                'yupe\components\validators\YSLugValidator',
                'message' => Yii::t(
                    'CallbackModule.callback',
                    'Неверный формат поля "{attribute}" допустимы только буквы, цифры и символ подчеркивания, от 2 до 50 символов'
                )
            ],
            ['code', 'unique'],
			['id, name, code, type, description, status', 'safe', 'on'=>'search'],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [];
	}

	public function scopes()
    {
        return [
            'active' => [
                'condition' => $this->tableAlias . '.status = :status',
                'params'    => [':status' => self::TURN_ON],
            ],
        ];
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Name',
			'code' => 'Code',
			'type' => 'Type',
			'template' => 'Template',
			'button_options' => 'Button Options',
			'modal_options' => 'Modal Options',
			'form_options' => 'Form Options',
			'template_options' => 'Template Options',
			'mail_options' => 'Mail Options',
			'title' => 'Title',
			'success_message' => 'Success Message',
			'error_message' => 'Error Message',
			'status' => 'Status',
			'description' => 'Description',
		];
	}

	/**
     * @return array customized attribute descriptions (name=>description)
     */
    public function attributeDescriptions()
    {
        return [
            'id' => 'ID',
            'status' => 'Status widget',
            'type' => 'Type',
			'title' => 'Title',
			'template' => 'Template',
			'buttonOptions' => 'Button Options',
			'modalOptions' => 'Modal Options',
			'formOptions' => 'Form Options',
			'templateOptions' => 'Template Options',
			'mailOptions' => 'Mail Options',
			'successMessage' => 'Success Message',
			'errorMessage' => 'Error Message',
        ];
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('description',$this->title,true);

		return new CActiveDataProvider($this, [
			'criteria'=>$criteria,
		]);
	}

	public function beforeValidate(){
		if($this->template!=''){
			foreach ($this->template as $key => $templ) {
				$templString .= $templ;
			}

			$this->template = $templString;
		}
		
		return parent::beforeValidate();
	}

	public function afterFind(){
		foreach ($this->getAttributes() as $key => $value) {
			$value = $this->getAttribute($key);
			if(preg_match('/^[asobN]:\d+:[\{\"].*[\}\"]*;*$/m',$value)){
				if(unserialize($value))
					$this->{$key} = html_entity_decode(unserialize($value));
				else
					$this->{$key} = '';
			}
		}
		return parent::afterFind();
	}

	public function beforeSave(){

		$this->button_options   = ($this->button_options!='')  	?	serialize($this->button_options)  	: serialize('');	
		$this->modal_options    = ($this->modal_options!='')   	?	serialize($this->modal_options)   	: serialize('');	
		$this->form_options     = ($this->form_options!='')    	?	serialize($this->form_options)    	: serialize('');
		$this->template_options = ($this->template_options!='')	?	serialize($this->template_options)	: serialize('');	
		$this->mail_options     = ($this->mail_options!='')    	?	serialize($this->mail_options)    	: serialize('');	

		return parent::beforeSave();
	}

	public function templateToArray(){
		preg_match_all('/\{[\w\d]+\}/',$this->template,$templates);
		$this->template = $templates[0];
	}

	public function getTypes()
    {
        return [
            self::MODAL 	=> Yii::t('CallbackModule.callback', 'Модальное окно'),
            self::FORM   	=> Yii::t('CallbackModule.callback', 'Форма'),
            self::BUTTON    => Yii::t('CallbackModule.callback', 'Кнопка для подъема модального окна'),
            self::PAGE    	=> Yii::t('CallbackModule.callback', 'Ссылка на страницу'),
        ];
    }

    public function getTemplates()
    {
        return [
            self::MODAL 	=> Yii::t('CallbackModule.callback', 'Модальное окно'),
            self::FORM   	=> Yii::t('CallbackModule.callback', 'Форма'),
            self::BUTTON    => Yii::t('CallbackModule.callback', 'Кнопка для подъема модального окна'),
            self::PAGE    	=> Yii::t('CallbackModule.callback', 'Ссылка на страницу'),
        ];
    }

    public function getType()
    {
        $data = $this->getTypes();

        return isset($data[$this->type]) ? $data[$this->type] : Yii::t(
            'CallbackModule.callback',
            '*неизвестный тип*'
        );
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Callback the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getStatusList()
    {
        return [
            self::TURN_OFF 	=> Yii::t('CallbackModule.callback', 'Turn off'),
            self::TURN_ON   => Yii::t('CallbackModule.callback', 'Turn on'),
        ];
    }

	public static function getSettings($code){
		$settings = self::model()->findByAttributes(['code'=>$code]);

		foreach ($settings->model()->getAttributes() as $key => $value) {
			$value = $settings->getAttribute($key);
			
			//Convert key to camel case
			preg_match('/_(\w)/',$key,$w);
			$key = preg_replace('/_\w/', strtoupper($w[1]), $key);

			
			//Сheck if string is serialized
			// if(preg_match('/^\{[\'\"\n ]|\{$/',$value)){
			// 	$settings_array[$key]= CJSON::decode($value);
			// }
			// else{
			// 	if($value!='') $settings_array[$key] = $value;
			// }
			if(preg_match('/^\[[\'\"\n ]|\]$|^Yii::t/m',$value)){
				if($value) $settings_array[$key] = eval(html_entity_decode('return '.$value.';'));
			}
			else{
				if($value!='') $settings_array[$key] = $value;
			}
		}

		$settings_array['id'] = $settings_array['code'];
			unset($settings_array['code']);
			unset($settings_array['name']);
			unset($settings_array['description']);
		return $settings_array;
	}
}
