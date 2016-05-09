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
use keeko\core\model\User;
use keeko\core\model\UserQuery;


/**
 * This class defines the structure of the 'kk_user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'keeko';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'kk_user';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\keeko\\core\\model\\User';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'User';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 15;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 15;

    /**
     * the column name for the id field
     */
    const COL_ID = 'kk_user.id';

    /**
     * the column name for the user_name field
     */
    const COL_USER_NAME = 'kk_user.user_name';

    /**
     * the column name for the password field
     */
    const COL_PASSWORD = 'kk_user.password';

    /**
     * the column name for the given_name field
     */
    const COL_GIVEN_NAME = 'kk_user.given_name';

    /**
     * the column name for the family_name field
     */
    const COL_FAMILY_NAME = 'kk_user.family_name';

    /**
     * the column name for the nick_name field
     */
    const COL_NICK_NAME = 'kk_user.nick_name';

    /**
     * the column name for the display_name field
     */
    const COL_DISPLAY_NAME = 'kk_user.display_name';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'kk_user.email';

    /**
     * the column name for the birth field
     */
    const COL_BIRTH = 'kk_user.birth';

    /**
     * the column name for the sex field
     */
    const COL_SEX = 'kk_user.sex';

    /**
     * the column name for the slug field
     */
    const COL_SLUG = 'kk_user.slug';

    /**
     * the column name for the password_recover_token field
     */
    const COL_PASSWORD_RECOVER_TOKEN = 'kk_user.password_recover_token';

    /**
     * the column name for the password_recover_time field
     */
    const COL_PASSWORD_RECOVER_TIME = 'kk_user.password_recover_time';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'kk_user.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'kk_user.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'UserName', 'Password', 'GivenName', 'FamilyName', 'NickName', 'DisplayName', 'Email', 'Birth', 'Sex', 'Slug', 'PasswordRecoverToken', 'PasswordRecoverTime', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'userName', 'password', 'givenName', 'familyName', 'nickName', 'displayName', 'email', 'birth', 'sex', 'slug', 'passwordRecoverToken', 'passwordRecoverTime', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_USER_NAME, UserTableMap::COL_PASSWORD, UserTableMap::COL_GIVEN_NAME, UserTableMap::COL_FAMILY_NAME, UserTableMap::COL_NICK_NAME, UserTableMap::COL_DISPLAY_NAME, UserTableMap::COL_EMAIL, UserTableMap::COL_BIRTH, UserTableMap::COL_SEX, UserTableMap::COL_SLUG, UserTableMap::COL_PASSWORD_RECOVER_TOKEN, UserTableMap::COL_PASSWORD_RECOVER_TIME, UserTableMap::COL_CREATED_AT, UserTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'user_name', 'password', 'given_name', 'family_name', 'nick_name', 'display_name', 'email', 'birth', 'sex', 'slug', 'password_recover_token', 'password_recover_time', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserName' => 1, 'Password' => 2, 'GivenName' => 3, 'FamilyName' => 4, 'NickName' => 5, 'DisplayName' => 6, 'Email' => 7, 'Birth' => 8, 'Sex' => 9, 'Slug' => 10, 'PasswordRecoverToken' => 11, 'PasswordRecoverTime' => 12, 'CreatedAt' => 13, 'UpdatedAt' => 14, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'userName' => 1, 'password' => 2, 'givenName' => 3, 'familyName' => 4, 'nickName' => 5, 'displayName' => 6, 'email' => 7, 'birth' => 8, 'sex' => 9, 'slug' => 10, 'passwordRecoverToken' => 11, 'passwordRecoverTime' => 12, 'createdAt' => 13, 'updatedAt' => 14, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_USER_NAME => 1, UserTableMap::COL_PASSWORD => 2, UserTableMap::COL_GIVEN_NAME => 3, UserTableMap::COL_FAMILY_NAME => 4, UserTableMap::COL_NICK_NAME => 5, UserTableMap::COL_DISPLAY_NAME => 6, UserTableMap::COL_EMAIL => 7, UserTableMap::COL_BIRTH => 8, UserTableMap::COL_SEX => 9, UserTableMap::COL_SLUG => 10, UserTableMap::COL_PASSWORD_RECOVER_TOKEN => 11, UserTableMap::COL_PASSWORD_RECOVER_TIME => 12, UserTableMap::COL_CREATED_AT => 13, UserTableMap::COL_UPDATED_AT => 14, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'user_name' => 1, 'password' => 2, 'given_name' => 3, 'family_name' => 4, 'nick_name' => 5, 'display_name' => 6, 'email' => 7, 'birth' => 8, 'sex' => 9, 'slug' => 10, 'password_recover_token' => 11, 'password_recover_time' => 12, 'created_at' => 13, 'updated_at' => 14, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
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
        $this->setName('kk_user');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\keeko\\core\\model\\User');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('user_name', 'UserName', 'VARCHAR', false, 100, null);
        $this->addColumn('password', 'Password', 'VARCHAR', false, 100, null);
        $this->addColumn('given_name', 'GivenName', 'VARCHAR', false, 100, null);
        $this->addColumn('family_name', 'FamilyName', 'VARCHAR', false, 100, null);
        $this->addColumn('nick_name', 'NickName', 'VARCHAR', false, 100, null);
        $this->addColumn('display_name', 'DisplayName', 'VARCHAR', false, 100, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('birth', 'Birth', 'DATE', false, null, null);
        $this->addColumn('sex', 'Sex', 'TINYINT', false, null, -1);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 100, null);
        $this->addColumn('password_recover_token', 'PasswordRecoverToken', 'VARCHAR', false, 32, null);
        $this->addColumn('password_recover_time', 'PasswordRecoverTime', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Session', '\\keeko\\core\\model\\Session', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Sessions', false);
        $this->addRelation('UserGroup', '\\keeko\\core\\model\\UserGroup', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), 'RESTRICT', null, 'UserGroups', false);
        $this->addRelation('Activity', '\\keeko\\core\\model\\Activity', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':actor_id',
    1 => ':id',
  ),
), null, null, 'Activities', false);
        $this->addRelation('Group', '\\keeko\\core\\model\\Group', RelationMap::MANY_TO_MANY, array(), 'RESTRICT', null, 'Groups');
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
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
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
     * @return array           (User object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
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
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_USER_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_GIVEN_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_FAMILY_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_NICK_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_DISPLAY_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_BIRTH);
            $criteria->addSelectColumn(UserTableMap::COL_SEX);
            $criteria->addSelectColumn(UserTableMap::COL_SLUG);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD_RECOVER_TOKEN);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD_RECOVER_TIME);
            $criteria->addSelectColumn(UserTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(UserTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_name');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.given_name');
            $criteria->addSelectColumn($alias . '.family_name');
            $criteria->addSelectColumn($alias . '.nick_name');
            $criteria->addSelectColumn($alias . '.display_name');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.birth');
            $criteria->addSelectColumn($alias . '.sex');
            $criteria->addSelectColumn($alias . '.slug');
            $criteria->addSelectColumn($alias . '.password_recover_token');
            $criteria->addSelectColumn($alias . '.password_recover_time');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or User object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \keeko\core\model\User) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the kk_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed               $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserTableMap::COL_ID) && $criteria->keyContainsValue(UserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserTableMap::buildTableMap();
