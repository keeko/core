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
use keeko\core\model\Action as ChildAction;
use keeko\core\model\ActionQuery as ChildActionQuery;
use keeko\core\model\Module as ChildModule;
use keeko\core\model\ModuleQuery as ChildModuleQuery;
use keeko\core\model\Package as ChildPackage;
use keeko\core\model\PackageQuery as ChildPackageQuery;
use keeko\core\model\Map\ModuleTableMap;

abstract class Module extends ChildPackage implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\ModuleTableMap';


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
     * The value for the class_name field.
     * @var        string
     */
    protected $class_name;

    /**
     * The value for the activated_version field.
     * @var        string
     */
    protected $activated_version;

    /**
     * The value for the default_action field.
     * @var        string
     */
    protected $default_action;

    /**
     * The value for the has_api field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $has_api;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the installed_version field.
     * @var        string
     */
    protected $installed_version;

    /**
     * @var        ChildPackage
     */
    protected $aPackage;

    /**
     * @var        ObjectCollection|ChildAction[] Collection to store aggregation of ChildAction objects.
     */
    protected $collActions;
    protected $collActionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAction[]
     */
    protected $actionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->has_api = false;
    }

    /**
     * Initializes internal state of keeko\core\model\Base\Module object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>Module</code> instance.  If
     * <code>obj</code> is an instance of <code>Module</code>, delegates to
     * <code>equals(Module)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Module The current object, for fluid interface
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
     * Get the [class_name] column value.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->class_name;
    }

    /**
     * Get the [activated_version] column value.
     *
     * @return string
     */
    public function getActivatedVersion()
    {
        return $this->activated_version;
    }

    /**
     * Get the [default_action] column value.
     *
     * @return string
     */
    public function getDefaultAction()
    {
        return $this->default_action;
    }

    /**
     * Get the [has_api] column value.
     *
     * @return boolean
     */
    public function getApi()
    {
        return $this->has_api;
    }

    /**
     * Get the [has_api] column value.
     *
     * @return boolean
     */
    public function isApi()
    {
        return $this->getApi();
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [installed_version] column value.
     *
     * @return string
     */
    public function getInstalledVersion()
    {
        return $this->installed_version;
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
            if ($this->has_api !== false) {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ModuleTableMap::translateFieldName('ClassName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->class_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ModuleTableMap::translateFieldName('ActivatedVersion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->activated_version = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ModuleTableMap::translateFieldName('DefaultAction', TableMap::TYPE_PHPNAME, $indexType)];
            $this->default_action = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ModuleTableMap::translateFieldName('Api', TableMap::TYPE_PHPNAME, $indexType)];
            $this->has_api = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ModuleTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ModuleTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ModuleTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ModuleTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ModuleTableMap::translateFieldName('InstalledVersion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->installed_version = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = ModuleTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\Module'), 0, $e);
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
        if ($this->aPackage !== null && $this->id !== $this->aPackage->getId()) {
            $this->aPackage = null;
        }
    } // ensureConsistency

    /**
     * Set the value of [class_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setClassName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->class_name !== $v) {
            $this->class_name = $v;
            $this->modifiedColumns[ModuleTableMap::COL_CLASS_NAME] = true;
        }

        return $this;
    } // setClassName()

    /**
     * Set the value of [activated_version] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setActivatedVersion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->activated_version !== $v) {
            $this->activated_version = $v;
            $this->modifiedColumns[ModuleTableMap::COL_ACTIVATED_VERSION] = true;
        }

        return $this;
    } // setActivatedVersion()

    /**
     * Set the value of [default_action] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setDefaultAction($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->default_action !== $v) {
            $this->default_action = $v;
            $this->modifiedColumns[ModuleTableMap::COL_DEFAULT_ACTION] = true;
        }

        return $this;
    } // setDefaultAction()

    /**
     * Sets the value of the [has_api] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setApi($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->has_api !== $v) {
            $this->has_api = $v;
            $this->modifiedColumns[ModuleTableMap::COL_HAS_API] = true;
        }

        return $this;
    } // setApi()

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ModuleTableMap::COL_ID] = true;
        }

        if ($this->aPackage !== null && $this->aPackage->getId() !== $v) {
            $this->aPackage = null;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[ModuleTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[ModuleTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[ModuleTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [installed_version] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function setInstalledVersion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->installed_version !== $v) {
            $this->installed_version = $v;
            $this->modifiedColumns[ModuleTableMap::COL_INSTALLED_VERSION] = true;
        }

        return $this;
    } // setInstalledVersion()

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
            $con = Propel::getServiceContainer()->getReadConnection(ModuleTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildModuleQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPackage = null;
            $this->collActions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Module::setDeleted()
     * @see Module::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ModuleTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildModuleQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // concrete_inheritance behavior
                $this->getParentOrCreate($con)->delete($con);

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
            $con = Propel::getServiceContainer()->getWriteConnection(ModuleTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            // concrete_inheritance behavior
            $parent = $this->getSyncParent($con);
            $parent->save($con);
            $this->setPrimaryKey($parent->getPrimaryKey());

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
                ModuleTableMap::addInstanceToPool($this);
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

            if ($this->aPackage !== null) {
                if ($this->aPackage->isModified() || $this->aPackage->isNew()) {
                    $affectedRows += $this->aPackage->save($con);
                }
                $this->setPackage($this->aPackage);
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

            if ($this->actionsScheduledForDeletion !== null) {
                if (!$this->actionsScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\ActionQuery::create()
                        ->filterByPrimaryKeys($this->actionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->actionsScheduledForDeletion = null;
                }
            }

            if ($this->collActions !== null) {
                foreach ($this->collActions as $referrerFK) {
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ModuleTableMap::COL_CLASS_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'CLASS_NAME';
        }
        if ($this->isColumnModified(ModuleTableMap::COL_ACTIVATED_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'ACTIVATED_VERSION';
        }
        if ($this->isColumnModified(ModuleTableMap::COL_DEFAULT_ACTION)) {
            $modifiedColumns[':p' . $index++]  = 'DEFAULT_ACTION';
        }
        if ($this->isColumnModified(ModuleTableMap::COL_HAS_API)) {
            $modifiedColumns[':p' . $index++]  = 'HAS_API';
        }
        if ($this->isColumnModified(ModuleTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(ModuleTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'NAME';
        }
        if ($this->isColumnModified(ModuleTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'TITLE';
        }
        if ($this->isColumnModified(ModuleTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'DESCRIPTION';
        }
        if ($this->isColumnModified(ModuleTableMap::COL_INSTALLED_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'INSTALLED_VERSION';
        }

        $sql = sprintf(
            'INSERT INTO keeko_module (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'CLASS_NAME':
                        $stmt->bindValue($identifier, $this->class_name, PDO::PARAM_STR);
                        break;
                    case 'ACTIVATED_VERSION':
                        $stmt->bindValue($identifier, $this->activated_version, PDO::PARAM_STR);
                        break;
                    case 'DEFAULT_ACTION':
                        $stmt->bindValue($identifier, $this->default_action, PDO::PARAM_STR);
                        break;
                    case 'HAS_API':
                        $stmt->bindValue($identifier, (int) $this->has_api, PDO::PARAM_INT);
                        break;
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'NAME':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'TITLE':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'DESCRIPTION':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'INSTALLED_VERSION':
                        $stmt->bindValue($identifier, $this->installed_version, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

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
        $pos = ModuleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getClassName();
                break;
            case 1:
                return $this->getActivatedVersion();
                break;
            case 2:
                return $this->getDefaultAction();
                break;
            case 3:
                return $this->getApi();
                break;
            case 4:
                return $this->getId();
                break;
            case 5:
                return $this->getName();
                break;
            case 6:
                return $this->getTitle();
                break;
            case 7:
                return $this->getDescription();
                break;
            case 8:
                return $this->getInstalledVersion();
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
        if (isset($alreadyDumpedObjects['Module'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Module'][$this->getPrimaryKey()] = true;
        $keys = ModuleTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getClassName(),
            $keys[1] => $this->getActivatedVersion(),
            $keys[2] => $this->getDefaultAction(),
            $keys[3] => $this->getApi(),
            $keys[4] => $this->getId(),
            $keys[5] => $this->getName(),
            $keys[6] => $this->getTitle(),
            $keys[7] => $this->getDescription(),
            $keys[8] => $this->getInstalledVersion(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPackage) {
                $result['Package'] = $this->aPackage->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collActions) {
                $result['Actions'] = $this->collActions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\keeko\core\model\Module
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ModuleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\keeko\core\model\Module
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setClassName($value);
                break;
            case 1:
                $this->setActivatedVersion($value);
                break;
            case 2:
                $this->setDefaultAction($value);
                break;
            case 3:
                $this->setApi($value);
                break;
            case 4:
                $this->setId($value);
                break;
            case 5:
                $this->setName($value);
                break;
            case 6:
                $this->setTitle($value);
                break;
            case 7:
                $this->setDescription($value);
                break;
            case 8:
                $this->setInstalledVersion($value);
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
        $keys = ModuleTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setClassName($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setActivatedVersion($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDefaultAction($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setApi($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setName($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTitle($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setDescription($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setInstalledVersion($arr[$keys[8]]);
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
     * @return $this|\keeko\core\model\Module The current object, for fluid interface
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
        $criteria = new Criteria(ModuleTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ModuleTableMap::COL_CLASS_NAME)) {
            $criteria->add(ModuleTableMap::COL_CLASS_NAME, $this->class_name);
        }
        if ($this->isColumnModified(ModuleTableMap::COL_ACTIVATED_VERSION)) {
            $criteria->add(ModuleTableMap::COL_ACTIVATED_VERSION, $this->activated_version);
        }
        if ($this->isColumnModified(ModuleTableMap::COL_DEFAULT_ACTION)) {
            $criteria->add(ModuleTableMap::COL_DEFAULT_ACTION, $this->default_action);
        }
        if ($this->isColumnModified(ModuleTableMap::COL_HAS_API)) {
            $criteria->add(ModuleTableMap::COL_HAS_API, $this->has_api);
        }
        if ($this->isColumnModified(ModuleTableMap::COL_ID)) {
            $criteria->add(ModuleTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ModuleTableMap::COL_NAME)) {
            $criteria->add(ModuleTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(ModuleTableMap::COL_TITLE)) {
            $criteria->add(ModuleTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(ModuleTableMap::COL_DESCRIPTION)) {
            $criteria->add(ModuleTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(ModuleTableMap::COL_INSTALLED_VERSION)) {
            $criteria->add(ModuleTableMap::COL_INSTALLED_VERSION, $this->installed_version);
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
        $criteria = new Criteria(ModuleTableMap::DATABASE_NAME);
        $criteria->add(ModuleTableMap::COL_ID, $this->id);

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

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation keeko_module_fk_7d823f to table keeko_package
        if ($this->aPackage && $hash = spl_object_hash($this->aPackage)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

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
     * @param      object $copyObj An object of \keeko\core\model\Module (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setClassName($this->getClassName());
        $copyObj->setActivatedVersion($this->getActivatedVersion());
        $copyObj->setDefaultAction($this->getDefaultAction());
        $copyObj->setApi($this->getApi());
        $copyObj->setId($this->getId());
        $copyObj->setName($this->getName());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setInstalledVersion($this->getInstalledVersion());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getActions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAction($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
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
     * @return \keeko\core\model\Module Clone of current object.
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
     * Declares an association between this object and a ChildPackage object.
     *
     * @param  ChildPackage $v
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPackage(ChildPackage $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aPackage = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setModule($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPackage object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPackage The associated ChildPackage object.
     * @throws PropelException
     */
    public function getPackage(ConnectionInterface $con = null)
    {
        if ($this->aPackage === null && ($this->id !== null)) {
            $this->aPackage = ChildPackageQuery::create()->findPk($this->id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aPackage->setModule($this);
        }

        return $this->aPackage;
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
        if ('Action' == $relationName) {
            return $this->initActions();
        }
    }

    /**
     * Clears out the collActions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActions()
     */
    public function clearActions()
    {
        $this->collActions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActions collection loaded partially.
     */
    public function resetPartialActions($v = true)
    {
        $this->collActionsPartial = $v;
    }

    /**
     * Initializes the collActions collection.
     *
     * By default this just sets the collActions collection to an empty array (like clearcollActions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActions($overrideExisting = true)
    {
        if (null !== $this->collActions && !$overrideExisting) {
            return;
        }
        $this->collActions = new ObjectCollection();
        $this->collActions->setModel('\keeko\core\model\Action');
    }

    /**
     * Gets an array of ChildAction objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildModule is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAction[] List of ChildAction objects
     * @throws PropelException
     */
    public function getActions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActionsPartial && !$this->isNew();
        if (null === $this->collActions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActions) {
                // return empty collection
                $this->initActions();
            } else {
                $collActions = ChildActionQuery::create(null, $criteria)
                    ->filterByModule($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActionsPartial && count($collActions)) {
                        $this->initActions(false);

                        foreach ($collActions as $obj) {
                            if (false == $this->collActions->contains($obj)) {
                                $this->collActions->append($obj);
                            }
                        }

                        $this->collActionsPartial = true;
                    }

                    return $collActions;
                }

                if ($partial && $this->collActions) {
                    foreach ($this->collActions as $obj) {
                        if ($obj->isNew()) {
                            $collActions[] = $obj;
                        }
                    }
                }

                $this->collActions = $collActions;
                $this->collActionsPartial = false;
            }
        }

        return $this->collActions;
    }

    /**
     * Sets a collection of ChildAction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $actions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildModule The current object (for fluent API support)
     */
    public function setActions(Collection $actions, ConnectionInterface $con = null)
    {
        /** @var ChildAction[] $actionsToDelete */
        $actionsToDelete = $this->getActions(new Criteria(), $con)->diff($actions);


        $this->actionsScheduledForDeletion = $actionsToDelete;

        foreach ($actionsToDelete as $actionRemoved) {
            $actionRemoved->setModule(null);
        }

        $this->collActions = null;
        foreach ($actions as $action) {
            $this->addAction($action);
        }

        $this->collActions = $actions;
        $this->collActionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Action objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Action objects.
     * @throws PropelException
     */
    public function countActions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActionsPartial && !$this->isNew();
        if (null === $this->collActions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActions());
            }

            $query = ChildActionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByModule($this)
                ->count($con);
        }

        return count($this->collActions);
    }

    /**
     * Method called to associate a ChildAction object to this object
     * through the ChildAction foreign key attribute.
     *
     * @param  ChildAction $l ChildAction
     * @return $this|\keeko\core\model\Module The current object (for fluent API support)
     */
    public function addAction(ChildAction $l)
    {
        if ($this->collActions === null) {
            $this->initActions();
            $this->collActionsPartial = true;
        }

        if (!$this->collActions->contains($l)) {
            $this->doAddAction($l);
        }

        return $this;
    }

    /**
     * @param ChildAction $action The ChildAction object to add.
     */
    protected function doAddAction(ChildAction $action)
    {
        $this->collActions[]= $action;
        $action->setModule($this);
    }

    /**
     * @param  ChildAction $action The ChildAction object to remove.
     * @return $this|ChildModule The current object (for fluent API support)
     */
    public function removeAction(ChildAction $action)
    {
        if ($this->getActions()->contains($action)) {
            $pos = $this->collActions->search($action);
            $this->collActions->remove($pos);
            if (null === $this->actionsScheduledForDeletion) {
                $this->actionsScheduledForDeletion = clone $this->collActions;
                $this->actionsScheduledForDeletion->clear();
            }
            $this->actionsScheduledForDeletion[]= clone $action;
            $action->setModule(null);
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
        if (null !== $this->aPackage) {
            $this->aPackage->removeModule($this);
        }
        $this->class_name = null;
        $this->activated_version = null;
        $this->default_action = null;
        $this->has_api = null;
        $this->id = null;
        $this->name = null;
        $this->title = null;
        $this->description = null;
        $this->installed_version = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collActions) {
                foreach ($this->collActions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collActions = null;
        $this->aPackage = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ModuleTableMap::DEFAULT_STRING_FORMAT);
    }

    // concrete_inheritance behavior

    /**
     * Get or Create the parent ChildPackage object of the current object
     *
     * @return    ChildPackage The parent object
     */
    public function getParentOrCreate($con = null)
    {
        if ($this->isNew()) {
            if ($this->isPrimaryKeyNull()) {
                $parent = new ChildPackage();
                $parent->setDescendantClass('keeko\core\model\Module');

                return $parent;
            } else {
                $parent = \keeko\core\model\PackageQuery::create()->findPk($this->getPrimaryKey(), $con);
                if (null === $parent || null !== $parent->getDescendantClass()) {
                    $parent = new ChildPackage();
                    $parent->setPrimaryKey($this->getPrimaryKey());
                    $parent->setDescendantClass('keeko\core\model\Module');
                }

                return $parent;
            }
        } else {
            return ChildPackageQuery::create()->findPk($this->getPrimaryKey(), $con);
        }
    }

    /**
     * Create or Update the parent Package object
     * And return its primary key
     *
     * @return    int The primary key of the parent object
     */
    public function getSyncParent($con = null)
    {
        $parent = $this->getParentOrCreate($con);
        $parent->setName($this->getName());
        $parent->setTitle($this->getTitle());
        $parent->setDescription($this->getDescription());
        $parent->setInstalledVersion($this->getInstalledVersion());

        return $parent;
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
