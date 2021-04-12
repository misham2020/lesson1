<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
?>
<?php
$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '.default',
    [
        'GRID_ID' => $arResult['GRID']['GRID_ID'],
        'COLUMNS' => $arResult['GRID']['COLUMNS'],
        'ROWS' => $arResult['GRID']['ROWS'],
        'PAGE_SIZES' => [
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20']
        ],
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID(
            'bitrix:main.ui.grid',
            '.default',
            ''
        ),
        'NAV_OBJECT' => $arResult['GRID']['NAV_OBJECT'],
        'SHOW_ROW_CHECKBOXES' => false,
        'AJAX_OPTION_JUMP' => 'N',
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        'SHOW_ROW_ACTIONS_MENU' => false,
        'SHOW_GRID_SETTINGS_MENU' => true,
        'SHOW_NAVIGATION_PANEL' => true,
        'SHOW_PAGINATION' => false,
        'SHOW_SELECTED_COUNTER' => false,
        'SHOW_TOTAL_COUNTER' => false,
        'SHOW_PAGESIZE' => true,
        'SHOW_ACTION_PANEL' => true,
        'ALLOW_COLUMNS_SORT' => true,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_HORIZONTAL_SCROLL' => true,
        'ALLOW_SORT' => true,
        'ALLOW_PIN_HEADER' => true,
        'AJAX_OPTION_HISTORY' => 'N'
    ],
    $component
); ?>