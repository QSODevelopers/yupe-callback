<?php
class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
			'request'=>'application.controllers.site.RequestAction',
			'qaptcha'=>'application.controllers.site.QaptchaAction',
		);
	}
}