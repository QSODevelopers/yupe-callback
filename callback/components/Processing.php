<?php 
class Processing extends CComponent {

	public static function validate($model = null){
		if(is_null($model)){
			$model = new CallbackModel;
			$model->setRules($_POST['template']);
		}

		// For validate on server. But why? Don't use this. Use clientValidation
		if(isset($_POST['ajax'])){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['CallbackModel'])){
			$model->attributes=$_POST['CallbackModel'];
			if($model->validate()){
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
			}
		}
	}

	public static function mail($model, $mail){
    	$params = CJSON::decode($mail)[0];

    	$controller = Yii::app()->createController('callback/callback/index');

   		$txt_message = $controller[0]->renderPartial('mail/'.$params['view'], array('model'=>$model), true, false);
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
?>
