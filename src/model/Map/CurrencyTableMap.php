<?php

namespace keeko\core\model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use keeko\core\model\Currency;
use keeko\core\model\CurrencyQuery;


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
 */
class CurrencyTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.model.Map.CurrencyTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'keeko_currency';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Currency';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'keeko.core.model.Currency';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the ISO_NR field
     */
    const COL_ISO_NR = 'keeko_currency.ISO_NR';

    /**
     * the column name for the ISO3 field
     */
    const COL_ISO3 = 'keeko_currency.ISO3';

    /**
     * the column name for the EN_NAME field
     */
    const COL_EN_NAME = 'keeko_currency.EN_NAME';

    /**
     * the column name for the SYMBOL_LEFT field
     */
    const COL_SYMBOL_LEFT = 'keeko_currency.SYMBOL_LEFT';

    /**
     * the column name for the SYMBOL_RIGHT field
     */
    const COL_SYMBOL_RIGHT = 'keeko_currency.SYMBOL_RIGHT';

    /**
     * the column name for the DECIMAL_DIGITS field
     */
    const COL_DECIMAL_DIGITS = 'keeko_currency.DECIMAL_DIGITS';

    /**
     * the column name for the SUB_DIVISOR field
     */
    const COL_SUB_DIVISOR = 'keeko_currency.SUB_DIVISOR';

    /**
     * the column name for the SUB_SYMBOL_LEFT field
     */
    const COL_SUB_SYMBOL_LEFT = 'keeko_currency.SUB_SYMBOL_LEFT';

    /**
     * the column name for the SUB_SYMBOL_RIGHT field
     */
    const COL_SUB_SYMBOL_RIGHT = 'keeko_currency.SUB_SYMBOL_RIGHT';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('IsoNr', 'Iso3', 'EnName', 'SymbolLeft', 'SymbolRight', 'DecimalDigits', 'SubDivisor', 'SubSymbolLeft', 'SubSymbolRight', ),
        self::TYPE_STUDLYPHPNAME => array('isoNr', 'iso3', 'enName', 'symbolLeft', 'symbolRight', 'decimalDigits', 'subDivisor', 'subSymbolLeft', 'subSymbolRight', ),
        self::TYPE_COLNAME       => array(CurrencyTableMap::COL_ISO_NR, CurrencyTableMap::COL_ISO3, CurrencyTableMap::COL_EN_NAME, CurrencyTableMap::COL_SYMBOL_LEFT, CurrencyTableMap::COL_SYMBOL_RIGHT, CurrencyTableMap::COL_DECIMAL_DIGITS, CurrencyTableMap::COL_SUB_DIVISOR, CurrencyTableMap::COL_SUB_SYMBOL_LEFT, CurrencyTableMap::COL_SUB_SYMBOL_RIGHT, ),
        self::TYPE_RAW_COLNAME   => array('COL_ISO_NR', 'COL_ISO3', 'COL_EN_NAME', 'COL_SYMBOL_LEFT', 'COL_SYMBOL_RIGHT', 'COL_DECIMAL_DIGITS', 'COL_SUB_DIVISOR', 'COL_SUB_SYMBOL_LEFT', 'COL_SUB_SYMBOL_RIGHT', ),
        self::TYPE_FIELDNAME     => array('iso_nr', 'iso3', 'en_name', 'symbol_left', 'symbol_right', 'decimal_digits', 'sub_divisor', 'sub_symbol_left', 'sub_symbol_right', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IsoNr' => 0, 'Iso3' => 1, 'EnName' => 2, 'SymbolLeft' => 3, 'SymbolRight' => 4, 'DecimalDigits' => 5, 'SubDivisor' => 6, 'SubSymbolLeft' => 7, 'SubSymbolRight' => 8, ),
        self::TYPE_STUDLYPHPNAME => array('isoNr' => 0, 'iso3' => 1, 'enName' => 2, 'symbolLeft' => 3, 'symbolRight' => 4, 'decimalDigits' => 5, 'subDivisor' => 6, 'subSymbolLeft' => 7, 'subSymbolRight' => 8, ),
        self::TYPE_COLNAME       => array(CurrencyTableMap::COL_ISO_NR => 0, CurrencyTableMap::COL_ISO3 => 1, CurrencyTableMap::COL_EN_NAME => 2, CurrencyTableMap::COL_SYMBOL_LEFT => 3, CurrencyTableMap::COL_SYMBOL_RIGHT => 4, CurrencyTableMap::COL_DECIMAL_DIGITS => 5, CurrencyTableMap::COL_SUB_DIVISOR => 6, CurrencyTableMap::COL_SUB_SYMBOL_LEFT => 7, CurrencyTableMap::COL_SUB_SYMBOL_RIGHT => 8, ),
        self::TYPE_RAW_COLNAME   => array('COL_ISO_NR' => 0, 'COL_ISO3' => 1, 'COL_EN_NAME' => 2, 'COL_SYMBOL_LEFT' => 3, 'COL_SYMBOL_RIGHT' => 4, 'COL_DECIMAL_DIGITS' => 5, 'COL_SUB_DIVISOR' => 6, 'COL_SUB_SYMBOL_LEFT' => 7, 'COL_SUB_SYMBOL_RIGHT' => 8, ),
        self::TYPE_FIELDNAME     => array('iso_nr' => 0, 'iso3' => 1, 'en_name' => 2, 'symbol_left' => 3, 'symbol_right' => 4, 'decimal_digits' => 5, 'sub_divisor' => 6, 'sub_symbol_left' => 7, 'sub_symbol_right' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
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
        $this->setClassName('\\keeko\\core\\model\\Currency');
        $this->setPackage('keeko.core.model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('ISO_NR', 'IsoNr', 'INTEGER', true, null, null);
        $this->addColumn('ISO3', 'Iso3', 'CHAR', true, 3, null);
        $this->addColumn('EN_NAME', 'EnName', 'VARCHAR', false, 45, null);
        $this->addColumn('SYMBOL_LEFT', 'SymbolLeft', 'VARCHAR', false, 45, null);
        $this->addColumn('SYMBOL_RIGHT', 'SymbolRight', 'VARCHAR', false, 45, null);
        $this->addColumn('DECIMAL_DIGITS', 'DecimalDigits', 'INTEGER', false, null, null);
        $this->addColumn('SUB_DIVISOR', 'SubDivisor', 'INTEGER', false, null, null);
        $this->addColumn('SUB_SYMBOL_LEFT', 'SubSymbolLeft', 'VARCHAR', false, 45, null);
        $this->addColumn('SUB_SYMBOL_RIGHT', 'SubSymbolRight', 'VARCHAR', false, 45, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', '\\keeko\\core\\model\\Country', RelationMap::ONE_TO_MANY, array('iso_nr' => 'currency_iso_nr', ), null, null, 'Countries');
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IsoNr', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IsoNr', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('IsoNr', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? CurrencyTableMap::CLASS_DEFAULT : CurrencyTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Currency object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CurrencyTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CurrencyTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CurrencyTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CurrencyTableMap::OM_CLASS;
            /** @var Currency $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CurrencyTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = CurrencyTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CurrencyTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Currency $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CurrencyTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(CurrencyTableMap::COL_ISO_NR);
            $criteria->addSelectColumn(CurrencyTableMap::COL_ISO3);
            $criteria->addSelectColumn(CurrencyTableMap::COL_EN_NAME);
            $criteria->addSelectColumn(CurrencyTableMap::COL_SYMBOL_LEFT);
            $criteria->addSelectColumn(CurrencyTableMap::COL_SYMBOL_RIGHT);
            $criteria->addSelectColumn(CurrencyTableMap::COL_DECIMAL_DIGITS);
            $criteria->addSelectColumn(CurrencyTableMap::COL_SUB_DIVISOR);
            $criteria->addSelectColumn(CurrencyTableMap::COL_SUB_SYMBOL_LEFT);
            $criteria->addSelectColumn(CurrencyTableMap::COL_SUB_SYMBOL_RIGHT);
        } else {
            $criteria->addSelectColumn($alias . '.ISO_NR');
            $criteria->addSelectColumn($alias . '.ISO3');
            $criteria->addSelectColumn($alias . '.EN_NAME');
            $criteria->addSelectColumn($alias . '.SYMBOL_LEFT');
            $criteria->addSelectColumn($alias . '.SYMBOL_RIGHT');
            $criteria->addSelectColumn($alias . '.DECIMAL_DIGITS');
            $criteria->addSelectColumn($alias . '.SUB_DIVISOR');
            $criteria->addSelectColumn($alias . '.SUB_SYMBOL_LEFT');
            $criteria->addSelectColumn($alias . '.SUB_SYMBOL_RIGHT');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(CurrencyTableMap::DATABASE_NAME)->getTable(CurrencyTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CurrencyTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CurrencyTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CurrencyTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Currency or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Currency object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Currency) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CurrencyTableMap::DATABASE_NAME);
            $criteria->add(CurrencyTableMap::COL_ISO_NR, (array) $values, Criteria::IN);
        }

        $query = CurrencyQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CurrencyTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CurrencyTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the keeko_currency table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CurrencyQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Currency or Criteria object.
     *
     * @param mixed               $criteria Criteria or Currency object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Currency object
        }


        // Set the correct dbName
        $query = CurrencyQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CurrencyTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CurrencyTableMap::buildTableMap();
