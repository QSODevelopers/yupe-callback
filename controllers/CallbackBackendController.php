<?php

/**
 * CallbackBackendController контроллер для работы с конструктором форм обратной связи в панели управления
 *
 * @author UnnamedTeam
 * @link http://none.shit
 * @copyright 2015 UnnamedTeam & Project Yupe!Flavoring 
 * @package yupe.modules.callback.install
 * @license  BSD
 *
 **/
class CallbackBackendController extends yupe\components\controllers\BackController
{
	public function accessRules()
    {
        return [
            ['allow', 'roles' => ['admin']],
            ['allow', 'actions' => ['create'], 'roles' => ['Callback.CallbackBackend.Create']],
            ['allow', 'actions' => ['delete'], 'roles' => ['Callback.CallbackBackend.Delete']],
            ['allow', 'actions' => ['index'], 'roles' => ['Callback.CallbackBackend.Index']],
            ['allow', 'actions' => ['update', 'toggle', 'inline'], 'roles' => ['Callback.CallbackBackend.Update']],
            ['allow', 'actions' => ['view'], 'roles' => ['Callback.CallbackBackend.View']],
            ['allow', 'actions' => ['widget'], 'roles' => ['Callback.CallbackBackend.Widget']],
            ['deny']
        ];
    }

    public function actions()
    {
        return [
            'inline' => [
                'class'           => 'yupe\components\actions\YInLineEditAction',
                'model'           => 'Callback',
                'validAttributes' => ['name', 'code', 'type']
            ],
            'toggle' => [
                'class'     => 'booster.actions.TbToggleAction',
                'modelName' => 'Callback',
            ],
        ];
    }
	/**
     * Manages all models.
     *
     * @return void
     */
    public function actionIndex()
    {
        $model = new Callback('search');

        $model->unsetAttributes(); // clear any default values

        $model->setAttributes(
            Yii::app()->getRequest()->getParam(
                'Callback',
                []
            )
        );

        $this->render('index', ['model' => $model]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id the ID of the model to be loaded
     *
     * @return Callback $model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Callback::model()->findByPk((int)$id);
        if ($model === null) {
            throw new CHttpException(404, Yii::t('CallbackModule.callback', 'Чьих сапоx эти туфли?'));
        }

        return $model;
    }

    /**
     * Displays a particular model.
     *
     * @param integer $id the ID of the model to be displayed
     *
     * @return void
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $code = "<?php \$this->widget(\n\t\"application.modules.callback.widgets.CallbackWidget\",Callback::getSettings('".$model->code."'));\n?>";

        $highlighter = new CTextHighlighter();
        $highlighter->language = 'PHP';
        $example = $highlighter->highlight($code);

        $this->render(
            'view',
            [
                'model'     =>	$model,
                'example'   =>	$example,
                'code'		=>	$code,
            ]
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return void
     */
    public function actionCreate()
    {
        $model = new Callback();
        $model->templateToArray();
        if (($data = Yii::app()->getRequest()->getPost('Callback')) !== null) {
            $model->setAttributes($data);
            if ($model->save()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('CallbackModule.callback', 'Новые настройки для CallbackMixer сохранены')
                );

                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        ['create']
                    )
                );
            }
        }
        
        $this->render('create', ['model' => $model]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id the ID of the model to be updated
     *
     * @return void
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
       	$model->templateToArray();
        if (($data = Yii::app()->getRequest()->getPost('Callback')) !== null) {
            $model->setAttributes($data);

            if ($model->save()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('CallbackModule.callback', 'Настройки для CallbackMixer сохранены')
                );

                Yii::app()->cache->delete("Callback{$model->code}");

                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        ['update', 'id' => $model->id]
                    )
                );
            }
        }
        $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     *
     * @param integer $id the ID of the model to be deleted
     *
     * @return void
     *
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {

            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            Yii::app()->getRequest()->getIsAjaxRequest() || $this->redirect(
                (array)Yii::app()->getRequest()->getPost('returnUrl', 'index')
            );

        } else {
            throw new CHttpException(400, Yii::t('ContentBlockModule.contentblock', 'Unknown request!'));
        }
    }
    
}