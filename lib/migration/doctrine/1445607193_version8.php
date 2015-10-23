<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version8 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('category_relations', 'category_relations_category_id_category_id', array(
             'name' => 'category_relations_category_id_category_id',
             'local' => 'category_id',
             'foreign' => 'id',
             'foreignTable' => 'category',
             ));
        $this->createForeignKey('category_translation', 'category_translation_id_category_id', array(
             'name' => 'category_translation_id_category_id',
             'local' => 'id',
             'foreign' => 'id',
             'foreignTable' => 'category',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('category_relations', 'category_relations_category_id', array(
             'fields' => 
             array(
              0 => 'category_id',
             ),
             ));
        $this->addIndex('category_translation', 'category_translation_id', array(
             'fields' => 
             array(
              0 => 'id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('category_relations', 'category_relations_category_id_category_id');
        $this->dropForeignKey('category_translation', 'category_translation_id_category_id');
        $this->removeIndex('category_relations', 'category_relations_category_id', array(
             'fields' => 
             array(
              0 => 'category_id',
             ),
             ));
        $this->removeIndex('category_translation', 'category_translation_id', array(
             'fields' => 
             array(
              0 => 'id',
             ),
             ));
    }
}