<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_group_user' table.
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
class GroupUserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.GroupUserTableMap';

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
        $this->setName('keeko_group_user');
        $this->setPhpName('GroupUser');
        $this->setClassname('keeko\\core\\entities\\GroupUser');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('user_id', 'UserId', 'INTEGER' , 'keeko_user', 'id', true, 10, null);
        $this->addForeignPrimaryKey('group_id', 'GroupId', 'INTEGER' , 'keeko_group', 'id', true, 10, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Group', 'keeko\\core\\entities\\Group', RelationMap::MANY_TO_ONE, array('group_id' => 'id', ), 'RESTRICT', null);
        $this->addRelation('User', 'keeko\\core\\entities\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'RESTRICT', null);
    } // buildRelations()

} // GroupUserTableMap
