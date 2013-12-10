<?php

namespace keeko\core\entities\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use \RuntimeException;
use keeko\core\entities\BlockGrid;
use keeko\core\entities\BlockGridExtraProperty;
use keeko\core\entities\BlockGridExtraPropertyQuery;
use keeko\core\entities\BlockGridPeer;
use keeko\core\entities\BlockGridQuery;
use keeko\core\entities\BlockItem;
use keeko\core\entities\BlockItemQuery;

/**
 * Base class that represents a row from the 'keeko_block_grid' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseBlockGrid extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\BlockGridPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BlockGridPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the block_item_id field.
     * @var        int
     */
    protected $block_item_id;

    /**
     * The value for the span field.
     * @var        int
     */
    protected $span;

    /**
     * @var        BlockItem
     */
    protected $aBlockItem;

    /**
     * @var        PropelObjectCollection|BlockGridExtraProperty[] Collection to store aggregation of BlockGridExtraProperty objects.
     */
    protected $collBlockGridExtraPropertys;
    protected $collBlockGridExtraPropertysPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    // extra_properties behavior

    /** the list of all single properties */
    protected $extraProperties = array();
    /** the list of all multiple properties */
    protected $multipleExtraProperties = array();
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $blockGridExtraPropertysScheduledForDeletion = null;

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
     * Get the [block_item_id] column value.
     *
     * @return int
     */
    public function getBlockItemId()
    {

        return $this->block_item_id;
    }

    /**
     * Get the [span] column value.
     *
     * @return int
     */
    public function getSpan()
    {

        return $this->span;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return BlockGrid The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = BlockGridPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [block_item_id] column.
     *
     * @param  int $v new value
     * @return BlockGrid The current object (for fluent API support)
     */
    public function setBlockItemId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->block_item_id !== $v) {
            $this->block_item_id = $v;
            $this->modifiedColumns[] = BlockGridPeer::BLOCK_ITEM_ID;
        }

        if ($this->aBlockItem !== null && $this->aBlockItem->getId() !== $v) {
            $this->aBlockItem = null;
        }


        return $this;
    } // setBlockItemId()

    /**
     * Set the value of [span] column.
     *
     * @param  int $v new value
     * @return BlockGrid The current object (for fluent API support)
     */
    public function setSpan($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->span !== $v) {
            $this->span = $v;
            $this->modifiedColumns[] = BlockGridPeer::SPAN;
        }


        return $this;
    } // setSpan()

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
        // otherwise, everything was equal, so return true
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
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->block_item_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->span = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 3; // 3 = BlockGridPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating BlockGrid object", $e);
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

        if ($this->aBlockItem !== null && $this->block_item_id !== $this->aBlockItem->getId()) {
            $this->aBlockItem = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(BlockGridPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = BlockGridPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aBlockItem = null;
            $this->collBlockGridExtraPropertys = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(BlockGridPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = BlockGridQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(BlockGridPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
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
                BlockGridPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBlockItem !== null) {
                if ($this->aBlockItem->isModified() || $this->aBlockItem->isNew()) {
                    $affectedRows += $this->aBlockItem->save($con);
                }
                $this->setBlockItem($this->aBlockItem);
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

            if ($this->blockGridExtraPropertysScheduledForDeletion !== null) {
                if (!$this->blockGridExtraPropertysScheduledForDeletion->isEmpty()) {
                    BlockGridExtraPropertyQuery::create()
                        ->filterByPrimaryKeys($this->blockGridExtraPropertysScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->blockGridExtraPropertysScheduledForDeletion = null;
                }
            }

            if ($this->collBlockGridExtraPropertys !== null) {
                foreach ($this->collBlockGridExtraPropertys as $referrerFK) {
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
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = BlockGridPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BlockGridPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BlockGridPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(BlockGridPeer::BLOCK_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = '`block_item_id`';
        }
        if ($this->isColumnModified(BlockGridPeer::SPAN)) {
            $modifiedColumns[':p' . $index++]  = '`span`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_block_grid` (%s) VALUES (%s)',
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
                    case '`block_item_id`':
                        $stmt->bindValue($identifier, $this->block_item_id, PDO::PARAM_INT);
                        break;
                    case '`span`':
                        $stmt->bindValue($identifier, $this->span, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBlockItem !== null) {
                if (!$this->aBlockItem->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBlockItem->getValidationFailures());
                }
            }


            if (($retval = BlockGridPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBlockGridExtraPropertys !== null) {
                    foreach ($this->collBlockGridExtraPropertys as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = BlockGridPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getBlockItemId();
                break;
            case 2:
                return $this->getSpan();
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
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['BlockGrid'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['BlockGrid'][$this->getPrimaryKey()] = true;
        $keys = BlockGridPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getBlockItemId(),
            $keys[2] => $this->getSpan(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aBlockItem) {
                $result['BlockItem'] = $this->aBlockItem->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collBlockGridExtraPropertys) {
                $result['BlockGridExtraPropertys'] = $this->collBlockGridExtraPropertys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = BlockGridPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setBlockItemId($value);
                break;
            case 2:
                $this->setSpan($value);
                break;
        } // switch()
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
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = BlockGridPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setBlockItemId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSpan($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BlockGridPeer::DATABASE_NAME);

        if ($this->isColumnModified(BlockGridPeer::ID)) $criteria->add(BlockGridPeer::ID, $this->id);
        if ($this->isColumnModified(BlockGridPeer::BLOCK_ITEM_ID)) $criteria->add(BlockGridPeer::BLOCK_ITEM_ID, $this->block_item_id);
        if ($this->isColumnModified(BlockGridPeer::SPAN)) $criteria->add(BlockGridPeer::SPAN, $this->span);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(BlockGridPeer::DATABASE_NAME);
        $criteria->add(BlockGridPeer::ID, $this->id);

        return $criteria;
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
     * @param  int $key Primary key.
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
     * @param object $copyObj An object of BlockGrid (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setBlockItemId($this->getBlockItemId());
        $copyObj->setSpan($this->getSpan());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getBlockGridExtraPropertys() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBlockGridExtraProperty($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
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
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return BlockGrid Clone of current object.
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
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return BlockGridPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BlockGridPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a BlockItem object.
     *
     * @param                  BlockItem $v
     * @return BlockGrid The current object (for fluent API support)
     * @throws PropelException
     */
    public function setBlockItem(BlockItem $v = null)
    {
        if ($v === null) {
            $this->setBlockItemId(NULL);
        } else {
            $this->setBlockItemId($v->getId());
        }

        $this->aBlockItem = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the BlockItem object, it will not be re-added.
        if ($v !== null) {
            $v->addBlockGrid($this);
        }


        return $this;
    }


    /**
     * Get the associated BlockItem object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return BlockItem The associated BlockItem object.
     * @throws PropelException
     */
    public function getBlockItem(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aBlockItem === null && ($this->block_item_id !== null) && $doQuery) {
            $this->aBlockItem = BlockItemQuery::create()->findPk($this->block_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aBlockItem->addBlockGrids($this);
             */
        }

        return $this->aBlockItem;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('BlockGridExtraProperty' == $relationName) {
            $this->initBlockGridExtraPropertys();
        }
    }

    /**
     * Clears out the collBlockGridExtraPropertys collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return BlockGrid The current object (for fluent API support)
     * @see        addBlockGridExtraPropertys()
     */
    public function clearBlockGridExtraPropertys()
    {
        $this->collBlockGridExtraPropertys = null; // important to set this to null since that means it is uninitialized
        $this->collBlockGridExtraPropertysPartial = null;

        return $this;
    }

    /**
     * reset is the collBlockGridExtraPropertys collection loaded partially
     *
     * @return void
     */
    public function resetPartialBlockGridExtraPropertys($v = true)
    {
        $this->collBlockGridExtraPropertysPartial = $v;
    }

    /**
     * Initializes the collBlockGridExtraPropertys collection.
     *
     * By default this just sets the collBlockGridExtraPropertys collection to an empty array (like clearcollBlockGridExtraPropertys());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBlockGridExtraPropertys($overrideExisting = true)
    {
        if (null !== $this->collBlockGridExtraPropertys && !$overrideExisting) {
            return;
        }
        $this->collBlockGridExtraPropertys = new PropelObjectCollection();
        $this->collBlockGridExtraPropertys->setModel('BlockGridExtraProperty');
    }

    /**
     * Gets an array of BlockGridExtraProperty objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this BlockGrid is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BlockGridExtraProperty[] List of BlockGridExtraProperty objects
     * @throws PropelException
     */
    public function getBlockGridExtraPropertys($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBlockGridExtraPropertysPartial && !$this->isNew();
        if (null === $this->collBlockGridExtraPropertys || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBlockGridExtraPropertys) {
                // return empty collection
                $this->initBlockGridExtraPropertys();
            } else {
                $collBlockGridExtraPropertys = BlockGridExtraPropertyQuery::create(null, $criteria)
                    ->filterByBlockGrid($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBlockGridExtraPropertysPartial && count($collBlockGridExtraPropertys)) {
                      $this->initBlockGridExtraPropertys(false);

                      foreach ($collBlockGridExtraPropertys as $obj) {
                        if (false == $this->collBlockGridExtraPropertys->contains($obj)) {
                          $this->collBlockGridExtraPropertys->append($obj);
                        }
                      }

                      $this->collBlockGridExtraPropertysPartial = true;
                    }

                    $collBlockGridExtraPropertys->getInternalIterator()->rewind();

                    return $collBlockGridExtraPropertys;
                }

                if ($partial && $this->collBlockGridExtraPropertys) {
                    foreach ($this->collBlockGridExtraPropertys as $obj) {
                        if ($obj->isNew()) {
                            $collBlockGridExtraPropertys[] = $obj;
                        }
                    }
                }

                $this->collBlockGridExtraPropertys = $collBlockGridExtraPropertys;
                $this->collBlockGridExtraPropertysPartial = false;
            }
        }

        return $this->collBlockGridExtraPropertys;
    }

    /**
     * Sets a collection of BlockGridExtraProperty objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $blockGridExtraPropertys A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return BlockGrid The current object (for fluent API support)
     */
    public function setBlockGridExtraPropertys(PropelCollection $blockGridExtraPropertys, PropelPDO $con = null)
    {
        $blockGridExtraPropertysToDelete = $this->getBlockGridExtraPropertys(new Criteria(), $con)->diff($blockGridExtraPropertys);


        $this->blockGridExtraPropertysScheduledForDeletion = $blockGridExtraPropertysToDelete;

        foreach ($blockGridExtraPropertysToDelete as $blockGridExtraPropertyRemoved) {
            $blockGridExtraPropertyRemoved->setBlockGrid(null);
        }

        $this->collBlockGridExtraPropertys = null;
        foreach ($blockGridExtraPropertys as $blockGridExtraProperty) {
            $this->addBlockGridExtraProperty($blockGridExtraProperty);
        }

        $this->collBlockGridExtraPropertys = $blockGridExtraPropertys;
        $this->collBlockGridExtraPropertysPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BlockGridExtraProperty objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BlockGridExtraProperty objects.
     * @throws PropelException
     */
    public function countBlockGridExtraPropertys(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBlockGridExtraPropertysPartial && !$this->isNew();
        if (null === $this->collBlockGridExtraPropertys || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBlockGridExtraPropertys) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBlockGridExtraPropertys());
            }
            $query = BlockGridExtraPropertyQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBlockGrid($this)
                ->count($con);
        }

        return count($this->collBlockGridExtraPropertys);
    }

    /**
     * Method called to associate a BlockGridExtraProperty object to this object
     * through the BlockGridExtraProperty foreign key attribute.
     *
     * @param    BlockGridExtraProperty $l BlockGridExtraProperty
     * @return BlockGrid The current object (for fluent API support)
     */
    public function addBlockGridExtraProperty(BlockGridExtraProperty $l)
    {
        if ($this->collBlockGridExtraPropertys === null) {
            $this->initBlockGridExtraPropertys();
            $this->collBlockGridExtraPropertysPartial = true;
        }

        if (!in_array($l, $this->collBlockGridExtraPropertys->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBlockGridExtraProperty($l);

            if ($this->blockGridExtraPropertysScheduledForDeletion and $this->blockGridExtraPropertysScheduledForDeletion->contains($l)) {
                $this->blockGridExtraPropertysScheduledForDeletion->remove($this->blockGridExtraPropertysScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BlockGridExtraProperty $blockGridExtraProperty The blockGridExtraProperty object to add.
     */
    protected function doAddBlockGridExtraProperty($blockGridExtraProperty)
    {
        $this->collBlockGridExtraPropertys[]= $blockGridExtraProperty;
        $blockGridExtraProperty->setBlockGrid($this);
    }

    /**
     * @param	BlockGridExtraProperty $blockGridExtraProperty The blockGridExtraProperty object to remove.
     * @return BlockGrid The current object (for fluent API support)
     */
    public function removeBlockGridExtraProperty($blockGridExtraProperty)
    {
        if ($this->getBlockGridExtraPropertys()->contains($blockGridExtraProperty)) {
            $this->collBlockGridExtraPropertys->remove($this->collBlockGridExtraPropertys->search($blockGridExtraProperty));
            if (null === $this->blockGridExtraPropertysScheduledForDeletion) {
                $this->blockGridExtraPropertysScheduledForDeletion = clone $this->collBlockGridExtraPropertys;
                $this->blockGridExtraPropertysScheduledForDeletion->clear();
            }
            $this->blockGridExtraPropertysScheduledForDeletion[]= clone $blockGridExtraProperty;
            $blockGridExtraProperty->setBlockGrid(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->block_item_id = null;
        $this->span = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collBlockGridExtraPropertys) {
                foreach ($this->collBlockGridExtraPropertys as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aBlockItem instanceof Persistent) {
              $this->aBlockItem->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collBlockGridExtraPropertys instanceof PropelCollection) {
            $this->collBlockGridExtraPropertys->clearIterator();
        }
        $this->collBlockGridExtraPropertys = null;
        $this->aBlockItem = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(BlockGridPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // extra_properties behavior
    /**
     * convert a value to a valid property name
     *
     * @param String $name the camelized property name
     *
     * @return String
     */
    protected function extraPropertyNameFromMethod($name)
    {
      $tmp = $name;
      $tmp = str_replace('::', '/', $tmp);
      $tmp = preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'),
                          array('\1_\2', '\1_\2'), $tmp);
      return strtolower($tmp);
    }

    /**
     * checks that the event defines a property with $propertyName
     *
     * @todo optimize to make it stop on first occurence
     *
     * @param String    $propertyName  name of the property to check.
     * @param PropelPDO $con           Optional connection object
     *
     * @return Boolean
     */
    public function hasProperty($propertyName, PropelPDO $con = null)
    {
      return $this->countPropertiesByName($propertyName, $con) > 0;
    }

    /**
     * Count the number of occurences of $propertyName.
     *
     * @param   String    $propertyName   the property to count.
     * @param   PropelPDO $con            Optional connection object
     *
     * @return  Integer
     */
    public function countPropertiesByName($propertyName, PropelPDO $con = null)
    {
      $count = 0;
      $properties = $this->getBlockGridExtraPropertys(null, $con);
      $propertyName = BlockGridPeer::normalizePropertyName($propertyName);
      foreach($properties as $property)
      {
        if($property->getPropertyName() == $propertyName)
        {
          $count++;
        }
      }
      return $count;
    }

    /**
     * Set the property with id $id.
     * can only be used with an already set property
     *
     * @param   PropelPDO $con Optional connection object
     *
     * @return BlockGrid|false
     */
    protected function setPropertyById($id, $value, PropelPDO $con = null)
    {
      $property = $this->getPropertyObjectById($id, $con);
      if($property instanceof BlockGridExtraProperty)
      {
        $property->setPropertyValue(BlockGridPeer::normalizePropertyValue($value));
        return $this;
      }
      else
      {
        return false;
      }
    }

    /**
     * Retrive property objects with $propertyName.
     *
     * @param   String    $propertyName the properties to look for.
     * @param   PropelPDO $con          Optional connection object
     *
     * @return  Array
     */
    protected function getPropertiesObjectsByName($propertyName, PropelPDO $con = null)
    {
      $ret = array();
      $properties = $this->getBlockGridExtraPropertys(null, $con);
      $propertyName = BlockGridPeer::normalizePropertyName($propertyName);
      foreach($properties as $property)
      {
        if($property->getPropertyName() == $propertyName)
        {
          $ret[$property->getId() ? $property->getId() : $propertyName.'_'.count($ret)] = $property;
        }
      }
      return $ret;
    }

    /**
     * Retrieve related property with $id.
     * If property is not saved yet, id is the list index, created this way :
     * $propertyName.'_'.$index.
     *
     * @param Integer|String  $id   the id of the property to retrieve.
     * @param PropelPDO       $con  Optional connection object
     *
     * @return BlockGridExtraProperty
     */
    protected function getPropertyObjectById($id, PropelPDO $con = null)
    {
      if(is_numeric($id))
      {
        $properties = $this->getBlockGridExtraPropertys(null, $con);
        foreach($properties as $property)
        {
          if($property->getId() == $id)
          {
            return $property;
          }
        }
      }
      else
      {
        $propertyName = substr($id, 0, strrpos($id, '_'));
        $properties = $this->getPropertiesObjectsByName($propertyName, $con);
        return $properties[$id];
      }
    }

    /**
     * Check wether property with $id is
     *
     * @param PropelPDO $con  Optional connection object
     */
    protected function isPropertyWithIdA($id, $propertyName, PropelPDO $con = null)
    {
      $property = $this->getPropertyObjectById($id, $con);
      return $property && $property->getPropertyName() == BlockGridPeer::normalizePropertyName($propertyName);
    }

    /**
     * wrapped function on update{Property} callback
     *
     * @param string          $name  the property to update's type
     * @param mixed           $value the new value
     * @param integer|string  $id    the id of the property to update
     * @param PropelPDO       $con   Optional connection object
     *
     * @return Boolean|BlockGridExtraProperty
     */
    protected function setPropertyByNameAndId($name, $value, $id, PropelPDO $con = null)
    {
      if($this->isPropertyWithIdA($id, BlockGridPeer::normalizePropertyName($name), $con))
      {
        return $this->setPropertyById($id, $value);
      }
      return false;
    }

    /**
     * get the property with id $id.
     * can only be used with an already set property
     *
     * @param PropelPDO $con Optional connection object
     */
    protected function getPropertyById($id, $defaultValue = null, PropelPDO $con = null)
    {
      $property = $this->getPropertyObjectById($id, $con);
      if($property instanceof BlockGridExtraProperty)
      {
        return $property->getPropertyValue();
      }
      else
      {
        return $defaultValue;
      }
    }

    /**
     * wrapped function on deleteProperty callback
     *
     * @param PropelPDO $con Optional connection object
     */
    protected function deletePropertyByNameAndId($name, $id, PropelPDO $con = null)
    {
      if($this->isPropertyWithIdA($id, BlockGridPeer::normalizePropertyName($name), $con))
      {
        return $this->deletePropertyById($id, $con);
      }
      return false;
    }

    /**
     * delete a multiple occurence property
     *
     * @param PropelPDO $con  Optional connection object
     */
    protected function deletePropertyById($id, PropelPDO $con = null)
    {
      $property = $this->getPropertyObjectById($id, $con);
      if($property instanceof BlockGridExtraProperty)
      {
        if(!$property->isNew())
        {
          $property->delete($con);
        }
        $this->collBlockGridExtraPropertys->remove($this->collBlockGridExtraPropertys->search($property));
        return $property;
      }
      else
      {
        return false;
      }
    }

    /**
     * delete all properties with $name
     *
     * @param PropelPDO $con Optional connection object
     */
    public function deletepropertiesByName($name, PropelPDO $con = null)
    {
      $properties = $this->getPropertiesObjectsByName($name, $con);
      foreach($properties as $property)
      {
        if($property instanceof BlockGridExtraProperty)
        {
          $property->delete($con);
          $this->collBlockGridExtraPropertys->remove($this->collBlockGridExtraPropertys->search($property));
        }
      }
      return $properties;
    }
/**
 * Initializes internal state of BlockGrid object.
 */
public function __construct()
{
  parent::__construct();

  $this->initializeProperties();
}

/**
     * initialize properties.
     * called in the constructor to add default properties.
     */
    protected function initializeProperties()
    {
    }/**
     * Returns the list of registered properties
     * that can be set only once.
     *
     * @return array
     */
    public function getRegisteredSingleProperties()
    {
      return array_keys($this->extraProperties);
    }

    /**
     * Register a new single occurence property $propertyName for the object.
     * The property will be accessible through getPropertyName method.
     *
     * @param String  $propertyName   the property name.
     * @param Mixed   $defaultValue   default property value.
     *
     * @return BlockGrid
     */
    public function registerProperty($propertyName, $defaultValue = null)
    {
      $propertyName = BlockGridPeer::normalizePropertyName($propertyName);
      /* comment this line to remove default value update ability
      if(!array_key_exists($propertyName, $this->extraProperties))
      {
        $this->extraProperties[$propertyName] = $defaultValue;
      }
      /*/
      $this->extraProperties[$propertyName] = $defaultValue;
      //*/
      return $this;
    }

    /**
     * Set a single occurence property.
     * If the property already exists, then it is overriden, ortherwise
     * new property is created.
     *
     * @param String    $name   the property name.
     * @param Mixed     $value  default property value.
     * @param PropelPDO $con    Optional connection object
     *
     * @return BlockGrid
     */
    public function setProperty($name, $value, PropelPDO $con = null)
    {
      $name = BlockGridPeer::normalizePropertyName($name);
      if($this->hasProperty($name, $con))
      {
        $properties = $this->getBlockGridExtraPropertys(null, $con);
        foreach($properties as $property)
        {
          if($property->getPropertyName() == $name)
          {
            $property->setPropertyValue(BlockGridPeer::normalizePropertyValue($value));
            return $this;
          }
        }
      }
      else
      {
        $property = new BlockGridExtraProperty();
        $property->setPropertyName($name);
        $property->setPropertyValue(BlockGridPeer::normalizePropertyValue($value));
        $this->addBlockGridExtraProperty($property);
      }
      return $this;
    }

    /**
     * Get the value of an extra property that can appear only once.
     *
     * @param   String    $propertyName   the name of property retrieve.
     * @param   Mixed     $defaultValue   default value if property isn't set.
     * @param   PropelPDO $con            Optional connection object
     *
     * @return  Mixed
     */
    public function getProperty($propertyName, $defaultValue = null, PropelPDO $con = null)
    {
      $properties = $this->getBlockGridExtraPropertys(null, $con);
      $propertyName = BlockGridPeer::normalizePropertyName($propertyName);
      foreach($properties as $property)
      {
        if($property->getPropertyName() == $propertyName)
        {
          return $property->getPropertyValue();
        }
      }
      return is_null($defaultValue)
                ? isset($this->extraProperties[$propertyName])
                          ? $this->extraProperties[$propertyName]
                          : null
                : $defaultValue;
    }/**
     * returns the list of registered multiple properties
     *
     * @return array
     */
    public function getRegisteredMultipleProperties()
    {
      return array_keys($this->multipleExtraProperties);
    }

    /**
     * Register a new multiple occurence property $propertyName for the object.
     * The properties will be accessible through getPropertyNames method.
     *
     * @param String  $propertyName   the property name.
     * @param Mixed   $defaultValue   default property value.
     * @return BlockGrid
     */
    public function registerMultipleProperty($propertyName, $defaultValue = null)
    {
      $propertyName = BlockGridPeer::normalizePropertyName($propertyName);
      /* comment this line to remove default value update ability
      if(!array_key_exists($propertyName, $this->multipleExtraProperties))
      {
        $this->multipleExtraProperties[$propertyName] = $defaultValue;
      }
      /*/
      $this->multipleExtraProperties[$propertyName] = $defaultValue;
      //*/
      return $this;
    }

    /**
     * adds a multiple instance property to event
     *
     * @param String  $propertyName   the name of the property to add.
     * @param Mixed   $value          the value for new property.
     */
    public function addProperty($propertyName, $value)
    {
      $property = new BlockGridExtraProperty();
      $property->setPropertyName(BlockGridPeer::normalizePropertyName($propertyName));
      $property->setPropertyValue(BlockGridPeer::normalizePropertyValue($value));
      $this->addBlockGridExtraProperty($property);
      return $this;
    }

    /**
     * returns an array of all matching values for given property
     * the array keys are the values ID
     * @todo enhance the case an id is given
     * @todo check the case there is an id but does not exists
     *
     * @param string    $propertyName    the name of properties to retrieve
     * @param mixed     $default         The default value to use
     * @param Integer   $id              The unique id of the property to retrieve
     * @param PropelPDO $con             Optional connection object
     *
     * @return array  the list of matching properties (prop_id => value).
     */
    public function getPropertiesByName($propertyName, $default = array(), $id = null, PropelPDO $con = null)
    {
      $ret = array();
      $properties = $this->getPropertiesObjectsByName($propertyName, $con);
      foreach($properties as $key => $property)
      {
        $ret[$key] = $property->getPropertyValue();
      }
      // is there a property id ?
      if (!is_null($id) && isset($ret[$id]))
      {
        return $ret[$id];
      }
      // no results ?
      if(!count($ret))
      {
        return $default;
      }
      return $ret;
    }
    /**
     * returns an associative array with the properties and associated values.
     *
     * @deprecated Prefer the getProperties() method
     *
     * @return array
     */
    public function getExtraProperties($con = null)
    {
      return $this->getProperties($con);
    }

    /**
     * returns an associative array with the properties and associated values.
     *
     * @return array
     */
    public function getProperties($con = null)
    {
      $ret = array();

      // init with default single and multiple properties
      $ret = array_merge($ret, $this->extraProperties);
      foreach ($this->multipleExtraProperties as $propertyName => $default) {
        $ret[$propertyName] = array();
      }

      foreach ($this->getBlockGridExtraPropertys(null, $con) as $property) {
        $pname = $property->getPropertyName();
        $pvalue = $property->getPropertyValue();

        if (array_key_exists($pname, $this->extraProperties)) {
          // single property
          $ret[$pname] = $pvalue;
        }
        elseif (array_key_exists($pname, $ret) && is_array($ret[$pname])){
          $ret[$pname][] = $pvalue;
        }
        elseif (array_key_exists($pname, $ret)){
          $ret[$pname] = array($ret[$pname], $pvalue);
        }
        else {
          $ret[$pname] = $pvalue;
        }
      }

      // set multiple properties default
      foreach ($this->multipleExtraProperties as $propertyName => $default) {
        if (!is_null($default) && !count($ret[$propertyName])) {
          $ret[$propertyName][] = $default;
        }
      }

      return $ret;
    }
    /**
     * Catches calls to virtual methods
     */
    public function __call($name, $params)
    {

        // delegate behavior

        if (is_callable(array('keeko\core\entities\BlockItem', $name))) {
            if (!$delegate = $this->getBlockItem()) {
                $delegate = new BlockItem();
                $this->setBlockItem($delegate);
            }

            return call_user_func_array(array($delegate, $name), $params);
        }
        // extra_properties behavior
        // calls the registered properties dedicated functions
        if(in_array($methodName = substr($name, 0,3), array('add', 'set', 'has', 'get')))
        {
          $propertyName = BlockGridPeer::normalizePropertyName($this->extraPropertyNameFromMethod(substr($name, 3)));
        }
        else if(in_array($methodName = substr($name, 0,5), array('count', 'clear')))
        {
          $propertyName = BlockGridPeer::normalizePropertyName($this->extraPropertyNameFromMethod(substr($name, 5)));
        }
        else if(in_array($methodName = substr($name, 0,6), array('delete', 'update')))
        {
          $propertyName = BlockGridPeer::normalizePropertyName($this->extraPropertyNameFromMethod(substr($name, 6)));
        }
        if(isset($propertyName))
        {
          if(array_key_exists($propertyName, $this->extraProperties))
          {
            switch($methodName)
            {
              case 'add':
              case 'set':
                $callable = array($this, 'setProperty');
                break;
              case 'get':
                $callable = array($this, 'getProperty');
                break;
              case 'has':
                $callable = array($this, 'hasProperty');
                break;
              case 'count':
                $callable = array($this, 'countPropertiesByName');
                break;
              case 'clear':
              case 'delete':
                $callable = array($this, 'deletePropertiesByName');
                break;
              case 'update':
                $callable = array($this, 'setPropertyByName');
                break;
            }
          }
          else if(array_key_exists($propertyName, $this->multipleExtraProperties) ||
                  ('S' == substr($propertyName, -1) && array_key_exists($propertyName = substr($propertyName, 0, -1), $this->multipleExtraProperties)))
          {
            switch($methodName)
            {
              case 'add':
              case 'set':
                $callable = array($this, 'addProperty');
                break;
              case 'get':
                $callable = array($this, 'getPropertiesByName');
                break;
              case 'has':
                $callable = array($this, 'hasProperty');
                break;
              case 'count':
                $callable = array($this, 'countPropertiesByName');
                break;
              case 'clear':
                $callable = array($this, 'deletePropertiesByName');
                break;
              case 'delete':
                $callable = array($this, 'deletePropertyByNameAndId');
                break;
              case 'update':
                $callable = array($this, 'setPropertyByNameAndId');
                break;
            }
          }
            //* no error throw to make sure other behaviors can be called.
            else
            {
              throw new RuntimeException(sprintf('Unknown property %s.<br />possible single properties: %s<br />possible multiple properties', $propertyName, join(',', array_keys($this->extraProperties)), join(',', array_keys($this->multipleExtraProperties))));
            }
            //*/
          if(isset($callable))
          {
            array_unshift($params, $propertyName);
            return call_user_func_array($callable, $params);
          }

        }


        return parent::__call($name, $params);
    }

}
