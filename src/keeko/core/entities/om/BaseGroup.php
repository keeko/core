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
use keeko\core\entities\Group;
use keeko\core\entities\GroupAction;
use keeko\core\entities\GroupActionQuery;
use keeko\core\entities\GroupPeer;
use keeko\core\entities\GroupQuery;
use keeko\core\entities\GroupUser;
use keeko\core\entities\GroupUserQuery;
use keeko\core\entities\User;
use keeko\core\entities\UserQuery;

/**
 * Base class that represents a row from the 'keeko_group' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseGroup extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\GroupPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        GroupPeer
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
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the is_guest field.
     * @var        boolean
     */
    protected $is_guest;

    /**
     * The value for the is_default field.
     * @var        boolean
     */
    protected $is_default;

    /**
     * The value for the is_active field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $is_active;

    /**
     * The value for the is_system field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_system;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        PropelObjectCollection|GroupUser[] Collection to store aggregation of GroupUser objects.
     */
    protected $collGroupUsers;
    protected $collGroupUsersPartial;

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
    protected $groupUsersScheduledForDeletion = null;

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
        $this->is_active = true;
        $this->is_system = false;
    }

    /**
     * Initializes internal state of BaseGroup object.
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
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
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
     * Get the [is_guest] column value.
     *
     * @return boolean
     */
    public function getIsGuest()
    {
        return $this->is_guest;
    }

    /**
     * Get the [is_default] column value.
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * Get the [is_active] column value.
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Get the [is_system] column value.
     *
     * @return boolean
     */
    public function getIsSystem()
    {
        return $this->is_system;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Group The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = GroupPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return Group The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = GroupPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Group The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = GroupPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Sets the value of the [is_guest] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Group The current object (for fluent API support)
     */
    public function setIsGuest($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_guest !== $v) {
            $this->is_guest = $v;
            $this->modifiedColumns[] = GroupPeer::IS_GUEST;
        }


        return $this;
    } // setIsGuest()

    /**
     * Sets the value of the [is_default] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Group The current object (for fluent API support)
     */
    public function setIsDefault($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_default !== $v) {
            $this->is_default = $v;
            $this->modifiedColumns[] = GroupPeer::IS_DEFAULT;
        }


        return $this;
    } // setIsDefault()

    /**
     * Sets the value of the [is_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Group The current object (for fluent API support)
     */
    public function setIsActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_active !== $v) {
            $this->is_active = $v;
            $this->modifiedColumns[] = GroupPeer::IS_ACTIVE;
        }


        return $this;
    } // setIsActive()

    /**
     * Sets the value of the [is_system] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Group The current object (for fluent API support)
     */
    public function setIsSystem($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_system !== $v) {
            $this->is_system = $v;
            $this->modifiedColumns[] = GroupPeer::IS_SYSTEM;
        }


        return $this;
    } // setIsSystem()

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
            if ($this->is_active !== true) {
                return false;
            }

            if ($this->is_system !== false) {
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
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->is_guest = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->is_default = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->is_active = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->is_system = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 7; // 7 = GroupPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Group object", $e);
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

        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
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
            $con = Propel::getConnection(GroupPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = GroupPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collGroupUsers = null;

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
            $con = Propel::getConnection(GroupPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = GroupQuery::create()
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
            $con = Propel::getConnection(GroupPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                GroupPeer::addInstanceToPool($this);
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

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
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

            if ($this->groupUsersScheduledForDeletion !== null) {
                if (!$this->groupUsersScheduledForDeletion->isEmpty()) {
                    GroupUserQuery::create()
                        ->filterByPrimaryKeys($this->groupUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->groupUsersScheduledForDeletion = null;
                }
            }

            if ($this->collGroupUsers !== null) {
                foreach ($this->collGroupUsers as $referrerFK) {
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

        $this->modifiedColumns[] = GroupPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GroupPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GroupPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(GroupPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(GroupPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(GroupPeer::IS_GUEST)) {
            $modifiedColumns[':p' . $index++]  = '`is_guest`';
        }
        if ($this->isColumnModified(GroupPeer::IS_DEFAULT)) {
            $modifiedColumns[':p' . $index++]  = '`is_default`';
        }
        if ($this->isColumnModified(GroupPeer::IS_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`is_active`';
        }
        if ($this->isColumnModified(GroupPeer::IS_SYSTEM)) {
            $modifiedColumns[':p' . $index++]  = '`is_system`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_group` (%s) VALUES (%s)',
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
                    case '`user_id`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`is_guest`':
                        $stmt->bindValue($identifier, (int) $this->is_guest, PDO::PARAM_INT);
                        break;
                    case '`is_default`':
                        $stmt->bindValue($identifier, (int) $this->is_default, PDO::PARAM_INT);
                        break;
                    case '`is_active`':
                        $stmt->bindValue($identifier, (int) $this->is_active, PDO::PARAM_INT);
                        break;
                    case '`is_system`':
                        $stmt->bindValue($identifier, (int) $this->is_system, PDO::PARAM_INT);
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

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }


            if (($retval = GroupPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collGroupUsers !== null) {
                    foreach ($this->collGroupUsers as $referrerFK) {
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
        $pos = GroupPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUserId();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getIsGuest();
                break;
            case 4:
                return $this->getIsDefault();
                break;
            case 5:
                return $this->getIsActive();
                break;
            case 6:
                return $this->getIsSystem();
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
        if (isset($alreadyDumpedObjects['Group'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Group'][$this->getPrimaryKey()] = true;
        $keys = GroupPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getIsGuest(),
            $keys[4] => $this->getIsDefault(),
            $keys[5] => $this->getIsActive(),
            $keys[6] => $this->getIsSystem(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collGroupUsers) {
                $result['GroupUsers'] = $this->collGroupUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = GroupPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUserId($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setIsGuest($value);
                break;
            case 4:
                $this->setIsDefault($value);
                break;
            case 5:
                $this->setIsActive($value);
                break;
            case 6:
                $this->setIsSystem($value);
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
        $keys = GroupPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setIsGuest($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setIsDefault($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setIsActive($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setIsSystem($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(GroupPeer::DATABASE_NAME);

        if ($this->isColumnModified(GroupPeer::ID)) $criteria->add(GroupPeer::ID, $this->id);
        if ($this->isColumnModified(GroupPeer::USER_ID)) $criteria->add(GroupPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(GroupPeer::NAME)) $criteria->add(GroupPeer::NAME, $this->name);
        if ($this->isColumnModified(GroupPeer::IS_GUEST)) $criteria->add(GroupPeer::IS_GUEST, $this->is_guest);
        if ($this->isColumnModified(GroupPeer::IS_DEFAULT)) $criteria->add(GroupPeer::IS_DEFAULT, $this->is_default);
        if ($this->isColumnModified(GroupPeer::IS_ACTIVE)) $criteria->add(GroupPeer::IS_ACTIVE, $this->is_active);
        if ($this->isColumnModified(GroupPeer::IS_SYSTEM)) $criteria->add(GroupPeer::IS_SYSTEM, $this->is_system);

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
        $criteria = new Criteria(GroupPeer::DATABASE_NAME);
        $criteria->add(GroupPeer::ID, $this->id);

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
     * @param object $copyObj An object of Group (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setName($this->getName());
        $copyObj->setIsGuest($this->getIsGuest());
        $copyObj->setIsDefault($this->getIsDefault());
        $copyObj->setIsActive($this->getIsActive());
        $copyObj->setIsSystem($this->getIsSystem());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getGroupUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroupUser($relObj->copy($deepCopy));
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
     * @return Group Clone of current object.
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
     * @return GroupPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new GroupPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return Group The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addGroup($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUser === null && ($this->user_id !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addGroups($this);
             */
        }

        return $this->aUser;
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
        if ('GroupUser' == $relationName) {
            $this->initGroupUsers();
        }
        if ('GroupAction' == $relationName) {
            $this->initGroupActions();
        }
    }

    /**
     * Clears out the collGroupUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Group The current object (for fluent API support)
     * @see        addGroupUsers()
     */
    public function clearGroupUsers()
    {
        $this->collGroupUsers = null; // important to set this to null since that means it is uninitialized
        $this->collGroupUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collGroupUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialGroupUsers($v = true)
    {
        $this->collGroupUsersPartial = $v;
    }

    /**
     * Initializes the collGroupUsers collection.
     *
     * By default this just sets the collGroupUsers collection to an empty array (like clearcollGroupUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroupUsers($overrideExisting = true)
    {
        if (null !== $this->collGroupUsers && !$overrideExisting) {
            return;
        }
        $this->collGroupUsers = new PropelObjectCollection();
        $this->collGroupUsers->setModel('GroupUser');
    }

    /**
     * Gets an array of GroupUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Group is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|GroupUser[] List of GroupUser objects
     * @throws PropelException
     */
    public function getGroupUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collGroupUsersPartial && !$this->isNew();
        if (null === $this->collGroupUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGroupUsers) {
                // return empty collection
                $this->initGroupUsers();
            } else {
                $collGroupUsers = GroupUserQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collGroupUsersPartial && count($collGroupUsers)) {
                      $this->initGroupUsers(false);

                      foreach($collGroupUsers as $obj) {
                        if (false == $this->collGroupUsers->contains($obj)) {
                          $this->collGroupUsers->append($obj);
                        }
                      }

                      $this->collGroupUsersPartial = true;
                    }

                    $collGroupUsers->getInternalIterator()->rewind();
                    return $collGroupUsers;
                }

                if($partial && $this->collGroupUsers) {
                    foreach($this->collGroupUsers as $obj) {
                        if($obj->isNew()) {
                            $collGroupUsers[] = $obj;
                        }
                    }
                }

                $this->collGroupUsers = $collGroupUsers;
                $this->collGroupUsersPartial = false;
            }
        }

        return $this->collGroupUsers;
    }

    /**
     * Sets a collection of GroupUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $groupUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Group The current object (for fluent API support)
     */
    public function setGroupUsers(PropelCollection $groupUsers, PropelPDO $con = null)
    {
        $groupUsersToDelete = $this->getGroupUsers(new Criteria(), $con)->diff($groupUsers);

        $this->groupUsersScheduledForDeletion = unserialize(serialize($groupUsersToDelete));

        foreach ($groupUsersToDelete as $groupUserRemoved) {
            $groupUserRemoved->setGroup(null);
        }

        $this->collGroupUsers = null;
        foreach ($groupUsers as $groupUser) {
            $this->addGroupUser($groupUser);
        }

        $this->collGroupUsers = $groupUsers;
        $this->collGroupUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GroupUser objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related GroupUser objects.
     * @throws PropelException
     */
    public function countGroupUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collGroupUsersPartial && !$this->isNew();
        if (null === $this->collGroupUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroupUsers) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getGroupUsers());
            }
            $query = GroupUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collGroupUsers);
    }

    /**
     * Method called to associate a GroupUser object to this object
     * through the GroupUser foreign key attribute.
     *
     * @param    GroupUser $l GroupUser
     * @return Group The current object (for fluent API support)
     */
    public function addGroupUser(GroupUser $l)
    {
        if ($this->collGroupUsers === null) {
            $this->initGroupUsers();
            $this->collGroupUsersPartial = true;
        }
        if (!in_array($l, $this->collGroupUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGroupUser($l);
        }

        return $this;
    }

    /**
     * @param	GroupUser $groupUser The groupUser object to add.
     */
    protected function doAddGroupUser($groupUser)
    {
        $this->collGroupUsers[]= $groupUser;
        $groupUser->setGroup($this);
    }

    /**
     * @param	GroupUser $groupUser The groupUser object to remove.
     * @return Group The current object (for fluent API support)
     */
    public function removeGroupUser($groupUser)
    {
        if ($this->getGroupUsers()->contains($groupUser)) {
            $this->collGroupUsers->remove($this->collGroupUsers->search($groupUser));
            if (null === $this->groupUsersScheduledForDeletion) {
                $this->groupUsersScheduledForDeletion = clone $this->collGroupUsers;
                $this->groupUsersScheduledForDeletion->clear();
            }
            $this->groupUsersScheduledForDeletion[]= clone $groupUser;
            $groupUser->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related GroupUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GroupUser[] List of GroupUser objects
     */
    public function getGroupUsersJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GroupUserQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getGroupUsers($query, $con);
    }

    /**
     * Clears out the collGroupActions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Group The current object (for fluent API support)
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
     * If this Group is new, it will return
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
                    ->filterByGroup($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collGroupActionsPartial && count($collGroupActions)) {
                      $this->initGroupActions(false);

                      foreach($collGroupActions as $obj) {
                        if (false == $this->collGroupActions->contains($obj)) {
                          $this->collGroupActions->append($obj);
                        }
                      }

                      $this->collGroupActionsPartial = true;
                    }

                    $collGroupActions->getInternalIterator()->rewind();
                    return $collGroupActions;
                }

                if($partial && $this->collGroupActions) {
                    foreach($this->collGroupActions as $obj) {
                        if($obj->isNew()) {
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
     * @return Group The current object (for fluent API support)
     */
    public function setGroupActions(PropelCollection $groupActions, PropelPDO $con = null)
    {
        $groupActionsToDelete = $this->getGroupActions(new Criteria(), $con)->diff($groupActions);

        $this->groupActionsScheduledForDeletion = unserialize(serialize($groupActionsToDelete));

        foreach ($groupActionsToDelete as $groupActionRemoved) {
            $groupActionRemoved->setGroup(null);
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

            if($partial && !$criteria) {
                return count($this->getGroupActions());
            }
            $query = GroupActionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collGroupActions);
    }

    /**
     * Method called to associate a GroupAction object to this object
     * through the GroupAction foreign key attribute.
     *
     * @param    GroupAction $l GroupAction
     * @return Group The current object (for fluent API support)
     */
    public function addGroupAction(GroupAction $l)
    {
        if ($this->collGroupActions === null) {
            $this->initGroupActions();
            $this->collGroupActionsPartial = true;
        }
        if (!in_array($l, $this->collGroupActions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGroupAction($l);
        }

        return $this;
    }

    /**
     * @param	GroupAction $groupAction The groupAction object to add.
     */
    protected function doAddGroupAction($groupAction)
    {
        $this->collGroupActions[]= $groupAction;
        $groupAction->setGroup($this);
    }

    /**
     * @param	GroupAction $groupAction The groupAction object to remove.
     * @return Group The current object (for fluent API support)
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
            $groupAction->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related GroupActions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GroupAction[] List of GroupAction objects
     */
    public function getGroupActionsJoinAction($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GroupActionQuery::create(null, $criteria);
        $query->joinWith('Action', $join_behavior);

        return $this->getGroupActions($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->user_id = null;
        $this->name = null;
        $this->is_guest = null;
        $this->is_default = null;
        $this->is_active = null;
        $this->is_system = null;
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
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collGroupUsers) {
                foreach ($this->collGroupUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroupActions) {
                foreach ($this->collGroupActions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collGroupUsers instanceof PropelCollection) {
            $this->collGroupUsers->clearIterator();
        }
        $this->collGroupUsers = null;
        if ($this->collGroupActions instanceof PropelCollection) {
            $this->collGroupActions->clearIterator();
        }
        $this->collGroupActions = null;
        $this->aUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GroupPeer::DEFAULT_STRING_FORMAT);
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