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
use keeko\core\model\Module;
use keeko\core\model\ModuleQuery;


/**
 * This class defines the structure of the 'kk_module' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ModuleTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ModuleTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'kk_module';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Module';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Module';

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
     * the column name for the class_name field
     */
    const COL_CLASS_NAME = 'kk_module.class_name';

    /**
     * the column name for the activated_version field
     */
    const COL_ACTIVATED_VERSION = 'kk_module.activated_version';

    /**
     * the column name for the default_action field
     */
    const COL_DEFAULT_ACTION = 'kk_module.default_action';

    /**
     * the column name for the slug field
     */
    const COL_SLUG = 'kk_module.slug';

    /**
     * the column name for the has_api field
     */
    const COL_HAS_API = 'kk_module.has_api';

    /**
     * the column name for the id field
     */
    const COL_ID = 'kk_module.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'kk_module.name';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'kk_module.title';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'kk_module.description';

    /**
     * the column name for the installed_version field
     */
    const COL_INSTALLED_VERSION = 'kk_module.installed_version';

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
        self::TYPE_PHPNAME       => array('ClassName', 'ActivatedVersion', 'DefaultAction', 'Slug', 'Api', 'Id', 'Name', 'Title', 'Description', 'InstalledVersion', ),
        self::TYPE_CAMELNAME     => array('className', 'activatedVersion', 'defaultAction', 'slug', 'api', 'id', 'name', 'title', 'description', 'installedVersion', ),
        self::TYPE_COLNAME       => array(ModuleTableMap::COL_CLASS_NAME, ModuleTableMap::COL_ACTIVATED_VERSION, ModuleTableMap::COL_DEFAULT_ACTION, ModuleTableMap::COL_SLUG, ModuleTableMap::COL_HAS_API, ModuleTableMap::COL_ID, ModuleTableMap::COL_NAME, ModuleTableMap::COL_TITLE, ModuleTableMap::COL_DESCRIPTION, ModuleTableMap::COL_INSTALLED_VERSION, ),
        self::TYPE_FIELDNAME     => array('class_name', 'activated_version', 'default_action', 'slug', 'has_api', 'id', 'name', 'title', 'description', 'installed_version', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('ClassName' => 0, 'ActivatedVersion' => 1, 'DefaultAction' => 2, 'Slug' => 3, 'Api' => 4, 'Id' => 5, 'Name' => 6, 'Title' => 7, 'Description' => 8, 'InstalledVersion' => 9, ),
        self::TYPE_CAMELNAME     => array('className' => 0, 'activatedVersion' => 1, 'defaultAction' => 2, 'slug' => 3, 'api' => 4, 'id' => 5, 'name' => 6, 'title' => 7, 'description' => 8, 'installedVersion' => 9, ),
        self::TYPE_COLNAME       => array(ModuleTableMap::COL_CLASS_NAME => 0, ModuleTableMap::COL_ACTIVATED_VERSION => 1, ModuleTableMap::COL_DEFAULT_ACTION => 2, ModuleTableMap::COL_SLUG => 3, ModuleTableMap::COL_HAS_API => 4, ModuleTableMap::COL_ID => 5, ModuleTableMap::COL_NAME => 6, ModuleTableMap::COL_TITLE => 7, ModuleTableMap::COL_DESCRIPTION => 8, ModuleTableMap::COL_INSTALLED_VERSION => 9, ),
        self::TYPE_FIELDNAME     => array('class_name' => 0, 'activated_version' => 1, 'default_action' => 2, 'slug' => 3, 'has_api' => 4, 'id' => 5, 'name' => 6, 'title' => 7, 'description' => 8, 'installed_version' => 9, ),
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
        $this->setName('kk_module');
        $this->setPhpName('Module');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\keeko\\core\\model\\Module');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('class_name', 'ClassName', 'VARCHAR', true, 255, null);
        $this->addColumn('activated_version', 'ActivatedVersion', 'VARCHAR', false, 50, null);
        $this->addColumn('default_action', 'DefaultAction', 'VARCHAR', false, 255, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        $this->addColumn('has_api', 'Api', 'BOOLEAN', false, 1, false);
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'kk_package', 'id', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('installed_version', 'InstalledVersion', 'VARCHAR', false, 50, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Package', '\\keeko\\core\\model\\Package', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Action', '\\keeko\\core\\model\\Action', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':module_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Actions', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'concrete_inheritance' => array('extends' => 'package', 'descendant_column' => 'descendant_class', 'copy_data_to_parent' => 'true', 'copy_data_to_child' => 'false', 'schema' => '', 'exclude_behaviors' => '', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to kk_module     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ActionTableMap::clearInstancePool();
    }

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
                ? 5 + $offset
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
        return $withPrefix ? ModuleTableMap::CLASS_DEFAULT : ModuleTableMap::OM_CLASS;
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
     * @return array           (Module object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ModuleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ModuleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ModuleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ModuleTableMap::OM_CLASS;
            /** @var Module $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ModuleTableMap::addInstanceToPool($obj, $key);
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
            $key = ModuleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ModuleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Module $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ModuleTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ModuleTableMap::COL_CLASS_NAME);
            $criteria->addSelectColumn(ModuleTableMap::COL_ACTIVATED_VERSION);
            $criteria->addSelectColumn(ModuleTableMap::COL_DEFAULT_ACTION);
            $criteria->addSelectColumn(ModuleTableMap::COL_SLUG);
            $criteria->addSelectColumn(ModuleTableMap::COL_HAS_API);
            $criteria->addSelectColumn(ModuleTableMap::COL_ID);
            $criteria->addSelectColumn(ModuleTableMap::COL_NAME);
            $criteria->addSelectColumn(ModuleTableMap::COL_TITLE);
            $criteria->addSelectColumn(ModuleTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(ModuleTableMap::COL_INSTALLED_VERSION);
        } else {
            $criteria->addSelectColumn($alias . '.class_name');
            $criteria->addSelectColumn($alias . '.activated_version');
            $criteria->addSelectColumn($alias . '.default_action');
            $criteria->addSelectColumn($alias . '.slug');
            $criteria->addSelectColumn($alias . '.has_api');
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.installed_version');
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
        return Propel::getServiceContainer()->getDatabaseMap(ModuleTableMap::DATABASE_NAME)->getTable(ModuleTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ModuleTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ModuleTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ModuleTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Module or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Module object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ModuleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Module) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ModuleTableMap::DATABASE_NAME);
            $criteria->add(ModuleTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ModuleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ModuleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ModuleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the kk_module table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ModuleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Module or Criteria object.
     *
     * @param mixed               $criteria Criteria or Module object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ModuleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Module object
        }


        // Set the correct dbName
        $query = ModuleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ModuleTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ModuleTableMap::buildTableMap();
