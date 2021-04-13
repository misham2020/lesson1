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
class MonitoringProfileManager extends CBitrixComponent
{
    /**
     * @var array
     */
    private $arButtons = [];

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
     * Индификатор фильтра
     * @var string
     */
    private $sGridFilterID = 'monitoring_profiles_list';

    /**
     * Индификатор пагинации
     * @var string
     */
    private $sGridPaginationID = 'monitoring_profiles_list';

    /**
     * Получаем индификатор грида
     * @return string
     */
    public function getGridID()
    {
        return $this->sGridID;
    }

    /**
     * Получаем индификатор фильтра
     * @return string
     */
    public function getGridFilterID()
    {
        return $this->sGridFilterID;
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
        $this->arResult['BUTTONS'] = [
            'ADD_PROFILE' => [
                'LINK' => '/bitrix/admin/rest.monitoring_profiles_modify.php',
                'NAME' => Loc::getMessage('REST_MONITORING_PROFILE_MANAGER_ADD')
            ]
        ];

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
     * Получаем индификатор пагинации
     * @return string
     */
    public function getGridPaginationID()
    {
        return $this->sGridPaginationID;
    }

    /**
     * Генерация данных для гридов
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getGridProfilesData()
    {
        $oGridOptions = new GridOptions($this->sGridID);

        $arSort = $oGridOptions->GetSorting([
            'sort' => ['ID' => 'ASC'],
            'vars' => ['by' => 'by', 'order' => 'order']
        ]);

        $arNavParams = $oGridOptions->GetNavParams();

        $oNav = new PageNavigation($this->sGridPaginationID);
        $oNav->allowAllRecords(true)
            ->setPageSize($arNavParams['nPageSize'])
            ->initFromUri();

        $oFilterOption = new FilterOptions($this->sGridID);
        $arFilterData = $oFilterOption->getFilter([]);

        $arFilter = [];
        if (isset($arFilterData['FIND'])) {
            $arFilter['NAME'] = '%' . $arFilterData['FIND'] . '%';
        }
        if (isset($arFilterData['FILTER_ID_numsel'])) {
            switch ($arFilterData['FILTER_ID_numsel']) {
                case 'exact':
                    $arFilter['=ID'] = $arFilterData['FILTER_ID_from'];
                    break;
                case 'range':
                    $arFilter['>ID'] = $arFilterData['FILTER_ID_from'];
                    $arFilter['<ID'] = $arFilterData['FILTER_ID_to'];
                    break;
                case 'more':
                    $arFilter['>ID'] = $arFilterData['FILTER_ID_from'];
                    break;
                case 'less':
                    $arFilter['<ID'] = $arFilterData['FILTER_ID_to'];
                    break;
            }
        }
        if (isset($arFilterData['FILTER_NAME'])) {
            $arFilter['NAME'] = $arFilterData['FILTER_NAME'];
        }
        if (isset($arFilterData['FILTER_URL'])) {
            $arFilter['=URL'] = $arFilterData['FILTER_URL'];
        }
        if (isset($arFilterData['FILTER_METHOD'])) {
            switch ($arFilterData['FILTER_METHOD']) {
                case 'P':
                    $arFilter['=METHOD'] = 'POST';
                    break;
                case 'G':
                    $arFilter['=METHOD'] = 'GET';
                    break;
            }
        }
        if (isset($arFilterData['FILTER_ACTIVITY'])) {
            switch ($arFilterData['FILTER_ACTIVITY']) {
                case 'Y':
                    $arFilter['=ACTIVITY'] = 'Y';
                    break;
                case 'N':
                    $arFilter['=ACTIVITY'] = 'N';
                    break;
            }
        }

        $arParams = [
            'select' => [
                'ID',
                'NAME',
                'URL',
                'CHECK_INTERVAL',
                'ACTIVITY'
            ],
            'offset' => $oNav->getOffset(),
            'order' => $arSort['sort'],
            'filter' => $arFilter
        ];

        $oProfile = new Profile();

        $arProfilesData = $oProfile->getListData($arParams);

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
                            'onclick' => 'document.location.href="action/edit?type=edit&pid=' . $arProfile['ID'] . '"'
                        ],
                        [
                            'text' => Loc::getMessage('REST_MONITORING_PROFILE_VIEW'),
                            'onclick' => 'document.location.href="actoin/view?type=view&pid=' . $arProfile['ID'] . '"'
                        ], 
                        [
                            'text' => Loc::getMessage('REST_MONITORING_PROFILE_DELETE'),
                            'onclick' => 'document.location.href="action/delete?type=delete&pid=' . $arProfile['ID'] . '"'
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
     * Поля для фильтрации
     * @return array
     */
    public function getGridFilter()
    {
        return [
            [
                'id' => 'FILTER_ID',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_ID'),
                'type' => 'number'
            ],
            [
                'id' => 'FILTER_NAME',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_NAME')
            ],
            [
                'id' => 'FILTER_URL',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_URL')
            ],
            [
                'id' => 'FILTER_METHOD',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_METHOD'),
                'type' => 'list',
                'items' => [
                    'P' => 'POST',
                    'G' => 'GET'
                ]
            ],
            [
                'id' => 'FILTER_ACTIVITY',
                'name' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_ACTIVE'),
                'type' => 'list',
                'items' => [
                    'Y' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_ACTIVE_YES'),
                    'N' => Loc::getMessage('REST_MONITORING_PROFILE_HEAD_ACTIVE_NO')
                ]
            ]
        ];
    }
    
    /**
     * @return bool
     */
    private function haveAccess()
    {
        return true;
    }
}