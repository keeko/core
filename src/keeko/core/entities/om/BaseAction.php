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
use keeko\core\entities\Action;
use keeko\core\entities\ActionPeer;
use keeko\core\entities\ActionQuery;
use keeko\core\entities\BlockContent;
use keeko\core\entities\BlockContentQuery;
use keeko\core\entities\GroupAction;
use keeko\core\entities\GroupActionQuery;
use keeko\core\entities\Module;
use keeko\core\entities\ModuleQuery;

/**
 * Base class that represents a row from the 'keeko_action' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseAction extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\ActionPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ActionPeer
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
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the api field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $api;

    /**
     * The value for the module_id field.
     * @var        int
     */
    protected $module_id;

    /**
     * @var        Module
     */
    protected $aModule;

    /**
     * @var        PropelObjectCollection|BlockContent[] Collection to store aggregation of BlockContent objects.
     */
    protected $collBlockContents;
    protected $collBlockContentsPartial;

    /**
     * @var        PropelObjectCollection|GroupAction[] Collection to store aggregation of GroupAction objects.
     */
    protected $collGroupActions;
    protected $collGroupActionsPartial;

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
    protected $blockContentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $groupActionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->api = false;
    }

    /**
     * Initializes internal state of BaseAction object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
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
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /**
     * Get the [api] column value.
     *
     * @return boolean
     */
    public function getApi()
    {

        return $this->api;
    }

    /**
     * Get the [module_id] column value.
     *
     * @return int
     */
    public function getModuleId()
    {

        return $this->module_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Action The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ActionPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Action The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = ActionPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return Action The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = ActionPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Action The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = ActionPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Sets the value of the [api] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Action The current object (for fluent API support)
     */
    public function setApi($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->api !== $v) {
            $this->api = $v;
            $this->modifiedColumns[] = ActionPeer::API;
        }


        return $this;
    } // setApi()

    /**
     * Set the value of [module_id] column.
     *
     * @param  int $v new value
     * @return Action The current object (for fluent API support)
     */
    public function setModuleId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->module_id !== $v) {
            $this->module_id = $v;
            $this->modifiedColumns[] = ActionPeer::MODULE_ID;
        }

        if ($this->aModule !== null && $this->aModule->getId() !== $v) {
            $this->aModule = null;
        }


        return $this;
    } // setModuleId()

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
            if ($this->api !== false) {
                return false;
            }

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
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->api = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->module_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 6; // 6 = ActionPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Action object", $e);
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

        if ($this->aModule !== null && $this->module_id !== $this->aModule->getId()) {
            $this->aModule = null;
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
            $con = Propel::getConnection(ActionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ActionPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aModule = null;
            $this->collBlockContents = null;

            $this->collGroupActions = null;

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
            $con = Propel::getConnection(ActionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ActionQuery::create()
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
            $con = Propel::getConnection(ActionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                ActionPeer::addInstanceToPool($this);
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

            if ($this->aModule !== null) {
                if ($this->aModule->isModified() || $this->aModule->isNew()) {
                    $affectedRows += $this->aModule->save($con);
                }
                $this->setModule($this->aModule);
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

            if ($this->blockContentsScheduledForDeletion !== null) {
                if (!$this->blockContentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->blockContentsScheduledForDeletion as $blockContent) {
                        // need to save related object because we set the relation to null
                        $blockContent->save($con);
                    }
                    $this->blockContentsScheduledForDeletion = null;
                }
            }

            if ($this->collBlockContents !== null) {
                foreach ($this->collBlockContents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->groupActionsScheduledForDeletion !== null) {
                if (!$this->groupActionsScheduledForDeletion->isEmpty()) {
                    GroupActionQuery::create()
                        ->filterByPrimaryKeys($this->groupActionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->groupActionsScheduledForDeletion = null;
                }
            }

            if ($this->collGroupActions !== null) {
                foreach ($this->collGroupActions as $referrerFK) {
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

        $this->modifiedColumns[] = ActionPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ActionPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ActionPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ActionPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(ActionPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(ActionPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(ActionPeer::API)) {
            $modifiedColumns[':p' . $index++]  = '`api`';
        }
        if ($this->isColumnModified(ActionPeer::MODULE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`module_id`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_action` (%s) VALUES (%s)',
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
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`api`':
                        $stmt->bindValue($identifier, (int) $this->api, PDO::PARAM_INT);
                        break;
                    case '`module_id`':
                        $stmt->bindValue($identifier, $this->module_id, PDO::PARAM_INT);
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

            if ($this->aModule !== null) {
                if (!$this->aModule->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aModule->getValidationFailures());
                }
            }


            if (($retval = ActionPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBlockContents !== null) {
                    foreach ($this->collBlockContents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collGroupActions !== null) {
                    foreach ($this->collGroupActions as $referrerFK) {
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
        $pos = ActionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getDescription();
                break;
            case 4:
                return $this->getApi();
                break;
            case 5:
                return $this->getModuleId();
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
        if (isset($alreadyDumpedObjects['Action'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Action'][$this->getPrimaryKey()] = true;
        $keys = ActionPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getApi(),
            $keys[5] => $this->getModuleId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aModule) {
                $result['Module'] = $this->aModule->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collBlockContents) {
                $result['BlockContents'] = $this->collBlockContents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGroupActions) {
                $result['GroupActions'] = $this->collGroupActions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ActionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setDescription($value);
                break;
            case 4:
                $this->setApi($value);
                break;
            case 5:
                $this->setModuleId($value);
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
        $keys = ActionPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setApi($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setModuleId($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ActionPeer::DATABASE_NAME);

        if ($this->isColumnModified(ActionPeer::ID)) $criteria->add(ActionPeer::ID, $this->id);
        if ($this->isColumnModified(ActionPeer::NAME)) $criteria->add(ActionPeer::NAME, $this->name);
        if ($this->isColumnModified(ActionPeer::TITLE)) $criteria->add(ActionPeer::TITLE, $this->title);
        if ($this->isColumnModified(ActionPeer::DESCRIPTION)) $criteria->add(ActionPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(ActionPeer::API)) $criteria->add(ActionPeer::API, $this->api);
        if ($this->isColumnModified(ActionPeer::MODULE_ID)) $criteria->add(ActionPeer::MODULE_ID, $this->module_id);

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
        $criteria = new Criteria(ActionPeer::DATABASE_NAME);
        $criteria->add(ActionPeer::ID, $this->id);

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
     * @param object $copyObj An object of Action (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setApi($this->getApi());
        $copyObj->setModuleId($this->getModuleId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getBlockContents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBlockContent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGroupActions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroupAction($relObj->copy($deepCopy));
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
     * @return Action Clone of current object.
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
     * @return ActionPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ActionPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Module object.
     *
     * @param                  Module $v
     * @return Action The current object (for fluent API support)
     * @throws PropelException
     */
    public function setModule(Module $v = null)
    {
        if ($v === null) {
            $this->setModuleId(NULL);
        } else {
            $this->setModuleId($v->getId());
        }

        $this->aModule = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Module object, it will not be re-added.
        if ($v !== null) {
            $v->addAction($this);
        }


        return $this;
    }


    /**
     * Get the associated Module object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Module The associated Module object.
     * @throws PropelException
     */
    public function getModule(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aModule === null && ($this->module_id !== null) && $doQuery) {
            $this->aModule = ModuleQuery::create()->findPk($this->module_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aModule->addActions($this);
             */
        }

        return $this->aModule;
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
        if ('BlockContent' == $relationName) {
            $this->initBlockContents();
        }
        if ('GroupAction' == $relationName) {
            $this->initGroupActions();
        }
    }

    /**
     * Clears out the collBlockContents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Action The current object (for fluent API support)
     * @see        addBlockContents()
     */
    public function clearBlockContents()
    {
        $this->collBlockContents = null; // important to set this to null since that means it is uninitialized
        $this->collBlockContentsPartial = null;

        return $this;
    }

    /**
     * reset is the collBlockContents collection loaded partially
     *
     * @return void
     */
    public function resetPartialBlockContents($v = true)
    {
        $this->collBlockContentsPartial = $v;
    }

    /**
     * Initializes the collBlockContents collection.
     *
     * By default this just sets the collBlockContents collection to an empty array (like clearcollBlockContents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBlockContents($overrideExisting = true)
    {
        if (null !== $this->collBlockContents && !$overrideExisting) {
            return;
        }
        $this->collBlockContents = new PropelObjectCollection();
        $this->collBlockContents->setModel('BlockContent');
    }

    /**
     * Gets an array of BlockContent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Action is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BlockContent[] List of BlockContent objects
     * @throws PropelException
     */
    public function getBlockContents($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBlockContentsPartial && !$this->isNew();
        if (null === $this->collBlockContents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBlockContents) {
                // return empty collection
                $this->initBlockContents();
            } else {
                $collBlockContents = BlockContentQuery::create(null, $criteria)
                    ->filterByAction($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBlockContentsPartial && count($collBlockContents)) {
                      $this->initBlockContents(false);

                      foreach ($collBlockContents as $obj) {
                        if (false == $this->collBlockContents->contains($obj)) {
                          $this->collBlockContents->append($obj);
                        }
                      }

                      $this->collBlockContentsPartial = true;
                    }

                    $collBlockContents->getInternalIterator()->rewind();

                    return $collBlockContents;
                }

                if ($partial && $this->collBlockContents) {
                    foreach ($this->collBlockContents as $obj) {
                        if ($obj->isNew()) {
                            $collBlockContents[] = $obj;
                        }
                    }
                }

                $this->collBlockContents = $collBlockContents;
                $this->collBlockContentsPartial = false;
            }
        }

        return $this->collBlockContents;
    }

    /**
     * Sets a collection of BlockContent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $blockContents A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Action The current object (for fluent API support)
     */
    public function setBlockContents(PropelCollection $blockContents, PropelPDO $con = null)
    {
        $blockContentsToDelete = $this->getBlockContents(new Criteria(), $con)->diff($blockContents);


        $this->blockContentsScheduledForDeletion = $blockContentsToDelete;

        foreach ($blockContentsToDelete as $blockContentRemoved) {
            $blockContentRemoved->setAction(null);
        }

        $this->collBlockContents = null;
        foreach ($blockContents as $blockContent) {
            $this->addBlockContent($blockContent);
        }

        $this->collBlockContents = $blockContents;
        $this->collBlockContentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BlockContent objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BlockContent objects.
     * @throws PropelException
     */
    public function countBlockContents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBlockContentsPartial && !$this->isNew();
        if (null === $this->collBlockContents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBlockContents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBlockContents());
            }
            $query = BlockContentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAction($this)
                ->count($con);
        }

        return count($this->collBlockContents);
    }

    /**
     * Method called to associate a BlockContent object to this object
     * through the BlockContent foreign key attribute.
     *
     * @param    BlockContent $l BlockContent
     * @return Action The current object (for fluent API support)
     */
    public function addBlockContent(BlockContent $l)
    {
        if ($this->collBlockContents === null) {
            $this->initBlockContents();
            $this->collBlockContentsPartial = true;
        }

        if (!in_array($l, $this->collBlockContents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBlockContent($l);

            if ($this->blockContentsScheduledForDeletion and $this->blockContentsScheduledForDeletion->contains($l)) {
                $this->blockContentsScheduledForDeletion->remove($this->blockContentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BlockContent $blockContent The blockContent object to add.
     */
    protected function doAddBlockContent($blockContent)
    {
        $this->collBlockContents[]= $blockContent;
        $blockContent->setAction($this);
    }

    /**
     * @param	BlockContent $blockContent The blockContent object to remove.
     * @return Action The current object (for fluent API support)
     */
    public function removeBlockContent($blockContent)
    {
        if ($this->getBlockContents()->contains($blockContent)) {
            $this->collBlockContents->remove($this->collBlockContents->search($blockContent));
            if (null === $this->blockContentsScheduledForDeletion) {
                $this->blockContentsScheduledForDeletion = clone $this->collBlockContents;
                $this->blockContentsScheduledForDeletion->clear();
            }
            $this->blockContentsScheduledForDeletion[]= $blockContent;
            $blockContent->setAction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Action is new, it will return
     * an empty collection; or if this Action has previously
     * been saved, it will retrieve related BlockContents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Action.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BlockContent[] List of BlockContent objects
     */
    public function getBlockContentsJoinBlockItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BlockContentQuery::create(null, $criteria);
        $query->joinWith('BlockItem', $join_behavior);

        return $this->getBlockContents($query, $con);
    }

    /**
     * Clears out the collGroupActions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Action The current object (for fluent API support)
     * @see        addGroupActions()
     */
    public function clearGroupActions()
    {
        $this->collGroupActions = null; // important to set this to null since that means it is uninitialized
        $this->collGroupActionsPartial = null;

        return $this;
    }

    /**
     * reset is the collGroupActions collection loaded partially
     *
     * @return void
     */
    public function resetPartialGroupActions($v = true)
    {
        $this->collGroupActionsPartial = $v;
    }

    /**
     * Initializes the collGroupActions collection.
     *
     * By default this just sets the collGroupActions collection to an empty array (like clearcollGroupActions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroupActions($overrideExisting = true)
    {
        if (null !== $this->collGroupActions && !$overrideExisting) {
            return;
        }
        $this->collGroupActions = new PropelObjectCollection();
        $this->collGroupActions->setModel('GroupAction');
    }

    /**
     * Gets an array of GroupAction objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Action is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|GroupAction[] List of GroupAction objects
     * @throws PropelException
     */
    public function getGroupActions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collGroupActionsPartial && !$this->isNew();
        if (null === $this->collGroupActions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGroupActions) {
                // return empty collection
                $this->initGroupActions();
            } else {
                $collGroupActions = GroupActionQuery::create(null, $criteria)
                    ->filterByAction($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collGroupActionsPartial && count($collGroupActions)) {
                      $this->initGroupActions(false);

                      foreach ($collGroupActions as $obj) {
                        if (false == $this->collGroupActions->contains($obj)) {
                          $this->collGroupActions->append($obj);
                        }
                      }

                      $this->collGroupActionsPartial = true;
                    }

                    $collGroupActions->getInternalIterator()->rewind();

                    return $collGroupActions;
                }

                if ($partial && $this->collGroupActions) {
                    foreach ($this->collGroupActions as $obj) {
                        if ($obj->isNew()) {
                            $collGroupActions[] = $obj;
                        }
                    }
                }

                $this->collGroupActions = $collGroupActions;
                $this->collGroupActionsPartial = false;
            }
        }

        return $this->collGroupActions;
    }

    /**
     * Sets a collection of GroupAction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $groupActions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Action The current object (for fluent API support)
     */
    public function setGroupActions(PropelCollection $groupActions, PropelPDO $con = null)
    {
        $groupActionsToDelete = $this->getGroupActions(new Criteria(), $con)->diff($groupActions);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->groupActionsScheduledForDeletion = clone $groupActionsToDelete;

        foreach ($groupActionsToDelete as $groupActionRemoved) {
            $groupActionRemoved->setAction(null);
        }

        $this->collGroupActions = null;
        foreach ($groupActions as $groupAction) {
            $this->addGroupAction($groupAction);
        }

        $this->collGroupActions = $groupActions;
        $this->collGroupActionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GroupAction objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related GroupAction objects.
     * @throws PropelException
     */
    public function countGroupActions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collGroupActionsPartial && !$this->isNew();
        if (null === $this->collGroupActions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroupActions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGroupActions());
            }
            $query = GroupActionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAction($this)
                ->count($con);
        }

        return count($this->collGroupActions);
    }

    /**
     * Method called to associate a GroupAction object to this object
     * through the GroupAction foreign key attribute.
     *
     * @param    GroupAction $l GroupAction
     * @return Action The current object (for fluent API support)
     */
    public function addGroupAction(GroupAction $l)
    {
        if ($this->collGroupActions === null) {
            $this->initGroupActions();
            $this->collGroupActionsPartial = true;
        }

        if (!in_array($l, $this->collGroupActions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGroupAction($l);

            if ($this->groupActionsScheduledForDeletion and $this->groupActionsScheduledForDeletion->contains($l)) {
                $this->groupActionsScheduledForDeletion->remove($this->groupActionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	GroupAction $groupAction The groupAction object to add.
     */
    protected function doAddGroupAction($groupAction)
    {
        $this->collGroupActions[]= $groupAction;
        $groupAction->setAction($this);
    }

    /**
     * @param	GroupAction $groupAction The groupAction object to remove.
     * @return Action The current object (for fluent API support)
     */
    public function removeGroupAction($groupAction)
    {
        if ($this->getGroupActions()->contains($groupAction)) {
            $this->collGroupActions->remove($this->collGroupActions->search($groupAction));
            if (null === $this->groupActionsScheduledForDeletion) {
                $this->groupActionsScheduledForDeletion = clone $this->collGroupActions;
                $this->groupActionsScheduledForDeletion->clear();
            }
            $this->groupActionsScheduledForDeletion[]= clone $groupAction;
            $groupAction->setAction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Action is new, it will return
     * an empty collection; or if this Action has previously
     * been saved, it will retrieve related GroupActions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Action.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GroupAction[] List of GroupAction objects
     */
    public function getGroupActionsJoinGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GroupActionQuery::create(null, $criteria);
        $query->joinWith('Group', $join_behavior);

        return $this->getGroupActions($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->title = null;
        $this->description = null;
        $this->api = null;
        $this->module_id = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collBlockContents) {
                foreach ($this->collBlockContents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroupActions) {
                foreach ($this->collGroupActions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aModule instanceof Persistent) {
              $this->aModule->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collBlockContents instanceof PropelCollection) {
            $this->collBlockContents->clearIterator();
        }
        $this->collBlockContents = null;
        if ($this->collGroupActions instanceof PropelCollection) {
            $this->collGroupActions->clearIterator();
        }
        $this->collGroupActions = null;
        $this->aModule = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ActionPeer::DEFAULT_STRING_FORMAT);
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
