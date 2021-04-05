<?php


namespace Rest\Monitoring;

use Rest\Monitoring\Orm\MonitoringProfilesTable;



class Profile extends \Bitrix\Main\Entity\DataManager
{

    public function GetList()
    {
        $result = MonitoringProfilesTable::getList()->fetchCollection();
        return $result;
    }

    public function getById($id)
    {
        $result = MonitoringProfilesTable::getRowById($id);
        return $result;
    }
    

    public function edit($id, array $parameters)
    {
        $result = MonitoringProfilesTable::update($id, $parameters);
        if (!$result->isSuccess())
        {
            $errors = $result->getErrorMessages();
        }
        
    }
    public function add(array $parameters)
    {
        $result = MonitoringProfilesTable::add($parameters);

        if ($result->isSuccess())
          {
           $id = $result->getId();
          }else{
            $errors = $result->getErrorMessages();
          }
        
    }
    public function delete($id)
    {
        $result = MonitoringProfilesTable::delete($id);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
        }
    }
}
