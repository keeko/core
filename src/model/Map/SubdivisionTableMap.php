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
use keeko\core\model\Subdivision;
use keeko\core\model\SubdivisionQuery;


/**
 * This class defines the structure of the 'kk_subdivision' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SubdivisionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.SubdivisionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'kk_subdivision';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Subdivision';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Subdivision';

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
     * the column name for the id field
     */
    const COL_ID = 'kk_subdivision.id';

    /**
     * the column name for the iso field
     */
    const COL_ISO = 'kk_subdivision.iso';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'kk_subdivision.name';

    /**
     * the column name for the local_name field
     */
    const COL_LOCAL_NAME = 'kk_subdivision.local_name';

    /**
     * the column name for the en_name field
     */
    const COL_EN_NAME = 'kk_subdivision.en_name';

    /**
     * the column name for the alt_names field
     */
    const COL_ALT_NAMES = 'kk_subdivision.alt_names';

    /**
     * the column name for the parent_id field
     */
    const COL_PARENT_ID = 'kk_subdivision.parent_id';

    /**
     * the column name for the country_iso_nr field
     */
    const COL_COUNTRY_ISO_NR = 'kk_subdivision.country_iso_nr';

    /**
     * the column name for the subdivision_type_id field
     */
    const COL_SUBDIVISION_TYPE_ID = 'kk_subdivision.subdivision_type_id';

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
        self::TYPE_PHPNAME       => array('Id', 'Iso', 'Name', 'LocalName', 'EnName', 'AltNames', 'ParentId', 'CountryIsoNr', 'SubdivisionTypeId', ),
        self::TYPE_CAMELNAME     => array('id', 'iso', 'name', 'localName', 'enName', 'altNames', 'parentId', 'countryIsoNr', 'subdivisionTypeId', ),
        self::TYPE_COLNAME       => array(SubdivisionTableMap::COL_ID, SubdivisionTableMap::COL_ISO, SubdivisionTableMap::COL_NAME, SubdivisionTableMap::COL_LOCAL_NAME, SubdivisionTableMap::COL_EN_NAME, SubdivisionTableMap::COL_ALT_NAMES, SubdivisionTableMap::COL_PARENT_ID, SubdivisionTableMap::COL_COUNTRY_ISO_NR, SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID, ),
        self::TYPE_FIELDNAME     => array('id', 'iso', 'name', 'local_name', 'en_name', 'alt_names', 'parent_id', 'country_iso_nr', 'subdivision_type_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Iso' => 1, 'Name' => 2, 'LocalName' => 3, 'EnName' => 4, 'AltNames' => 5, 'ParentId' => 6, 'CountryIsoNr' => 7, 'SubdivisionTypeId' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'iso' => 1, 'name' => 2, 'localName' => 3, 'enName' => 4, 'altNames' => 5, 'parentId' => 6, 'countryIsoNr' => 7, 'subdivisionTypeId' => 8, ),
        self::TYPE_COLNAME       => array(SubdivisionTableMap::COL_ID => 0, SubdivisionTableMap::COL_ISO => 1, SubdivisionTableMap::COL_NAME => 2, SubdivisionTableMap::COL_LOCAL_NAME => 3, SubdivisionTableMap::COL_EN_NAME => 4, SubdivisionTableMap::COL_ALT_NAMES => 5, SubdivisionTableMap::COL_PARENT_ID => 6, SubdivisionTableMap::COL_COUNTRY_ISO_NR => 7, SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'iso' => 1, 'name' => 2, 'local_name' => 3, 'en_name' => 4, 'alt_names' => 5, 'parent_id' => 6, 'country_iso_nr' => 7, 'subdivision_type_id' => 8, ),
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
        $this->setName('kk_subdivision');
        $this->setPhpName('Subdivision');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\keeko\\core\\model\\Subdivision');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('iso', 'Iso', 'VARCHAR', false, 45, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 128, null);
        $this->addColumn('local_name', 'LocalName', 'VARCHAR', false, 128, null);
        $this->addColumn('en_name', 'EnName', 'VARCHAR', false, 128, null);
        $this->addColumn('alt_names', 'AltNames', 'VARCHAR', false, 255, null);
        $this->addColumn('parent_id', 'ParentId', 'INTEGER', false, null, null);
        $this->addForeignKey('country_iso_nr', 'CountryIsoNr', 'INTEGER', 'kk_country', 'iso_nr', true, null, null);
        $this->addForeignKey('subdivision_type_id', 'SubdivisionTypeId', 'INTEGER', 'kk_subdivision_type', 'id', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', '\\keeko\\core\\model\\Country', RelationMap::MANY_TO_ONE, array('country_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('SubdivisionType', '\\keeko\\core\\model\\SubdivisionType', RelationMap::MANY_TO_ONE, array('subdivision_type_id' => 'id', ), null, null);
        $this->addRelation('User', '\\keeko\\core\\model\\User', RelationMap::ONE_TO_MANY, array('id' => 'subdivision_id', ), null, null, 'Users');
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
        return $withPrefix ? SubdivisionTableMap::CLASS_DEFAULT : SubdivisionTableMap::OM_CLASS;
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
     * @return array           (Subdivision object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SubdivisionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SubdivisionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SubdivisionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SubdivisionTableMap::OM_CLASS;
            /** @var Subdivision $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SubdivisionTableMap::addInstanceToPool($obj, $key);
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
            $key = SubdivisionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SubdivisionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Subdivision $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SubdivisionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SubdivisionTableMap::COL_ID);
            $criteria->addSelectColumn(SubdivisionTableMap::COL_ISO);
            $criteria->addSelectColumn(SubdivisionTableMap::COL_NAME);
            $criteria->addSelectColumn(SubdivisionTableMap::COL_LOCAL_NAME);
            $criteria->addSelectColumn(SubdivisionTableMap::COL_EN_NAME);
            $criteria->addSelectColumn(SubdivisionTableMap::COL_ALT_NAMES);
            $criteria->addSelectColumn(SubdivisionTableMap::COL_PARENT_ID);
            $criteria->addSelectColumn(SubdivisionTableMap::COL_COUNTRY_ISO_NR);
            $criteria->addSelectColumn(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.iso');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.local_name');
            $criteria->addSelectColumn($alias . '.en_name');
            $criteria->addSelectColumn($alias . '.alt_names');
            $criteria->addSelectColumn($alias . '.parent_id');
            $criteria->addSelectColumn($alias . '.country_iso_nr');
            $criteria->addSelectColumn($alias . '.subdivision_type_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(SubdivisionTableMap::DATABASE_NAME)->getTable(SubdivisionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SubdivisionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SubdivisionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SubdivisionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Subdivision or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Subdivision object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SubdivisionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Subdivision) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SubdivisionTableMap::DATABASE_NAME);
            $criteria->add(SubdivisionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = SubdivisionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SubdivisionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SubdivisionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the kk_subdivision table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SubdivisionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Subdivision or Criteria object.
     *
     * @param mixed               $criteria Criteria or Subdivision object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubdivisionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Subdivision object
        }

        if ($criteria->containsKey(SubdivisionTableMap::COL_ID) && $criteria->keyContainsValue(SubdivisionTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SubdivisionTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = SubdivisionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SubdivisionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SubdivisionTableMap::buildTableMap();
