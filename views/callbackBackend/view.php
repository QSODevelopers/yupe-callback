<style>.close{margin-left: 10px;}</style>
<?php
$this->breadcrumbs = [
    Yii::t('CallbackModule.callback', 'Настройки для CallbackMixer') => ['/callback/callbackBackend/index'],
    $model->name,
];

$this->pageTitle = Yii::t('CallbackModule.callback', 'Подробный просмотр виджета {widgetName}',['{widgetName}'=>$model->name]);

$this->menu = [
    [
        'icon'  => 'fa fa-fw fa-list-alt',
        'label' => Yii::t('CallbackModule.callback', 'Управление сохраненными настройками для виджетов CallbackMixer'),
        'url'   => ['/callback/callbackBackend/index']
    ],
    [
        'icon'  => 'fa fa-fw fa-plus-square',
        'label' => Yii::t('CallbackModule.callback', 'Создать новые настройки для виджета'),
        'url'   => ['/callback/callbackBackend/create']
    ],
    [
        'label' => Yii::t('CallbackModule.callback', 'CallbackMixer') . ' «' . mb_substr(
                $model->name,
                0,
                32
            ) . '»'
    ],
    [
        'icon'  => 'fa fa-fw fa-pencil',
        'label' => Yii::t('CallbackModule.callback', 'Изменить настройки для виджета'),
        'url'   => [
            '/callback/callbackBackend/update',
            'id' => $model->id
        ]
    ],
    [
        'icon'        => 'fa fa-fw fa-trash-o',
        'label'       => Yii::t('CallbackModule.callback', 'Удалить настройки для виджета'),
        'url'         => '#',
        'linkOptions' => [
            'submit'  => ['/callback/callbackBackend/delete', 'id' => $model->id],
            'params'  => [Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken],
            'confirm' => Yii::t('CallbackModule.callback', 'Удалить данные настройки для виджета? Ты супердупер сурьёзно?'),
        ]
    ],
];
?>

<div class='page-header'>
    <h1>
        <?php echo Yii::t('CallbackModule.callback', 'Подробный просмотр виджета'); ?><br/>
        <small>&laquo;<?php echo $model->name; ?>&raquo;</small>
    </h1>
</div>
<div class='alert col-xs-12' id='view'>
    <?php $this->beginWidget('zii.widgets.jui.CJuiResizable',array(
        'options'=>[
            'minHeight'=>'150',
            'maxHeight'=>'800',
            'minWidth'=>'350',
            'maxWidth'=>'1500'
        ],
        'htmlOptions'=>[
            'class'=>'panel panel-default',
            'style'=>'overflow:hidden'
        ]
    )); ?>
        <div class='panel-heading'>
            <strong><i class='fa fa-gears'></i> <?php echo Yii::t('CallbackModule.callback', 'Результат вызова формы'); ?></strong>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'><i class='fa fa-close'></i></button>
            <?php $this->widget('bootstrap.widgets.TbButton',[
                    'buttonType' => 'ajaxButton',
                    'context' => 'link',
                    'encodeLabel'=>false,
                    'label' => '<i class="fa fa-refresh"></i>',
                    'url' => Yii::app()->createUrl('/callback/widget'),
                    'htmlOptions'=>[
                        'class'=>'close'
                    ],
                    'ajaxOptions' => [
                                'type'=>'post',
                                'data'=> Yii::app()->request->csrfTokenName."=".Yii::app()->request->getCsrfToken().'&code='.$model->code,
                                'url' => Yii::app()->createUrl('/callback/widget'),
                                'beforeSend'=>'js:function(data){ 
                                                $("#view").find(".panel-body").animate({"opacity":0},300);
                                            }',
                                'success'=>'js:function(data){ 
                                                $("#view").find(".panel-body").html(data);
                                                $("#view").find(".panel-body").animate({"opacity":1},300);
                                            }',
                    ],
                ]); 
            ?>
        </div>
        <div class='panel-body' >
        <?php $model::getSettings($model->code); ?>
            <?php $this->widget('application.modules.callback.widgets.CallbackWidget',$model::getSettings($model->code)) ?>
        </div>
    <?php  $this->endWidget();?>
</div>
<div class='col-xs-8'>
    <div class='panel panel-success'>
        <div class='panel-heading'>
            <strong><i class='fa fa-heartbeat'></i> <?php echo Yii::t('CallbackModule.callback', 'Параметры виджета'); ?></strong> 
        </div>
        <?php 
            $this->widget(
                'bootstrap.widgets.TbDetailView',
                [
                    'data'       => $model,
                    'htmlOptions'=>[
                        'class'=>'table-bordered table-hover'
                    ],
                    'attributes' => [
                        'id',
                        'name',
                        'code',
                        [
                            'name' => 'description',
                            'type' => 'raw',
                            'value' => Yii::t('CallbackModule.callback',$model->description),
                        ],
                        'title',
                        [
                            'name'  => 'type',
                            'value' => $model->getType(),
                        ],
                        'template',
                        [
                            'name'=>'button_options',
                            'value'=>'<pre class="pre-scrollable">'.$model->button_options.'</pre>',
                            'type'=>'raw'
                        ],
                        [
                            'name'=>'modal_options',
                            'value'=>'<pre class="pre-scrollable">'.$model->modal_options.'</pre>',
                            'type'=>'raw'
                        ],
                        [
                            'name'=>'form_options',
                            'value'=>'<pre class="pre-scrollable">'.$model->form_options.'</pre>',
                            'type'=>'raw'
                        ],
                        [
                            'name'=>'template_options',
                            'value'=>'<pre class="pre-scrollable">'.$model->template_options.'</pre>',
                            'type'=>'raw'
                        ],
                        [
                            'name'=>'mail_options',
                            'value'=>'<pre class="pre-scrollable">'.$model->mail_options.'</pre>',
                            'type'=>'raw'
                        ],
                        'success_message',
                        'error_message',
                    ],
                ]
            ); 
        ?>
    </div>
</div>
<div class='col-xs-4 '>
    <div class='panel panel-warning'>
        <div class='panel-heading'>
            <strong>
                <i class='fa fa-code'></i> <?php echo Yii::t('CallbackModule.callback', 'Код для вставки на странице'); ?>
            </strong>
            <?php $this->widget('application.modules.callback.extensions.EZClipboard.EZClipboard', array(
                    'tag' => 'a',
                    'tagHtmlOptions' =>[
                        'class'                     =>  'label label-danger pull-right',
                        'data-placement-tooltip'    =>  'bottom',
                        'data-placement-title'      =>  Yii::t("CallbackModule.callback", "Скопировать в буфер обмена"),
                    ],
                    'tagContent'    =>  '<i class="fa fa-keyboard-o fa-2x"></i>',
                    'tagId' => 'copy_button',
                    'clipboardText' => $code
                )); 
            ?>
        </div>
        <div class='panel-body'> 
            <?php echo $example; ?>
        </div>
    </div>
</div>
<div class='col-xs-4 '>
    <div class='panel panel-info'>
        <div class='panel-heading'>
            <strong><i class='fa fa-info-circle'></i> <?php echo Yii::t('CallbackModule.callback', 'Справка по параметрам'); ?></strong> 
        </div>
            <table class='table table-hover table-striped'>
            <thead>
                <tr>
                    <th>Параметр виджета</th>
                    <th>Описание</th>
                </tr>
            <thead>
            <tbody>
            <?php foreach ($model->attributeDescriptions() as $attr => $descr): ?>
                 <tr>
                    <td><strong><?php echo $attr?></strong>:</td>
                    <td><?php echo $descr ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
    
