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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\LegacyValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use keeko\core\model\Activity as ChildActivity;
use keeko\core\model\ActivityQuery as ChildActivityQuery;
use keeko\core\model\Auth as ChildAuth;
use keeko\core\model\AuthQuery as ChildAuthQuery;
use keeko\core\model\Group as ChildGroup;
use keeko\core\model\GroupQuery as ChildGroupQuery;
use keeko\core\model\User as ChildUser;
use keeko\core\model\UserGroup as ChildUserGroup;
use keeko\core\model\UserGroupQuery as ChildUserGroupQuery;
use keeko\core\model\UserQuery as ChildUserQuery;
use keeko\core\model\Map\UserTableMap;

/**
 * Base class that represents a row from the 'kk_user' table.
 *
 *
 *
* @package    propel.generator..Base
*/
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
     * @var        ObjectCollection|ChildAuth[] Collection to store aggregation of ChildAuth objects.
     */
    protected $collAuths;
    protected $collAuthsPartial;

    /**
     * @var        ObjectCollection|ChildUserGroup[] Collection to store aggregation of ChildUserGroup objects.
     */
    protected $collUserGroups;
    protected $collUserGroupsPartial;

    /**
     * @var        ObjectCollection|ChildActivity[] Collection to store aggregation of ChildActivity objects.
     */
    protected $collActivities;
    protected $collActivitiesPartial;

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
     * @var ObjectCollection|ChildAuth[]
     */
    protected $authsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserGroup[]
     */
    protected $userGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActivity[]
     */
    protected $activitiesScheduledForDeletion = null;

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
     * Get the [optionally formatted] temporal [birthday] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
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
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
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
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
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
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
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
     * Sets the value of [birthday] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setBirthday($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
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
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->password_recover_time !== null || $dt !== null) {
            if ($dt !== $this->password_recover_time) {
                $this->password_recover_time = $dt;
                $this->modifiedColumns[UserTableMap::COL_PASSWORD_RECOVER_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setPasswordRecoverTime()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
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
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

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
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Birthday', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->birthday = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('Sex', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sex = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('PasswordRecoverCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password_recover_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('PasswordRecoverTime', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->password_recover_time = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = UserTableMap::NUM_HYDRATE_COLUMNS.

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
    } // ensureConsistency

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

            $this->collAuths = null;

            $this->collUserGroups = null;

            $this->collActivities = null;

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

                    \keeko\core\model\UserGroupQuery::create()
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


            if ($this->authsScheduledForDeletion !== null) {
                if (!$this->authsScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\AuthQuery::create()
                        ->filterByPrimaryKeys($this->authsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->authsScheduledForDeletion = null;
                }
            }

            if ($this->collAuths !== null) {
                foreach ($this->collAuths as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userGroupsScheduledForDeletion !== null) {
                if (!$this->userGroupsScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\UserGroupQuery::create()
                        ->filterByPrimaryKeys($this->userGroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userGroupsScheduledForDeletion = null;
                }
            }

            if ($this->collUserGroups !== null) {
                foreach ($this->collUserGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->activitiesScheduledForDeletion !== null) {
                if (!$this->activitiesScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\ActivityQuery::create()
                        ->filterByPrimaryKeys($this->activitiesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->activitiesScheduledForDeletion = null;
                }
            }

            if ($this->collActivities !== null) {
                foreach ($this->collActivities as $referrerFK) {
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
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGIN_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`login_name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(UserTableMap::COL_GIVEN_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`given_name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_FAMILY_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`family_name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_DISPLAY_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`display_name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(UserTableMap::COL_BIRTHDAY)) {
            $modifiedColumns[':p' . $index++]  = '`birthday`';
        }
        if ($this->isColumnModified(UserTableMap::COL_SEX)) {
            $modifiedColumns[':p' . $index++]  = '`sex`';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RECOVER_CODE)) {
            $modifiedColumns[':p' . $index++]  = '`password_recover_code`';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RECOVER_TIME)) {
            $modifiedColumns[':p' . $index++]  = '`password_recover_time`';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `kk_user` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`login_name`':
                        $stmt->bindValue($identifier, $this->login_name, PDO::PARAM_STR);
                        break;
                    case '`password`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`given_name`':
                        $stmt->bindValue($identifier, $this->given_name, PDO::PARAM_STR);
                        break;
                    case '`family_name`':
                        $stmt->bindValue($identifier, $this->family_name, PDO::PARAM_STR);
                        break;
                    case '`display_name`':
                        $stmt->bindValue($identifier, $this->display_name, PDO::PARAM_STR);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`birthday`':
                        $stmt->bindValue($identifier, $this->birthday ? $this->birthday->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case '`sex`':
                        $stmt->bindValue($identifier, $this->sex, PDO::PARAM_INT);
                        break;
                    case '`password_recover_code`':
                        $stmt->bindValue($identifier, $this->password_recover_code, PDO::PARAM_STR);
                        break;
                    case '`password_recover_time`':
                        $stmt->bindValue($identifier, $this->password_recover_time ? $this->password_recover_time->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
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
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
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
                return $this->getBirthday();
                break;
            case 8:
                return $this->getSex();
                break;
            case 9:
                return $this->getPasswordRecoverCode();
                break;
            case 10:
                return $this->getPasswordRecoverTime();
                break;
            case 11:
                return $this->getCreatedAt();
                break;
            case 12:
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
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getLoginName(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getGivenName(),
            $keys[4] => $this->getFamilyName(),
            $keys[5] => $this->getDisplayName(),
            $keys[6] => $this->getEmail(),
            $keys[7] => $this->getBirthday(),
            $keys[8] => $this->getSex(),
            $keys[9] => $this->getPasswordRecoverCode(),
            $keys[10] => $this->getPasswordRecoverTime(),
            $keys[11] => $this->getCreatedAt(),
            $keys[12] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collAuths) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'auths';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_auths';
                        break;
                    default:
                        $key = 'Auths';
                }

                $result[$key] = $this->collAuths->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserGroups) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userGroups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_user_groups';
                        break;
                    default:
                        $key = 'UserGroups';
                }

                $result[$key] = $this->collUserGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collActivities) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'activities';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_activities';
                        break;
                    default:
                        $key = 'Activities';
                }

                $result[$key] = $this->collActivities->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
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
                $this->setBirthday($value);
                break;
            case 8:
                $this->setSex($value);
                break;
            case 9:
                $this->setPasswordRecoverCode($value);
                break;
            case 10:
                $this->setPasswordRecoverTime($value);
                break;
            case 11:
                $this->setCreatedAt($value);
                break;
            case 12:
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
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
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
            $this->setBirthday($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setSex($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setPasswordRecoverCode($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setPasswordRecoverTime($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setCreatedAt($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setUpdatedAt($arr[$keys[12]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\keeko\core\model\User The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

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
        if ($this->isColumnModified(UserTableMap::COL_BIRTHDAY)) {
            $criteria->add(UserTableMap::COL_BIRTHDAY, $this->birthday);
        }
        if ($this->isColumnModified(UserTableMap::COL_SEX)) {
            $criteria->add(UserTableMap::COL_SEX, $this->sex);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RECOVER_CODE)) {
            $criteria->add(UserTableMap::COL_PASSWORD_RECOVER_CODE, $this->password_recover_code);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RECOVER_TIME)) {
            $criteria->add(UserTableMap::COL_PASSWORD_RECOVER_TIME, $this->password_recover_time);
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
        $criteria = ChildUserQuery::create();
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
        $copyObj->setBirthday($this->getBirthday());
        $copyObj->setSex($this->getSex());
        $copyObj->setPasswordRecoverCode($this->getPasswordRecoverCode());
        $copyObj->setPasswordRecoverTime($this->getPasswordRecoverTime());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAuths() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAuth($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getActivities() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addActivity($relObj->copy($deepCopy));
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Auth' == $relationName) {
            return $this->initAuths();
        }
        if ('UserGroup' == $relationName) {
            return $this->initUserGroups();
        }
        if ('Activity' == $relationName) {
            return $this->initActivities();
        }
    }

    /**
     * Clears out the collAuths collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAuths()
     */
    public function clearAuths()
    {
        $this->collAuths = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAuths collection loaded partially.
     */
    public function resetPartialAuths($v = true)
    {
        $this->collAuthsPartial = $v;
    }

    /**
     * Initializes the collAuths collection.
     *
     * By default this just sets the collAuths collection to an empty array (like clearcollAuths());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAuths($overrideExisting = true)
    {
        if (null !== $this->collAuths && !$overrideExisting) {
            return;
        }
        $this->collAuths = new ObjectCollection();
        $this->collAuths->setModel('\keeko\core\model\Auth');
    }

    /**
     * Gets an array of ChildAuth objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAuth[] List of ChildAuth objects
     * @throws PropelException
     */
    public function getAuths(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAuthsPartial && !$this->isNew();
        if (null === $this->collAuths || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAuths) {
                // return empty collection
                $this->initAuths();
            } else {
                $collAuths = ChildAuthQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAuthsPartial && count($collAuths)) {
                        $this->initAuths(false);

                        foreach ($collAuths as $obj) {
                            if (false == $this->collAuths->contains($obj)) {
                                $this->collAuths->append($obj);
                            }
                        }

                        $this->collAuthsPartial = true;
                    }

                    return $collAuths;
                }

                if ($partial && $this->collAuths) {
                    foreach ($this->collAuths as $obj) {
                        if ($obj->isNew()) {
                            $collAuths[] = $obj;
                        }
                    }
                }

                $this->collAuths = $collAuths;
                $this->collAuthsPartial = false;
            }
        }

        return $this->collAuths;
    }

    /**
     * Sets a collection of ChildAuth objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $auths A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAuths(Collection $auths, ConnectionInterface $con = null)
    {
        /** @var ChildAuth[] $authsToDelete */
        $authsToDelete = $this->getAuths(new Criteria(), $con)->diff($auths);


        $this->authsScheduledForDeletion = $authsToDelete;

        foreach ($authsToDelete as $authRemoved) {
            $authRemoved->setUser(null);
        }

        $this->collAuths = null;
        foreach ($auths as $auth) {
            $this->addAuth($auth);
        }

        $this->collAuths = $auths;
        $this->collAuthsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Auth objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Auth objects.
     * @throws PropelException
     */
    public function countAuths(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAuthsPartial && !$this->isNew();
        if (null === $this->collAuths || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAuths) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAuths());
            }

            $query = ChildAuthQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAuths);
    }

    /**
     * Method called to associate a ChildAuth object to this object
     * through the ChildAuth foreign key attribute.
     *
     * @param  ChildAuth $l ChildAuth
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function addAuth(ChildAuth $l)
    {
        if ($this->collAuths === null) {
            $this->initAuths();
            $this->collAuthsPartial = true;
        }

        if (!$this->collAuths->contains($l)) {
            $this->doAddAuth($l);
        }

        return $this;
    }

    /**
     * @param ChildAuth $auth The ChildAuth object to add.
     */
    protected function doAddAuth(ChildAuth $auth)
    {
        $this->collAuths[]= $auth;
        $auth->setUser($this);
    }

    /**
     * @param  ChildAuth $auth The ChildAuth object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeAuth(ChildAuth $auth)
    {
        if ($this->getAuths()->contains($auth)) {
            $pos = $this->collAuths->search($auth);
            $this->collAuths->remove($pos);
            if (null === $this->authsScheduledForDeletion) {
                $this->authsScheduledForDeletion = clone $this->collAuths;
                $this->authsScheduledForDeletion->clear();
            }
            $this->authsScheduledForDeletion[]= clone $auth;
            $auth->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserGroups()
     */
    public function clearUserGroups()
    {
        $this->collUserGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserGroups collection loaded partially.
     */
    public function resetPartialUserGroups($v = true)
    {
        $this->collUserGroupsPartial = $v;
    }

    /**
     * Initializes the collUserGroups collection.
     *
     * By default this just sets the collUserGroups collection to an empty array (like clearcollUserGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserGroups($overrideExisting = true)
    {
        if (null !== $this->collUserGroups && !$overrideExisting) {
            return;
        }
        $this->collUserGroups = new ObjectCollection();
        $this->collUserGroups->setModel('\keeko\core\model\UserGroup');
    }

    /**
     * Gets an array of ChildUserGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserGroup[] List of ChildUserGroup objects
     * @throws PropelException
     */
    public function getUserGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserGroupsPartial && !$this->isNew();
        if (null === $this->collUserGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserGroups) {
                // return empty collection
                $this->initUserGroups();
            } else {
                $collUserGroups = ChildUserGroupQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserGroupsPartial && count($collUserGroups)) {
                        $this->initUserGroups(false);

                        foreach ($collUserGroups as $obj) {
                            if (false == $this->collUserGroups->contains($obj)) {
                                $this->collUserGroups->append($obj);
                            }
                        }

                        $this->collUserGroupsPartial = true;
                    }

                    return $collUserGroups;
                }

                if ($partial && $this->collUserGroups) {
                    foreach ($this->collUserGroups as $obj) {
                        if ($obj->isNew()) {
                            $collUserGroups[] = $obj;
                        }
                    }
                }

                $this->collUserGroups = $collUserGroups;
                $this->collUserGroupsPartial = false;
            }
        }

        return $this->collUserGroups;
    }

    /**
     * Sets a collection of ChildUserGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userGroups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setUserGroups(Collection $userGroups, ConnectionInterface $con = null)
    {
        /** @var ChildUserGroup[] $userGroupsToDelete */
        $userGroupsToDelete = $this->getUserGroups(new Criteria(), $con)->diff($userGroups);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userGroupsScheduledForDeletion = clone $userGroupsToDelete;

        foreach ($userGroupsToDelete as $userGroupRemoved) {
            $userGroupRemoved->setUser(null);
        }

        $this->collUserGroups = null;
        foreach ($userGroups as $userGroup) {
            $this->addUserGroup($userGroup);
        }

        $this->collUserGroups = $userGroups;
        $this->collUserGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserGroup objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserGroup objects.
     * @throws PropelException
     */
    public function countUserGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserGroupsPartial && !$this->isNew();
        if (null === $this->collUserGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserGroups());
            }

            $query = ChildUserGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserGroups);
    }

    /**
     * Method called to associate a ChildUserGroup object to this object
     * through the ChildUserGroup foreign key attribute.
     *
     * @param  ChildUserGroup $l ChildUserGroup
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function addUserGroup(ChildUserGroup $l)
    {
        if ($this->collUserGroups === null) {
            $this->initUserGroups();
            $this->collUserGroupsPartial = true;
        }

        if (!$this->collUserGroups->contains($l)) {
            $this->doAddUserGroup($l);
        }

        return $this;
    }

    /**
     * @param ChildUserGroup $userGroup The ChildUserGroup object to add.
     */
    protected function doAddUserGroup(ChildUserGroup $userGroup)
    {
        $this->collUserGroups[]= $userGroup;
        $userGroup->setUser($this);
    }

    /**
     * @param  ChildUserGroup $userGroup The ChildUserGroup object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeUserGroup(ChildUserGroup $userGroup)
    {
        if ($this->getUserGroups()->contains($userGroup)) {
            $pos = $this->collUserGroups->search($userGroup);
            $this->collUserGroups->remove($pos);
            if (null === $this->userGroupsScheduledForDeletion) {
                $this->userGroupsScheduledForDeletion = clone $this->collUserGroups;
                $this->userGroupsScheduledForDeletion->clear();
            }
            $this->userGroupsScheduledForDeletion[]= clone $userGroup;
            $userGroup->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserGroup[] List of ChildUserGroup objects
     */
    public function getUserGroupsJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserGroupQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getUserGroups($query, $con);
    }

    /**
     * Clears out the collActivities collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActivities()
     */
    public function clearActivities()
    {
        $this->collActivities = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActivities collection loaded partially.
     */
    public function resetPartialActivities($v = true)
    {
        $this->collActivitiesPartial = $v;
    }

    /**
     * Initializes the collActivities collection.
     *
     * By default this just sets the collActivities collection to an empty array (like clearcollActivities());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActivities($overrideExisting = true)
    {
        if (null !== $this->collActivities && !$overrideExisting) {
            return;
        }
        $this->collActivities = new ObjectCollection();
        $this->collActivities->setModel('\keeko\core\model\Activity');
    }

    /**
     * Gets an array of ChildActivity objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildActivity[] List of ChildActivity objects
     * @throws PropelException
     */
    public function getActivities(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActivitiesPartial && !$this->isNew();
        if (null === $this->collActivities || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActivities) {
                // return empty collection
                $this->initActivities();
            } else {
                $collActivities = ChildActivityQuery::create(null, $criteria)
                    ->filterByActor($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActivitiesPartial && count($collActivities)) {
                        $this->initActivities(false);

                        foreach ($collActivities as $obj) {
                            if (false == $this->collActivities->contains($obj)) {
                                $this->collActivities->append($obj);
                            }
                        }

                        $this->collActivitiesPartial = true;
                    }

                    return $collActivities;
                }

                if ($partial && $this->collActivities) {
                    foreach ($this->collActivities as $obj) {
                        if ($obj->isNew()) {
                            $collActivities[] = $obj;
                        }
                    }
                }

                $this->collActivities = $collActivities;
                $this->collActivitiesPartial = false;
            }
        }

        return $this->collActivities;
    }

    /**
     * Sets a collection of ChildActivity objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $activities A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setActivities(Collection $activities, ConnectionInterface $con = null)
    {
        /** @var ChildActivity[] $activitiesToDelete */
        $activitiesToDelete = $this->getActivities(new Criteria(), $con)->diff($activities);


        $this->activitiesScheduledForDeletion = $activitiesToDelete;

        foreach ($activitiesToDelete as $activityRemoved) {
            $activityRemoved->setActor(null);
        }

        $this->collActivities = null;
        foreach ($activities as $activity) {
            $this->addActivity($activity);
        }

        $this->collActivities = $activities;
        $this->collActivitiesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Activity objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Activity objects.
     * @throws PropelException
     */
    public function countActivities(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActivitiesPartial && !$this->isNew();
        if (null === $this->collActivities || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActivities) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActivities());
            }

            $query = ChildActivityQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByActor($this)
                ->count($con);
        }

        return count($this->collActivities);
    }

    /**
     * Method called to associate a ChildActivity object to this object
     * through the ChildActivity foreign key attribute.
     *
     * @param  ChildActivity $l ChildActivity
     * @return $this|\keeko\core\model\User The current object (for fluent API support)
     */
    public function addActivity(ChildActivity $l)
    {
        if ($this->collActivities === null) {
            $this->initActivities();
            $this->collActivitiesPartial = true;
        }

        if (!$this->collActivities->contains($l)) {
            $this->doAddActivity($l);
        }

        return $this;
    }

    /**
     * @param ChildActivity $activity The ChildActivity object to add.
     */
    protected function doAddActivity(ChildActivity $activity)
    {
        $this->collActivities[]= $activity;
        $activity->setActor($this);
    }

    /**
     * @param  ChildActivity $activity The ChildActivity object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeActivity(ChildActivity $activity)
    {
        if ($this->getActivities()->contains($activity)) {
            $pos = $this->collActivities->search($activity);
            $this->collActivities->remove($pos);
            if (null === $this->activitiesScheduledForDeletion) {
                $this->activitiesScheduledForDeletion = clone $this->collActivities;
                $this->activitiesScheduledForDeletion->clear();
            }
            $this->activitiesScheduledForDeletion[]= clone $activity;
            $activity->setActor(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Activities from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildActivity[] List of ChildActivity objects
     */
    public function getActivitiesJoinObject(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildActivityQuery::create(null, $criteria);
        $query->joinWith('Object', $joinBehavior);

        return $this->getActivities($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Activities from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildActivity[] List of ChildActivity objects
     */
    public function getActivitiesJoinTarget(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildActivityQuery::create(null, $criteria);
        $query->joinWith('Target', $joinBehavior);

        return $this->getActivities($query, $con);
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
     * Initializes the collGroups crossRef collection.
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
     * to the current object by way of the kk_user_group cross-reference table.
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
     * to the current object by way of the kk_user_group cross-reference table.
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
     * to the current object by way of the kk_user_group cross-reference table.
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
     * through the kk_user_group cross reference table.
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
        $userGroup = new ChildUserGroup();

        $userGroup->setGroup($group);

        $userGroup->setUser($this);

        $this->addUserGroup($userGroup);

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
     * through the kk_user_group cross reference table.
     *
     * @param ChildGroup $group
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeGroup(ChildGroup $group)
    {
        if ($this->getGroups()->contains($group)) { $userGroup = new ChildUserGroup();

            $userGroup->setGroup($group);
            if ($group->isUsersLoaded()) {
                //remove the back reference if available
                $group->getUsers()->removeObject($this);
            }

            $userGroup->setUser($this);
            $this->removeUserGroup(clone $userGroup);
            $userGroup->clear();

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
        $this->id = null;
        $this->login_name = null;
        $this->password = null;
        $this->given_name = null;
        $this->family_name = null;
        $this->display_name = null;
        $this->email = null;
        $this->birthday = null;
        $this->sex = null;
        $this->password_recover_code = null;
        $this->password_recover_time = null;
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
            if ($this->collAuths) {
                foreach ($this->collAuths as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserGroups) {
                foreach ($this->collUserGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collActivities) {
                foreach ($this->collActivities as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroups) {
                foreach ($this->collGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAuths = null;
        $this->collUserGroups = null;
        $this->collActivities = null;
        $this->collGroups = null;
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
    public function validate(ValidatorInterface $validator = null)
    {
        if (null === $validator) {
            if(class_exists('Symfony\\Component\\Validator\\Validator\\LegacyValidator')){
                $validator = new LegacyValidator(
                            new ExecutionContextFactory(new DefaultTranslator()),
                            new ClassMetaDataFactory(new StaticMethodLoader()),
                            new ConstraintValidatorFactory()
                );
            }else{
                $validator = new Validator(
                            new ClassMetadataFactory(new StaticMethodLoader()),
                            new ConstraintValidatorFactory(),
                            new DefaultTranslator()
                );
            }
        }

        $failureMap = new ConstraintViolationList();

        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;


            $retval = $validator->validate($this);
            if (count($retval) > 0) {
                $failureMap->addAll($retval);
            }

            if (null !== $this->collAuths) {
                foreach ($this->collAuths as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collUserGroups) {
                foreach ($this->collUserGroups as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collActivities) {
                foreach ($this->collActivities as $referrerFK) {
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
