<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_layout' table.
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
class LayoutTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.LayoutTableMap';

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
        $this->setName('keeko_layout');
        $this->setPhpName('Layout');
        $this->setClassname('keeko\\core\\entities\\Layout');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 255, null);
        $this->addForeignKey('design_id', 'DesignId', 'INTEGER', 'keeko_design', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Design', 'keeko\\core\\entities\\Design', RelationMap::MANY_TO_ONE, array('design_id' => 'id', ), null, null);
        $this->addRelation('Page', 'keeko\\core\\entities\\Page', RelationMap::ONE_TO_MANY, array('id' => 'layout_id', ), null, null, 'Pages');
        $this->addRelation('Block', 'keeko\\core\\entities\\Block', RelationMap::ONE_TO_MANY, array('id' => 'layout_id', ), null, null, 'Blocks');
    } // buildRelations()

} // LayoutTableMap
