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
 * This class defines the structure of the 'kk_country' table.
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
    const CLASS_NAME = '.Map.CountryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'kk_country';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Country';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Country';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 23;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 23;

    /**
     * the column name for the id field
     */
    const COL_ID = 'kk_country.id';

    /**
     * the column name for the numeric field
     */
    const COL_NUMERIC = 'kk_country.numeric';

    /**
     * the column name for the alpha_2 field
     */
    const COL_ALPHA_2 = 'kk_country.alpha_2';

    /**
     * the column name for the alpha_3 field
     */
    const COL_ALPHA_3 = 'kk_country.alpha_3';

    /**
     * the column name for the short_name field
     */
    const COL_SHORT_NAME = 'kk_country.short_name';

    /**
     * the column name for the ioc field
     */
    const COL_IOC = 'kk_country.ioc';

    /**
     * the column name for the tld field
     */
    const COL_TLD = 'kk_country.tld';

    /**
     * the column name for the phone field
     */
    const COL_PHONE = 'kk_country.phone';

    /**
     * the column name for the capital field
     */
    const COL_CAPITAL = 'kk_country.capital';

    /**
     * the column name for the postal_code_format field
     */
    const COL_POSTAL_CODE_FORMAT = 'kk_country.postal_code_format';

    /**
     * the column name for the postal_code_regex field
     */
    const COL_POSTAL_CODE_REGEX = 'kk_country.postal_code_regex';

    /**
     * the column name for the continent_id field
     */
    const COL_CONTINENT_ID = 'kk_country.continent_id';

    /**
     * the column name for the currency_id field
     */
    const COL_CURRENCY_ID = 'kk_country.currency_id';

    /**
     * the column name for the type_id field
     */
    const COL_TYPE_ID = 'kk_country.type_id';

    /**
     * the column name for the subtype_id field
     */
    const COL_SUBTYPE_ID = 'kk_country.subtype_id';

    /**
     * the column name for the sovereignity_id field
     */
    const COL_SOVEREIGNITY_ID = 'kk_country.sovereignity_id';

    /**
     * the column name for the formal_name field
     */
    const COL_FORMAL_NAME = 'kk_country.formal_name';

    /**
     * the column name for the formal_native_name field
     */
    const COL_FORMAL_NATIVE_NAME = 'kk_country.formal_native_name';

    /**
     * the column name for the short_native_name field
     */
    const COL_SHORT_NATIVE_NAME = 'kk_country.short_native_name';

    /**
     * the column name for the bbox_sw_lat field
     */
    const COL_BBOX_SW_LAT = 'kk_country.bbox_sw_lat';

    /**
     * the column name for the bbox_sw_lng field
     */
    const COL_BBOX_SW_LNG = 'kk_country.bbox_sw_lng';

    /**
     * the column name for the bbox_ne_lat field
     */
    const COL_BBOX_NE_LAT = 'kk_country.bbox_ne_lat';

    /**
     * the column name for the bbox_ne_lng field
     */
    const COL_BBOX_NE_LNG = 'kk_country.bbox_ne_lng';

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
        self::TYPE_PHPNAME       => array('Id', 'Numeric', 'Alpha2', 'Alpha3', 'ShortName', 'Ioc', 'Tld', 'Phone', 'Capital', 'PostalCodeFormat', 'PostalCodeRegex', 'ContinentId', 'CurrencyId', 'TypeId', 'SubtypeId', 'SovereignityId', 'FormalName', 'FormalNativeName', 'ShortNativeName', 'BboxSwLat', 'BboxSwLng', 'BboxNeLat', 'BboxNeLng', ),
        self::TYPE_CAMELNAME     => array('id', 'numeric', 'alpha2', 'alpha3', 'shortName', 'ioc', 'tld', 'phone', 'capital', 'postalCodeFormat', 'postalCodeRegex', 'continentId', 'currencyId', 'typeId', 'subtypeId', 'sovereignityId', 'formalName', 'formalNativeName', 'shortNativeName', 'bboxSwLat', 'bboxSwLng', 'bboxNeLat', 'bboxNeLng', ),
        self::TYPE_COLNAME       => array(CountryTableMap::COL_ID, CountryTableMap::COL_NUMERIC, CountryTableMap::COL_ALPHA_2, CountryTableMap::COL_ALPHA_3, CountryTableMap::COL_SHORT_NAME, CountryTableMap::COL_IOC, CountryTableMap::COL_TLD, CountryTableMap::COL_PHONE, CountryTableMap::COL_CAPITAL, CountryTableMap::COL_POSTAL_CODE_FORMAT, CountryTableMap::COL_POSTAL_CODE_REGEX, CountryTableMap::COL_CONTINENT_ID, CountryTableMap::COL_CURRENCY_ID, CountryTableMap::COL_TYPE_ID, CountryTableMap::COL_SUBTYPE_ID, CountryTableMap::COL_SOVEREIGNITY_ID, CountryTableMap::COL_FORMAL_NAME, CountryTableMap::COL_FORMAL_NATIVE_NAME, CountryTableMap::COL_SHORT_NATIVE_NAME, CountryTableMap::COL_BBOX_SW_LAT, CountryTableMap::COL_BBOX_SW_LNG, CountryTableMap::COL_BBOX_NE_LAT, CountryTableMap::COL_BBOX_NE_LNG, ),
        self::TYPE_FIELDNAME     => array('id', 'numeric', 'alpha_2', 'alpha_3', 'short_name', 'ioc', 'tld', 'phone', 'capital', 'postal_code_format', 'postal_code_regex', 'continent_id', 'currency_id', 'type_id', 'subtype_id', 'sovereignity_id', 'formal_name', 'formal_native_name', 'short_native_name', 'bbox_sw_lat', 'bbox_sw_lng', 'bbox_ne_lat', 'bbox_ne_lng', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Numeric' => 1, 'Alpha2' => 2, 'Alpha3' => 3, 'ShortName' => 4, 'Ioc' => 5, 'Tld' => 6, 'Phone' => 7, 'Capital' => 8, 'PostalCodeFormat' => 9, 'PostalCodeRegex' => 10, 'ContinentId' => 11, 'CurrencyId' => 12, 'TypeId' => 13, 'SubtypeId' => 14, 'SovereignityId' => 15, 'FormalName' => 16, 'FormalNativeName' => 17, 'ShortNativeName' => 18, 'BboxSwLat' => 19, 'BboxSwLng' => 20, 'BboxNeLat' => 21, 'BboxNeLng' => 22, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'numeric' => 1, 'alpha2' => 2, 'alpha3' => 3, 'shortName' => 4, 'ioc' => 5, 'tld' => 6, 'phone' => 7, 'capital' => 8, 'postalCodeFormat' => 9, 'postalCodeRegex' => 10, 'continentId' => 11, 'currencyId' => 12, 'typeId' => 13, 'subtypeId' => 14, 'sovereignityId' => 15, 'formalName' => 16, 'formalNativeName' => 17, 'shortNativeName' => 18, 'bboxSwLat' => 19, 'bboxSwLng' => 20, 'bboxNeLat' => 21, 'bboxNeLng' => 22, ),
        self::TYPE_COLNAME       => array(CountryTableMap::COL_ID => 0, CountryTableMap::COL_NUMERIC => 1, CountryTableMap::COL_ALPHA_2 => 2, CountryTableMap::COL_ALPHA_3 => 3, CountryTableMap::COL_SHORT_NAME => 4, CountryTableMap::COL_IOC => 5, CountryTableMap::COL_TLD => 6, CountryTableMap::COL_PHONE => 7, CountryTableMap::COL_CAPITAL => 8, CountryTableMap::COL_POSTAL_CODE_FORMAT => 9, CountryTableMap::COL_POSTAL_CODE_REGEX => 10, CountryTableMap::COL_CONTINENT_ID => 11, CountryTableMap::COL_CURRENCY_ID => 12, CountryTableMap::COL_TYPE_ID => 13, CountryTableMap::COL_SUBTYPE_ID => 14, CountryTableMap::COL_SOVEREIGNITY_ID => 15, CountryTableMap::COL_FORMAL_NAME => 16, CountryTableMap::COL_FORMAL_NATIVE_NAME => 17, CountryTableMap::COL_SHORT_NATIVE_NAME => 18, CountryTableMap::COL_BBOX_SW_LAT => 19, CountryTableMap::COL_BBOX_SW_LNG => 20, CountryTableMap::COL_BBOX_NE_LAT => 21, CountryTableMap::COL_BBOX_NE_LNG => 22, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'numeric' => 1, 'alpha_2' => 2, 'alpha_3' => 3, 'short_name' => 4, 'ioc' => 5, 'tld' => 6, 'phone' => 7, 'capital' => 8, 'postal_code_format' => 9, 'postal_code_regex' => 10, 'continent_id' => 11, 'currency_id' => 12, 'type_id' => 13, 'subtype_id' => 14, 'sovereignity_id' => 15, 'formal_name' => 16, 'formal_native_name' => 17, 'short_native_name' => 18, 'bbox_sw_lat' => 19, 'bbox_sw_lng' => 20, 'bbox_ne_lat' => 21, 'bbox_ne_lng' => 22, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, )
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
        $this->setName('kk_country');
        $this->setPhpName('Country');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\keeko\\core\\model\\Country');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('numeric', 'Numeric', 'INTEGER', false, null, null);
        $this->addColumn('alpha_2', 'Alpha2', 'CHAR', false, 2, null);
        $this->addColumn('alpha_3', 'Alpha3', 'CHAR', false, 3, null);
        $this->addColumn('short_name', 'ShortName', 'VARCHAR', false, 128, null);
        $this->addColumn('ioc', 'Ioc', 'CHAR', false, 3, null);
        $this->addColumn('tld', 'Tld', 'VARCHAR', false, 3, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 16, null);
        $this->addColumn('capital', 'Capital', 'VARCHAR', false, 128, null);
        $this->addColumn('postal_code_format', 'PostalCodeFormat', 'VARCHAR', false, 255, null);
        $this->addColumn('postal_code_regex', 'PostalCodeRegex', 'VARCHAR', false, 255, null);
        $this->addForeignKey('continent_id', 'ContinentId', 'INTEGER', 'kk_continent', 'id', true, null, null);
        $this->addForeignKey('currency_id', 'CurrencyId', 'INTEGER', 'kk_currency', 'id', true, null, null);
        $this->addForeignKey('type_id', 'TypeId', 'INTEGER', 'kk_region_type', 'id', true, 10, null);
        $this->addForeignKey('subtype_id', 'SubtypeId', 'INTEGER', 'kk_region_type', 'id', false, 10, null);
        $this->addForeignKey('sovereignity_id', 'SovereignityId', 'INTEGER', 'kk_country', 'id', false, 10, null);
        $this->addColumn('formal_name', 'FormalName', 'VARCHAR', false, 128, null);
        $this->addColumn('formal_native_name', 'FormalNativeName', 'VARCHAR', false, 128, null);
        $this->addColumn('short_native_name', 'ShortNativeName', 'VARCHAR', false, 128, null);
        $this->addColumn('bbox_sw_lat', 'BboxSwLat', 'FLOAT', false, null, null);
        $this->addColumn('bbox_sw_lng', 'BboxSwLng', 'FLOAT', false, null, null);
        $this->addColumn('bbox_ne_lat', 'BboxNeLat', 'FLOAT', false, null, null);
        $this->addColumn('bbox_ne_lng', 'BboxNeLng', 'FLOAT', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Continent', '\\keeko\\core\\model\\Continent', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':continent_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Currency', '\\keeko\\core\\model\\Currency', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':currency_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Type', '\\keeko\\core\\model\\RegionType', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':type_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Subtype', '\\keeko\\core\\model\\RegionType', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':subtype_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('CountryRelatedBySovereignityId', '\\keeko\\core\\model\\Country', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':sovereignity_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Subordinate', '\\keeko\\core\\model\\Country', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':sovereignity_id',
    1 => ':id',
  ),
), null, null, 'Subordinates', false);
        $this->addRelation('Subdivision', '\\keeko\\core\\model\\Subdivision', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':country_id',
    1 => ':id',
  ),
), null, null, 'Subdivisions', false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
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
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
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
            $criteria->addSelectColumn(CountryTableMap::COL_ID);
            $criteria->addSelectColumn(CountryTableMap::COL_NUMERIC);
            $criteria->addSelectColumn(CountryTableMap::COL_ALPHA_2);
            $criteria->addSelectColumn(CountryTableMap::COL_ALPHA_3);
            $criteria->addSelectColumn(CountryTableMap::COL_SHORT_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_IOC);
            $criteria->addSelectColumn(CountryTableMap::COL_TLD);
            $criteria->addSelectColumn(CountryTableMap::COL_PHONE);
            $criteria->addSelectColumn(CountryTableMap::COL_CAPITAL);
            $criteria->addSelectColumn(CountryTableMap::COL_POSTAL_CODE_FORMAT);
            $criteria->addSelectColumn(CountryTableMap::COL_POSTAL_CODE_REGEX);
            $criteria->addSelectColumn(CountryTableMap::COL_CONTINENT_ID);
            $criteria->addSelectColumn(CountryTableMap::COL_CURRENCY_ID);
            $criteria->addSelectColumn(CountryTableMap::COL_TYPE_ID);
            $criteria->addSelectColumn(CountryTableMap::COL_SUBTYPE_ID);
            $criteria->addSelectColumn(CountryTableMap::COL_SOVEREIGNITY_ID);
            $criteria->addSelectColumn(CountryTableMap::COL_FORMAL_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_FORMAL_NATIVE_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_SHORT_NATIVE_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_BBOX_SW_LAT);
            $criteria->addSelectColumn(CountryTableMap::COL_BBOX_SW_LNG);
            $criteria->addSelectColumn(CountryTableMap::COL_BBOX_NE_LAT);
            $criteria->addSelectColumn(CountryTableMap::COL_BBOX_NE_LNG);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.numeric');
            $criteria->addSelectColumn($alias . '.alpha_2');
            $criteria->addSelectColumn($alias . '.alpha_3');
            $criteria->addSelectColumn($alias . '.short_name');
            $criteria->addSelectColumn($alias . '.ioc');
            $criteria->addSelectColumn($alias . '.tld');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.capital');
            $criteria->addSelectColumn($alias . '.postal_code_format');
            $criteria->addSelectColumn($alias . '.postal_code_regex');
            $criteria->addSelectColumn($alias . '.continent_id');
            $criteria->addSelectColumn($alias . '.currency_id');
            $criteria->addSelectColumn($alias . '.type_id');
            $criteria->addSelectColumn($alias . '.subtype_id');
            $criteria->addSelectColumn($alias . '.sovereignity_id');
            $criteria->addSelectColumn($alias . '.formal_name');
            $criteria->addSelectColumn($alias . '.formal_native_name');
            $criteria->addSelectColumn($alias . '.short_native_name');
            $criteria->addSelectColumn($alias . '.bbox_sw_lat');
            $criteria->addSelectColumn($alias . '.bbox_sw_lng');
            $criteria->addSelectColumn($alias . '.bbox_ne_lat');
            $criteria->addSelectColumn($alias . '.bbox_ne_lng');
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
            $criteria->add(CountryTableMap::COL_ID, (array) $values, Criteria::IN);
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
     * Deletes all rows from the kk_country table.
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

        if ($criteria->containsKey(CountryTableMap::COL_ID) && $criteria->keyContainsValue(CountryTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CountryTableMap::COL_ID.')');
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
