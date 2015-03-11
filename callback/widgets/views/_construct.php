<?php
	switch ($this->type) {
		case 'modal':
			$this->widget('bootstrap.widgets.TbButton',$this->buttonOptions);
			$this->render('_modal',array('model'=>$model,'body'=>$body)); 
			break;

		case 'block':
			$this->render('_form',array('model'=>$model,'body'=>$body));
			break;

		case 'button':
			$this->widget('bootstrap.widgets.TbButton',$this->buttonOptions);
			break;	
			
		/*Write your type here
		case '@type':
			@php;
			break;
		*/

		default:
			echo '<div class="alert alert-error">
					<h5>Кто к нам с чем и зачем, тот от того и того.</h5>
					Сам ты '.$this->type.'. Данный тип обратной заявки является не коректным. Загляни в исходный код, или просмотри коментарии.
				</div>';
			break;
	}
	Yii::app()->clientScript->registerScript('enabledJs','
		var date = new Date( new Date().getTime() + 60*1000);
		document.cookie="js=true; path=/; expires="+date.toUTCString();
	')
?>