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
	/**
     * Manages all models.
     *
     * @return void
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}