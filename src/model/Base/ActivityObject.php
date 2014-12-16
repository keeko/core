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
use keeko\core\model\Activity as ChildActivity;
use keeko\core\model\ActivityObject as ChildActivityObject;
use keeko\core\model\ActivityObjectQuery as ChildActivityObjectQuery;
use keeko\core\model\ActivityQuery as ChildActivityQuery;
use keeko\core\model\Map\ActivityObjectTableMap;

/**
 * Base class that represents a row from the 'kk_activity_object' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class ActivityObject implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\ActivityObjectTableMap';


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
     * The value for the class_name field.
     * @var        string
     */
    protected $class_name;

    /**
     * The value for the type field.
     * @var        string
     */
    protected $type;

    /**
     * The value for the display_name field.
     * @var        string
     */
    protected $display_name;

    /**
     * The value for the url field.
     * @var        string
     */
    protected $url;

    /**
     * The value for the reference_id field.
     * @var        int
     */
    protected $reference_id;

    /**
     * The value for the version field.
     * @var        int
     */
    protected $version;

    /**
     * The value for the extra field.
     * @var        string
     */
    protected $extra;

    /**
     * @var        ObjectCollection|ChildActivity[] Collection to store aggregation of ChildActivity objects.
     */
    protected $collActivitiesRelatedByObjectId;
    protected $collActivitiesRelatedByObjectIdPartial;

    /**
     * @var        ObjectCollection|ChildActivity[] Collection to store aggregation of ChildActivity objects.
     */
    protected $collActivitiesRelatedByTargetId;
    protected $collActivitiesRelatedByTargetIdPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActivity[]
     */
    protected $activitiesRelatedByObjectIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActivity[]
     */
    protected $activitiesRelatedByTargetIdScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\ActivityObject object.
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
     * Compares this with another <code>ActivityObject</code> instance.  If
     * <code>obj</code> is an instance of <code>ActivityObject</code>, delegates to
     * <code>equals(ActivityObject)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|ActivityObject The current object, for fluid interface
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
     * Get the [class_name] column value.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->class_name;
    }

    /**
     * Get the [type] column value.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * Get the [url] column value.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the [reference_id] column value.
     *
     * @return int
     */
    public function getReferenceId()
    {
        return $this->reference_id;
    }

    /**
     * Get the [version] column value.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get the [extra] column value.
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ActivityObjectTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [class_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function setClassName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->class_name !== $v) {
            $this->class_name = $v;
            $this->modifiedColumns[ActivityObjectTableMap::COL_CLASS_NAME] = true;
        }

        return $this;
    } // setClassName()

    /**
     * Set the value of [type] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[ActivityObjectTableMap::COL_TYPE] = true;
        }

        return $this;
    } // setType()

    /**
     * Set the value of [display_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function setDisplayName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->display_name !== $v) {
            $this->display_name = $v;
            $this->modifiedColumns[ActivityObjectTableMap::COL_DISPLAY_NAME] = true;
        }

        return $this;
    } // setDisplayName()

    /**
     * Set the value of [url] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[ActivityObjectTableMap::COL_URL] = true;
        }

        return $this;
    } // setUrl()

    /**
     * Set the value of [reference_id] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function setReferenceId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reference_id !== $v) {
            $this->reference_id = $v;
            $this->modifiedColumns[ActivityObjectTableMap::COL_REFERENCE_ID] = true;
        }

        return $this;
    } // setReferenceId()

    /**
     * Set the value of [version] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[ActivityObjectTableMap::COL_VERSION] = true;
        }

        return $this;
    } // setVersion()

    /**
     * Set the value of [extra] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function setExtra($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->extra !== $v) {
            $this->extra = $v;
            $this->modifiedColumns[ActivityObjectTableMap::COL_EXTRA] = true;
        }

        return $this;
    } // setExtra()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ActivityObjectTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ActivityObjectTableMap::translateFieldName('ClassName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->class_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ActivityObjectTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ActivityObjectTableMap::translateFieldName('DisplayName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->display_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ActivityObjectTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ActivityObjectTableMap::translateFieldName('ReferenceId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reference_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ActivityObjectTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ActivityObjectTableMap::translateFieldName('Extra', TableMap::TYPE_PHPNAME, $indexType)];
            $this->extra = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = ActivityObjectTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\ActivityObject'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(ActivityObjectTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildActivityObjectQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collActivitiesRelatedByObjectId = null;

            $this->collActivitiesRelatedByTargetId = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see ActivityObject::setDeleted()
     * @see ActivityObject::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityObjectTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildActivityObjectQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityObjectTableMap::DATABASE_NAME);
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
                ActivityObjectTableMap::addInstanceToPool($this);
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

            if ($this->activitiesRelatedByObjectIdScheduledForDeletion !== null) {
                if (!$this->activitiesRelatedByObjectIdScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\ActivityQuery::create()
                        ->filterByPrimaryKeys($this->activitiesRelatedByObjectIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->activitiesRelatedByObjectIdScheduledForDeletion = null;
                }
            }

            if ($this->collActivitiesRelatedByObjectId !== null) {
                foreach ($this->collActivitiesRelatedByObjectId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->activitiesRelatedByTargetIdScheduledForDeletion !== null) {
                if (!$this->activitiesRelatedByTargetIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->activitiesRelatedByTargetIdScheduledForDeletion as $activityRelatedByTargetId) {
                        // need to save related object because we set the relation to null
                        $activityRelatedByTargetId->save($con);
                    }
                    $this->activitiesRelatedByTargetIdScheduledForDeletion = null;
                }
            }

            if ($this->collActivitiesRelatedByTargetId !== null) {
                foreach ($this->collActivitiesRelatedByTargetId as $referrerFK) {
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

        $this->modifiedColumns[ActivityObjectTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ActivityObjectTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ActivityObjectTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_CLASS_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`class_name`';
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_DISPLAY_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`display_name`';
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_URL)) {
            $modifiedColumns[':p' . $index++]  = '`url`';
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_REFERENCE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`reference_id`';
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_VERSION)) {
            $modifiedColumns[':p' . $index++]  = '`version`';
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_EXTRA)) {
            $modifiedColumns[':p' . $index++]  = '`extra`';
        }

        $sql = sprintf(
            'INSERT INTO `kk_activity_object` (%s) VALUES (%s)',
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
                    case '`class_name`':
                        $stmt->bindValue($identifier, $this->class_name, PDO::PARAM_STR);
                        break;
                    case '`type`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
                        break;
                    case '`display_name`':
                        $stmt->bindValue($identifier, $this->display_name, PDO::PARAM_STR);
                        break;
                    case '`url`':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
                        break;
                    case '`reference_id`':
                        $stmt->bindValue($identifier, $this->reference_id, PDO::PARAM_INT);
                        break;
                    case '`version`':
                        $stmt->bindValue($identifier, $this->version, PDO::PARAM_INT);
                        break;
                    case '`extra`':
                        $stmt->bindValue($identifier, $this->extra, PDO::PARAM_STR);
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
        $pos = ActivityObjectTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getClassName();
                break;
            case 2:
                return $this->getType();
                break;
            case 3:
                return $this->getDisplayName();
                break;
            case 4:
                return $this->getUrl();
                break;
            case 5:
                return $this->getReferenceId();
                break;
            case 6:
                return $this->getVersion();
                break;
            case 7:
                return $this->getExtra();
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

        if (isset($alreadyDumpedObjects['ActivityObject'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ActivityObject'][$this->hashCode()] = true;
        $keys = ActivityObjectTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getClassName(),
            $keys[2] => $this->getType(),
            $keys[3] => $this->getDisplayName(),
            $keys[4] => $this->getUrl(),
            $keys[5] => $this->getReferenceId(),
            $keys[6] => $this->getVersion(),
            $keys[7] => $this->getExtra(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collActivitiesRelatedByObjectId) {

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

                $result[$key] = $this->collActivitiesRelatedByObjectId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collActivitiesRelatedByTargetId) {

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

                $result[$key] = $this->collActivitiesRelatedByTargetId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\keeko\core\model\ActivityObject
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ActivityObjectTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\keeko\core\model\ActivityObject
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setClassName($value);
                break;
            case 2:
                $this->setType($value);
                break;
            case 3:
                $this->setDisplayName($value);
                break;
            case 4:
                $this->setUrl($value);
                break;
            case 5:
                $this->setReferenceId($value);
                break;
            case 6:
                $this->setVersion($value);
                break;
            case 7:
                $this->setExtra($value);
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
        $keys = ActivityObjectTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setClassName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setType($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setDisplayName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUrl($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setReferenceId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setVersion($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setExtra($arr[$keys[7]]);
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
     * @return $this|\keeko\core\model\ActivityObject The current object, for fluid interface
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
        $criteria = new Criteria(ActivityObjectTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ActivityObjectTableMap::COL_ID)) {
            $criteria->add(ActivityObjectTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_CLASS_NAME)) {
            $criteria->add(ActivityObjectTableMap::COL_CLASS_NAME, $this->class_name);
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_TYPE)) {
            $criteria->add(ActivityObjectTableMap::COL_TYPE, $this->type);
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_DISPLAY_NAME)) {
            $criteria->add(ActivityObjectTableMap::COL_DISPLAY_NAME, $this->display_name);
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_URL)) {
            $criteria->add(ActivityObjectTableMap::COL_URL, $this->url);
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_REFERENCE_ID)) {
            $criteria->add(ActivityObjectTableMap::COL_REFERENCE_ID, $this->reference_id);
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_VERSION)) {
            $criteria->add(ActivityObjectTableMap::COL_VERSION, $this->version);
        }
        if ($this->isColumnModified(ActivityObjectTableMap::COL_EXTRA)) {
            $criteria->add(ActivityObjectTableMap::COL_EXTRA, $this->extra);
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
        $criteria = ChildActivityObjectQuery::create();
        $criteria->add(ActivityObjectTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\ActivityObject (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setClassName($this->getClassName());
        $copyObj->setType($this->getType());
        $copyObj->setDisplayName($this->getDisplayName());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setReferenceId($this->getReferenceId());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setExtra($this->getExtra());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getActivitiesRelatedByObjectId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addActivityRelatedByObjectId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getActivitiesRelatedByTargetId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addActivityRelatedByTargetId($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\ActivityObject Clone of current object.
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
        if ('ActivityRelatedByObjectId' == $relationName) {
            return $this->initActivitiesRelatedByObjectId();
        }
        if ('ActivityRelatedByTargetId' == $relationName) {
            return $this->initActivitiesRelatedByTargetId();
        }
    }

    /**
     * Clears out the collActivitiesRelatedByObjectId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActivitiesRelatedByObjectId()
     */
    public function clearActivitiesRelatedByObjectId()
    {
        $this->collActivitiesRelatedByObjectId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActivitiesRelatedByObjectId collection loaded partially.
     */
    public function resetPartialActivitiesRelatedByObjectId($v = true)
    {
        $this->collActivitiesRelatedByObjectIdPartial = $v;
    }

    /**
     * Initializes the collActivitiesRelatedByObjectId collection.
     *
     * By default this just sets the collActivitiesRelatedByObjectId collection to an empty array (like clearcollActivitiesRelatedByObjectId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActivitiesRelatedByObjectId($overrideExisting = true)
    {
        if (null !== $this->collActivitiesRelatedByObjectId && !$overrideExisting) {
            return;
        }
        $this->collActivitiesRelatedByObjectId = new ObjectCollection();
        $this->collActivitiesRelatedByObjectId->setModel('\keeko\core\model\Activity');
    }

    /**
     * Gets an array of ChildActivity objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildActivityObject is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildActivity[] List of ChildActivity objects
     * @throws PropelException
     */
    public function getActivitiesRelatedByObjectId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActivitiesRelatedByObjectIdPartial && !$this->isNew();
        if (null === $this->collActivitiesRelatedByObjectId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActivitiesRelatedByObjectId) {
                // return empty collection
                $this->initActivitiesRelatedByObjectId();
            } else {
                $collActivitiesRelatedByObjectId = ChildActivityQuery::create(null, $criteria)
                    ->filterByObject($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActivitiesRelatedByObjectIdPartial && count($collActivitiesRelatedByObjectId)) {
                        $this->initActivitiesRelatedByObjectId(false);

                        foreach ($collActivitiesRelatedByObjectId as $obj) {
                            if (false == $this->collActivitiesRelatedByObjectId->contains($obj)) {
                                $this->collActivitiesRelatedByObjectId->append($obj);
                            }
                        }

                        $this->collActivitiesRelatedByObjectIdPartial = true;
                    }

                    return $collActivitiesRelatedByObjectId;
                }

                if ($partial && $this->collActivitiesRelatedByObjectId) {
                    foreach ($this->collActivitiesRelatedByObjectId as $obj) {
                        if ($obj->isNew()) {
                            $collActivitiesRelatedByObjectId[] = $obj;
                        }
                    }
                }

                $this->collActivitiesRelatedByObjectId = $collActivitiesRelatedByObjectId;
                $this->collActivitiesRelatedByObjectIdPartial = false;
            }
        }

        return $this->collActivitiesRelatedByObjectId;
    }

    /**
     * Sets a collection of ChildActivity objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $activitiesRelatedByObjectId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildActivityObject The current object (for fluent API support)
     */
    public function setActivitiesRelatedByObjectId(Collection $activitiesRelatedByObjectId, ConnectionInterface $con = null)
    {
        /** @var ChildActivity[] $activitiesRelatedByObjectIdToDelete */
        $activitiesRelatedByObjectIdToDelete = $this->getActivitiesRelatedByObjectId(new Criteria(), $con)->diff($activitiesRelatedByObjectId);


        $this->activitiesRelatedByObjectIdScheduledForDeletion = $activitiesRelatedByObjectIdToDelete;

        foreach ($activitiesRelatedByObjectIdToDelete as $activityRelatedByObjectIdRemoved) {
            $activityRelatedByObjectIdRemoved->setObject(null);
        }

        $this->collActivitiesRelatedByObjectId = null;
        foreach ($activitiesRelatedByObjectId as $activityRelatedByObjectId) {
            $this->addActivityRelatedByObjectId($activityRelatedByObjectId);
        }

        $this->collActivitiesRelatedByObjectId = $activitiesRelatedByObjectId;
        $this->collActivitiesRelatedByObjectIdPartial = false;

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
    public function countActivitiesRelatedByObjectId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActivitiesRelatedByObjectIdPartial && !$this->isNew();
        if (null === $this->collActivitiesRelatedByObjectId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActivitiesRelatedByObjectId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActivitiesRelatedByObjectId());
            }

            $query = ChildActivityQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByObject($this)
                ->count($con);
        }

        return count($this->collActivitiesRelatedByObjectId);
    }

    /**
     * Method called to associate a ChildActivity object to this object
     * through the ChildActivity foreign key attribute.
     *
     * @param  ChildActivity $l ChildActivity
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function addActivityRelatedByObjectId(ChildActivity $l)
    {
        if ($this->collActivitiesRelatedByObjectId === null) {
            $this->initActivitiesRelatedByObjectId();
            $this->collActivitiesRelatedByObjectIdPartial = true;
        }

        if (!$this->collActivitiesRelatedByObjectId->contains($l)) {
            $this->doAddActivityRelatedByObjectId($l);
        }

        return $this;
    }

    /**
     * @param ChildActivity $activityRelatedByObjectId The ChildActivity object to add.
     */
    protected function doAddActivityRelatedByObjectId(ChildActivity $activityRelatedByObjectId)
    {
        $this->collActivitiesRelatedByObjectId[]= $activityRelatedByObjectId;
        $activityRelatedByObjectId->setObject($this);
    }

    /**
     * @param  ChildActivity $activityRelatedByObjectId The ChildActivity object to remove.
     * @return $this|ChildActivityObject The current object (for fluent API support)
     */
    public function removeActivityRelatedByObjectId(ChildActivity $activityRelatedByObjectId)
    {
        if ($this->getActivitiesRelatedByObjectId()->contains($activityRelatedByObjectId)) {
            $pos = $this->collActivitiesRelatedByObjectId->search($activityRelatedByObjectId);
            $this->collActivitiesRelatedByObjectId->remove($pos);
            if (null === $this->activitiesRelatedByObjectIdScheduledForDeletion) {
                $this->activitiesRelatedByObjectIdScheduledForDeletion = clone $this->collActivitiesRelatedByObjectId;
                $this->activitiesRelatedByObjectIdScheduledForDeletion->clear();
            }
            $this->activitiesRelatedByObjectIdScheduledForDeletion[]= clone $activityRelatedByObjectId;
            $activityRelatedByObjectId->setObject(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ActivityObject is new, it will return
     * an empty collection; or if this ActivityObject has previously
     * been saved, it will retrieve related ActivitiesRelatedByObjectId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ActivityObject.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildActivity[] List of ChildActivity objects
     */
    public function getActivitiesRelatedByObjectIdJoinActor(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildActivityQuery::create(null, $criteria);
        $query->joinWith('Actor', $joinBehavior);

        return $this->getActivitiesRelatedByObjectId($query, $con);
    }

    /**
     * Clears out the collActivitiesRelatedByTargetId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActivitiesRelatedByTargetId()
     */
    public function clearActivitiesRelatedByTargetId()
    {
        $this->collActivitiesRelatedByTargetId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActivitiesRelatedByTargetId collection loaded partially.
     */
    public function resetPartialActivitiesRelatedByTargetId($v = true)
    {
        $this->collActivitiesRelatedByTargetIdPartial = $v;
    }

    /**
     * Initializes the collActivitiesRelatedByTargetId collection.
     *
     * By default this just sets the collActivitiesRelatedByTargetId collection to an empty array (like clearcollActivitiesRelatedByTargetId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActivitiesRelatedByTargetId($overrideExisting = true)
    {
        if (null !== $this->collActivitiesRelatedByTargetId && !$overrideExisting) {
            return;
        }
        $this->collActivitiesRelatedByTargetId = new ObjectCollection();
        $this->collActivitiesRelatedByTargetId->setModel('\keeko\core\model\Activity');
    }

    /**
     * Gets an array of ChildActivity objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildActivityObject is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildActivity[] List of ChildActivity objects
     * @throws PropelException
     */
    public function getActivitiesRelatedByTargetId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActivitiesRelatedByTargetIdPartial && !$this->isNew();
        if (null === $this->collActivitiesRelatedByTargetId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActivitiesRelatedByTargetId) {
                // return empty collection
                $this->initActivitiesRelatedByTargetId();
            } else {
                $collActivitiesRelatedByTargetId = ChildActivityQuery::create(null, $criteria)
                    ->filterByTarget($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActivitiesRelatedByTargetIdPartial && count($collActivitiesRelatedByTargetId)) {
                        $this->initActivitiesRelatedByTargetId(false);

                        foreach ($collActivitiesRelatedByTargetId as $obj) {
                            if (false == $this->collActivitiesRelatedByTargetId->contains($obj)) {
                                $this->collActivitiesRelatedByTargetId->append($obj);
                            }
                        }

                        $this->collActivitiesRelatedByTargetIdPartial = true;
                    }

                    return $collActivitiesRelatedByTargetId;
                }

                if ($partial && $this->collActivitiesRelatedByTargetId) {
                    foreach ($this->collActivitiesRelatedByTargetId as $obj) {
                        if ($obj->isNew()) {
                            $collActivitiesRelatedByTargetId[] = $obj;
                        }
                    }
                }

                $this->collActivitiesRelatedByTargetId = $collActivitiesRelatedByTargetId;
                $this->collActivitiesRelatedByTargetIdPartial = false;
            }
        }

        return $this->collActivitiesRelatedByTargetId;
    }

    /**
     * Sets a collection of ChildActivity objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $activitiesRelatedByTargetId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildActivityObject The current object (for fluent API support)
     */
    public function setActivitiesRelatedByTargetId(Collection $activitiesRelatedByTargetId, ConnectionInterface $con = null)
    {
        /** @var ChildActivity[] $activitiesRelatedByTargetIdToDelete */
        $activitiesRelatedByTargetIdToDelete = $this->getActivitiesRelatedByTargetId(new Criteria(), $con)->diff($activitiesRelatedByTargetId);


        $this->activitiesRelatedByTargetIdScheduledForDeletion = $activitiesRelatedByTargetIdToDelete;

        foreach ($activitiesRelatedByTargetIdToDelete as $activityRelatedByTargetIdRemoved) {
            $activityRelatedByTargetIdRemoved->setTarget(null);
        }

        $this->collActivitiesRelatedByTargetId = null;
        foreach ($activitiesRelatedByTargetId as $activityRelatedByTargetId) {
            $this->addActivityRelatedByTargetId($activityRelatedByTargetId);
        }

        $this->collActivitiesRelatedByTargetId = $activitiesRelatedByTargetId;
        $this->collActivitiesRelatedByTargetIdPartial = false;

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
    public function countActivitiesRelatedByTargetId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActivitiesRelatedByTargetIdPartial && !$this->isNew();
        if (null === $this->collActivitiesRelatedByTargetId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActivitiesRelatedByTargetId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActivitiesRelatedByTargetId());
            }

            $query = ChildActivityQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTarget($this)
                ->count($con);
        }

        return count($this->collActivitiesRelatedByTargetId);
    }

    /**
     * Method called to associate a ChildActivity object to this object
     * through the ChildActivity foreign key attribute.
     *
     * @param  ChildActivity $l ChildActivity
     * @return $this|\keeko\core\model\ActivityObject The current object (for fluent API support)
     */
    public function addActivityRelatedByTargetId(ChildActivity $l)
    {
        if ($this->collActivitiesRelatedByTargetId === null) {
            $this->initActivitiesRelatedByTargetId();
            $this->collActivitiesRelatedByTargetIdPartial = true;
        }

        if (!$this->collActivitiesRelatedByTargetId->contains($l)) {
            $this->doAddActivityRelatedByTargetId($l);
        }

        return $this;
    }

    /**
     * @param ChildActivity $activityRelatedByTargetId The ChildActivity object to add.
     */
    protected function doAddActivityRelatedByTargetId(ChildActivity $activityRelatedByTargetId)
    {
        $this->collActivitiesRelatedByTargetId[]= $activityRelatedByTargetId;
        $activityRelatedByTargetId->setTarget($this);
    }

    /**
     * @param  ChildActivity $activityRelatedByTargetId The ChildActivity object to remove.
     * @return $this|ChildActivityObject The current object (for fluent API support)
     */
    public function removeActivityRelatedByTargetId(ChildActivity $activityRelatedByTargetId)
    {
        if ($this->getActivitiesRelatedByTargetId()->contains($activityRelatedByTargetId)) {
            $pos = $this->collActivitiesRelatedByTargetId->search($activityRelatedByTargetId);
            $this->collActivitiesRelatedByTargetId->remove($pos);
            if (null === $this->activitiesRelatedByTargetIdScheduledForDeletion) {
                $this->activitiesRelatedByTargetIdScheduledForDeletion = clone $this->collActivitiesRelatedByTargetId;
                $this->activitiesRelatedByTargetIdScheduledForDeletion->clear();
            }
            $this->activitiesRelatedByTargetIdScheduledForDeletion[]= $activityRelatedByTargetId;
            $activityRelatedByTargetId->setTarget(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ActivityObject is new, it will return
     * an empty collection; or if this ActivityObject has previously
     * been saved, it will retrieve related ActivitiesRelatedByTargetId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ActivityObject.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildActivity[] List of ChildActivity objects
     */
    public function getActivitiesRelatedByTargetIdJoinActor(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildActivityQuery::create(null, $criteria);
        $query->joinWith('Actor', $joinBehavior);

        return $this->getActivitiesRelatedByTargetId($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->class_name = null;
        $this->type = null;
        $this->display_name = null;
        $this->url = null;
        $this->reference_id = null;
        $this->version = null;
        $this->extra = null;
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
            if ($this->collActivitiesRelatedByObjectId) {
                foreach ($this->collActivitiesRelatedByObjectId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collActivitiesRelatedByTargetId) {
                foreach ($this->collActivitiesRelatedByTargetId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collActivitiesRelatedByObjectId = null;
        $this->collActivitiesRelatedByTargetId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ActivityObjectTableMap::DEFAULT_STRING_FORMAT);
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
