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
use keeko\core\model\Continent as ChildContinent;
use keeko\core\model\ContinentQuery as ChildContinentQuery;
use keeko\core\model\Country as ChildCountry;
use keeko\core\model\CountryQuery as ChildCountryQuery;
use keeko\core\model\Currency as ChildCurrency;
use keeko\core\model\CurrencyQuery as ChildCurrencyQuery;
use keeko\core\model\RegionType as ChildRegionType;
use keeko\core\model\RegionTypeQuery as ChildRegionTypeQuery;
use keeko\core\model\Subdivision as ChildSubdivision;
use keeko\core\model\SubdivisionQuery as ChildSubdivisionQuery;
use keeko\core\model\Map\CountryTableMap;

/**
 * Base class that represents a row from the 'kk_country' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Country implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\CountryTableMap';


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
     * The value for the numeric field.
     * @var        int
     */
    protected $numeric;

    /**
     * The value for the alpha_2 field.
     * @var        string
     */
    protected $alpha_2;

    /**
     * The value for the alpha_3 field.
     * @var        string
     */
    protected $alpha_3;

    /**
     * The value for the short_name field.
     * @var        string
     */
    protected $short_name;

    /**
     * The value for the ioc field.
     * @var        string
     */
    protected $ioc;

    /**
     * The value for the tld field.
     * @var        string
     */
    protected $tld;

    /**
     * The value for the phone field.
     * @var        string
     */
    protected $phone;

    /**
     * The value for the capital field.
     * @var        string
     */
    protected $capital;

    /**
     * The value for the postal_code_format field.
     * @var        string
     */
    protected $postal_code_format;

    /**
     * The value for the postal_code_regex field.
     * @var        string
     */
    protected $postal_code_regex;

    /**
     * The value for the continent_id field.
     * @var        int
     */
    protected $continent_id;

    /**
     * The value for the currency_id field.
     * @var        int
     */
    protected $currency_id;

    /**
     * The value for the type_id field.
     * @var        int
     */
    protected $type_id;

    /**
     * The value for the subtype_id field.
     * @var        int
     */
    protected $subtype_id;

    /**
     * The value for the sovereignity_id field.
     * @var        int
     */
    protected $sovereignity_id;

    /**
     * The value for the formal_name field.
     * @var        string
     */
    protected $formal_name;

    /**
     * The value for the formal_native_name field.
     * @var        string
     */
    protected $formal_native_name;

    /**
     * The value for the short_native_name field.
     * @var        string
     */
    protected $short_native_name;

    /**
     * The value for the bbox_sw_lat field.
     * @var        double
     */
    protected $bbox_sw_lat;

    /**
     * The value for the bbox_sw_lng field.
     * @var        double
     */
    protected $bbox_sw_lng;

    /**
     * The value for the bbox_ne_lat field.
     * @var        double
     */
    protected $bbox_ne_lat;

    /**
     * The value for the bbox_ne_lng field.
     * @var        double
     */
    protected $bbox_ne_lng;

    /**
     * @var        ChildContinent
     */
    protected $aContinent;

    /**
     * @var        ChildCurrency
     */
    protected $aCurrency;

    /**
     * @var        ChildRegionType
     */
    protected $aType;

    /**
     * @var        ChildRegionType
     */
    protected $aSubtype;

    /**
     * @var        ChildCountry
     */
    protected $aCountryRelatedBySovereignityId;

    /**
     * @var        ObjectCollection|ChildCountry[] Collection to store aggregation of ChildCountry objects.
     */
    protected $collSubordinates;
    protected $collSubordinatesPartial;

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
    protected $subordinatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSubdivision[]
     */
    protected $subdivisionsScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\Country object.
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
     * Compares this with another <code>Country</code> instance.  If
     * <code>obj</code> is an instance of <code>Country</code>, delegates to
     * <code>equals(Country)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Country The current object, for fluid interface
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
     * Get the [numeric] column value.
     *
     * @return int
     */
    public function getNumeric()
    {
        return $this->numeric;
    }

    /**
     * Get the [alpha_2] column value.
     *
     * @return string
     */
    public function getAlpha2()
    {
        return $this->alpha_2;
    }

    /**
     * Get the [alpha_3] column value.
     *
     * @return string
     */
    public function getAlpha3()
    {
        return $this->alpha_3;
    }

    /**
     * Get the [short_name] column value.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * Get the [ioc] column value.
     *
     * @return string
     */
    public function getIoc()
    {
        return $this->ioc;
    }

    /**
     * Get the [tld] column value.
     *
     * @return string
     */
    public function getTld()
    {
        return $this->tld;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get the [capital] column value.
     *
     * @return string
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * Get the [postal_code_format] column value.
     *
     * @return string
     */
    public function getPostalCodeFormat()
    {
        return $this->postal_code_format;
    }

    /**
     * Get the [postal_code_regex] column value.
     *
     * @return string
     */
    public function getPostalCodeRegex()
    {
        return $this->postal_code_regex;
    }

    /**
     * Get the [continent_id] column value.
     *
     * @return int
     */
    public function getContinentId()
    {
        return $this->continent_id;
    }

    /**
     * Get the [currency_id] column value.
     *
     * @return int
     */
    public function getCurrencyId()
    {
        return $this->currency_id;
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
     * Get the [subtype_id] column value.
     *
     * @return int
     */
    public function getSubtypeId()
    {
        return $this->subtype_id;
    }

    /**
     * Get the [sovereignity_id] column value.
     *
     * @return int
     */
    public function getSovereignityId()
    {
        return $this->sovereignity_id;
    }

    /**
     * Get the [formal_name] column value.
     *
     * @return string
     */
    public function getFormalName()
    {
        return $this->formal_name;
    }

    /**
     * Get the [formal_native_name] column value.
     *
     * @return string
     */
    public function getFormalNativeName()
    {
        return $this->formal_native_name;
    }

    /**
     * Get the [short_native_name] column value.
     *
     * @return string
     */
    public function getShortNativeName()
    {
        return $this->short_native_name;
    }

    /**
     * Get the [bbox_sw_lat] column value.
     *
     * @return double
     */
    public function getBboxSwLat()
    {
        return $this->bbox_sw_lat;
    }

    /**
     * Get the [bbox_sw_lng] column value.
     *
     * @return double
     */
    public function getBboxSwLng()
    {
        return $this->bbox_sw_lng;
    }

    /**
     * Get the [bbox_ne_lat] column value.
     *
     * @return double
     */
    public function getBboxNeLat()
    {
        return $this->bbox_ne_lat;
    }

    /**
     * Get the [bbox_ne_lng] column value.
     *
     * @return double
     */
    public function getBboxNeLng()
    {
        return $this->bbox_ne_lng;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CountryTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [numeric] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setNumeric($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->numeric !== $v) {
            $this->numeric = $v;
            $this->modifiedColumns[CountryTableMap::COL_NUMERIC] = true;
        }

        return $this;
    } // setNumeric()

    /**
     * Set the value of [alpha_2] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setAlpha2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alpha_2 !== $v) {
            $this->alpha_2 = $v;
            $this->modifiedColumns[CountryTableMap::COL_ALPHA_2] = true;
        }

        return $this;
    } // setAlpha2()

    /**
     * Set the value of [alpha_3] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setAlpha3($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alpha_3 !== $v) {
            $this->alpha_3 = $v;
            $this->modifiedColumns[CountryTableMap::COL_ALPHA_3] = true;
        }

        return $this;
    } // setAlpha3()

    /**
     * Set the value of [short_name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setShortName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->short_name !== $v) {
            $this->short_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_SHORT_NAME] = true;
        }

        return $this;
    } // setShortName()

    /**
     * Set the value of [ioc] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setIoc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ioc !== $v) {
            $this->ioc = $v;
            $this->modifiedColumns[CountryTableMap::COL_IOC] = true;
        }

        return $this;
    } // setIoc()

    /**
     * Set the value of [tld] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setTld($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->tld !== $v) {
            $this->tld = $v;
            $this->modifiedColumns[CountryTableMap::COL_TLD] = true;
        }

        return $this;
    } // setTld()

    /**
     * Set the value of [phone] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[CountryTableMap::COL_PHONE] = true;
        }

        return $this;
    } // setPhone()

    /**
     * Set the value of [capital] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setCapital($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->capital !== $v) {
            $this->capital = $v;
            $this->modifiedColumns[CountryTableMap::COL_CAPITAL] = true;
        }

        return $this;
    } // setCapital()

    /**
     * Set the value of [postal_code_format] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setPostalCodeFormat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->postal_code_format !== $v) {
            $this->postal_code_format = $v;
            $this->modifiedColumns[CountryTableMap::COL_POSTAL_CODE_FORMAT] = true;
        }

        return $this;
    } // setPostalCodeFormat()

    /**
     * Set the value of [postal_code_regex] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setPostalCodeRegex($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->postal_code_regex !== $v) {
            $this->postal_code_regex = $v;
            $this->modifiedColumns[CountryTableMap::COL_POSTAL_CODE_REGEX] = true;
        }

        return $this;
    } // setPostalCodeRegex()

    /**
     * Set the value of [continent_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setContinentId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->continent_id !== $v) {
            $this->continent_id = $v;
            $this->modifiedColumns[CountryTableMap::COL_CONTINENT_ID] = true;
        }

        if ($this->aContinent !== null && $this->aContinent->getId() !== $v) {
            $this->aContinent = null;
        }

        return $this;
    } // setContinentId()

    /**
     * Set the value of [currency_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setCurrencyId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->currency_id !== $v) {
            $this->currency_id = $v;
            $this->modifiedColumns[CountryTableMap::COL_CURRENCY_ID] = true;
        }

        if ($this->aCurrency !== null && $this->aCurrency->getId() !== $v) {
            $this->aCurrency = null;
        }

        return $this;
    } // setCurrencyId()

    /**
     * Set the value of [type_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type_id !== $v) {
            $this->type_id = $v;
            $this->modifiedColumns[CountryTableMap::COL_TYPE_ID] = true;
        }

        if ($this->aType !== null && $this->aType->getId() !== $v) {
            $this->aType = null;
        }

        return $this;
    } // setTypeId()

    /**
     * Set the value of [subtype_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setSubtypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->subtype_id !== $v) {
            $this->subtype_id = $v;
            $this->modifiedColumns[CountryTableMap::COL_SUBTYPE_ID] = true;
        }

        if ($this->aSubtype !== null && $this->aSubtype->getId() !== $v) {
            $this->aSubtype = null;
        }

        return $this;
    } // setSubtypeId()

    /**
     * Set the value of [sovereignity_id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setSovereignityId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sovereignity_id !== $v) {
            $this->sovereignity_id = $v;
            $this->modifiedColumns[CountryTableMap::COL_SOVEREIGNITY_ID] = true;
        }

        if ($this->aCountryRelatedBySovereignityId !== null && $this->aCountryRelatedBySovereignityId->getId() !== $v) {
            $this->aCountryRelatedBySovereignityId = null;
        }

        return $this;
    } // setSovereignityId()

    /**
     * Set the value of [formal_name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setFormalName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->formal_name !== $v) {
            $this->formal_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_FORMAL_NAME] = true;
        }

        return $this;
    } // setFormalName()

    /**
     * Set the value of [formal_native_name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setFormalNativeName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->formal_native_name !== $v) {
            $this->formal_native_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_FORMAL_NATIVE_NAME] = true;
        }

        return $this;
    } // setFormalNativeName()

    /**
     * Set the value of [short_native_name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setShortNativeName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->short_native_name !== $v) {
            $this->short_native_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_SHORT_NATIVE_NAME] = true;
        }

        return $this;
    } // setShortNativeName()

    /**
     * Set the value of [bbox_sw_lat] column.
     *
     * @param double $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setBboxSwLat($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->bbox_sw_lat !== $v) {
            $this->bbox_sw_lat = $v;
            $this->modifiedColumns[CountryTableMap::COL_BBOX_SW_LAT] = true;
        }

        return $this;
    } // setBboxSwLat()

    /**
     * Set the value of [bbox_sw_lng] column.
     *
     * @param double $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setBboxSwLng($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->bbox_sw_lng !== $v) {
            $this->bbox_sw_lng = $v;
            $this->modifiedColumns[CountryTableMap::COL_BBOX_SW_LNG] = true;
        }

        return $this;
    } // setBboxSwLng()

    /**
     * Set the value of [bbox_ne_lat] column.
     *
     * @param double $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setBboxNeLat($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->bbox_ne_lat !== $v) {
            $this->bbox_ne_lat = $v;
            $this->modifiedColumns[CountryTableMap::COL_BBOX_NE_LAT] = true;
        }

        return $this;
    } // setBboxNeLat()

    /**
     * Set the value of [bbox_ne_lng] column.
     *
     * @param double $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    protected function setBboxNeLng($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->bbox_ne_lng !== $v) {
            $this->bbox_ne_lng = $v;
            $this->modifiedColumns[CountryTableMap::COL_BBOX_NE_LNG] = true;
        }

        return $this;
    } // setBboxNeLng()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CountryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CountryTableMap::translateFieldName('Numeric', TableMap::TYPE_PHPNAME, $indexType)];
            $this->numeric = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CountryTableMap::translateFieldName('Alpha2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CountryTableMap::translateFieldName('Alpha3', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_3 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CountryTableMap::translateFieldName('ShortName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->short_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CountryTableMap::translateFieldName('Ioc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ioc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CountryTableMap::translateFieldName('Tld', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tld = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CountryTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CountryTableMap::translateFieldName('Capital', TableMap::TYPE_PHPNAME, $indexType)];
            $this->capital = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CountryTableMap::translateFieldName('PostalCodeFormat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->postal_code_format = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CountryTableMap::translateFieldName('PostalCodeRegex', TableMap::TYPE_PHPNAME, $indexType)];
            $this->postal_code_regex = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CountryTableMap::translateFieldName('ContinentId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->continent_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CountryTableMap::translateFieldName('CurrencyId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->currency_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : CountryTableMap::translateFieldName('TypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : CountryTableMap::translateFieldName('SubtypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->subtype_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : CountryTableMap::translateFieldName('SovereignityId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sovereignity_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : CountryTableMap::translateFieldName('FormalName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->formal_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : CountryTableMap::translateFieldName('FormalNativeName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->formal_native_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : CountryTableMap::translateFieldName('ShortNativeName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->short_native_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : CountryTableMap::translateFieldName('BboxSwLat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bbox_sw_lat = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : CountryTableMap::translateFieldName('BboxSwLng', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bbox_sw_lng = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : CountryTableMap::translateFieldName('BboxNeLat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bbox_ne_lat = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : CountryTableMap::translateFieldName('BboxNeLng', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bbox_ne_lng = (null !== $col) ? (double) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 23; // 23 = CountryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\Country'), 0, $e);
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
        if ($this->aContinent !== null && $this->continent_id !== $this->aContinent->getId()) {
            $this->aContinent = null;
        }
        if ($this->aCurrency !== null && $this->currency_id !== $this->aCurrency->getId()) {
            $this->aCurrency = null;
        }
        if ($this->aType !== null && $this->type_id !== $this->aType->getId()) {
            $this->aType = null;
        }
        if ($this->aSubtype !== null && $this->subtype_id !== $this->aSubtype->getId()) {
            $this->aSubtype = null;
        }
        if ($this->aCountryRelatedBySovereignityId !== null && $this->sovereignity_id !== $this->aCountryRelatedBySovereignityId->getId()) {
            $this->aCountryRelatedBySovereignityId = null;
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
        $pos = CountryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getNumeric();
                break;
            case 2:
                return $this->getAlpha2();
                break;
            case 3:
                return $this->getAlpha3();
                break;
            case 4:
                return $this->getShortName();
                break;
            case 5:
                return $this->getIoc();
                break;
            case 6:
                return $this->getTld();
                break;
            case 7:
                return $this->getPhone();
                break;
            case 8:
                return $this->getCapital();
                break;
            case 9:
                return $this->getPostalCodeFormat();
                break;
            case 10:
                return $this->getPostalCodeRegex();
                break;
            case 11:
                return $this->getContinentId();
                break;
            case 12:
                return $this->getCurrencyId();
                break;
            case 13:
                return $this->getTypeId();
                break;
            case 14:
                return $this->getSubtypeId();
                break;
            case 15:
                return $this->getSovereignityId();
                break;
            case 16:
                return $this->getFormalName();
                break;
            case 17:
                return $this->getFormalNativeName();
                break;
            case 18:
                return $this->getShortNativeName();
                break;
            case 19:
                return $this->getBboxSwLat();
                break;
            case 20:
                return $this->getBboxSwLng();
                break;
            case 21:
                return $this->getBboxNeLat();
                break;
            case 22:
                return $this->getBboxNeLng();
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

        if (isset($alreadyDumpedObjects['Country'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Country'][$this->hashCode()] = true;
        $keys = CountryTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNumeric(),
            $keys[2] => $this->getAlpha2(),
            $keys[3] => $this->getAlpha3(),
            $keys[4] => $this->getShortName(),
            $keys[5] => $this->getIoc(),
            $keys[6] => $this->getTld(),
            $keys[7] => $this->getPhone(),
            $keys[8] => $this->getCapital(),
            $keys[9] => $this->getPostalCodeFormat(),
            $keys[10] => $this->getPostalCodeRegex(),
            $keys[11] => $this->getContinentId(),
            $keys[12] => $this->getCurrencyId(),
            $keys[13] => $this->getTypeId(),
            $keys[14] => $this->getSubtypeId(),
            $keys[15] => $this->getSovereignityId(),
            $keys[16] => $this->getFormalName(),
            $keys[17] => $this->getFormalNativeName(),
            $keys[18] => $this->getShortNativeName(),
            $keys[19] => $this->getBboxSwLat(),
            $keys[20] => $this->getBboxSwLng(),
            $keys[21] => $this->getBboxNeLat(),
            $keys[22] => $this->getBboxNeLng(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aContinent) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'continent';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_continent';
                        break;
                    default:
                        $key = 'Continent';
                }

                $result[$key] = $this->aContinent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCurrency) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'currency';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_currency';
                        break;
                    default:
                        $key = 'Currency';
                }

                $result[$key] = $this->aCurrency->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aType) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'regionType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_region_type';
                        break;
                    default:
                        $key = 'RegionType';
                }

                $result[$key] = $this->aType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSubtype) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'regionType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'kk_region_type';
                        break;
                    default:
                        $key = 'RegionType';
                }

                $result[$key] = $this->aSubtype->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCountryRelatedBySovereignityId) {

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

                $result[$key] = $this->aCountryRelatedBySovereignityId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collSubordinates) {

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

                $result[$key] = $this->collSubordinates->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $criteria = new Criteria(CountryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CountryTableMap::COL_ID)) {
            $criteria->add(CountryTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(CountryTableMap::COL_NUMERIC)) {
            $criteria->add(CountryTableMap::COL_NUMERIC, $this->numeric);
        }
        if ($this->isColumnModified(CountryTableMap::COL_ALPHA_2)) {
            $criteria->add(CountryTableMap::COL_ALPHA_2, $this->alpha_2);
        }
        if ($this->isColumnModified(CountryTableMap::COL_ALPHA_3)) {
            $criteria->add(CountryTableMap::COL_ALPHA_3, $this->alpha_3);
        }
        if ($this->isColumnModified(CountryTableMap::COL_SHORT_NAME)) {
            $criteria->add(CountryTableMap::COL_SHORT_NAME, $this->short_name);
        }
        if ($this->isColumnModified(CountryTableMap::COL_IOC)) {
            $criteria->add(CountryTableMap::COL_IOC, $this->ioc);
        }
        if ($this->isColumnModified(CountryTableMap::COL_TLD)) {
            $criteria->add(CountryTableMap::COL_TLD, $this->tld);
        }
        if ($this->isColumnModified(CountryTableMap::COL_PHONE)) {
            $criteria->add(CountryTableMap::COL_PHONE, $this->phone);
        }
        if ($this->isColumnModified(CountryTableMap::COL_CAPITAL)) {
            $criteria->add(CountryTableMap::COL_CAPITAL, $this->capital);
        }
        if ($this->isColumnModified(CountryTableMap::COL_POSTAL_CODE_FORMAT)) {
            $criteria->add(CountryTableMap::COL_POSTAL_CODE_FORMAT, $this->postal_code_format);
        }
        if ($this->isColumnModified(CountryTableMap::COL_POSTAL_CODE_REGEX)) {
            $criteria->add(CountryTableMap::COL_POSTAL_CODE_REGEX, $this->postal_code_regex);
        }
        if ($this->isColumnModified(CountryTableMap::COL_CONTINENT_ID)) {
            $criteria->add(CountryTableMap::COL_CONTINENT_ID, $this->continent_id);
        }
        if ($this->isColumnModified(CountryTableMap::COL_CURRENCY_ID)) {
            $criteria->add(CountryTableMap::COL_CURRENCY_ID, $this->currency_id);
        }
        if ($this->isColumnModified(CountryTableMap::COL_TYPE_ID)) {
            $criteria->add(CountryTableMap::COL_TYPE_ID, $this->type_id);
        }
        if ($this->isColumnModified(CountryTableMap::COL_SUBTYPE_ID)) {
            $criteria->add(CountryTableMap::COL_SUBTYPE_ID, $this->subtype_id);
        }
        if ($this->isColumnModified(CountryTableMap::COL_SOVEREIGNITY_ID)) {
            $criteria->add(CountryTableMap::COL_SOVEREIGNITY_ID, $this->sovereignity_id);
        }
        if ($this->isColumnModified(CountryTableMap::COL_FORMAL_NAME)) {
            $criteria->add(CountryTableMap::COL_FORMAL_NAME, $this->formal_name);
        }
        if ($this->isColumnModified(CountryTableMap::COL_FORMAL_NATIVE_NAME)) {
            $criteria->add(CountryTableMap::COL_FORMAL_NATIVE_NAME, $this->formal_native_name);
        }
        if ($this->isColumnModified(CountryTableMap::COL_SHORT_NATIVE_NAME)) {
            $criteria->add(CountryTableMap::COL_SHORT_NATIVE_NAME, $this->short_native_name);
        }
        if ($this->isColumnModified(CountryTableMap::COL_BBOX_SW_LAT)) {
            $criteria->add(CountryTableMap::COL_BBOX_SW_LAT, $this->bbox_sw_lat);
        }
        if ($this->isColumnModified(CountryTableMap::COL_BBOX_SW_LNG)) {
            $criteria->add(CountryTableMap::COL_BBOX_SW_LNG, $this->bbox_sw_lng);
        }
        if ($this->isColumnModified(CountryTableMap::COL_BBOX_NE_LAT)) {
            $criteria->add(CountryTableMap::COL_BBOX_NE_LAT, $this->bbox_ne_lat);
        }
        if ($this->isColumnModified(CountryTableMap::COL_BBOX_NE_LNG)) {
            $criteria->add(CountryTableMap::COL_BBOX_NE_LNG, $this->bbox_ne_lng);
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
        $criteria = ChildCountryQuery::create();
        $criteria->add(CountryTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\Country (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNumeric($this->getNumeric());
        $copyObj->setAlpha2($this->getAlpha2());
        $copyObj->setAlpha3($this->getAlpha3());
        $copyObj->setShortName($this->getShortName());
        $copyObj->setIoc($this->getIoc());
        $copyObj->setTld($this->getTld());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setCapital($this->getCapital());
        $copyObj->setPostalCodeFormat($this->getPostalCodeFormat());
        $copyObj->setPostalCodeRegex($this->getPostalCodeRegex());
        $copyObj->setContinentId($this->getContinentId());
        $copyObj->setCurrencyId($this->getCurrencyId());
        $copyObj->setTypeId($this->getTypeId());
        $copyObj->setSubtypeId($this->getSubtypeId());
        $copyObj->setSovereignityId($this->getSovereignityId());
        $copyObj->setFormalName($this->getFormalName());
        $copyObj->setFormalNativeName($this->getFormalNativeName());
        $copyObj->setShortNativeName($this->getShortNativeName());
        $copyObj->setBboxSwLat($this->getBboxSwLat());
        $copyObj->setBboxSwLng($this->getBboxSwLng());
        $copyObj->setBboxNeLat($this->getBboxNeLat());
        $copyObj->setBboxNeLng($this->getBboxNeLng());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getSubordinates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSubordinate($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\Country Clone of current object.
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
     * Declares an association between this object and a ChildContinent object.
     *
     * @param  ChildContinent $v
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     * @throws PropelException
     */
    public function setContinent(ChildContinent $v = null)
    {
        if ($v === null) {
            $this->setContinentId(NULL);
        } else {
            $this->setContinentId($v->getId());
        }

        $this->aContinent = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildContinent object, it will not be re-added.
        if ($v !== null) {
            $v->addCountry($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildContinent object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildContinent The associated ChildContinent object.
     * @throws PropelException
     */
    public function getContinent(ConnectionInterface $con = null)
    {
        if ($this->aContinent === null && ($this->continent_id !== null)) {
            $this->aContinent = ChildContinentQuery::create()->findPk($this->continent_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aContinent->addCountries($this);
             */
        }

        return $this->aContinent;
    }

    /**
     * Declares an association between this object and a ChildCurrency object.
     *
     * @param  ChildCurrency $v
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrency(ChildCurrency $v = null)
    {
        if ($v === null) {
            $this->setCurrencyId(NULL);
        } else {
            $this->setCurrencyId($v->getId());
        }

        $this->aCurrency = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCurrency object, it will not be re-added.
        if ($v !== null) {
            $v->addCountry($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCurrency object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCurrency The associated ChildCurrency object.
     * @throws PropelException
     */
    public function getCurrency(ConnectionInterface $con = null)
    {
        if ($this->aCurrency === null && ($this->currency_id !== null)) {
            $this->aCurrency = ChildCurrencyQuery::create()->findPk($this->currency_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrency->addCountries($this);
             */
        }

        return $this->aCurrency;
    }

    /**
     * Declares an association between this object and a ChildRegionType object.
     *
     * @param  ChildRegionType $v
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     * @throws PropelException
     */
    public function setType(ChildRegionType $v = null)
    {
        if ($v === null) {
            $this->setTypeId(NULL);
        } else {
            $this->setTypeId($v->getId());
        }

        $this->aType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildRegionType object, it will not be re-added.
        if ($v !== null) {
            $v->addCountryRelatedByTypeId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildRegionType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildRegionType The associated ChildRegionType object.
     * @throws PropelException
     */
    public function getType(ConnectionInterface $con = null)
    {
        if ($this->aType === null && ($this->type_id !== null)) {
            $this->aType = ChildRegionTypeQuery::create()->findPk($this->type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aType->addCountriesRelatedByTypeId($this);
             */
        }

        return $this->aType;
    }

    /**
     * Declares an association between this object and a ChildRegionType object.
     *
     * @param  ChildRegionType $v
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSubtype(ChildRegionType $v = null)
    {
        if ($v === null) {
            $this->setSubtypeId(NULL);
        } else {
            $this->setSubtypeId($v->getId());
        }

        $this->aSubtype = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildRegionType object, it will not be re-added.
        if ($v !== null) {
            $v->addCountryRelatedBySubtypeId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildRegionType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildRegionType The associated ChildRegionType object.
     * @throws PropelException
     */
    public function getSubtype(ConnectionInterface $con = null)
    {
        if ($this->aSubtype === null && ($this->subtype_id !== null)) {
            $this->aSubtype = ChildRegionTypeQuery::create()->findPk($this->subtype_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSubtype->addCountriesRelatedBySubtypeId($this);
             */
        }

        return $this->aSubtype;
    }

    /**
     * Declares an association between this object and a ChildCountry object.
     *
     * @param  ChildCountry $v
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCountryRelatedBySovereignityId(ChildCountry $v = null)
    {
        if ($v === null) {
            $this->setSovereignityId(NULL);
        } else {
            $this->setSovereignityId($v->getId());
        }

        $this->aCountryRelatedBySovereignityId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCountry object, it will not be re-added.
        if ($v !== null) {
            $v->addSubordinate($this);
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
    public function getCountryRelatedBySovereignityId(ConnectionInterface $con = null)
    {
        if ($this->aCountryRelatedBySovereignityId === null && ($this->sovereignity_id !== null)) {
            $this->aCountryRelatedBySovereignityId = ChildCountryQuery::create()->findPk($this->sovereignity_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCountryRelatedBySovereignityId->addSubordinates($this);
             */
        }

        return $this->aCountryRelatedBySovereignityId;
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
        if ('Subordinate' == $relationName) {
            return $this->initSubordinates();
        }
        if ('Subdivision' == $relationName) {
            return $this->initSubdivisions();
        }
    }

    /**
     * Clears out the collSubordinates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSubordinates()
     */
    public function clearSubordinates()
    {
        $this->collSubordinates = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSubordinates collection loaded partially.
     */
    public function resetPartialSubordinates($v = true)
    {
        $this->collSubordinatesPartial = $v;
    }

    /**
     * Initializes the collSubordinates collection.
     *
     * By default this just sets the collSubordinates collection to an empty array (like clearcollSubordinates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSubordinates($overrideExisting = true)
    {
        if (null !== $this->collSubordinates && !$overrideExisting) {
            return;
        }
        $this->collSubordinates = new ObjectCollection();
        $this->collSubordinates->setModel('\keeko\core\model\Country');
    }

    /**
     * Gets an array of ChildCountry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCountry is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     * @throws PropelException
     */
    public function getSubordinates(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSubordinatesPartial && !$this->isNew();
        if (null === $this->collSubordinates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSubordinates) {
                // return empty collection
                $this->initSubordinates();
            } else {
                $collSubordinates = ChildCountryQuery::create(null, $criteria)
                    ->filterByCountryRelatedBySovereignityId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSubordinatesPartial && count($collSubordinates)) {
                        $this->initSubordinates(false);

                        foreach ($collSubordinates as $obj) {
                            if (false == $this->collSubordinates->contains($obj)) {
                                $this->collSubordinates->append($obj);
                            }
                        }

                        $this->collSubordinatesPartial = true;
                    }

                    return $collSubordinates;
                }

                if ($partial && $this->collSubordinates) {
                    foreach ($this->collSubordinates as $obj) {
                        if ($obj->isNew()) {
                            $collSubordinates[] = $obj;
                        }
                    }
                }

                $this->collSubordinates = $collSubordinates;
                $this->collSubordinatesPartial = false;
            }
        }

        return $this->collSubordinates;
    }

    /**
     * Sets a collection of ChildCountry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $subordinates A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCountry The current object (for fluent API support)
     */
    public function setSubordinates(Collection $subordinates, ConnectionInterface $con = null)
    {
        /** @var ChildCountry[] $subordinatesToDelete */
        $subordinatesToDelete = $this->getSubordinates(new Criteria(), $con)->diff($subordinates);


        $this->subordinatesScheduledForDeletion = $subordinatesToDelete;

        foreach ($subordinatesToDelete as $subordinateRemoved) {
            $subordinateRemoved->setCountryRelatedBySovereignityId(null);
        }

        $this->collSubordinates = null;
        foreach ($subordinates as $subordinate) {
            $this->addSubordinate($subordinate);
        }

        $this->collSubordinates = $subordinates;
        $this->collSubordinatesPartial = false;

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
    public function countSubordinates(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSubordinatesPartial && !$this->isNew();
        if (null === $this->collSubordinates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSubordinates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSubordinates());
            }

            $query = ChildCountryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCountryRelatedBySovereignityId($this)
                ->count($con);
        }

        return count($this->collSubordinates);
    }

    /**
     * Method called to associate a ChildCountry object to this object
     * through the ChildCountry foreign key attribute.
     *
     * @param  ChildCountry $l ChildCountry
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function addSubordinate(ChildCountry $l)
    {
        if ($this->collSubordinates === null) {
            $this->initSubordinates();
            $this->collSubordinatesPartial = true;
        }

        if (!$this->collSubordinates->contains($l)) {
            $this->doAddSubordinate($l);
        }

        return $this;
    }

    /**
     * @param ChildCountry $subordinate The ChildCountry object to add.
     */
    protected function doAddSubordinate(ChildCountry $subordinate)
    {
        $this->collSubordinates[]= $subordinate;
        $subordinate->setCountryRelatedBySovereignityId($this);
    }

    /**
     * @param  ChildCountry $subordinate The ChildCountry object to remove.
     * @return $this|ChildCountry The current object (for fluent API support)
     */
    public function removeSubordinate(ChildCountry $subordinate)
    {
        if ($this->getSubordinates()->contains($subordinate)) {
            $pos = $this->collSubordinates->search($subordinate);
            $this->collSubordinates->remove($pos);
            if (null === $this->subordinatesScheduledForDeletion) {
                $this->subordinatesScheduledForDeletion = clone $this->collSubordinates;
                $this->subordinatesScheduledForDeletion->clear();
            }
            $this->subordinatesScheduledForDeletion[]= $subordinate;
            $subordinate->setCountryRelatedBySovereignityId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Subordinates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getSubordinatesJoinContinent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Continent', $joinBehavior);

        return $this->getSubordinates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Subordinates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getSubordinatesJoinCurrency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Currency', $joinBehavior);

        return $this->getSubordinates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Subordinates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getSubordinatesJoinType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Type', $joinBehavior);

        return $this->getSubordinates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Subordinates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getSubordinatesJoinSubtype(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Subtype', $joinBehavior);

        return $this->getSubordinates($query, $con);
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
     * If this ChildCountry is new, it will return
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
                    ->filterByCountry($this)
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
     * @return $this|ChildCountry The current object (for fluent API support)
     */
    public function setSubdivisions(Collection $subdivisions, ConnectionInterface $con = null)
    {
        /** @var ChildSubdivision[] $subdivisionsToDelete */
        $subdivisionsToDelete = $this->getSubdivisions(new Criteria(), $con)->diff($subdivisions);


        $this->subdivisionsScheduledForDeletion = $subdivisionsToDelete;

        foreach ($subdivisionsToDelete as $subdivisionRemoved) {
            $subdivisionRemoved->setCountry(null);
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
                ->filterByCountry($this)
                ->count($con);
        }

        return count($this->collSubdivisions);
    }

    /**
     * Method called to associate a ChildSubdivision object to this object
     * through the ChildSubdivision foreign key attribute.
     *
     * @param  ChildSubdivision $l ChildSubdivision
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
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
        $subdivision->setCountry($this);
    }

    /**
     * @param  ChildSubdivision $subdivision The ChildSubdivision object to remove.
     * @return $this|ChildCountry The current object (for fluent API support)
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
            $subdivision->setCountry(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Subdivisions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSubdivision[] List of ChildSubdivision objects
     */
    public function getSubdivisionsJoinRegionType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSubdivisionQuery::create(null, $criteria);
        $query->joinWith('RegionType', $joinBehavior);

        return $this->getSubdivisions($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aContinent) {
            $this->aContinent->removeCountry($this);
        }
        if (null !== $this->aCurrency) {
            $this->aCurrency->removeCountry($this);
        }
        if (null !== $this->aType) {
            $this->aType->removeCountryRelatedByTypeId($this);
        }
        if (null !== $this->aSubtype) {
            $this->aSubtype->removeCountryRelatedBySubtypeId($this);
        }
        if (null !== $this->aCountryRelatedBySovereignityId) {
            $this->aCountryRelatedBySovereignityId->removeSubordinate($this);
        }
        $this->id = null;
        $this->numeric = null;
        $this->alpha_2 = null;
        $this->alpha_3 = null;
        $this->short_name = null;
        $this->ioc = null;
        $this->tld = null;
        $this->phone = null;
        $this->capital = null;
        $this->postal_code_format = null;
        $this->postal_code_regex = null;
        $this->continent_id = null;
        $this->currency_id = null;
        $this->type_id = null;
        $this->subtype_id = null;
        $this->sovereignity_id = null;
        $this->formal_name = null;
        $this->formal_native_name = null;
        $this->short_native_name = null;
        $this->bbox_sw_lat = null;
        $this->bbox_sw_lng = null;
        $this->bbox_ne_lat = null;
        $this->bbox_ne_lng = null;
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
            if ($this->collSubordinates) {
                foreach ($this->collSubordinates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSubdivisions) {
                foreach ($this->collSubdivisions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collSubordinates = null;
        $this->collSubdivisions = null;
        $this->aContinent = null;
        $this->aCurrency = null;
        $this->aType = null;
        $this->aSubtype = null;
        $this->aCountryRelatedBySovereignityId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CountryTableMap::DEFAULT_STRING_FORMAT);
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
