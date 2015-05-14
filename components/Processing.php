<?php
/**
* Компонент с функциями
*
**/
Yii::import('application.modules.callback.widgets.UtActiveForm');
class Processing extends CComponent {
	/**
     * Функция валидации пришедшей формы
     *
     * @param СModel||CActiveForm $model модель для провеки 
     *
     * @return void
     */
	public static function validate($model = null){

		if(is_null($model)){
			$model = new CallbackForm;
			$model->setUnicName(Yii::app()->getRequest()->getPost('widgetId'));
			$model->setRules(Yii::app()->getRequest()->getPost('template'));
		}
		// For validate on server. But why? Don't use this. Use clientValidation
		if(Yii::app()->getRequest()->getPost('ajax') != null){
			echo UtActiveForm::validate($model);
			Yii::app()->end();
		}

		if(Yii::app()->getRequest()->getPost('CallbackForm')!= null && Yii::app()->getRequest()->csrfToken == Yii::app()->getRequest()->getPost('YUPE_TOKEN')){
			$model->attributes=Yii::app()->getRequest()->getPost('CallbackForm');
			if(Yii::app()->getRequest()->getPost('formValid') != null ){
				$hash = self::getHash(); 
			}

			$valid = Yii::app()->getRequest()->getPost('formValid') != null && Yii::app()->getRequest()->getPost('formValid') == $hash ? true : $model->validate();

			if($valid){
				$result = self::mail($model, Yii::app()->getRequest()->getPost('mail'));
				if(Yii::app()->request->isAjaxRequest){
					$data['result'] = $result;
					echo CJSON::encode($data);
					Yii::app()->end();
				}
				$message = CJSON::decode(Yii::app()->getRequest()->getPost('messages'));
				$model->unsetAttributes();
				if($result)
					Yii::app()->user->setFlash($message['id'],$message['success']);
				else
					Yii::app()->user->setFlash($message['id'],$message['error']);
			}
		}
	}

	/**
     * Функция получения подписи формы
     *
     * @return string
     */

	public static function getHash(){
		$hashString = Yii::app()->getRequest()->getPost(Yii::app()->getRequest()->csrfTokenName);
		foreach (Yii::app()->getRequest()->getPost('CallbackForm') as $key => $value) {
			$hashString.= $value;
		}
		$hashString.=Yii::app()->getRequest()->getPost('widgetId');
		for($i = strlen($hashString)-1, $hash = 0; $i >= 0; --$i) $hash+= ord(substr($hashString,$i,1));
		return $hash;
	}

	/**
     * Функция отправки письма
     *
     * @param СModel||CActiveForm $model модель для провеки
     * @param json $mail массив параметров для отправки письма
     *
     * @return boolean
     */
	public static function mail($model, $mail){
    	$params = CJSON::decode($mail)[0];
    	
    	$controller = Yii::app()->createController('callback/callback/index');
    	if(preg_match('/^:/',$params['from']))
    		$from = Yii::app()->getModule('callback')->getEmailSender($params['from']);
    	else
    		$from = $params['from'];
    	if(preg_match('/^:/',$params['to']))
    		$to = Yii::app()->getModule('callback')->getEmailRecipient($params['to']);
    	else
    		$to = $params['to'];

   		$txt_message = $controller[0]->renderPartial('mail/'.$params['view'], array('model'=>$model), true, false);
   		$headers = 'From:'.$from."\r\n".
					    'Content-type: text/html;'.
					    'charset=utf-8'."\r\n".
					    'X-Mailer: PHP/' . phpversion();
   		$result = mail(
            	$to,
            	$params['title'],
            	$txt_message,
            	$headers
            );
   		return $result;
	}

}
?>
