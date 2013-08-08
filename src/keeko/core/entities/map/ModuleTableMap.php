<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_module' table.
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
class ModuleTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.ModuleTableMap';

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
        $this->setName('keeko_module');
        $this->setPhpName('Module');
        $this->setClassname('keeko\\core\\entities\\Module');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('classname', 'Classname', 'VARCHAR', false, 255, null);
        $this->addColumn('activated_version', 'ActivatedVersion', 'VARCHAR', false, 50, null);
        $this->addForeignKey('package_id', 'PackageId', 'INTEGER', 'keeko_package', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Package', 'keeko\\core\\entities\\Package', RelationMap::MANY_TO_ONE, array('package_id' => 'id', ), null, null);
        $this->addRelation('Action', 'keeko\\core\\entities\\Action', RelationMap::ONE_TO_MANY, array('id' => 'module_id', ), 'CASCADE', null, 'Actions');
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
  'to' => 'package',
),
        );
    } // getBehaviors()

} // ModuleTableMap
