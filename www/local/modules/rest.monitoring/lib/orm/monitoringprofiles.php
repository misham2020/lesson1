<?php

namespace Rest\Monitoring\Orm;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class MonitoringProfilesTable
 * @package Rest\Monitoring\Orm
 */
class MonitoringProfilesTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     * @return string
     */
    public static function getTableName()
    {
        return 'y_monitoring_profiles';
    }

    /**
     * Returns entity map definition.
     * @return array
     * @throws \Exception
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('YLAB_CKECKUP_PROFILE_ID_FIELD'),
            ]),
            new Entity\StringField('NAME', [
                'validation' => [__CLASS__, 'validateName'],
                'title' => Loc::getMessage('YLAB_CKECKUP_PROFILE_NAME_FIELD'),
            ]),
            new Entity\StringField('URL', [
                'title' => Loc::getMessage('YLAB_CKECKUP_PROFILE_URL_FIELD'),
            ]),
            new Entity\EnumField('METHOD', [
                'values' => ['POST', 'GET'],
                'title' => Loc::getMessage('YLAB_CKECKUP_PROFILE_METHOD_FIELD'),
            ]),
            new Entity\IntegerField('CHECK_INTERVAL', [
                'title' => Loc::getMessage('YLAB_CKECKUP_PROFILE_CHECK_INTERVAL_FIELD'),
            ]),
            new Entity\BooleanField('ACTIVITY', [
                'values' => ['N', 'Y'],
                'title' => Loc::getMessage('YLAB_CKECKUP_PROFILE_ACTIVITY_FIELD'),
            ]),
        ];
    }

    /**
     * Returns validators for NAME field.
     * @return array
     * @throws \Bitrix\Main\ArgumentTypeException
     */
    public static function validateName()
    {
        return [
            new Entity\Validator\Length(null, 255),
        ];
    }
}
