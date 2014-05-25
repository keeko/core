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
use keeko\core\model\Country;
use keeko\core\model\CountryQuery;


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
 */
class CountryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.model.Map.CountryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'keeko_country';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Country';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'keeko.core.model.Country';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 17;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 17;

    /**
     * the column name for the ISO_NR field
     */
    const COL_ISO_NR = 'keeko_country.ISO_NR';

    /**
     * the column name for the ALPHA_2 field
     */
    const COL_ALPHA_2 = 'keeko_country.ALPHA_2';

    /**
     * the column name for the ALPHA_3 field
     */
    const COL_ALPHA_3 = 'keeko_country.ALPHA_3';

    /**
     * the column name for the IOC field
     */
    const COL_IOC = 'keeko_country.IOC';

    /**
     * the column name for the CAPITAL field
     */
    const COL_CAPITAL = 'keeko_country.CAPITAL';

    /**
     * the column name for the TLD field
     */
    const COL_TLD = 'keeko_country.TLD';

    /**
     * the column name for the PHONE field
     */
    const COL_PHONE = 'keeko_country.PHONE';

    /**
     * the column name for the TERRITORY_ISO_NR field
     */
    const COL_TERRITORY_ISO_NR = 'keeko_country.TERRITORY_ISO_NR';

    /**
     * the column name for the CURRENCY_ISO_NR field
     */
    const COL_CURRENCY_ISO_NR = 'keeko_country.CURRENCY_ISO_NR';

    /**
     * the column name for the OFFICIAL_LOCAL_NAME field
     */
    const COL_OFFICIAL_LOCAL_NAME = 'keeko_country.OFFICIAL_LOCAL_NAME';

    /**
     * the column name for the OFFICIAL_EN_NAME field
     */
    const COL_OFFICIAL_EN_NAME = 'keeko_country.OFFICIAL_EN_NAME';

    /**
     * the column name for the SHORT_LOCAL_NAME field
     */
    const COL_SHORT_LOCAL_NAME = 'keeko_country.SHORT_LOCAL_NAME';

    /**
     * the column name for the SHORT_EN_NAME field
     */
    const COL_SHORT_EN_NAME = 'keeko_country.SHORT_EN_NAME';

    /**
     * the column name for the BBOX_SW_LAT field
     */
    const COL_BBOX_SW_LAT = 'keeko_country.BBOX_SW_LAT';

    /**
     * the column name for the BBOX_SW_LNG field
     */
    const COL_BBOX_SW_LNG = 'keeko_country.BBOX_SW_LNG';

    /**
     * the column name for the BBOX_NE_LAT field
     */
    const COL_BBOX_NE_LAT = 'keeko_country.BBOX_NE_LAT';

    /**
     * the column name for the BBOX_NE_LNG field
     */
    const COL_BBOX_NE_LNG = 'keeko_country.BBOX_NE_LNG';

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
        self::TYPE_PHPNAME       => array('IsoNr', 'Alpha2', 'Alpha3', 'Ioc', 'Capital', 'Tld', 'Phone', 'TerritoryIsoNr', 'CurrencyIsoNr', 'OfficialLocalName', 'OfficialEnName', 'ShortLocalName', 'ShortEnName', 'BboxSwLat', 'BboxSwLng', 'BboxNeLat', 'BboxNeLng', ),
        self::TYPE_STUDLYPHPNAME => array('isoNr', 'alpha2', 'alpha3', 'ioc', 'capital', 'tld', 'phone', 'territoryIsoNr', 'currencyIsoNr', 'officialLocalName', 'officialEnName', 'shortLocalName', 'shortEnName', 'bboxSwLat', 'bboxSwLng', 'bboxNeLat', 'bboxNeLng', ),
        self::TYPE_COLNAME       => array(CountryTableMap::COL_ISO_NR, CountryTableMap::COL_ALPHA_2, CountryTableMap::COL_ALPHA_3, CountryTableMap::COL_IOC, CountryTableMap::COL_CAPITAL, CountryTableMap::COL_TLD, CountryTableMap::COL_PHONE, CountryTableMap::COL_TERRITORY_ISO_NR, CountryTableMap::COL_CURRENCY_ISO_NR, CountryTableMap::COL_OFFICIAL_LOCAL_NAME, CountryTableMap::COL_OFFICIAL_EN_NAME, CountryTableMap::COL_SHORT_LOCAL_NAME, CountryTableMap::COL_SHORT_EN_NAME, CountryTableMap::COL_BBOX_SW_LAT, CountryTableMap::COL_BBOX_SW_LNG, CountryTableMap::COL_BBOX_NE_LAT, CountryTableMap::COL_BBOX_NE_LNG, ),
        self::TYPE_RAW_COLNAME   => array('COL_ISO_NR', 'COL_ALPHA_2', 'COL_ALPHA_3', 'COL_IOC', 'COL_CAPITAL', 'COL_TLD', 'COL_PHONE', 'COL_TERRITORY_ISO_NR', 'COL_CURRENCY_ISO_NR', 'COL_OFFICIAL_LOCAL_NAME', 'COL_OFFICIAL_EN_NAME', 'COL_SHORT_LOCAL_NAME', 'COL_SHORT_EN_NAME', 'COL_BBOX_SW_LAT', 'COL_BBOX_SW_LNG', 'COL_BBOX_NE_LAT', 'COL_BBOX_NE_LNG', ),
        self::TYPE_FIELDNAME     => array('iso_nr', 'alpha_2', 'alpha_3', 'ioc', 'capital', 'tld', 'phone', 'territory_iso_nr', 'currency_iso_nr', 'official_local_name', 'official_en_name', 'short_local_name', 'short_en_name', 'bbox_sw_lat', 'bbox_sw_lng', 'bbox_ne_lat', 'bbox_ne_lng', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IsoNr' => 0, 'Alpha2' => 1, 'Alpha3' => 2, 'Ioc' => 3, 'Capital' => 4, 'Tld' => 5, 'Phone' => 6, 'TerritoryIsoNr' => 7, 'CurrencyIsoNr' => 8, 'OfficialLocalName' => 9, 'OfficialEnName' => 10, 'ShortLocalName' => 11, 'ShortEnName' => 12, 'BboxSwLat' => 13, 'BboxSwLng' => 14, 'BboxNeLat' => 15, 'BboxNeLng' => 16, ),
        self::TYPE_STUDLYPHPNAME => array('isoNr' => 0, 'alpha2' => 1, 'alpha3' => 2, 'ioc' => 3, 'capital' => 4, 'tld' => 5, 'phone' => 6, 'territoryIsoNr' => 7, 'currencyIsoNr' => 8, 'officialLocalName' => 9, 'officialEnName' => 10, 'shortLocalName' => 11, 'shortEnName' => 12, 'bboxSwLat' => 13, 'bboxSwLng' => 14, 'bboxNeLat' => 15, 'bboxNeLng' => 16, ),
        self::TYPE_COLNAME       => array(CountryTableMap::COL_ISO_NR => 0, CountryTableMap::COL_ALPHA_2 => 1, CountryTableMap::COL_ALPHA_3 => 2, CountryTableMap::COL_IOC => 3, CountryTableMap::COL_CAPITAL => 4, CountryTableMap::COL_TLD => 5, CountryTableMap::COL_PHONE => 6, CountryTableMap::COL_TERRITORY_ISO_NR => 7, CountryTableMap::COL_CURRENCY_ISO_NR => 8, CountryTableMap::COL_OFFICIAL_LOCAL_NAME => 9, CountryTableMap::COL_OFFICIAL_EN_NAME => 10, CountryTableMap::COL_SHORT_LOCAL_NAME => 11, CountryTableMap::COL_SHORT_EN_NAME => 12, CountryTableMap::COL_BBOX_SW_LAT => 13, CountryTableMap::COL_BBOX_SW_LNG => 14, CountryTableMap::COL_BBOX_NE_LAT => 15, CountryTableMap::COL_BBOX_NE_LNG => 16, ),
        self::TYPE_RAW_COLNAME   => array('COL_ISO_NR' => 0, 'COL_ALPHA_2' => 1, 'COL_ALPHA_3' => 2, 'COL_IOC' => 3, 'COL_CAPITAL' => 4, 'COL_TLD' => 5, 'COL_PHONE' => 6, 'COL_TERRITORY_ISO_NR' => 7, 'COL_CURRENCY_ISO_NR' => 8, 'COL_OFFICIAL_LOCAL_NAME' => 9, 'COL_OFFICIAL_EN_NAME' => 10, 'COL_SHORT_LOCAL_NAME' => 11, 'COL_SHORT_EN_NAME' => 12, 'COL_BBOX_SW_LAT' => 13, 'COL_BBOX_SW_LNG' => 14, 'COL_BBOX_NE_LAT' => 15, 'COL_BBOX_NE_LNG' => 16, ),
        self::TYPE_FIELDNAME     => array('iso_nr' => 0, 'alpha_2' => 1, 'alpha_3' => 2, 'ioc' => 3, 'capital' => 4, 'tld' => 5, 'phone' => 6, 'territory_iso_nr' => 7, 'currency_iso_nr' => 8, 'official_local_name' => 9, 'official_en_name' => 10, 'short_local_name' => 11, 'short_en_name' => 12, 'bbox_sw_lat' => 13, 'bbox_sw_lng' => 14, 'bbox_ne_lat' => 15, 'bbox_ne_lng' => 16, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
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
        $this->setName('keeko_country');
        $this->setPhpName('Country');
        $this->setClassName('\\keeko\\core\\model\\Country');
        $this->setPackage('keeko.core.model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('ISO_NR', 'IsoNr', 'INTEGER', true, null, null);
        $this->addColumn('ALPHA_2', 'Alpha2', 'CHAR', false, 2, null);
        $this->addColumn('ALPHA_3', 'Alpha3', 'CHAR', false, 3, null);
        $this->addColumn('IOC', 'Ioc', 'CHAR', false, 3, null);
        $this->addColumn('CAPITAL', 'Capital', 'VARCHAR', false, 128, null);
        $this->addColumn('TLD', 'Tld', 'VARCHAR', false, 3, null);
        $this->addColumn('PHONE', 'Phone', 'VARCHAR', false, 16, null);
        $this->addForeignKey('TERRITORY_ISO_NR', 'TerritoryIsoNr', 'INTEGER', 'keeko_territory', 'ISO_NR', true, null, null);
        $this->addForeignKey('CURRENCY_ISO_NR', 'CurrencyIsoNr', 'INTEGER', 'keeko_currency', 'ISO_NR', true, null, null);
        $this->addColumn('OFFICIAL_LOCAL_NAME', 'OfficialLocalName', 'VARCHAR', false, 128, null);
        $this->addColumn('OFFICIAL_EN_NAME', 'OfficialEnName', 'VARCHAR', false, 128, null);
        $this->addColumn('SHORT_LOCAL_NAME', 'ShortLocalName', 'VARCHAR', false, 128, null);
        $this->addColumn('SHORT_EN_NAME', 'ShortEnName', 'VARCHAR', false, 128, null);
        $this->addColumn('BBOX_SW_LAT', 'BboxSwLat', 'FLOAT', false, null, null);
        $this->addColumn('BBOX_SW_LNG', 'BboxSwLng', 'FLOAT', false, null, null);
        $this->addColumn('BBOX_NE_LAT', 'BboxNeLat', 'FLOAT', false, null, null);
        $this->addColumn('BBOX_NE_LNG', 'BboxNeLng', 'FLOAT', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Territory', '\\keeko\\core\\model\\Territory', RelationMap::MANY_TO_ONE, array('territory_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('Currency', '\\keeko\\core\\model\\Currency', RelationMap::MANY_TO_ONE, array('currency_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('Localization', '\\keeko\\core\\model\\Localization', RelationMap::ONE_TO_MANY, array('iso_nr' => 'country_iso_nr', ), null, null, 'Localizations');
        $this->addRelation('Subdivision', '\\keeko\\core\\model\\Subdivision', RelationMap::ONE_TO_MANY, array('iso_nr' => 'country_iso_nr', ), null, null, 'Subdivisions');
        $this->addRelation('User', '\\keeko\\core\\model\\User', RelationMap::ONE_TO_MANY, array('iso_nr' => 'country_iso_nr', ), null, null, 'Users');
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
        return $withPrefix ? CountryTableMap::CLASS_DEFAULT : CountryTableMap::OM_CLASS;
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
     * @return array           (Country object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CountryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CountryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CountryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CountryTableMap::OM_CLASS;
            /** @var Country $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CountryTableMap::addInstanceToPool($obj, $key);
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
            $key = CountryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CountryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Country $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CountryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CountryTableMap::COL_ISO_NR);
            $criteria->addSelectColumn(CountryTableMap::COL_ALPHA_2);
            $criteria->addSelectColumn(CountryTableMap::COL_ALPHA_3);
            $criteria->addSelectColumn(CountryTableMap::COL_IOC);
            $criteria->addSelectColumn(CountryTableMap::COL_CAPITAL);
            $criteria->addSelectColumn(CountryTableMap::COL_TLD);
            $criteria->addSelectColumn(CountryTableMap::COL_PHONE);
            $criteria->addSelectColumn(CountryTableMap::COL_TERRITORY_ISO_NR);
            $criteria->addSelectColumn(CountryTableMap::COL_CURRENCY_ISO_NR);
            $criteria->addSelectColumn(CountryTableMap::COL_OFFICIAL_LOCAL_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_OFFICIAL_EN_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_SHORT_LOCAL_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_SHORT_EN_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_BBOX_SW_LAT);
            $criteria->addSelectColumn(CountryTableMap::COL_BBOX_SW_LNG);
            $criteria->addSelectColumn(CountryTableMap::COL_BBOX_NE_LAT);
            $criteria->addSelectColumn(CountryTableMap::COL_BBOX_NE_LNG);
        } else {
            $criteria->addSelectColumn($alias . '.ISO_NR');
            $criteria->addSelectColumn($alias . '.ALPHA_2');
            $criteria->addSelectColumn($alias . '.ALPHA_3');
            $criteria->addSelectColumn($alias . '.IOC');
            $criteria->addSelectColumn($alias . '.CAPITAL');
            $criteria->addSelectColumn($alias . '.TLD');
            $criteria->addSelectColumn($alias . '.PHONE');
            $criteria->addSelectColumn($alias . '.TERRITORY_ISO_NR');
            $criteria->addSelectColumn($alias . '.CURRENCY_ISO_NR');
            $criteria->addSelectColumn($alias . '.OFFICIAL_LOCAL_NAME');
            $criteria->addSelectColumn($alias . '.OFFICIAL_EN_NAME');
            $criteria->addSelectColumn($alias . '.SHORT_LOCAL_NAME');
            $criteria->addSelectColumn($alias . '.SHORT_EN_NAME');
            $criteria->addSelectColumn($alias . '.BBOX_SW_LAT');
            $criteria->addSelectColumn($alias . '.BBOX_SW_LNG');
            $criteria->addSelectColumn($alias . '.BBOX_NE_LAT');
            $criteria->addSelectColumn($alias . '.BBOX_NE_LNG');
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
        return Propel::getServiceContainer()->getDatabaseMap(CountryTableMap::DATABASE_NAME)->getTable(CountryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CountryTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CountryTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CountryTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Country or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Country object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Country) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CountryTableMap::DATABASE_NAME);
            $criteria->add(CountryTableMap::COL_ISO_NR, (array) $values, Criteria::IN);
        }

        $query = CountryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CountryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CountryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the keeko_country table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CountryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Country or Criteria object.
     *
     * @param mixed               $criteria Criteria or Country object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Country object
        }


        // Set the correct dbName
        $query = CountryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CountryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CountryTableMap::buildTableMap();
