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
			'captcharightSend'         => [
                'class'     => 'yupe\components\actions\YCaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor'	=> 0x481704,
                'testLimit' => 1,
                //'minLength' => Yii::app()->getModule('callback')->minCaptchaLength,
            ],
            'captchacenterForm'         => [
                'class'     => 'yupe\components\actions\YCaptchaAction',
                'backColor' => 0xFFFFFF,
                'testLimit' => 1,
                //'minLength' => Yii::app()->getModule('callback')->minCaptchaLength,
            ],
			'page'=>array(
				'class'=>'CViewAction',
			),
			'validate'=>'application.modules.callback.actions.ValidateFormAction',
		);
	}

	//TODO сделать экшон для кнопок-ссылок
	public function actionIndex(){
		echo 'I see you';
	}
}