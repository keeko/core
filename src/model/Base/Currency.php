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
use keeko\core\model\Map\CurrencyTableMap;

/**
 * Base class that represents a row from the 'kk_currency' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Currency implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\CurrencyTableMap';


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
     * The value for the alpha_3 field.
     * @var        string
     */
    protected $alpha_3;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the symbol_left field.
     * @var        string
     */
    protected $symbol_left;

    /**
     * The value for the symbol_right field.
     * @var        string
     */
    protected $symbol_right;

    /**
     * The value for the decimal_digits field.
     * @var        int
     */
    protected $decimal_digits;

    /**
     * The value for the sub_divisor field.
     * @var        int
     */
    protected $sub_divisor;

    /**
     * The value for the sub_symbol_left field.
     * @var        string
     */
    protected $sub_symbol_left;

    /**
     * The value for the sub_symbol_right field.
     * @var        string
     */
    protected $sub_symbol_right;

    /**
     * @var        ObjectCollection|ChildCountry[] Collection to store aggregation of ChildCountry objects.
     */
    protected $collCountries;
    protected $collCountriesPartial;

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
    protected $countriesScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\Currency object.
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
     * Compares this with another <code>Currency</code> instance.  If
     * <code>obj</code> is an instance of <code>Currency</code>, delegates to
     * <code>equals(Currency)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Currency The current object, for fluid interface
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
     * Get the [alpha_3] column value.
     *
     * @return string
     */
    public function getAlpha3()
    {
        return $this->alpha_3;
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
     * Get the [symbol_left] column value.
     *
     * @return string
     */
    public function getSymbolLeft()
    {
        return $this->symbol_left;
    }

    /**
     * Get the [symbol_right] column value.
     *
     * @return string
     */
    public function getSymbolRight()
    {
        return $this->symbol_right;
    }

    /**
     * Get the [decimal_digits] column value.
     *
     * @return int
     */
    public function getDecimalDigits()
    {
        return $this->decimal_digits;
    }

    /**
     * Get the [sub_divisor] column value.
     *
     * @return int
     */
    public function getSubDivisor()
    {
        return $this->sub_divisor;
    }

    /**
     * Get the [sub_symbol_left] column value.
     *
     * @return string
     */
    public function getSubSymbolLeft()
    {
        return $this->sub_symbol_left;
    }

    /**
     * Get the [sub_symbol_right] column value.
     *
     * @return string
     */
    public function getSubSymbolRight()
    {
        return $this->sub_symbol_right;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [numeric] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setNumeric($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->numeric !== $v) {
            $this->numeric = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_NUMERIC] = true;
        }

        return $this;
    } // setNumeric()

    /**
     * Set the value of [alpha_3] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setAlpha3($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alpha_3 !== $v) {
            $this->alpha_3 = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_ALPHA_3] = true;
        }

        return $this;
    } // setAlpha3()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [symbol_left] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setSymbolLeft($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->symbol_left !== $v) {
            $this->symbol_left = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_SYMBOL_LEFT] = true;
        }

        return $this;
    } // setSymbolLeft()

    /**
     * Set the value of [symbol_right] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setSymbolRight($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->symbol_right !== $v) {
            $this->symbol_right = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_SYMBOL_RIGHT] = true;
        }

        return $this;
    } // setSymbolRight()

    /**
     * Set the value of [decimal_digits] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setDecimalDigits($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->decimal_digits !== $v) {
            $this->decimal_digits = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_DECIMAL_DIGITS] = true;
        }

        return $this;
    } // setDecimalDigits()

    /**
     * Set the value of [sub_divisor] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setSubDivisor($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sub_divisor !== $v) {
            $this->sub_divisor = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_SUB_DIVISOR] = true;
        }

        return $this;
    } // setSubDivisor()

    /**
     * Set the value of [sub_symbol_left] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setSubSymbolLeft($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sub_symbol_left !== $v) {
            $this->sub_symbol_left = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_SUB_SYMBOL_LEFT] = true;
        }

        return $this;
    } // setSubSymbolLeft()

    /**
     * Set the value of [sub_symbol_right] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function setSubSymbolRight($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sub_symbol_right !== $v) {
            $this->sub_symbol_right = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_SUB_SYMBOL_RIGHT] = true;
        }

        return $this;
    } // setSubSymbolRight()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CurrencyTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CurrencyTableMap::translateFieldName('Numeric', TableMap::TYPE_PHPNAME, $indexType)];
            $this->numeric = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CurrencyTableMap::translateFieldName('Alpha3', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_3 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CurrencyTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CurrencyTableMap::translateFieldName('SymbolLeft', TableMap::TYPE_PHPNAME, $indexType)];
            $this->symbol_left = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CurrencyTableMap::translateFieldName('SymbolRight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->symbol_right = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CurrencyTableMap::translateFieldName('DecimalDigits', TableMap::TYPE_PHPNAME, $indexType)];
            $this->decimal_digits = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CurrencyTableMap::translateFieldName('SubDivisor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sub_divisor = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CurrencyTableMap::translateFieldName('SubSymbolLeft', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sub_symbol_left = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CurrencyTableMap::translateFieldName('SubSymbolRight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sub_symbol_right = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = CurrencyTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\Currency'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCurrencyQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCountries = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Currency::setDeleted()
     * @see Currency::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCurrencyQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
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
                CurrencyTableMap::addInstanceToPool($this);
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
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->countriesScheduledForDeletion !== null) {
                if (!$this->countriesScheduledForDeletion->isEmpty()) {
                    \keeko\core\model\CountryQuery::create()
                        ->filterByPrimaryKeys($this->countriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->countriesScheduledForDeletion = null;
                }
            }

            if ($this->collCountries !== null) {
                foreach ($this->collCountries as $referrerFK) {
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

        $this->modifiedColumns[CurrencyTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CurrencyTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CurrencyTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_NUMERIC)) {
            $modifiedColumns[':p' . $index++]  = '`numeric`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_ALPHA_3)) {
            $modifiedColumns[':p' . $index++]  = '`alpha_3`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SYMBOL_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '`symbol_left`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SYMBOL_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`symbol_right`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_DECIMAL_DIGITS)) {
            $modifiedColumns[':p' . $index++]  = '`decimal_digits`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SUB_DIVISOR)) {
            $modifiedColumns[':p' . $index++]  = '`sub_divisor`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SUB_SYMBOL_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '`sub_symbol_left`';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SUB_SYMBOL_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`sub_symbol_right`';
        }

        $sql = sprintf(
            'INSERT INTO `kk_currency` (%s) VALUES (%s)',
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
                    case '`numeric`':
                        $stmt->bindValue($identifier, $this->numeric, PDO::PARAM_INT);
                        break;
                    case '`alpha_3`':
                        $stmt->bindValue($identifier, $this->alpha_3, PDO::PARAM_STR);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`symbol_left`':
                        $stmt->bindValue($identifier, $this->symbol_left, PDO::PARAM_STR);
                        break;
                    case '`symbol_right`':
                        $stmt->bindValue($identifier, $this->symbol_right, PDO::PARAM_STR);
                        break;
                    case '`decimal_digits`':
                        $stmt->bindValue($identifier, $this->decimal_digits, PDO::PARAM_INT);
                        break;
                    case '`sub_divisor`':
                        $stmt->bindValue($identifier, $this->sub_divisor, PDO::PARAM_INT);
                        break;
                    case '`sub_symbol_left`':
                        $stmt->bindValue($identifier, $this->sub_symbol_left, PDO::PARAM_STR);
                        break;
                    case '`sub_symbol_right`':
                        $stmt->bindValue($identifier, $this->sub_symbol_right, PDO::PARAM_STR);
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
        $pos = CurrencyTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAlpha3();
                break;
            case 3:
                return $this->getName();
                break;
            case 4:
                return $this->getSymbolLeft();
                break;
            case 5:
                return $this->getSymbolRight();
                break;
            case 6:
                return $this->getDecimalDigits();
                break;
            case 7:
                return $this->getSubDivisor();
                break;
            case 8:
                return $this->getSubSymbolLeft();
                break;
            case 9:
                return $this->getSubSymbolRight();
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

        if (isset($alreadyDumpedObjects['Currency'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Currency'][$this->hashCode()] = true;
        $keys = CurrencyTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNumeric(),
            $keys[2] => $this->getAlpha3(),
            $keys[3] => $this->getName(),
            $keys[4] => $this->getSymbolLeft(),
            $keys[5] => $this->getSymbolRight(),
            $keys[6] => $this->getDecimalDigits(),
            $keys[7] => $this->getSubDivisor(),
            $keys[8] => $this->getSubSymbolLeft(),
            $keys[9] => $this->getSubSymbolRight(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCountries) {

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

                $result[$key] = $this->collCountries->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\keeko\core\model\Currency
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CurrencyTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\keeko\core\model\Currency
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setNumeric($value);
                break;
            case 2:
                $this->setAlpha3($value);
                break;
            case 3:
                $this->setName($value);
                break;
            case 4:
                $this->setSymbolLeft($value);
                break;
            case 5:
                $this->setSymbolRight($value);
                break;
            case 6:
                $this->setDecimalDigits($value);
                break;
            case 7:
                $this->setSubDivisor($value);
                break;
            case 8:
                $this->setSubSymbolLeft($value);
                break;
            case 9:
                $this->setSubSymbolRight($value);
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
        $keys = CurrencyTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setNumeric($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAlpha3($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setSymbolLeft($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setSymbolRight($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setDecimalDigits($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSubDivisor($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setSubSymbolLeft($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setSubSymbolRight($arr[$keys[9]]);
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
     * @return $this|\keeko\core\model\Currency The current object, for fluid interface
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
        $criteria = new Criteria(CurrencyTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CurrencyTableMap::COL_ID)) {
            $criteria->add(CurrencyTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_NUMERIC)) {
            $criteria->add(CurrencyTableMap::COL_NUMERIC, $this->numeric);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_ALPHA_3)) {
            $criteria->add(CurrencyTableMap::COL_ALPHA_3, $this->alpha_3);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_NAME)) {
            $criteria->add(CurrencyTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SYMBOL_LEFT)) {
            $criteria->add(CurrencyTableMap::COL_SYMBOL_LEFT, $this->symbol_left);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SYMBOL_RIGHT)) {
            $criteria->add(CurrencyTableMap::COL_SYMBOL_RIGHT, $this->symbol_right);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_DECIMAL_DIGITS)) {
            $criteria->add(CurrencyTableMap::COL_DECIMAL_DIGITS, $this->decimal_digits);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SUB_DIVISOR)) {
            $criteria->add(CurrencyTableMap::COL_SUB_DIVISOR, $this->sub_divisor);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SUB_SYMBOL_LEFT)) {
            $criteria->add(CurrencyTableMap::COL_SUB_SYMBOL_LEFT, $this->sub_symbol_left);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SUB_SYMBOL_RIGHT)) {
            $criteria->add(CurrencyTableMap::COL_SUB_SYMBOL_RIGHT, $this->sub_symbol_right);
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
        $criteria = ChildCurrencyQuery::create();
        $criteria->add(CurrencyTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\Currency (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNumeric($this->getNumeric());
        $copyObj->setAlpha3($this->getAlpha3());
        $copyObj->setName($this->getName());
        $copyObj->setSymbolLeft($this->getSymbolLeft());
        $copyObj->setSymbolRight($this->getSymbolRight());
        $copyObj->setDecimalDigits($this->getDecimalDigits());
        $copyObj->setSubDivisor($this->getSubDivisor());
        $copyObj->setSubSymbolLeft($this->getSubSymbolLeft());
        $copyObj->setSubSymbolRight($this->getSubSymbolRight());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCountries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCountry($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\Currency Clone of current object.
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
        if ('Country' == $relationName) {
            return $this->initCountries();
        }
    }

    /**
     * Clears out the collCountries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCountries()
     */
    public function clearCountries()
    {
        $this->collCountries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCountries collection loaded partially.
     */
    public function resetPartialCountries($v = true)
    {
        $this->collCountriesPartial = $v;
    }

    /**
     * Initializes the collCountries collection.
     *
     * By default this just sets the collCountries collection to an empty array (like clearcollCountries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCountries($overrideExisting = true)
    {
        if (null !== $this->collCountries && !$overrideExisting) {
            return;
        }
        $this->collCountries = new ObjectCollection();
        $this->collCountries->setModel('\keeko\core\model\Country');
    }

    /**
     * Gets an array of ChildCountry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCurrency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     * @throws PropelException
     */
    public function getCountries(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCountriesPartial && !$this->isNew();
        if (null === $this->collCountries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCountries) {
                // return empty collection
                $this->initCountries();
            } else {
                $collCountries = ChildCountryQuery::create(null, $criteria)
                    ->filterByCurrency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCountriesPartial && count($collCountries)) {
                        $this->initCountries(false);

                        foreach ($collCountries as $obj) {
                            if (false == $this->collCountries->contains($obj)) {
                                $this->collCountries->append($obj);
                            }
                        }

                        $this->collCountriesPartial = true;
                    }

                    return $collCountries;
                }

                if ($partial && $this->collCountries) {
                    foreach ($this->collCountries as $obj) {
                        if ($obj->isNew()) {
                            $collCountries[] = $obj;
                        }
                    }
                }

                $this->collCountries = $collCountries;
                $this->collCountriesPartial = false;
            }
        }

        return $this->collCountries;
    }

    /**
     * Sets a collection of ChildCountry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $countries A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function setCountries(Collection $countries, ConnectionInterface $con = null)
    {
        /** @var ChildCountry[] $countriesToDelete */
        $countriesToDelete = $this->getCountries(new Criteria(), $con)->diff($countries);


        $this->countriesScheduledForDeletion = $countriesToDelete;

        foreach ($countriesToDelete as $countryRemoved) {
            $countryRemoved->setCurrency(null);
        }

        $this->collCountries = null;
        foreach ($countries as $country) {
            $this->addCountry($country);
        }

        $this->collCountries = $countries;
        $this->collCountriesPartial = false;

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
    public function countCountries(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCountriesPartial && !$this->isNew();
        if (null === $this->collCountries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCountries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCountries());
            }

            $query = ChildCountryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrency($this)
                ->count($con);
        }

        return count($this->collCountries);
    }

    /**
     * Method called to associate a ChildCountry object to this object
     * through the ChildCountry foreign key attribute.
     *
     * @param  ChildCountry $l ChildCountry
     * @return $this|\keeko\core\model\Currency The current object (for fluent API support)
     */
    public function addCountry(ChildCountry $l)
    {
        if ($this->collCountries === null) {
            $this->initCountries();
            $this->collCountriesPartial = true;
        }

        if (!$this->collCountries->contains($l)) {
            $this->doAddCountry($l);
        }

        return $this;
    }

    /**
     * @param ChildCountry $country The ChildCountry object to add.
     */
    protected function doAddCountry(ChildCountry $country)
    {
        $this->collCountries[]= $country;
        $country->setCurrency($this);
    }

    /**
     * @param  ChildCountry $country The ChildCountry object to remove.
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function removeCountry(ChildCountry $country)
    {
        if ($this->getCountries()->contains($country)) {
            $pos = $this->collCountries->search($country);
            $this->collCountries->remove($pos);
            if (null === $this->countriesScheduledForDeletion) {
                $this->countriesScheduledForDeletion = clone $this->collCountries;
                $this->countriesScheduledForDeletion->clear();
            }
            $this->countriesScheduledForDeletion[]= clone $country;
            $country->setCurrency(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related Countries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesJoinContinent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Continent', $joinBehavior);

        return $this->getCountries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related Countries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesJoinType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Type', $joinBehavior);

        return $this->getCountries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related Countries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesJoinSubtype(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('Subtype', $joinBehavior);

        return $this->getCountries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related Countries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     */
    public function getCountriesJoinCountryRelatedBySovereignityId(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCountryQuery::create(null, $criteria);
        $query->joinWith('CountryRelatedBySovereignityId', $joinBehavior);

        return $this->getCountries($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->numeric = null;
        $this->alpha_3 = null;
        $this->name = null;
        $this->symbol_left = null;
        $this->symbol_right = null;
        $this->decimal_digits = null;
        $this->sub_divisor = null;
        $this->sub_symbol_left = null;
        $this->sub_symbol_right = null;
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
            if ($this->collCountries) {
                foreach ($this->collCountries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCountries = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CurrencyTableMap::DEFAULT_STRING_FORMAT);
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
