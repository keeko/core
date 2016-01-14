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
use keeko\core\model\ApplicationUri as ChildApplicationUri;
use keeko\core\model\ApplicationUriQuery as ChildApplicationUriQuery;
use keeko\core\model\Country as ChildCountry;
use keeko\core\model\CountryQuery as ChildCountryQuery;
use keeko\core\model\Language as ChildLanguage;
use keeko\core\model\LanguageQuery as ChildLanguageQuery;
use keeko\core\model\Localization as ChildLocalization;
use keeko\core\model\LocalizationQuery as ChildLocalizationQuery;
use keeko\core\model\Map\LocalizationTableMap;

/**
 * Base class that represents a row from the 'kk_localization' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Localization implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\LocalizationTableMap';


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
     * The value for the parent_id field.
     * @var        int
     */
    protected $parent_id;

    /**
     * The value for the language_id field.
     * @var        int
     */
    protected $language_id;

    /**
     * The value for the country_iso_nr field.
     * @var        int
     */
    protected $country_iso_nr;

    /**
     * The value for the is_default field.
     * @var        boolean
     */
    protected $is_default;

    /**
     * @var        ChildLocalization
     */
    protected $aLocalizationRelatedByParentId;

    /**
     * @var        ChildLanguage
     */
    protected $aLanguage;

    /**
     * @var        ChildCountry
     */
    protected $aCountry;

    /**
     * @var        ObjectCollection|ChildLocalization[] Collection to store aggregation of ChildLocalization objects.
     */
    protected $collLocalizationsRelatedById;
    protected $collLocalizationsRelatedByIdPartial;

    /**
     * @var        ObjectCollection|ChildApplicationUri[] Collection to store aggregation of ChildApplicationUri objects.
     */
    protected $collApplicationUris;
    protected $collApplicationUrisPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocalization[]
     */
    protected $localizationsRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildApplicationUri[]
     */
    protected $applicationUrisScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\Localization object.
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
     * Compares this with another <code>Localization</code> instance.  If
     * <code>obj</code> is an instance of <code>Localization</code>, delegates to
     * <code>equals(Localization)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Localization The current object, for fluid interface
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
     * Get the [parent_id] column value.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Get the [language_id] column value.
     *
     * @return int
     */
    public function getLanguageId()
    {
        return $this->language_id;
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
     * Get the [is_default] column value.
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * Get the [is_default] column value.
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->getIsDefault();
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [parent_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setParentId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->parent_id !== $v) {
            $this->parent_id = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_PARENT_ID] = true;
        }

        if ($this->aLocalizationRelatedByParentId !== null && $this->aLocalizationRelatedByParentId->getId() !== $v) {
            $this->aLocalizationRelatedByParentId = null;
        }

        return $this;
    } // setParentId()

    /**
     * Set the value of [language_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setLanguageId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->language_id !== $v) {
            $this->language_id = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_LANGUAGE_ID] = true;
        }

        if ($this->aLanguage !== null && $this->aLanguage->getId() !== $v) {
            $this->aLanguage = null;
        }

        return $this;
    } // setLanguageId()

    /**
     * Set the value of [country_iso_nr] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setCountryIsoNr($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->country_iso_nr !== $v) {
            $this->country_iso_nr = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_COUNTRY_ISO_NR] = true;
        }

        if ($this->aCountry !== null && $this->aCountry->getIsoNr() !== $v) {
            $this->aCountry = null;
        }

        return $this;
    } // setCountryIsoNr()

    /**
     * Sets the value of the [is_default] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setIsDefault($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_default !== $v) {
            $this->is_default = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_IS_DEFAULT] = true;
        }

        return $this;
    } // setIsDefault()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LocalizationTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LocalizationTableMap::translateFieldName('ParentId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->parent_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LocalizationTableMap::translateFieldName('LanguageId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->language_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LocalizationTableMap::translateFieldName('CountryIsoNr', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_iso_nr = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LocalizationTableMap::translateFieldName('IsDefault', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_default = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = LocalizationTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\Localization'), 0, $e);
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
        if ($this->aLocalizationRelatedByParentId !== null && $this->parent_id !== $this->aLocalizationRelatedByParentId->getId()) {
            $this->aLocalizationRelatedByParentId = null;
        }
        if ($this->aLanguage !== null && $this->language_id !== $this->aLanguage->getId()) {
            $this->aLanguage = null;
        }
        if ($this->aCountry !== null && $this->country_iso_nr !== $this->aCountry->getIsoNr()) {
            $this->aCountry = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(LocalizationTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLocalizationQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aLocalizationRelatedByParentId = null;
            $this->aLanguage = null;
            $this->aCountry = null;
            $this->collLocalizationsRelatedById = null;

            $this->collApplicationUris = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Localization::setDeleted()
     * @see Localization::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocalizationTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLocalizationQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(LocalizationTableMap::DATABASE_NAME);
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
                LocalizationTableMap::addInstanceToPool($this);
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

            if ($this->aLocalizationRelatedByParentId !== null) {
                if ($this->aLocalizationRelatedByParentId->isModified() || $this->aLocalizationRelatedByParentId->isNew()) {
                    $affectedRows += $this->aLocalizationRelatedByParentId->save($con);
                }
                $this->setLocalizationRelatedByParentId($this->aLocalizationRelatedByParentId);
            }

            if ($this->aLanguage !== null) {
                if ($this->aLanguage->isModified() || $this->aLanguage->isNew()) {
                    $affectedRows += $this->aLanguage->save($con);
                }
                $this->setLanguage($this->aLanguage);
            }

            if ($this->aCountry !== null) {
                if ($this->aCountry->isModified() || $this->aCountry->isNew()) {
                    $affectedRows += $this->aCountry->save($con);
                }
                $this->setCountry($this->aCountry);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->localizationsRelatedByIdScheduledForDeletion !== null) {
                if (!$this->localizationsRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->localizationsRelatedByIdScheduledForDeletion as $localizationRelatedById) {
                        // need to save related object because we set the relation to null
                        $localizationRelatedById->save($con);
                    }
                    $this->localizationsRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collLocalizationsRelatedById !== null) {
                foreach ($this->collLocalizationsRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->applicationUrisScheduledForDeletion !== null) {
                if (!$this->applicationUrisScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\ApplicationUriQuery::create()
                        ->filterByPrimaryKeys($this->applicationUrisScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->applicationUrisScheduledForDeletion = null;
                }
            }

            if ($this->collApplicationUris !== null) {
                foreach ($this->collApplicationUris as $referrerFK) {
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

        $this->modifiedColumns[LocalizationTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LocalizationTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LocalizationTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_PARENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`parent_id`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_LANGUAGE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`language_id`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_COUNTRY_ISO_NR)) {
            $modifiedColumns[':p' . $index++]  = '`country_iso_nr`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_IS_DEFAULT)) {
            $modifiedColumns[':p' . $index++]  = '`is_default`';
        }

        $sql = sprintf(
            'INSERT INTO `kk_localization` (%s) VALUES (%s)',
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
                    case '`parent_id`':
                        $stmt->bindValue($identifier, $this->parent_id, PDO::PARAM_INT);
                        break;
                    case '`language_id`':
                        $stmt->bindValue($identifier, $this->language_id, PDO::PARAM_INT);
                        break;
                    case '`country_iso_nr`':
                        $stmt->bindValue($identifier, $this->country_iso_nr, PDO::PARAM_INT);
                        break;
                    case '`is_default`':
                        $stmt->bindValue($identifier, (int) $this->is_default, PDO::PARAM_INT);
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
        $pos = LocalizationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getParentId();
                break;
            case 2:
                return $this->getLanguageId();
                break;
            case 3:
                return $this->getCountryIsoNr();
                break;
            case 4:
                return $this->getIsDefault();
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

        if (isset($alreadyDumpedObjects['Localization'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Localization'][$this->hashCode()] = true;
        $keys = LocalizationTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getParentId(),
            $keys[2] => $this->getLanguageId(),
            $keys[3] => $this->getCountryIsoNr(),
            $keys[4] => $this->getIsDefault(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aLocalizationRelatedByParentId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'localization';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_localization';
                        break;
                    default:
                        $key = 'Localization';
                }

                $result[$key] = $this->aLocalizationRelatedByParentId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLanguage) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'language';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_language';
                        break;
                    default:
                        $key = 'Language';
                }

                $result[$key] = $this->aLanguage->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
            if (null !== $this->collLocalizationsRelatedById) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'localizations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_localizations';
                        break;
                    default:
                        $key = 'Localizations';
                }

                $result[$key] = $this->collLocalizationsRelatedById->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collApplicationUris) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'applicationUris';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_application_uris';
                        break;
                    default:
                        $key = 'ApplicationUris';
                }

                $result[$key] = $this->collApplicationUris->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\keeko\core\model\Localization
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LocalizationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\keeko\core\model\Localization
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setParentId($value);
                break;
            case 2:
                $this->setLanguageId($value);
                break;
            case 3:
                $this->setCountryIsoNr($value);
                break;
            case 4:
                $this->setIsDefault($value);
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
        $keys = LocalizationTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setParentId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setLanguageId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCountryIsoNr($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setIsDefault($arr[$keys[4]]);
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
     * @return $this|\keeko\core\model\Localization The current object, for fluid interface
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
        $criteria = new Criteria(LocalizationTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LocalizationTableMap::COL_ID)) {
            $criteria->add(LocalizationTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_PARENT_ID)) {
            $criteria->add(LocalizationTableMap::COL_PARENT_ID, $this->parent_id);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_LANGUAGE_ID)) {
            $criteria->add(LocalizationTableMap::COL_LANGUAGE_ID, $this->language_id);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_COUNTRY_ISO_NR)) {
            $criteria->add(LocalizationTableMap::COL_COUNTRY_ISO_NR, $this->country_iso_nr);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_IS_DEFAULT)) {
            $criteria->add(LocalizationTableMap::COL_IS_DEFAULT, $this->is_default);
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
        $criteria = ChildLocalizationQuery::create();
        $criteria->add(LocalizationTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\Localization (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setParentId($this->getParentId());
        $copyObj->setLanguageId($this->getLanguageId());
        $copyObj->setCountryIsoNr($this->getCountryIsoNr());
        $copyObj->setIsDefault($this->getIsDefault());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getLocalizationsRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLocalizationRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getApplicationUris() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addApplicationUri($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\Localization Clone of current object.
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
     * Declares an association between this object and a ChildLocalization object.
     *
     * @param  ChildLocalization $v
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLocalizationRelatedByParentId(ChildLocalization $v = null)
    {
        if ($v === null) {
            $this->setParentId(NULL);
        } else {
            $this->setParentId($v->getId());
        }

        $this->aLocalizationRelatedByParentId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLocalization object, it will not be re-added.
        if ($v !== null) {
            $v->addLocalizationRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLocalization object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLocalization The associated ChildLocalization object.
     * @throws PropelException
     */
    public function getLocalizationRelatedByParentId(ConnectionInterface $con = null)
    {
        if ($this->aLocalizationRelatedByParentId === null && ($this->parent_id !== null)) {
            $this->aLocalizationRelatedByParentId = ChildLocalizationQuery::create()->findPk($this->parent_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLocalizationRelatedByParentId->addLocalizationsRelatedById($this);
             */
        }

        return $this->aLocalizationRelatedByParentId;
    }

    /**
     * Declares an association between this object and a ChildLanguage object.
     *
     * @param  ChildLanguage $v
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLanguage(ChildLanguage $v = null)
    {
        if ($v === null) {
            $this->setLanguageId(NULL);
        } else {
            $this->setLanguageId($v->getId());
        }

        $this->aLanguage = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLanguage object, it will not be re-added.
        if ($v !== null) {
            $v->addLocalization($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLanguage object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLanguage The associated ChildLanguage object.
     * @throws PropelException
     */
    public function getLanguage(ConnectionInterface $con = null)
    {
        if ($this->aLanguage === null && ($this->language_id !== null)) {
            $this->aLanguage = ChildLanguageQuery::create()->findPk($this->language_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLanguage->addLocalizations($this);
             */
        }

        return $this->aLanguage;
    }

    /**
     * Declares an association between this object and a ChildCountry object.
     *
     * @param  ChildCountry $v
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
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
            $v->addLocalization($this);
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
                $this->aCountry->addLocalizations($this);
             */
        }

        return $this->aCountry;
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
        if ('LocalizationRelatedById' == $relationName) {
            return $this->initLocalizationsRelatedById();
        }
        if ('ApplicationUri' == $relationName) {
            return $this->initApplicationUris();
        }
    }

    /**
     * Clears out the collLocalizationsRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLocalizationsRelatedById()
     */
    public function clearLocalizationsRelatedById()
    {
        $this->collLocalizationsRelatedById = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLocalizationsRelatedById collection loaded partially.
     */
    public function resetPartialLocalizationsRelatedById($v = true)
    {
        $this->collLocalizationsRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collLocalizationsRelatedById collection.
     *
     * By default this just sets the collLocalizationsRelatedById collection to an empty array (like clearcollLocalizationsRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLocalizationsRelatedById($overrideExisting = true)
    {
        if (null !== $this->collLocalizationsRelatedById && !$overrideExisting) {
            return;
        }
        $this->collLocalizationsRelatedById = new ObjectCollection();
        $this->collLocalizationsRelatedById->setModel('\keeko\core\model\Localization');
    }

    /**
     * Gets an array of ChildLocalization objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocalization is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     * @throws PropelException
     */
    public function getLocalizationsRelatedById(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsRelatedByIdPartial && !$this->isNew();
        if (null === $this->collLocalizationsRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLocalizationsRelatedById) {
                // return empty collection
                $this->initLocalizationsRelatedById();
            } else {
                $collLocalizationsRelatedById = ChildLocalizationQuery::create(null, $criteria)
                    ->filterByLocalizationRelatedByParentId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLocalizationsRelatedByIdPartial && count($collLocalizationsRelatedById)) {
                        $this->initLocalizationsRelatedById(false);

                        foreach ($collLocalizationsRelatedById as $obj) {
                            if (false == $this->collLocalizationsRelatedById->contains($obj)) {
                                $this->collLocalizationsRelatedById->append($obj);
                            }
                        }

                        $this->collLocalizationsRelatedByIdPartial = true;
                    }

                    return $collLocalizationsRelatedById;
                }

                if ($partial && $this->collLocalizationsRelatedById) {
                    foreach ($this->collLocalizationsRelatedById as $obj) {
                        if ($obj->isNew()) {
                            $collLocalizationsRelatedById[] = $obj;
                        }
                    }
                }

                $this->collLocalizationsRelatedById = $collLocalizationsRelatedById;
                $this->collLocalizationsRelatedByIdPartial = false;
            }
        }

        return $this->collLocalizationsRelatedById;
    }

    /**
     * Sets a collection of ChildLocalization objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $localizationsRelatedById A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLocalization The current object (for fluent API support)
     */
    public function setLocalizationsRelatedById(Collection $localizationsRelatedById, ConnectionInterface $con = null)
    {
        /** @var ChildLocalization[] $localizationsRelatedByIdToDelete */
        $localizationsRelatedByIdToDelete = $this->getLocalizationsRelatedById(new Criteria(), $con)->diff($localizationsRelatedById);


        $this->localizationsRelatedByIdScheduledForDeletion = $localizationsRelatedByIdToDelete;

        foreach ($localizationsRelatedByIdToDelete as $localizationRelatedByIdRemoved) {
            $localizationRelatedByIdRemoved->setLocalizationRelatedByParentId(null);
        }

        $this->collLocalizationsRelatedById = null;
        foreach ($localizationsRelatedById as $localizationRelatedById) {
            $this->addLocalizationRelatedById($localizationRelatedById);
        }

        $this->collLocalizationsRelatedById = $localizationsRelatedById;
        $this->collLocalizationsRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Localization objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Localization objects.
     * @throws PropelException
     */
    public function countLocalizationsRelatedById(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsRelatedByIdPartial && !$this->isNew();
        if (null === $this->collLocalizationsRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLocalizationsRelatedById) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLocalizationsRelatedById());
            }

            $query = ChildLocalizationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocalizationRelatedByParentId($this)
                ->count($con);
        }

        return count($this->collLocalizationsRelatedById);
    }

    /**
     * Method called to associate a ChildLocalization object to this object
     * through the ChildLocalization foreign key attribute.
     *
     * @param  ChildLocalization $l ChildLocalization
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function addLocalizationRelatedById(ChildLocalization $l)
    {
        if ($this->collLocalizationsRelatedById === null) {
            $this->initLocalizationsRelatedById();
            $this->collLocalizationsRelatedByIdPartial = true;
        }

        if (!$this->collLocalizationsRelatedById->contains($l)) {
            $this->doAddLocalizationRelatedById($l);
        }

        return $this;
    }

    /**
     * @param ChildLocalization $localizationRelatedById The ChildLocalization object to add.
     */
    protected function doAddLocalizationRelatedById(ChildLocalization $localizationRelatedById)
    {
        $this->collLocalizationsRelatedById[]= $localizationRelatedById;
        $localizationRelatedById->setLocalizationRelatedByParentId($this);
    }

    /**
     * @param  ChildLocalization $localizationRelatedById The ChildLocalization object to remove.
     * @return $this|ChildLocalization The current object (for fluent API support)
     */
    public function removeLocalizationRelatedById(ChildLocalization $localizationRelatedById)
    {
        if ($this->getLocalizationsRelatedById()->contains($localizationRelatedById)) {
            $pos = $this->collLocalizationsRelatedById->search($localizationRelatedById);
            $this->collLocalizationsRelatedById->remove($pos);
            if (null === $this->localizationsRelatedByIdScheduledForDeletion) {
                $this->localizationsRelatedByIdScheduledForDeletion = clone $this->collLocalizationsRelatedById;
                $this->localizationsRelatedByIdScheduledForDeletion->clear();
            }
            $this->localizationsRelatedByIdScheduledForDeletion[]= $localizationRelatedById;
            $localizationRelatedById->setLocalizationRelatedByParentId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Localization is new, it will return
     * an empty collection; or if this Localization has previously
     * been saved, it will retrieve related LocalizationsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Localization.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsRelatedByIdJoinLanguage(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('Language', $joinBehavior);

        return $this->getLocalizationsRelatedById($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Localization is new, it will return
     * an empty collection; or if this Localization has previously
     * been saved, it will retrieve related LocalizationsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Localization.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsRelatedByIdJoinCountry(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('Country', $joinBehavior);

        return $this->getLocalizationsRelatedById($query, $con);
    }

    /**
     * Clears out the collApplicationUris collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addApplicationUris()
     */
    public function clearApplicationUris()
    {
        $this->collApplicationUris = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collApplicationUris collection loaded partially.
     */
    public function resetPartialApplicationUris($v = true)
    {
        $this->collApplicationUrisPartial = $v;
    }

    /**
     * Initializes the collApplicationUris collection.
     *
     * By default this just sets the collApplicationUris collection to an empty array (like clearcollApplicationUris());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initApplicationUris($overrideExisting = true)
    {
        if (null !== $this->collApplicationUris && !$overrideExisting) {
            return;
        }
        $this->collApplicationUris = new ObjectCollection();
        $this->collApplicationUris->setModel('\keeko\core\model\ApplicationUri');
    }

    /**
     * Gets an array of ChildApplicationUri objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocalization is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildApplicationUri[] List of ChildApplicationUri objects
     * @throws PropelException
     */
    public function getApplicationUris(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collApplicationUrisPartial && !$this->isNew();
        if (null === $this->collApplicationUris || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collApplicationUris) {
                // return empty collection
                $this->initApplicationUris();
            } else {
                $collApplicationUris = ChildApplicationUriQuery::create(null, $criteria)
                    ->filterByLocalization($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collApplicationUrisPartial && count($collApplicationUris)) {
                        $this->initApplicationUris(false);

                        foreach ($collApplicationUris as $obj) {
                            if (false == $this->collApplicationUris->contains($obj)) {
                                $this->collApplicationUris->append($obj);
                            }
                        }

                        $this->collApplicationUrisPartial = true;
                    }

                    return $collApplicationUris;
                }

                if ($partial && $this->collApplicationUris) {
                    foreach ($this->collApplicationUris as $obj) {
                        if ($obj->isNew()) {
                            $collApplicationUris[] = $obj;
                        }
                    }
                }

                $this->collApplicationUris = $collApplicationUris;
                $this->collApplicationUrisPartial = false;
            }
        }

        return $this->collApplicationUris;
    }

    /**
     * Sets a collection of ChildApplicationUri objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $applicationUris A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLocalization The current object (for fluent API support)
     */
    public function setApplicationUris(Collection $applicationUris, ConnectionInterface $con = null)
    {
        /** @var ChildApplicationUri[] $applicationUrisToDelete */
        $applicationUrisToDelete = $this->getApplicationUris(new Criteria(), $con)->diff($applicationUris);


        $this->applicationUrisScheduledForDeletion = $applicationUrisToDelete;

        foreach ($applicationUrisToDelete as $applicationUriRemoved) {
            $applicationUriRemoved->setLocalization(null);
        }

        $this->collApplicationUris = null;
        foreach ($applicationUris as $applicationUri) {
            $this->addApplicationUri($applicationUri);
        }

        $this->collApplicationUris = $applicationUris;
        $this->collApplicationUrisPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ApplicationUri objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ApplicationUri objects.
     * @throws PropelException
     */
    public function countApplicationUris(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collApplicationUrisPartial && !$this->isNew();
        if (null === $this->collApplicationUris || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collApplicationUris) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getApplicationUris());
            }

            $query = ChildApplicationUriQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocalization($this)
                ->count($con);
        }

        return count($this->collApplicationUris);
    }

    /**
     * Method called to associate a ChildApplicationUri object to this object
     * through the ChildApplicationUri foreign key attribute.
     *
     * @param  ChildApplicationUri $l ChildApplicationUri
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function addApplicationUri(ChildApplicationUri $l)
    {
        if ($this->collApplicationUris === null) {
            $this->initApplicationUris();
            $this->collApplicationUrisPartial = true;
        }

        if (!$this->collApplicationUris->contains($l)) {
            $this->doAddApplicationUri($l);
        }

        return $this;
    }

    /**
     * @param ChildApplicationUri $applicationUri The ChildApplicationUri object to add.
     */
    protected function doAddApplicationUri(ChildApplicationUri $applicationUri)
    {
        $this->collApplicationUris[]= $applicationUri;
        $applicationUri->setLocalization($this);
    }

    /**
     * @param  ChildApplicationUri $applicationUri The ChildApplicationUri object to remove.
     * @return $this|ChildLocalization The current object (for fluent API support)
     */
    public function removeApplicationUri(ChildApplicationUri $applicationUri)
    {
        if ($this->getApplicationUris()->contains($applicationUri)) {
            $pos = $this->collApplicationUris->search($applicationUri);
            $this->collApplicationUris->remove($pos);
            if (null === $this->applicationUrisScheduledForDeletion) {
                $this->applicationUrisScheduledForDeletion = clone $this->collApplicationUris;
                $this->applicationUrisScheduledForDeletion->clear();
            }
            $this->applicationUrisScheduledForDeletion[]= clone $applicationUri;
            $applicationUri->setLocalization(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Localization is new, it will return
     * an empty collection; or if this Localization has previously
     * been saved, it will retrieve related ApplicationUris from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Localization.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildApplicationUri[] List of ChildApplicationUri objects
     */
    public function getApplicationUrisJoinApplication(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildApplicationUriQuery::create(null, $criteria);
        $query->joinWith('Application', $joinBehavior);

        return $this->getApplicationUris($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aLocalizationRelatedByParentId) {
            $this->aLocalizationRelatedByParentId->removeLocalizationRelatedById($this);
        }
        if (null !== $this->aLanguage) {
            $this->aLanguage->removeLocalization($this);
        }
        if (null !== $this->aCountry) {
            $this->aCountry->removeLocalization($this);
        }
        $this->id = null;
        $this->parent_id = null;
        $this->language_id = null;
        $this->country_iso_nr = null;
        $this->is_default = null;
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
            if ($this->collLocalizationsRelatedById) {
                foreach ($this->collLocalizationsRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collApplicationUris) {
                foreach ($this->collApplicationUris as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLocalizationsRelatedById = null;
        $this->collApplicationUris = null;
        $this->aLocalizationRelatedByParentId = null;
        $this->aLanguage = null;
        $this->aCountry = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LocalizationTableMap::DEFAULT_STRING_FORMAT);
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
