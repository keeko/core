<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_group' table.
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
class GroupTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.GroupTableMap';

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
        $this->setName('keeko_group');
        $this->setPhpName('Group');
        $this->setClassname('keeko\\core\\entities\\Group');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'keeko_user', 'id', false, 10, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 64, null);
        $this->addColumn('is_guest', 'IsGuest', 'BOOLEAN', false, 1, null);
        $this->addColumn('is_default', 'IsDefault', 'BOOLEAN', false, 1, null);
        $this->addColumn('is_active', 'IsActive', 'BOOLEAN', false, 1, true);
        $this->addColumn('is_system', 'IsSystem', 'BOOLEAN', false, 1, false);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'keeko\\core\\entities\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'RESTRICT', null);
        $this->addRelation('GroupUser', 'keeko\\core\\entities\\GroupUser', RelationMap::ONE_TO_MANY, array('id' => 'group_id', ), 'RESTRICT', null, 'GroupUsers');
        $this->addRelation('GroupAction', 'keeko\\core\\entities\\GroupAction', RelationMap::ONE_TO_MANY, array('id' => 'group_id', ), 'RESTRICT', null, 'GroupActions');
    } // buildRelations()

} // GroupTableMap
