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

	public function actionIndex(){
		echo 'I see you';
	}

	public function mail($model,$mail)
	    {
	    	$params = json_decode($mail)[0];
       		$txt_message = $this->renderPartial('mail/'.$params->view, array('model'=>$model), true, false);
       		$headers = 'From:'.$params->from."\r\n".
						    'Content-type: text/html;'.
						    'charset=utf-8'."\r\n".
						    'X-Mailer: PHP/' . phpversion();
       		$result = mail(
                	$params->to,
                	$params->title,
                	$txt_message,
                	$headers
                );
       		return $result;
		}
}