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
use keeko\core\entities\Page;
use keeko\core\entities\PageQuery;
use keeko\core\entities\Route;
use keeko\core\entities\RoutePeer;
use keeko\core\entities\RouteQuery;

/**
 * Base class that represents a row from the 'keeko_route' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseRoute extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\RoutePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        RoutePeer
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
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * The value for the redirect_id field.
     * @var        int
     */
    protected $redirect_id;

    /**
     * The value for the page_id field.
     * @var        int
     */
    protected $page_id;

    /**
     * @var        Route
     */
    protected $aRouteRelatedByRedirectId;

    /**
     * @var        Page
     */
    protected $aPage;

    /**
     * @var        PropelObjectCollection|Route[] Collection to store aggregation of Route objects.
     */
    protected $collRoutesRelatedById;
    protected $collRoutesRelatedByIdPartial;

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
    protected $routesRelatedByIdScheduledForDeletion = null;

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
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {

        return $this->slug;
    }

    /**
     * Get the [redirect_id] column value.
     *
     * @return int
     */
    public function getRedirectId()
    {

        return $this->redirect_id;
    }

    /**
     * Get the [page_id] column value.
     *
     * @return int
     */
    public function getPageId()
    {

        return $this->page_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Route The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = RoutePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return Route The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = RoutePeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Set the value of [redirect_id] column.
     *
     * @param  int $v new value
     * @return Route The current object (for fluent API support)
     */
    public function setRedirectId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->redirect_id !== $v) {
            $this->redirect_id = $v;
            $this->modifiedColumns[] = RoutePeer::REDIRECT_ID;
        }

        if ($this->aRouteRelatedByRedirectId !== null && $this->aRouteRelatedByRedirectId->getId() !== $v) {
            $this->aRouteRelatedByRedirectId = null;
        }


        return $this;
    } // setRedirectId()

    /**
     * Set the value of [page_id] column.
     *
     * @param  int $v new value
     * @return Route The current object (for fluent API support)
     */
    public function setPageId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->page_id !== $v) {
            $this->page_id = $v;
            $this->modifiedColumns[] = RoutePeer::PAGE_ID;
        }

        if ($this->aPage !== null && $this->aPage->getId() !== $v) {
            $this->aPage = null;
        }


        return $this;
    } // setPageId()

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
            $this->slug = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->redirect_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->page_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 4; // 4 = RoutePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Route object", $e);
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

        if ($this->aRouteRelatedByRedirectId !== null && $this->redirect_id !== $this->aRouteRelatedByRedirectId->getId()) {
            $this->aRouteRelatedByRedirectId = null;
        }
        if ($this->aPage !== null && $this->page_id !== $this->aPage->getId()) {
            $this->aPage = null;
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
            $con = Propel::getConnection(RoutePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = RoutePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aRouteRelatedByRedirectId = null;
            $this->aPage = null;
            $this->collRoutesRelatedById = null;

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
            $con = Propel::getConnection(RoutePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = RouteQuery::create()
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
            $con = Propel::getConnection(RoutePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                RoutePeer::addInstanceToPool($this);
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

            if ($this->aRouteRelatedByRedirectId !== null) {
                if ($this->aRouteRelatedByRedirectId->isModified() || $this->aRouteRelatedByRedirectId->isNew()) {
                    $affectedRows += $this->aRouteRelatedByRedirectId->save($con);
                }
                $this->setRouteRelatedByRedirectId($this->aRouteRelatedByRedirectId);
            }

            if ($this->aPage !== null) {
                if ($this->aPage->isModified() || $this->aPage->isNew()) {
                    $affectedRows += $this->aPage->save($con);
                }
                $this->setPage($this->aPage);
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

            if ($this->routesRelatedByIdScheduledForDeletion !== null) {
                if (!$this->routesRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->routesRelatedByIdScheduledForDeletion as $routeRelatedById) {
                        // need to save related object because we set the relation to null
                        $routeRelatedById->save($con);
                    }
                    $this->routesRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collRoutesRelatedById !== null) {
                foreach ($this->collRoutesRelatedById as $referrerFK) {
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

        $this->modifiedColumns[] = RoutePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . RoutePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(RoutePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(RoutePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(RoutePeer::REDIRECT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`redirect_id`';
        }
        if ($this->isColumnModified(RoutePeer::PAGE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`page_id`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_route` (%s) VALUES (%s)',
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
                    case '`slug`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                    case '`redirect_id`':
                        $stmt->bindValue($identifier, $this->redirect_id, PDO::PARAM_INT);
                        break;
                    case '`page_id`':
                        $stmt->bindValue($identifier, $this->page_id, PDO::PARAM_INT);
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

            if ($this->aRouteRelatedByRedirectId !== null) {
                if (!$this->aRouteRelatedByRedirectId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aRouteRelatedByRedirectId->getValidationFailures());
                }
            }

            if ($this->aPage !== null) {
                if (!$this->aPage->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPage->getValidationFailures());
                }
            }


            if (($retval = RoutePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collRoutesRelatedById !== null) {
                    foreach ($this->collRoutesRelatedById as $referrerFK) {
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
        $pos = RoutePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getSlug();
                break;
            case 2:
                return $this->getRedirectId();
                break;
            case 3:
                return $this->getPageId();
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
        if (isset($alreadyDumpedObjects['Route'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Route'][$this->getPrimaryKey()] = true;
        $keys = RoutePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSlug(),
            $keys[2] => $this->getRedirectId(),
            $keys[3] => $this->getPageId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aRouteRelatedByRedirectId) {
                $result['RouteRelatedByRedirectId'] = $this->aRouteRelatedByRedirectId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPage) {
                $result['Page'] = $this->aPage->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collRoutesRelatedById) {
                $result['RoutesRelatedById'] = $this->collRoutesRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = RoutePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setSlug($value);
                break;
            case 2:
                $this->setRedirectId($value);
                break;
            case 3:
                $this->setPageId($value);
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
        $keys = RoutePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setSlug($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setRedirectId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPageId($arr[$keys[3]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(RoutePeer::DATABASE_NAME);

        if ($this->isColumnModified(RoutePeer::ID)) $criteria->add(RoutePeer::ID, $this->id);
        if ($this->isColumnModified(RoutePeer::SLUG)) $criteria->add(RoutePeer::SLUG, $this->slug);
        if ($this->isColumnModified(RoutePeer::REDIRECT_ID)) $criteria->add(RoutePeer::REDIRECT_ID, $this->redirect_id);
        if ($this->isColumnModified(RoutePeer::PAGE_ID)) $criteria->add(RoutePeer::PAGE_ID, $this->page_id);

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
        $criteria = new Criteria(RoutePeer::DATABASE_NAME);
        $criteria->add(RoutePeer::ID, $this->id);

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
     * @param object $copyObj An object of Route (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setSlug($this->getSlug());
        $copyObj->setRedirectId($this->getRedirectId());
        $copyObj->setPageId($this->getPageId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getRoutesRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRouteRelatedById($relObj->copy($deepCopy));
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
     * @return Route Clone of current object.
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
     * @return RoutePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new RoutePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Route object.
     *
     * @param                  Route $v
     * @return Route The current object (for fluent API support)
     * @throws PropelException
     */
    public function setRouteRelatedByRedirectId(Route $v = null)
    {
        if ($v === null) {
            $this->setRedirectId(NULL);
        } else {
            $this->setRedirectId($v->getId());
        }

        $this->aRouteRelatedByRedirectId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Route object, it will not be re-added.
        if ($v !== null) {
            $v->addRouteRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated Route object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Route The associated Route object.
     * @throws PropelException
     */
    public function getRouteRelatedByRedirectId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aRouteRelatedByRedirectId === null && ($this->redirect_id !== null) && $doQuery) {
            $this->aRouteRelatedByRedirectId = RouteQuery::create()->findPk($this->redirect_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aRouteRelatedByRedirectId->addRoutesRelatedById($this);
             */
        }

        return $this->aRouteRelatedByRedirectId;
    }

    /**
     * Declares an association between this object and a Page object.
     *
     * @param                  Page $v
     * @return Route The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPage(Page $v = null)
    {
        if ($v === null) {
            $this->setPageId(NULL);
        } else {
            $this->setPageId($v->getId());
        }

        $this->aPage = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Page object, it will not be re-added.
        if ($v !== null) {
            $v->addRoute($this);
        }


        return $this;
    }


    /**
     * Get the associated Page object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Page The associated Page object.
     * @throws PropelException
     */
    public function getPage(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPage === null && ($this->page_id !== null) && $doQuery) {
            $this->aPage = PageQuery::create()->findPk($this->page_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPage->addRoutes($this);
             */
        }

        return $this->aPage;
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
        if ('RouteRelatedById' == $relationName) {
            $this->initRoutesRelatedById();
        }
    }

    /**
     * Clears out the collRoutesRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Route The current object (for fluent API support)
     * @see        addRoutesRelatedById()
     */
    public function clearRoutesRelatedById()
    {
        $this->collRoutesRelatedById = null; // important to set this to null since that means it is uninitialized
        $this->collRoutesRelatedByIdPartial = null;

        return $this;
    }

    /**
     * reset is the collRoutesRelatedById collection loaded partially
     *
     * @return void
     */
    public function resetPartialRoutesRelatedById($v = true)
    {
        $this->collRoutesRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collRoutesRelatedById collection.
     *
     * By default this just sets the collRoutesRelatedById collection to an empty array (like clearcollRoutesRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRoutesRelatedById($overrideExisting = true)
    {
        if (null !== $this->collRoutesRelatedById && !$overrideExisting) {
            return;
        }
        $this->collRoutesRelatedById = new PropelObjectCollection();
        $this->collRoutesRelatedById->setModel('Route');
    }

    /**
     * Gets an array of Route objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Route is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Route[] List of Route objects
     * @throws PropelException
     */
    public function getRoutesRelatedById($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collRoutesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collRoutesRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRoutesRelatedById) {
                // return empty collection
                $this->initRoutesRelatedById();
            } else {
                $collRoutesRelatedById = RouteQuery::create(null, $criteria)
                    ->filterByRouteRelatedByRedirectId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collRoutesRelatedByIdPartial && count($collRoutesRelatedById)) {
                      $this->initRoutesRelatedById(false);

                      foreach ($collRoutesRelatedById as $obj) {
                        if (false == $this->collRoutesRelatedById->contains($obj)) {
                          $this->collRoutesRelatedById->append($obj);
                        }
                      }

                      $this->collRoutesRelatedByIdPartial = true;
                    }

                    $collRoutesRelatedById->getInternalIterator()->rewind();

                    return $collRoutesRelatedById;
                }

                if ($partial && $this->collRoutesRelatedById) {
                    foreach ($this->collRoutesRelatedById as $obj) {
                        if ($obj->isNew()) {
                            $collRoutesRelatedById[] = $obj;
                        }
                    }
                }

                $this->collRoutesRelatedById = $collRoutesRelatedById;
                $this->collRoutesRelatedByIdPartial = false;
            }
        }

        return $this->collRoutesRelatedById;
    }

    /**
     * Sets a collection of RouteRelatedById objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $routesRelatedById A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Route The current object (for fluent API support)
     */
    public function setRoutesRelatedById(PropelCollection $routesRelatedById, PropelPDO $con = null)
    {
        $routesRelatedByIdToDelete = $this->getRoutesRelatedById(new Criteria(), $con)->diff($routesRelatedById);


        $this->routesRelatedByIdScheduledForDeletion = $routesRelatedByIdToDelete;

        foreach ($routesRelatedByIdToDelete as $routeRelatedByIdRemoved) {
            $routeRelatedByIdRemoved->setRouteRelatedByRedirectId(null);
        }

        $this->collRoutesRelatedById = null;
        foreach ($routesRelatedById as $routeRelatedById) {
            $this->addRouteRelatedById($routeRelatedById);
        }

        $this->collRoutesRelatedById = $routesRelatedById;
        $this->collRoutesRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Route objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Route objects.
     * @throws PropelException
     */
    public function countRoutesRelatedById(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collRoutesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collRoutesRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRoutesRelatedById) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRoutesRelatedById());
            }
            $query = RouteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRouteRelatedByRedirectId($this)
                ->count($con);
        }

        return count($this->collRoutesRelatedById);
    }

    /**
     * Method called to associate a Route object to this object
     * through the Route foreign key attribute.
     *
     * @param    Route $l Route
     * @return Route The current object (for fluent API support)
     */
    public function addRouteRelatedById(Route $l)
    {
        if ($this->collRoutesRelatedById === null) {
            $this->initRoutesRelatedById();
            $this->collRoutesRelatedByIdPartial = true;
        }

        if (!in_array($l, $this->collRoutesRelatedById->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddRouteRelatedById($l);

            if ($this->routesRelatedByIdScheduledForDeletion and $this->routesRelatedByIdScheduledForDeletion->contains($l)) {
                $this->routesRelatedByIdScheduledForDeletion->remove($this->routesRelatedByIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	RouteRelatedById $routeRelatedById The routeRelatedById object to add.
     */
    protected function doAddRouteRelatedById($routeRelatedById)
    {
        $this->collRoutesRelatedById[]= $routeRelatedById;
        $routeRelatedById->setRouteRelatedByRedirectId($this);
    }

    /**
     * @param	RouteRelatedById $routeRelatedById The routeRelatedById object to remove.
     * @return Route The current object (for fluent API support)
     */
    public function removeRouteRelatedById($routeRelatedById)
    {
        if ($this->getRoutesRelatedById()->contains($routeRelatedById)) {
            $this->collRoutesRelatedById->remove($this->collRoutesRelatedById->search($routeRelatedById));
            if (null === $this->routesRelatedByIdScheduledForDeletion) {
                $this->routesRelatedByIdScheduledForDeletion = clone $this->collRoutesRelatedById;
                $this->routesRelatedByIdScheduledForDeletion->clear();
            }
            $this->routesRelatedByIdScheduledForDeletion[]= $routeRelatedById;
            $routeRelatedById->setRouteRelatedByRedirectId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Route is new, it will return
     * an empty collection; or if this Route has previously
     * been saved, it will retrieve related RoutesRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Route.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Route[] List of Route objects
     */
    public function getRoutesRelatedByIdJoinPage($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = RouteQuery::create(null, $criteria);
        $query->joinWith('Page', $join_behavior);

        return $this->getRoutesRelatedById($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->slug = null;
        $this->redirect_id = null;
        $this->page_id = null;
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
            if ($this->collRoutesRelatedById) {
                foreach ($this->collRoutesRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aRouteRelatedByRedirectId instanceof Persistent) {
              $this->aRouteRelatedByRedirectId->clearAllReferences($deep);
            }
            if ($this->aPage instanceof Persistent) {
              $this->aPage->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collRoutesRelatedById instanceof PropelCollection) {
            $this->collRoutesRelatedById->clearIterator();
        }
        $this->collRoutesRelatedById = null;
        $this->aRouteRelatedByRedirectId = null;
        $this->aPage = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RoutePeer::DEFAULT_STRING_FORMAT);
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
