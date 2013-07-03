<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_currency' table.
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
class CurrencyTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.CurrencyTableMap';

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
        $this->setName('keeko_currency');
        $this->setPhpName('Currency');
        $this->setClassname('keeko\\core\\entities\\Currency');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('iso_nr', 'IsoNr', 'INTEGER', true, null, null);
        $this->addColumn('iso3', 'Iso3', 'CHAR', true, 3, null);
        $this->addColumn('en_name', 'EnName', 'VARCHAR', false, 45, null);
        $this->addColumn('symbol_left', 'SymbolLeft', 'VARCHAR', false, 45, null);
        $this->addColumn('symbol_right', 'SymbolRight', 'VARCHAR', false, 45, null);
        $this->addColumn('decimal_digits', 'DecimalDigits', 'INTEGER', false, null, null);
        $this->addColumn('sub_divisor', 'SubDivisor', 'INTEGER', false, null, null);
        $this->addColumn('sub_symbol_left', 'SubSymbolLeft', 'VARCHAR', false, 45, null);
        $this->addColumn('sub_symbol_right', 'SubSymbolRight', 'VARCHAR', false, 45, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', 'keeko\\core\\entities\\Country', RelationMap::ONE_TO_MANY, array('iso_nr' => 'currency_iso_nr', ), null, null, 'Countrys');
    } // buildRelations()

} // CurrencyTableMap
