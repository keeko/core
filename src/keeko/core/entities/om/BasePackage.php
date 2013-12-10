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
use \PropelQuery;
use keeko\core\entities\Application;
use keeko\core\entities\ApplicationQuery;
use keeko\core\entities\Design;
use keeko\core\entities\DesignQuery;
use keeko\core\entities\Module;
use keeko\core\entities\ModuleQuery;
use keeko\core\entities\Package;
use keeko\core\entities\PackagePeer;
use keeko\core\entities\PackageQuery;

/**
 * Base class that represents a row from the 'keeko_package' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BasePackage extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\PackagePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PackagePeer
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
     * The value for the installed_version field.
     * @var        string
     */
    protected $installed_version;

    /**
     * The value for the descendant_class field.
     * @var        string
     */
    protected $descendant_class;

    /**
     * @var        PropelObjectCollection|Application[] Collection to store aggregation of Application objects.
     */
    protected $collApplications;
    protected $collApplicationsPartial;

    /**
     * @var        Design one-to-one related Design object
     */
    protected $singleDesign;

    /**
     * @var        Module one-to-one related Module object
     */
    protected $singleModule;

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
    protected $applicationsScheduledForDeletion = null;

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
     * Get the [installed_version] column value.
     *
     * @return string
     */
    public function getInstalledVersion()
    {

        return $this->installed_version;
    }

    /**
     * Get the [descendant_class] column value.
     *
     * @return string
     */
    public function getDescendantClass()
    {

        return $this->descendant_class;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Package The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PackagePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Package The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = PackagePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return Package The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PackagePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Package The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PackagePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [installed_version] column.
     *
     * @param  string $v new value
     * @return Package The current object (for fluent API support)
     */
    public function setInstalledVersion($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->installed_version !== $v) {
            $this->installed_version = $v;
            $this->modifiedColumns[] = PackagePeer::INSTALLED_VERSION;
        }


        return $this;
    } // setInstalledVersion()

    /**
     * Set the value of [descendant_class] column.
     *
     * @param  string $v new value
     * @return Package The current object (for fluent API support)
     */
    public function setDescendantClass($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->descendant_class !== $v) {
            $this->descendant_class = $v;
            $this->modifiedColumns[] = PackagePeer::DESCENDANT_CLASS;
        }


        return $this;
    } // setDescendantClass()

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
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->installed_version = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->descendant_class = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 6; // 6 = PackagePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Package object", $e);
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
            $con = Propel::getConnection(PackagePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PackagePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collApplications = null;

            $this->singleDesign = null;

            $this->singleModule = null;

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
            $con = Propel::getConnection(PackagePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PackageQuery::create()
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
            $con = Propel::getConnection(PackagePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                PackagePeer::addInstanceToPool($this);
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

            if ($this->applicationsScheduledForDeletion !== null) {
                if (!$this->applicationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->applicationsScheduledForDeletion as $application) {
                        // need to save related object because we set the relation to null
                        $application->save($con);
                    }
                    $this->applicationsScheduledForDeletion = null;
                }
            }

            if ($this->collApplications !== null) {
                foreach ($this->collApplications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->singleDesign !== null) {
                if (!$this->singleDesign->isDeleted() && ($this->singleDesign->isNew() || $this->singleDesign->isModified())) {
                        $affectedRows += $this->singleDesign->save($con);
                }
            }

            if ($this->singleModule !== null) {
                if (!$this->singleModule->isDeleted() && ($this->singleModule->isNew() || $this->singleModule->isModified())) {
                        $affectedRows += $this->singleModule->save($con);
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

        $this->modifiedColumns[] = PackagePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PackagePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PackagePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PackagePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(PackagePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PackagePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PackagePeer::INSTALLED_VERSION)) {
            $modifiedColumns[':p' . $index++]  = '`installed_version`';
        }
        if ($this->isColumnModified(PackagePeer::DESCENDANT_CLASS)) {
            $modifiedColumns[':p' . $index++]  = '`descendant_class`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_package` (%s) VALUES (%s)',
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
                    case '`installed_version`':
                        $stmt->bindValue($identifier, $this->installed_version, PDO::PARAM_STR);
                        break;
                    case '`descendant_class`':
                        $stmt->bindValue($identifier, $this->descendant_class, PDO::PARAM_STR);
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


            if (($retval = PackagePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collApplications !== null) {
                    foreach ($this->collApplications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->singleDesign !== null) {
                    if (!$this->singleDesign->validate($columns)) {
                        $failureMap = array_merge($failureMap, $this->singleDesign->getValidationFailures());
                    }
                }

                if ($this->singleModule !== null) {
                    if (!$this->singleModule->validate($columns)) {
                        $failureMap = array_merge($failureMap, $this->singleModule->getValidationFailures());
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
        $pos = PackagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getInstalledVersion();
                break;
            case 5:
                return $this->getDescendantClass();
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
        if (isset($alreadyDumpedObjects['Package'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Package'][$this->getPrimaryKey()] = true;
        $keys = PackagePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getInstalledVersion(),
            $keys[5] => $this->getDescendantClass(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collApplications) {
                $result['Applications'] = $this->collApplications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->singleDesign) {
                $result['Design'] = $this->singleDesign->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->singleModule) {
                $result['Module'] = $this->singleModule->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
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
        $pos = PackagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setInstalledVersion($value);
                break;
            case 5:
                $this->setDescendantClass($value);
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
        $keys = PackagePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setInstalledVersion($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDescendantClass($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PackagePeer::DATABASE_NAME);

        if ($this->isColumnModified(PackagePeer::ID)) $criteria->add(PackagePeer::ID, $this->id);
        if ($this->isColumnModified(PackagePeer::NAME)) $criteria->add(PackagePeer::NAME, $this->name);
        if ($this->isColumnModified(PackagePeer::TITLE)) $criteria->add(PackagePeer::TITLE, $this->title);
        if ($this->isColumnModified(PackagePeer::DESCRIPTION)) $criteria->add(PackagePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PackagePeer::INSTALLED_VERSION)) $criteria->add(PackagePeer::INSTALLED_VERSION, $this->installed_version);
        if ($this->isColumnModified(PackagePeer::DESCENDANT_CLASS)) $criteria->add(PackagePeer::DESCENDANT_CLASS, $this->descendant_class);

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
        $criteria = new Criteria(PackagePeer::DATABASE_NAME);
        $criteria->add(PackagePeer::ID, $this->id);

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
     * @param object $copyObj An object of Package (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setInstalledVersion($this->getInstalledVersion());
        $copyObj->setDescendantClass($this->getDescendantClass());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getApplications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addApplication($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getDesign();
            if ($relObj) {
                $copyObj->setDesign($relObj->copy($deepCopy));
            }

            $relObj = $this->getModule();
            if ($relObj) {
                $copyObj->setModule($relObj->copy($deepCopy));
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
     * @return Package Clone of current object.
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
     * @return PackagePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PackagePeer();
        }

        return self::$peer;
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
        if ('Application' == $relationName) {
            $this->initApplications();
        }
    }

    /**
     * Clears out the collApplications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Package The current object (for fluent API support)
     * @see        addApplications()
     */
    public function clearApplications()
    {
        $this->collApplications = null; // important to set this to null since that means it is uninitialized
        $this->collApplicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collApplications collection loaded partially
     *
     * @return void
     */
    public function resetPartialApplications($v = true)
    {
        $this->collApplicationsPartial = $v;
    }

    /**
     * Initializes the collApplications collection.
     *
     * By default this just sets the collApplications collection to an empty array (like clearcollApplications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initApplications($overrideExisting = true)
    {
        if (null !== $this->collApplications && !$overrideExisting) {
            return;
        }
        $this->collApplications = new PropelObjectCollection();
        $this->collApplications->setModel('Application');
    }

    /**
     * Gets an array of Application objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Package is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Application[] List of Application objects
     * @throws PropelException
     */
    public function getApplications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collApplicationsPartial && !$this->isNew();
        if (null === $this->collApplications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collApplications) {
                // return empty collection
                $this->initApplications();
            } else {
                $collApplications = ApplicationQuery::create(null, $criteria)
                    ->filterByPackage($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collApplicationsPartial && count($collApplications)) {
                      $this->initApplications(false);

                      foreach ($collApplications as $obj) {
                        if (false == $this->collApplications->contains($obj)) {
                          $this->collApplications->append($obj);
                        }
                      }

                      $this->collApplicationsPartial = true;
                    }

                    $collApplications->getInternalIterator()->rewind();

                    return $collApplications;
                }

                if ($partial && $this->collApplications) {
                    foreach ($this->collApplications as $obj) {
                        if ($obj->isNew()) {
                            $collApplications[] = $obj;
                        }
                    }
                }

                $this->collApplications = $collApplications;
                $this->collApplicationsPartial = false;
            }
        }

        return $this->collApplications;
    }

    /**
     * Sets a collection of Application objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $applications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Package The current object (for fluent API support)
     */
    public function setApplications(PropelCollection $applications, PropelPDO $con = null)
    {
        $applicationsToDelete = $this->getApplications(new Criteria(), $con)->diff($applications);


        $this->applicationsScheduledForDeletion = $applicationsToDelete;

        foreach ($applicationsToDelete as $applicationRemoved) {
            $applicationRemoved->setPackage(null);
        }

        $this->collApplications = null;
        foreach ($applications as $application) {
            $this->addApplication($application);
        }

        $this->collApplications = $applications;
        $this->collApplicationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Application objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Application objects.
     * @throws PropelException
     */
    public function countApplications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collApplicationsPartial && !$this->isNew();
        if (null === $this->collApplications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collApplications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getApplications());
            }
            $query = ApplicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPackage($this)
                ->count($con);
        }

        return count($this->collApplications);
    }

    /**
     * Method called to associate a Application object to this object
     * through the Application foreign key attribute.
     *
     * @param    Application $l Application
     * @return Package The current object (for fluent API support)
     */
    public function addApplication(Application $l)
    {
        if ($this->collApplications === null) {
            $this->initApplications();
            $this->collApplicationsPartial = true;
        }

        if (!in_array($l, $this->collApplications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddApplication($l);

            if ($this->applicationsScheduledForDeletion and $this->applicationsScheduledForDeletion->contains($l)) {
                $this->applicationsScheduledForDeletion->remove($this->applicationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Application $application The application object to add.
     */
    protected function doAddApplication($application)
    {
        $this->collApplications[]= $application;
        $application->setPackage($this);
    }

    /**
     * @param	Application $application The application object to remove.
     * @return Package The current object (for fluent API support)
     */
    public function removeApplication($application)
    {
        if ($this->getApplications()->contains($application)) {
            $this->collApplications->remove($this->collApplications->search($application));
            if (null === $this->applicationsScheduledForDeletion) {
                $this->applicationsScheduledForDeletion = clone $this->collApplications;
                $this->applicationsScheduledForDeletion->clear();
            }
            $this->applicationsScheduledForDeletion[]= $application;
            $application->setPackage(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Package is new, it will return
     * an empty collection; or if this Package has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Package.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinApplicationType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('ApplicationType', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Package is new, it will return
     * an empty collection; or if this Package has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Package.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinRouter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('Router', $join_behavior);

        return $this->getApplications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Package is new, it will return
     * an empty collection; or if this Package has previously
     * been saved, it will retrieve related Applications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Package.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Application[] List of Application objects
     */
    public function getApplicationsJoinDesign($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationQuery::create(null, $criteria);
        $query->joinWith('Design', $join_behavior);

        return $this->getApplications($query, $con);
    }

    /**
     * Gets a single Design object, which is related to this object by a one-to-one relationship.
     *
     * @param PropelPDO $con optional connection object
     * @return Design
     * @throws PropelException
     */
    public function getDesign(PropelPDO $con = null)
    {

        if ($this->singleDesign === null && !$this->isNew()) {
            $this->singleDesign = DesignQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleDesign;
    }

    /**
     * Sets a single Design object as related to this object by a one-to-one relationship.
     *
     * @param                  Design $v Design
     * @return Package The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDesign(Design $v = null)
    {
        $this->singleDesign = $v;

        // Make sure that that the passed-in Design isn't already associated with this object
        if ($v !== null && $v->getPackage(null, false) === null) {
            $v->setPackage($this);
        }

        return $this;
    }

    /**
     * Gets a single Module object, which is related to this object by a one-to-one relationship.
     *
     * @param PropelPDO $con optional connection object
     * @return Module
     * @throws PropelException
     */
    public function getModule(PropelPDO $con = null)
    {

        if ($this->singleModule === null && !$this->isNew()) {
            $this->singleModule = ModuleQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleModule;
    }

    /**
     * Sets a single Module object as related to this object by a one-to-one relationship.
     *
     * @param                  Module $v Module
     * @return Package The current object (for fluent API support)
     * @throws PropelException
     */
    public function setModule(Module $v = null)
    {
        $this->singleModule = $v;

        // Make sure that that the passed-in Module isn't already associated with this object
        if ($v !== null && $v->getPackage(null, false) === null) {
            $v->setPackage($this);
        }

        return $this;
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
        $this->installed_version = null;
        $this->descendant_class = null;
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
            if ($this->collApplications) {
                foreach ($this->collApplications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->singleDesign) {
                $this->singleDesign->clearAllReferences($deep);
            }
            if ($this->singleModule) {
                $this->singleModule->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collApplications instanceof PropelCollection) {
            $this->collApplications->clearIterator();
        }
        $this->collApplications = null;
        if ($this->singleDesign instanceof PropelCollection) {
            $this->singleDesign->clearIterator();
        }
        $this->singleDesign = null;
        if ($this->singleModule instanceof PropelCollection) {
            $this->singleModule->clearIterator();
        }
        $this->singleModule = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PackagePeer::DEFAULT_STRING_FORMAT);
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

    // concrete_inheritance_parent behavior

    /**
     * Whether or not this object is the parent of a child object
     *
     * @return    bool
     */
    public function hasChildObject()
    {
        return $this->getDescendantClass() !== null;
    }

    /**
     * Get the child object of this object
     *
     * @return    mixed
     */
    public function getChildObject()
    {
        if (!$this->hasChildObject()) {
            return null;
        }
        $childObjectClass = $this->getDescendantClass();
        $childObject = PropelQuery::from($childObjectClass)->findPk($this->getPrimaryKey());

        return $childObject->hasChildObject() ? $childObject->getChildObject() : $childObject;
    }

}
