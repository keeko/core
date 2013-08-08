<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_localization' table.
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
class LocalizationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.LocalizationTableMap';

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
        $this->setName('keeko_localization');
        $this->setPhpName('Localization');
        $this->setClassname('keeko\\core\\entities\\Localization');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('parent_id', 'ParentId', 'INTEGER', 'keeko_localization', 'id', false, 10, null);
        $this->addForeignKey('language_id', 'LanguageId', 'INTEGER', 'keeko_language', 'id', false, 10, null);
        $this->addForeignKey('country_iso_nr', 'CountryIsoNr', 'INTEGER', 'keeko_country', 'iso_nr', false, 10, null);
        $this->addColumn('is_default', 'IsDefault', 'BOOLEAN', false, 1, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('LocalizationRelatedByParentId', 'keeko\\core\\entities\\Localization', RelationMap::MANY_TO_ONE, array('parent_id' => 'id', ), null, null);
        $this->addRelation('Language', 'keeko\\core\\entities\\Language', RelationMap::MANY_TO_ONE, array('language_id' => 'id', ), null, null);
        $this->addRelation('Country', 'keeko\\core\\entities\\Country', RelationMap::MANY_TO_ONE, array('country_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('LocalizationRelatedById', 'keeko\\core\\entities\\Localization', RelationMap::ONE_TO_MANY, array('id' => 'parent_id', ), null, null, 'LocalizationsRelatedById');
        $this->addRelation('ApplicationUri', 'keeko\\core\\entities\\ApplicationUri', RelationMap::ONE_TO_MANY, array('id' => 'localization_id', ), 'RESTRICT', null, 'ApplicationUris');
    } // buildRelations()

} // LocalizationTableMap
