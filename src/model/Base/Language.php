<?php

namespace keeko\core\model\Base;

use \Exception;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use keeko\core\model\Language as ChildLanguage;
use keeko\core\model\LanguageFamily as ChildLanguageFamily;
use keeko\core\model\LanguageFamilyQuery as ChildLanguageFamilyQuery;
use keeko\core\model\LanguageQuery as ChildLanguageQuery;
use keeko\core\model\LanguageScope as ChildLanguageScope;
use keeko\core\model\LanguageScopeQuery as ChildLanguageScopeQuery;
use keeko\core\model\LanguageScript as ChildLanguageScript;
use keeko\core\model\LanguageScriptQuery as ChildLanguageScriptQuery;
use keeko\core\model\LanguageType as ChildLanguageType;
use keeko\core\model\LanguageTypeQuery as ChildLanguageTypeQuery;
use keeko\core\model\Localization as ChildLocalization;
use keeko\core\model\LocalizationQuery as ChildLocalizationQuery;
use keeko\core\model\Map\LanguageTableMap;

/**
 * Base class that represents a row from the 'kk_language' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Language implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\LanguageTableMap';


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
     * The value for the alpha_2 field.
     * @var        string
     */
    protected $alpha_2;

    /**
     * The value for the alpha_3t field.
     * @var        string
     */
    protected $alpha_3t;

    /**
     * The value for the alpha_3b field.
     * @var        string
     */
    protected $alpha_3b;

    /**
     * The value for the alpha_3 field.
     * @var        string
     */
    protected $alpha_3;

    /**
     * The value for the parent_id field.
     * @var        int
     */
    protected $parent_id;

    /**
     * The value for the macrolanguage_status field.
     * @var        string
     */
    protected $macrolanguage_status;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the native_name field.
     * @var        string
     */
    protected $native_name;

    /**
     * The value for the collate field.
     * @var        string
     */
    protected $collate;

    /**
     * The value for the subtag field.
     * @var        string
     */
    protected $subtag;

    /**
     * The value for the prefix field.
     * @var        string
     */
    protected $prefix;

    /**
     * The value for the scope_id field.
     * @var        int
     */
    protected $scope_id;

    /**
     * The value for the type_id field.
     * @var        int
     */
    protected $type_id;

    /**
     * The value for the family_id field.
     * @var        int
     */
    protected $family_id;

    /**
     * The value for the default_script_id field.
     * @var        int
     */
    protected $default_script_id;

    /**
     * @var        ChildLanguage
     */
    protected $aParent;

    /**
     * @var        ChildLanguageScope
     */
    protected $aScope;

    /**
     * @var        ChildLanguageType
     */
    protected $aType;

    /**
     * @var        ChildLanguageScript
     */
    protected $aScript;

    /**
     * @var        ChildLanguageFamily
     */
    protected $aFamily;

    /**
     * @var        ObjectCollection|ChildLanguage[] Collection to store aggregation of ChildLanguage objects.
     */
    protected $collSublanguages;
    protected $collSublanguagesPartial;

    /**
     * @var        ObjectCollection|ChildLocalization[] Collection to store aggregation of ChildLocalization objects.
     */
    protected $collLocalizationsRelatedByLanguageId;
    protected $collLocalizationsRelatedByLanguageIdPartial;

    /**
     * @var        ObjectCollection|ChildLocalization[] Collection to store aggregation of ChildLocalization objects.
     */
    protected $collLocalizationsRelatedByExtLanguageId;
    protected $collLocalizationsRelatedByExtLanguageIdPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLanguage[]
     */
    protected $sublanguagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocalization[]
     */
    protected $localizationsRelatedByLanguageIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocalization[]
     */
    protected $localizationsRelatedByExtLanguageIdScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\Language object.
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
     * Compares this with another <code>Language</code> instance.  If
     * <code>obj</code> is an instance of <code>Language</code>, delegates to
     * <code>equals(Language)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Language The current object, for fluid interface
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
     * Get the [alpha_2] column value.
     * ISO 639-1 two-letter code
     * @return string
     */
    public function getAlpha2()
    {
        return $this->alpha_2;
    }

    /**
     * Get the [alpha_3t] column value.
     * ISO 639-2/T three-letter code
     * @return string
     */
    public function getAlpha3T()
    {
        return $this->alpha_3t;
    }

    /**
     * Get the [alpha_3b] column value.
     * ISO 639-2/B three-letter code
     * @return string
     */
    public function getAlpha3B()
    {
        return $this->alpha_3b;
    }

    /**
     * Get the [alpha_3] column value.
     * ISO 639-3 three-letter code
     * @return string
     */
    public function getAlpha3()
    {
        return $this->alpha_3;
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
     * Get the [macrolanguage_status] column value.
     *
     * @return string
     */
    public function getMacrolanguageStatus()
    {
        return $this->macrolanguage_status;
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
     * Get the [native_name] column value.
     *
     * @return string
     */
    public function getNativeName()
    {
        return $this->native_name;
    }

    /**
     * Get the [collate] column value.
     *
     * @return string
     */
    public function getCollate()
    {
        return $this->collate;
    }

    /**
     * Get the [subtag] column value.
     * Subtag used for locales
     * @return string
     */
    public function getSubtag()
    {
        return $this->subtag;
    }

    /**
     * Get the [prefix] column value.
     * Possibly (required) subtag (means this is an extlang)
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get the [scope_id] column value.
     *
     * @return int
     */
    public function getScopeId()
    {
        return $this->scope_id;
    }

    /**
     * Get the [type_id] column value.
     *
     * @return int
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Get the [family_id] column value.
     *
     * @return int
     */
    public function getFamilyId()
    {
        return $this->family_id;
    }

    /**
     * Get the [default_script_id] column value.
     *
     * @return int
     */
    public function getDefaultScriptId()
    {
        return $this->default_script_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LanguageTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [alpha_2] column.
     * ISO 639-1 two-letter code
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setAlpha2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alpha_2 !== $v) {
            $this->alpha_2 = $v;
            $this->modifiedColumns[LanguageTableMap::COL_ALPHA_2] = true;
        }

        return $this;
    } // setAlpha2()

    /**
     * Set the value of [alpha_3t] column.
     * ISO 639-2/T three-letter code
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setAlpha3T($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alpha_3t !== $v) {
            $this->alpha_3t = $v;
            $this->modifiedColumns[LanguageTableMap::COL_ALPHA_3T] = true;
        }

        return $this;
    } // setAlpha3T()

    /**
     * Set the value of [alpha_3b] column.
     * ISO 639-2/B three-letter code
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setAlpha3B($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alpha_3b !== $v) {
            $this->alpha_3b = $v;
            $this->modifiedColumns[LanguageTableMap::COL_ALPHA_3B] = true;
        }

        return $this;
    } // setAlpha3B()

    /**
     * Set the value of [alpha_3] column.
     * ISO 639-3 three-letter code
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setAlpha3($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alpha_3 !== $v) {
            $this->alpha_3 = $v;
            $this->modifiedColumns[LanguageTableMap::COL_ALPHA_3] = true;
        }

        return $this;
    } // setAlpha3()

    /**
     * Set the value of [parent_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setParentId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->parent_id !== $v) {
            $this->parent_id = $v;
            $this->modifiedColumns[LanguageTableMap::COL_PARENT_ID] = true;
        }

        if ($this->aParent !== null && $this->aParent->getId() !== $v) {
            $this->aParent = null;
        }

        return $this;
    } // setParentId()

    /**
     * Set the value of [macrolanguage_status] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setMacrolanguageStatus($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->macrolanguage_status !== $v) {
            $this->macrolanguage_status = $v;
            $this->modifiedColumns[LanguageTableMap::COL_MACROLANGUAGE_STATUS] = true;
        }

        return $this;
    } // setMacrolanguageStatus()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[LanguageTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [native_name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setNativeName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->native_name !== $v) {
            $this->native_name = $v;
            $this->modifiedColumns[LanguageTableMap::COL_NATIVE_NAME] = true;
        }

        return $this;
    } // setNativeName()

    /**
     * Set the value of [collate] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setCollate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->collate !== $v) {
            $this->collate = $v;
            $this->modifiedColumns[LanguageTableMap::COL_COLLATE] = true;
        }

        return $this;
    } // setCollate()

    /**
     * Set the value of [subtag] column.
     * Subtag used for locales
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setSubtag($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->subtag !== $v) {
            $this->subtag = $v;
            $this->modifiedColumns[LanguageTableMap::COL_SUBTAG] = true;
        }

        return $this;
    } // setSubtag()

    /**
     * Set the value of [prefix] column.
     * Possibly (required) subtag (means this is an extlang)
     * @param string $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setPrefix($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->prefix !== $v) {
            $this->prefix = $v;
            $this->modifiedColumns[LanguageTableMap::COL_PREFIX] = true;
        }

        return $this;
    } // setPrefix()

    /**
     * Set the value of [scope_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setScopeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->scope_id !== $v) {
            $this->scope_id = $v;
            $this->modifiedColumns[LanguageTableMap::COL_SCOPE_ID] = true;
        }

        if ($this->aScope !== null && $this->aScope->getId() !== $v) {
            $this->aScope = null;
        }

        return $this;
    } // setScopeId()

    /**
     * Set the value of [type_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type_id !== $v) {
            $this->type_id = $v;
            $this->modifiedColumns[LanguageTableMap::COL_TYPE_ID] = true;
        }

        if ($this->aType !== null && $this->aType->getId() !== $v) {
            $this->aType = null;
        }

        return $this;
    } // setTypeId()

    /**
     * Set the value of [family_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setFamilyId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->family_id !== $v) {
            $this->family_id = $v;
            $this->modifiedColumns[LanguageTableMap::COL_FAMILY_ID] = true;
        }

        if ($this->aFamily !== null && $this->aFamily->getId() !== $v) {
            $this->aFamily = null;
        }

        return $this;
    } // setFamilyId()

    /**
     * Set the value of [default_script_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    protected function setDefaultScriptId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->default_script_id !== $v) {
            $this->default_script_id = $v;
            $this->modifiedColumns[LanguageTableMap::COL_DEFAULT_SCRIPT_ID] = true;
        }

        if ($this->aScript !== null && $this->aScript->getId() !== $v) {
            $this->aScript = null;
        }

        return $this;
    } // setDefaultScriptId()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LanguageTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LanguageTableMap::translateFieldName('Alpha2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LanguageTableMap::translateFieldName('Alpha3T', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_3t = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LanguageTableMap::translateFieldName('Alpha3B', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_3b = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LanguageTableMap::translateFieldName('Alpha3', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_3 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LanguageTableMap::translateFieldName('ParentId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->parent_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : LanguageTableMap::translateFieldName('MacrolanguageStatus', TableMap::TYPE_PHPNAME, $indexType)];
            $this->macrolanguage_status = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : LanguageTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : LanguageTableMap::translateFieldName('NativeName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->native_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : LanguageTableMap::translateFieldName('Collate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collate = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : LanguageTableMap::translateFieldName('Subtag', TableMap::TYPE_PHPNAME, $indexType)];
            $this->subtag = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : LanguageTableMap::translateFieldName('Prefix', TableMap::TYPE_PHPNAME, $indexType)];
            $this->prefix = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : LanguageTableMap::translateFieldName('ScopeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->scope_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : LanguageTableMap::translateFieldName('TypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : LanguageTableMap::translateFieldName('FamilyId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->family_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : LanguageTableMap::translateFieldName('DefaultScriptId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->default_script_id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 16; // 16 = LanguageTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\Language'), 0, $e);
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
        if ($this->aScope !== null && $this->scope_id !== $this->aScope->getId()) {
            $this->aScope = null;
        }
        if ($this->aType !== null && $this->type_id !== $this->aType->getId()) {
            $this->aType = null;
        }
        if ($this->aFamily !== null && $this->family_id !== $this->aFamily->getId()) {
            $this->aFamily = null;
        }
        if ($this->aScript !== null && $this->default_script_id !== $this->aScript->getId()) {
            $this->aScript = null;
        }
    } // ensureConsistency

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
        $pos = LanguageTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAlpha2();
                break;
            case 2:
                return $this->getAlpha3T();
                break;
            case 3:
                return $this->getAlpha3B();
                break;
            case 4:
                return $this->getAlpha3();
                break;
            case 5:
                return $this->getParentId();
                break;
            case 6:
                return $this->getMacrolanguageStatus();
                break;
            case 7:
                return $this->getName();
                break;
            case 8:
                return $this->getNativeName();
                break;
            case 9:
                return $this->getCollate();
                break;
            case 10:
                return $this->getSubtag();
                break;
            case 11:
                return $this->getPrefix();
                break;
            case 12:
                return $this->getScopeId();
                break;
            case 13:
                return $this->getTypeId();
                break;
            case 14:
                return $this->getFamilyId();
                break;
            case 15:
                return $this->getDefaultScriptId();
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

        if (isset($alreadyDumpedObjects['Language'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Language'][$this->hashCode()] = true;
        $keys = LanguageTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAlpha2(),
            $keys[2] => $this->getAlpha3T(),
            $keys[3] => $this->getAlpha3B(),
            $keys[4] => $this->getAlpha3(),
            $keys[5] => $this->getParentId(),
            $keys[6] => $this->getMacrolanguageStatus(),
            $keys[7] => $this->getName(),
            $keys[8] => $this->getNativeName(),
            $keys[9] => $this->getCollate(),
            $keys[10] => $this->getSubtag(),
            $keys[11] => $this->getPrefix(),
            $keys[12] => $this->getScopeId(),
            $keys[13] => $this->getTypeId(),
            $keys[14] => $this->getFamilyId(),
            $keys[15] => $this->getDefaultScriptId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aParent) {

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

                $result[$key] = $this->aParent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aScope) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'languageScope';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_language_scope';
                        break;
                    default:
                        $key = 'LanguageScope';
                }

                $result[$key] = $this->aScope->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aType) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'languageType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_language_type';
                        break;
                    default:
                        $key = 'LanguageType';
                }

                $result[$key] = $this->aType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->aFamily) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'languageFamily';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_language_family';
                        break;
                    default:
                        $key = 'LanguageFamily';
                }

                $result[$key] = $this->aFamily->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collSublanguages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'languages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_languages';
                        break;
                    default:
                        $key = 'Languages';
                }

                $result[$key] = $this->collSublanguages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLocalizationsRelatedByLanguageId) {

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

                $result[$key] = $this->collLocalizationsRelatedByLanguageId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLocalizationsRelatedByExtLanguageId) {

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

                $result[$key] = $this->collLocalizationsRelatedByExtLanguageId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(LanguageTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LanguageTableMap::COL_ID)) {
            $criteria->add(LanguageTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_ALPHA_2)) {
            $criteria->add(LanguageTableMap::COL_ALPHA_2, $this->alpha_2);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_ALPHA_3T)) {
            $criteria->add(LanguageTableMap::COL_ALPHA_3T, $this->alpha_3t);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_ALPHA_3B)) {
            $criteria->add(LanguageTableMap::COL_ALPHA_3B, $this->alpha_3b);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_ALPHA_3)) {
            $criteria->add(LanguageTableMap::COL_ALPHA_3, $this->alpha_3);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_PARENT_ID)) {
            $criteria->add(LanguageTableMap::COL_PARENT_ID, $this->parent_id);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_MACROLANGUAGE_STATUS)) {
            $criteria->add(LanguageTableMap::COL_MACROLANGUAGE_STATUS, $this->macrolanguage_status);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_NAME)) {
            $criteria->add(LanguageTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_NATIVE_NAME)) {
            $criteria->add(LanguageTableMap::COL_NATIVE_NAME, $this->native_name);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_COLLATE)) {
            $criteria->add(LanguageTableMap::COL_COLLATE, $this->collate);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_SUBTAG)) {
            $criteria->add(LanguageTableMap::COL_SUBTAG, $this->subtag);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_PREFIX)) {
            $criteria->add(LanguageTableMap::COL_PREFIX, $this->prefix);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_SCOPE_ID)) {
            $criteria->add(LanguageTableMap::COL_SCOPE_ID, $this->scope_id);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_TYPE_ID)) {
            $criteria->add(LanguageTableMap::COL_TYPE_ID, $this->type_id);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_FAMILY_ID)) {
            $criteria->add(LanguageTableMap::COL_FAMILY_ID, $this->family_id);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_DEFAULT_SCRIPT_ID)) {
            $criteria->add(LanguageTableMap::COL_DEFAULT_SCRIPT_ID, $this->default_script_id);
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
        $criteria = ChildLanguageQuery::create();
        $criteria->add(LanguageTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\Language (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAlpha2($this->getAlpha2());
        $copyObj->setAlpha3T($this->getAlpha3T());
        $copyObj->setAlpha3B($this->getAlpha3B());
        $copyObj->setAlpha3($this->getAlpha3());
        $copyObj->setParentId($this->getParentId());
        $copyObj->setMacrolanguageStatus($this->getMacrolanguageStatus());
        $copyObj->setName($this->getName());
        $copyObj->setNativeName($this->getNativeName());
        $copyObj->setCollate($this->getCollate());
        $copyObj->setSubtag($this->getSubtag());
        $copyObj->setPrefix($this->getPrefix());
        $copyObj->setScopeId($this->getScopeId());
        $copyObj->setTypeId($this->getTypeId());
        $copyObj->setFamilyId($this->getFamilyId());
        $copyObj->setDefaultScriptId($this->getDefaultScriptId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getSublanguages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSublanguage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLocalizationsRelatedByLanguageId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLocalizationRelatedByLanguageId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLocalizationsRelatedByExtLanguageId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLocalizationRelatedByExtLanguageId($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\Language Clone of current object.
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
     * Declares an association between this object and a ChildLanguage object.
     *
     * @param  ChildLanguage $v
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     * @throws PropelException
     */
    public function setParent(ChildLanguage $v = null)
    {
        if ($v === null) {
            $this->setParentId(NULL);
        } else {
            $this->setParentId($v->getId());
        }

        $this->aParent = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLanguage object, it will not be re-added.
        if ($v !== null) {
            $v->addSublanguage($this);
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
    public function getParent(ConnectionInterface $con = null)
    {
        if ($this->aParent === null && ($this->parent_id !== null)) {
            $this->aParent = ChildLanguageQuery::create()->findPk($this->parent_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aParent->addSublanguages($this);
             */
        }

        return $this->aParent;
    }

    /**
     * Declares an association between this object and a ChildLanguageScope object.
     *
     * @param  ChildLanguageScope $v
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     * @throws PropelException
     */
    public function setScope(ChildLanguageScope $v = null)
    {
        if ($v === null) {
            $this->setScopeId(NULL);
        } else {
            $this->setScopeId($v->getId());
        }

        $this->aScope = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLanguageScope object, it will not be re-added.
        if ($v !== null) {
            $v->addLanguage($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLanguageScope object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLanguageScope The associated ChildLanguageScope object.
     * @throws PropelException
     */
    public function getScope(ConnectionInterface $con = null)
    {
        if ($this->aScope === null && ($this->scope_id !== null)) {
            $this->aScope = ChildLanguageScopeQuery::create()->findPk($this->scope_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aScope->addLanguages($this);
             */
        }

        return $this->aScope;
    }

    /**
     * Declares an association between this object and a ChildLanguageType object.
     *
     * @param  ChildLanguageType $v
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     * @throws PropelException
     */
    public function setType(ChildLanguageType $v = null)
    {
        if ($v === null) {
            $this->setTypeId(NULL);
        } else {
            $this->setTypeId($v->getId());
        }

        $this->aType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLanguageType object, it will not be re-added.
        if ($v !== null) {
            $v->addLanguage($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLanguageType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLanguageType The associated ChildLanguageType object.
     * @throws PropelException
     */
    public function getType(ConnectionInterface $con = null)
    {
        if ($this->aType === null && ($this->type_id !== null)) {
            $this->aType = ChildLanguageTypeQuery::create()->findPk($this->type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aType->addLanguages($this);
             */
        }

        return $this->aType;
    }

    /**
     * Declares an association between this object and a ChildLanguageScript object.
     *
     * @param  ChildLanguageScript $v
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     * @throws PropelException
     */
    public function setScript(ChildLanguageScript $v = null)
    {
        if ($v === null) {
            $this->setDefaultScriptId(NULL);
        } else {
            $this->setDefaultScriptId($v->getId());
        }

        $this->aScript = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLanguageScript object, it will not be re-added.
        if ($v !== null) {
            $v->addLanguage($this);
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
        if ($this->aScript === null && ($this->default_script_id !== null)) {
            $this->aScript = ChildLanguageScriptQuery::create()->findPk($this->default_script_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aScript->addLanguages($this);
             */
        }

        return $this->aScript;
    }

    /**
     * Declares an association between this object and a ChildLanguageFamily object.
     *
     * @param  ChildLanguageFamily $v
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFamily(ChildLanguageFamily $v = null)
    {
        if ($v === null) {
            $this->setFamilyId(NULL);
        } else {
            $this->setFamilyId($v->getId());
        }

        $this->aFamily = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLanguageFamily object, it will not be re-added.
        if ($v !== null) {
            $v->addLanguage($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLanguageFamily object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLanguageFamily The associated ChildLanguageFamily object.
     * @throws PropelException
     */
    public function getFamily(ConnectionInterface $con = null)
    {
        if ($this->aFamily === null && ($this->family_id !== null)) {
            $this->aFamily = ChildLanguageFamilyQuery::create()->findPk($this->family_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFamily->addLanguages($this);
             */
        }

        return $this->aFamily;
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
        if ('Sublanguage' == $relationName) {
            return $this->initSublanguages();
        }
        if ('LocalizationRelatedByLanguageId' == $relationName) {
            return $this->initLocalizationsRelatedByLanguageId();
        }
        if ('LocalizationRelatedByExtLanguageId' == $relationName) {
            return $this->initLocalizationsRelatedByExtLanguageId();
        }
    }

    /**
     * Clears out the collSublanguages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSublanguages()
     */
    public function clearSublanguages()
    {
        $this->collSublanguages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSublanguages collection loaded partially.
     */
    public function resetPartialSublanguages($v = true)
    {
        $this->collSublanguagesPartial = $v;
    }

    /**
     * Initializes the collSublanguages collection.
     *
     * By default this just sets the collSublanguages collection to an empty array (like clearcollSublanguages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSublanguages($overrideExisting = true)
    {
        if (null !== $this->collSublanguages && !$overrideExisting) {
            return;
        }
        $this->collSublanguages = new ObjectCollection();
        $this->collSublanguages->setModel('\keeko\core\model\Language');
    }

    /**
     * Gets an array of ChildLanguage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLanguage is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     * @throws PropelException
     */
    public function getSublanguages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSublanguagesPartial && !$this->isNew();
        if (null === $this->collSublanguages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSublanguages) {
                // return empty collection
                $this->initSublanguages();
            } else {
                $collSublanguages = ChildLanguageQuery::create(null, $criteria)
                    ->filterByParent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSublanguagesPartial && count($collSublanguages)) {
                        $this->initSublanguages(false);

                        foreach ($collSublanguages as $obj) {
                            if (false == $this->collSublanguages->contains($obj)) {
                                $this->collSublanguages->append($obj);
                            }
                        }

                        $this->collSublanguagesPartial = true;
                    }

                    return $collSublanguages;
                }

                if ($partial && $this->collSublanguages) {
                    foreach ($this->collSublanguages as $obj) {
                        if ($obj->isNew()) {
                            $collSublanguages[] = $obj;
                        }
                    }
                }

                $this->collSublanguages = $collSublanguages;
                $this->collSublanguagesPartial = false;
            }
        }

        return $this->collSublanguages;
    }

    /**
     * Sets a collection of ChildLanguage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $sublanguages A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function setSublanguages(Collection $sublanguages, ConnectionInterface $con = null)
    {
        /** @var ChildLanguage[] $sublanguagesToDelete */
        $sublanguagesToDelete = $this->getSublanguages(new Criteria(), $con)->diff($sublanguages);


        $this->sublanguagesScheduledForDeletion = $sublanguagesToDelete;

        foreach ($sublanguagesToDelete as $sublanguageRemoved) {
            $sublanguageRemoved->setParent(null);
        }

        $this->collSublanguages = null;
        foreach ($sublanguages as $sublanguage) {
            $this->addSublanguage($sublanguage);
        }

        $this->collSublanguages = $sublanguages;
        $this->collSublanguagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Language objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Language objects.
     * @throws PropelException
     */
    public function countSublanguages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSublanguagesPartial && !$this->isNew();
        if (null === $this->collSublanguages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSublanguages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSublanguages());
            }

            $query = ChildLanguageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByParent($this)
                ->count($con);
        }

        return count($this->collSublanguages);
    }

    /**
     * Method called to associate a ChildLanguage object to this object
     * through the ChildLanguage foreign key attribute.
     *
     * @param  ChildLanguage $l ChildLanguage
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    public function addSublanguage(ChildLanguage $l)
    {
        if ($this->collSublanguages === null) {
            $this->initSublanguages();
            $this->collSublanguagesPartial = true;
        }

        if (!$this->collSublanguages->contains($l)) {
            $this->doAddSublanguage($l);
        }

        return $this;
    }

    /**
     * @param ChildLanguage $sublanguage The ChildLanguage object to add.
     */
    protected function doAddSublanguage(ChildLanguage $sublanguage)
    {
        $this->collSublanguages[]= $sublanguage;
        $sublanguage->setParent($this);
    }

    /**
     * @param  ChildLanguage $sublanguage The ChildLanguage object to remove.
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function removeSublanguage(ChildLanguage $sublanguage)
    {
        if ($this->getSublanguages()->contains($sublanguage)) {
            $pos = $this->collSublanguages->search($sublanguage);
            $this->collSublanguages->remove($pos);
            if (null === $this->sublanguagesScheduledForDeletion) {
                $this->sublanguagesScheduledForDeletion = clone $this->collSublanguages;
                $this->sublanguagesScheduledForDeletion->clear();
            }
            $this->sublanguagesScheduledForDeletion[]= $sublanguage;
            $sublanguage->setParent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related Sublanguages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getSublanguagesJoinScope(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLanguageQuery::create(null, $criteria);
        $query->joinWith('Scope', $joinBehavior);

        return $this->getSublanguages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related Sublanguages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getSublanguagesJoinType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLanguageQuery::create(null, $criteria);
        $query->joinWith('Type', $joinBehavior);

        return $this->getSublanguages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related Sublanguages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getSublanguagesJoinScript(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLanguageQuery::create(null, $criteria);
        $query->joinWith('Script', $joinBehavior);

        return $this->getSublanguages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related Sublanguages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getSublanguagesJoinFamily(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLanguageQuery::create(null, $criteria);
        $query->joinWith('Family', $joinBehavior);

        return $this->getSublanguages($query, $con);
    }

    /**
     * Clears out the collLocalizationsRelatedByLanguageId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLocalizationsRelatedByLanguageId()
     */
    public function clearLocalizationsRelatedByLanguageId()
    {
        $this->collLocalizationsRelatedByLanguageId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLocalizationsRelatedByLanguageId collection loaded partially.
     */
    public function resetPartialLocalizationsRelatedByLanguageId($v = true)
    {
        $this->collLocalizationsRelatedByLanguageIdPartial = $v;
    }

    /**
     * Initializes the collLocalizationsRelatedByLanguageId collection.
     *
     * By default this just sets the collLocalizationsRelatedByLanguageId collection to an empty array (like clearcollLocalizationsRelatedByLanguageId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLocalizationsRelatedByLanguageId($overrideExisting = true)
    {
        if (null !== $this->collLocalizationsRelatedByLanguageId && !$overrideExisting) {
            return;
        }
        $this->collLocalizationsRelatedByLanguageId = new ObjectCollection();
        $this->collLocalizationsRelatedByLanguageId->setModel('\keeko\core\model\Localization');
    }

    /**
     * Gets an array of ChildLocalization objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLanguage is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     * @throws PropelException
     */
    public function getLocalizationsRelatedByLanguageId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsRelatedByLanguageIdPartial && !$this->isNew();
        if (null === $this->collLocalizationsRelatedByLanguageId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLocalizationsRelatedByLanguageId) {
                // return empty collection
                $this->initLocalizationsRelatedByLanguageId();
            } else {
                $collLocalizationsRelatedByLanguageId = ChildLocalizationQuery::create(null, $criteria)
                    ->filterByLanguage($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLocalizationsRelatedByLanguageIdPartial && count($collLocalizationsRelatedByLanguageId)) {
                        $this->initLocalizationsRelatedByLanguageId(false);

                        foreach ($collLocalizationsRelatedByLanguageId as $obj) {
                            if (false == $this->collLocalizationsRelatedByLanguageId->contains($obj)) {
                                $this->collLocalizationsRelatedByLanguageId->append($obj);
                            }
                        }

                        $this->collLocalizationsRelatedByLanguageIdPartial = true;
                    }

                    return $collLocalizationsRelatedByLanguageId;
                }

                if ($partial && $this->collLocalizationsRelatedByLanguageId) {
                    foreach ($this->collLocalizationsRelatedByLanguageId as $obj) {
                        if ($obj->isNew()) {
                            $collLocalizationsRelatedByLanguageId[] = $obj;
                        }
                    }
                }

                $this->collLocalizationsRelatedByLanguageId = $collLocalizationsRelatedByLanguageId;
                $this->collLocalizationsRelatedByLanguageIdPartial = false;
            }
        }

        return $this->collLocalizationsRelatedByLanguageId;
    }

    /**
     * Sets a collection of ChildLocalization objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $localizationsRelatedByLanguageId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function setLocalizationsRelatedByLanguageId(Collection $localizationsRelatedByLanguageId, ConnectionInterface $con = null)
    {
        /** @var ChildLocalization[] $localizationsRelatedByLanguageIdToDelete */
        $localizationsRelatedByLanguageIdToDelete = $this->getLocalizationsRelatedByLanguageId(new Criteria(), $con)->diff($localizationsRelatedByLanguageId);


        $this->localizationsRelatedByLanguageIdScheduledForDeletion = $localizationsRelatedByLanguageIdToDelete;

        foreach ($localizationsRelatedByLanguageIdToDelete as $localizationRelatedByLanguageIdRemoved) {
            $localizationRelatedByLanguageIdRemoved->setLanguage(null);
        }

        $this->collLocalizationsRelatedByLanguageId = null;
        foreach ($localizationsRelatedByLanguageId as $localizationRelatedByLanguageId) {
            $this->addLocalizationRelatedByLanguageId($localizationRelatedByLanguageId);
        }

        $this->collLocalizationsRelatedByLanguageId = $localizationsRelatedByLanguageId;
        $this->collLocalizationsRelatedByLanguageIdPartial = false;

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
    public function countLocalizationsRelatedByLanguageId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsRelatedByLanguageIdPartial && !$this->isNew();
        if (null === $this->collLocalizationsRelatedByLanguageId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLocalizationsRelatedByLanguageId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLocalizationsRelatedByLanguageId());
            }

            $query = ChildLocalizationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLanguage($this)
                ->count($con);
        }

        return count($this->collLocalizationsRelatedByLanguageId);
    }

    /**
     * Method called to associate a ChildLocalization object to this object
     * through the ChildLocalization foreign key attribute.
     *
     * @param  ChildLocalization $l ChildLocalization
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    public function addLocalizationRelatedByLanguageId(ChildLocalization $l)
    {
        if ($this->collLocalizationsRelatedByLanguageId === null) {
            $this->initLocalizationsRelatedByLanguageId();
            $this->collLocalizationsRelatedByLanguageIdPartial = true;
        }

        if (!$this->collLocalizationsRelatedByLanguageId->contains($l)) {
            $this->doAddLocalizationRelatedByLanguageId($l);
        }

        return $this;
    }

    /**
     * @param ChildLocalization $localizationRelatedByLanguageId The ChildLocalization object to add.
     */
    protected function doAddLocalizationRelatedByLanguageId(ChildLocalization $localizationRelatedByLanguageId)
    {
        $this->collLocalizationsRelatedByLanguageId[]= $localizationRelatedByLanguageId;
        $localizationRelatedByLanguageId->setLanguage($this);
    }

    /**
     * @param  ChildLocalization $localizationRelatedByLanguageId The ChildLocalization object to remove.
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function removeLocalizationRelatedByLanguageId(ChildLocalization $localizationRelatedByLanguageId)
    {
        if ($this->getLocalizationsRelatedByLanguageId()->contains($localizationRelatedByLanguageId)) {
            $pos = $this->collLocalizationsRelatedByLanguageId->search($localizationRelatedByLanguageId);
            $this->collLocalizationsRelatedByLanguageId->remove($pos);
            if (null === $this->localizationsRelatedByLanguageIdScheduledForDeletion) {
                $this->localizationsRelatedByLanguageIdScheduledForDeletion = clone $this->collLocalizationsRelatedByLanguageId;
                $this->localizationsRelatedByLanguageIdScheduledForDeletion->clear();
            }
            $this->localizationsRelatedByLanguageIdScheduledForDeletion[]= $localizationRelatedByLanguageId;
            $localizationRelatedByLanguageId->setLanguage(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related LocalizationsRelatedByLanguageId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsRelatedByLanguageIdJoinParent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('Parent', $joinBehavior);

        return $this->getLocalizationsRelatedByLanguageId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related LocalizationsRelatedByLanguageId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsRelatedByLanguageIdJoinScript(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('Script', $joinBehavior);

        return $this->getLocalizationsRelatedByLanguageId($query, $con);
    }

    /**
     * Clears out the collLocalizationsRelatedByExtLanguageId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLocalizationsRelatedByExtLanguageId()
     */
    public function clearLocalizationsRelatedByExtLanguageId()
    {
        $this->collLocalizationsRelatedByExtLanguageId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLocalizationsRelatedByExtLanguageId collection loaded partially.
     */
    public function resetPartialLocalizationsRelatedByExtLanguageId($v = true)
    {
        $this->collLocalizationsRelatedByExtLanguageIdPartial = $v;
    }

    /**
     * Initializes the collLocalizationsRelatedByExtLanguageId collection.
     *
     * By default this just sets the collLocalizationsRelatedByExtLanguageId collection to an empty array (like clearcollLocalizationsRelatedByExtLanguageId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLocalizationsRelatedByExtLanguageId($overrideExisting = true)
    {
        if (null !== $this->collLocalizationsRelatedByExtLanguageId && !$overrideExisting) {
            return;
        }
        $this->collLocalizationsRelatedByExtLanguageId = new ObjectCollection();
        $this->collLocalizationsRelatedByExtLanguageId->setModel('\keeko\core\model\Localization');
    }

    /**
     * Gets an array of ChildLocalization objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLanguage is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     * @throws PropelException
     */
    public function getLocalizationsRelatedByExtLanguageId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsRelatedByExtLanguageIdPartial && !$this->isNew();
        if (null === $this->collLocalizationsRelatedByExtLanguageId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLocalizationsRelatedByExtLanguageId) {
                // return empty collection
                $this->initLocalizationsRelatedByExtLanguageId();
            } else {
                $collLocalizationsRelatedByExtLanguageId = ChildLocalizationQuery::create(null, $criteria)
                    ->filterByExtLang($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLocalizationsRelatedByExtLanguageIdPartial && count($collLocalizationsRelatedByExtLanguageId)) {
                        $this->initLocalizationsRelatedByExtLanguageId(false);

                        foreach ($collLocalizationsRelatedByExtLanguageId as $obj) {
                            if (false == $this->collLocalizationsRelatedByExtLanguageId->contains($obj)) {
                                $this->collLocalizationsRelatedByExtLanguageId->append($obj);
                            }
                        }

                        $this->collLocalizationsRelatedByExtLanguageIdPartial = true;
                    }

                    return $collLocalizationsRelatedByExtLanguageId;
                }

                if ($partial && $this->collLocalizationsRelatedByExtLanguageId) {
                    foreach ($this->collLocalizationsRelatedByExtLanguageId as $obj) {
                        if ($obj->isNew()) {
                            $collLocalizationsRelatedByExtLanguageId[] = $obj;
                        }
                    }
                }

                $this->collLocalizationsRelatedByExtLanguageId = $collLocalizationsRelatedByExtLanguageId;
                $this->collLocalizationsRelatedByExtLanguageIdPartial = false;
            }
        }

        return $this->collLocalizationsRelatedByExtLanguageId;
    }

    /**
     * Sets a collection of ChildLocalization objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $localizationsRelatedByExtLanguageId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function setLocalizationsRelatedByExtLanguageId(Collection $localizationsRelatedByExtLanguageId, ConnectionInterface $con = null)
    {
        /** @var ChildLocalization[] $localizationsRelatedByExtLanguageIdToDelete */
        $localizationsRelatedByExtLanguageIdToDelete = $this->getLocalizationsRelatedByExtLanguageId(new Criteria(), $con)->diff($localizationsRelatedByExtLanguageId);


        $this->localizationsRelatedByExtLanguageIdScheduledForDeletion = $localizationsRelatedByExtLanguageIdToDelete;

        foreach ($localizationsRelatedByExtLanguageIdToDelete as $localizationRelatedByExtLanguageIdRemoved) {
            $localizationRelatedByExtLanguageIdRemoved->setExtLang(null);
        }

        $this->collLocalizationsRelatedByExtLanguageId = null;
        foreach ($localizationsRelatedByExtLanguageId as $localizationRelatedByExtLanguageId) {
            $this->addLocalizationRelatedByExtLanguageId($localizationRelatedByExtLanguageId);
        }

        $this->collLocalizationsRelatedByExtLanguageId = $localizationsRelatedByExtLanguageId;
        $this->collLocalizationsRelatedByExtLanguageIdPartial = false;

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
    public function countLocalizationsRelatedByExtLanguageId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsRelatedByExtLanguageIdPartial && !$this->isNew();
        if (null === $this->collLocalizationsRelatedByExtLanguageId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLocalizationsRelatedByExtLanguageId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLocalizationsRelatedByExtLanguageId());
            }

            $query = ChildLocalizationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByExtLang($this)
                ->count($con);
        }

        return count($this->collLocalizationsRelatedByExtLanguageId);
    }

    /**
     * Method called to associate a ChildLocalization object to this object
     * through the ChildLocalization foreign key attribute.
     *
     * @param  ChildLocalization $l ChildLocalization
     * @return $this|\keeko\core\model\Language The current object (for fluent API support)
     */
    public function addLocalizationRelatedByExtLanguageId(ChildLocalization $l)
    {
        if ($this->collLocalizationsRelatedByExtLanguageId === null) {
            $this->initLocalizationsRelatedByExtLanguageId();
            $this->collLocalizationsRelatedByExtLanguageIdPartial = true;
        }

        if (!$this->collLocalizationsRelatedByExtLanguageId->contains($l)) {
            $this->doAddLocalizationRelatedByExtLanguageId($l);
        }

        return $this;
    }

    /**
     * @param ChildLocalization $localizationRelatedByExtLanguageId The ChildLocalization object to add.
     */
    protected function doAddLocalizationRelatedByExtLanguageId(ChildLocalization $localizationRelatedByExtLanguageId)
    {
        $this->collLocalizationsRelatedByExtLanguageId[]= $localizationRelatedByExtLanguageId;
        $localizationRelatedByExtLanguageId->setExtLang($this);
    }

    /**
     * @param  ChildLocalization $localizationRelatedByExtLanguageId The ChildLocalization object to remove.
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function removeLocalizationRelatedByExtLanguageId(ChildLocalization $localizationRelatedByExtLanguageId)
    {
        if ($this->getLocalizationsRelatedByExtLanguageId()->contains($localizationRelatedByExtLanguageId)) {
            $pos = $this->collLocalizationsRelatedByExtLanguageId->search($localizationRelatedByExtLanguageId);
            $this->collLocalizationsRelatedByExtLanguageId->remove($pos);
            if (null === $this->localizationsRelatedByExtLanguageIdScheduledForDeletion) {
                $this->localizationsRelatedByExtLanguageIdScheduledForDeletion = clone $this->collLocalizationsRelatedByExtLanguageId;
                $this->localizationsRelatedByExtLanguageIdScheduledForDeletion->clear();
            }
            $this->localizationsRelatedByExtLanguageIdScheduledForDeletion[]= $localizationRelatedByExtLanguageId;
            $localizationRelatedByExtLanguageId->setExtLang(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related LocalizationsRelatedByExtLanguageId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsRelatedByExtLanguageIdJoinParent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('Parent', $joinBehavior);

        return $this->getLocalizationsRelatedByExtLanguageId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related LocalizationsRelatedByExtLanguageId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsRelatedByExtLanguageIdJoinScript(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('Script', $joinBehavior);

        return $this->getLocalizationsRelatedByExtLanguageId($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aParent) {
            $this->aParent->removeSublanguage($this);
        }
        if (null !== $this->aScope) {
            $this->aScope->removeLanguage($this);
        }
        if (null !== $this->aType) {
            $this->aType->removeLanguage($this);
        }
        if (null !== $this->aScript) {
            $this->aScript->removeLanguage($this);
        }
        if (null !== $this->aFamily) {
            $this->aFamily->removeLanguage($this);
        }
        $this->id = null;
        $this->alpha_2 = null;
        $this->alpha_3t = null;
        $this->alpha_3b = null;
        $this->alpha_3 = null;
        $this->parent_id = null;
        $this->macrolanguage_status = null;
        $this->name = null;
        $this->native_name = null;
        $this->collate = null;
        $this->subtag = null;
        $this->prefix = null;
        $this->scope_id = null;
        $this->type_id = null;
        $this->family_id = null;
        $this->default_script_id = null;
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
            if ($this->collSublanguages) {
                foreach ($this->collSublanguages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLocalizationsRelatedByLanguageId) {
                foreach ($this->collLocalizationsRelatedByLanguageId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLocalizationsRelatedByExtLanguageId) {
                foreach ($this->collLocalizationsRelatedByExtLanguageId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collSublanguages = null;
        $this->collLocalizationsRelatedByLanguageId = null;
        $this->collLocalizationsRelatedByExtLanguageId = null;
        $this->aParent = null;
        $this->aScope = null;
        $this->aType = null;
        $this->aScript = null;
        $this->aFamily = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LanguageTableMap::DEFAULT_STRING_FORMAT);
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
