<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_action' table.
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
class ActionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.ActionTableMap';

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
        $this->setName('keeko_action');
        $this->setPhpName('Action');
        $this->setClassname('keeko\\core\\entities\\Action');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('module_id', 'ModuleId', 'INTEGER', 'keeko_module', 'id', true, 10, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 32, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Module', 'keeko\\core\\entities\\Module', RelationMap::MANY_TO_ONE, array('module_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('GroupAction', 'keeko\\core\\entities\\GroupAction', RelationMap::ONE_TO_MANY, array('id' => 'action_id', ), 'RESTRICT', null, 'GroupActions');
    } // buildRelations()

} // ActionTableMap
