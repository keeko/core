<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_design' table.
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
class DesignTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.DesignTableMap';

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
        $this->setName('keeko_design');
        $this->setPhpName('Design');
        $this->setClassname('keeko\\core\\entities\\Design');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('package_id', 'PackageId', 'INTEGER', 'keeko_package', 'id', false, null, null);
        $this->addForeignKey('application_type_id', 'ApplicationTypeId', 'INTEGER', 'keeko_application_type', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Package', 'keeko\\core\\entities\\Package', RelationMap::MANY_TO_ONE, array('package_id' => 'id', ), null, null);
        $this->addRelation('ApplicationType', 'keeko\\core\\entities\\ApplicationType', RelationMap::MANY_TO_ONE, array('application_type_id' => 'id', ), null, null);
        $this->addRelation('Layout', 'keeko\\core\\entities\\Layout', RelationMap::ONE_TO_MANY, array('id' => 'design_id', ), null, null, 'Layouts');
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

} // DesignTableMap
