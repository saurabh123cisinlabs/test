<?php
class FW_Subscription_Model_Resource_Magazine_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected function _construct()
    {
            $this->_init('subscription/magazine');
    }
}