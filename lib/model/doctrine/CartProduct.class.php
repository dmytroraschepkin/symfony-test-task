<?php

/**
 * CartProduct
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    shop
 * @subpackage model
 * @author     Dmitriy
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class CartProduct extends BaseCartProduct
{
  /**
   * ShopProduct item for current CartProduct
   *
   * @var null|ShopProduct
   */
  protected $shopProduct = null;

  /**
   * Get ShopProduct item based on current CartProduct one
   *
   * @return null|ShopProduct
   */
  public function getShopProduct()
  {
    if(!$this->shopProduct)
    {
      $redis = sfRedis::getClient();
      $this->shopProduct = unserialize($redis->hget('shopProduct', $this->getShopProductId()));
      if (!$this->shopProduct)
      {
        // Find ShopProduct in case no ShopProduct saved locally nor in cache
        $this->shopProduct = ShopProduct::findOneByTypeAndId($this->getType(), $this->getWhmcsPid());

        // Saving shopProduct to Redis cache
        $redis->hset('shopProduct', $this->getShopProductId(), serialize($this->shopProduct));
        // Using config value or 30 days (60 * 60 * 24 * 30)
        $shopProductExpiration = sfConfig::get('app_cache_expiration_shop_products', 60 * 60 * 24 * 30);
        $redis->expire('shopProduct', $shopProductExpiration);
      }
    }
    return $this->shopProduct;
  }

  public function getShopProductId()
  {
    return $this->getType() . $this->getWhmcsPid();
  }

  /**
   * Get Total CartProduct prices (from ShopProduct item)
   *
   * @param $currencyId
   * @return float Price
   */
  public function getTotalPrice($currencyId)
  {
    if($this->getShopProduct())
    {
      // Get Price object from ShopProduct item for provided currency and current billing period
      $price = $this->getShopProduct()->getPrice($currencyId, $this->getPeriod());
      if($price)
      {
        // If Price object is found - get it's total value
        return $price->getTotal();
      }
    }
    return null;
  }

  public function __toString()
  {
    return $this->getShopProduct()->name;
  }

  /**
   * Return value of requested field from Params
   *
   * @param $field
   * @return null|string
   */
  public function getParamValue($field)
  {
    $params = $this->getDecodedParams();
    return (!empty($params->$field) && !is_array($params->$field))? $params->$field : null;
  }

  /**
   * @return array|StdClass List of param values
   */
  public function getDecodedParams()
  {
    $result = json_decode($this->getParams());
    return ($result)? : [];
  }

  /**
   * List of additional field keys based on product type
   *
   * @return array
   */
  public function getAdditionalFieldKeys()
  {
    $additionalFieldKeys = [];
    switch($this->getType())
    {
      case ShopProduct::TYPE_PRODUCT:
        if($this->getShopProduct()->productType == ShopProduct::PRODUCT_TYPE_SERVER)
        {
          $additionalFieldKeys = PluginWhmcsConnection::getServerAdditionalFields();
        }
        break;
      case ShopProduct::TYPE_DOMAIN:
        $additionalFieldKeys = PluginWhmcsConnection::getDomainAdditionalFields();
        break;
    }
    return $additionalFieldKeys;
  }

  /**
   * Send Changed event after insert
   * Used in Cart total recalculation
   *
   * @param $event
   */
  public function postInsert($event)
  {
    $this->sendChangedEvent();
  }

  /**
   * Send Changed event after update
   * Used in Cart total recalculation
   *
   * @param $event
   */
  public function postUpdate($event)
  {
    $this->sendChangedEvent();
  }

  /**
   * Send Changed event after delete
   * Used in Cart total recalculation
   *
   * @param $event
   */
  public function postDelete($event)
  {
    $this->sendChangedEvent();
  }

  /**
   * Send Changed event
   * Used in Cart total recalculation
   */
  protected function sendChangedEvent()
  {
    // TODO: Split into separate events
    $dispatcher = ProjectConfiguration::getActive()->getEventDispatcher();
    $dispatcher->notify(new sfEvent($this, 'cartProduct.changed'));
  }
}
