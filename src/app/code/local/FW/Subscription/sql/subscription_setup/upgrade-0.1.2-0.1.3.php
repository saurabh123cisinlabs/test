<?php
$installer = $this;
$installer->startSetup();

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
'comparable'=>'1',
'searchable'=>'1',
'is_configurable'=>'1',
'user_defined'=>'1',
'visible_on_front' => 1, //want to show on frontend?
'visible_in_advanced_search' => 1,
'is_html_allowed_on_front' => 1,
'unique'=>false,
'apply_to' => 'downloadable', //simple,configurable,bundled,grouped,virtual,downloadable
'is_configurable' => false
);

$model->addAttribute('catalog_product','digital_fulfilment_date',$data);
$attributeCode = 'digital_fulfilment_date';
$attributeGroup = 'custom';
$entity = Mage_Catalog_Model_Product::ENTITY;
$attributeSetIds = $model->getAllAttributeSetIds($entity);
foreach($attributeSetIds as $attributeSetId)
{
$model->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroup, $attributeCode);
}

$data=array(
'type'=>'text',
'input'=>'text',
'sort_order'=>50,
'label'=>'Digital Parent',
'global'=>Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
'required'=>'0',
'comparable'=>'1',
'searchable'=>'1',
'is_configurable'=>'1',
'user_defined'=>'1',
'visible_on_front' => 1, //want to show on frontend?
'visible_in_advanced_search' => 1,
'is_html_allowed_on_front' => 1,
'unique'=>false,
'apply_to' => 'downloadable', //simple,configurable,bundled,grouped,virtual,downloadable
'is_configurable' => false
);

$model->addAttribute('catalog_product','digital_parent',$data);
$attributeCode = 'digital_parent';
$attributeGroup = 'custom';
$entity = Mage_Catalog_Model_Product::ENTITY;
$attributeSetIds = $model->getAllAttributeSetIds($entity);
foreach($attributeSetIds as $attributeSetId)
{
$model->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroup, $attributeCode);
}


$data=array(
'type'=>'text',
'input'=>'text',
'sort_order'=>50,
'default'=>'no',
'label'=>'Flag',
'global'=>Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
'required'=>'0',
'comparable'=>'1',
'searchable'=>'1',
'is_configurable'=>'1',
'user_defined'=>'1',
'visible_on_front' => 1, //want to show on frontend?
'visible_in_advanced_search' => 1,
'is_html_allowed_on_front' => 1,
'unique'=>false,
'apply_to' => 'downloadable', //simple,configurable,bundled,grouped,virtual,downloadable
'is_configurable' => false
);

$model->addAttribute('catalog_product','flag',$data);
$attributeCode = 'flag';
$attributeGroup = 'custom';
$entity = Mage_Catalog_Model_Product::ENTITY;
$attributeSetIds = $model->getAllAttributeSetIds($entity);
foreach($attributeSetIds as $attributeSetId)
{
$model->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroup, $attributeCode);
}

$installer->endSetup();
