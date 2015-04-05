<?php
/**Переменная накапливающая в себе скрипт перезагрузки формы 
	коль установлен сброс формы после отправки**/
$resetJs = 'setTimeout(function(){';
$resetJs .= $this->formOptions['resetOptions']['resetForm']? 
					'$("#'.$this->formOptions['id'].'").trigger("reset");
					 $("button",form).removeAttr("disabled");
					 form.removeClass("succes error");':'';

$resetJs .= $this->formOptions['resetOptions']['resetCaptcha']? 
					'$(".captcha>a,.captcha>button",form).click();':'';					 
$resetJs .= $this->formOptions['resetOptions']['closeModal']? 
					'$("#close-modal").click();':'';
$resetJs .= $this->formOptions['resetOptions']['clearMessage']? 
					'var $success = $("#'.$this->templateOptions['message']['id'].'",form);
					 $success.removeClass("alert-success alert-danger show").html("");':'';
$resetJs .='},'.$this->formOptions['resetOptions']['timeout'].');';

//Подпись формы после валидации для ajax						
$ajaxSuck = $this->formOptions['ajax']?'+"&formValid="+getHash(form.serialize())':'';						
						
//Переменная накапливающая скрипт действий после валидации
$afterValidateJs = 'js:function(form,data,hasError){
						function getHash(formData){
							hashString = "";
							found = decodeURI(formData).match(/=[\w]+/g);
							found.forEach (function(item, i, arr) {
								hashString += /[\w]+/.exec(item)[0];
							})
console.log(hashString);
							for(var i=hashString.length-1, hash=0; i >= 0; --i) hash+=hashString.charCodeAt(i);
							return hash;
						}
						if(!hasError)
						{
							
							$.ajax(
							{
								"type"		: 	"POST",
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
															form.addClass("success");
															$success.html("'.$this->successMessage.'");
													}else{
															$success.addClass("alert-danger show");
															form.addClass("error");
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
			echo "<script>$('#".$this->formOptions['id']."').attr('action','".$this->formOptions['action']."');</script>";
		
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