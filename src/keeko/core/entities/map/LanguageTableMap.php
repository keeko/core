<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_language' table.
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
class LanguageTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.LanguageTableMap';

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
        $this->setName('keeko_language');
        $this->setPhpName('Language');
        $this->setClassname('keeko\\core\\entities\\Language');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('alpha_2', 'Alpha2', 'VARCHAR', false, 2, null);
        $this->addColumn('alpha_3T', 'Alpha3T', 'VARCHAR', false, 3, null);
        $this->addColumn('alpha_3B', 'Alpha3B', 'VARCHAR', false, 3, null);
        $this->addColumn('alpha_3', 'Alpha3', 'VARCHAR', false, 3, null);
        $this->addColumn('local_name', 'LocalName', 'VARCHAR', false, 128, null);
        $this->addColumn('en_name', 'EnName', 'VARCHAR', false, 128, null);
        $this->addColumn('collate', 'Collate', 'VARCHAR', false, 10, null);
        $this->addForeignKey('scope_id', 'ScopeId', 'INTEGER', 'keeko_language_scope', 'id', false, 10, null);
        $this->addForeignKey('type_id', 'TypeId', 'INTEGER', 'keeko_language_type', 'id', false, 10, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('LanguageScope', 'keeko\\core\\entities\\LanguageScope', RelationMap::MANY_TO_ONE, array('scope_id' => 'id', ), null, null);
        $this->addRelation('LanguageType', 'keeko\\core\\entities\\LanguageType', RelationMap::MANY_TO_ONE, array('type_id' => 'id', ), null, null);
        $this->addRelation('Localization', 'keeko\\core\\entities\\Localization', RelationMap::ONE_TO_MANY, array('id' => 'language_id', ), null, null, 'Localizations');
    } // buildRelations()

} // LanguageTableMap
