<?php
//Переменная накапливающая в себе скрипт перезагрузки формы 
//коль установлен сброс формы после отправки
$resetJs = 'setTimeout(function(){';
$resetJs .= $this->formOptions['resetOptions']['resetForm']? 
					'$("#'.$this->formOptions['id'].'").trigger("reset");
					 $("button",form).removeAttr("disabled");':'';
$resetJs .= $this->formOptions['resetOptions']['resetCaptcha']? 
					'$(".captcha>a,.captcha>button",form).click();':'';					 
$resetJs .= $this->formOptions['resetOptions']['closeModal']? 
					'$("#close-modal").click();':'';
$resetJs .= $this->formOptions['resetOptions']['clearMessage']? 
					'var $success = $("#'.$this->templateOptions['message']['id'].'",form);
					 $success.removeClass("alert-success alert-danger show").html("");':'';
$resetJs .='},'.$this->formOptions['resetOptions']['timeout'].');';


						
$ajaxSuck = $this->formOptions['ajax']?'+"&formValid=1"':'';						
						
//Переменная накапливающая скрипт действий после валидации
$afterValidateJs = 'js:function(form,data,hasError){
						if(!hasError)
						{
							$.ajax(
							{
								"type"		: 	"POST",
								//"dataType"	: 	"json",
								"url"		: 	"'.$this->formOptions['action'].'",
								"data"		: 	form.serialize()'.$ajaxSuck.',
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
															$success.addClass("alert-danger show");
															$success.html("'.$this->errorMessage.'");
													}
												},
								"complete"  :	function(){
													'.$resetJs.'
												},
							});
						}
					}';

echo $this->formOptions['prevText'];
//Если установлена переменная "отображать форму после обработки" или нету сообщения после обработки
if($this->formOptions['showFormAfterSend'] || !Yii::app()->user->hasFlash($this->formOptions['id'])){
//создаем и рендерим форму
	$form = $this->beginWidget('application.modules.callback.components.UtActiveForm',[
		'id'						=>  $this->formOptions['id'],
		'type'						=>  $this->formOptions['type'],
		'enableAjaxValidation'		=>  $this->formOptions['ajax'],
		'enableClientValidation'	=>  $this->formOptions['enableClientValidation'],
		'htmlOptions'				=>  $this->formOptions['htmlOptions'],
		'clientOptions'				=>  $this->formOptions['ajax'] || $this->formOptions['enableClientValidation'] 
										? 
											[
												'validateOnSubmit'	=>	$this->formOptions['ajax'] || $this->formOptions['enableClientValidation'] ,
												'afterValidate'		=>	$afterValidateJs
											]
										:
											'',
	]);
		echo $this->formOptions['title'] ? '<h3>'.$this->formOptions['title'].'</h3>': '';
		if($this->formOptions['ajax'] || $this->formOptions['enableClientValidation'])
			echo "<script>$('#".$this->formOptions['id']."').attr('action','".$this->formOptions['action']."');</script>";
		echo $this->formOptions['prevBodyText'];
		
		//иполнение кода тела формы
		eval($body);
?>
		
<?php
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

	$this->endWidget();
}else{
//иначе просто выводим сообщение
	echo Yii::app()->user->hasFlash($this->formOptions["id"]) ? "<div class='alert alert-success show'>".Yii::app()->user->getFlash($this->formOptions["id"])."</div>":"";
}
echo $this->formOptions['afterText'];

?>