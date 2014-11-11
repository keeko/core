<?php

namespace keeko\core\model\Base;

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
use keeko\core\model\Country as ChildCountry;
use keeko\core\model\CountryQuery as ChildCountryQuery;
use keeko\core\model\Subdivision as ChildSubdivision;
use keeko\core\model\SubdivisionQuery as ChildSubdivisionQuery;
use keeko\core\model\SubdivisionType as ChildSubdivisionType;
use keeko\core\model\SubdivisionTypeQuery as ChildSubdivisionTypeQuery;
use keeko\core\model\User as ChildUser;
use keeko\core\model\UserQuery as ChildUserQuery;
use keeko\core\model\Map\SubdivisionTableMap;

/**
 * Base class that represents a row from the 'kk_subdivision' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Subdivision implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\SubdivisionTableMap';


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
     * The value for the iso field.
     * @var        string
     */
    protected $iso;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the local_name field.
     * @var        string
     */
    protected $local_name;

    /**
     * The value for the en_name field.
     * @var        string
     */
    protected $en_name;

    /**
     * The value for the alt_names field.
     * @var        string
     */
    protected $alt_names;

    /**
     * The value for the parent_id field.
     * @var        int
     */
    protected $parent_id;

    /**
     * The value for the country_iso_nr field.
     * @var        int
     */
    protected $country_iso_nr;

    /**
     * The value for the subdivision_type_id field.
     * @var        int
     */
    protected $subdivision_type_id;

    /**
     * @var        ChildCountry
     */
    protected $aCountry;

    /**
     * @var        ChildSubdivisionType
     */
    protected $aSubdivisionType;

    /**
     * @var        ObjectCollection|ChildUser[] Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;
    protected $collUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\Subdivision object.
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
     * Compares this with another <code>Subdivision</code> instance.  If
     * <code>obj</code> is an instance of <code>Subdivision</code>, delegates to
     * <code>equals(Subdivision)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Subdivision The current object, for fluid interface
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
     * Get the [iso] column value.
     *
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [local_name] column value.
     *
     * @return string
     */
    public function getLocalName()
    {
        return $this->local_name;
    }

    /**
     * Get the [en_name] column value.
     *
     * @return string
     */
    public function getEnName()
    {
        return $this->en_name;
    }

    /**
     * Get the [alt_names] column value.
     *
     * @return string
     */
    public function getAltNames()
    {
        return $this->alt_names;
    }

    /**
     * Get the [parent_id] column value.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
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
     * Get the [subdivision_type_id] column value.
     *
     * @return int
     */
    public function getSubdivisionTypeId()
    {
        return $this->subdivision_type_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [iso] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setIso($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->iso !== $v) {
            $this->iso = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_ISO] = true;
        }

        return $this;
    } // setIso()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [local_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setLocalName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->local_name !== $v) {
            $this->local_name = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_LOCAL_NAME] = true;
        }

        return $this;
    } // setLocalName()

    /**
     * Set the value of [en_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setEnName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->en_name !== $v) {
            $this->en_name = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_EN_NAME] = true;
        }

        return $this;
    } // setEnName()

    /**
     * Set the value of [alt_names] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setAltNames($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alt_names !== $v) {
            $this->alt_names = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_ALT_NAMES] = true;
        }

        return $this;
    } // setAltNames()

    /**
     * Set the value of [parent_id] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setParentId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->parent_id !== $v) {
            $this->parent_id = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_PARENT_ID] = true;
        }

        return $this;
    } // setParentId()

    /**
     * Set the value of [country_iso_nr] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setCountryIsoNr($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->country_iso_nr !== $v) {
            $this->country_iso_nr = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_COUNTRY_ISO_NR] = true;
        }

        if ($this->aCountry !== null && $this->aCountry->getIsoNr() !== $v) {
            $this->aCountry = null;
        }

        return $this;
    } // setCountryIsoNr()

    /**
     * Set the value of [subdivision_type_id] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function setSubdivisionTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->subdivision_type_id !== $v) {
            $this->subdivision_type_id = $v;
            $this->modifiedColumns[SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID] = true;
        }

        if ($this->aSubdivisionType !== null && $this->aSubdivisionType->getId() !== $v) {
            $this->aSubdivisionType = null;
        }

        return $this;
    } // setSubdivisionTypeId()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SubdivisionTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SubdivisionTableMap::translateFieldName('Iso', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iso = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SubdivisionTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SubdivisionTableMap::translateFieldName('LocalName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->local_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SubdivisionTableMap::translateFieldName('EnName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->en_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : SubdivisionTableMap::translateFieldName('AltNames', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alt_names = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : SubdivisionTableMap::translateFieldName('ParentId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->parent_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : SubdivisionTableMap::translateFieldName('CountryIsoNr', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_iso_nr = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : SubdivisionTableMap::translateFieldName('SubdivisionTypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->subdivision_type_id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = SubdivisionTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\Subdivision'), 0, $e);
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
        if ($this->aSubdivisionType !== null && $this->subdivision_type_id !== $this->aSubdivisionType->getId()) {
            $this->aSubdivisionType = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(SubdivisionTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSubdivisionQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCountry = null;
            $this->aSubdivisionType = null;
            $this->collUsers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Subdivision::setDeleted()
     * @see Subdivision::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubdivisionTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSubdivisionQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(SubdivisionTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SubdivisionTableMap::addInstanceToPool($this);
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

            if ($this->aSubdivisionType !== null) {
                if ($this->aSubdivisionType->isModified() || $this->aSubdivisionType->isNew()) {
                    $affectedRows += $this->aSubdivisionType->save($con);
                }
                $this->setSubdivisionType($this->aSubdivisionType);
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

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    foreach ($this->usersScheduledForDeletion as $user) {
                        // need to save related object because we set the relation to null
                        $user->save($con);
                    }
                    $this->usersScheduledForDeletion = null;
                }
            }

            if ($this->collUsers !== null) {
                foreach ($this->collUsers as $referrerFK) {
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

        $this->modifiedColumns[SubdivisionTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SubdivisionTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SubdivisionTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_ISO)) {
            $modifiedColumns[':p' . $index++]  = '`iso`';
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_LOCAL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`local_name`';
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_EN_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`en_name`';
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_ALT_NAMES)) {
            $modifiedColumns[':p' . $index++]  = '`alt_names`';
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_PARENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`parent_id`';
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_COUNTRY_ISO_NR)) {
            $modifiedColumns[':p' . $index++]  = '`country_iso_nr`';
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`subdivision_type_id`';
        }

        $sql = sprintf(
            'INSERT INTO `kk_subdivision` (%s) VALUES (%s)',
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
                    case '`iso`':
                        $stmt->bindValue($identifier, $this->iso, PDO::PARAM_STR);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`local_name`':
                        $stmt->bindValue($identifier, $this->local_name, PDO::PARAM_STR);
                        break;
                    case '`en_name`':
                        $stmt->bindValue($identifier, $this->en_name, PDO::PARAM_STR);
                        break;
                    case '`alt_names`':
                        $stmt->bindValue($identifier, $this->alt_names, PDO::PARAM_STR);
                        break;
                    case '`parent_id`':
                        $stmt->bindValue($identifier, $this->parent_id, PDO::PARAM_INT);
                        break;
                    case '`country_iso_nr`':
                        $stmt->bindValue($identifier, $this->country_iso_nr, PDO::PARAM_INT);
                        break;
                    case '`subdivision_type_id`':
                        $stmt->bindValue($identifier, $this->subdivision_type_id, PDO::PARAM_INT);
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
        $pos = SubdivisionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getIso();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getLocalName();
                break;
            case 4:
                return $this->getEnName();
                break;
            case 5:
                return $this->getAltNames();
                break;
            case 6:
                return $this->getParentId();
                break;
            case 7:
                return $this->getCountryIsoNr();
                break;
            case 8:
                return $this->getSubdivisionTypeId();
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

        if (isset($alreadyDumpedObjects['Subdivision'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Subdivision'][$this->hashCode()] = true;
        $keys = SubdivisionTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getIso(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getLocalName(),
            $keys[4] => $this->getEnName(),
            $keys[5] => $this->getAltNames(),
            $keys[6] => $this->getParentId(),
            $keys[7] => $this->getCountryIsoNr(),
            $keys[8] => $this->getSubdivisionTypeId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCountry) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'country';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_country';
                        break;
                    default:
                        $key = 'Country';
                }

                $result[$key] = $this->aCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSubdivisionType) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'subdivisionType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_subdivision_type';
                        break;
                    default:
                        $key = 'SubdivisionType';
                }

                $result[$key] = $this->aSubdivisionType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_users';
                        break;
                    default:
                        $key = 'Users';
                }

                $result[$key] = $this->collUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\keeko\core\model\Subdivision
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SubdivisionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\keeko\core\model\Subdivision
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setIso($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setLocalName($value);
                break;
            case 4:
                $this->setEnName($value);
                break;
            case 5:
                $this->setAltNames($value);
                break;
            case 6:
                $this->setParentId($value);
                break;
            case 7:
                $this->setCountryIsoNr($value);
                break;
            case 8:
                $this->setSubdivisionTypeId($value);
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
        $keys = SubdivisionTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setIso($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setLocalName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEnName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAltNames($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setParentId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCountryIsoNr($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setSubdivisionTypeId($arr[$keys[8]]);
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
     * @return $this|\keeko\core\model\Subdivision The current object, for fluid interface
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
        $criteria = new Criteria(SubdivisionTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SubdivisionTableMap::COL_ID)) {
            $criteria->add(SubdivisionTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_ISO)) {
            $criteria->add(SubdivisionTableMap::COL_ISO, $this->iso);
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_NAME)) {
            $criteria->add(SubdivisionTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_LOCAL_NAME)) {
            $criteria->add(SubdivisionTableMap::COL_LOCAL_NAME, $this->local_name);
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_EN_NAME)) {
            $criteria->add(SubdivisionTableMap::COL_EN_NAME, $this->en_name);
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_ALT_NAMES)) {
            $criteria->add(SubdivisionTableMap::COL_ALT_NAMES, $this->alt_names);
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_PARENT_ID)) {
            $criteria->add(SubdivisionTableMap::COL_PARENT_ID, $this->parent_id);
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_COUNTRY_ISO_NR)) {
            $criteria->add(SubdivisionTableMap::COL_COUNTRY_ISO_NR, $this->country_iso_nr);
        }
        if ($this->isColumnModified(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID)) {
            $criteria->add(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID, $this->subdivision_type_id);
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
        $criteria = ChildSubdivisionQuery::create();
        $criteria->add(SubdivisionTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\Subdivision (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setIso($this->getIso());
        $copyObj->setName($this->getName());
        $copyObj->setLocalName($this->getLocalName());
        $copyObj->setEnName($this->getEnName());
        $copyObj->setAltNames($this->getAltNames());
        $copyObj->setParentId($this->getParentId());
        $copyObj->setCountryIsoNr($this->getCountryIsoNr());
        $copyObj->setSubdivisionTypeId($this->getSubdivisionTypeId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUser($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\Subdivision Clone of current object.
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
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
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
            $v->addSubdivision($this);
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
                $this->aCountry->addSubdivisions($this);
             */
        }

        return $this->aCountry;
    }

    /**
     * Declares an association between this object and a ChildSubdivisionType object.
     *
     * @param  ChildSubdivisionType $v
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSubdivisionType(ChildSubdivisionType $v = null)
    {
        if ($v === null) {
            $this->setSubdivisionTypeId(NULL);
        } else {
            $this->setSubdivisionTypeId($v->getId());
        }

        $this->aSubdivisionType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSubdivisionType object, it will not be re-added.
        if ($v !== null) {
            $v->addSubdivision($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSubdivisionType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildSubdivisionType The associated ChildSubdivisionType object.
     * @throws PropelException
     */
    public function getSubdivisionType(ConnectionInterface $con = null)
    {
        if ($this->aSubdivisionType === null && ($this->subdivision_type_id !== null)) {
            $this->aSubdivisionType = ChildSubdivisionTypeQuery::create()->findPk($this->subdivision_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSubdivisionType->addSubdivisions($this);
             */
        }

        return $this->aSubdivisionType;
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
        if ('User' == $relationName) {
            return $this->initUsers();
        }
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsers collection loaded partially.
     */
    public function resetPartialUsers($v = true)
    {
        $this->collUsersPartial = $v;
    }

    /**
     * Initializes the collUsers collection.
     *
     * By default this just sets the collUsers collection to an empty array (like clearcollUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsers($overrideExisting = true)
    {
        if (null !== $this->collUsers && !$overrideExisting) {
            return;
        }
        $this->collUsers = new ObjectCollection();
        $this->collUsers->setModel('\keeko\core\model\User');
    }

    /**
     * Gets an array of ChildUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSubdivision is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     * @throws PropelException
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                // return empty collection
                $this->initUsers();
            } else {
                $collUsers = ChildUserQuery::create(null, $criteria)
                    ->filterBySubdivision($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersPartial && count($collUsers)) {
                        $this->initUsers(false);

                        foreach ($collUsers as $obj) {
                            if (false == $this->collUsers->contains($obj)) {
                                $this->collUsers->append($obj);
                            }
                        }

                        $this->collUsersPartial = true;
                    }

                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    foreach ($this->collUsers as $obj) {
                        if ($obj->isNew()) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of ChildUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $users A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSubdivision The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $usersToDelete */
        $usersToDelete = $this->getUsers(new Criteria(), $con)->diff($users);


        $this->usersScheduledForDeletion = $usersToDelete;

        foreach ($usersToDelete as $userRemoved) {
            $userRemoved->setSubdivision(null);
        }

        $this->collUsers = null;
        foreach ($users as $user) {
            $this->addUser($user);
        }

        $this->collUsers = $users;
        $this->collUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsers());
            }

            $query = ChildUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySubdivision($this)
                ->count($con);
        }

        return count($this->collUsers);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\keeko\core\model\Subdivision The current object (for fluent API support)
     */
    public function addUser(ChildUser $l)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
            $this->collUsersPartial = true;
        }

        if (!$this->collUsers->contains($l)) {
            $this->doAddUser($l);
        }

        return $this;
    }

    /**
     * @param ChildUser $user The ChildUser object to add.
     */
    protected function doAddUser(ChildUser $user)
    {
        $this->collUsers[]= $user;
        $user->setSubdivision($this);
    }

    /**
     * @param  ChildUser $user The ChildUser object to remove.
     * @return $this|ChildSubdivision The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) {
            $pos = $this->collUsers->search($user);
            $this->collUsers->remove($pos);
            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }
            $this->usersScheduledForDeletion[]= $user;
            $user->setSubdivision(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Subdivision is new, it will return
     * an empty collection; or if this Subdivision has previously
     * been saved, it will retrieve related Users from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Subdivision.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getUsersJoinCountry(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserQuery::create(null, $criteria);
        $query->joinWith('Country', $joinBehavior);

        return $this->getUsers($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aCountry) {
            $this->aCountry->removeSubdivision($this);
        }
        if (null !== $this->aSubdivisionType) {
            $this->aSubdivisionType->removeSubdivision($this);
        }
        $this->id = null;
        $this->iso = null;
        $this->name = null;
        $this->local_name = null;
        $this->en_name = null;
        $this->alt_names = null;
        $this->parent_id = null;
        $this->country_iso_nr = null;
        $this->subdivision_type_id = null;
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
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUsers = null;
        $this->aCountry = null;
        $this->aSubdivisionType = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SubdivisionTableMap::DEFAULT_STRING_FORMAT);
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
