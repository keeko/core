<?php

namespace keeko\core\entities\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use keeko\core\entities\Application;
use keeko\core\entities\ApplicationQuery;
use keeko\core\entities\ApplicationUri;
use keeko\core\entities\ApplicationUriPeer;
use keeko\core\entities\ApplicationUriQuery;
use keeko\core\entities\Localization;
use keeko\core\entities\LocalizationQuery;

/**
 * Base class that represents a row from the 'keeko_application_uri' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseApplicationUri extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\ApplicationUriPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ApplicationUriPeer
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
     * The value for the httphost field.
     * @var        string
     */
    protected $httphost;

    /**
     * The value for the basepath field.
     * @var        string
     */
    protected $basepath;

    /**
     * The value for the secure field.
     * @var        boolean
     */
    protected $secure;

    /**
     * The value for the application_id field.
     * @var        int
     */
    protected $application_id;

    /**
     * The value for the localization_id field.
     * @var        int
     */
    protected $localization_id;

    /**
     * @var        Application
     */
    protected $aApplication;

    /**
     * @var        Localization
     */
    protected $aLocalization;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [httphost] column value.
     *
     * @return string
     */
    public function getHttphost()
    {
        return $this->httphost;
    }

    /**
     * Get the [basepath] column value.
     *
     * @return string
     */
    public function getBasepath()
    {
        return $this->basepath;
    }

    /**
     * Get the [secure] column value.
     *
     * @return boolean
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * Get the [application_id] column value.
     *
     * @return int
     */
    public function getApplicationId()
    {
        return $this->application_id;
    }

    /**
     * Get the [localization_id] column value.
     *
     * @return int
     */
    public function getLocalizationId()
    {
        return $this->localization_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return ApplicationUri The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ApplicationUriPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [httphost] column.
     *
     * @param string $v new value
     * @return ApplicationUri The current object (for fluent API support)
     */
    public function setHttphost($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->httphost !== $v) {
            $this->httphost = $v;
            $this->modifiedColumns[] = ApplicationUriPeer::HTTPHOST;
        }


        return $this;
    } // setHttphost()

    /**
     * Set the value of [basepath] column.
     *
     * @param string $v new value
     * @return ApplicationUri The current object (for fluent API support)
     */
    public function setBasepath($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->basepath !== $v) {
            $this->basepath = $v;
            $this->modifiedColumns[] = ApplicationUriPeer::BASEPATH;
        }


        return $this;
    } // setBasepath()

    /**
     * Sets the value of the [secure] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return ApplicationUri The current object (for fluent API support)
     */
    public function setSecure($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->secure !== $v) {
            $this->secure = $v;
            $this->modifiedColumns[] = ApplicationUriPeer::SECURE;
        }


        return $this;
    } // setSecure()

    /**
     * Set the value of [application_id] column.
     *
     * @param int $v new value
     * @return ApplicationUri The current object (for fluent API support)
     */
    public function setApplicationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->application_id !== $v) {
            $this->application_id = $v;
            $this->modifiedColumns[] = ApplicationUriPeer::APPLICATION_ID;
        }

        if ($this->aApplication !== null && $this->aApplication->getId() !== $v) {
            $this->aApplication = null;
        }


        return $this;
    } // setApplicationId()

    /**
     * Set the value of [localization_id] column.
     *
     * @param int $v new value
     * @return ApplicationUri The current object (for fluent API support)
     */
    public function setLocalizationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->localization_id !== $v) {
            $this->localization_id = $v;
            $this->modifiedColumns[] = ApplicationUriPeer::LOCALIZATION_ID;
        }

        if ($this->aLocalization !== null && $this->aLocalization->getId() !== $v) {
            $this->aLocalization = null;
        }


        return $this;
    } // setLocalizationId()

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
            $this->httphost = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->basepath = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->secure = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->application_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->localization_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 6; // 6 = ApplicationUriPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating ApplicationUri object", $e);
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

        if ($this->aApplication !== null && $this->application_id !== $this->aApplication->getId()) {
            $this->aApplication = null;
        }
        if ($this->aLocalization !== null && $this->localization_id !== $this->aLocalization->getId()) {
            $this->aLocalization = null;
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
            $con = Propel::getConnection(ApplicationUriPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ApplicationUriPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aApplication = null;
            $this->aLocalization = null;
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
            $con = Propel::getConnection(ApplicationUriPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ApplicationUriQuery::create()
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
            $con = Propel::getConnection(ApplicationUriPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                ApplicationUriPeer::addInstanceToPool($this);
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

            if ($this->aApplication !== null) {
                if ($this->aApplication->isModified() || $this->aApplication->isNew()) {
                    $affectedRows += $this->aApplication->save($con);
                }
                $this->setApplication($this->aApplication);
            }

            if ($this->aLocalization !== null) {
                if ($this->aLocalization->isModified() || $this->aLocalization->isNew()) {
                    $affectedRows += $this->aLocalization->save($con);
                }
                $this->setLocalization($this->aLocalization);
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

        $this->modifiedColumns[] = ApplicationUriPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ApplicationUriPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ApplicationUriPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ApplicationUriPeer::HTTPHOST)) {
            $modifiedColumns[':p' . $index++]  = '`httphost`';
        }
        if ($this->isColumnModified(ApplicationUriPeer::BASEPATH)) {
            $modifiedColumns[':p' . $index++]  = '`basepath`';
        }
        if ($this->isColumnModified(ApplicationUriPeer::SECURE)) {
            $modifiedColumns[':p' . $index++]  = '`secure`';
        }
        if ($this->isColumnModified(ApplicationUriPeer::APPLICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`application_id`';
        }
        if ($this->isColumnModified(ApplicationUriPeer::LOCALIZATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`localization_id`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_application_uri` (%s) VALUES (%s)',
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
                    case '`httphost`':
                        $stmt->bindValue($identifier, $this->httphost, PDO::PARAM_STR);
                        break;
                    case '`basepath`':
                        $stmt->bindValue($identifier, $this->basepath, PDO::PARAM_STR);
                        break;
                    case '`secure`':
                        $stmt->bindValue($identifier, (int) $this->secure, PDO::PARAM_INT);
                        break;
                    case '`application_id`':
                        $stmt->bindValue($identifier, $this->application_id, PDO::PARAM_INT);
                        break;
                    case '`localization_id`':
                        $stmt->bindValue($identifier, $this->localization_id, PDO::PARAM_INT);
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

            if ($this->aApplication !== null) {
                if (!$this->aApplication->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aApplication->getValidationFailures());
                }
            }

            if ($this->aLocalization !== null) {
                if (!$this->aLocalization->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLocalization->getValidationFailures());
                }
            }


            if (($retval = ApplicationUriPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = ApplicationUriPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getHttphost();
                break;
            case 2:
                return $this->getBasepath();
                break;
            case 3:
                return $this->getSecure();
                break;
            case 4:
                return $this->getApplicationId();
                break;
            case 5:
                return $this->getLocalizationId();
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
        if (isset($alreadyDumpedObjects['ApplicationUri'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ApplicationUri'][$this->getPrimaryKey()] = true;
        $keys = ApplicationUriPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getHttphost(),
            $keys[2] => $this->getBasepath(),
            $keys[3] => $this->getSecure(),
            $keys[4] => $this->getApplicationId(),
            $keys[5] => $this->getLocalizationId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aApplication) {
                $result['Application'] = $this->aApplication->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLocalization) {
                $result['Localization'] = $this->aLocalization->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = ApplicationUriPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setHttphost($value);
                break;
            case 2:
                $this->setBasepath($value);
                break;
            case 3:
                $this->setSecure($value);
                break;
            case 4:
                $this->setApplicationId($value);
                break;
            case 5:
                $this->setLocalizationId($value);
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
        $keys = ApplicationUriPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setHttphost($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setBasepath($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSecure($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setApplicationId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setLocalizationId($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ApplicationUriPeer::DATABASE_NAME);

        if ($this->isColumnModified(ApplicationUriPeer::ID)) $criteria->add(ApplicationUriPeer::ID, $this->id);
        if ($this->isColumnModified(ApplicationUriPeer::HTTPHOST)) $criteria->add(ApplicationUriPeer::HTTPHOST, $this->httphost);
        if ($this->isColumnModified(ApplicationUriPeer::BASEPATH)) $criteria->add(ApplicationUriPeer::BASEPATH, $this->basepath);
        if ($this->isColumnModified(ApplicationUriPeer::SECURE)) $criteria->add(ApplicationUriPeer::SECURE, $this->secure);
        if ($this->isColumnModified(ApplicationUriPeer::APPLICATION_ID)) $criteria->add(ApplicationUriPeer::APPLICATION_ID, $this->application_id);
        if ($this->isColumnModified(ApplicationUriPeer::LOCALIZATION_ID)) $criteria->add(ApplicationUriPeer::LOCALIZATION_ID, $this->localization_id);

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
        $criteria = new Criteria(ApplicationUriPeer::DATABASE_NAME);
        $criteria->add(ApplicationUriPeer::ID, $this->id);

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
     * @param object $copyObj An object of ApplicationUri (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setHttphost($this->getHttphost());
        $copyObj->setBasepath($this->getBasepath());
        $copyObj->setSecure($this->getSecure());
        $copyObj->setApplicationId($this->getApplicationId());
        $copyObj->setLocalizationId($this->getLocalizationId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return ApplicationUri Clone of current object.
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
     * @return ApplicationUriPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ApplicationUriPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Application object.
     *
     * @param             Application $v
     * @return ApplicationUri The current object (for fluent API support)
     * @throws PropelException
     */
    public function setApplication(Application $v = null)
    {
        if ($v === null) {
            $this->setApplicationId(NULL);
        } else {
            $this->setApplicationId($v->getId());
        }

        $this->aApplication = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Application object, it will not be re-added.
        if ($v !== null) {
            $v->addApplicationUri($this);
        }


        return $this;
    }


    /**
     * Get the associated Application object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Application The associated Application object.
     * @throws PropelException
     */
    public function getApplication(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aApplication === null && ($this->application_id !== null) && $doQuery) {
            $this->aApplication = ApplicationQuery::create()->findPk($this->application_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aApplication->addApplicationUris($this);
             */
        }

        return $this->aApplication;
    }

    /**
     * Declares an association between this object and a Localization object.
     *
     * @param             Localization $v
     * @return ApplicationUri The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLocalization(Localization $v = null)
    {
        if ($v === null) {
            $this->setLocalizationId(NULL);
        } else {
            $this->setLocalizationId($v->getId());
        }

        $this->aLocalization = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Localization object, it will not be re-added.
        if ($v !== null) {
            $v->addApplicationUri($this);
        }


        return $this;
    }


    /**
     * Get the associated Localization object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Localization The associated Localization object.
     * @throws PropelException
     */
    public function getLocalization(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aLocalization === null && ($this->localization_id !== null) && $doQuery) {
            $this->aLocalization = LocalizationQuery::create()->findPk($this->localization_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLocalization->addApplicationUris($this);
             */
        }

        return $this->aLocalization;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->httphost = null;
        $this->basepath = null;
        $this->secure = null;
        $this->application_id = null;
        $this->localization_id = null;
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
            if ($this->aApplication instanceof Persistent) {
              $this->aApplication->clearAllReferences($deep);
            }
            if ($this->aLocalization instanceof Persistent) {
              $this->aLocalization->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aApplication = null;
        $this->aLocalization = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ApplicationUriPeer::DEFAULT_STRING_FORMAT);
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