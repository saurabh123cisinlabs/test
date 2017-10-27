<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table subscription_table
(id int not null auto_increment, customer_id varchar(100),order_id varchar(100),
downloadable_sku varchar(100),flag varchar(100) DEFAULT 0,downloadable_link varchar(100),
primary key(id),unique (order_id));
		
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 