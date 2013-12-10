<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_application' table.
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
class ApplicationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.ApplicationTableMap';

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
        $this->setName('keeko_application');
        $this->setPhpName('Application');
        $this->setClassname('keeko\\core\\entities\\Application');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 255, null);
        $this->addForeignKey('application_type_id', 'ApplicationTypeId', 'INTEGER', 'keeko_application_type', 'id', true, 10, null);
        $this->addForeignKey('router_id', 'RouterId', 'INTEGER', 'keeko_router', 'id', true, 10, null);
        $this->addForeignKey('design_id', 'DesignId', 'INTEGER', 'keeko_design', 'id', true, null, null);
        $this->addForeignKey('package_id', 'PackageId', 'INTEGER', 'keeko_package', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ApplicationType', 'keeko\\core\\entities\\ApplicationType', RelationMap::MANY_TO_ONE, array('application_type_id' => 'id', ), null, null);
        $this->addRelation('Package', 'keeko\\core\\entities\\Package', RelationMap::MANY_TO_ONE, array('package_id' => 'id', ), null, null);
        $this->addRelation('Router', 'keeko\\core\\entities\\Router', RelationMap::MANY_TO_ONE, array('router_id' => 'id', ), null, null);
        $this->addRelation('Design', 'keeko\\core\\entities\\Design', RelationMap::MANY_TO_ONE, array('design_id' => 'id', ), null, null);
        $this->addRelation('ApplicationUri', 'keeko\\core\\entities\\ApplicationUri', RelationMap::ONE_TO_MANY, array('id' => 'application_id', ), null, null, 'ApplicationUris');
        $this->addRelation('Page', 'keeko\\core\\entities\\Page', RelationMap::ONE_TO_MANY, array('id' => 'application_id', ), null, null, 'Pages');
        $this->addRelation('ApplicationExtraProperty', 'keeko\\core\\entities\\ApplicationExtraProperty', RelationMap::ONE_TO_MANY, array('id' => 'keeko_application_id', ), 'CASCADE', null, 'ApplicationExtraPropertys');
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
            'extra_properties' =>  array (
  'properties_table' => 'application_params',
  'property_name' => 'property',
  'property_name_column' => 'property_name',
  'property_value_column' => 'property_value',
  'default_properties' => '',
  'normalize' => 'false',
  'throw_error' => 'true',
),
        );
    } // getBehaviors()

} // ApplicationTableMap
