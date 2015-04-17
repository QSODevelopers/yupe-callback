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
    ]
];
?>

<div class='page-header'>
    <h1>
        <?php echo Yii::t('CallbackModule.callback', 'Создание нового массива настроек для виджета CallbackMixer'); ?><br/>
    </h1>
</div>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
