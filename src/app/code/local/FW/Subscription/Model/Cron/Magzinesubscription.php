<?php
class FW_Subscription_Model_Cron_Magzinesubscription extends Mage_Core_Model_Abstract

    {
    public function process()
        {
        $baseurl = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL);
        $todayDate = array('gt' => date("m/d/Y", strtotime('-10 day')));
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection = $collection->addAttributeToFilter('type_id', 'downloadable')
                                 ->addAttributeToFilter('status', '1')
                                 ->addAttributeToFilter('flag', array(array('null' => false), array('neq' => 'yes')))
                                 ->addAttributeToFilter('digital_parent', array('neq' => ''))
                                 ->addFieldToFilter('digital_fulfilment_date', $todayDate)
                                 ->addAttributeToSelect('digital_fulfilment_date');
                                 
        $subsModel = Mage::getModel('subscription/magazine');
        $catalogModel = Mage::getModel('catalog/product');
        $Ordercollection = Mage::getModel('sales/order')->getCollection(); // get order collection
        $SalesOrdercollection = Mage::getModel('sales/order');
        if (!empty($collection->getData()))
          {

            foreach($collection as $product)
              {
                $downloadableid = $product->getId();
                $_product = $catalogModel->load($downloadableid);
                $downloadableProductName = $_product->getName();
                $downloadableSku = $_product->getSku();
                $downloadableProductUrl = $_product->getProductUrl();
                $links = Mage::getModel('downloadable/link')->getCollection()->addFieldToFilter('product_id', array(
                    'eq' => $downloadableid
                ));
                foreach($links as $downloadlink)
                  {
                    $productLink = $downloadlink->getData('link_url');
                    if(empty($productLink)){
                      $productLink = $baseurl."media/downloadable/files/links".$downloadlink->getData('link_file');
                    }
                  }

                if ($product->getData('digital_parent'))
                  {
                    $virtualsku = $product->getData('digital_parent');
                    $_productId = $catalogModel->getIdBySku($virtualsku);
                    $_product = $catalogModel->load($_productId);
                    $virtualProductName = $_product->getName();
                  }

                foreach($Ordercollection as $order)
                  {
                    $OrderNumber = $order->getIncrementId(); // get increment Id
                    /*get product sku from order start*/

                    $order = Mage::getModel('sales/order')->load($OrderNumber, 'increment_id');
                    $order->getAllVisibleItems();
                    $orderItems = $order->getItemsCollection()->load();
                    foreach($orderItems as $sItem)
                      {
                        $orderSku = $sItem->getSku();

                        if ($orderSku == $virtualsku)
                          {

                            $orderId = $order->getIncrementId();

                            $customerId = $order->getData('customer_id');
                            $customerFname = $order->getData('customer_firstname');
                            $customerLname = $order->getData('customer_lastname');
                            $customerEmailId = $order->getData('customer_email');
                            $productids = $downloadableid;
                            
                            $websiteId = Mage::app()->getWebsite()->getId();
                            $store = Mage::app()->getStore();
                            $customer_id = $customerId;
                            $order_id = $OrderNumber;
                            $downloadable_sku = $downloadableSku;
                            
                            $downloadable_link = $productLink;
                            /**********************Save Edit in database***********************/
                           $data = [];
                            $data = array(
                                'customer_id' => $customer_id,
                                'order_id' => $order_id,
                                'downloadable_sku' => $downloadable_sku,
                                'flag' => 1,
                                'downloadable_link' => $downloadable_link
                            );

                            $subsModel->setData($data);
                            try
                              {
                                $insertId = $subsModel->save();
                              }

                            catch(Exception $e)
                              {
                                $e->getMessage();
                              }
                            if($insertId)
                                {
                                    /*to set flag as yes for downloadbale product*/
                                    Mage::getSingleton('catalog/product_action')->updateAttributes(array(
                                    $downloadableid
                                    ), array(
                                    'flag' => 'yes'
                                    ), $storeId);
                                
                            /**********************Edit order***************************/
                            $productId = $downloadableid;
                            $orderId = $order_id;
                            $product = Mage::getModel('catalog/product')->load($productId);
                            $product->setPrice(0);
                            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

                            $quote = Mage::getModel('sales/quote')->getCollection()->addFieldToFilter("entity_id", $order->getQuoteId())->getFirstItem();
                            if (!$quote->getId())
                              {
                                $quote = Mage::getModel('sales/convert_order')->setIsActive(false)->toQuote($order)->setReservedOrderId($order->getIncrementId())->save();
                              }

                            $params = array();
                            $links = Mage::getModel('downloadable/product_type')->getLinks($product);

                            $linkId = 0;
                            foreach($links as $link)
                              {
                                $linkId = $link->getId();
                              }

                            $params['qty'] = 1;
                            $params['links'] = array(
                                $linkId
                            );
                            $request = new Varien_Object();
                            $request->setData($params);

                            // Create the item for the quote

                            $quoteItem = Mage::getModel('sales/quote_item')->setProduct($product, $request);
                            $quoteItem->setQuote($quote);
                            $quoteItem->setQty('1');
                            $quoteItem->save();
                            $orderItem = Mage::getModel('sales/convert_quote')->itemToOrderItem($quoteItem)->setOrderID($order->getId())->save($orderId);
                             $templateId = Mage::getStoreConfig('subscription/subscription/email_tmp_id');

                            // Set sender information

                            $senderName = Mage::getStoreConfig('trans_email/ident_sales/name');
                            $senderEmail = Mage::getStoreConfig('trans_email/ident_sales/email');
                            $sender = array(
                                'name' => $senderName,
                                'email' => $senderEmail
                            );

                            // Set recepient information

                            $recepientEmail = $customerEmailId;
                            $recepientName = $customerFname . " " . $customerLname;

                            // Get Store ID

                            $storeId = Mage::app()->getStore()->getId();

                            // Set variables that can be used in email template

                            $vars = array(
                                'customer_name' => $customerFname,
                                'product_name' => $downloadableProductName,
                                'product_url' => $downloadableProductUrl,
                                'digital_subscription_name' => $virtualProductName
                            );
                            $translate = Mage::getSingleton('core/translate');

                            // Send Transactional Email

                            Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
                            $translate->setTranslateInline(true);
                            }                          
                          }
                      }
                  }
              }
          }
        }
    }

