<?php 
//Старый экшн использовавшийс для обработки запросов ajax
	class RequestAction extends CAction
	{
	 //    public function run()
	 //    {
	 //    	Yii::import('wii.Request.models.*');
	 //        $model = new RequestModel(); 
		// 	$model->setRules($_POST['template']);

		// 	if(isset($_POST['ajax'])){
		// 		echo CActiveForm::validate($model);
		// 		Yii::app()->end();
		// 	}

		// 	if(isset($_POST['RequestModel']) && Yii::app()->request->isAjaxRequest){
		// 		$request = $this->controller->widget('wii.Request.Request');		
		//     }else{
		// 		return $this->controller->render('request');
		// 	}
		// }
	}
?>