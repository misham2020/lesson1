<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Rest\Monitoring\Profile;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;

Loader::includeModule('rest.monitoring');

/**
 * Class MonitoringProfileManager
 */
class MonitoringProfileId extends CBitrixComponent
{
   

    /**
     * @var array
     */
    private $arGrid = [];

    /**
     * Индификатор грида
     * @var string
     */
    private $sGridID = 'monitoring_profiles_list';

   
    
    /**
     * Получаем индификатор грида
     * @return string
     */
    public function getGridID()
    {
        return $this->sGridID;
    }

   

    /**
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams = [])
    {
        parent::onPrepareComponentParams($arParams);
    }

    /**
     * @return string
     */
    public function executeComponent()
    {
    

        $arProfilesGridData = $this->getGridProfilesData();

        $this->arResult['GRID'] = [
            'FILTER_ID' => $this->getGridFilterID(),
            'GRID_ID' => $this->getGridID(),
            'FILTER' => $this->getGridFilter(),
            'COLUMNS' => $this->getGridHeaders(),
            'ROWS' => $arProfilesGridData['ROWS'],
            'NAV_OBJECT' => $arProfilesGridData['NAV_OBJECT']
        ];

        $this->includeComponentTemplate();
    }

    
    /**
     * Генерация данных для гридов
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getGridProfilesData()
    {
    
        $arParams = [
            'select' => [
                'ID',
                'NAME',
                'URL',
                'CHECK_INTERVAL',
                'ACTIVITY'
            ]
        ];

        $oProfile = new Profile();

        $arProfilesData = $oProfile->getProfile($iProfileID);

        $oNav->setRecordCount(
            $arProfilesData->getSelectedRowsCount()
        );

        $arParams['limit'] = $oNav->getLimit();

        $oProfiles = $arProfilesData;

        $arResult['ROWS'] = [];
        $arResult['NAV_OBJECT'] = [];

        if ($oProfiles->getSelectedRowsCount()) {
            while ($arProfile = $oProfiles->fetch()) {
                $arResult['ROWS'][] = [
                    'id' => 'PROFILE_' . $arProfile['ID'],
                    'has_child' => false,
                    'parent_id' => 0,
                    'not_count' => true,
                    'draggable' => false,
                    'attrs' => [
                        'data-type' => 'template',
                        'data-group-id' => 0,
                    ],
                    'data' => [
                        'ID' => $arProfile['ID'],
                        'NAME' => $arProfile['NAME'],
                        'URL' => $arProfile['URL'],
                        'CHECK_INTERVAL' => $arProfile['CHECK_INTERVAL'],
                        'ACTIVITY' => $arProfile['ACTIVITY'] === 'N' ? Loc::getMessage('REST_MONITORING_PROFILE_HEAD_ACTIVE_NO') : Loc::getMessage('REST_MONITORING_PROFILE_HEAD_ACTIVE_YES'),
                    ],
                    'actions' => [
                        [
                            'text' => Loc::getMessage('REST_MONITORING_PROFILE_EDIT'),
                            'onclick' => 'document.location.href="rest.monitoring_profiles_modify.php?type=edit&pid=' . $arProfile['ID'] . '"'
                        ],
                
                        [
                            'text' => Loc::getMessage('REST_MONITORING_PROFILE_DELETE'),
                            'onclick' => 'document.location.href="rest.monitoring_profiles_modify.php?type=delete&pid=' . $arProfile['ID'] . '"'
                        ]
                    ]
                ];
            }

            $arResult['NAV_OBJECT'] = $oNav;
        }

        return $arResult;
    }

    /**
     * Получаем заголовки для грида
     * @return array
     */
    public function getGridHeaders()
    {
        $arHeaders = [
            [
                'id' => 'ID',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_ID'),
                'sort' => false,
                'default' => true
            ],
            [
                'id' => 'NAME',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_NAME'),
                'sort' => false,
                'default' => true
            ],
            [
                'id' => 'URL',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_URL'),
                'sort' => false,
                'default' => true
            ],
            [
                'id' => 'CHECK_INTERVAL',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_CHECK'),
                'sort' => false,
                'default' => true
            ],
            [
                'id' => 'ACTIVITY',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_ACTIVE'),
                'sort' => false,
                'default' => true
            ]
        ];

        return $arHeaders;
    }

  
    
    /**
     * @return bool
     */
    private function haveAccess()
    {
        return true;
    }
}