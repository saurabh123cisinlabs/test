<?php
class FW_Subscription_Model_Resource_Magazine extends Mage_Core_Model_Resource_Db_Abstract {
    
     protected function _construct()
    {
        $this->_init('subscription/magazine', 'id');
    }

}
