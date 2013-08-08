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
use keeko\core\entities\Block;
use keeko\core\entities\BlockQuery;
use keeko\core\entities\Design;
use keeko\core\entities\DesignQuery;
use keeko\core\entities\Layout;
use keeko\core\entities\LayoutPeer;
use keeko\core\entities\LayoutQuery;
use keeko\core\entities\Page;
use keeko\core\entities\PageQuery;

/**
 * Base class that represents a row from the 'keeko_layout' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseLayout extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\LayoutPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        LayoutPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the design_id field.
     * @var        int
     */
    protected $design_id;

    /**
     * @var        Design
     */
    protected $aDesign;

    /**
     * @var        PropelObjectCollection|Page[] Collection to store aggregation of Page objects.
     */
    protected $collPages;
    protected $collPagesPartial;

    /**
     * @var        PropelObjectCollection|Block[] Collection to store aggregation of Block objects.
     */
    protected $collBlocks;
    protected $collBlocksPartial;

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

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $blocksScheduledForDeletion = null;

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
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [design_id] column value.
     *
     * @return int
     */
    public function getDesignId()
    {
        return $this->design_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Layout The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = LayoutPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Layout The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = LayoutPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [design_id] column.
     *
     * @param int $v new value
     * @return Layout The current object (for fluent API support)
     */
    public function setDesignId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->design_id !== $v) {
            $this->design_id = $v;
            $this->modifiedColumns[] = LayoutPeer::DESIGN_ID;
        }

        if ($this->aDesign !== null && $this->aDesign->getId() !== $v) {
            $this->aDesign = null;
        }


        return $this;
    } // setDesignId()

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
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->title = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->design_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 3; // 3 = LayoutPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Layout object", $e);
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

        if ($this->aDesign !== null && $this->design_id !== $this->aDesign->getId()) {
            $this->aDesign = null;
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
            $con = Propel::getConnection(LayoutPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = LayoutPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aDesign = null;
            $this->collPages = null;

            $this->collBlocks = null;

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
            $con = Propel::getConnection(LayoutPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = LayoutQuery::create()
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
            $con = Propel::getConnection(LayoutPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                LayoutPeer::addInstanceToPool($this);
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
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aDesign !== null) {
                if ($this->aDesign->isModified() || $this->aDesign->isNew()) {
                    $affectedRows += $this->aDesign->save($con);
                }
                $this->setDesign($this->aDesign);
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

            if ($this->pagesScheduledForDeletion !== null) {
                if (!$this->pagesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pagesScheduledForDeletion as $page) {
                        // need to save related object because we set the relation to null
                        $page->save($con);
                    }
                    $this->pagesScheduledForDeletion = null;
                }
            }

            if ($this->collPages !== null) {
                foreach ($this->collPages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->blocksScheduledForDeletion !== null) {
                if (!$this->blocksScheduledForDeletion->isEmpty()) {
                    foreach ($this->blocksScheduledForDeletion as $block) {
                        // need to save related object because we set the relation to null
                        $block->save($con);
                    }
                    $this->blocksScheduledForDeletion = null;
                }
            }

            if ($this->collBlocks !== null) {
                foreach ($this->collBlocks as $referrerFK) {
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

        $this->modifiedColumns[] = LayoutPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LayoutPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LayoutPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(LayoutPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(LayoutPeer::DESIGN_ID)) {
            $modifiedColumns[':p' . $index++]  = '`design_id`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_layout` (%s) VALUES (%s)',
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
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`design_id`':
                        $stmt->bindValue($identifier, $this->design_id, PDO::PARAM_INT);
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
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aDesign !== null) {
                if (!$this->aDesign->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDesign->getValidationFailures());
                }
            }


            if (($retval = LayoutPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPages !== null) {
                    foreach ($this->collPages as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBlocks !== null) {
                    foreach ($this->collBlocks as $referrerFK) {
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
        $pos = LayoutPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTitle();
                break;
            case 2:
                return $this->getDesignId();
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
        if (isset($alreadyDumpedObjects['Layout'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Layout'][$this->getPrimaryKey()] = true;
        $keys = LayoutPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getDesignId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aDesign) {
                $result['Design'] = $this->aDesign->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPages) {
                $result['Pages'] = $this->collPages->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBlocks) {
                $result['Blocks'] = $this->collBlocks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = LayoutPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setTitle($value);
                break;
            case 2:
                $this->setDesignId($value);
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
        $keys = LayoutPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitle($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDesignId($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(LayoutPeer::DATABASE_NAME);

        if ($this->isColumnModified(LayoutPeer::ID)) $criteria->add(LayoutPeer::ID, $this->id);
        if ($this->isColumnModified(LayoutPeer::TITLE)) $criteria->add(LayoutPeer::TITLE, $this->title);
        if ($this->isColumnModified(LayoutPeer::DESIGN_ID)) $criteria->add(LayoutPeer::DESIGN_ID, $this->design_id);

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
        $criteria = new Criteria(LayoutPeer::DATABASE_NAME);
        $criteria->add(LayoutPeer::ID, $this->id);

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
     * @param object $copyObj An object of Layout (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDesignId($this->getDesignId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBlocks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBlock($relObj->copy($deepCopy));
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
     * @return Layout Clone of current object.
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
     * @return LayoutPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new LayoutPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Design object.
     *
     * @param             Design $v
     * @return Layout The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDesign(Design $v = null)
    {
        if ($v === null) {
            $this->setDesignId(NULL);
        } else {
            $this->setDesignId($v->getId());
        }

        $this->aDesign = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Design object, it will not be re-added.
        if ($v !== null) {
            $v->addLayout($this);
        }


        return $this;
    }


    /**
     * Get the associated Design object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Design The associated Design object.
     * @throws PropelException
     */
    public function getDesign(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDesign === null && ($this->design_id !== null) && $doQuery) {
            $this->aDesign = DesignQuery::create()->findPk($this->design_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDesign->addLayouts($this);
             */
        }

        return $this->aDesign;
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
        if ('Page' == $relationName) {
            $this->initPages();
        }
        if ('Block' == $relationName) {
            $this->initBlocks();
        }
    }

    /**
     * Clears out the collPages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Layout The current object (for fluent API support)
     * @see        addPages()
     */
    public function clearPages()
    {
        $this->collPages = null; // important to set this to null since that means it is uninitialized
        $this->collPagesPartial = null;

        return $this;
    }

    /**
     * reset is the collPages collection loaded partially
     *
     * @return void
     */
    public function resetPartialPages($v = true)
    {
        $this->collPagesPartial = $v;
    }

    /**
     * Initializes the collPages collection.
     *
     * By default this just sets the collPages collection to an empty array (like clearcollPages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPages($overrideExisting = true)
    {
        if (null !== $this->collPages && !$overrideExisting) {
            return;
        }
        $this->collPages = new PropelObjectCollection();
        $this->collPages->setModel('Page');
    }

    /**
     * Gets an array of Page objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Layout is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Page[] List of Page objects
     * @throws PropelException
     */
    public function getPages($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPagesPartial && !$this->isNew();
        if (null === $this->collPages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPages) {
                // return empty collection
                $this->initPages();
            } else {
                $collPages = PageQuery::create(null, $criteria)
                    ->filterByLayout($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPagesPartial && count($collPages)) {
                      $this->initPages(false);

                      foreach($collPages as $obj) {
                        if (false == $this->collPages->contains($obj)) {
                          $this->collPages->append($obj);
                        }
                      }

                      $this->collPagesPartial = true;
                    }

                    $collPages->getInternalIterator()->rewind();
                    return $collPages;
                }

                if($partial && $this->collPages) {
                    foreach($this->collPages as $obj) {
                        if($obj->isNew()) {
                            $collPages[] = $obj;
                        }
                    }
                }

                $this->collPages = $collPages;
                $this->collPagesPartial = false;
            }
        }

        return $this->collPages;
    }

    /**
     * Sets a collection of Page objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pages A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Layout The current object (for fluent API support)
     */
    public function setPages(PropelCollection $pages, PropelPDO $con = null)
    {
        $pagesToDelete = $this->getPages(new Criteria(), $con)->diff($pages);

        $this->pagesScheduledForDeletion = unserialize(serialize($pagesToDelete));

        foreach ($pagesToDelete as $pageRemoved) {
            $pageRemoved->setLayout(null);
        }

        $this->collPages = null;
        foreach ($pages as $page) {
            $this->addPage($page);
        }

        $this->collPages = $pages;
        $this->collPagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Page objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Page objects.
     * @throws PropelException
     */
    public function countPages(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPagesPartial && !$this->isNew();
        if (null === $this->collPages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPages) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPages());
            }
            $query = PageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLayout($this)
                ->count($con);
        }

        return count($this->collPages);
    }

    /**
     * Method called to associate a Page object to this object
     * through the Page foreign key attribute.
     *
     * @param    Page $l Page
     * @return Layout The current object (for fluent API support)
     */
    public function addPage(Page $l)
    {
        if ($this->collPages === null) {
            $this->initPages();
            $this->collPagesPartial = true;
        }
        if (!in_array($l, $this->collPages->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPage($l);
        }

        return $this;
    }

    /**
     * @param	Page $page The page object to add.
     */
    protected function doAddPage($page)
    {
        $this->collPages[]= $page;
        $page->setLayout($this);
    }

    /**
     * @param	Page $page The page object to remove.
     * @return Layout The current object (for fluent API support)
     */
    public function removePage($page)
    {
        if ($this->getPages()->contains($page)) {
            $this->collPages->remove($this->collPages->search($page));
            if (null === $this->pagesScheduledForDeletion) {
                $this->pagesScheduledForDeletion = clone $this->collPages;
                $this->pagesScheduledForDeletion->clear();
            }
            $this->pagesScheduledForDeletion[]= $page;
            $page->setLayout(null);
        }

        return $this;
    }

    /**
     * Clears out the collBlocks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Layout The current object (for fluent API support)
     * @see        addBlocks()
     */
    public function clearBlocks()
    {
        $this->collBlocks = null; // important to set this to null since that means it is uninitialized
        $this->collBlocksPartial = null;

        return $this;
    }

    /**
     * reset is the collBlocks collection loaded partially
     *
     * @return void
     */
    public function resetPartialBlocks($v = true)
    {
        $this->collBlocksPartial = $v;
    }

    /**
     * Initializes the collBlocks collection.
     *
     * By default this just sets the collBlocks collection to an empty array (like clearcollBlocks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBlocks($overrideExisting = true)
    {
        if (null !== $this->collBlocks && !$overrideExisting) {
            return;
        }
        $this->collBlocks = new PropelObjectCollection();
        $this->collBlocks->setModel('Block');
    }

    /**
     * Gets an array of Block objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Layout is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Block[] List of Block objects
     * @throws PropelException
     */
    public function getBlocks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBlocksPartial && !$this->isNew();
        if (null === $this->collBlocks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBlocks) {
                // return empty collection
                $this->initBlocks();
            } else {
                $collBlocks = BlockQuery::create(null, $criteria)
                    ->filterByLayout($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBlocksPartial && count($collBlocks)) {
                      $this->initBlocks(false);

                      foreach($collBlocks as $obj) {
                        if (false == $this->collBlocks->contains($obj)) {
                          $this->collBlocks->append($obj);
                        }
                      }

                      $this->collBlocksPartial = true;
                    }

                    $collBlocks->getInternalIterator()->rewind();
                    return $collBlocks;
                }

                if($partial && $this->collBlocks) {
                    foreach($this->collBlocks as $obj) {
                        if($obj->isNew()) {
                            $collBlocks[] = $obj;
                        }
                    }
                }

                $this->collBlocks = $collBlocks;
                $this->collBlocksPartial = false;
            }
        }

        return $this->collBlocks;
    }

    /**
     * Sets a collection of Block objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $blocks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Layout The current object (for fluent API support)
     */
    public function setBlocks(PropelCollection $blocks, PropelPDO $con = null)
    {
        $blocksToDelete = $this->getBlocks(new Criteria(), $con)->diff($blocks);

        $this->blocksScheduledForDeletion = unserialize(serialize($blocksToDelete));

        foreach ($blocksToDelete as $blockRemoved) {
            $blockRemoved->setLayout(null);
        }

        $this->collBlocks = null;
        foreach ($blocks as $block) {
            $this->addBlock($block);
        }

        $this->collBlocks = $blocks;
        $this->collBlocksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Block objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Block objects.
     * @throws PropelException
     */
    public function countBlocks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBlocksPartial && !$this->isNew();
        if (null === $this->collBlocks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBlocks) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getBlocks());
            }
            $query = BlockQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLayout($this)
                ->count($con);
        }

        return count($this->collBlocks);
    }

    /**
     * Method called to associate a Block object to this object
     * through the Block foreign key attribute.
     *
     * @param    Block $l Block
     * @return Layout The current object (for fluent API support)
     */
    public function addBlock(Block $l)
    {
        if ($this->collBlocks === null) {
            $this->initBlocks();
            $this->collBlocksPartial = true;
        }
        if (!in_array($l, $this->collBlocks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBlock($l);
        }

        return $this;
    }

    /**
     * @param	Block $block The block object to add.
     */
    protected function doAddBlock($block)
    {
        $this->collBlocks[]= $block;
        $block->setLayout($this);
    }

    /**
     * @param	Block $block The block object to remove.
     * @return Layout The current object (for fluent API support)
     */
    public function removeBlock($block)
    {
        if ($this->getBlocks()->contains($block)) {
            $this->collBlocks->remove($this->collBlocks->search($block));
            if (null === $this->blocksScheduledForDeletion) {
                $this->blocksScheduledForDeletion = clone $this->collBlocks;
                $this->blocksScheduledForDeletion->clear();
            }
            $this->blocksScheduledForDeletion[]= $block;
            $block->setLayout(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title = null;
        $this->design_id = null;
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
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collPages) {
                foreach ($this->collPages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBlocks) {
                foreach ($this->collBlocks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aDesign instanceof Persistent) {
              $this->aDesign->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPages instanceof PropelCollection) {
            $this->collPages->clearIterator();
        }
        $this->collPages = null;
        if ($this->collBlocks instanceof PropelCollection) {
            $this->collBlocks->clearIterator();
        }
        $this->collBlocks = null;
        $this->aDesign = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LayoutPeer::DEFAULT_STRING_FORMAT);
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

}
