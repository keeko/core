<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_subdivision' table.
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
class SubdivisionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.SubdivisionTableMap';

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
        $this->setName('keeko_subdivision');
        $this->setPhpName('Subdivision');
        $this->setClassname('keeko\\core\\entities\\Subdivision');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('iso', 'Iso', 'VARCHAR', false, 45, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 128, null);
        $this->addColumn('local_name', 'LocalName', 'VARCHAR', false, 128, null);
        $this->addColumn('en_name', 'EnName', 'VARCHAR', false, 128, null);
        $this->addColumn('alt_names', 'AltNames', 'VARCHAR', false, 255, null);
        $this->addColumn('parent_id', 'ParentId', 'INTEGER', false, null, null);
        $this->addForeignKey('country_iso_nr', 'CountryIsoNr', 'INTEGER', 'keeko_country', 'iso_nr', true, null, null);
        $this->addForeignKey('subdivision_type_id', 'SubdivisionTypeId', 'INTEGER', 'keeko_subdivision_type', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', 'keeko\\core\\entities\\Country', RelationMap::MANY_TO_ONE, array('country_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('SubdivisionType', 'keeko\\core\\entities\\SubdivisionType', RelationMap::MANY_TO_ONE, array('subdivision_type_id' => 'id', ), null, null);
        $this->addRelation('User', 'keeko\\core\\entities\\User', RelationMap::ONE_TO_MANY, array('id' => 'subdivision_id', ), null, null, 'Users');
    } // buildRelations()

} // SubdivisionTableMap
