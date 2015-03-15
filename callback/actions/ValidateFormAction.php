<?php
/**
* Экшн для валидации формы пришедшей через ajax запрос 
*
*
**/
Yii::import('application.modules.callback.components.Processing');

	class ValidateFormAction extends CAction
	{
		public function run(){
			Processing::validate();
		}
	}
?>