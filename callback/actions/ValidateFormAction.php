<?php
Yii::import('application.modules.callback.components.Processing');

	class ValidateFormAction extends CAction
	{
		public function run(){
			Processing::validate();
		}
	}
?>