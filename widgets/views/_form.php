<?php
echo $this->formOptions['prevText'];
//Если установлена переменная "отображать форму после обработки" или нету сообщения после обработки
if($this->formOptions['showFormAfterSend'] || !Yii::app()->user->hasFlash($this->formOptions['id'])){
//создаем и рендерим форму
	$form = $this->beginWidget('application.modules.callback.widgets.UtActiveForm',[
		'id'						=>  $this->formOptions['id'],
		'type'						=>  $this->formOptions['type'],
		'enableAjaxValidation'		=>  $this->formOptions['ajax'],
		'enableClientValidation'	=>  $this->formOptions['clientValidation'],
		'htmlOptions'				=>  $this->formOptions['htmlOptions'],
		'clientOptions'				=>  $this->formOptions['ajax'] || $this->formOptions['clientValidation'] 
										? 
											[
												'validateOnSubmit'	=>	$this->formOptions['ajax'] || $this->formOptions['clientValidation'] ,
												'afterValidate'		=>	$afterValidateJs
											]
										:
											'',
	]);
		echo $this->formOptions['title'] ? '<div class="title">'.$this->formOptions['title'].'</div>': '';

		if($this->formOptions['ajax'] || $this->formOptions['clientValidation'])
			Yii::app()->clientScript->registerScript('callback-zad',"
				$('#".$this->formOptions['id']."').attr('action','".$this->formOptions['action']."');
			");
			
		
		echo Chtml::tag('div',['class'=>'col-xs-12 form-body']);
		echo $this->formOptions['prevBodyText'];
		
		//иполнение кода тела формы
		eval($body);
		
		echo CHtml::hiddenField('widgetId',$this->id);
		echo CHtml::hiddenField('mail',json_encode([$this->mailOptions]));
		echo CHtml::hiddenField('template',$this->template);
		echo CHtml::hiddenField('messages',json_encode([
					'id'		=>	$this->formOptions['id'],
					'success'	=>	$this->successMessage,
					'error'		=>	$this->successMessage
				])
		);
		
		echo $this->formOptions['afterBodyText'];
		echo '</div>';
	$this->endWidget();
}else{
//иначе просто выводим сообщение
	echo Yii::app()->user->hasFlash($this->formOptions["id"]) ? "<div class='alert alert-success show'>".Yii::app()->user->getFlash($this->formOptions["id"])."</div>":"";
}
echo $this->formOptions['afterText'];

?>