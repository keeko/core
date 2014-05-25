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
use keeko\core\model\Action;
use keeko\core\model\ActionQuery;


/**
 * This class defines the structure of the 'keeko_action' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ActionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.model.Map.ActionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'keeko_action';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Action';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'keeko.core.model.Action';

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
     * the column name for the ID field
     */
    const COL_ID = 'keeko_action.ID';

    /**
     * the column name for the NAME field
     */
    const COL_NAME = 'keeko_action.NAME';

    /**
     * the column name for the TITLE field
     */
    const COL_TITLE = 'keeko_action.TITLE';

    /**
     * the column name for the DESCRIPTION field
     */
    const COL_DESCRIPTION = 'keeko_action.DESCRIPTION';

    /**
     * the column name for the CLASS_NAME field
     */
    const COL_CLASS_NAME = 'keeko_action.CLASS_NAME';

    /**
     * the column name for the MODULE_ID field
     */
    const COL_MODULE_ID = 'keeko_action.MODULE_ID';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Title', 'Description', 'ClassName', 'ModuleId', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'name', 'title', 'description', 'className', 'moduleId', ),
        self::TYPE_COLNAME       => array(ActionTableMap::COL_ID, ActionTableMap::COL_NAME, ActionTableMap::COL_TITLE, ActionTableMap::COL_DESCRIPTION, ActionTableMap::COL_CLASS_NAME, ActionTableMap::COL_MODULE_ID, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID', 'COL_NAME', 'COL_TITLE', 'COL_DESCRIPTION', 'COL_CLASS_NAME', 'COL_MODULE_ID', ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'title', 'description', 'class_name', 'module_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Title' => 2, 'Description' => 3, 'ClassName' => 4, 'ModuleId' => 5, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'name' => 1, 'title' => 2, 'description' => 3, 'className' => 4, 'moduleId' => 5, ),
        self::TYPE_COLNAME       => array(ActionTableMap::COL_ID => 0, ActionTableMap::COL_NAME => 1, ActionTableMap::COL_TITLE => 2, ActionTableMap::COL_DESCRIPTION => 3, ActionTableMap::COL_CLASS_NAME => 4, ActionTableMap::COL_MODULE_ID => 5, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID' => 0, 'COL_NAME' => 1, 'COL_TITLE' => 2, 'COL_DESCRIPTION' => 3, 'COL_CLASS_NAME' => 4, 'COL_MODULE_ID' => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'title' => 2, 'description' => 3, 'class_name' => 4, 'module_id' => 5, ),
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
        $this->setName('keeko_action');
        $this->setPhpName('Action');
        $this->setClassName('\\keeko\\core\\model\\Action');
        $this->setPackage('keeko.core.model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('DESCRIPTION', 'Description', 'VARCHAR', false, 255, null);
        $this->addColumn('CLASS_NAME', 'ClassName', 'VARCHAR', true, 255, null);
        $this->addForeignKey('MODULE_ID', 'ModuleId', 'INTEGER', 'keeko_module', 'ID', true, 10, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Module', '\\keeko\\core\\model\\Module', RelationMap::MANY_TO_ONE, array('module_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Api', '\\keeko\\core\\model\\Api', RelationMap::ONE_TO_MANY, array('id' => 'action_id', ), 'CASCADE', null, 'Apis');
        $this->addRelation('GroupAction', '\\keeko\\core\\model\\GroupAction', RelationMap::ONE_TO_MANY, array('id' => 'action_id', ), 'RESTRICT', null, 'GroupActions');
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to keeko_action     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ApiTableMap::clearInstancePool();
    }

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
        return $withPrefix ? ActionTableMap::CLASS_DEFAULT : ActionTableMap::OM_CLASS;
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
     * @return array           (Action object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ActionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ActionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ActionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ActionTableMap::OM_CLASS;
            /** @var Action $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ActionTableMap::addInstanceToPool($obj, $key);
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
            $key = ActionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ActionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Action $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ActionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ActionTableMap::COL_ID);
            $criteria->addSelectColumn(ActionTableMap::COL_NAME);
            $criteria->addSelectColumn(ActionTableMap::COL_TITLE);
            $criteria->addSelectColumn(ActionTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(ActionTableMap::COL_CLASS_NAME);
            $criteria->addSelectColumn(ActionTableMap::COL_MODULE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.NAME');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.DESCRIPTION');
            $criteria->addSelectColumn($alias . '.CLASS_NAME');
            $criteria->addSelectColumn($alias . '.MODULE_ID');
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
        return Propel::getServiceContainer()->getDatabaseMap(ActionTableMap::DATABASE_NAME)->getTable(ActionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ActionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ActionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ActionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Action or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Action object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ActionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Action) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ActionTableMap::DATABASE_NAME);
            $criteria->add(ActionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ActionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ActionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ActionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the keeko_action table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ActionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Action or Criteria object.
     *
     * @param mixed               $criteria Criteria or Action object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Action object
        }

        if ($criteria->containsKey(ActionTableMap::COL_ID) && $criteria->keyContainsValue(ActionTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ActionTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ActionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ActionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ActionTableMap::buildTableMap();
