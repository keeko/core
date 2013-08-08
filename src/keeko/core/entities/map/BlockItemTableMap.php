<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_block_item' table.
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
class BlockItemTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.BlockItemTableMap';

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
        $this->setName('keeko_block_item');
        $this->setPhpName('BlockItem');
        $this->setClassname('keeko\\core\\entities\\BlockItem');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('block_id', 'BlockId', 'INTEGER', false, null, null);
        $this->addColumn('parent_id', 'ParentId', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('BlockGrid', 'keeko\\core\\entities\\BlockGrid', RelationMap::ONE_TO_MANY, array('id' => 'block_item_id', ), null, null, 'BlockGrids');
        $this->addRelation('BlockContent', 'keeko\\core\\entities\\BlockContent', RelationMap::ONE_TO_MANY, array('id' => 'block_item_id', ), null, null, 'BlockContents');
    } // buildRelations()

} // BlockItemTableMap
