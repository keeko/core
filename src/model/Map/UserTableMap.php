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
    const CLASS_NAME = 'keeko.core.model.Map.UserTableMap';

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
    const CLASS_DEFAULT = 'keeko.core.model.User';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 22;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 22;

    /**
     * the column name for the ID field
     */
    const COL_ID = 'kk_user.ID';

    /**
     * the column name for the LOGIN_NAME field
     */
    const COL_LOGIN_NAME = 'kk_user.LOGIN_NAME';

    /**
     * the column name for the PASSWORD field
     */
    const COL_PASSWORD = 'kk_user.PASSWORD';

    /**
     * the column name for the GIVEN_NAME field
     */
    const COL_GIVEN_NAME = 'kk_user.GIVEN_NAME';

    /**
     * the column name for the FAMILY_NAME field
     */
    const COL_FAMILY_NAME = 'kk_user.FAMILY_NAME';

    /**
     * the column name for the DISPLAY_NAME field
     */
    const COL_DISPLAY_NAME = 'kk_user.DISPLAY_NAME';

    /**
     * the column name for the EMAIL field
     */
    const COL_EMAIL = 'kk_user.EMAIL';

    /**
     * the column name for the COUNTRY_ISO_NR field
     */
    const COL_COUNTRY_ISO_NR = 'kk_user.COUNTRY_ISO_NR';

    /**
     * the column name for the SUBDIVISION_ID field
     */
    const COL_SUBDIVISION_ID = 'kk_user.SUBDIVISION_ID';

    /**
     * the column name for the ADDRESS field
     */
    const COL_ADDRESS = 'kk_user.ADDRESS';

    /**
     * the column name for the ADDRESS2 field
     */
    const COL_ADDRESS2 = 'kk_user.ADDRESS2';

    /**
     * the column name for the BIRTHDAY field
     */
    const COL_BIRTHDAY = 'kk_user.BIRTHDAY';

    /**
     * the column name for the SEX field
     */
    const COL_SEX = 'kk_user.SEX';

    /**
     * the column name for the CITY field
     */
    const COL_CITY = 'kk_user.CITY';

    /**
     * the column name for the POSTAL_CODE field
     */
    const COL_POSTAL_CODE = 'kk_user.POSTAL_CODE';

    /**
     * the column name for the PASSWORD_RECOVER_CODE field
     */
    const COL_PASSWORD_RECOVER_CODE = 'kk_user.PASSWORD_RECOVER_CODE';

    /**
     * the column name for the PASSWORD_RECOVER_TIME field
     */
    const COL_PASSWORD_RECOVER_TIME = 'kk_user.PASSWORD_RECOVER_TIME';

    /**
     * the column name for the LOCATION_STATUS field
     */
    const COL_LOCATION_STATUS = 'kk_user.LOCATION_STATUS';

    /**
     * the column name for the LATITUDE field
     */
    const COL_LATITUDE = 'kk_user.LATITUDE';

    /**
     * the column name for the LONGITUDE field
     */
    const COL_LONGITUDE = 'kk_user.LONGITUDE';

    /**
     * the column name for the CREATED_AT field
     */
    const COL_CREATED_AT = 'kk_user.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const COL_UPDATED_AT = 'kk_user.UPDATED_AT';

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
        self::TYPE_PHPNAME       => array('Id', 'LoginName', 'Password', 'GivenName', 'FamilyName', 'DisplayName', 'Email', 'CountryIsoNr', 'SubdivisionId', 'Address', 'Address2', 'Birthday', 'Sex', 'City', 'PostalCode', 'PasswordRecoverCode', 'PasswordRecoverTime', 'LocationStatus', 'Latitude', 'Longitude', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'loginName', 'password', 'givenName', 'familyName', 'displayName', 'email', 'countryIsoNr', 'subdivisionId', 'address', 'address2', 'birthday', 'sex', 'city', 'postalCode', 'passwordRecoverCode', 'passwordRecoverTime', 'locationStatus', 'latitude', 'longitude', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_LOGIN_NAME, UserTableMap::COL_PASSWORD, UserTableMap::COL_GIVEN_NAME, UserTableMap::COL_FAMILY_NAME, UserTableMap::COL_DISPLAY_NAME, UserTableMap::COL_EMAIL, UserTableMap::COL_COUNTRY_ISO_NR, UserTableMap::COL_SUBDIVISION_ID, UserTableMap::COL_ADDRESS, UserTableMap::COL_ADDRESS2, UserTableMap::COL_BIRTHDAY, UserTableMap::COL_SEX, UserTableMap::COL_CITY, UserTableMap::COL_POSTAL_CODE, UserTableMap::COL_PASSWORD_RECOVER_CODE, UserTableMap::COL_PASSWORD_RECOVER_TIME, UserTableMap::COL_LOCATION_STATUS, UserTableMap::COL_LATITUDE, UserTableMap::COL_LONGITUDE, UserTableMap::COL_CREATED_AT, UserTableMap::COL_UPDATED_AT, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID', 'COL_LOGIN_NAME', 'COL_PASSWORD', 'COL_GIVEN_NAME', 'COL_FAMILY_NAME', 'COL_DISPLAY_NAME', 'COL_EMAIL', 'COL_COUNTRY_ISO_NR', 'COL_SUBDIVISION_ID', 'COL_ADDRESS', 'COL_ADDRESS2', 'COL_BIRTHDAY', 'COL_SEX', 'COL_CITY', 'COL_POSTAL_CODE', 'COL_PASSWORD_RECOVER_CODE', 'COL_PASSWORD_RECOVER_TIME', 'COL_LOCATION_STATUS', 'COL_LATITUDE', 'COL_LONGITUDE', 'COL_CREATED_AT', 'COL_UPDATED_AT', ),
        self::TYPE_FIELDNAME     => array('id', 'login_name', 'password', 'given_name', 'family_name', 'display_name', 'email', 'country_iso_nr', 'subdivision_id', 'address', 'address2', 'birthday', 'sex', 'city', 'postal_code', 'password_recover_code', 'password_recover_time', 'location_status', 'latitude', 'longitude', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'LoginName' => 1, 'Password' => 2, 'GivenName' => 3, 'FamilyName' => 4, 'DisplayName' => 5, 'Email' => 6, 'CountryIsoNr' => 7, 'SubdivisionId' => 8, 'Address' => 9, 'Address2' => 10, 'Birthday' => 11, 'Sex' => 12, 'City' => 13, 'PostalCode' => 14, 'PasswordRecoverCode' => 15, 'PasswordRecoverTime' => 16, 'LocationStatus' => 17, 'Latitude' => 18, 'Longitude' => 19, 'CreatedAt' => 20, 'UpdatedAt' => 21, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'loginName' => 1, 'password' => 2, 'givenName' => 3, 'familyName' => 4, 'displayName' => 5, 'email' => 6, 'countryIsoNr' => 7, 'subdivisionId' => 8, 'address' => 9, 'address2' => 10, 'birthday' => 11, 'sex' => 12, 'city' => 13, 'postalCode' => 14, 'passwordRecoverCode' => 15, 'passwordRecoverTime' => 16, 'locationStatus' => 17, 'latitude' => 18, 'longitude' => 19, 'createdAt' => 20, 'updatedAt' => 21, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_LOGIN_NAME => 1, UserTableMap::COL_PASSWORD => 2, UserTableMap::COL_GIVEN_NAME => 3, UserTableMap::COL_FAMILY_NAME => 4, UserTableMap::COL_DISPLAY_NAME => 5, UserTableMap::COL_EMAIL => 6, UserTableMap::COL_COUNTRY_ISO_NR => 7, UserTableMap::COL_SUBDIVISION_ID => 8, UserTableMap::COL_ADDRESS => 9, UserTableMap::COL_ADDRESS2 => 10, UserTableMap::COL_BIRTHDAY => 11, UserTableMap::COL_SEX => 12, UserTableMap::COL_CITY => 13, UserTableMap::COL_POSTAL_CODE => 14, UserTableMap::COL_PASSWORD_RECOVER_CODE => 15, UserTableMap::COL_PASSWORD_RECOVER_TIME => 16, UserTableMap::COL_LOCATION_STATUS => 17, UserTableMap::COL_LATITUDE => 18, UserTableMap::COL_LONGITUDE => 19, UserTableMap::COL_CREATED_AT => 20, UserTableMap::COL_UPDATED_AT => 21, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID' => 0, 'COL_LOGIN_NAME' => 1, 'COL_PASSWORD' => 2, 'COL_GIVEN_NAME' => 3, 'COL_FAMILY_NAME' => 4, 'COL_DISPLAY_NAME' => 5, 'COL_EMAIL' => 6, 'COL_COUNTRY_ISO_NR' => 7, 'COL_SUBDIVISION_ID' => 8, 'COL_ADDRESS' => 9, 'COL_ADDRESS2' => 10, 'COL_BIRTHDAY' => 11, 'COL_SEX' => 12, 'COL_CITY' => 13, 'COL_POSTAL_CODE' => 14, 'COL_PASSWORD_RECOVER_CODE' => 15, 'COL_PASSWORD_RECOVER_TIME' => 16, 'COL_LOCATION_STATUS' => 17, 'COL_LATITUDE' => 18, 'COL_LONGITUDE' => 19, 'COL_CREATED_AT' => 20, 'COL_UPDATED_AT' => 21, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'login_name' => 1, 'password' => 2, 'given_name' => 3, 'family_name' => 4, 'display_name' => 5, 'email' => 6, 'country_iso_nr' => 7, 'subdivision_id' => 8, 'address' => 9, 'address2' => 10, 'birthday' => 11, 'sex' => 12, 'city' => 13, 'postal_code' => 14, 'password_recover_code' => 15, 'password_recover_time' => 16, 'location_status' => 17, 'latitude' => 18, 'longitude' => 19, 'created_at' => 20, 'updated_at' => 21, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
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
        $this->setClassName('\\keeko\\core\\model\\User');
        $this->setPackage('keeko.core.model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('LOGIN_NAME', 'LoginName', 'VARCHAR', true, 100, null);
        $this->addColumn('PASSWORD', 'Password', 'VARCHAR', true, 100, null);
        $this->addColumn('GIVEN_NAME', 'GivenName', 'VARCHAR', false, 100, null);
        $this->addColumn('FAMILY_NAME', 'FamilyName', 'VARCHAR', false, 100, null);
        $this->addColumn('DISPLAY_NAME', 'DisplayName', 'VARCHAR', false, 100, null);
        $this->addColumn('EMAIL', 'Email', 'VARCHAR', true, 255, null);
        $this->addForeignKey('COUNTRY_ISO_NR', 'CountryIsoNr', 'INTEGER', 'kk_country', 'ISO_NR', false, null, null);
        $this->addForeignKey('SUBDIVISION_ID', 'SubdivisionId', 'INTEGER', 'kk_subdivision', 'ID', false, null, null);
        $this->addColumn('ADDRESS', 'Address', 'LONGVARCHAR', false, null, null);
        $this->addColumn('ADDRESS2', 'Address2', 'LONGVARCHAR', false, null, null);
        $this->addColumn('BIRTHDAY', 'Birthday', 'DATE', false, null, null);
        $this->addColumn('SEX', 'Sex', 'TINYINT', false, null, null);
        $this->addColumn('CITY', 'City', 'VARCHAR', false, 128, null);
        $this->addColumn('POSTAL_CODE', 'PostalCode', 'VARCHAR', false, 45, null);
        $this->addColumn('PASSWORD_RECOVER_CODE', 'PasswordRecoverCode', 'VARCHAR', false, 32, null);
        $this->addColumn('PASSWORD_RECOVER_TIME', 'PasswordRecoverTime', 'TIMESTAMP', false, null, null);
        $this->addColumn('LOCATION_STATUS', 'LocationStatus', 'TINYINT', false, 2, null);
        $this->addColumn('LATITUDE', 'Latitude', 'FLOAT', false, 10, null);
        $this->addColumn('LONGITUDE', 'Longitude', 'FLOAT', false, 10, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', '\\keeko\\core\\model\\Country', RelationMap::MANY_TO_ONE, array('country_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('Subdivision', '\\keeko\\core\\model\\Subdivision', RelationMap::MANY_TO_ONE, array('subdivision_id' => 'id', ), null, null);
        $this->addRelation('Group', '\\keeko\\core\\model\\Group', RelationMap::ONE_TO_MANY, array('id' => 'owner_id', ), 'RESTRICT', null, 'Groups');
        $this->addRelation('GroupUser', '\\keeko\\core\\model\\GroupUser', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'RESTRICT', null, 'GroupUsers');
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
            'validate' => array('rule1' => array ('column' => 'login_name','validator' => 'NotNull',), 'rule2' => array ('column' => 'email','validator' => 'NotNull',), 'rule3' => array ('column' => 'email','validator' => 'Email',), 'rule4' => array ('column' => 'password','validator' => 'NotNull',), ),
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
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
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
            $criteria->addSelectColumn(UserTableMap::COL_LOGIN_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_GIVEN_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_FAMILY_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_DISPLAY_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_COUNTRY_ISO_NR);
            $criteria->addSelectColumn(UserTableMap::COL_SUBDIVISION_ID);
            $criteria->addSelectColumn(UserTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(UserTableMap::COL_ADDRESS2);
            $criteria->addSelectColumn(UserTableMap::COL_BIRTHDAY);
            $criteria->addSelectColumn(UserTableMap::COL_SEX);
            $criteria->addSelectColumn(UserTableMap::COL_CITY);
            $criteria->addSelectColumn(UserTableMap::COL_POSTAL_CODE);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD_RECOVER_CODE);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD_RECOVER_TIME);
            $criteria->addSelectColumn(UserTableMap::COL_LOCATION_STATUS);
            $criteria->addSelectColumn(UserTableMap::COL_LATITUDE);
            $criteria->addSelectColumn(UserTableMap::COL_LONGITUDE);
            $criteria->addSelectColumn(UserTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(UserTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.LOGIN_NAME');
            $criteria->addSelectColumn($alias . '.PASSWORD');
            $criteria->addSelectColumn($alias . '.GIVEN_NAME');
            $criteria->addSelectColumn($alias . '.FAMILY_NAME');
            $criteria->addSelectColumn($alias . '.DISPLAY_NAME');
            $criteria->addSelectColumn($alias . '.EMAIL');
            $criteria->addSelectColumn($alias . '.COUNTRY_ISO_NR');
            $criteria->addSelectColumn($alias . '.SUBDIVISION_ID');
            $criteria->addSelectColumn($alias . '.ADDRESS');
            $criteria->addSelectColumn($alias . '.ADDRESS2');
            $criteria->addSelectColumn($alias . '.BIRTHDAY');
            $criteria->addSelectColumn($alias . '.SEX');
            $criteria->addSelectColumn($alias . '.CITY');
            $criteria->addSelectColumn($alias . '.POSTAL_CODE');
            $criteria->addSelectColumn($alias . '.PASSWORD_RECOVER_CODE');
            $criteria->addSelectColumn($alias . '.PASSWORD_RECOVER_TIME');
            $criteria->addSelectColumn($alias . '.LOCATION_STATUS');
            $criteria->addSelectColumn($alias . '.LATITUDE');
            $criteria->addSelectColumn($alias . '.LONGITUDE');
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
