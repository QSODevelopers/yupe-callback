<?php $this->beginWidget('bootstrap.widgets.TbModal', $this->modalOptions['widgetOptions']); 
?>
<div class="modal-header">
	<a class="close" id="close-modal" data-dismiss="modal"><?php echo $this->modalOptions['closeText'] ?></a>  
    <h4 class="title"><?php echo $this->modalOptions['title']?></h4>
</div>
<div class="modal-body row">
	<?php $this->render('_form',array('model'=>$model,'body'=>$body,'afterValidateJs'=>$afterValidateJs));?>
</div>
<?php $this->endWidget(); ?>