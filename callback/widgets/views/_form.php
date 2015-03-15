<?php
//Переменная накапливающая в себе скрипт перезагрузки формы 
//коль установлен сброс формы после отправки
$resetJs = $this->formOptions['resetOptions']['enableReset'] 
					? 
					'setTimeout(function(){
						var $success = $("#'.$this->templateOptions['message']['id'].'",form);

						$("#'.$this->formOptions['id'].'").trigger("reset");
						$success.removeClass("alert-success alert-error show").html("");
						$("#close-modal").click();
						$("button",form).removeAttr("disabled");
					},'.$this->formOptions['resetOptions']['timeout'].');'
					:
					'';
//Переменная накапливающая скрипт действий после валидации
$afterValidateJs = 'js:function(form,data,hasError){
						if(!hasError)
						{
							$.ajax(
							{
								"type"		: 	"POST",
								"url"		: 	"'.$this->formOptions['action'].'",
								"data"		: 	form.serialize(),
								"beforeSend": 	function(){
													$("button",form).attr("disabled","disabled");
												},
								"success"	:	function(data){
													data = $.parseJSON(data);
													var $success = $("#'.$this->templateOptions['message']['id'].'",form);
													if(data.result){
															$success.addClass("alert-success show");
															$success.html("'.$this->successMessage.'");
													}else{
															$success.addClass("alert-error show");
															$success.html("'.$this->errorMessage.'");
													}
												},
								"complete"  :	function(){
													'.$resetJs.'
												},
							});
						}
					}';


//Если установлена переменная "отображать форму после обработки" или нету сообщения после обработки
if($this->formOptions['showFormAfterSend'] || !Yii::app()->user->hasFlash($this->formOptions['id'])){
//создаем и рендерим форму
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',[
		'id'						=>  $this->formOptions['id'],
		'type'						=>  $this->formOptions['type'],
		'ajax'						=>  $this->formOptions['ajax'],
		'enableClientValidation'	=>  $this->formOptions['enableClientValidation'],
		'htmlOptions'				=>  $this->formOptions['htmlOptions'],
		'clientOptions'				=>  $this->formOptions['ajax'] || $this->formOptions['enableClientValidation'] 
										? 
											[
												'validateOnSubmit'	=>	$this->formOptions['ajax'],
												'afterValidate'		=>	$afterValidateJs
											]
										:
											'',
	]);
		echo $this->formOptions['title'] ? '<h3>'.$this->formOptions['title'].'</h3>': '';
		if($this->formOptions['ajax'])
			echo "<script>$('#".$this->formOptions['id']."').attr('action','".$this->formOptions['action']."');</script>";
		echo $this->formOptions['prevBodyText'];
		
		//иполнение кода тела формы
		eval($body);

		echo CHtml::hiddenField('formId',$this->formOptions['id']);
		echo CHtml::hiddenField('mail',json_encode([$this->mailOptions]));
		echo CHtml::hiddenField('template',$this->template);
		echo CHtml::hiddenField('messages',json_encode([
					'id'		=>	$this->formOptions['id'],
					'success'	=>	$this->successMessage,
					'error'		=>	$this->successMessage
				])
		);
		
		echo $this->formOptions['afterBodyText'];

	$this->endWidget();
}else{
//иначе просто выводим сообщение
	echo Yii::app()->user->hasFlash($this->formOptions["id"]) ? "<div class='alert alert-success show'>".Yii::app()->user->getFlash($this->formOptions["id"])."</div>":"";
}
echo $this->formOptions['afterText'];

?>