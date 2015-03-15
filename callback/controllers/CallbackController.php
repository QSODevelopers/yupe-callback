<?php

/**
 * CallbackController контроллер публичной части для обработки форм обратной связи
 *
 * @author UnnamedTeam
 * @link http://none.shit
 * @copyright 2015 UnnamedTeam & Project Yupe!Flavoring 
 * @package yupe.modules.callback.install
 * @license  BSD
 *
 **/

use yupe\widgets\YFlashMessages;

class CallbackController extends \yupe\components\controllers\FrontController
{

	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
			'request'=>'application.modules.callback.actions.RequestAction',
			'qaptcha'=>'application.modules.callback.actions.QaptchaAction',
			'validate'=>'application.modules.callback.actions.ValidateFormAction',
		);
	}

	//TODO сделать экшон для кнопок-ссылок
	public function actionIndex(){
		echo 'I see you';
	}
}