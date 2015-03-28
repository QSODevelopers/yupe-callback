<?php
/**
* Компонент с функциями
*
**/
Yii::import('application.modules.callback.components.UtActiveForm');
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
			$model = new CallbackModel;
			$model->setUnicName($_POST['widgetId']);
			if(empty($_POST['CallbackModel']['verifyCode']))
				$_POST['template'] = preg_replace('/\{verifyCode\}/', '', $_POST['template']);
			$model->setRules($_POST['template']);
		}

		// For validate on server. But why? Don't use this. Use clientValidation
		if(isset($_POST['ajax'])){
			echo UtActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['CallbackModel'])){
			
			if($_POST['formValid']==='1'){
				$valid  =  true;
			}else{
				$model->attributes=$_POST['CallbackModel'];
				$valid  = $model->validate();
			}
			
			if($valid){
				$result = self::mail($model,$_POST['mail']);
				if(Yii::app()->request->isAjaxRequest){
					$data["result"] = $result;
					echo CJSON::encode($data);
					Yii::app()->end();
				}
				$message = CJSON::decode($_POST['messages']);
				$model->unsetAttributes();
				if($result)
					Yii::app()->user->setFlash($message['id'],$message['success']);
				else
					Yii::app()->user->setFlash($message['id'],$message['error']);
			}else{
				foreach($model->getErrors() as $attribute=>$errors)
				$result[$model->getIdAttribute($attribute)]=$errors;
				echo function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);
				Yii::app()->end();
			}
		}
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

   		$txt_message = $controller[0]->renderPartial('mail/'.$params['view'], array('model'=>$model), true, false);
   		$headers = 'From:'.$params['from']."\r\n".
					    'Content-type: text/html;'.
					    'charset=utf-8'."\r\n".
					    'X-Mailer: PHP/' . phpversion();
   		$result = mail(
            	$params['to'],
            	$params['title'],
            	$txt_message,
            	$headers
            );
   		return $result;
	}	
}
?>
