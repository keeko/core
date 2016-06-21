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
use keeko\core\model\Country as ChildCountry;
use keeko\core\model\CountryQuery as ChildCountryQuery;
use keeko\core\model\RegionArea as ChildRegionArea;
use keeko\core\model\RegionAreaQuery as ChildRegionAreaQuery;
use keeko\core\model\RegionType as ChildRegionType;
use keeko\core\model\RegionTypeQuery as ChildRegionTypeQuery;
use keeko\core\model\Subdivision as ChildSubdivision;
use keeko\core\model\SubdivisionQuery as ChildSubdivisionQuery;
use keeko\core\model\Map\RegionTypeTableMap;

/**
 * Base class that represents a row from the 'kk_region_type' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class RegionType implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\RegionTypeTableMap';


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
     * The value for the area_id field.
     * @var        int
     */
    protected $area_id;

    /**
     * @var        ChildRegionArea
     */
    protected $aArea;

    /**
     * @var        ObjectCollection|ChildCountry[] Collection to store aggregation of ChildCountry objects.
     */
    protected $collCountriesRelatedByTypeId;
    protected $collCountriesRelatedByTypeIdPartial;

    /**
     * @var        ObjectCollection|ChildCountry[] Collection to store aggregation of ChildCountry objects.
     */
    protected $collCountriesRelatedBySubtypeId;
    protected $collCountriesRelatedBySubtypeIdPartial;

    /**
     * @var        ObjectCollection|ChildSubdivision[] Collection to store aggregation of ChildSubdivision objects.
     */
    protected $collSubdivisions;
    protected $collSubdivisionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCountry[]
     */
    protected $countriesRelatedByTypeIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCountry[]
     */
    protected $countriesRelatedBySubtypeIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSubdivision[]
     */
    protected $subdivisionsScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\RegionType object.
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
     * Compares this with another <code>RegionType</code> instance.  If
     * <code>obj</code> is an instance of <code>RegionType</code>, delegates to
     * <code>equals(RegionType)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|RegionType The current object, for fluid interface
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
     * Get the [area_id] column value.
     *
     * @return int
     */
    public function getAreaId()
    {
        return $this->area_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\RegionType The current object (for fluent API support)
     */
    protected function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[RegionTypeTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\RegionType The current object (for fluent API support)
     */
    protected function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[RegionTypeTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [area_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\RegionType The current object (for fluent API support)
     */
    protected function setAreaId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->area_id !== $v) {
            $this->area_id = $v;
            $this->modifiedColumns[RegionTypeTableMap::COL_AREA_ID] = true;
        }

        if ($this->aArea !== null && $this->aArea->getId() !== $v) {
            $this->aArea = null;
        }

        return $this;
    } // setAreaId()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : RegionTypeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : RegionTypeTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : RegionTypeTableMap::translateFieldName('AreaId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->area_id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = RegionTypeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\RegionType'), 0, $e);
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
        if ($this->aArea !== null && $this->area_id !== $this->aArea->getId()) {
            $this->aArea = null;
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
        $pos = RegionTypeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAreaId();
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

        if (isset($alreadyDumpedObjects['RegionType'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['RegionType'][$this->hashCode()] = true;
        $keys = RegionTypeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getAreaId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aArea) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'regionArea';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_region_area';
                        break;
                    default:
                        $key = 'RegionArea';
                }

                $result[$key] = $this->aArea->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCountriesRelatedByTypeId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'countries';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_countries';
                        break;
                    default:
                        $key = 'Countries';
                }

                $result[$key] = $this->collCountriesRelatedByTypeId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCountriesRelatedBySubtypeId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'countries';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_countries';
                        break;
                    default:
                        $key = 'Countries';
                }

                $result[$key] = $this->collCountriesRelatedBySubtypeId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSubdivisions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'subdivisions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_subdivisions';
                        break;
                    default:
                        $key = 'Subdivisions';
                }

                $result[$key] = $this->collSubdivisions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $criteria = new Criteria(RegionTypeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(RegionTypeTableMap::COL_ID)) {
            $criteria->add(RegionTypeTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(RegionTypeTableMap::COL_NAME)) {
            $criteria->add(RegionTypeTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(RegionTypeTableMap::COL_AREA_ID)) {
            $criteria->add(RegionTypeTableMap::COL_AREA_ID, $this->area_id);
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
        $criteria = ChildRegionTypeQuery::create();
        $criteria->add(RegionTypeTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\RegionType (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setAreaId($this->getAreaId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCountriesRelatedByTypeId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCountryRelatedByTypeId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCountriesRelatedBySubtypeId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCountryRelatedBySubtypeId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSubdivisions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSubdivision($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\RegionType Clone of current object.
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
     * Declares an association between this object and a ChildRegionArea object.
     *
     * @param  ChildRegionArea $v
     * @return $this|\keeko\core\model\RegionType The current object (for fluent API support)
     * @throws PropelException
     */
    public function setArea(ChildRegionArea $v = null)
    {
        if ($v === null) {
            $this->setAreaId(NULL);
        } else {
            $this->setAreaId($v->getId());
        }

        $this->aArea = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildRegionArea object, it will not be re-added.
        if ($v !== null) {
            $v->addType($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildRegionArea object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildRegionArea The associated ChildRegionArea object.
     * @throws PropelException
     */
    public function getArea(ConnectionInterface $con = null)
    {
        if ($this->aArea === null && ($this->area_id !== null)) {
            $this->aArea = ChildRegionAreaQuery::create()->findPk($this->area_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aArea->addTypes($this);
             */
        }

        return $this->aArea;
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
        if ('CountryRelatedByTypeId' == $relationName) {
            return $this->initCountriesRelatedByTypeId();
        }
        if ('CountryRelatedBySubtypeId' == $relationName) {
            return $this->initCountriesRelatedBySubtypeId();
        }
        if ('Subdivision' == $relationName) {
            return $this->initSubdivisions();
        }
    }

    /**
     * Clears out the collCountriesRelatedByTypeId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCountriesRelatedByTypeId()
     */
    public function clearCountriesRelatedByTypeId()
    {
        $this->collCountriesRelatedByTypeId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCountriesRelatedByTypeId collection loaded partially.
     */
    public function resetPartialCountriesRelatedByTypeId($v = true)
    {
        $this->collCountriesRelatedByTypeIdPartial = $v;
    }

    /**
     * Initializes the collCountriesRelatedByTypeId collection.
     *
     * By default this just sets the collCountriesRelatedByTypeId collection to an empty array (like clearcollCountriesRelatedByTypeId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCountriesRelatedByTypeId($overrideExisting = true)
    {
        if (null !== $this->collCountriesRelatedByTypeId && !$overrideExisting) {
            return;
        }
        $this->collCountriesRelatedByTypeId = new ObjectCollection();
        $this->collCountriesRelatedByTypeId->setModel('\keeko\core\model\Country');
    }

    /**
     * Gets an array of ChildCountry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRegionType is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     * @throws PropelException
     */
    public function getCountriesRelatedByTypeId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCountriesRelatedByTypeIdPartial && !$this->isNew();
        if (null === $this->collCountriesRelatedByTypeId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCountriesRelatedByTypeId) {
                // return empty collection
                $this->initCountriesRelatedByTypeId();
            } else {
                $collCountriesRelatedByTypeId = ChildCountryQuery::create(null, $criteria)
                    ->filterByType($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCountriesRelatedByTypeIdPartial && count($collCountriesRelatedByTypeId)) {
                        $this->initCountriesRelatedByTypeId(false);

                        foreach ($collCountriesRelatedByTypeId as $obj) {
                            if (false == $this->collCountriesRelatedByTypeId->contains($obj)) {
                                $this->collCountriesRelatedByTypeId->append($obj);
                            }
                        }

                        $this->collCountriesRelatedByTypeIdPartial = true;
                    }

                    return $collCountriesRelatedByTypeId;
                }

                if ($partial && $this->collCountriesRelatedByTypeId) {
                    foreach ($this->collCountriesRelatedByTypeId as $obj) {
                        if ($obj->isNew()) {
                            $collCountriesRelatedByTypeId[] = $obj;
                        }
                    }
                }

                $this->collCountriesRelatedByTypeId = $collCountriesRelatedByTypeId;
                $this->collCountriesRelatedByTypeIdPartial = false;
            }
        }

        return $this->collCountriesRelatedByTypeId;
    }

    /**
     * Sets a collection of ChildCountry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $countriesRelatedByTypeId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRegionType The current object (for fluent API support)
     */
    public function setCountriesRelatedByTypeId(Collection $countriesRelatedByTypeId, ConnectionInterface $con = null)
    {
        /** @var ChildCountry[] $countriesRelatedByTypeIdToDelete */
        $countriesRelatedByTypeIdToDelete = $this->getCountriesRelatedByTypeId(new Criteria(), $con)->diff($countriesRelatedByTypeId);


        $this->countriesRelatedByTypeIdScheduledForDeletion = $countriesRelatedByTypeIdToDelete;

        foreach ($countriesRelatedByTypeIdToDelete as $countryRelatedByTypeIdRemoved) {
            $countryRelatedByTypeIdRemoved->setType(null);
        }

        $this->collCountriesRelatedByTypeId = null;
        foreach ($countriesRelatedByTypeId as $countryRelatedByTypeId) {
            $this->addCountryRelatedByTypeId($countryRelatedByTypeId);
        }

        $this->collCountriesRelatedByTypeId = $countriesRelatedByTypeId;
        $this->collCountriesRelatedByTypeIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Country objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Country objects.
     * @throws PropelException
     */
    public function countCountriesRelatedByTypeId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCountriesRelatedByTypeIdPartial && !$this->isNew();
        if (null === $this->collCountriesRelatedByTypeId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCountriesRelatedByTypeId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCountriesRelatedByTypeId());
            }

            $query = ChildCountryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByType($this)
                ->count($con);
        }

        return count($this->collCountriesRelatedByTypeId);
    }

    /**
     * Method called to associate a ChildCountry object to this object
     * through the ChildCountry foreign key attribute.
     *
     * @param  ChildCountry $l ChildCountry
     * @return $this|\keeko\core\model\RegionType The current object (for fluent API support)
     */
    public function addCountryRelatedByTypeId(ChildCountry $l)
    {
        if ($this->collCountriesRelatedByTypeId === null) {
            $this->initCountriesRelatedByTypeId();
            $this->collCountriesRelatedByTypeIdPartial = true;
        }

        if (!$this->collCountriesRelatedByTypeId->contains($l)) {
            $this->doAddCountryRelatedByTypeId($l);
        }

        return $this;
    }

    /**
     * @param ChildCountry $countryRelatedByTypeId The ChildCountry object to add.
     */
    protected function doAddCountryRelatedByTypeId(ChildCountry $countryRelatedByTypeId)
    {
        $this->collCountriesRelatedByTypeId[]= $countryRelatedByTypeId;
        $countryRelatedByTypeId->setType($this);
    }

    /**
     * @param  ChildCountry $countryRelatedByTypeId The ChildCountry object to remove.
     * @return $this|ChildRegionType The current object (for fluent API support)
     */
    public function removeCountryRelatedByTypeId(ChildCountry $countryRelatedByTypeId)
    {
        if ($this->getCountriesRelatedByTypeId()->contains($countryRelatedByTypeId)) {
            $pos = $this->collCountriesRelatedByTypeId->search($countryRelatedByTypeId);
            $this->collCountriesRelatedByTypeId->remove($pos);
            if (null === $this->countriesRelatedByTypeIdScheduledForDeletion) {
                $this->countriesRelatedByTypeIdScheduledForDeletion = clone $this->collCountriesRelatedByTypeId;
                $this->countriesRelatedByTypeIdScheduledForDeletion->clear();
            }
            $this->countriesRelatedByTypeIdScheduledForDeletion[]= clone $countryRelatedByTypeId;
            $countryRelatedByTypeId->setType(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this RegionType is new, it will return
     * an empty collection; or if this RegionType has previously
     * been saved, it will retrieve related CountriesRelatedByTypeId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in RegionType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesRelatedByTypeIdJoinContinent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Continent', $joinBehavior);

        return $this->getCountriesRelatedByTypeId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this RegionType is new, it will return
     * an empty collection; or if this RegionType has previously
     * been saved, it will retrieve related CountriesRelatedByTypeId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in RegionType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesRelatedByTypeIdJoinCurrency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Currency', $joinBehavior);

        return $this->getCountriesRelatedByTypeId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this RegionType is new, it will return
     * an empty collection; or if this RegionType has previously
     * been saved, it will retrieve related CountriesRelatedByTypeId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in RegionType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesRelatedByTypeIdJoinCountryRelatedBySovereignityId(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('CountryRelatedBySovereignityId', $joinBehavior);

        return $this->getCountriesRelatedByTypeId($query, $con);
    }

    /**
     * Clears out the collCountriesRelatedBySubtypeId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCountriesRelatedBySubtypeId()
     */
    public function clearCountriesRelatedBySubtypeId()
    {
        $this->collCountriesRelatedBySubtypeId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCountriesRelatedBySubtypeId collection loaded partially.
     */
    public function resetPartialCountriesRelatedBySubtypeId($v = true)
    {
        $this->collCountriesRelatedBySubtypeIdPartial = $v;
    }

    /**
     * Initializes the collCountriesRelatedBySubtypeId collection.
     *
     * By default this just sets the collCountriesRelatedBySubtypeId collection to an empty array (like clearcollCountriesRelatedBySubtypeId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCountriesRelatedBySubtypeId($overrideExisting = true)
    {
        if (null !== $this->collCountriesRelatedBySubtypeId && !$overrideExisting) {
            return;
        }
        $this->collCountriesRelatedBySubtypeId = new ObjectCollection();
        $this->collCountriesRelatedBySubtypeId->setModel('\keeko\core\model\Country');
    }

    /**
     * Gets an array of ChildCountry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRegionType is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     * @throws PropelException
     */
    public function getCountriesRelatedBySubtypeId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCountriesRelatedBySubtypeIdPartial && !$this->isNew();
        if (null === $this->collCountriesRelatedBySubtypeId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCountriesRelatedBySubtypeId) {
                // return empty collection
                $this->initCountriesRelatedBySubtypeId();
            } else {
                $collCountriesRelatedBySubtypeId = ChildCountryQuery::create(null, $criteria)
                    ->filterBySubtype($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCountriesRelatedBySubtypeIdPartial && count($collCountriesRelatedBySubtypeId)) {
                        $this->initCountriesRelatedBySubtypeId(false);

                        foreach ($collCountriesRelatedBySubtypeId as $obj) {
                            if (false == $this->collCountriesRelatedBySubtypeId->contains($obj)) {
                                $this->collCountriesRelatedBySubtypeId->append($obj);
                            }
                        }

                        $this->collCountriesRelatedBySubtypeIdPartial = true;
                    }

                    return $collCountriesRelatedBySubtypeId;
                }

                if ($partial && $this->collCountriesRelatedBySubtypeId) {
                    foreach ($this->collCountriesRelatedBySubtypeId as $obj) {
                        if ($obj->isNew()) {
                            $collCountriesRelatedBySubtypeId[] = $obj;
                        }
                    }
                }

                $this->collCountriesRelatedBySubtypeId = $collCountriesRelatedBySubtypeId;
                $this->collCountriesRelatedBySubtypeIdPartial = false;
            }
        }

        return $this->collCountriesRelatedBySubtypeId;
    }

    /**
     * Sets a collection of ChildCountry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $countriesRelatedBySubtypeId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRegionType The current object (for fluent API support)
     */
    public function setCountriesRelatedBySubtypeId(Collection $countriesRelatedBySubtypeId, ConnectionInterface $con = null)
    {
        /** @var ChildCountry[] $countriesRelatedBySubtypeIdToDelete */
        $countriesRelatedBySubtypeIdToDelete = $this->getCountriesRelatedBySubtypeId(new Criteria(), $con)->diff($countriesRelatedBySubtypeId);


        $this->countriesRelatedBySubtypeIdScheduledForDeletion = $countriesRelatedBySubtypeIdToDelete;

        foreach ($countriesRelatedBySubtypeIdToDelete as $countryRelatedBySubtypeIdRemoved) {
            $countryRelatedBySubtypeIdRemoved->setSubtype(null);
        }

        $this->collCountriesRelatedBySubtypeId = null;
        foreach ($countriesRelatedBySubtypeId as $countryRelatedBySubtypeId) {
            $this->addCountryRelatedBySubtypeId($countryRelatedBySubtypeId);
        }

        $this->collCountriesRelatedBySubtypeId = $countriesRelatedBySubtypeId;
        $this->collCountriesRelatedBySubtypeIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Country objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Country objects.
     * @throws PropelException
     */
    public function countCountriesRelatedBySubtypeId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCountriesRelatedBySubtypeIdPartial && !$this->isNew();
        if (null === $this->collCountriesRelatedBySubtypeId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCountriesRelatedBySubtypeId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCountriesRelatedBySubtypeId());
            }

            $query = ChildCountryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySubtype($this)
                ->count($con);
        }

        return count($this->collCountriesRelatedBySubtypeId);
    }

    /**
     * Method called to associate a ChildCountry object to this object
     * through the ChildCountry foreign key attribute.
     *
     * @param  ChildCountry $l ChildCountry
     * @return $this|\keeko\core\model\RegionType The current object (for fluent API support)
     */
    public function addCountryRelatedBySubtypeId(ChildCountry $l)
    {
        if ($this->collCountriesRelatedBySubtypeId === null) {
            $this->initCountriesRelatedBySubtypeId();
            $this->collCountriesRelatedBySubtypeIdPartial = true;
        }

        if (!$this->collCountriesRelatedBySubtypeId->contains($l)) {
            $this->doAddCountryRelatedBySubtypeId($l);
        }

        return $this;
    }

    /**
     * @param ChildCountry $countryRelatedBySubtypeId The ChildCountry object to add.
     */
    protected function doAddCountryRelatedBySubtypeId(ChildCountry $countryRelatedBySubtypeId)
    {
        $this->collCountriesRelatedBySubtypeId[]= $countryRelatedBySubtypeId;
        $countryRelatedBySubtypeId->setSubtype($this);
    }

    /**
     * @param  ChildCountry $countryRelatedBySubtypeId The ChildCountry object to remove.
     * @return $this|ChildRegionType The current object (for fluent API support)
     */
    public function removeCountryRelatedBySubtypeId(ChildCountry $countryRelatedBySubtypeId)
    {
        if ($this->getCountriesRelatedBySubtypeId()->contains($countryRelatedBySubtypeId)) {
            $pos = $this->collCountriesRelatedBySubtypeId->search($countryRelatedBySubtypeId);
            $this->collCountriesRelatedBySubtypeId->remove($pos);
            if (null === $this->countriesRelatedBySubtypeIdScheduledForDeletion) {
                $this->countriesRelatedBySubtypeIdScheduledForDeletion = clone $this->collCountriesRelatedBySubtypeId;
                $this->countriesRelatedBySubtypeIdScheduledForDeletion->clear();
            }
            $this->countriesRelatedBySubtypeIdScheduledForDeletion[]= $countryRelatedBySubtypeId;
            $countryRelatedBySubtypeId->setSubtype(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this RegionType is new, it will return
     * an empty collection; or if this RegionType has previously
     * been saved, it will retrieve related CountriesRelatedBySubtypeId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in RegionType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesRelatedBySubtypeIdJoinContinent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Continent', $joinBehavior);

        return $this->getCountriesRelatedBySubtypeId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this RegionType is new, it will return
     * an empty collection; or if this RegionType has previously
     * been saved, it will retrieve related CountriesRelatedBySubtypeId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in RegionType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesRelatedBySubtypeIdJoinCurrency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Currency', $joinBehavior);

        return $this->getCountriesRelatedBySubtypeId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this RegionType is new, it will return
     * an empty collection; or if this RegionType has previously
     * been saved, it will retrieve related CountriesRelatedBySubtypeId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in RegionType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesRelatedBySubtypeIdJoinCountryRelatedBySovereignityId(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('CountryRelatedBySovereignityId', $joinBehavior);

        return $this->getCountriesRelatedBySubtypeId($query, $con);
    }

    /**
     * Clears out the collSubdivisions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSubdivisions()
     */
    public function clearSubdivisions()
    {
        $this->collSubdivisions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSubdivisions collection loaded partially.
     */
    public function resetPartialSubdivisions($v = true)
    {
        $this->collSubdivisionsPartial = $v;
    }

    /**
     * Initializes the collSubdivisions collection.
     *
     * By default this just sets the collSubdivisions collection to an empty array (like clearcollSubdivisions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSubdivisions($overrideExisting = true)
    {
        if (null !== $this->collSubdivisions && !$overrideExisting) {
            return;
        }
        $this->collSubdivisions = new ObjectCollection();
        $this->collSubdivisions->setModel('\keeko\core\model\Subdivision');
    }

    /**
     * Gets an array of ChildSubdivision objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRegionType is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSubdivision[] List of ChildSubdivision objects
     * @throws PropelException
     */
    public function getSubdivisions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSubdivisionsPartial && !$this->isNew();
        if (null === $this->collSubdivisions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSubdivisions) {
                // return empty collection
                $this->initSubdivisions();
            } else {
                $collSubdivisions = ChildSubdivisionQuery::create(null, $criteria)
                    ->filterByType($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSubdivisionsPartial && count($collSubdivisions)) {
                        $this->initSubdivisions(false);

                        foreach ($collSubdivisions as $obj) {
                            if (false == $this->collSubdivisions->contains($obj)) {
                                $this->collSubdivisions->append($obj);
                            }
                        }

                        $this->collSubdivisionsPartial = true;
                    }

                    return $collSubdivisions;
                }

                if ($partial && $this->collSubdivisions) {
                    foreach ($this->collSubdivisions as $obj) {
                        if ($obj->isNew()) {
                            $collSubdivisions[] = $obj;
                        }
                    }
                }

                $this->collSubdivisions = $collSubdivisions;
                $this->collSubdivisionsPartial = false;
            }
        }

        return $this->collSubdivisions;
    }

    /**
     * Sets a collection of ChildSubdivision objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $subdivisions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRegionType The current object (for fluent API support)
     */
    public function setSubdivisions(Collection $subdivisions, ConnectionInterface $con = null)
    {
        /** @var ChildSubdivision[] $subdivisionsToDelete */
        $subdivisionsToDelete = $this->getSubdivisions(new Criteria(), $con)->diff($subdivisions);


        $this->subdivisionsScheduledForDeletion = $subdivisionsToDelete;

        foreach ($subdivisionsToDelete as $subdivisionRemoved) {
            $subdivisionRemoved->setType(null);
        }

        $this->collSubdivisions = null;
        foreach ($subdivisions as $subdivision) {
            $this->addSubdivision($subdivision);
        }

        $this->collSubdivisions = $subdivisions;
        $this->collSubdivisionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Subdivision objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Subdivision objects.
     * @throws PropelException
     */
    public function countSubdivisions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSubdivisionsPartial && !$this->isNew();
        if (null === $this->collSubdivisions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSubdivisions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSubdivisions());
            }

            $query = ChildSubdivisionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByType($this)
                ->count($con);
        }

        return count($this->collSubdivisions);
    }

    /**
     * Method called to associate a ChildSubdivision object to this object
     * through the ChildSubdivision foreign key attribute.
     *
     * @param  ChildSubdivision $l ChildSubdivision
     * @return $this|\keeko\core\model\RegionType The current object (for fluent API support)
     */
    public function addSubdivision(ChildSubdivision $l)
    {
        if ($this->collSubdivisions === null) {
            $this->initSubdivisions();
            $this->collSubdivisionsPartial = true;
        }

        if (!$this->collSubdivisions->contains($l)) {
            $this->doAddSubdivision($l);
        }

        return $this;
    }

    /**
     * @param ChildSubdivision $subdivision The ChildSubdivision object to add.
     */
    protected function doAddSubdivision(ChildSubdivision $subdivision)
    {
        $this->collSubdivisions[]= $subdivision;
        $subdivision->setType($this);
    }

    /**
     * @param  ChildSubdivision $subdivision The ChildSubdivision object to remove.
     * @return $this|ChildRegionType The current object (for fluent API support)
     */
    public function removeSubdivision(ChildSubdivision $subdivision)
    {
        if ($this->getSubdivisions()->contains($subdivision)) {
            $pos = $this->collSubdivisions->search($subdivision);
            $this->collSubdivisions->remove($pos);
            if (null === $this->subdivisionsScheduledForDeletion) {
                $this->subdivisionsScheduledForDeletion = clone $this->collSubdivisions;
                $this->subdivisionsScheduledForDeletion->clear();
            }
            $this->subdivisionsScheduledForDeletion[]= clone $subdivision;
            $subdivision->setType(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this RegionType is new, it will return
     * an empty collection; or if this RegionType has previously
     * been saved, it will retrieve related Subdivisions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in RegionType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSubdivision[] List of ChildSubdivision objects
     */
    public function getSubdivisionsJoinCountry(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSubdivisionQuery::create(null, $criteria);
        $query->joinWith('Country', $joinBehavior);

        return $this->getSubdivisions($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aArea) {
            $this->aArea->removeType($this);
        }
        $this->id = null;
        $this->name = null;
        $this->area_id = null;
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
            if ($this->collCountriesRelatedByTypeId) {
                foreach ($this->collCountriesRelatedByTypeId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCountriesRelatedBySubtypeId) {
                foreach ($this->collCountriesRelatedBySubtypeId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSubdivisions) {
                foreach ($this->collSubdivisions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCountriesRelatedByTypeId = null;
        $this->collCountriesRelatedBySubtypeId = null;
        $this->collSubdivisions = null;
        $this->aArea = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RegionTypeTableMap::DEFAULT_STRING_FORMAT);
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
