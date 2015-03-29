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
            ],
            'captchacenterForm'         => [
                'class'     => 'yupe\components\actions\YCaptchaAction',
                'backColor' => 0xFFFFFF,
                'testLimit' => 1,
            ],
			'page'=>array(
				'class'=>'CViewAction',
			),
			'validate'=>'application.modules.callback.actions.ValidateFormAction',
		);
	}

	//Действие для рендера формы на странице, или рендера формы передаваймой в модальное окно
	public function actionIndex($code = null){
		$this->render('index');
	}
}