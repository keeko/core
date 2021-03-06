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
use keeko\core\model\Language as ChildLanguage;
use keeko\core\model\LanguageQuery as ChildLanguageQuery;
use keeko\core\model\LanguageScript as ChildLanguageScript;
use keeko\core\model\LanguageScriptQuery as ChildLanguageScriptQuery;
use keeko\core\model\LanguageVariant as ChildLanguageVariant;
use keeko\core\model\LanguageVariantQuery as ChildLanguageVariantQuery;
use keeko\core\model\Localization as ChildLocalization;
use keeko\core\model\LocalizationQuery as ChildLocalizationQuery;
use keeko\core\model\LocalizationVariant as ChildLocalizationVariant;
use keeko\core\model\LocalizationVariantQuery as ChildLocalizationVariantQuery;
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
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the locale field.
     * @var        string
     */
    protected $locale;

    /**
     * The value for the language_id field.
     * @var        int
     */
    protected $language_id;

    /**
     * The value for the ext_language_id field.
     * @var        int
     */
    protected $ext_language_id;

    /**
     * The value for the region field.
     * @var        string
     */
    protected $region;

    /**
     * The value for the script_id field.
     * @var        int
     */
    protected $script_id;

    /**
     * The value for the is_default field.
     * @var        boolean
     */
    protected $is_default;

    /**
     * @var        ChildLocalization
     */
    protected $aParent;

    /**
     * @var        ChildLanguage
     */
    protected $aLanguage;

    /**
     * @var        ChildLanguage
     */
    protected $aExtLang;

    /**
     * @var        ChildLanguageScript
     */
    protected $aScript;

    /**
     * @var        ObjectCollection|ChildLocalization[] Collection to store aggregation of ChildLocalization objects.
     */
    protected $collLocalizationsRelatedById;
    protected $collLocalizationsRelatedByIdPartial;

    /**
     * @var        ObjectCollection|ChildLocalizationVariant[] Collection to store aggregation of ChildLocalizationVariant objects.
     */
    protected $collLocalizationVariants;
    protected $collLocalizationVariantsPartial;

    /**
     * @var        ObjectCollection|ChildApplicationUri[] Collection to store aggregation of ChildApplicationUri objects.
     */
    protected $collApplicationUris;
    protected $collApplicationUrisPartial;

    /**
     * @var        ObjectCollection|ChildLanguageVariant[] Cross Collection to store aggregation of ChildLanguageVariant objects.
     */
    protected $collLanguageVariants;

    /**
     * @var bool
     */
    protected $collLanguageVariantsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLanguageVariant[]
     */
    protected $languageVariantsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocalization[]
     */
    protected $localizationsRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocalizationVariant[]
     */
    protected $localizationVariantsScheduledForDeletion = null;

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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [locale] column value.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
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
     * Get the [ext_language_id] column value.
     *
     * @return int
     */
    public function getExtLanguageId()
    {
        return $this->ext_language_id;
    }

    /**
     * Get the [region] column value.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Get the [script_id] column value.
     *
     * @return int
     */
    public function getScriptId()
    {
        return $this->script_id;
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

        if ($this->aParent !== null && $this->aParent->getId() !== $v) {
            $this->aParent = null;
        }

        return $this;
    } // setParentId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [locale] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setLocale($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->locale !== $v) {
            $this->locale = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_LOCALE] = true;
        }

        return $this;
    } // setLocale()

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
     * Set the value of [ext_language_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setExtLanguageId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->ext_language_id !== $v) {
            $this->ext_language_id = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_EXT_LANGUAGE_ID] = true;
        }

        if ($this->aExtLang !== null && $this->aExtLang->getId() !== $v) {
            $this->aExtLang = null;
        }

        return $this;
    } // setExtLanguageId()

    /**
     * Set the value of [region] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setRegion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->region !== $v) {
            $this->region = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_REGION] = true;
        }

        return $this;
    } // setRegion()

    /**
     * Set the value of [script_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function setScriptId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->script_id !== $v) {
            $this->script_id = $v;
            $this->modifiedColumns[LocalizationTableMap::COL_SCRIPT_ID] = true;
        }

        if ($this->aScript !== null && $this->aScript->getId() !== $v) {
            $this->aScript = null;
        }

        return $this;
    } // setScriptId()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LocalizationTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LocalizationTableMap::translateFieldName('Locale', TableMap::TYPE_PHPNAME, $indexType)];
            $this->locale = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LocalizationTableMap::translateFieldName('LanguageId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->language_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LocalizationTableMap::translateFieldName('ExtLanguageId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ext_language_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : LocalizationTableMap::translateFieldName('Region', TableMap::TYPE_PHPNAME, $indexType)];
            $this->region = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : LocalizationTableMap::translateFieldName('ScriptId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->script_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : LocalizationTableMap::translateFieldName('IsDefault', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_default = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = LocalizationTableMap::NUM_HYDRATE_COLUMNS.

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
        if ($this->aParent !== null && $this->parent_id !== $this->aParent->getId()) {
            $this->aParent = null;
        }
        if ($this->aLanguage !== null && $this->language_id !== $this->aLanguage->getId()) {
            $this->aLanguage = null;
        }
        if ($this->aExtLang !== null && $this->ext_language_id !== $this->aExtLang->getId()) {
            $this->aExtLang = null;
        }
        if ($this->aScript !== null && $this->script_id !== $this->aScript->getId()) {
            $this->aScript = null;
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

            $this->aParent = null;
            $this->aLanguage = null;
            $this->aExtLang = null;
            $this->aScript = null;
            $this->collLocalizationsRelatedById = null;

            $this->collLocalizationVariants = null;

            $this->collApplicationUris = null;

            $this->collLanguageVariants = null;
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

            if ($this->aParent !== null) {
                if ($this->aParent->isModified() || $this->aParent->isNew()) {
                    $affectedRows += $this->aParent->save($con);
                }
                $this->setParent($this->aParent);
            }

            if ($this->aLanguage !== null) {
                if ($this->aLanguage->isModified() || $this->aLanguage->isNew()) {
                    $affectedRows += $this->aLanguage->save($con);
                }
                $this->setLanguage($this->aLanguage);
            }

            if ($this->aExtLang !== null) {
                if ($this->aExtLang->isModified() || $this->aExtLang->isNew()) {
                    $affectedRows += $this->aExtLang->save($con);
                }
                $this->setExtLang($this->aExtLang);
            }

            if ($this->aScript !== null) {
                if ($this->aScript->isModified() || $this->aScript->isNew()) {
                    $affectedRows += $this->aScript->save($con);
                }
                $this->setScript($this->aScript);
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

            if ($this->languageVariantsScheduledForDeletion !== null) {
                if (!$this->languageVariantsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->languageVariantsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \keeko\core\model\LocalizationVariantQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->languageVariantsScheduledForDeletion = null;
                }

            }

            if ($this->collLanguageVariants) {
                foreach ($this->collLanguageVariants as $languageVariant) {
                    if (!$languageVariant->isDeleted() && ($languageVariant->isNew() || $languageVariant->isModified())) {
                        $languageVariant->save($con);
                    }
                }
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

            if ($this->localizationVariantsScheduledForDeletion !== null) {
                if (!$this->localizationVariantsScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\LocalizationVariantQuery::create()
                        ->filterByPrimaryKeys($this->localizationVariantsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->localizationVariantsScheduledForDeletion = null;
                }
            }

            if ($this->collLocalizationVariants !== null) {
                foreach ($this->collLocalizationVariants as $referrerFK) {
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
        if ($this->isColumnModified(LocalizationTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_LOCALE)) {
            $modifiedColumns[':p' . $index++]  = '`locale`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_LANGUAGE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`language_id`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_EXT_LANGUAGE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`ext_language_id`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_REGION)) {
            $modifiedColumns[':p' . $index++]  = '`region`';
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_SCRIPT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`script_id`';
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
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`locale`':
                        $stmt->bindValue($identifier, $this->locale, PDO::PARAM_STR);
                        break;
                    case '`language_id`':
                        $stmt->bindValue($identifier, $this->language_id, PDO::PARAM_INT);
                        break;
                    case '`ext_language_id`':
                        $stmt->bindValue($identifier, $this->ext_language_id, PDO::PARAM_INT);
                        break;
                    case '`region`':
                        $stmt->bindValue($identifier, $this->region, PDO::PARAM_STR);
                        break;
                    case '`script_id`':
                        $stmt->bindValue($identifier, $this->script_id, PDO::PARAM_INT);
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
                return $this->getName();
                break;
            case 3:
                return $this->getLocale();
                break;
            case 4:
                return $this->getLanguageId();
                break;
            case 5:
                return $this->getExtLanguageId();
                break;
            case 6:
                return $this->getRegion();
                break;
            case 7:
                return $this->getScriptId();
                break;
            case 8:
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
            $keys[2] => $this->getName(),
            $keys[3] => $this->getLocale(),
            $keys[4] => $this->getLanguageId(),
            $keys[5] => $this->getExtLanguageId(),
            $keys[6] => $this->getRegion(),
            $keys[7] => $this->getScriptId(),
            $keys[8] => $this->getIsDefault(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aParent) {

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

                $result[$key] = $this->aParent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->aExtLang) {

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

                $result[$key] = $this->aExtLang->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aScript) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'languageScript';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_language_script';
                        break;
                    default:
                        $key = 'LanguageScript';
                }

                $result[$key] = $this->aScript->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collLocalizationVariants) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'localizationVariants';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_localization_variants';
                        break;
                    default:
                        $key = 'LocalizationVariants';
                }

                $result[$key] = $this->collLocalizationVariants->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setName($value);
                break;
            case 3:
                $this->setLocale($value);
                break;
            case 4:
                $this->setLanguageId($value);
                break;
            case 5:
                $this->setExtLanguageId($value);
                break;
            case 6:
                $this->setRegion($value);
                break;
            case 7:
                $this->setScriptId($value);
                break;
            case 8:
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
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setLocale($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLanguageId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setExtLanguageId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setRegion($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setScriptId($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIsDefault($arr[$keys[8]]);
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
        if ($this->isColumnModified(LocalizationTableMap::COL_NAME)) {
            $criteria->add(LocalizationTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_LOCALE)) {
            $criteria->add(LocalizationTableMap::COL_LOCALE, $this->locale);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_LANGUAGE_ID)) {
            $criteria->add(LocalizationTableMap::COL_LANGUAGE_ID, $this->language_id);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_EXT_LANGUAGE_ID)) {
            $criteria->add(LocalizationTableMap::COL_EXT_LANGUAGE_ID, $this->ext_language_id);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_REGION)) {
            $criteria->add(LocalizationTableMap::COL_REGION, $this->region);
        }
        if ($this->isColumnModified(LocalizationTableMap::COL_SCRIPT_ID)) {
            $criteria->add(LocalizationTableMap::COL_SCRIPT_ID, $this->script_id);
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
        $copyObj->setName($this->getName());
        $copyObj->setLocale($this->getLocale());
        $copyObj->setLanguageId($this->getLanguageId());
        $copyObj->setExtLanguageId($this->getExtLanguageId());
        $copyObj->setRegion($this->getRegion());
        $copyObj->setScriptId($this->getScriptId());
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

            foreach ($this->getLocalizationVariants() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLocalizationVariant($relObj->copy($deepCopy));
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
    public function setParent(ChildLocalization $v = null)
    {
        if ($v === null) {
            $this->setParentId(NULL);
        } else {
            $this->setParentId($v->getId());
        }

        $this->aParent = $v;

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
    public function getParent(ConnectionInterface $con = null)
    {
        if ($this->aParent === null && ($this->parent_id !== null)) {
            $this->aParent = ChildLocalizationQuery::create()->findPk($this->parent_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aParent->addLocalizationsRelatedById($this);
             */
        }

        return $this->aParent;
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
            $v->addLocalizationRelatedByLanguageId($this);
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
                $this->aLanguage->addLocalizationsRelatedByLanguageId($this);
             */
        }

        return $this->aLanguage;
    }

    /**
     * Declares an association between this object and a ChildLanguage object.
     *
     * @param  ChildLanguage $v
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     * @throws PropelException
     */
    public function setExtLang(ChildLanguage $v = null)
    {
        if ($v === null) {
            $this->setExtLanguageId(NULL);
        } else {
            $this->setExtLanguageId($v->getId());
        }

        $this->aExtLang = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLanguage object, it will not be re-added.
        if ($v !== null) {
            $v->addLocalizationRelatedByExtLanguageId($this);
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
    public function getExtLang(ConnectionInterface $con = null)
    {
        if ($this->aExtLang === null && ($this->ext_language_id !== null)) {
            $this->aExtLang = ChildLanguageQuery::create()->findPk($this->ext_language_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aExtLang->addLocalizationsRelatedByExtLanguageId($this);
             */
        }

        return $this->aExtLang;
    }

    /**
     * Declares an association between this object and a ChildLanguageScript object.
     *
     * @param  ChildLanguageScript $v
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     * @throws PropelException
     */
    public function setScript(ChildLanguageScript $v = null)
    {
        if ($v === null) {
            $this->setScriptId(NULL);
        } else {
            $this->setScriptId($v->getId());
        }

        $this->aScript = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLanguageScript object, it will not be re-added.
        if ($v !== null) {
            $v->addLocalization($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLanguageScript object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLanguageScript The associated ChildLanguageScript object.
     * @throws PropelException
     */
    public function getScript(ConnectionInterface $con = null)
    {
        if ($this->aScript === null && ($this->script_id !== null)) {
            $this->aScript = ChildLanguageScriptQuery::create()->findPk($this->script_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aScript->addLocalizations($this);
             */
        }

        return $this->aScript;
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
        if ('LocalizationVariant' == $relationName) {
            return $this->initLocalizationVariants();
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
                    ->filterByParent($this)
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
            $localizationRelatedByIdRemoved->setParent(null);
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
                ->filterByParent($this)
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
        $localizationRelatedById->setParent($this);
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
            $localizationRelatedById->setParent(null);
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
    public function getLocalizationsRelatedByIdJoinExtLang(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('ExtLang', $joinBehavior);

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
    public function getLocalizationsRelatedByIdJoinScript(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('Script', $joinBehavior);

        return $this->getLocalizationsRelatedById($query, $con);
    }

    /**
     * Clears out the collLocalizationVariants collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLocalizationVariants()
     */
    public function clearLocalizationVariants()
    {
        $this->collLocalizationVariants = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLocalizationVariants collection loaded partially.
     */
    public function resetPartialLocalizationVariants($v = true)
    {
        $this->collLocalizationVariantsPartial = $v;
    }

    /**
     * Initializes the collLocalizationVariants collection.
     *
     * By default this just sets the collLocalizationVariants collection to an empty array (like clearcollLocalizationVariants());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLocalizationVariants($overrideExisting = true)
    {
        if (null !== $this->collLocalizationVariants && !$overrideExisting) {
            return;
        }
        $this->collLocalizationVariants = new ObjectCollection();
        $this->collLocalizationVariants->setModel('\keeko\core\model\LocalizationVariant');
    }

    /**
     * Gets an array of ChildLocalizationVariant objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocalization is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLocalizationVariant[] List of ChildLocalizationVariant objects
     * @throws PropelException
     */
    public function getLocalizationVariants(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationVariantsPartial && !$this->isNew();
        if (null === $this->collLocalizationVariants || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLocalizationVariants) {
                // return empty collection
                $this->initLocalizationVariants();
            } else {
                $collLocalizationVariants = ChildLocalizationVariantQuery::create(null, $criteria)
                    ->filterByLocalization($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLocalizationVariantsPartial && count($collLocalizationVariants)) {
                        $this->initLocalizationVariants(false);

                        foreach ($collLocalizationVariants as $obj) {
                            if (false == $this->collLocalizationVariants->contains($obj)) {
                                $this->collLocalizationVariants->append($obj);
                            }
                        }

                        $this->collLocalizationVariantsPartial = true;
                    }

                    return $collLocalizationVariants;
                }

                if ($partial && $this->collLocalizationVariants) {
                    foreach ($this->collLocalizationVariants as $obj) {
                        if ($obj->isNew()) {
                            $collLocalizationVariants[] = $obj;
                        }
                    }
                }

                $this->collLocalizationVariants = $collLocalizationVariants;
                $this->collLocalizationVariantsPartial = false;
            }
        }

        return $this->collLocalizationVariants;
    }

    /**
     * Sets a collection of ChildLocalizationVariant objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $localizationVariants A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLocalization The current object (for fluent API support)
     */
    public function setLocalizationVariants(Collection $localizationVariants, ConnectionInterface $con = null)
    {
        /** @var ChildLocalizationVariant[] $localizationVariantsToDelete */
        $localizationVariantsToDelete = $this->getLocalizationVariants(new Criteria(), $con)->diff($localizationVariants);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->localizationVariantsScheduledForDeletion = clone $localizationVariantsToDelete;

        foreach ($localizationVariantsToDelete as $localizationVariantRemoved) {
            $localizationVariantRemoved->setLocalization(null);
        }

        $this->collLocalizationVariants = null;
        foreach ($localizationVariants as $localizationVariant) {
            $this->addLocalizationVariant($localizationVariant);
        }

        $this->collLocalizationVariants = $localizationVariants;
        $this->collLocalizationVariantsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LocalizationVariant objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related LocalizationVariant objects.
     * @throws PropelException
     */
    public function countLocalizationVariants(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationVariantsPartial && !$this->isNew();
        if (null === $this->collLocalizationVariants || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLocalizationVariants) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLocalizationVariants());
            }

            $query = ChildLocalizationVariantQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocalization($this)
                ->count($con);
        }

        return count($this->collLocalizationVariants);
    }

    /**
     * Method called to associate a ChildLocalizationVariant object to this object
     * through the ChildLocalizationVariant foreign key attribute.
     *
     * @param  ChildLocalizationVariant $l ChildLocalizationVariant
     * @return $this|\keeko\core\model\Localization The current object (for fluent API support)
     */
    public function addLocalizationVariant(ChildLocalizationVariant $l)
    {
        if ($this->collLocalizationVariants === null) {
            $this->initLocalizationVariants();
            $this->collLocalizationVariantsPartial = true;
        }

        if (!$this->collLocalizationVariants->contains($l)) {
            $this->doAddLocalizationVariant($l);
        }

        return $this;
    }

    /**
     * @param ChildLocalizationVariant $localizationVariant The ChildLocalizationVariant object to add.
     */
    protected function doAddLocalizationVariant(ChildLocalizationVariant $localizationVariant)
    {
        $this->collLocalizationVariants[]= $localizationVariant;
        $localizationVariant->setLocalization($this);
    }

    /**
     * @param  ChildLocalizationVariant $localizationVariant The ChildLocalizationVariant object to remove.
     * @return $this|ChildLocalization The current object (for fluent API support)
     */
    public function removeLocalizationVariant(ChildLocalizationVariant $localizationVariant)
    {
        if ($this->getLocalizationVariants()->contains($localizationVariant)) {
            $pos = $this->collLocalizationVariants->search($localizationVariant);
            $this->collLocalizationVariants->remove($pos);
            if (null === $this->localizationVariantsScheduledForDeletion) {
                $this->localizationVariantsScheduledForDeletion = clone $this->collLocalizationVariants;
                $this->localizationVariantsScheduledForDeletion->clear();
            }
            $this->localizationVariantsScheduledForDeletion[]= clone $localizationVariant;
            $localizationVariant->setLocalization(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Localization is new, it will return
     * an empty collection; or if this Localization has previously
     * been saved, it will retrieve related LocalizationVariants from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Localization.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalizationVariant[] List of ChildLocalizationVariant objects
     */
    public function getLocalizationVariantsJoinLanguageVariant(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationVariantQuery::create(null, $criteria);
        $query->joinWith('LanguageVariant', $joinBehavior);

        return $this->getLocalizationVariants($query, $con);
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
     * Clears out the collLanguageVariants collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLanguageVariants()
     */
    public function clearLanguageVariants()
    {
        $this->collLanguageVariants = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collLanguageVariants crossRef collection.
     *
     * By default this just sets the collLanguageVariants collection to an empty collection (like clearLanguageVariants());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initLanguageVariants()
    {
        $this->collLanguageVariants = new ObjectCollection();
        $this->collLanguageVariantsPartial = true;

        $this->collLanguageVariants->setModel('\keeko\core\model\LanguageVariant');
    }

    /**
     * Checks if the collLanguageVariants collection is loaded.
     *
     * @return bool
     */
    public function isLanguageVariantsLoaded()
    {
        return null !== $this->collLanguageVariants;
    }

    /**
     * Gets a collection of ChildLanguageVariant objects related by a many-to-many relationship
     * to the current object by way of the kk_localization_variant cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocalization is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildLanguageVariant[] List of ChildLanguageVariant objects
     */
    public function getLanguageVariants(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLanguageVariantsPartial && !$this->isNew();
        if (null === $this->collLanguageVariants || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collLanguageVariants) {
                    $this->initLanguageVariants();
                }
            } else {

                $query = ChildLanguageVariantQuery::create(null, $criteria)
                    ->filterByLocalization($this);
                $collLanguageVariants = $query->find($con);
                if (null !== $criteria) {
                    return $collLanguageVariants;
                }

                if ($partial && $this->collLanguageVariants) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collLanguageVariants as $obj) {
                        if (!$collLanguageVariants->contains($obj)) {
                            $collLanguageVariants[] = $obj;
                        }
                    }
                }

                $this->collLanguageVariants = $collLanguageVariants;
                $this->collLanguageVariantsPartial = false;
            }
        }

        return $this->collLanguageVariants;
    }

    /**
     * Sets a collection of LanguageVariant objects related by a many-to-many relationship
     * to the current object by way of the kk_localization_variant cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $languageVariants A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildLocalization The current object (for fluent API support)
     */
    public function setLanguageVariants(Collection $languageVariants, ConnectionInterface $con = null)
    {
        $this->clearLanguageVariants();
        $currentLanguageVariants = $this->getLanguageVariants();

        $languageVariantsScheduledForDeletion = $currentLanguageVariants->diff($languageVariants);

        foreach ($languageVariantsScheduledForDeletion as $toDelete) {
            $this->removeLanguageVariant($toDelete);
        }

        foreach ($languageVariants as $languageVariant) {
            if (!$currentLanguageVariants->contains($languageVariant)) {
                $this->doAddLanguageVariant($languageVariant);
            }
        }

        $this->collLanguageVariantsPartial = false;
        $this->collLanguageVariants = $languageVariants;

        return $this;
    }

    /**
     * Gets the number of LanguageVariant objects related by a many-to-many relationship
     * to the current object by way of the kk_localization_variant cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related LanguageVariant objects
     */
    public function countLanguageVariants(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLanguageVariantsPartial && !$this->isNew();
        if (null === $this->collLanguageVariants || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLanguageVariants) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getLanguageVariants());
                }

                $query = ChildLanguageVariantQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLocalization($this)
                    ->count($con);
            }
        } else {
            return count($this->collLanguageVariants);
        }
    }

    /**
     * Associate a ChildLanguageVariant to this object
     * through the kk_localization_variant cross reference table.
     *
     * @param ChildLanguageVariant $languageVariant
     * @return ChildLocalization The current object (for fluent API support)
     */
    public function addLanguageVariant(ChildLanguageVariant $languageVariant)
    {
        if ($this->collLanguageVariants === null) {
            $this->initLanguageVariants();
        }

        if (!$this->getLanguageVariants()->contains($languageVariant)) {
            // only add it if the **same** object is not already associated
            $this->collLanguageVariants->push($languageVariant);
            $this->doAddLanguageVariant($languageVariant);
        }

        return $this;
    }

    /**
     *
     * @param ChildLanguageVariant $languageVariant
     */
    protected function doAddLanguageVariant(ChildLanguageVariant $languageVariant)
    {
        $localizationVariant = new ChildLocalizationVariant();

        $localizationVariant->setLanguageVariant($languageVariant);

        $localizationVariant->setLocalization($this);

        $this->addLocalizationVariant($localizationVariant);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$languageVariant->isLocalizationsLoaded()) {
            $languageVariant->initLocalizations();
            $languageVariant->getLocalizations()->push($this);
        } elseif (!$languageVariant->getLocalizations()->contains($this)) {
            $languageVariant->getLocalizations()->push($this);
        }

    }

    /**
     * Remove languageVariant of this object
     * through the kk_localization_variant cross reference table.
     *
     * @param ChildLanguageVariant $languageVariant
     * @return ChildLocalization The current object (for fluent API support)
     */
    public function removeLanguageVariant(ChildLanguageVariant $languageVariant)
    {
        if ($this->getLanguageVariants()->contains($languageVariant)) { $localizationVariant = new ChildLocalizationVariant();

            $localizationVariant->setLanguageVariant($languageVariant);
            if ($languageVariant->isLocalizationsLoaded()) {
                //remove the back reference if available
                $languageVariant->getLocalizations()->removeObject($this);
            }

            $localizationVariant->setLocalization($this);
            $this->removeLocalizationVariant(clone $localizationVariant);
            $localizationVariant->clear();

            $this->collLanguageVariants->remove($this->collLanguageVariants->search($languageVariant));

            if (null === $this->languageVariantsScheduledForDeletion) {
                $this->languageVariantsScheduledForDeletion = clone $this->collLanguageVariants;
                $this->languageVariantsScheduledForDeletion->clear();
            }

            $this->languageVariantsScheduledForDeletion->push($languageVariant);
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
        if (null !== $this->aParent) {
            $this->aParent->removeLocalizationRelatedById($this);
        }
        if (null !== $this->aLanguage) {
            $this->aLanguage->removeLocalizationRelatedByLanguageId($this);
        }
        if (null !== $this->aExtLang) {
            $this->aExtLang->removeLocalizationRelatedByExtLanguageId($this);
        }
        if (null !== $this->aScript) {
            $this->aScript->removeLocalization($this);
        }
        $this->id = null;
        $this->parent_id = null;
        $this->name = null;
        $this->locale = null;
        $this->language_id = null;
        $this->ext_language_id = null;
        $this->region = null;
        $this->script_id = null;
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
            if ($this->collLocalizationVariants) {
                foreach ($this->collLocalizationVariants as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collApplicationUris) {
                foreach ($this->collApplicationUris as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLanguageVariants) {
                foreach ($this->collLanguageVariants as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLocalizationsRelatedById = null;
        $this->collLocalizationVariants = null;
        $this->collApplicationUris = null;
        $this->collLanguageVariants = null;
        $this->aParent = null;
        $this->aLanguage = null;
        $this->aExtLang = null;
        $this->aScript = null;
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
