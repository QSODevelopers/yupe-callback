<?php $this->beginWidget('bootstrap.widgets.TbModal', [
		'htmlOptions'=>[
			'class'	=>	$this->modalOptions['сlass'],
			'id'	=>	$this->modalOptions['id']
		]
	]); 
?>
<div class="modal-header">
	<a class="close" id="close-modal" data-dismiss="modal"><?php echo $this->modalOptions['closeText'] ?></a>  
    <h3 class="title"><?php echo $this->modalOptions['title']?></h3>
</div>
<div class="modal-body row">
	<?php $this->render('_form',array('model'=>$model,'body'=>$body));?>
</div>
<?php $this->endWidget(); ?>