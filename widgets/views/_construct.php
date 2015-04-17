<?php
	switch ($this->type) {
		case 'modal':
			$this->widget('bootstrap.widgets.TbButton',$this->buttonOptions);
			$this->render('_modal',array('model'=>$model,'body'=>$body, 'afterValidateJs'=>$afterValidateJs)); 
			break;

		case 'block':
			$this->render('_form',array('model'=>$model,'body'=>$body, 'afterValidateJs'=>$afterValidateJs));
			break;

		case 'buttonModal':
			$this->widget('bootstrap.widgets.TbButton',$this->buttonOptions);
			break;	
		
		case 'toPage':
			$this->buttonOptions['htmlOptions']	=	[
															'data-toggle'	=>	'',
													        'data-target'	=>	'',
														];
			$this->widget('bootstrap.widgets.TbButton',$this->buttonOptions);
			break;
		/*Write your type here
		case '@type':
			@php;
			break;
		*/

		default:
			echo '<div class="alert alert-error">
					<h5>'.Yii::t('CallbackModule.callback','Кто к нам с чем и зачем, тот от того и того.').'</h5>'.
					Yii::t('CallbackModule.callback', 'Сам ты {type}. Данный тип виджета является не коректным. Загляни в исходный код, или просмотри коментарии.',['type'=>$this->type]).'
				</div>';
			break;
	}

	//Спорный скрипт, для того чтобы не использвались правила для maskedField если нету JS
	Yii::app()->clientScript->registerScript('enabledJs','
		var date = new Date( new Date().getTime() + 60*1000);
		document.cookie="js=true; path=/; expires="+date.toUTCString();
	')
?>