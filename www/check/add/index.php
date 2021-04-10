<?php

use Rest\Monitoring\Profile;
use Bitrix\Main\Loader;

require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php';

Loader::includeModule('rest.monitoring');

$arFields = [
    'NAME' => 'Test 2',
    'URL' => 'https://vk.ru/api',
    'METHOD' => 'POST',
    'CHECK_INTERVAL' => '60',
    'ACTIVITY' => 'Y',
];

$oProfile = new Profile();

echo '<pre>';

var_dump($oProfile->addProfile($arFields));

echo '</pre>';

require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/footer.php';