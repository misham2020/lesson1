<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php';

\CModule::IncludeModule("rest.monitoring");
$newProfile=[
    'NAME' => 'Test Profile',
    'URL'=> 'https://site.ru/api',
    'METHOD'=>'GET',
    'CHECK_INTERVAL'=>3600,
    'ACTIVITY'=>'Y'
];
$profile=new Rest\Monitoring\Profile();
var_dump($profile->add($newProfile));

require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/footer.php';
