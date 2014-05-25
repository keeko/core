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
use keeko\core\model\Currency as ChildCurrency;
use keeko\core\model\CurrencyQuery as ChildCurrencyQuery;
use keeko\core\model\Localization as ChildLocalization;
use keeko\core\model\LocalizationQuery as ChildLocalizationQuery;
use keeko\core\model\Subdivision as ChildSubdivision;
use keeko\core\model\SubdivisionQuery as ChildSubdivisionQuery;
use keeko\core\model\Territory as ChildTerritory;
use keeko\core\model\TerritoryQuery as ChildTerritoryQuery;
use keeko\core\model\User as ChildUser;
use keeko\core\model\UserQuery as ChildUserQuery;
use keeko\core\model\Map\CountryTableMap;

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
     * The value for the iso_nr field.
     * @var        int
     */
    protected $iso_nr;

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
     * The value for the ioc field.
     * @var        string
     */
    protected $ioc;

    /**
     * The value for the capital field.
     * @var        string
     */
    protected $capital;

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
     * The value for the territory_iso_nr field.
     * @var        int
     */
    protected $territory_iso_nr;

    /**
     * The value for the currency_iso_nr field.
     * @var        int
     */
    protected $currency_iso_nr;

    /**
     * The value for the official_local_name field.
     * @var        string
     */
    protected $official_local_name;

    /**
     * The value for the official_en_name field.
     * @var        string
     */
    protected $official_en_name;

    /**
     * The value for the short_local_name field.
     * @var        string
     */
    protected $short_local_name;

    /**
     * The value for the short_en_name field.
     * @var        string
     */
    protected $short_en_name;

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
     * @var        ChildTerritory
     */
    protected $aTerritory;

    /**
     * @var        ChildCurrency
     */
    protected $aCurrency;

    /**
     * @var        ObjectCollection|ChildLocalization[] Collection to store aggregation of ChildLocalization objects.
     */
    protected $collLocalizations;
    protected $collLocalizationsPartial;

    /**
     * @var        ObjectCollection|ChildSubdivision[] Collection to store aggregation of ChildSubdivision objects.
     */
    protected $collSubdivisions;
    protected $collSubdivisionsPartial;

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
     * @var ObjectCollection|ChildLocalization[]
     */
    protected $localizationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSubdivision[]
     */
    protected $subdivisionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

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
     * Get the [iso_nr] column value.
     *
     * @return int
     */
    public function getIsoNr()
    {
        return $this->iso_nr;
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
     * Get the [ioc] column value.
     *
     * @return string
     */
    public function getIoc()
    {
        return $this->ioc;
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
     * Get the [territory_iso_nr] column value.
     *
     * @return int
     */
    public function getTerritoryIsoNr()
    {
        return $this->territory_iso_nr;
    }

    /**
     * Get the [currency_iso_nr] column value.
     *
     * @return int
     */
    public function getCurrencyIsoNr()
    {
        return $this->currency_iso_nr;
    }

    /**
     * Get the [official_local_name] column value.
     *
     * @return string
     */
    public function getOfficialLocalName()
    {
        return $this->official_local_name;
    }

    /**
     * Get the [official_en_name] column value.
     *
     * @return string
     */
    public function getOfficialEnName()
    {
        return $this->official_en_name;
    }

    /**
     * Get the [short_local_name] column value.
     *
     * @return string
     */
    public function getShortLocalName()
    {
        return $this->short_local_name;
    }

    /**
     * Get the [short_en_name] column value.
     *
     * @return string
     */
    public function getShortEnName()
    {
        return $this->short_en_name;
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
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CountryTableMap::translateFieldName('IsoNr', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iso_nr = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CountryTableMap::translateFieldName('Alpha2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CountryTableMap::translateFieldName('Alpha3', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_3 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CountryTableMap::translateFieldName('Ioc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ioc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CountryTableMap::translateFieldName('Capital', TableMap::TYPE_PHPNAME, $indexType)];
            $this->capital = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CountryTableMap::translateFieldName('Tld', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tld = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CountryTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CountryTableMap::translateFieldName('TerritoryIsoNr', TableMap::TYPE_PHPNAME, $indexType)];
            $this->territory_iso_nr = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CountryTableMap::translateFieldName('CurrencyIsoNr', TableMap::TYPE_PHPNAME, $indexType)];
            $this->currency_iso_nr = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CountryTableMap::translateFieldName('OfficialLocalName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->official_local_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CountryTableMap::translateFieldName('OfficialEnName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->official_en_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CountryTableMap::translateFieldName('ShortLocalName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->short_local_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CountryTableMap::translateFieldName('ShortEnName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->short_en_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : CountryTableMap::translateFieldName('BboxSwLat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bbox_sw_lat = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : CountryTableMap::translateFieldName('BboxSwLng', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bbox_sw_lng = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : CountryTableMap::translateFieldName('BboxNeLat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bbox_ne_lat = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : CountryTableMap::translateFieldName('BboxNeLng', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bbox_ne_lng = (null !== $col) ? (double) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 17; // 17 = CountryTableMap::NUM_HYDRATE_COLUMNS.

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
        if ($this->aTerritory !== null && $this->territory_iso_nr !== $this->aTerritory->getIsoNr()) {
            $this->aTerritory = null;
        }
        if ($this->aCurrency !== null && $this->currency_iso_nr !== $this->aCurrency->getIsoNr()) {
            $this->aCurrency = null;
        }
    } // ensureConsistency

    /**
     * Set the value of [iso_nr] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setIsoNr($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->iso_nr !== $v) {
            $this->iso_nr = $v;
            $this->modifiedColumns[CountryTableMap::COL_ISO_NR] = true;
        }

        return $this;
    } // setIsoNr()

    /**
     * Set the value of [alpha_2] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setAlpha2($v)
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
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setAlpha3($v)
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
     * Set the value of [ioc] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setIoc($v)
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
     * Set the value of [capital] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setCapital($v)
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
     * Set the value of [tld] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setTld($v)
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
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setPhone($v)
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
     * Set the value of [territory_iso_nr] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setTerritoryIsoNr($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->territory_iso_nr !== $v) {
            $this->territory_iso_nr = $v;
            $this->modifiedColumns[CountryTableMap::COL_TERRITORY_ISO_NR] = true;
        }

        if ($this->aTerritory !== null && $this->aTerritory->getIsoNr() !== $v) {
            $this->aTerritory = null;
        }

        return $this;
    } // setTerritoryIsoNr()

    /**
     * Set the value of [currency_iso_nr] column.
     *
     * @param  int $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setCurrencyIsoNr($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->currency_iso_nr !== $v) {
            $this->currency_iso_nr = $v;
            $this->modifiedColumns[CountryTableMap::COL_CURRENCY_ISO_NR] = true;
        }

        if ($this->aCurrency !== null && $this->aCurrency->getIsoNr() !== $v) {
            $this->aCurrency = null;
        }

        return $this;
    } // setCurrencyIsoNr()

    /**
     * Set the value of [official_local_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setOfficialLocalName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->official_local_name !== $v) {
            $this->official_local_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_OFFICIAL_LOCAL_NAME] = true;
        }

        return $this;
    } // setOfficialLocalName()

    /**
     * Set the value of [official_en_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setOfficialEnName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->official_en_name !== $v) {
            $this->official_en_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_OFFICIAL_EN_NAME] = true;
        }

        return $this;
    } // setOfficialEnName()

    /**
     * Set the value of [short_local_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setShortLocalName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->short_local_name !== $v) {
            $this->short_local_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_SHORT_LOCAL_NAME] = true;
        }

        return $this;
    } // setShortLocalName()

    /**
     * Set the value of [short_en_name] column.
     *
     * @param  string $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setShortEnName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->short_en_name !== $v) {
            $this->short_en_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_SHORT_EN_NAME] = true;
        }

        return $this;
    } // setShortEnName()

    /**
     * Set the value of [bbox_sw_lat] column.
     *
     * @param  double $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setBboxSwLat($v)
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
     * @param  double $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setBboxSwLng($v)
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
     * @param  double $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setBboxNeLat($v)
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
     * @param  double $v new value
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function setBboxNeLng($v)
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
            $con = Propel::getServiceContainer()->getReadConnection(CountryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCountryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aTerritory = null;
            $this->aCurrency = null;
            $this->collLocalizations = null;

            $this->collSubdivisions = null;

            $this->collUsers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Country::setDeleted()
     * @see Country::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCountryQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
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
                CountryTableMap::addInstanceToPool($this);
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

            if ($this->aTerritory !== null) {
                if ($this->aTerritory->isModified() || $this->aTerritory->isNew()) {
                    $affectedRows += $this->aTerritory->save($con);
                }
                $this->setTerritory($this->aTerritory);
            }

            if ($this->aCurrency !== null) {
                if ($this->aCurrency->isModified() || $this->aCurrency->isNew()) {
                    $affectedRows += $this->aCurrency->save($con);
                }
                $this->setCurrency($this->aCurrency);
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

            if ($this->localizationsScheduledForDeletion !== null) {
                if (!$this->localizationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->localizationsScheduledForDeletion as $localization) {
                        // need to save related object because we set the relation to null
                        $localization->save($con);
                    }
                    $this->localizationsScheduledForDeletion = null;
                }
            }

            if ($this->collLocalizations !== null) {
                foreach ($this->collLocalizations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->subdivisionsScheduledForDeletion !== null) {
                if (!$this->subdivisionsScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\SubdivisionQuery::create()
                        ->filterByPrimaryKeys($this->subdivisionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->subdivisionsScheduledForDeletion = null;
                }
            }

            if ($this->collSubdivisions !== null) {
                foreach ($this->collSubdivisions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CountryTableMap::COL_ISO_NR)) {
            $modifiedColumns[':p' . $index++]  = 'ISO_NR';
        }
        if ($this->isColumnModified(CountryTableMap::COL_ALPHA_2)) {
            $modifiedColumns[':p' . $index++]  = 'ALPHA_2';
        }
        if ($this->isColumnModified(CountryTableMap::COL_ALPHA_3)) {
            $modifiedColumns[':p' . $index++]  = 'ALPHA_3';
        }
        if ($this->isColumnModified(CountryTableMap::COL_IOC)) {
            $modifiedColumns[':p' . $index++]  = 'IOC';
        }
        if ($this->isColumnModified(CountryTableMap::COL_CAPITAL)) {
            $modifiedColumns[':p' . $index++]  = 'CAPITAL';
        }
        if ($this->isColumnModified(CountryTableMap::COL_TLD)) {
            $modifiedColumns[':p' . $index++]  = 'TLD';
        }
        if ($this->isColumnModified(CountryTableMap::COL_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'PHONE';
        }
        if ($this->isColumnModified(CountryTableMap::COL_TERRITORY_ISO_NR)) {
            $modifiedColumns[':p' . $index++]  = 'TERRITORY_ISO_NR';
        }
        if ($this->isColumnModified(CountryTableMap::COL_CURRENCY_ISO_NR)) {
            $modifiedColumns[':p' . $index++]  = 'CURRENCY_ISO_NR';
        }
        if ($this->isColumnModified(CountryTableMap::COL_OFFICIAL_LOCAL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'OFFICIAL_LOCAL_NAME';
        }
        if ($this->isColumnModified(CountryTableMap::COL_OFFICIAL_EN_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'OFFICIAL_EN_NAME';
        }
        if ($this->isColumnModified(CountryTableMap::COL_SHORT_LOCAL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'SHORT_LOCAL_NAME';
        }
        if ($this->isColumnModified(CountryTableMap::COL_SHORT_EN_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'SHORT_EN_NAME';
        }
        if ($this->isColumnModified(CountryTableMap::COL_BBOX_SW_LAT)) {
            $modifiedColumns[':p' . $index++]  = 'BBOX_SW_LAT';
        }
        if ($this->isColumnModified(CountryTableMap::COL_BBOX_SW_LNG)) {
            $modifiedColumns[':p' . $index++]  = 'BBOX_SW_LNG';
        }
        if ($this->isColumnModified(CountryTableMap::COL_BBOX_NE_LAT)) {
            $modifiedColumns[':p' . $index++]  = 'BBOX_NE_LAT';
        }
        if ($this->isColumnModified(CountryTableMap::COL_BBOX_NE_LNG)) {
            $modifiedColumns[':p' . $index++]  = 'BBOX_NE_LNG';
        }

        $sql = sprintf(
            'INSERT INTO keeko_country (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ISO_NR':
                        $stmt->bindValue($identifier, $this->iso_nr, PDO::PARAM_INT);
                        break;
                    case 'ALPHA_2':
                        $stmt->bindValue($identifier, $this->alpha_2, PDO::PARAM_STR);
                        break;
                    case 'ALPHA_3':
                        $stmt->bindValue($identifier, $this->alpha_3, PDO::PARAM_STR);
                        break;
                    case 'IOC':
                        $stmt->bindValue($identifier, $this->ioc, PDO::PARAM_STR);
                        break;
                    case 'CAPITAL':
                        $stmt->bindValue($identifier, $this->capital, PDO::PARAM_STR);
                        break;
                    case 'TLD':
                        $stmt->bindValue($identifier, $this->tld, PDO::PARAM_STR);
                        break;
                    case 'PHONE':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case 'TERRITORY_ISO_NR':
                        $stmt->bindValue($identifier, $this->territory_iso_nr, PDO::PARAM_INT);
                        break;
                    case 'CURRENCY_ISO_NR':
                        $stmt->bindValue($identifier, $this->currency_iso_nr, PDO::PARAM_INT);
                        break;
                    case 'OFFICIAL_LOCAL_NAME':
                        $stmt->bindValue($identifier, $this->official_local_name, PDO::PARAM_STR);
                        break;
                    case 'OFFICIAL_EN_NAME':
                        $stmt->bindValue($identifier, $this->official_en_name, PDO::PARAM_STR);
                        break;
                    case 'SHORT_LOCAL_NAME':
                        $stmt->bindValue($identifier, $this->short_local_name, PDO::PARAM_STR);
                        break;
                    case 'SHORT_EN_NAME':
                        $stmt->bindValue($identifier, $this->short_en_name, PDO::PARAM_STR);
                        break;
                    case 'BBOX_SW_LAT':
                        $stmt->bindValue($identifier, $this->bbox_sw_lat, PDO::PARAM_STR);
                        break;
                    case 'BBOX_SW_LNG':
                        $stmt->bindValue($identifier, $this->bbox_sw_lng, PDO::PARAM_STR);
                        break;
                    case 'BBOX_NE_LAT':
                        $stmt->bindValue($identifier, $this->bbox_ne_lat, PDO::PARAM_STR);
                        break;
                    case 'BBOX_NE_LNG':
                        $stmt->bindValue($identifier, $this->bbox_ne_lng, PDO::PARAM_STR);
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
                return $this->getIsoNr();
                break;
            case 1:
                return $this->getAlpha2();
                break;
            case 2:
                return $this->getAlpha3();
                break;
            case 3:
                return $this->getIoc();
                break;
            case 4:
                return $this->getCapital();
                break;
            case 5:
                return $this->getTld();
                break;
            case 6:
                return $this->getPhone();
                break;
            case 7:
                return $this->getTerritoryIsoNr();
                break;
            case 8:
                return $this->getCurrencyIsoNr();
                break;
            case 9:
                return $this->getOfficialLocalName();
                break;
            case 10:
                return $this->getOfficialEnName();
                break;
            case 11:
                return $this->getShortLocalName();
                break;
            case 12:
                return $this->getShortEnName();
                break;
            case 13:
                return $this->getBboxSwLat();
                break;
            case 14:
                return $this->getBboxSwLng();
                break;
            case 15:
                return $this->getBboxNeLat();
                break;
            case 16:
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
        if (isset($alreadyDumpedObjects['Country'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Country'][$this->getPrimaryKey()] = true;
        $keys = CountryTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getIsoNr(),
            $keys[1] => $this->getAlpha2(),
            $keys[2] => $this->getAlpha3(),
            $keys[3] => $this->getIoc(),
            $keys[4] => $this->getCapital(),
            $keys[5] => $this->getTld(),
            $keys[6] => $this->getPhone(),
            $keys[7] => $this->getTerritoryIsoNr(),
            $keys[8] => $this->getCurrencyIsoNr(),
            $keys[9] => $this->getOfficialLocalName(),
            $keys[10] => $this->getOfficialEnName(),
            $keys[11] => $this->getShortLocalName(),
            $keys[12] => $this->getShortEnName(),
            $keys[13] => $this->getBboxSwLat(),
            $keys[14] => $this->getBboxSwLng(),
            $keys[15] => $this->getBboxNeLat(),
            $keys[16] => $this->getBboxNeLng(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aTerritory) {
                $result['Territory'] = $this->aTerritory->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCurrency) {
                $result['Currency'] = $this->aCurrency->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collLocalizations) {
                $result['Localizations'] = $this->collLocalizations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSubdivisions) {
                $result['Subdivisions'] = $this->collSubdivisions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUsers) {
                $result['Users'] = $this->collUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\keeko\core\model\Country
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CountryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\keeko\core\model\Country
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setIsoNr($value);
                break;
            case 1:
                $this->setAlpha2($value);
                break;
            case 2:
                $this->setAlpha3($value);
                break;
            case 3:
                $this->setIoc($value);
                break;
            case 4:
                $this->setCapital($value);
                break;
            case 5:
                $this->setTld($value);
                break;
            case 6:
                $this->setPhone($value);
                break;
            case 7:
                $this->setTerritoryIsoNr($value);
                break;
            case 8:
                $this->setCurrencyIsoNr($value);
                break;
            case 9:
                $this->setOfficialLocalName($value);
                break;
            case 10:
                $this->setOfficialEnName($value);
                break;
            case 11:
                $this->setShortLocalName($value);
                break;
            case 12:
                $this->setShortEnName($value);
                break;
            case 13:
                $this->setBboxSwLat($value);
                break;
            case 14:
                $this->setBboxSwLng($value);
                break;
            case 15:
                $this->setBboxNeLat($value);
                break;
            case 16:
                $this->setBboxNeLng($value);
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
        $keys = CountryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setIsoNr($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAlpha2($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAlpha3($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setIoc($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCapital($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setTld($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPhone($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setTerritoryIsoNr($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCurrencyIsoNr($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setOfficialLocalName($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setOfficialEnName($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setShortLocalName($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setShortEnName($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setBboxSwLat($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setBboxSwLng($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setBboxNeLat($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setBboxNeLng($arr[$keys[16]]);
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
     * @return $this|\keeko\core\model\Country The current object, for fluid interface
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
        $criteria = new Criteria(CountryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CountryTableMap::COL_ISO_NR)) {
            $criteria->add(CountryTableMap::COL_ISO_NR, $this->iso_nr);
        }
        if ($this->isColumnModified(CountryTableMap::COL_ALPHA_2)) {
            $criteria->add(CountryTableMap::COL_ALPHA_2, $this->alpha_2);
        }
        if ($this->isColumnModified(CountryTableMap::COL_ALPHA_3)) {
            $criteria->add(CountryTableMap::COL_ALPHA_3, $this->alpha_3);
        }
        if ($this->isColumnModified(CountryTableMap::COL_IOC)) {
            $criteria->add(CountryTableMap::COL_IOC, $this->ioc);
        }
        if ($this->isColumnModified(CountryTableMap::COL_CAPITAL)) {
            $criteria->add(CountryTableMap::COL_CAPITAL, $this->capital);
        }
        if ($this->isColumnModified(CountryTableMap::COL_TLD)) {
            $criteria->add(CountryTableMap::COL_TLD, $this->tld);
        }
        if ($this->isColumnModified(CountryTableMap::COL_PHONE)) {
            $criteria->add(CountryTableMap::COL_PHONE, $this->phone);
        }
        if ($this->isColumnModified(CountryTableMap::COL_TERRITORY_ISO_NR)) {
            $criteria->add(CountryTableMap::COL_TERRITORY_ISO_NR, $this->territory_iso_nr);
        }
        if ($this->isColumnModified(CountryTableMap::COL_CURRENCY_ISO_NR)) {
            $criteria->add(CountryTableMap::COL_CURRENCY_ISO_NR, $this->currency_iso_nr);
        }
        if ($this->isColumnModified(CountryTableMap::COL_OFFICIAL_LOCAL_NAME)) {
            $criteria->add(CountryTableMap::COL_OFFICIAL_LOCAL_NAME, $this->official_local_name);
        }
        if ($this->isColumnModified(CountryTableMap::COL_OFFICIAL_EN_NAME)) {
            $criteria->add(CountryTableMap::COL_OFFICIAL_EN_NAME, $this->official_en_name);
        }
        if ($this->isColumnModified(CountryTableMap::COL_SHORT_LOCAL_NAME)) {
            $criteria->add(CountryTableMap::COL_SHORT_LOCAL_NAME, $this->short_local_name);
        }
        if ($this->isColumnModified(CountryTableMap::COL_SHORT_EN_NAME)) {
            $criteria->add(CountryTableMap::COL_SHORT_EN_NAME, $this->short_en_name);
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
        $criteria = new Criteria(CountryTableMap::DATABASE_NAME);
        $criteria->add(CountryTableMap::COL_ISO_NR, $this->iso_nr);

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
        $validPk = null !== $this->getIsoNr();

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
        return $this->getIsoNr();
    }

    /**
     * Generic method to set the primary key (iso_nr column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setIsoNr($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getIsoNr();
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
        $copyObj->setIsoNr($this->getIsoNr());
        $copyObj->setAlpha2($this->getAlpha2());
        $copyObj->setAlpha3($this->getAlpha3());
        $copyObj->setIoc($this->getIoc());
        $copyObj->setCapital($this->getCapital());
        $copyObj->setTld($this->getTld());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setTerritoryIsoNr($this->getTerritoryIsoNr());
        $copyObj->setCurrencyIsoNr($this->getCurrencyIsoNr());
        $copyObj->setOfficialLocalName($this->getOfficialLocalName());
        $copyObj->setOfficialEnName($this->getOfficialEnName());
        $copyObj->setShortLocalName($this->getShortLocalName());
        $copyObj->setShortEnName($this->getShortEnName());
        $copyObj->setBboxSwLat($this->getBboxSwLat());
        $copyObj->setBboxSwLng($this->getBboxSwLng());
        $copyObj->setBboxNeLat($this->getBboxNeLat());
        $copyObj->setBboxNeLng($this->getBboxNeLng());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getLocalizations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLocalization($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSubdivisions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSubdivision($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUser($relObj->copy($deepCopy));
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
     * Declares an association between this object and a ChildTerritory object.
     *
     * @param  ChildTerritory $v
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTerritory(ChildTerritory $v = null)
    {
        if ($v === null) {
            $this->setTerritoryIsoNr(NULL);
        } else {
            $this->setTerritoryIsoNr($v->getIsoNr());
        }

        $this->aTerritory = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTerritory object, it will not be re-added.
        if ($v !== null) {
            $v->addCountry($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildTerritory object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildTerritory The associated ChildTerritory object.
     * @throws PropelException
     */
    public function getTerritory(ConnectionInterface $con = null)
    {
        if ($this->aTerritory === null && ($this->territory_iso_nr !== null)) {
            $this->aTerritory = ChildTerritoryQuery::create()->findPk($this->territory_iso_nr, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTerritory->addCountries($this);
             */
        }

        return $this->aTerritory;
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
            $this->setCurrencyIsoNr(NULL);
        } else {
            $this->setCurrencyIsoNr($v->getIsoNr());
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
        if ($this->aCurrency === null && ($this->currency_iso_nr !== null)) {
            $this->aCurrency = ChildCurrencyQuery::create()->findPk($this->currency_iso_nr, $con);
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Localization' == $relationName) {
            return $this->initLocalizations();
        }
        if ('Subdivision' == $relationName) {
            return $this->initSubdivisions();
        }
        if ('User' == $relationName) {
            return $this->initUsers();
        }
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
     * Reset is the collLocalizations collection loaded partially.
     */
    public function resetPartialLocalizations($v = true)
    {
        $this->collLocalizationsPartial = $v;
    }

    /**
     * Initializes the collLocalizations collection.
     *
     * By default this just sets the collLocalizations collection to an empty array (like clearcollLocalizations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLocalizations($overrideExisting = true)
    {
        if (null !== $this->collLocalizations && !$overrideExisting) {
            return;
        }
        $this->collLocalizations = new ObjectCollection();
        $this->collLocalizations->setModel('\keeko\core\model\Localization');
    }

    /**
     * Gets an array of ChildLocalization objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCountry is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     * @throws PropelException
     */
    public function getLocalizations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsPartial && !$this->isNew();
        if (null === $this->collLocalizations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLocalizations) {
                // return empty collection
                $this->initLocalizations();
            } else {
                $collLocalizations = ChildLocalizationQuery::create(null, $criteria)
                    ->filterByCountry($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLocalizationsPartial && count($collLocalizations)) {
                        $this->initLocalizations(false);

                        foreach ($collLocalizations as $obj) {
                            if (false == $this->collLocalizations->contains($obj)) {
                                $this->collLocalizations->append($obj);
                            }
                        }

                        $this->collLocalizationsPartial = true;
                    }

                    return $collLocalizations;
                }

                if ($partial && $this->collLocalizations) {
                    foreach ($this->collLocalizations as $obj) {
                        if ($obj->isNew()) {
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
     * Sets a collection of ChildLocalization objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $localizations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCountry The current object (for fluent API support)
     */
    public function setLocalizations(Collection $localizations, ConnectionInterface $con = null)
    {
        /** @var ChildLocalization[] $localizationsToDelete */
        $localizationsToDelete = $this->getLocalizations(new Criteria(), $con)->diff($localizations);


        $this->localizationsScheduledForDeletion = $localizationsToDelete;

        foreach ($localizationsToDelete as $localizationRemoved) {
            $localizationRemoved->setCountry(null);
        }

        $this->collLocalizations = null;
        foreach ($localizations as $localization) {
            $this->addLocalization($localization);
        }

        $this->collLocalizations = $localizations;
        $this->collLocalizationsPartial = false;

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
    public function countLocalizations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLocalizationsPartial && !$this->isNew();
        if (null === $this->collLocalizations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLocalizations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLocalizations());
            }

            $query = ChildLocalizationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCountry($this)
                ->count($con);
        }

        return count($this->collLocalizations);
    }

    /**
     * Method called to associate a ChildLocalization object to this object
     * through the ChildLocalization foreign key attribute.
     *
     * @param  ChildLocalization $l ChildLocalization
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
     */
    public function addLocalization(ChildLocalization $l)
    {
        if ($this->collLocalizations === null) {
            $this->initLocalizations();
            $this->collLocalizationsPartial = true;
        }

        if (!$this->collLocalizations->contains($l)) {
            $this->doAddLocalization($l);
        }

        return $this;
    }

    /**
     * @param ChildLocalization $localization The ChildLocalization object to add.
     */
    protected function doAddLocalization(ChildLocalization $localization)
    {
        $this->collLocalizations[]= $localization;
        $localization->setCountry($this);
    }

    /**
     * @param  ChildLocalization $localization The ChildLocalization object to remove.
     * @return $this|ChildCountry The current object (for fluent API support)
     */
    public function removeLocalization(ChildLocalization $localization)
    {
        if ($this->getLocalizations()->contains($localization)) {
            $pos = $this->collLocalizations->search($localization);
            $this->collLocalizations->remove($pos);
            if (null === $this->localizationsScheduledForDeletion) {
                $this->localizationsScheduledForDeletion = clone $this->collLocalizations;
                $this->localizationsScheduledForDeletion->clear();
            }
            $this->localizationsScheduledForDeletion[]= $localization;
            $localization->setCountry(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Localizations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsJoinLocalizationRelatedByParentId(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('LocalizationRelatedByParentId', $joinBehavior);

        return $this->getLocalizations($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Localizations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsJoinLanguage(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('Language', $joinBehavior);

        return $this->getLocalizations($query, $con);
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
    public function getSubdivisionsJoinSubdivisionType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSubdivisionQuery::create(null, $criteria);
        $query->joinWith('SubdivisionType', $joinBehavior);

        return $this->getSubdivisions($query, $con);
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
     * If this ChildCountry is new, it will return
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
                    ->filterByCountry($this)
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
     * @return $this|ChildCountry The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $usersToDelete */
        $usersToDelete = $this->getUsers(new Criteria(), $con)->diff($users);


        $this->usersScheduledForDeletion = $usersToDelete;

        foreach ($usersToDelete as $userRemoved) {
            $userRemoved->setCountry(null);
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
                ->filterByCountry($this)
                ->count($con);
        }

        return count($this->collUsers);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\keeko\core\model\Country The current object (for fluent API support)
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
        $user->setCountry($this);
    }

    /**
     * @param  ChildUser $user The ChildUser object to remove.
     * @return $this|ChildCountry The current object (for fluent API support)
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
            $user->setCountry(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Users from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getUsersJoinSubdivision(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserQuery::create(null, $criteria);
        $query->joinWith('Subdivision', $joinBehavior);

        return $this->getUsers($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aTerritory) {
            $this->aTerritory->removeCountry($this);
        }
        if (null !== $this->aCurrency) {
            $this->aCurrency->removeCountry($this);
        }
        $this->iso_nr = null;
        $this->alpha_2 = null;
        $this->alpha_3 = null;
        $this->ioc = null;
        $this->capital = null;
        $this->tld = null;
        $this->phone = null;
        $this->territory_iso_nr = null;
        $this->currency_iso_nr = null;
        $this->official_local_name = null;
        $this->official_en_name = null;
        $this->short_local_name = null;
        $this->short_en_name = null;
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
            if ($this->collLocalizations) {
                foreach ($this->collLocalizations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSubdivisions) {
                foreach ($this->collSubdivisions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLocalizations = null;
        $this->collSubdivisions = null;
        $this->collUsers = null;
        $this->aTerritory = null;
        $this->aCurrency = null;
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
