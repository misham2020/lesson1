<?php

use Rest\Monitoring\Profile;
use Bitrix\Main\Loader;

require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php';

Loader::includeModule('rest.monitoring');

$oProfile = new Profile();

echo '<pre>';

var_dump($oProfile->deleteProfile(3));

echo '</pre>';

require_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/footer.php';