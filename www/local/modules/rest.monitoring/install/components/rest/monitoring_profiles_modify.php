<?php
//type=delete&pid=' . $arProfile['ID'] .
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}





  
 
use Rest\Monitoring\Profile;
use Bitrix\Main\Loader;

require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php';

Loader::includeModule('rest.monitoring');

$oProfile = new Profile();

if ($type == 'View'){
    $APPLICATION->includeComponent(
        'rest:monitoring.profile.id',
        '',
        [

        ]
    );
}
$APPLICATION->includeComponent(
    'rest:monitoring.properties',
    '',
    [

    ]
);
require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/footer.php';