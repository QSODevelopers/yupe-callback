<?php
/**
 *
 * Файл конфигурации модуля callback
 *
 * @author UnnamedTeam
 * @link http://none.shit
 * @copyright 2015 UnnamedTeam & Project Yupe!Flavoring 
 * @package yupe.modules.callback.install
 * @license  BSD
 * @since 0.0.1
 *
 */
return [
    'module'   => [
        'class'  => 'application.modules.callback.CallbackModule',
    ],
    'import'    => [
        'application.modules.callback.models.*',
    ],
    'rules'     => [
        '/callback' => 'callback/callback/index',
        '/callback/send' => 'callback/callback/validate',
    ],
    'component' => []
];