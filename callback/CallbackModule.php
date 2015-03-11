<?php

/**
 * CallbackModule основной класс модуля callback
 *
 * @author WebGears team
 * @link http://none.shit
 * @copyright 2015-2013 BlackTag && WebGears team
 * @package yupe.modules.callback.install
 * @license  BSD
 * @since 0.0.1
 *
 */

use yupe\components\WebModule;

class CallbackModule extends WebModule
{
	const VERSION = '0.0.1';
	public $assetsPath = "application.modules.callback.views.assets";

    /**
     * @var email'ы на которые будут приходить заявки с форм
     */
    public $emailsRecipients = '';

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
        return Yii::t('CallbackModule.callback', 'WebGears team');
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
	    return Yii::t('CallbackModule.callback', 'Services');
	}
	public function getVersion()
    {
        return self::VERSION;
    }
    public function getIcon()
    {
        return 'dc dc-mail-open';
    }
	public function init()
	{
		$this->setImport(array(
			'callback.models.*',
			'callback.components.*',
		));
	}

    public function getAdminPageLink()
    {
        return '/callback/callbackBackend/index';
    }

    public function getAddress($id){
        return 'Оповещение <test@test.ru>';
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
