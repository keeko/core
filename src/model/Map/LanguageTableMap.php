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
use keeko\core\model\Language;
use keeko\core\model\LanguageQuery;


/**
 * This class defines the structure of the 'kk_language' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LanguageTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.model.Map.LanguageTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'kk_language';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Language';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'keeko.core.model.Language';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the ID field
     */
    const COL_ID = 'kk_language.ID';

    /**
     * the column name for the ALPHA_2 field
     */
    const COL_ALPHA_2 = 'kk_language.ALPHA_2';

    /**
     * the column name for the ALPHA_3T field
     */
    const COL_ALPHA_3T = 'kk_language.ALPHA_3T';

    /**
     * the column name for the ALPHA_3B field
     */
    const COL_ALPHA_3B = 'kk_language.ALPHA_3B';

    /**
     * the column name for the ALPHA_3 field
     */
    const COL_ALPHA_3 = 'kk_language.ALPHA_3';

    /**
     * the column name for the LOCAL_NAME field
     */
    const COL_LOCAL_NAME = 'kk_language.LOCAL_NAME';

    /**
     * the column name for the EN_NAME field
     */
    const COL_EN_NAME = 'kk_language.EN_NAME';

    /**
     * the column name for the COLLATE field
     */
    const COL_COLLATE = 'kk_language.COLLATE';

    /**
     * the column name for the SCOPE_ID field
     */
    const COL_SCOPE_ID = 'kk_language.SCOPE_ID';

    /**
     * the column name for the TYPE_ID field
     */
    const COL_TYPE_ID = 'kk_language.TYPE_ID';

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
        self::TYPE_PHPNAME       => array('Id', 'Alpha2', 'Alpha3T', 'Alpha3B', 'Alpha3', 'LocalName', 'EnName', 'Collate', 'ScopeId', 'TypeId', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'alpha2', 'alpha3T', 'alpha3B', 'alpha3', 'localName', 'enName', 'collate', 'scopeId', 'typeId', ),
        self::TYPE_COLNAME       => array(LanguageTableMap::COL_ID, LanguageTableMap::COL_ALPHA_2, LanguageTableMap::COL_ALPHA_3T, LanguageTableMap::COL_ALPHA_3B, LanguageTableMap::COL_ALPHA_3, LanguageTableMap::COL_LOCAL_NAME, LanguageTableMap::COL_EN_NAME, LanguageTableMap::COL_COLLATE, LanguageTableMap::COL_SCOPE_ID, LanguageTableMap::COL_TYPE_ID, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID', 'COL_ALPHA_2', 'COL_ALPHA_3T', 'COL_ALPHA_3B', 'COL_ALPHA_3', 'COL_LOCAL_NAME', 'COL_EN_NAME', 'COL_COLLATE', 'COL_SCOPE_ID', 'COL_TYPE_ID', ),
        self::TYPE_FIELDNAME     => array('id', 'alpha_2', 'alpha_3T', 'alpha_3B', 'alpha_3', 'local_name', 'en_name', 'collate', 'scope_id', 'type_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Alpha2' => 1, 'Alpha3T' => 2, 'Alpha3B' => 3, 'Alpha3' => 4, 'LocalName' => 5, 'EnName' => 6, 'Collate' => 7, 'ScopeId' => 8, 'TypeId' => 9, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'alpha2' => 1, 'alpha3T' => 2, 'alpha3B' => 3, 'alpha3' => 4, 'localName' => 5, 'enName' => 6, 'collate' => 7, 'scopeId' => 8, 'typeId' => 9, ),
        self::TYPE_COLNAME       => array(LanguageTableMap::COL_ID => 0, LanguageTableMap::COL_ALPHA_2 => 1, LanguageTableMap::COL_ALPHA_3T => 2, LanguageTableMap::COL_ALPHA_3B => 3, LanguageTableMap::COL_ALPHA_3 => 4, LanguageTableMap::COL_LOCAL_NAME => 5, LanguageTableMap::COL_EN_NAME => 6, LanguageTableMap::COL_COLLATE => 7, LanguageTableMap::COL_SCOPE_ID => 8, LanguageTableMap::COL_TYPE_ID => 9, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID' => 0, 'COL_ALPHA_2' => 1, 'COL_ALPHA_3T' => 2, 'COL_ALPHA_3B' => 3, 'COL_ALPHA_3' => 4, 'COL_LOCAL_NAME' => 5, 'COL_EN_NAME' => 6, 'COL_COLLATE' => 7, 'COL_SCOPE_ID' => 8, 'COL_TYPE_ID' => 9, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'alpha_2' => 1, 'alpha_3T' => 2, 'alpha_3B' => 3, 'alpha_3' => 4, 'local_name' => 5, 'en_name' => 6, 'collate' => 7, 'scope_id' => 8, 'type_id' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
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
        $this->setName('kk_language');
        $this->setPhpName('Language');
        $this->setClassName('\\keeko\\core\\model\\Language');
        $this->setPackage('keeko.core.model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('ALPHA_2', 'Alpha2', 'VARCHAR', false, 2, null);
        $this->addColumn('ALPHA_3T', 'Alpha3T', 'VARCHAR', false, 3, null);
        $this->addColumn('ALPHA_3B', 'Alpha3B', 'VARCHAR', false, 3, null);
        $this->addColumn('ALPHA_3', 'Alpha3', 'VARCHAR', false, 3, null);
        $this->addColumn('LOCAL_NAME', 'LocalName', 'VARCHAR', false, 128, null);
        $this->addColumn('EN_NAME', 'EnName', 'VARCHAR', false, 128, null);
        $this->addColumn('COLLATE', 'Collate', 'VARCHAR', false, 10, null);
        $this->addForeignKey('SCOPE_ID', 'ScopeId', 'INTEGER', 'kk_language_scope', 'ID', false, 10, null);
        $this->addForeignKey('TYPE_ID', 'TypeId', 'INTEGER', 'kk_language_type', 'ID', false, 10, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('LanguageScope', '\\keeko\\core\\model\\LanguageScope', RelationMap::MANY_TO_ONE, array('scope_id' => 'id', ), null, null);
        $this->addRelation('LanguageType', '\\keeko\\core\\model\\LanguageType', RelationMap::MANY_TO_ONE, array('type_id' => 'id', ), null, null);
        $this->addRelation('Localization', '\\keeko\\core\\model\\Localization', RelationMap::ONE_TO_MANY, array('id' => 'language_id', ), null, null, 'Localizations');
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
        return $withPrefix ? LanguageTableMap::CLASS_DEFAULT : LanguageTableMap::OM_CLASS;
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
     * @return array           (Language object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LanguageTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LanguageTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LanguageTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LanguageTableMap::OM_CLASS;
            /** @var Language $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LanguageTableMap::addInstanceToPool($obj, $key);
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
            $key = LanguageTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LanguageTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Language $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LanguageTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LanguageTableMap::COL_ID);
            $criteria->addSelectColumn(LanguageTableMap::COL_ALPHA_2);
            $criteria->addSelectColumn(LanguageTableMap::COL_ALPHA_3T);
            $criteria->addSelectColumn(LanguageTableMap::COL_ALPHA_3B);
            $criteria->addSelectColumn(LanguageTableMap::COL_ALPHA_3);
            $criteria->addSelectColumn(LanguageTableMap::COL_LOCAL_NAME);
            $criteria->addSelectColumn(LanguageTableMap::COL_EN_NAME);
            $criteria->addSelectColumn(LanguageTableMap::COL_COLLATE);
            $criteria->addSelectColumn(LanguageTableMap::COL_SCOPE_ID);
            $criteria->addSelectColumn(LanguageTableMap::COL_TYPE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.ALPHA_2');
            $criteria->addSelectColumn($alias . '.ALPHA_3T');
            $criteria->addSelectColumn($alias . '.ALPHA_3B');
            $criteria->addSelectColumn($alias . '.ALPHA_3');
            $criteria->addSelectColumn($alias . '.LOCAL_NAME');
            $criteria->addSelectColumn($alias . '.EN_NAME');
            $criteria->addSelectColumn($alias . '.COLLATE');
            $criteria->addSelectColumn($alias . '.SCOPE_ID');
            $criteria->addSelectColumn($alias . '.TYPE_ID');
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
        return Propel::getServiceContainer()->getDatabaseMap(LanguageTableMap::DATABASE_NAME)->getTable(LanguageTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LanguageTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LanguageTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LanguageTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Language or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Language object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Language) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LanguageTableMap::DATABASE_NAME);
            $criteria->add(LanguageTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = LanguageQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LanguageTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LanguageTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the kk_language table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LanguageQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Language or Criteria object.
     *
     * @param mixed               $criteria Criteria or Language object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Language object
        }

        if ($criteria->containsKey(LanguageTableMap::COL_ID) && $criteria->keyContainsValue(LanguageTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LanguageTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = LanguageQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LanguageTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LanguageTableMap::buildTableMap();
