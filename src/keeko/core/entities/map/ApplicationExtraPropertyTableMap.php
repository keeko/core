<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_application_params' table.
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
class ApplicationExtraPropertyTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.ApplicationExtraPropertyTableMap';

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
        $this->setName('keeko_application_params');
        $this->setPhpName('ApplicationExtraProperty');
        $this->setClassname('keeko\\core\\entities\\ApplicationExtraProperty');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('property_name', 'PropertyName', 'VARCHAR', true, 255, null);
        $this->addColumn('property_value', 'PropertyValue', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('keeko_application_id', 'KeekoApplicationId', 'INTEGER', 'keeko_application', 'id', true, 10, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Application', 'keeko\\core\\entities\\Application', RelationMap::MANY_TO_ONE, array('keeko_application_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // ApplicationExtraPropertyTableMap
