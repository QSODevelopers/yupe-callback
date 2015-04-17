<?php

/**
 * CallbackModule основной класс модуля callback
 *
 * @author UnnamedTeam
 * @link http://none.shit
 * @copyright 2015 UnnamedTeam & Project Yupe!Flavoring 
 * @package yupe.modules.callback.install
 * @license  BSD
 * @since 0.0.1
 *
 */

use yupe\components\WebModule;

class CallbackModule extends WebModule
{
	const VERSION = '0.7.5';
	public $assetsPath = "application.modules.callback.views.assets";

    /**
     * @var email'ы на которые будут приходить заявки с форм
     */
    public $emailRecipient = '{"admin": "Михаил Cергеевич <ap@gmail.com>"}';
    /**
     * @var email'ы с которых будут приходить заявки
     */
    public $emailSender = '{"unnamed": "UnnamedTeam <unnamed.team211015@gmail.com>"}';
    

	// название модуля
    public function getName()
    {
        return Yii::t('CallbackModule.callback', 'Callback');
    }
 
    // описание модуля
    public function getDescription()
    {
        return Yii::t('CallbackModule.callback', 'Module for constructing feedback forms');
    }
 
    // автор модуля (Ваше Имя, название студии и т.п.)
    public function getAuthor()
    {
        return Yii::t('CallbackModule.callback', 'UnnamedTeam');
    }
 
    // контактный email автора
    public function getAuthorEmail()
    {
        return Yii::t('CallbackModule.callback', 'konstantin24121@gmail.com');
    }
 
    // сайт автора или страничка модуля
    public function getUrl()
    {
        return Yii::t('CallbackModule.callback', 'http://none.shit');
    }
    // категория модуля
	public function getCategory()
	{
	    return Yii::t('CallbackModule.callback', 'Сервисы');
	}
	public function getVersion()
    {
        return self::VERSION;
    }
    public function getIcon()
    {
        return 'fa fa-beer';
    }
	public function init()
	{

		$this->setImport(array(
			'callback.models.*',
			'callback.components.*',
		));
        parent::init();
	}

    public function getIsInstallDefault()
    {
        return true;
    }

    public function getAdminPageLink()
    {
        return '/callback/callbackBackend/index';
    }
    
    /**
     * Функция возвращает строку email который будет использоваться для отправки оповещения
     *
     * @param string $email email
     * 
     * @return string
     */
    public function getEmailSender($email){
        $email = substr($email, 1);
        $adresses = CJSON::decode($this->emailSender);
        if($recipient == 'fromUser'){
            return 'Пользователь с сайта <'.Yii::app()->getRequest()->getPost('CallbackForm')['email'].'>';
        }elseif(array_key_exists($email,$adresses))
            return $adresses[$email];
        elseif(preg_match('/,/',$email))
            throw new CException(Yii::t('CallbackModule.callback', 'Невозможно отправить письмо от нескольких адресатов {email}. Красная или белая?',['email'=>$email]));
        else
            throw new CException(Yii::t('CallbackModule.callback', 'Email {email} не найден в настройках. Добавить email для отправки писем можно в настройках виджета, неандерталец.',['email'=>$email]));
    }

    /**
     * Функция возвращает строку email адресов для получения оповещений
     *
     * @param string $emails список email
     * 
     * @return string
     */
    public function getEmailRecipient($emails){
        $adresses = CJSON::decode($this->emailRecipient);
        $emails = explode(',',substr($emails,1));
        $recipients = '';
        foreach ($emails as $recipient) {
            if($recipient == 'toUser'){
                $recipients[] = 'Пользователь с сайта <'.Yii::app()->getRequest()->getPost('CallbackForm')['email'].'>';
            }elseif(array_key_exists($recipient,$adresses))
                $recipients[] = $adresses[$recipient];
            else
                throw new CException(Yii::t('CallbackModule.callback', 'Email {email} не найден в настройках. Добавить email для отправки писем можно в настройках виджета, неандерталец.',['email'=>$recipient]));
        }
        return implode(',',$recipients);
    }
	
    public function getEditableParams()
    {
        return [
            'emailSender',
            'emailRecipient'
        ];
    }

    public function getParamsLabels()
    {
        return [
            'adminMenuOrder'    => Yii::t('CallbackModule.callback', 'Порядок следования в меню'),
            'emailSender'       => Yii::t('CallbackModule.callback', 'Email\'ы для отправки уведомлений'),
            'emailRecipient'    => Yii::t('CallbackModule.callback', 'Email\'ы для получения уведомлений'),
        ];
    }

    public function getEditableParamsGroups()
    {
        return [
            'main'      => [
                'label' => Yii::t('CallbackModule.callback', 'Главные настройки'),
                'items' => [
                    'adminMenuOrder',
                ]
            ],
            'email'      => [
                'label' => Yii::t('CallbackModule.callback', 'Настройки отправки'),
                'items' => [
                    'emailSender',
                    'emailRecipient'
                ]
            ],
        ];
    }

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
			return false;
	}
}
