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
use keeko\core\model\Application;
use keeko\core\model\ApplicationQuery;


/**
 * This class defines the structure of the 'keeko_application' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ApplicationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.model.Map.ApplicationTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'keeko_application';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Application';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'keeko.core.model.Application';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the CLASS_NAME field
     */
    const COL_CLASS_NAME = 'keeko_application.CLASS_NAME';

    /**
     * the column name for the ID field
     */
    const COL_ID = 'keeko_application.ID';

    /**
     * the column name for the NAME field
     */
    const COL_NAME = 'keeko_application.NAME';

    /**
     * the column name for the TITLE field
     */
    const COL_TITLE = 'keeko_application.TITLE';

    /**
     * the column name for the DESCRIPTION field
     */
    const COL_DESCRIPTION = 'keeko_application.DESCRIPTION';

    /**
     * the column name for the INSTALLED_VERSION field
     */
    const COL_INSTALLED_VERSION = 'keeko_application.INSTALLED_VERSION';

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
        self::TYPE_PHPNAME       => array('ClassName', 'Id', 'Name', 'Title', 'Description', 'InstalledVersion', ),
        self::TYPE_STUDLYPHPNAME => array('className', 'id', 'name', 'title', 'description', 'installedVersion', ),
        self::TYPE_COLNAME       => array(ApplicationTableMap::COL_CLASS_NAME, ApplicationTableMap::COL_ID, ApplicationTableMap::COL_NAME, ApplicationTableMap::COL_TITLE, ApplicationTableMap::COL_DESCRIPTION, ApplicationTableMap::COL_INSTALLED_VERSION, ),
        self::TYPE_RAW_COLNAME   => array('COL_CLASS_NAME', 'COL_ID', 'COL_NAME', 'COL_TITLE', 'COL_DESCRIPTION', 'COL_INSTALLED_VERSION', ),
        self::TYPE_FIELDNAME     => array('class_name', 'id', 'name', 'title', 'description', 'installed_version', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('ClassName' => 0, 'Id' => 1, 'Name' => 2, 'Title' => 3, 'Description' => 4, 'InstalledVersion' => 5, ),
        self::TYPE_STUDLYPHPNAME => array('className' => 0, 'id' => 1, 'name' => 2, 'title' => 3, 'description' => 4, 'installedVersion' => 5, ),
        self::TYPE_COLNAME       => array(ApplicationTableMap::COL_CLASS_NAME => 0, ApplicationTableMap::COL_ID => 1, ApplicationTableMap::COL_NAME => 2, ApplicationTableMap::COL_TITLE => 3, ApplicationTableMap::COL_DESCRIPTION => 4, ApplicationTableMap::COL_INSTALLED_VERSION => 5, ),
        self::TYPE_RAW_COLNAME   => array('COL_CLASS_NAME' => 0, 'COL_ID' => 1, 'COL_NAME' => 2, 'COL_TITLE' => 3, 'COL_DESCRIPTION' => 4, 'COL_INSTALLED_VERSION' => 5, ),
        self::TYPE_FIELDNAME     => array('class_name' => 0, 'id' => 1, 'name' => 2, 'title' => 3, 'description' => 4, 'installed_version' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
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
        $this->setName('keeko_application');
        $this->setPhpName('Application');
        $this->setClassName('\\keeko\\core\\model\\Application');
        $this->setPackage('keeko.core.model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('CLASS_NAME', 'ClassName', 'VARCHAR', true, 255, null);
        $this->addForeignPrimaryKey('ID', 'Id', 'INTEGER' , 'keeko_package', 'ID', true, null, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('DESCRIPTION', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('INSTALLED_VERSION', 'InstalledVersion', 'VARCHAR', false, 50, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Package', '\\keeko\\core\\model\\Package', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
        $this->addRelation('ApplicationUri', '\\keeko\\core\\model\\ApplicationUri', RelationMap::ONE_TO_MANY, array('id' => 'application_id', ), 'RESTRICT', null, 'ApplicationUris');
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
            'concrete_inheritance' => array('extends' => 'package', 'descendant_column' => 'descendant_class', 'copy_data_to_parent' => 'true', 'schema' => '', ),
        );
    } // getBehaviors()

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
                ? 1 + $offset
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
        return $withPrefix ? ApplicationTableMap::CLASS_DEFAULT : ApplicationTableMap::OM_CLASS;
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
     * @return array           (Application object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ApplicationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ApplicationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ApplicationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ApplicationTableMap::OM_CLASS;
            /** @var Application $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ApplicationTableMap::addInstanceToPool($obj, $key);
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
            $key = ApplicationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ApplicationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Application $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ApplicationTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ApplicationTableMap::COL_CLASS_NAME);
            $criteria->addSelectColumn(ApplicationTableMap::COL_ID);
            $criteria->addSelectColumn(ApplicationTableMap::COL_NAME);
            $criteria->addSelectColumn(ApplicationTableMap::COL_TITLE);
            $criteria->addSelectColumn(ApplicationTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(ApplicationTableMap::COL_INSTALLED_VERSION);
        } else {
            $criteria->addSelectColumn($alias . '.CLASS_NAME');
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.NAME');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.DESCRIPTION');
            $criteria->addSelectColumn($alias . '.INSTALLED_VERSION');
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
        return Propel::getServiceContainer()->getDatabaseMap(ApplicationTableMap::DATABASE_NAME)->getTable(ApplicationTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ApplicationTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ApplicationTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ApplicationTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Application or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Application object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ApplicationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Application) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ApplicationTableMap::DATABASE_NAME);
            $criteria->add(ApplicationTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ApplicationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ApplicationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ApplicationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the keeko_application table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ApplicationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Application or Criteria object.
     *
     * @param mixed               $criteria Criteria or Application object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ApplicationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Application object
        }


        // Set the correct dbName
        $query = ApplicationQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ApplicationTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ApplicationTableMap::buildTableMap();
