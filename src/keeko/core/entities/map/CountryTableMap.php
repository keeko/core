<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_country' table.
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
class CountryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.CountryTableMap';

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
        $this->setName('keeko_country');
        $this->setPhpName('Country');
        $this->setClassname('keeko\\core\\entities\\Country');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('iso_nr', 'IsoNr', 'INTEGER', true, null, null);
        $this->addColumn('alpha_2', 'Alpha2', 'CHAR', false, 2, null);
        $this->addColumn('alpha_3', 'Alpha3', 'CHAR', false, 3, null);
        $this->addColumn('ioc', 'Ioc', 'CHAR', false, 3, null);
        $this->addColumn('capital', 'Capital', 'VARCHAR', false, 128, null);
        $this->addColumn('tld', 'Tld', 'VARCHAR', false, 3, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 16, null);
        $this->addForeignKey('territory_iso_nr', 'TerritoryIsoNr', 'INTEGER', 'keeko_territory', 'iso_nr', true, null, null);
        $this->addForeignKey('currency_iso_nr', 'CurrencyIsoNr', 'INTEGER', 'keeko_currency', 'iso_nr', true, null, null);
        $this->addColumn('official_local_name', 'OfficialLocalName', 'VARCHAR', false, 128, null);
        $this->addColumn('official_en_name', 'OfficialEnName', 'VARCHAR', false, 128, null);
        $this->addColumn('short_local_name', 'ShortLocalName', 'VARCHAR', false, 128, null);
        $this->addColumn('short_en_name', 'ShortEnName', 'VARCHAR', false, 128, null);
        $this->addColumn('bbox_sw_lat', 'BboxSwLat', 'FLOAT', false, null, null);
        $this->addColumn('bbox_sw_lng', 'BboxSwLng', 'FLOAT', false, null, null);
        $this->addColumn('bbox_ne_lat', 'BboxNeLat', 'FLOAT', false, null, null);
        $this->addColumn('bbox_ne_lng', 'BboxNeLng', 'FLOAT', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Territory', 'keeko\\core\\entities\\Territory', RelationMap::MANY_TO_ONE, array('territory_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('Currency', 'keeko\\core\\entities\\Currency', RelationMap::MANY_TO_ONE, array('currency_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('Localization', 'keeko\\core\\entities\\Localization', RelationMap::ONE_TO_MANY, array('iso_nr' => 'country_iso_nr', ), null, null, 'Localizations');
        $this->addRelation('Subdivision', 'keeko\\core\\entities\\Subdivision', RelationMap::ONE_TO_MANY, array('iso_nr' => 'country_iso_nr', ), null, null, 'Subdivisions');
        $this->addRelation('User', 'keeko\\core\\entities\\User', RelationMap::ONE_TO_MANY, array('iso_nr' => 'country_iso_nr', ), null, null, 'Users');
    } // buildRelations()

} // CountryTableMap
