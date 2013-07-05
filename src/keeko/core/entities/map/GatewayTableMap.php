<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_gateway' table.
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
class GatewayTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.GatewayTableMap';

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
        $this->setName('keeko_gateway');
        $this->setPhpName('Gateway');
        $this->setClassname('keeko\\core\\entities\\Gateway');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 255, null);
        $this->addForeignKey('application_id', 'ApplicationId', 'INTEGER', 'keeko_application', 'id', true, 10, null);
        $this->addForeignKey('router_id', 'RouterId', 'INTEGER', 'keeko_router', 'id', true, 10, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Application', 'keeko\\core\\entities\\Application', RelationMap::MANY_TO_ONE, array('application_id' => 'id', ), 'RESTRICT', null);
        $this->addRelation('Router', 'keeko\\core\\entities\\Router', RelationMap::MANY_TO_ONE, array('router_id' => 'id', ), 'RESTRICT', null);
        $this->addRelation('GatewayUri', 'keeko\\core\\entities\\GatewayUri', RelationMap::ONE_TO_MANY, array('id' => 'gateway_id', ), 'RESTRICT', null, 'GatewayUris');
        $this->addRelation('GatewayExtraProperty', 'keeko\\core\\entities\\GatewayExtraProperty', RelationMap::ONE_TO_MANY, array('id' => 'keeko_gateway_id', ), 'CASCADE', null, 'GatewayExtraPropertys');
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
            'extra_properties' =>  array (
  'properties_table' => NULL,
  'property_name_column' => 'property_name',
  'property_value_column' => 'property_value',
  'default_properties' => '',
  'normalize' => 'true',
  'throw_error' => 'true',
),
        );
    } // getBehaviors()

} // GatewayTableMap
