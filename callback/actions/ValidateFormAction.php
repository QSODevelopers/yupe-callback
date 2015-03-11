<?php
Yii::import('application.modules.callback.models.CallbackModel');

	class ValidateFormAction extends CAction
	{
		public function run(){
			
			$model = new CallbackModel;
			$model->setRules($_POST['template']);

			if(isset($_POST['ajax'])){
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			if(isset($_POST['CallbackModel'])){
				$model->attributes=$_POST['CallbackModel'];
				if($model->validate()){
					$result = $this->controller->mail($model,$_POST['mail']);
					if(Yii::app()->request->isAjaxRequest){
						$data["result"] = $result;
						echo json_encode($data);
						Yii::app()->end();
					}
					$message = json_decode($_POST['messages']);
					$model->unsetAttributes();
					if($result)
						Yii::app()->user->setFlash($message['id'],$message['success']);
					else
						Yii::app()->user->setFlash($message['id'],$message['error']);
				}
			}
		}
	}
?>