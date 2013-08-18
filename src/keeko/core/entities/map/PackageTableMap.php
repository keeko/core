<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_package' table.
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
class PackageTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.PackageTableMap';

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
        $this->setName('keeko_package');
        $this->setPhpName('Package');
        $this->setClassname('keeko\\core\\entities\\Package');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('installed_version', 'InstalledVersion', 'VARCHAR', false, 50, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ApplicationType', 'keeko\\core\\entities\\ApplicationType', RelationMap::ONE_TO_MANY, array('id' => 'package_id', ), null, null, 'ApplicationTypes');
        $this->addRelation('Design', 'keeko\\core\\entities\\Design', RelationMap::ONE_TO_MANY, array('id' => 'package_id', ), null, null, 'Designs');
        $this->addRelation('Module', 'keeko\\core\\entities\\Module', RelationMap::ONE_TO_MANY, array('id' => 'package_id', ), null, null, 'Modules');
    } // buildRelations()

} // PackageTableMap
