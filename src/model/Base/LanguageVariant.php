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
use keeko\core\model\LanguageVariant as ChildLanguageVariant;
use keeko\core\model\LanguageVariantQuery as ChildLanguageVariantQuery;
use keeko\core\model\Localization as ChildLocalization;
use keeko\core\model\LocalizationQuery as ChildLocalizationQuery;
use keeko\core\model\LocalizationVariant as ChildLocalizationVariant;
use keeko\core\model\LocalizationVariantQuery as ChildLocalizationVariantQuery;
use keeko\core\model\Map\LanguageVariantTableMap;

/**
 * Base class that represents a row from the 'kk_language_variant' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class LanguageVariant implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\LanguageVariantTableMap';


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
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the subtag field.
     * @var        string
     */
    protected $subtag;

    /**
     * The value for the prefixes field.
     * @var        string
     */
    protected $prefixes;

    /**
     * The value for the comment field.
     * @var        string
     */
    protected $comment;

    /**
     * @var        ObjectCollection|ChildLocalizationVariant[] Collection to store aggregation of ChildLocalizationVariant objects.
     */
    protected $collLocalizationVariants;
    protected $collLocalizationVariantsPartial;

    /**
     * @var        ObjectCollection|ChildLocalization[] Cross Collection to store aggregation of ChildLocalization objects.
     */
    protected $collLocalizations;

    /**
     * @var bool
     */
    protected $collLocalizationsPartial;

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
    protected $localizationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocalizationVariant[]
     */
    protected $localizationVariantsScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\LanguageVariant object.
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
     * Compares this with another <code>LanguageVariant</code> instance.  If
     * <code>obj</code> is an instance of <code>LanguageVariant</code>, delegates to
     * <code>equals(LanguageVariant)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|LanguageVariant The current object, for fluid interface
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [subtag] column value.
     *
     * @return string
     */
    public function getSubtag()
    {
        return $this->subtag;
    }

    /**
     * Get the [prefixes] column value.
     *
     * @return string
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * Get the [comment] column value.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\LanguageVariant The current object (for fluent API support)
     */
    protected function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LanguageVariantTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\LanguageVariant The current object (for fluent API support)
     */
    protected function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[LanguageVariantTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [subtag] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\LanguageVariant The current object (for fluent API support)
     */
    protected function setSubtag($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->subtag !== $v) {
            $this->subtag = $v;
            $this->modifiedColumns[LanguageVariantTableMap::COL_SUBTAG] = true;
        }

        return $this;
    } // setSubtag()

    /**
     * Set the value of [prefixes] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\LanguageVariant The current object (for fluent API support)
     */
    protected function setPrefixes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->prefixes !== $v) {
            $this->prefixes = $v;
            $this->modifiedColumns[LanguageVariantTableMap::COL_PREFIXES] = true;
        }

        return $this;
    } // setPrefixes()

    /**
     * Set the value of [comment] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\LanguageVariant The current object (for fluent API support)
     */
    protected function setComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[LanguageVariantTableMap::COL_COMMENT] = true;
        }

        return $this;
    } // setComment()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LanguageVariantTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LanguageVariantTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LanguageVariantTableMap::translateFieldName('Subtag', TableMap::TYPE_PHPNAME, $indexType)];
            $this->subtag = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LanguageVariantTableMap::translateFieldName('Prefixes', TableMap::TYPE_PHPNAME, $indexType)];
            $this->prefixes = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LanguageVariantTableMap::translateFieldName('Comment', TableMap::TYPE_PHPNAME, $indexType)];
            $this->comment = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = LanguageVariantTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\LanguageVariant'), 0, $e);
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
        $pos = LanguageVariantTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getSubtag();
                break;
            case 3:
                return $this->getPrefixes();
                break;
            case 4:
                return $this->getComment();
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

        if (isset($alreadyDumpedObjects['LanguageVariant'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['LanguageVariant'][$this->hashCode()] = true;
        $keys = LanguageVariantTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getSubtag(),
            $keys[3] => $this->getPrefixes(),
            $keys[4] => $this->getComment(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
        $criteria = new Criteria(LanguageVariantTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LanguageVariantTableMap::COL_ID)) {
            $criteria->add(LanguageVariantTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LanguageVariantTableMap::COL_NAME)) {
            $criteria->add(LanguageVariantTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(LanguageVariantTableMap::COL_SUBTAG)) {
            $criteria->add(LanguageVariantTableMap::COL_SUBTAG, $this->subtag);
        }
        if ($this->isColumnModified(LanguageVariantTableMap::COL_PREFIXES)) {
            $criteria->add(LanguageVariantTableMap::COL_PREFIXES, $this->prefixes);
        }
        if ($this->isColumnModified(LanguageVariantTableMap::COL_COMMENT)) {
            $criteria->add(LanguageVariantTableMap::COL_COMMENT, $this->comment);
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
        $criteria = ChildLanguageVariantQuery::create();
        $criteria->add(LanguageVariantTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\LanguageVariant (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setSubtag($this->getSubtag());
        $copyObj->setPrefixes($this->getPrefixes());
        $copyObj->setComment($this->getComment());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getLocalizationVariants() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLocalizationVariant($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\LanguageVariant Clone of current object.
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
        if ('LocalizationVariant' == $relationName) {
            return $this->initLocalizationVariants();
        }
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
     * If this ChildLanguageVariant is new, it will return
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
                    ->filterByLanguageVariant($this)
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
     * @return $this|ChildLanguageVariant The current object (for fluent API support)
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
            $localizationVariantRemoved->setLanguageVariant(null);
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
                ->filterByLanguageVariant($this)
                ->count($con);
        }

        return count($this->collLocalizationVariants);
    }

    /**
     * Method called to associate a ChildLocalizationVariant object to this object
     * through the ChildLocalizationVariant foreign key attribute.
     *
     * @param  ChildLocalizationVariant $l ChildLocalizationVariant
     * @return $this|\keeko\core\model\LanguageVariant The current object (for fluent API support)
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
        $localizationVariant->setLanguageVariant($this);
    }

    /**
     * @param  ChildLocalizationVariant $localizationVariant The ChildLocalizationVariant object to remove.
     * @return $this|ChildLanguageVariant The current object (for fluent API support)
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
            $localizationVariant->setLanguageVariant(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LanguageVariant is new, it will return
     * an empty collection; or if this LanguageVariant has previously
     * been saved, it will retrieve related LocalizationVariants from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LanguageVariant.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalizationVariant[] List of ChildLocalizationVariant objects
     */
    public function getLocalizationVariantsJoinLocalization(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationVariantQuery::create(null, $criteria);
        $query->joinWith('Localization', $joinBehavior);

        return $this->getLocalizationVariants($query, $con);
    }

    /**
     * Clears out the collLocalizations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLocalizations()
     */
    public function clearLocalizations()
    {
        $this->collLocalizations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collLocalizations crossRef collection.
     *
     * By default this just sets the collLocalizations collection to an empty collection (like clearLocalizations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initLocalizations()
    {
        $this->collLocalizations = new ObjectCollection();
        $this->collLocalizationsPartial = true;

        $this->collLocalizations->setModel('\keeko\core\model\Localization');
    }

    /**
     * Checks if the collLocalizations collection is loaded.
     *
     * @return bool
     */
    public function isLocalizationsLoaded()
    {
        return null !== $this->collLocalizations;
    }

    /**
     * Gets a collection of ChildLocalization objects related by a many-to-many relationship
     * to the current object by way of the kk_localization_variant cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLanguageVariant is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsPartial && !$this->isNew();
        if (null === $this->collLocalizations || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collLocalizations) {
                    $this->initLocalizations();
                }
            } else {

                $query = ChildLocalizationQuery::create(null, $criteria)
                    ->filterByLanguageVariant($this);
                $collLocalizations = $query->find($con);
                if (null !== $criteria) {
                    return $collLocalizations;
                }

                if ($partial && $this->collLocalizations) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collLocalizations as $obj) {
                        if (!$collLocalizations->contains($obj)) {
                            $collLocalizations[] = $obj;
                        }
                    }
                }

                $this->collLocalizations = $collLocalizations;
                $this->collLocalizationsPartial = false;
            }
        }

        return $this->collLocalizations;
    }

    /**
     * Sets a collection of Localization objects related by a many-to-many relationship
     * to the current object by way of the kk_localization_variant cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $localizations A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildLanguageVariant The current object (for fluent API support)
     */
    public function setLocalizations(Collection $localizations, ConnectionInterface $con = null)
    {
        $this->clearLocalizations();
        $currentLocalizations = $this->getLocalizations();

        $localizationsScheduledForDeletion = $currentLocalizations->diff($localizations);

        foreach ($localizationsScheduledForDeletion as $toDelete) {
            $this->removeLocalization($toDelete);
        }

        foreach ($localizations as $localization) {
            if (!$currentLocalizations->contains($localization)) {
                $this->doAddLocalization($localization);
            }
        }

        $this->collLocalizationsPartial = false;
        $this->collLocalizations = $localizations;

        return $this;
    }

    /**
     * Gets the number of Localization objects related by a many-to-many relationship
     * to the current object by way of the kk_localization_variant cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Localization objects
     */
    public function countLocalizations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsPartial && !$this->isNew();
        if (null === $this->collLocalizations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLocalizations) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getLocalizations());
                }

                $query = ChildLocalizationQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLanguageVariant($this)
                    ->count($con);
            }
        } else {
            return count($this->collLocalizations);
        }
    }

    /**
     * Associate a ChildLocalization to this object
     * through the kk_localization_variant cross reference table.
     *
     * @param ChildLocalization $localization
     * @return ChildLanguageVariant The current object (for fluent API support)
     */
    public function addLocalization(ChildLocalization $localization)
    {
        if ($this->collLocalizations === null) {
            $this->initLocalizations();
        }

        if (!$this->getLocalizations()->contains($localization)) {
            // only add it if the **same** object is not already associated
            $this->collLocalizations->push($localization);
            $this->doAddLocalization($localization);
        }

        return $this;
    }

    /**
     *
     * @param ChildLocalization $localization
     */
    protected function doAddLocalization(ChildLocalization $localization)
    {
        $localizationVariant = new ChildLocalizationVariant();

        $localizationVariant->setLocalization($localization);

        $localizationVariant->setLanguageVariant($this);

        $this->addLocalizationVariant($localizationVariant);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$localization->isLanguageVariantsLoaded()) {
            $localization->initLanguageVariants();
            $localization->getLanguageVariants()->push($this);
        } elseif (!$localization->getLanguageVariants()->contains($this)) {
            $localization->getLanguageVariants()->push($this);
        }

    }

    /**
     * Remove localization of this object
     * through the kk_localization_variant cross reference table.
     *
     * @param ChildLocalization $localization
     * @return ChildLanguageVariant The current object (for fluent API support)
     */
    public function removeLocalization(ChildLocalization $localization)
    {
        if ($this->getLocalizations()->contains($localization)) { $localizationVariant = new ChildLocalizationVariant();

            $localizationVariant->setLocalization($localization);
            if ($localization->isLanguageVariantsLoaded()) {
                //remove the back reference if available
                $localization->getLanguageVariants()->removeObject($this);
            }

            $localizationVariant->setLanguageVariant($this);
            $this->removeLocalizationVariant(clone $localizationVariant);
            $localizationVariant->clear();

            $this->collLocalizations->remove($this->collLocalizations->search($localization));

            if (null === $this->localizationsScheduledForDeletion) {
                $this->localizationsScheduledForDeletion = clone $this->collLocalizations;
                $this->localizationsScheduledForDeletion->clear();
            }

            $this->localizationsScheduledForDeletion->push($localization);
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
        $this->name = null;
        $this->subtag = null;
        $this->prefixes = null;
        $this->comment = null;
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
            if ($this->collLocalizationVariants) {
                foreach ($this->collLocalizationVariants as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLocalizations) {
                foreach ($this->collLocalizations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLocalizationVariants = null;
        $this->collLocalizations = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LanguageVariantTableMap::DEFAULT_STRING_FORMAT);
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
