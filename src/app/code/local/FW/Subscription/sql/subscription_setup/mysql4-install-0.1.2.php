<?php
$installer = $this;
$installer->startSetup();

$sql=<<<SQLTEXT
DROP TABLE IF EXISTS `subscription_table`;
create table subscription_table
(id int not null auto_increment, customer_id varchar(100),order_id varchar(100),
downloadable_sku varchar(100),flag varchar(100) DEFAULT 0,downloadable_link varchar(100),
primary key(id),unique (order_id));

SQLTEXT;

$installer->run($sql);

$model = Mage::getResourceModel('catalog/setup','catalog_setup');
$model->removeAttribute('catalog_product','digital_fullfilment_date');
$model->removeAttribute('catalog_product','digital_parent');
$model->removeAttribute('catalog_product','flag');

$model = Mage::getResourceModel('catalog/setup','catalog_setup');

$data=array(
'type'=>'text',
'input'=>'date',
'sort_order'=>50,
'label'=>'Digital Date',
'global'=>Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
'required'=>'0',
'comparable'=>'0',
'searchable'=>'0',
'is_configurable'=>'1',
'user_defined'=>'1',
'visible_on_front' => 0, //want to show on frontend?
'visible_in_advanced_search' => 0,
'is_html_allowed_on_front' => 0,
'required'=> 0,
'unique'=>false,
'apply_to' => 'downloadable', //simple,configurable,bundled,grouped,virtual,downloadable
'is_configurable' => false
);

$model->addAttribute('catalog_product','digital_fullfilment_date',$data);

$model->addAttributeToSet(
    'catalog_product', 'Default', 'General', 'digital_fullfilment_date'
); //Default = attribute set, General = attribute group

$data=array(
'type'=>'text',
'input'=>'text',
'sort_order'=>50,
'label'=>'Digital Parent',
'global'=>Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
'required'=>'0',
'comparable'=>'0',
'searchable'=>'0',
'is_configurable'=>'1',
'user_defined'=>'1',
'visible_on_front' => 0, //want to show on frontend?
'visible_in_advanced_search' => 0,
'is_html_allowed_on_front' => 0,
'required'=> 0,
'unique'=>false,
'apply_to' => 'downloadable', //simple,configurable,bundled,grouped,virtual,downloadable
'is_configurable' => false
);

$model->addAttribute('catalog_product','digital_parent',$data);

$model->addAttributeToSet(
    'catalog_product', 'Default', 'General', 'digital_parent'
); //Default = attribute set, General = attribute group


$data=array(
'type'=>'text',
'input'=>'text',
'sort_order'=>50,
'default'=>'no',
'label'=>'Flag',
'global'=>Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
'required'=>'0',
'comparable'=>'0',
'searchable'=>'0',
'is_configurable'=>'1',
'user_defined'=>'1',
'visible_on_front' => 0, //want to show on frontend?
'visible_in_advanced_search' => 0,
'is_html_allowed_on_front' => 0,
'required'=> 0,
'unique'=>false,
'apply_to' => 'downloadable', //simple,configurable,bundled,grouped,virtual,downloadable
'is_configurable' => false
);

$model->addAttribute('catalog_product','flag',$data);

$model->addAttributeToSet(
    'catalog_product', 'Default', 'General', 'flag'
); //Default = attribute set, General = attribute group

$installer->endSetup();
