<style>.area_toolbar{display: none;}.close{margin-left: 10px;}</style>
<?php
$this->breadcrumbs = [
    Yii::t('CallbackModule.callback', 'Настройки для CallbackMixer') => ['/callback/callbackBackend/index'],
    $model->name                                                => [
        '/callback/callbackBackend/view',
        'id' => $model->id
    ],
    Yii::t('CallbackModule.callback', 'Редактирование настроек виджета'),
];

$this->pageTitle = Yii::t('CallbackModule.callback', 'Content blocks - edit');

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
        <!-- <iframe src="<?php echo $this->createUrl('/callback/widget/'.$model->code);?>" frameborder='0' class="col-xs-12" scrolling="no"></iframe> -->
            <?php $this->widget('application.modules.callback.widgets.CallbackWidget',$model::getSettings($model->code)) ?>
        </div>
    <?php  $this->endWidget();?>
</div>
<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
