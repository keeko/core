<?php

namespace keeko\core\model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\DefaultTranslator;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use keeko\core\model\Country as ChildCountry;
use keeko\core\model\CountryQuery as ChildCountryQuery;
use keeko\core\model\Group as ChildGroup;
use keeko\core\model\GroupQuery as ChildGroupQuery;
use keeko\core\model\GroupUser as ChildGroupUser;
use keeko\core\model\GroupUserQuery as ChildGroupUserQuery;
use keeko\core\model\Subdivision as ChildSubdivision;
use keeko\core\model\SubdivisionQuery as ChildSubdivisionQuery;
use keeko\core\model\User as ChildUser;
use keeko\core\model\UserQuery as ChildUserQuery;
use keeko\core\model\Map\UserTableMap;

abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\UserTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the login_name field.
     * @var        string
     */
    protected $login_name;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the given_name field.
     * @var        string
     */
    protected $given_name;

    /**
     * The value for the family_name field.
     * @var        string
     */
    protected $family_name;

    /**
     * The value for the display_name field.
     * @var        string
     */
    protected $display_name;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the country_iso_nr field.
     * @var        int
     */
    protected $country_iso_nr;

    /**
     * The value for the subdivision_id field.
     * @var        int
     */
    protected $subdivision_id;

    /**
     * The value for the address field.
     * @var        string
     */
    protected $address;

    /**
     * The value for the address2 field.
     * @var        string
     */
    protected $address2;

    /**
     * The value for the birthday field.
     * @var        \DateTime
     */
    protected $birthday;

    /**
     * The value for the sex field.
     * @var        int
     */
    protected $sex;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the postal_code field.
     * @var        string
     */
    protected $postal_code;

    /**
     * The value for the password_recover_code field.
     * @var        string
     */
    protected $password_recover_code;

    /**
     * The value for the password_recover_time field.
     * @var        \DateTime
     */
    protected $password_recover_time;

    /**
     * The value for the location_status field.
     * @var        int
     */
    protected $location_status;

    /**
     * The value for the latitude field.
     * @var        double
     */
    protected $latitude;

    /**
     * The value for the longitude field.
     * @var        double
     */
    protected $longitude;

    /**
     * The value for the created_at field.
     * @var        \DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        \DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildCountry
     */
    protected $aCountry;

    /**
     * @var        ChildSubdivision
     */
    protected $aSubdivision;

    /**
     * @var        ObjectCollection|ChildGroup[] Collection to store aggregation of ChildGroup objects.
     */
    protected $collGroups;
    protected $collGroupsPartial;

    /**
     * @var        ObjectCollection|ChildGroupUser[] Collection to store aggregation of ChildGroupUser objects.
     */
    protected $collGroupUsers;
    protected $collGroupUsersPartial;

    /**
     * @var        ObjectCollection|ChildGroup[] Cross Collection to store aggregation of ChildGroup objects.
     */
    protected $collGroups;

    /**
     * @var bool
     */
    protected $collGroupsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // validate behavior

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * ConstraintViolationList object
     *
     * @see     http://api.symfony.com/2.0/Symfony/Component/Validator/ConstraintViolationList.html
     * @var     ConstraintViolationList
     */
    protected $validationFailures;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroup[]
     */
    protected $groupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroup[]
     */
    protected $groupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroupUser[]
     */
    protected $groupUsersScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\User object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|User The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [login_name] column value.
     *
     * @return string
     */
    public function getLoginName()
    {
        return $this->login_name;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [given_name] column value.
     *
     * @return string
     */
    public function getGivenName()
    {
        return $this->given_name;
    }

    /**
     * Get the [family_name] column value.
     *
     * @return string
     */
    public function getFamilyName()
    {
        return $this->family_name;
    }

    /**
     * Get the [display_name] column value.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [country_iso_nr] column value.
     *
     * @return int
     */
    public function getCountryIsoNr()
    {
        return $this->country_iso_nr;
    }

    /**
     * Get the [subdivision_id] column value.
     *
     * @return int
     */
    public function getSubdivisionId()
    {
        return $this->subdivision_id;
    }

    /**
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get the [address2] column value.
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Get the [optionally formatted] temporal [birthday] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBirthday($format = NULL)
    {
        if ($format === null) {
            return $this->birthday;
        } else {
            return $this->birthday instanceof \DateTime ? $this->birthday->format($format) : null;
        }
    }

    /**
     * Get the [sex] column value.
     *
     * @return int
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the [postal_code] column value.
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Get the [password_recover_code] column value.
     *
     * @return string
     */
    public function getPasswordRecoverCode()
    {
        return $this->password_recover_code;
    }

    /**
     * Get the [optionally formatted] temporal [password_recover_time] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPasswordRecoverTime($format = NULL)
    {
        if ($format === null) {
            return $this->password_recover_time;
        } else {
            return $this->password_recover_time instanceof \DateTime ? $this->password_recover_time->format($format) : null;
        }
    }

    /**
     * Get the [location_status] column value.
     *
     * @return int
     */
    public function getLocationStatus()
    {
        return $this->location_status;
    }

    /**
     * Get the [latitude] column value.
     *
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get the [longitude] column value.
     *
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('LoginName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->login_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('GivenName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->given_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('FamilyName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->family_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('DisplayName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->display_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('CountryIsoNr', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_iso_nr = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('SubdivisionId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->subdivision_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('Address2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('Birthday', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->birthday = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('Sex', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sex = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : UserTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : UserTableMap::translateFieldName('PostalCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->postal_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : UserTableMap::translateFieldName('PasswordRecoverCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password_recover_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : UserTableMap::translateFieldName('PasswordRecoverTime', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->password_recover_time = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : UserTableMap::translateFieldName('LocationStatus', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : UserTableMap::translateFieldName('Latitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->latitude = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : UserTableMap::translateFieldName('Longitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->longitude = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : UserTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : UserTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 22; // 22 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\User'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aCountry !== null && $this->country_iso_nr !== $this->aCountry->getIsoNr()) {
            $this->aCountry = null;
        }
        if ($this->aSubdivision !== null && $this->subdivision_id !== $this->aSubdivision->getId()) {
            $this->aSubdivision = null;
        }
    } // ensureConsistency

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [login_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setLoginName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->login_name !== $v) {
            $this->login_name = $v;
            $this->modifiedColumns[UserTableMap::COL_LOGIN_NAME] = true;
        }

        return $this;
    } // setLoginName()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [given_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setGivenName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->given_name !== $v) {
            $this->given_name = $v;
            $this->modifiedColumns[UserTableMap::COL_GIVEN_NAME] = true;
        }

        return $this;
    } // setGivenName()

    /**
     * Set the value of [family_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setFamilyName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->family_name !== $v) {
            $this->family_name = $v;
            $this->modifiedColumns[UserTableMap::COL_FAMILY_NAME] = true;
        }

        return $this;
    } // setFamilyName()

    /**
     * Set the value of [display_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setDisplayName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->display_name !== $v) {
            $this->display_name = $v;
            $this->modifiedColumns[UserTableMap::COL_DISPLAY_NAME] = true;
        }

        return $this;
    } // setDisplayName()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [country_iso_nr] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setCountryIsoNr($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->country_iso_nr !== $v) {
            $this->country_iso_nr = $v;
            $this->modifiedColumns[UserTableMap::COL_COUNTRY_ISO_NR] = true;
        }

        if ($this->aCountry !== null && $this->aCountry->getIsoNr() !== $v) {
            $this->aCountry = null;
        }

        return $this;
    } // setCountryIsoNr()

    /**
     * Set the value of [subdivision_id] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setSubdivisionId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->subdivision_id !== $v) {
            $this->subdivision_id = $v;
            $this->modifiedColumns[UserTableMap::COL_SUBDIVISION_ID] = true;
        }

        if ($this->aSubdivision !== null && $this->aSubdivision->getId() !== $v) {
            $this->aSubdivision = null;
        }

        return $this;
    } // setSubdivisionId()

    /**
     * Set the value of [address] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[UserTableMap::COL_ADDRESS] = true;
        }

        return $this;
    } // setAddress()

    /**
     * Set the value of [address2] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setAddress2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address2 !== $v) {
            $this->address2 = $v;
            $this->modifiedColumns[UserTableMap::COL_ADDRESS2] = true;
        }

        return $this;
    } // setAddress2()

    /**
     * Sets the value of [birthday] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setBirthday($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->birthday !== null || $dt !== null) {
            if ($dt !== $this->birthday) {
                $this->birthday = $dt;
                $this->modifiedColumns[UserTableMap::COL_BIRTHDAY] = true;
            }
        } // if either are not null

        return $this;
    } // setBirthday()

    /**
     * Set the value of [sex] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setSex($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sex !== $v) {
            $this->sex = $v;
            $this->modifiedColumns[UserTableMap::COL_SEX] = true;
        }

        return $this;
    } // setSex()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[UserTableMap::COL_CITY] = true;
        }

        return $this;
    } // setCity()

    /**
     * Set the value of [postal_code] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setPostalCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->postal_code !== $v) {
            $this->postal_code = $v;
            $this->modifiedColumns[UserTableMap::COL_POSTAL_CODE] = true;
        }

        return $this;
    } // setPostalCode()

    /**
     * Set the value of [password_recover_code] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setPasswordRecoverCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password_recover_code !== $v) {
            $this->password_recover_code = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD_RECOVER_CODE] = true;
        }

        return $this;
    } // setPasswordRecoverCode()

    /**
     * Sets the value of [password_recover_time] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setPasswordRecoverTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->password_recover_time !== null || $dt !== null) {
            if ($dt !== $this->password_recover_time) {
                $this->password_recover_time = $dt;
                $this->modifiedColumns[UserTableMap::COL_PASSWORD_RECOVER_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setPasswordRecoverTime()

    /**
     * Set the value of [location_status] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setLocationStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->location_status !== $v) {
            $this->location_status = $v;
            $this->modifiedColumns[UserTableMap::COL_LOCATION_STATUS] = true;
        }

        return $this;
    } // setLocationStatus()

    /**
     * Set the value of [latitude] column.
     *
     * @param  double $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setLatitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->latitude !== $v) {
            $this->latitude = $v;
            $this->modifiedColumns[UserTableMap::COL_LATITUDE] = true;
        }

        return $this;
    } // setLatitude()

    /**
     * Set the value of [longitude] column.
     *
     * @param  double $v new value
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setLongitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->longitude !== $v) {
            $this->longitude = $v;
            $this->modifiedColumns[UserTableMap::COL_LONGITUDE] = true;
        }

        return $this;
    } // setLongitude()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[UserTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCountry = null;
            $this->aSubdivision = null;
            $this->collGroups = null;

            $this->collGroupUsers = null;

            $this->collGroups = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCountry !== null) {
                if ($this->aCountry->isModified() || $this->aCountry->isNew()) {
                    $affectedRows += $this->aCountry->save($con);
                }
                $this->setCountry($this->aCountry);
            }

            if ($this->aSubdivision !== null) {
                if ($this->aSubdivision->isModified() || $this->aSubdivision->isNew()) {
                    $affectedRows += $this->aSubdivision->save($con);
                }
                $this->setSubdivision($this->aSubdivision);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->groupsScheduledForDeletion !== null) {
                if (!$this->groupsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->groupsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \keeko\core\model\GroupUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->groupsScheduledForDeletion = null;
                }

            }

            if ($this->collGroups) {
                foreach ($this->collGroups as $group) {
                    if (!$group->isDeleted() && ($group->isNew() || $group->isModified())) {
                        $group->save($con);
                    }
                }
            }


            if ($this->groupsScheduledForDeletion !== null) {
                if (!$this->groupsScheduledForDeletion->isEmpty()) {
                    foreach ($this->groupsScheduledForDeletion as $group) {
                        // need to save related object because we set the relation to null
                        $group->save($con);
                    }
                    $this->groupsScheduledForDeletion = null;
                }
            }

            if ($this->collGroups !== null) {
                foreach ($this->collGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->groupUsersScheduledForDeletion !== null) {
                if (!$this->groupUsersScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\GroupUserQuery::create()
                        ->filterByPrimaryKeys($this->groupUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->groupUsersScheduledForDeletion = null;
                }
            }

            if ($this->collGroupUsers !== null) {
                foreach ($this->collGroupUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGIN_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'LOGIN_NAME';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'PASSWORD';
        }
        if ($this->isColumnModified(UserTableMap::COL_GIVEN_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'GIVEN_NAME';
        }
        if ($this->isColumnModified(UserTableMap::COL_FAMILY_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'FAMILY_NAME';
        }
        if ($this->isColumnModified(UserTableMap::COL_DISPLAY_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'DISPLAY_NAME';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'EMAIL';
        }
        if ($this->isColumnModified(UserTableMap::COL_COUNTRY_ISO_NR)) {
            $modifiedColumns[':p' . $index++]  = 'COUNTRY_ISO_NR';
        }
        if ($this->isColumnModified(UserTableMap::COL_SUBDIVISION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'SUBDIVISION_ID';
        }
        if ($this->isColumnModified(UserTableMap::COL_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'ADDRESS';
        }
        if ($this->isColumnModified(UserTableMap::COL_ADDRESS2)) {
            $modifiedColumns[':p' . $index++]  = 'ADDRESS2';
        }
        if ($this->isColumnModified(UserTableMap::COL_BIRTHDAY)) {
            $modifiedColumns[':p' . $index++]  = 'BIRTHDAY';
        }
        if ($this->isColumnModified(UserTableMap::COL_SEX)) {
            $modifiedColumns[':p' . $index++]  = 'SEX';
        }
        if ($this->isColumnModified(UserTableMap::COL_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'CITY';
        }
        if ($this->isColumnModified(UserTableMap::COL_POSTAL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'POSTAL_CODE';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RECOVER_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'PASSWORD_RECOVER_CODE';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RECOVER_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'PASSWORD_RECOVER_TIME';
        }
        if ($this->isColumnModified(UserTableMap::COL_LOCATION_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'LOCATION_STATUS';
        }
        if ($this->isColumnModified(UserTableMap::COL_LATITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'LATITUDE';
        }
        if ($this->isColumnModified(UserTableMap::COL_LONGITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'LONGITUDE';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }

        $sql = sprintf(
            'INSERT INTO kk_user (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'LOGIN_NAME':
                        $stmt->bindValue($identifier, $this->login_name, PDO::PARAM_STR);
                        break;
                    case 'PASSWORD':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'GIVEN_NAME':
                        $stmt->bindValue($identifier, $this->given_name, PDO::PARAM_STR);
                        break;
                    case 'FAMILY_NAME':
                        $stmt->bindValue($identifier, $this->family_name, PDO::PARAM_STR);
                        break;
                    case 'DISPLAY_NAME':
                        $stmt->bindValue($identifier, $this->display_name, PDO::PARAM_STR);
                        break;
                    case 'EMAIL':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'COUNTRY_ISO_NR':
                        $stmt->bindValue($identifier, $this->country_iso_nr, PDO::PARAM_INT);
                        break;
                    case 'SUBDIVISION_ID':
                        $stmt->bindValue($identifier, $this->subdivision_id, PDO::PARAM_INT);
                        break;
                    case 'ADDRESS':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case 'ADDRESS2':
                        $stmt->bindValue($identifier, $this->address2, PDO::PARAM_STR);
                        break;
                    case 'BIRTHDAY':
                        $stmt->bindValue($identifier, $this->birthday ? $this->birthday->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'SEX':
                        $stmt->bindValue($identifier, $this->sex, PDO::PARAM_INT);
                        break;
                    case 'CITY':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case 'POSTAL_CODE':
                        $stmt->bindValue($identifier, $this->postal_code, PDO::PARAM_STR);
                        break;
                    case 'PASSWORD_RECOVER_CODE':
                        $stmt->bindValue($identifier, $this->password_recover_code, PDO::PARAM_STR);
                        break;
                    case 'PASSWORD_RECOVER_TIME':
                        $stmt->bindValue($identifier, $this->password_recover_time ? $this->password_recover_time->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'LOCATION_STATUS':
                        $stmt->bindValue($identifier, $this->location_status, PDO::PARAM_INT);
                        break;
                    case 'LATITUDE':
                        $stmt->bindValue($identifier, $this->latitude, PDO::PARAM_STR);
                        break;
                    case 'LONGITUDE':
                        $stmt->bindValue($identifier, $this->longitude, PDO::PARAM_STR);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getLoginName();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getGivenName();
                break;
            case 4:
                return $this->getFamilyName();
                break;
            case 5:
                return $this->getDisplayName();
                break;
            case 6:
                return $this->getEmail();
                break;
            case 7:
                return $this->getCountryIsoNr();
                break;
            case 8:
                return $this->getSubdivisionId();
                break;
            case 9:
                return $this->getAddress();
                break;
            case 10:
                return $this->getAddress2();
                break;
            case 11:
                return $this->getBirthday();
                break;
            case 12:
                return $this->getSex();
                break;
            case 13:
                return $this->getCity();
                break;
            case 14:
                return $this->getPostalCode();
                break;
            case 15:
                return $this->getPasswordRecoverCode();
                break;
            case 16:
                return $this->getPasswordRecoverTime();
                break;
            case 17:
                return $this->getLocationStatus();
                break;
            case 18:
                return $this->getLatitude();
                break;
            case 19:
                return $this->getLongitude();
                break;
            case 20:
                return $this->getCreatedAt();
                break;
            case 21:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getLoginName(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getGivenName(),
            $keys[4] => $this->getFamilyName(),
            $keys[5] => $this->getDisplayName(),
            $keys[6] => $this->getEmail(),
            $keys[7] => $this->getCountryIsoNr(),
            $keys[8] => $this->getSubdivisionId(),
            $keys[9] => $this->getAddress(),
            $keys[10] => $this->getAddress2(),
            $keys[11] => $this->getBirthday(),
            $keys[12] => $this->getSex(),
            $keys[13] => $this->getCity(),
            $keys[14] => $this->getPostalCode(),
            $keys[15] => $this->getPasswordRecoverCode(),
            $keys[16] => $this->getPasswordRecoverTime(),
            $keys[17] => $this->getLocationStatus(),
            $keys[18] => $this->getLatitude(),
            $keys[19] => $this->getLongitude(),
            $keys[20] => $this->getCreatedAt(),
            $keys[21] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCountry) {
                $result['Country'] = $this->aCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSubdivision) {
                $result['Subdivision'] = $this->aSubdivision->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collGroups) {
                $result['Groups'] = $this->collGroups->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGroupUsers) {
                $result['GroupUsers'] = $this->collGroupUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\keeko\core\model\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\keeko\core\model\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setLoginName($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setGivenName($value);
                break;
            case 4:
                $this->setFamilyName($value);
                break;
            case 5:
                $this->setDisplayName($value);
                break;
            case 6:
                $this->setEmail($value);
                break;
            case 7:
                $this->setCountryIsoNr($value);
                break;
            case 8:
                $this->setSubdivisionId($value);
                break;
            case 9:
                $this->setAddress($value);
                break;
            case 10:
                $this->setAddress2($value);
                break;
            case 11:
                $this->setBirthday($value);
                break;
            case 12:
                $this->setSex($value);
                break;
            case 13:
                $this->setCity($value);
                break;
            case 14:
                $this->setPostalCode($value);
                break;
            case 15:
                $this->setPasswordRecoverCode($value);
                break;
            case 16:
                $this->setPasswordRecoverTime($value);
                break;
            case 17:
                $this->setLocationStatus($value);
                break;
            case 18:
                $this->setLatitude($value);
                break;
            case 19:
                $this->setLongitude($value);
                break;
            case 20:
                $this->setCreatedAt($value);
                break;
            case 21:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setLoginName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPassword($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setGivenName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setFamilyName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDisplayName($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setEmail($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCountryIsoNr($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setSubdivisionId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setAddress($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setAddress2($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setBirthday($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setSex($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setCity($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setPostalCode($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setPasswordRecoverCode($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setPasswordRecoverTime($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setLocationStatus($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setLatitude($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setLongitude($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setCreatedAt($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setUpdatedAt($arr[$keys[21]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return $this|\keeko\core\model\User The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGIN_NAME)) {
            $criteria->add(UserTableMap::COL_LOGIN_NAME, $this->login_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_GIVEN_NAME)) {
            $criteria->add(UserTableMap::COL_GIVEN_NAME, $this->given_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_FAMILY_NAME)) {
            $criteria->add(UserTableMap::COL_FAMILY_NAME, $this->family_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_DISPLAY_NAME)) {
            $criteria->add(UserTableMap::COL_DISPLAY_NAME, $this->display_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_COUNTRY_ISO_NR)) {
            $criteria->add(UserTableMap::COL_COUNTRY_ISO_NR, $this->country_iso_nr);
        }
        if ($this->isColumnModified(UserTableMap::COL_SUBDIVISION_ID)) {
            $criteria->add(UserTableMap::COL_SUBDIVISION_ID, $this->subdivision_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_ADDRESS)) {
            $criteria->add(UserTableMap::COL_ADDRESS, $this->address);
        }
        if ($this->isColumnModified(UserTableMap::COL_ADDRESS2)) {
            $criteria->add(UserTableMap::COL_ADDRESS2, $this->address2);
        }
        if ($this->isColumnModified(UserTableMap::COL_BIRTHDAY)) {
            $criteria->add(UserTableMap::COL_BIRTHDAY, $this->birthday);
        }
        if ($this->isColumnModified(UserTableMap::COL_SEX)) {
            $criteria->add(UserTableMap::COL_SEX, $this->sex);
        }
        if ($this->isColumnModified(UserTableMap::COL_CITY)) {
            $criteria->add(UserTableMap::COL_CITY, $this->city);
        }
        if ($this->isColumnModified(UserTableMap::COL_POSTAL_CODE)) {
            $criteria->add(UserTableMap::COL_POSTAL_CODE, $this->postal_code);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RECOVER_CODE)) {
            $criteria->add(UserTableMap::COL_PASSWORD_RECOVER_CODE, $this->password_recover_code);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RECOVER_TIME)) {
            $criteria->add(UserTableMap::COL_PASSWORD_RECOVER_TIME, $this->password_recover_time);
        }
        if ($this->isColumnModified(UserTableMap::COL_LOCATION_STATUS)) {
            $criteria->add(UserTableMap::COL_LOCATION_STATUS, $this->location_status);
        }
        if ($this->isColumnModified(UserTableMap::COL_LATITUDE)) {
            $criteria->add(UserTableMap::COL_LATITUDE, $this->latitude);
        }
        if ($this->isColumnModified(UserTableMap::COL_LONGITUDE)) {
            $criteria->add(UserTableMap::COL_LONGITUDE, $this->longitude);
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $criteria->add(UserTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $criteria->add(UserTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);
        $criteria->add(UserTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \keeko\core\model\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setLoginName($this->getLoginName());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setGivenName($this->getGivenName());
        $copyObj->setFamilyName($this->getFamilyName());
        $copyObj->setDisplayName($this->getDisplayName());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setCountryIsoNr($this->getCountryIsoNr());
        $copyObj->setSubdivisionId($this->getSubdivisionId());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setAddress2($this->getAddress2());
        $copyObj->setBirthday($this->getBirthday());
        $copyObj->setSex($this->getSex());
        $copyObj->setCity($this->getCity());
        $copyObj->setPostalCode($this->getPostalCode());
        $copyObj->setPasswordRecoverCode($this->getPasswordRecoverCode());
        $copyObj->setPasswordRecoverTime($this->getPasswordRecoverTime());
        $copyObj->setLocationStatus($this->getLocationStatus());
        $copyObj->setLatitude($this->getLatitude());
        $copyObj->setLongitude($this->getLongitude());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGroupUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroupUser($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \keeko\core\model\User Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildCountry object.
     *
     * @param  ChildCountry $v
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCountry(ChildCountry $v = null)
    {
        if ($v === null) {
            $this->setCountryIsoNr(NULL);
        } else {
            $this->setCountryIsoNr($v->getIsoNr());
        }

        $this->aCountry = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCountry object, it will not be re-added.
        if ($v !== null) {
            $v->addUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCountry object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCountry The associated ChildCountry object.
     * @throws PropelException
     */
    public function getCountry(ConnectionInterface $con = null)
    {
        if ($this->aCountry === null && ($this->country_iso_nr !== null)) {
            $this->aCountry = ChildCountryQuery::create()->findPk($this->country_iso_nr, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCountry->addUsers($this);
             */
        }

        return $this->aCountry;
    }

    /**
     * Declares an association between this object and a ChildSubdivision object.
     *
     * @param  ChildSubdivision $v
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSubdivision(ChildSubdivision $v = null)
    {
        if ($v === null) {
            $this->setSubdivisionId(NULL);
        } else {
            $this->setSubdivisionId($v->getId());
        }

        $this->aSubdivision = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSubdivision object, it will not be re-added.
        if ($v !== null) {
            $v->addUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSubdivision object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildSubdivision The associated ChildSubdivision object.
     * @throws PropelException
     */
    public function getSubdivision(ConnectionInterface $con = null)
    {
        if ($this->aSubdivision === null && ($this->subdivision_id !== null)) {
            $this->aSubdivision = ChildSubdivisionQuery::create()->findPk($this->subdivision_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSubdivision->addUsers($this);
             */
        }

        return $this->aSubdivision;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Group' == $relationName) {
            return $this->initGroups();
        }
        if ('GroupUser' == $relationName) {
            return $this->initGroupUsers();
        }
    }

    /**
     * Clears out the collGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGroups()
     */
    public function clearGroups()
    {
        $this->collGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGroups collection loaded partially.
     */
    public function resetPartialGroups($v = true)
    {
        $this->collGroupsPartial = $v;
    }

    /**
     * Initializes the collGroups collection.
     *
     * By default this just sets the collGroups collection to an empty array (like clearcollGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroups($overrideExisting = true)
    {
        if (null !== $this->collGroups && !$overrideExisting) {
            return;
        }
        $this->collGroups = new ObjectCollection();
        $this->collGroups->setModel('\keeko\core\model\Group');
    }

    /**
     * Gets an array of ChildGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGroup[] List of ChildGroup objects
     * @throws PropelException
     */
    public function getGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGroups) {
                // return empty collection
                $this->initGroups();
            } else {
                $collGroups = ChildGroupQuery::create(null, $criteria)
                    ->filterByOwner($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGroupsPartial && count($collGroups)) {
                        $this->initGroups(false);

                        foreach ($collGroups as $obj) {
                            if (false == $this->collGroups->contains($obj)) {
                                $this->collGroups->append($obj);
                            }
                        }

                        $this->collGroupsPartial = true;
                    }

                    return $collGroups;
                }

                if ($partial && $this->collGroups) {
                    foreach ($this->collGroups as $obj) {
                        if ($obj->isNew()) {
                            $collGroups[] = $obj;
                        }
                    }
                }

                $this->collGroups = $collGroups;
                $this->collGroupsPartial = false;
            }
        }

        return $this->collGroups;
    }

    /**
     * Sets a collection of ChildGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $groups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setGroups(Collection $groups, ConnectionInterface $con = null)
    {
        /** @var ChildGroup[] $groupsToDelete */
        $groupsToDelete = $this->getGroups(new Criteria(), $con)->diff($groups);


        $this->groupsScheduledForDeletion = $groupsToDelete;

        foreach ($groupsToDelete as $groupRemoved) {
            $groupRemoved->setOwner(null);
        }

        $this->collGroups = null;
        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        $this->collGroups = $groups;
        $this->collGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Group objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Group objects.
     * @throws PropelException
     */
    public function countGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGroups());
            }

            $query = ChildGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOwner($this)
                ->count($con);
        }

        return count($this->collGroups);
    }

    /**
     * Method called to associate a ChildGroup object to this object
     * through the ChildGroup foreign key attribute.
     *
     * @param  ChildGroup $l ChildGroup
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function addGroup(ChildGroup $l)
    {
        if ($this->collGroups === null) {
            $this->initGroups();
            $this->collGroupsPartial = true;
        }

        if (!$this->collGroups->contains($l)) {
            $this->doAddGroup($l);
        }

        return $this;
    }

    /**
     * @param ChildGroup $group The ChildGroup object to add.
     */
    protected function doAddGroup(ChildGroup $group)
    {
        $this->collGroups[]= $group;
        $group->setOwner($this);
    }

    /**
     * @param  ChildGroup $group The ChildGroup object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeGroup(ChildGroup $group)
    {
        if ($this->getGroups()->contains($group)) {
            $pos = $this->collGroups->search($group);
            $this->collGroups->remove($pos);
            if (null === $this->groupsScheduledForDeletion) {
                $this->groupsScheduledForDeletion = clone $this->collGroups;
                $this->groupsScheduledForDeletion->clear();
            }
            $this->groupsScheduledForDeletion[]= $group;
            $group->setOwner(null);
        }

        return $this;
    }

    /**
     * Clears out the collGroupUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGroupUsers()
     */
    public function clearGroupUsers()
    {
        $this->collGroupUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGroupUsers collection loaded partially.
     */
    public function resetPartialGroupUsers($v = true)
    {
        $this->collGroupUsersPartial = $v;
    }

    /**
     * Initializes the collGroupUsers collection.
     *
     * By default this just sets the collGroupUsers collection to an empty array (like clearcollGroupUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroupUsers($overrideExisting = true)
    {
        if (null !== $this->collGroupUsers && !$overrideExisting) {
            return;
        }
        $this->collGroupUsers = new ObjectCollection();
        $this->collGroupUsers->setModel('\keeko\core\model\GroupUser');
    }

    /**
     * Gets an array of ChildGroupUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGroupUser[] List of ChildGroupUser objects
     * @throws PropelException
     */
    public function getGroupUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupUsersPartial && !$this->isNew();
        if (null === $this->collGroupUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGroupUsers) {
                // return empty collection
                $this->initGroupUsers();
            } else {
                $collGroupUsers = ChildGroupUserQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGroupUsersPartial && count($collGroupUsers)) {
                        $this->initGroupUsers(false);

                        foreach ($collGroupUsers as $obj) {
                            if (false == $this->collGroupUsers->contains($obj)) {
                                $this->collGroupUsers->append($obj);
                            }
                        }

                        $this->collGroupUsersPartial = true;
                    }

                    return $collGroupUsers;
                }

                if ($partial && $this->collGroupUsers) {
                    foreach ($this->collGroupUsers as $obj) {
                        if ($obj->isNew()) {
                            $collGroupUsers[] = $obj;
                        }
                    }
                }

                $this->collGroupUsers = $collGroupUsers;
                $this->collGroupUsersPartial = false;
            }
        }

        return $this->collGroupUsers;
    }

    /**
     * Sets a collection of ChildGroupUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $groupUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setGroupUsers(Collection $groupUsers, ConnectionInterface $con = null)
    {
        /** @var ChildGroupUser[] $groupUsersToDelete */
        $groupUsersToDelete = $this->getGroupUsers(new Criteria(), $con)->diff($groupUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->groupUsersScheduledForDeletion = clone $groupUsersToDelete;

        foreach ($groupUsersToDelete as $groupUserRemoved) {
            $groupUserRemoved->setUser(null);
        }

        $this->collGroupUsers = null;
        foreach ($groupUsers as $groupUser) {
            $this->addGroupUser($groupUser);
        }

        $this->collGroupUsers = $groupUsers;
        $this->collGroupUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GroupUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GroupUser objects.
     * @throws PropelException
     */
    public function countGroupUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupUsersPartial && !$this->isNew();
        if (null === $this->collGroupUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroupUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGroupUsers());
            }

            $query = ChildGroupUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collGroupUsers);
    }

    /**
     * Method called to associate a ChildGroupUser object to this object
     * through the ChildGroupUser foreign key attribute.
     *
     * @param  ChildGroupUser $l ChildGroupUser
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function addGroupUser(ChildGroupUser $l)
    {
        if ($this->collGroupUsers === null) {
            $this->initGroupUsers();
            $this->collGroupUsersPartial = true;
        }

        if (!$this->collGroupUsers->contains($l)) {
            $this->doAddGroupUser($l);
        }

        return $this;
    }

    /**
     * @param ChildGroupUser $groupUser The ChildGroupUser object to add.
     */
    protected function doAddGroupUser(ChildGroupUser $groupUser)
    {
        $this->collGroupUsers[]= $groupUser;
        $groupUser->setUser($this);
    }

    /**
     * @param  ChildGroupUser $groupUser The ChildGroupUser object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeGroupUser(ChildGroupUser $groupUser)
    {
        if ($this->getGroupUsers()->contains($groupUser)) {
            $pos = $this->collGroupUsers->search($groupUser);
            $this->collGroupUsers->remove($pos);
            if (null === $this->groupUsersScheduledForDeletion) {
                $this->groupUsersScheduledForDeletion = clone $this->collGroupUsers;
                $this->groupUsersScheduledForDeletion->clear();
            }
            $this->groupUsersScheduledForDeletion[]= clone $groupUser;
            $groupUser->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related GroupUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGroupUser[] List of ChildGroupUser objects
     */
    public function getGroupUsersJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGroupUserQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getGroupUsers($query, $con);
    }

    /**
     * Clears out the collGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGroups()
     */
    public function clearGroups()
    {
        $this->collGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collGroups collection.
     *
     * By default this just sets the collGroups collection to an empty collection (like clearGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initGroups()
    {
        $this->collGroups = new ObjectCollection();
        $this->collGroupsPartial = true;

        $this->collGroups->setModel('\keeko\core\model\Group');
    }

    /**
     * Checks if the collGroups collection is loaded.
     *
     * @return bool
     */
    public function isGroupsLoaded()
    {
        return null !== $this->collGroups;
    }

    /**
     * Gets a collection of ChildGroup objects related by a many-to-many relationship
     * to the current object by way of the kk_group_user cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildGroup[] List of ChildGroup objects
     */
    public function getGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collGroups) {
                    $this->initGroups();
                }
            } else {

                $query = ChildGroupQuery::create(null, $criteria)
                    ->filterByUser($this);
                $collGroups = $query->find($con);
                if (null !== $criteria) {
                    return $collGroups;
                }

                if ($partial && $this->collGroups) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collGroups as $obj) {
                        if (!$collGroups->contains($obj)) {
                            $collGroups[] = $obj;
                        }
                    }
                }

                $this->collGroups = $collGroups;
                $this->collGroupsPartial = false;
            }
        }

        return $this->collGroups;
    }

    /**
     * Sets a collection of Group objects related by a many-to-many relationship
     * to the current object by way of the kk_group_user cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $groups A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setGroups(Collection $groups, ConnectionInterface $con = null)
    {
        $this->clearGroups();
        $currentGroups = $this->getGroups();

        $groupsScheduledForDeletion = $currentGroups->diff($groups);

        foreach ($groupsScheduledForDeletion as $toDelete) {
            $this->removeGroup($toDelete);
        }

        foreach ($groups as $group) {
            if (!$currentGroups->contains($group)) {
                $this->doAddGroup($group);
            }
        }

        $this->collGroupsPartial = false;
        $this->collGroups = $groups;

        return $this;
    }

    /**
     * Gets the number of Group objects related by a many-to-many relationship
     * to the current object by way of the kk_group_user cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Group objects
     */
    public function countGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroups) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getGroups());
                }

                $query = ChildGroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collGroups);
        }
    }

    /**
     * Associate a ChildGroup to this object
     * through the kk_group_user cross reference table.
     *
     * @param ChildGroup $group
     * @return ChildUser The current object (for fluent API support)
     */
    public function addGroup(ChildGroup $group)
    {
        if ($this->collGroups === null) {
            $this->initGroups();
        }

        if (!$this->getGroups()->contains($group)) {
            // only add it if the **same** object is not already associated
            $this->collGroups->push($group);
            $this->doAddGroup($group);
        }

        return $this;
    }

    /**
     *
     * @param ChildGroup $group
     */
    protected function doAddGroup(ChildGroup $group)
    {
        $groupUser = new ChildGroupUser();

        $groupUser->setGroup($group);

        $groupUser->setUser($this);

        $this->addGroupUser($groupUser);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$group->isUsersLoaded()) {
            $group->initUsers();
            $group->getUsers()->push($this);
        } elseif (!$group->getUsers()->contains($this)) {
            $group->getUsers()->push($this);
        }

    }

    /**
     * Remove group of this object
     * through the kk_group_user cross reference table.
     *
     * @param ChildGroup $group
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeGroup(ChildGroup $group)
    {
        if ($this->getGroups()->contains($group)) { $groupUser = new ChildGroupUser();

            $groupUser->setGroup($group);
            if ($group->isUsersLoaded()) {
                //remove the back reference if available
                $group->getUsers()->removeObject($this);
            }

            $groupUser->setUser($this);
            $this->removeGroupUser(clone $groupUser);
            $groupUser->clear();

            $this->collGroups->remove($this->collGroups->search($group));

            if (null === $this->groupsScheduledForDeletion) {
                $this->groupsScheduledForDeletion = clone $this->collGroups;
                $this->groupsScheduledForDeletion->clear();
            }

            $this->groupsScheduledForDeletion->push($group);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aCountry) {
            $this->aCountry->removeUser($this);
        }
        if (null !== $this->aSubdivision) {
            $this->aSubdivision->removeUser($this);
        }
        $this->id = null;
        $this->login_name = null;
        $this->password = null;
        $this->given_name = null;
        $this->family_name = null;
        $this->display_name = null;
        $this->email = null;
        $this->country_iso_nr = null;
        $this->subdivision_id = null;
        $this->address = null;
        $this->address2 = null;
        $this->birthday = null;
        $this->sex = null;
        $this->city = null;
        $this->postal_code = null;
        $this->password_recover_code = null;
        $this->password_recover_time = null;
        $this->location_status = null;
        $this->latitude = null;
        $this->longitude = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collGroups) {
                foreach ($this->collGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroupUsers) {
                foreach ($this->collGroupUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroups) {
                foreach ($this->collGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collGroups = null;
        $this->collGroupUsers = null;
        $this->collGroups = null;
        $this->aCountry = null;
        $this->aSubdivision = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildUser The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    // validate behavior

    /**
     * Configure validators constraints. The Validator object uses this method
     * to perform object validation.
     *
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('login_name', new NotNull());
        $metadata->addPropertyConstraint('email', new NotNull());
        $metadata->addPropertyConstraint('email', new Email());
        $metadata->addPropertyConstraint('password', new NotNull());
    }

    /**
     * Validates the object and all objects related to this table.
     *
     * @see        getValidationFailures()
     * @param      object $validator A Validator class instance
     * @return     boolean Whether all objects pass validation.
     */
    public function validate(Validator $validator = null)
    {
        if (null === $validator) {
            $validator = new Validator(new ClassMetadataFactory(new StaticMethodLoader()), new ConstraintValidatorFactory(), new DefaultTranslator());
        }

        $failureMap = new ConstraintViolationList();

        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            // If validate() method exists, the validate-behavior is configured for related object
            if (method_exists($this->aCountry, 'validate')) {
                if (!$this->aCountry->validate($validator)) {
                    $failureMap->addAll($this->aCountry->getValidationFailures());
                }
            }
            // If validate() method exists, the validate-behavior is configured for related object
            if (method_exists($this->aSubdivision, 'validate')) {
                if (!$this->aSubdivision->validate($validator)) {
                    $failureMap->addAll($this->aSubdivision->getValidationFailures());
                }
            }

            $retval = $validator->validate($this);
            if (count($retval) > 0) {
                $failureMap->addAll($retval);
            }

            if (null !== $this->collGroups) {
                foreach ($this->collGroups as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collGroupUsers) {
                foreach ($this->collGroupUsers as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }

            $this->alreadyInValidation = false;
        }

        $this->validationFailures = $failureMap;

        return (Boolean) (!(count($this->validationFailures) > 0));

    }

    /**
     * Gets any ConstraintViolation objects that resulted from last call to validate().
     *
     *
     * @return     object ConstraintViolationList
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
