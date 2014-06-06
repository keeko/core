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
use keeko\core\model\Group;
use keeko\core\model\GroupQuery;


/**
 * This class defines the structure of the 'kk_group' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class GroupTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.model.Map.GroupTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'kk_group';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\Group';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'keeko.core.model.Group';

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
     * the column name for the ID field
     */
    const COL_ID = 'kk_group.ID';

    /**
     * the column name for the USER_ID field
     */
    const COL_USER_ID = 'kk_group.USER_ID';

    /**
     * the column name for the NAME field
     */
    const COL_NAME = 'kk_group.NAME';

    /**
     * the column name for the IS_GUEST field
     */
    const COL_IS_GUEST = 'kk_group.IS_GUEST';

    /**
     * the column name for the IS_DEFAULT field
     */
    const COL_IS_DEFAULT = 'kk_group.IS_DEFAULT';

    /**
     * the column name for the IS_ACTIVE field
     */
    const COL_IS_ACTIVE = 'kk_group.IS_ACTIVE';

    /**
     * the column name for the IS_SYSTEM field
     */
    const COL_IS_SYSTEM = 'kk_group.IS_SYSTEM';

    /**
     * the column name for the CREATED_AT field
     */
    const COL_CREATED_AT = 'kk_group.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const COL_UPDATED_AT = 'kk_group.UPDATED_AT';

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
        self::TYPE_PHPNAME       => array('Id', 'UserId', 'Name', 'IsGuest', 'IsDefault', 'IsActive', 'IsSystem', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'userId', 'name', 'isGuest', 'isDefault', 'isActive', 'isSystem', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(GroupTableMap::COL_ID, GroupTableMap::COL_USER_ID, GroupTableMap::COL_NAME, GroupTableMap::COL_IS_GUEST, GroupTableMap::COL_IS_DEFAULT, GroupTableMap::COL_IS_ACTIVE, GroupTableMap::COL_IS_SYSTEM, GroupTableMap::COL_CREATED_AT, GroupTableMap::COL_UPDATED_AT, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID', 'COL_USER_ID', 'COL_NAME', 'COL_IS_GUEST', 'COL_IS_DEFAULT', 'COL_IS_ACTIVE', 'COL_IS_SYSTEM', 'COL_CREATED_AT', 'COL_UPDATED_AT', ),
        self::TYPE_FIELDNAME     => array('id', 'user_id', 'name', 'is_guest', 'is_default', 'is_active', 'is_system', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserId' => 1, 'Name' => 2, 'IsGuest' => 3, 'IsDefault' => 4, 'IsActive' => 5, 'IsSystem' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'userId' => 1, 'name' => 2, 'isGuest' => 3, 'isDefault' => 4, 'isActive' => 5, 'isSystem' => 6, 'createdAt' => 7, 'updatedAt' => 8, ),
        self::TYPE_COLNAME       => array(GroupTableMap::COL_ID => 0, GroupTableMap::COL_USER_ID => 1, GroupTableMap::COL_NAME => 2, GroupTableMap::COL_IS_GUEST => 3, GroupTableMap::COL_IS_DEFAULT => 4, GroupTableMap::COL_IS_ACTIVE => 5, GroupTableMap::COL_IS_SYSTEM => 6, GroupTableMap::COL_CREATED_AT => 7, GroupTableMap::COL_UPDATED_AT => 8, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID' => 0, 'COL_USER_ID' => 1, 'COL_NAME' => 2, 'COL_IS_GUEST' => 3, 'COL_IS_DEFAULT' => 4, 'COL_IS_ACTIVE' => 5, 'COL_IS_SYSTEM' => 6, 'COL_CREATED_AT' => 7, 'COL_UPDATED_AT' => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'user_id' => 1, 'name' => 2, 'is_guest' => 3, 'is_default' => 4, 'is_active' => 5, 'is_system' => 6, 'created_at' => 7, 'updated_at' => 8, ),
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
        $this->setName('kk_group');
        $this->setPhpName('Group');
        $this->setClassName('\\keeko\\core\\model\\Group');
        $this->setPackage('keeko.core.model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'kk_user', 'ID', false, 10, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', false, 64, null);
        $this->addColumn('IS_GUEST', 'IsGuest', 'BOOLEAN', false, 1, null);
        $this->addColumn('IS_DEFAULT', 'IsDefault', 'BOOLEAN', false, 1, null);
        $this->addColumn('IS_ACTIVE', 'IsActive', 'BOOLEAN', false, 1, true);
        $this->addColumn('IS_SYSTEM', 'IsSystem', 'BOOLEAN', false, 1, false);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\keeko\\core\\model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'RESTRICT', null);
        $this->addRelation('GroupUser', '\\keeko\\core\\model\\GroupUser', RelationMap::ONE_TO_MANY, array('id' => 'group_id', ), 'RESTRICT', null, 'GroupUsers');
        $this->addRelation('GroupAction', '\\keeko\\core\\model\\GroupAction', RelationMap::ONE_TO_MANY, array('id' => 'group_id', ), 'RESTRICT', null, 'GroupActions');
        $this->addRelation('User', '\\keeko\\core\\model\\User', RelationMap::MANY_TO_MANY, array(), 'RESTRICT', null, 'Users');
        $this->addRelation('Action', '\\keeko\\core\\model\\Action', RelationMap::MANY_TO_MANY, array(), 'RESTRICT', null, 'Actions');
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
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
            'validate' => array('rule1' => array ('column' => 'name','validator' => 'NotNull',), ),
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
        return $withPrefix ? GroupTableMap::CLASS_DEFAULT : GroupTableMap::OM_CLASS;
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
     * @return array           (Group object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = GroupTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = GroupTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + GroupTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = GroupTableMap::OM_CLASS;
            /** @var Group $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            GroupTableMap::addInstanceToPool($obj, $key);
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
            $key = GroupTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = GroupTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Group $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                GroupTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(GroupTableMap::COL_ID);
            $criteria->addSelectColumn(GroupTableMap::COL_USER_ID);
            $criteria->addSelectColumn(GroupTableMap::COL_NAME);
            $criteria->addSelectColumn(GroupTableMap::COL_IS_GUEST);
            $criteria->addSelectColumn(GroupTableMap::COL_IS_DEFAULT);
            $criteria->addSelectColumn(GroupTableMap::COL_IS_ACTIVE);
            $criteria->addSelectColumn(GroupTableMap::COL_IS_SYSTEM);
            $criteria->addSelectColumn(GroupTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(GroupTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.USER_ID');
            $criteria->addSelectColumn($alias . '.NAME');
            $criteria->addSelectColumn($alias . '.IS_GUEST');
            $criteria->addSelectColumn($alias . '.IS_DEFAULT');
            $criteria->addSelectColumn($alias . '.IS_ACTIVE');
            $criteria->addSelectColumn($alias . '.IS_SYSTEM');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
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
        return Propel::getServiceContainer()->getDatabaseMap(GroupTableMap::DATABASE_NAME)->getTable(GroupTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(GroupTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(GroupTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new GroupTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Group or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Group object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\Group) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(GroupTableMap::DATABASE_NAME);
            $criteria->add(GroupTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = GroupQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            GroupTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                GroupTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the kk_group table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return GroupQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Group or Criteria object.
     *
     * @param mixed               $criteria Criteria or Group object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Group object
        }

        if ($criteria->containsKey(GroupTableMap::COL_ID) && $criteria->keyContainsValue(GroupTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.GroupTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = GroupQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // GroupTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
GroupTableMap::buildTableMap();
