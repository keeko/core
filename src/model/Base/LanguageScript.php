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
use keeko\core\model\Language as ChildLanguage;
use keeko\core\model\LanguageQuery as ChildLanguageQuery;
use keeko\core\model\LanguageScript as ChildLanguageScript;
use keeko\core\model\LanguageScriptQuery as ChildLanguageScriptQuery;
use keeko\core\model\Localization as ChildLocalization;
use keeko\core\model\LocalizationQuery as ChildLocalizationQuery;
use keeko\core\model\Map\LanguageScriptTableMap;

/**
 * Base class that represents a row from the 'kk_language_script' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class LanguageScript implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\keeko\\core\\model\\Map\\LanguageScriptTableMap';


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
     * The value for the alpha_4 field.
     * @var        string
     */
    protected $alpha_4;

    /**
     * The value for the numeric field.
     * @var        int
     */
    protected $numeric;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the alias field.
     * @var        string
     */
    protected $alias;

    /**
     * The value for the direction field.
     * @var        string
     */
    protected $direction;

    /**
     * @var        ObjectCollection|ChildLanguage[] Collection to store aggregation of ChildLanguage objects.
     */
    protected $collLanguages;
    protected $collLanguagesPartial;

    /**
     * @var        ObjectCollection|ChildLocalization[] Collection to store aggregation of ChildLocalization objects.
     */
    protected $collLocalizations;
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
     * @var ObjectCollection|ChildLanguage[]
     */
    protected $languagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocalization[]
     */
    protected $localizationsScheduledForDeletion = null;

    /**
     * Initializes internal state of keeko\core\model\Base\LanguageScript object.
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
     * Compares this with another <code>LanguageScript</code> instance.  If
     * <code>obj</code> is an instance of <code>LanguageScript</code>, delegates to
     * <code>equals(LanguageScript)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|LanguageScript The current object, for fluid interface
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
     * Get the [alpha_4] column value.
     *
     * @return string
     */
    public function getAlpha4()
    {
        return $this->alpha_4;
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [alias] column value.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Get the [direction] column value.
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\LanguageScript The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LanguageScriptTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [alpha_4] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\LanguageScript The current object (for fluent API support)
     */
    public function setAlpha4($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alpha_4 !== $v) {
            $this->alpha_4 = $v;
            $this->modifiedColumns[LanguageScriptTableMap::COL_ALPHA_4] = true;
        }

        return $this;
    } // setAlpha4()

    /**
     * Set the value of [numeric] column.
     *
     * @param int $v new value
     * @return $this|\keeko\core\model\LanguageScript The current object (for fluent API support)
     */
    public function setNumeric($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->numeric !== $v) {
            $this->numeric = $v;
            $this->modifiedColumns[LanguageScriptTableMap::COL_NUMERIC] = true;
        }

        return $this;
    } // setNumeric()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\LanguageScript The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[LanguageScriptTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [alias] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\LanguageScript The current object (for fluent API support)
     */
    public function setAlias($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alias !== $v) {
            $this->alias = $v;
            $this->modifiedColumns[LanguageScriptTableMap::COL_ALIAS] = true;
        }

        return $this;
    } // setAlias()

    /**
     * Set the value of [direction] column.
     *
     * @param string $v new value
     * @return $this|\keeko\core\model\LanguageScript The current object (for fluent API support)
     */
    public function setDirection($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->direction !== $v) {
            $this->direction = $v;
            $this->modifiedColumns[LanguageScriptTableMap::COL_DIRECTION] = true;
        }

        return $this;
    } // setDirection()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LanguageScriptTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LanguageScriptTableMap::translateFieldName('Alpha4', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alpha_4 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LanguageScriptTableMap::translateFieldName('Numeric', TableMap::TYPE_PHPNAME, $indexType)];
            $this->numeric = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LanguageScriptTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LanguageScriptTableMap::translateFieldName('Alias', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alias = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LanguageScriptTableMap::translateFieldName('Direction', TableMap::TYPE_PHPNAME, $indexType)];
            $this->direction = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = LanguageScriptTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\keeko\\core\\model\\LanguageScript'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(LanguageScriptTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLanguageScriptQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collLanguages = null;

            $this->collLocalizations = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see LanguageScript::setDeleted()
     * @see LanguageScript::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageScriptTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLanguageScriptQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageScriptTableMap::DATABASE_NAME);
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
                LanguageScriptTableMap::addInstanceToPool($this);
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

            if ($this->languagesScheduledForDeletion !== null) {
                if (!$this->languagesScheduledForDeletion->isEmpty()) {
                    foreach ($this->languagesScheduledForDeletion as $language) {
                        // need to save related object because we set the relation to null
                        $language->save($con);
                    }
                    $this->languagesScheduledForDeletion = null;
                }
            }

            if ($this->collLanguages !== null) {
                foreach ($this->collLanguages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[LanguageScriptTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LanguageScriptTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LanguageScriptTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_ALPHA_4)) {
            $modifiedColumns[':p' . $index++]  = '`alpha_4`';
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_NUMERIC)) {
            $modifiedColumns[':p' . $index++]  = '`numeric`';
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_ALIAS)) {
            $modifiedColumns[':p' . $index++]  = '`alias`';
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_DIRECTION)) {
            $modifiedColumns[':p' . $index++]  = '`direction`';
        }

        $sql = sprintf(
            'INSERT INTO `kk_language_script` (%s) VALUES (%s)',
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
                    case '`alpha_4`':
                        $stmt->bindValue($identifier, $this->alpha_4, PDO::PARAM_STR);
                        break;
                    case '`numeric`':
                        $stmt->bindValue($identifier, $this->numeric, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`alias`':
                        $stmt->bindValue($identifier, $this->alias, PDO::PARAM_STR);
                        break;
                    case '`direction`':
                        $stmt->bindValue($identifier, $this->direction, PDO::PARAM_STR);
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
        $pos = LanguageScriptTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAlpha4();
                break;
            case 2:
                return $this->getNumeric();
                break;
            case 3:
                return $this->getName();
                break;
            case 4:
                return $this->getAlias();
                break;
            case 5:
                return $this->getDirection();
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

        if (isset($alreadyDumpedObjects['LanguageScript'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['LanguageScript'][$this->hashCode()] = true;
        $keys = LanguageScriptTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAlpha4(),
            $keys[2] => $this->getNumeric(),
            $keys[3] => $this->getName(),
            $keys[4] => $this->getAlias(),
            $keys[5] => $this->getDirection(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collLanguages) {

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

                $result[$key] = $this->collLanguages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLocalizations) {

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

                $result[$key] = $this->collLocalizations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\keeko\core\model\LanguageScript
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LanguageScriptTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\keeko\core\model\LanguageScript
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setAlpha4($value);
                break;
            case 2:
                $this->setNumeric($value);
                break;
            case 3:
                $this->setName($value);
                break;
            case 4:
                $this->setAlias($value);
                break;
            case 5:
                $this->setDirection($value);
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
        $keys = LanguageScriptTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAlpha4($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setNumeric($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAlias($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDirection($arr[$keys[5]]);
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
     * @return $this|\keeko\core\model\LanguageScript The current object, for fluid interface
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
        $criteria = new Criteria(LanguageScriptTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LanguageScriptTableMap::COL_ID)) {
            $criteria->add(LanguageScriptTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_ALPHA_4)) {
            $criteria->add(LanguageScriptTableMap::COL_ALPHA_4, $this->alpha_4);
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_NUMERIC)) {
            $criteria->add(LanguageScriptTableMap::COL_NUMERIC, $this->numeric);
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_NAME)) {
            $criteria->add(LanguageScriptTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_ALIAS)) {
            $criteria->add(LanguageScriptTableMap::COL_ALIAS, $this->alias);
        }
        if ($this->isColumnModified(LanguageScriptTableMap::COL_DIRECTION)) {
            $criteria->add(LanguageScriptTableMap::COL_DIRECTION, $this->direction);
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
        $criteria = ChildLanguageScriptQuery::create();
        $criteria->add(LanguageScriptTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \keeko\core\model\LanguageScript (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAlpha4($this->getAlpha4());
        $copyObj->setNumeric($this->getNumeric());
        $copyObj->setName($this->getName());
        $copyObj->setAlias($this->getAlias());
        $copyObj->setDirection($this->getDirection());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getLanguages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLanguage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLocalizations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLocalization($relObj->copy($deepCopy));
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
     * @return \keeko\core\model\LanguageScript Clone of current object.
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
        if ('Language' == $relationName) {
            return $this->initLanguages();
        }
        if ('Localization' == $relationName) {
            return $this->initLocalizations();
        }
    }

    /**
     * Clears out the collLanguages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLanguages()
     */
    public function clearLanguages()
    {
        $this->collLanguages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLanguages collection loaded partially.
     */
    public function resetPartialLanguages($v = true)
    {
        $this->collLanguagesPartial = $v;
    }

    /**
     * Initializes the collLanguages collection.
     *
     * By default this just sets the collLanguages collection to an empty array (like clearcollLanguages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLanguages($overrideExisting = true)
    {
        if (null !== $this->collLanguages && !$overrideExisting) {
            return;
        }
        $this->collLanguages = new ObjectCollection();
        $this->collLanguages->setModel('\keeko\core\model\Language');
    }

    /**
     * Gets an array of ChildLanguage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLanguageScript is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     * @throws PropelException
     */
    public function getLanguages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLanguagesPartial && !$this->isNew();
        if (null === $this->collLanguages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLanguages) {
                // return empty collection
                $this->initLanguages();
            } else {
                $collLanguages = ChildLanguageQuery::create(null, $criteria)
                    ->filterByScript($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLanguagesPartial && count($collLanguages)) {
                        $this->initLanguages(false);

                        foreach ($collLanguages as $obj) {
                            if (false == $this->collLanguages->contains($obj)) {
                                $this->collLanguages->append($obj);
                            }
                        }

                        $this->collLanguagesPartial = true;
                    }

                    return $collLanguages;
                }

                if ($partial && $this->collLanguages) {
                    foreach ($this->collLanguages as $obj) {
                        if ($obj->isNew()) {
                            $collLanguages[] = $obj;
                        }
                    }
                }

                $this->collLanguages = $collLanguages;
                $this->collLanguagesPartial = false;
            }
        }

        return $this->collLanguages;
    }

    /**
     * Sets a collection of ChildLanguage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $languages A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLanguageScript The current object (for fluent API support)
     */
    public function setLanguages(Collection $languages, ConnectionInterface $con = null)
    {
        /** @var ChildLanguage[] $languagesToDelete */
        $languagesToDelete = $this->getLanguages(new Criteria(), $con)->diff($languages);


        $this->languagesScheduledForDeletion = $languagesToDelete;

        foreach ($languagesToDelete as $languageRemoved) {
            $languageRemoved->setScript(null);
        }

        $this->collLanguages = null;
        foreach ($languages as $language) {
            $this->addLanguage($language);
        }

        $this->collLanguages = $languages;
        $this->collLanguagesPartial = false;

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
    public function countLanguages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLanguagesPartial && !$this->isNew();
        if (null === $this->collLanguages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLanguages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLanguages());
            }

            $query = ChildLanguageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByScript($this)
                ->count($con);
        }

        return count($this->collLanguages);
    }

    /**
     * Method called to associate a ChildLanguage object to this object
     * through the ChildLanguage foreign key attribute.
     *
     * @param  ChildLanguage $l ChildLanguage
     * @return $this|\keeko\core\model\LanguageScript The current object (for fluent API support)
     */
    public function addLanguage(ChildLanguage $l)
    {
        if ($this->collLanguages === null) {
            $this->initLanguages();
            $this->collLanguagesPartial = true;
        }

        if (!$this->collLanguages->contains($l)) {
            $this->doAddLanguage($l);
        }

        return $this;
    }

    /**
     * @param ChildLanguage $language The ChildLanguage object to add.
     */
    protected function doAddLanguage(ChildLanguage $language)
    {
        $this->collLanguages[]= $language;
        $language->setScript($this);
    }

    /**
     * @param  ChildLanguage $language The ChildLanguage object to remove.
     * @return $this|ChildLanguageScript The current object (for fluent API support)
     */
    public function removeLanguage(ChildLanguage $language)
    {
        if ($this->getLanguages()->contains($language)) {
            $pos = $this->collLanguages->search($language);
            $this->collLanguages->remove($pos);
            if (null === $this->languagesScheduledForDeletion) {
                $this->languagesScheduledForDeletion = clone $this->collLanguages;
                $this->languagesScheduledForDeletion->clear();
            }
            $this->languagesScheduledForDeletion[]= $language;
            $language->setScript(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LanguageScript is new, it will return
     * an empty collection; or if this LanguageScript has previously
     * been saved, it will retrieve related Languages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LanguageScript.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getLanguagesJoinLanguageRelatedByParentId(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLanguageQuery::create(null, $criteria);
        $query->joinWith('LanguageRelatedByParentId', $joinBehavior);

        return $this->getLanguages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LanguageScript is new, it will return
     * an empty collection; or if this LanguageScript has previously
     * been saved, it will retrieve related Languages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LanguageScript.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getLanguagesJoinScope(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLanguageQuery::create(null, $criteria);
        $query->joinWith('Scope', $joinBehavior);

        return $this->getLanguages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LanguageScript is new, it will return
     * an empty collection; or if this LanguageScript has previously
     * been saved, it will retrieve related Languages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LanguageScript.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getLanguagesJoinType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLanguageQuery::create(null, $criteria);
        $query->joinWith('Type', $joinBehavior);

        return $this->getLanguages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LanguageScript is new, it will return
     * an empty collection; or if this LanguageScript has previously
     * been saved, it will retrieve related Languages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LanguageScript.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getLanguagesJoinFamily(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLanguageQuery::create(null, $criteria);
        $query->joinWith('Family', $joinBehavior);

        return $this->getLanguages($query, $con);
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
     * If this ChildLanguageScript is new, it will return
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
                    ->filterByScript($this)
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
     * @return $this|ChildLanguageScript The current object (for fluent API support)
     */
    public function setLocalizations(Collection $localizations, ConnectionInterface $con = null)
    {
        /** @var ChildLocalization[] $localizationsToDelete */
        $localizationsToDelete = $this->getLocalizations(new Criteria(), $con)->diff($localizations);


        $this->localizationsScheduledForDeletion = $localizationsToDelete;

        foreach ($localizationsToDelete as $localizationRemoved) {
            $localizationRemoved->setScript(null);
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
                ->filterByScript($this)
                ->count($con);
        }

        return count($this->collLocalizations);
    }

    /**
     * Method called to associate a ChildLocalization object to this object
     * through the ChildLocalization foreign key attribute.
     *
     * @param  ChildLocalization $l ChildLocalization
     * @return $this|\keeko\core\model\LanguageScript The current object (for fluent API support)
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
        $localization->setScript($this);
    }

    /**
     * @param  ChildLocalization $localization The ChildLocalization object to remove.
     * @return $this|ChildLanguageScript The current object (for fluent API support)
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
            $localization->setScript(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LanguageScript is new, it will return
     * an empty collection; or if this LanguageScript has previously
     * been saved, it will retrieve related Localizations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LanguageScript.
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
     * Otherwise if this LanguageScript is new, it will return
     * an empty collection; or if this LanguageScript has previously
     * been saved, it will retrieve related Localizations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LanguageScript.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this LanguageScript is new, it will return
     * an empty collection; or if this LanguageScript has previously
     * been saved, it will retrieve related Localizations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in LanguageScript.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocalization[] List of ChildLocalization objects
     */
    public function getLocalizationsJoinExtLang(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocalizationQuery::create(null, $criteria);
        $query->joinWith('ExtLang', $joinBehavior);

        return $this->getLocalizations($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->alpha_4 = null;
        $this->numeric = null;
        $this->name = null;
        $this->alias = null;
        $this->direction = null;
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
            if ($this->collLanguages) {
                foreach ($this->collLanguages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLocalizations) {
                foreach ($this->collLocalizations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLanguages = null;
        $this->collLocalizations = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LanguageScriptTableMap::DEFAULT_STRING_FORMAT);
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
