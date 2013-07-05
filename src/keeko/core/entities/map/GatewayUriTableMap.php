<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_gateway_uri' table.
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
class GatewayUriTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.GatewayUriTableMap';

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
        $this->setName('keeko_gateway_uri');
        $this->setPhpName('GatewayUri');
        $this->setClassname('keeko\\core\\entities\\GatewayUri');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('httphost', 'Httphost', 'VARCHAR', true, 255, null);
        $this->addColumn('basepath', 'Basepath', 'VARCHAR', true, 255, null);
        $this->addColumn('secure', 'Secure', 'BOOLEAN', false, 1, null);
        $this->addForeignKey('gateway_id', 'GatewayId', 'INTEGER', 'keeko_gateway', 'id', true, 10, null);
        $this->addForeignKey('localization_id', 'LocalizationId', 'INTEGER', 'keeko_localization', 'id', true, 10, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Gateway', 'keeko\\core\\entities\\Gateway', RelationMap::MANY_TO_ONE, array('gateway_id' => 'id', ), 'RESTRICT', null);
        $this->addRelation('Localization', 'keeko\\core\\entities\\Localization', RelationMap::MANY_TO_ONE, array('localization_id' => 'id', ), 'RESTRICT', null);
    } // buildRelations()

} // GatewayUriTableMap
