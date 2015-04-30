<?php
/**
 * @var $model Callback
 * @var $this CallbackBackendController
 * @var $form TbActiveForm
 */
?>
<style>textarea{min-height: 150px;}</style>
<script type='text/javascript'>
    function keyDown(e,obj) {
          if (!e && event.keyCode == 9)
          {
            event.returnValue = false;
            insertAtCursor(obj, "    ");
          }
          else if (e.keyCode == 9)
          {
            e.preventDefault();
            insertAtCursor(obj, "    ");
          }
    };
    function insertAtCursor(myField, myValue) {
      //IE support
      if (document.selection) {
        var temp;
        myField.focus();
        sel = document.selection.createRange();
        temp = sel.text.length;
        sel.text = myValue;
        if (myValue.length == 0) {
          sel.moveStart('character', myValue.length);
          sel.moveEnd('character', myValue.length);
        } else {
          sel.moveStart('character', -myValue.length + temp);
        }
        sel.select();
      }
      else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
        myField.selectionStart = startPos + myValue.length;
        myField.selectionEnd = startPos + myValue.length;
      } else {
        myField.value += myValue;
      }
    }

    $(document).ready(function () {
        $('#callback-form').liTranslit({
            elName: '#Callback_name',
            elAlias: '#Callback_code'
        });
        
    })
</script>

<?php


$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    [
        'id'                     => 'callback-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'type'                   => 'vertical',
        'htmlOptions'            => ['class' => 'well col-xs-12'],
    ]
); ?>

<div class="alert alert-info col-xs-12">
    <?php echo Yii::t('CallbackModule.callback', 'Поля отмеченные {sign} обязательны к заполнению.',['{sign}'=>'<span class="required">*</span>']); ?>
</div>

<?php echo $form->errorSummary($model); ?>


<div class="col-xs-6">
    <div class="col-xs-6">
        <?php echo $form->textFieldGroup($model, 'name'); ?>
    </div>


    <div class="col-xs-6">
        <?php echo $form->textFieldGroup($model, 'code'); ?>
    </div>


    <div class="col-xs-12">
        <?php echo $form->textFieldGroup($model, 'description'); ?>
    </div>

    <div class="col-xs-12">
        <?php echo $form->radioButtonListGroup(
            $model,
            'type',
            ['widgetOptions' => ['data' => $model->getTypes()]]
        ); ?>
    </div>


    <div class="col-xs-12">
        <?php echo $form->textFieldGroup($model, 'title'); ?>
    </div>


<!--     <div class="col-xs-12">
        <?php //echo $form->checkboxListGroup($model, 'template',
        //); ?>
    </div> -->
    
    <div class="col-xs-12">
        <?php echo $form->checkboxListGroup(
            $model,
            'template',
            ['widgetOptions' => ['data' => FormTemplater::getTemplates()],
            'inline'=>true]

        ); ?>
    </div>

    <div class="col-xs-12">
        <?php echo $form->textAreaGroup($model, 'success_message'); ?>
    </div>


    <div class="col-xs-12">
        <?php echo $form->textAreaGroup($model, 'error_message'); ?>
    </div>
    <div class="col-xs-12">
        <?php echo $form->dropDownListGroup($model, 'status', ['widgetOptions' => ['data' => $model->getStatusList()]]); ?>
    </div>
    <div class="col-xs-12">
<?php $this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType' => 'submit',
        'context'    => 'primary',
        'label'      => $model->isNewRecord ? Yii::t(
            'CallbackModule.callback',
            'Добавить и продолжить'
        ) : Yii::t('CallbackModule.callback', 'Сохранить и продолжить'),
    ]
); ?>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType'  => 'submit',
        'htmlOptions' => ['name' => 'submit-type', 'value' => 'index'],
        'label'       => $model->isNewRecord ? Yii::t(
            'CallbackModule.callback',
            'Добавить и закрыть'
        ) : Yii::t('CallbackModule.callback', 'Сохранить и закрыть'),
    ]
); ?>
</div>
</div>
<div class="col-xs-6">
<!-- 
    <div class="col-xs-12">
        <?php /*$this->widget('application.modules.callback.extensions.editarea.EEditArea', array(
                'value'=>'button_options',
                'htmlOptions'=>array(
                        'name'=>'Callback[button_options]',
                        'syntax'=>'php',
                        'allow_toggle'=>true,
                        )
                )
        ); */?>
    </div>
 -->

    <div class="col-xs-12">
        <?php echo $form->textAreaGroup($model, 'button_options',[
            'widgetOptions'=>[
                'htmlOptions'=>[
                    'onkeydown'=>'javascript:keyDown(event, this)'
                ]
            ]
        ]); ?>
    </div>
 

    <div class="col-xs-12">
        <?php echo $form->textAreaGroup($model, 'modal_options',[
            'widgetOptions'=>[
                'htmlOptions'=>[
                    'onkeydown'=>'javascript:keyDown(event, this)'
                ]
            ]
        ]); ?>
    </div>


    <div class="col-xs-12">
        <?php echo $form->textAreaGroup($model, 'form_options',[
            'widgetOptions'=>[
                'htmlOptions'=>[
                    'onkeydown'=>'javascript:keyDown(event, this)'
                ]
            ]
        ]); ?>
    </div>


    <div class="col-xs-12">
        <?php echo $form->textAreaGroup($model, 'template_options',[
            'widgetOptions'=>[
                'htmlOptions'=>[
                    'onkeydown'=>'javascript:keyDown(event, this)'
                ]
            ]
        ]); ?>
    </div>


    <div class="col-xs-12">
        <?php echo $form->textAreaGroup($model, 'mail_options',[
            'widgetOptions'=>[
                'htmlOptions'=>[
                    'onkeydown'=>'javascript:keyDown(event, this)'
                ]
            ]
        ]); ?>
    </div>

    
</div>

<?php $this->endWidget(); ?>
