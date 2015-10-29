<?php

/**
 * BaseShopCart
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property Doctrine_Collection $CartProducts
 * @property Doctrine_Collection $ShopCartProductRelations
 * 
 * @method Doctrine_Collection getCartProducts()             Returns the current record's "CartProducts" collection
 * @method Doctrine_Collection getShopCartProductRelations() Returns the current record's "ShopCartProductRelations" collection
 * @method ShopCart            setCartProducts()             Sets the current record's "CartProducts" collection
 * @method ShopCart            setShopCartProductRelations() Sets the current record's "ShopCartProductRelations" collection
 * 
 * @package    shop
 * @subpackage model
 * @author     Dmitriy
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseShopCart extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('shop_cart');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('CartProduct as CartProducts', array(
             'refClass' => 'ShopCartProductRelations',
             'local' => 'cart_id',
             'foreign' => 'product_id'));

        $this->hasMany('ShopCartProductRelations', array(
             'local' => 'id',
             'foreign' => 'cart_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}