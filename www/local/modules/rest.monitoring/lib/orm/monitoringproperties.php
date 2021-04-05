<?php

namespace Rest\Monitoring\Orm;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class MonitoringPropertiesTable
 * @package Rest\Monitoring\Orm
 */
class MonitoringPropertiesTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     * @return string
     */
    public static function getTableName()
    {
        return 'y_monitoring_properties';
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
                'title' => Loc::getMessage('YLAB_CKECKUP_PROPERTIES_ID_FIELD'),
            ]),
            new Entity\IntegerField('PROFILE_ID'),
            new Entity\ReferenceField(
                'PROFILE',
                'Rest\Monitoring\Orm\MonitoringProfilesTable',
                ['=this.PROFILE_ID' => 'ref.ID']
            ),
            new Entity\StringField('NAME', [
                'validation' => [__CLASS__, 'validateName'],
                'title' => Loc::getMessage('YLAB_CKECKUP_PROPERTIES_NAME_FIELD'),
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