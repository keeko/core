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
use keeko\core\model\Localization;
use keeko\core\model\LocalizationQuery;


/**
 * This class defines the structure of the 'kk_localization' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LocalizationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.LocalizationTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'kk_localization';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Localization';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Localization';

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
    const COL_ID = 'kk_localization.id';

    /**
     * the column name for the parent_id field
     */
    const COL_PARENT_ID = 'kk_localization.parent_id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'kk_localization.name';

    /**
     * the column name for the locale field
     */
    const COL_LOCALE = 'kk_localization.locale';

    /**
     * the column name for the language_id field
     */
    const COL_LANGUAGE_ID = 'kk_localization.language_id';

    /**
     * the column name for the ext_language_id field
     */
    const COL_EXT_LANGUAGE_ID = 'kk_localization.ext_language_id';

    /**
     * the column name for the region field
     */
    const COL_REGION = 'kk_localization.region';

    /**
     * the column name for the script_id field
     */
    const COL_SCRIPT_ID = 'kk_localization.script_id';

    /**
     * the column name for the is_default field
     */
    const COL_IS_DEFAULT = 'kk_localization.is_default';

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
        self::TYPE_PHPNAME       => array('Id', 'ParentId', 'Name', 'Locale', 'LanguageId', 'ExtLanguageId', 'Region', 'ScriptId', 'IsDefault', ),
        self::TYPE_CAMELNAME     => array('id', 'parentId', 'name', 'locale', 'languageId', 'extLanguageId', 'region', 'scriptId', 'isDefault', ),
        self::TYPE_COLNAME       => array(LocalizationTableMap::COL_ID, LocalizationTableMap::COL_PARENT_ID, LocalizationTableMap::COL_NAME, LocalizationTableMap::COL_LOCALE, LocalizationTableMap::COL_LANGUAGE_ID, LocalizationTableMap::COL_EXT_LANGUAGE_ID, LocalizationTableMap::COL_REGION, LocalizationTableMap::COL_SCRIPT_ID, LocalizationTableMap::COL_IS_DEFAULT, ),
        self::TYPE_FIELDNAME     => array('id', 'parent_id', 'name', 'locale', 'language_id', 'ext_language_id', 'region', 'script_id', 'is_default', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ParentId' => 1, 'Name' => 2, 'Locale' => 3, 'LanguageId' => 4, 'ExtLanguageId' => 5, 'Region' => 6, 'ScriptId' => 7, 'IsDefault' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'parentId' => 1, 'name' => 2, 'locale' => 3, 'languageId' => 4, 'extLanguageId' => 5, 'region' => 6, 'scriptId' => 7, 'isDefault' => 8, ),
        self::TYPE_COLNAME       => array(LocalizationTableMap::COL_ID => 0, LocalizationTableMap::COL_PARENT_ID => 1, LocalizationTableMap::COL_NAME => 2, LocalizationTableMap::COL_LOCALE => 3, LocalizationTableMap::COL_LANGUAGE_ID => 4, LocalizationTableMap::COL_EXT_LANGUAGE_ID => 5, LocalizationTableMap::COL_REGION => 6, LocalizationTableMap::COL_SCRIPT_ID => 7, LocalizationTableMap::COL_IS_DEFAULT => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'parent_id' => 1, 'name' => 2, 'locale' => 3, 'language_id' => 4, 'ext_language_id' => 5, 'region' => 6, 'script_id' => 7, 'is_default' => 8, ),
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
        $this->setName('kk_localization');
        $this->setPhpName('Localization');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\keeko\\core\\model\\Localization');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('parent_id', 'ParentId', 'INTEGER', 'kk_localization', 'id', false, 10, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 128, null);
        $this->addColumn('locale', 'Locale', 'VARCHAR', false, 76, null);
        $this->addForeignKey('language_id', 'LanguageId', 'INTEGER', 'kk_language', 'id', false, 10, null);
        $this->addForeignKey('ext_language_id', 'ExtLanguageId', 'INTEGER', 'kk_language', 'id', false, 10, null);
        $this->addColumn('region', 'Region', 'VARCHAR', false, 3, null);
        $this->addForeignKey('script_id', 'ScriptId', 'INTEGER', 'kk_language_script', 'id', false, 10, null);
        $this->addColumn('is_default', 'IsDefault', 'BOOLEAN', false, 1, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Parent', '\\keeko\\core\\model\\Localization', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':parent_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Language', '\\keeko\\core\\model\\Language', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':language_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('ExtLang', '\\keeko\\core\\model\\Language', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':ext_language_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Script', '\\keeko\\core\\model\\LanguageScript', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':script_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('LocalizationRelatedById', '\\keeko\\core\\model\\Localization', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':parent_id',
    1 => ':id',
  ),
), null, null, 'LocalizationsRelatedById', false);
        $this->addRelation('LocalizationVariant', '\\keeko\\core\\model\\LocalizationVariant', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':localization_id',
    1 => ':id',
  ),
), 'RESTRICT', null, 'LocalizationVariants', false);
        $this->addRelation('ApplicationUri', '\\keeko\\core\\model\\ApplicationUri', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':localization_id',
    1 => ':id',
  ),
), 'RESTRICT', null, 'ApplicationUris', false);
        $this->addRelation('LanguageVariant', '\\keeko\\core\\model\\LanguageVariant', RelationMap::MANY_TO_MANY, array(), 'RESTRICT', null, 'LanguageVariants');
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
        return $withPrefix ? LocalizationTableMap::CLASS_DEFAULT : LocalizationTableMap::OM_CLASS;
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
     * @return array           (Localization object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LocalizationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LocalizationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LocalizationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LocalizationTableMap::OM_CLASS;
            /** @var Localization $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LocalizationTableMap::addInstanceToPool($obj, $key);
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
            $key = LocalizationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LocalizationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Localization $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LocalizationTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LocalizationTableMap::COL_ID);
            $criteria->addSelectColumn(LocalizationTableMap::COL_PARENT_ID);
            $criteria->addSelectColumn(LocalizationTableMap::COL_NAME);
            $criteria->addSelectColumn(LocalizationTableMap::COL_LOCALE);
            $criteria->addSelectColumn(LocalizationTableMap::COL_LANGUAGE_ID);
            $criteria->addSelectColumn(LocalizationTableMap::COL_EXT_LANGUAGE_ID);
            $criteria->addSelectColumn(LocalizationTableMap::COL_REGION);
            $criteria->addSelectColumn(LocalizationTableMap::COL_SCRIPT_ID);
            $criteria->addSelectColumn(LocalizationTableMap::COL_IS_DEFAULT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.parent_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.locale');
            $criteria->addSelectColumn($alias . '.language_id');
            $criteria->addSelectColumn($alias . '.ext_language_id');
            $criteria->addSelectColumn($alias . '.region');
            $criteria->addSelectColumn($alias . '.script_id');
            $criteria->addSelectColumn($alias . '.is_default');
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
        return Propel::getServiceContainer()->getDatabaseMap(LocalizationTableMap::DATABASE_NAME)->getTable(LocalizationTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LocalizationTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LocalizationTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LocalizationTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Localization or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Localization object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LocalizationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Localization) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LocalizationTableMap::DATABASE_NAME);
            $criteria->add(LocalizationTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = LocalizationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LocalizationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LocalizationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the kk_localization table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LocalizationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Localization or Criteria object.
     *
     * @param mixed               $criteria Criteria or Localization object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocalizationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Localization object
        }

        if ($criteria->containsKey(LocalizationTableMap::COL_ID) && $criteria->keyContainsValue(LocalizationTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LocalizationTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = LocalizationQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LocalizationTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LocalizationTableMap::buildTableMap();
