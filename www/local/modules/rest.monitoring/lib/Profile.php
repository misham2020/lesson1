<?php

namespace Rest\Monitoring;

use Rest\Monitoring\Orm\MonitoringProfilesTable;
use Rest\Monitoring\Orm\MonitoringPropertiesTable;
use Bitrix\Main\Localization\Loc;

/**
 * Class Profile
 * @package Rest\Monitoring
 * Класс работы с профилями
 */
class Profile
{

    /**
     * Получис список профилей
     * @param $arParams
     * @return object
     */
    public function getListData($arParams)
    {
        return MonitoringProfilesTable::getList($arParams);
    }

    /**
     * Добавляем профиль
     * @param $arFields
     * @return array|int
     * @throws \Exception
     */
    public function addProfile($arFields)
    {
        $arFieldsAddProfile = $this->checkProfilePostData($arFields);
        $arFieldsAddProperty = [];

        $oProfile = MonitoringProfilesTable::add($arFieldsAddProfile);

        if ($oProfile->isSuccess()) {
            $iProfileID = $oProfile->getId();

            if (isset($arFieldsAddProperty)) {
                foreach ($arFieldsAddProperty as $arProperty) {
                    MonitoringPropertiesTable::add([
                        'PROFILE_ID' => $iProfileID,
                        'NAME' => $arProperty['NAME'],
                        'VALUE' => $arProperty['VALUE']
                    ]);
                }
            }

            return $iProfileID;
        }

        return $oProfile->getErrorMessages();
    }

    /**
     * Обновляем профиль
     * @param $iProfileID
     * @param $arFields
     * @return array|bool
     * @throws \Exception
     */
    public function updateProfile($iProfileID, $arFields)
    {
        $arFieldsAdd = $this->checkProfilePostData($arFields);

        $oProfile = MonitoringProfilesTable::update($iProfileID, $arFieldsAdd);

        if ($oProfile->isSuccess()) {
            $arOldHeaders = $this->getProfileHeaders($iProfileID);
            if (isset($arOldHeaders)) {
                foreach ($arOldHeaders as $arHeader) {
                    MonitoringPropertiesTable::delete($arHeader['ID']);
                }
            }

            $arFieldsAddProperty = [];
            if (isset($arFieldsAddProperty)) {
                foreach ($arFieldsAddProperty as $arProperty) {
                    MonitoringPropertiesTable::add([
                        'PROFILE_ID' => $iProfileID,
                        'NAME' => $arProperty['NAME'],
                        'VALUE' => $arProperty['VALUE']
                    ]);
                }
            }

            return true;
        }

        return $oProfile->getErrorMessages();
    }

    /**
     * Удаляем профиль
     * @param $iProfileID
     * @return array|bool
     * @throws \Exception
     */
    public function deleteProfile($iProfileID)
    {
        $oProfile = MonitoringProfilesTable::delete($iProfileID);

        if ($oProfile->isSuccess()) {
            $arOldHeaders = $this->getProfileHeaders($iProfileID);
            if (isset($arOldHeaders)) {
                foreach ($arOldHeaders as $arHeader) {
                    MonitoringPropertiesTable::delete($arHeader['ID']);
                }
            }

            return true;
        }

        return $oProfile->getErrorMessages();
    }

    /**
     * Получаем данные выбранного профиля
     * @param $iProfileID
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getProfile($iProfileID)
    {
        $arProfile = MonitoringProfilesTable::getById($iProfileID)->fetchAll();

        if (isset($arProfile[0]['ID']) && is_numeric($arProfile[0]['ID'])) {
            return $arProfile[0];
        }

        return false;
    }

    /**
     * Получаем заголовки профиля
     * @param $iProfileID
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getProfileHeaders($iProfileID)
    {
        $arResult = [];

        $arHeaders = MonitoringPropertiesTable::getList([
            'filter' => [
                '=PROFILE_ID' => $iProfileID
            ]
        ])->fetchAll();

        if (isset($arHeaders[0])) {
            $arResult = $arHeaders;
        }

        return $arResult;
    }

    /**
     * Поля по умолчанию
     * @return array
     */
    public function getDefaultEditFields()
    {
        return [
            'ID' => '',
            'NAME' => '',
            'URL' => '',
            'METHOD' => 'POST',
            'CHECK_INTERVAL' => '60',
            'ACTIVITY' => 'Y',
        ];
    }

    /**
     * @param $arFields
     * @return array
     */
    protected function checkProfilePostData($arFields)
    {
        $arResult = [];

        if ($arFields) {
            foreach ($this->getDefaultEditFields() as $sNameField => $sDataField) {
                if ($sNameField == 'ID') {
                    continue;
                } elseif ($sNameField == 'ACTIVITY') {
                    $arResult[$sNameField] = empty($arFields[$sNameField]) ? 'N' : 'Y';
                } else {
                    $arResult[$sNameField] = $arFields[$sNameField];
                }
            }
        }

        return $arResult;
    }

    /**
     * @param $arFields
     * @return array
     */
    protected function checkHeadersPostData($arFields)
    {
        $arFieldsAdd = [];

        if ($arFields) {
            foreach ($arFields['NAME'] as $iKey => $sField) {
                if ($sField && $arFields['VALUE'][$iKey]) {
                    $arFieldsAdd[] = [
                        'NAME' => Helpers::clearString($sField),
                        'VALUE' => Helpers::clearString($arFields['VALUE'][$iKey])
                    ];
                }
            }
        }

        return $arFieldsAdd;
    }
}