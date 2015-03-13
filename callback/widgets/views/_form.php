<?php

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

$afterValidateJs = 'js:function(form,data,hasError)
									{
										if(!hasError)
										{
											$.ajax(
											{
												"type"			: 	"POST",
												"url"			: 	"'.$this->formOptions['action'].'",
												"data"			: 	form.serialize(),
												"beforeSend"  	: 	function(){
																		$("button",form).attr("disabled","disabled");
																	},
												"success"		:	function(data){
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
												"complete"    	:	 function(){
																		'.$resetJs.'
																	},
											});
										}
									}';



if($this->formOptions['showFormAfterSend'] || !Yii::app()->user->hasFlash($this->formOptions['id'])){

	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',[
		'id'                      =>  $this->formOptions['id'],
		'type'                    =>  $this->formOptions['type'],
		'enableClientValidation'  =>  $this->formOptions['enableClientValidation'],
		'htmlOptions'             =>  $this->formOptions['htmlOptions'],
		'clientOptions'           =>  $this->formOptions['ajax'] 
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
	echo Yii::app()->user->hasFlash($this->formOptions["id"]) ? "<div class='alert alert-success show'>".Yii::app()->user->getFlash($this->formOptions["id"])."</div>":"";
}
echo $this->formOptions['afterText'];

?>