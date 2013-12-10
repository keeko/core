<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_block_grid' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.keeko.core.entities.map
 */
class BlockGridTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.BlockGridTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('keeko_block_grid');
        $this->setPhpName('BlockGrid');
        $this->setClassname('keeko\\core\\entities\\BlockGrid');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('block_item_id', 'BlockItemId', 'INTEGER', 'keeko_block_item', 'id', false, null, null);
        $this->addColumn('span', 'Span', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('BlockItem', 'keeko\\core\\entities\\BlockItem', RelationMap::MANY_TO_ONE, array('block_item_id' => 'id', ), null, null);
        $this->addRelation('BlockGridExtraProperty', 'keeko\\core\\entities\\BlockGridExtraProperty', RelationMap::ONE_TO_MANY, array('id' => 'keeko_block_grid_id', ), 'CASCADE', null, 'BlockGridExtraPropertys');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'delegate' =>  array (
  'to' => 'block_item',
),
            'extra_properties' =>  array (
  'properties_table' => 'block_grid_properties',
  'property_name' => 'property',
  'property_name_column' => 'property_name',
  'property_value_column' => 'property_value',
  'default_properties' => '',
  'normalize' => 'true',
  'throw_error' => 'true',
),
        );
    } // getBehaviors()

} // BlockGridTableMap
