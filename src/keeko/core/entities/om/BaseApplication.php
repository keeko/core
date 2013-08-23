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
use keeko\core\entities\Application;
use keeko\core\entities\ApplicationExtraProperty;
use keeko\core\entities\ApplicationExtraPropertyQuery;
use keeko\core\entities\ApplicationPeer;
use keeko\core\entities\ApplicationQuery;
use keeko\core\entities\ApplicationType;
use keeko\core\entities\ApplicationTypeQuery;
use keeko\core\entities\ApplicationUri;
use keeko\core\entities\ApplicationUriQuery;
use keeko\core\entities\Design;
use keeko\core\entities\DesignQuery;
use keeko\core\entities\Package;
use keeko\core\entities\PackageQuery;
use keeko\core\entities\Page;
use keeko\core\entities\PageQuery;
use keeko\core\entities\Router;
use keeko\core\entities\RouterQuery;

/**
 * Base class that represents a row from the 'keeko_application' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseApplication extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\ApplicationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ApplicationPeer
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
     * The value for the application_type_id field.
     * @var        int
     */
    protected $application_type_id;

    /**
     * The value for the router_id field.
     * @var        int
     */
    protected $router_id;

    /**
     * The value for the design_id field.
     * @var        int
     */
    protected $design_id;

    /**
     * The value for the package_id field.
     * @var        int
     */
    protected $package_id;

    /**
     * @var        ApplicationType
     */
    protected $aApplicationType;

    /**
     * @var        Package
     */
    protected $aPackage;

    /**
     * @var        Router
     */
    protected $aRouter;

    /**
     * @var        Design
     */
    protected $aDesign;

    /**
     * @var        PropelObjectCollection|ApplicationUri[] Collection to store aggregation of ApplicationUri objects.
     */
    protected $collApplicationUris;
    protected $collApplicationUrisPartial;

    /**
     * @var        PropelObjectCollection|Page[] Collection to store aggregation of Page objects.
     */
    protected $collPages;
    protected $collPagesPartial;

    /**
     * @var        PropelObjectCollection|ApplicationExtraProperty[] Collection to store aggregation of ApplicationExtraProperty objects.
     */
    protected $collApplicationExtraPropertys;
    protected $collApplicationExtraPropertysPartial;

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
    protected $applicationUrisScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $applicationExtraPropertysScheduledForDeletion = null;

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
     * Get the [application_type_id] column value.
     *
     * @return int
     */
    public function getApplicationTypeId()
    {
        return $this->application_type_id;
    }

    /**
     * Get the [router_id] column value.
     *
     * @return int
     */
    public function getRouterId()
    {
        return $this->router_id;
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
     * Get the [package_id] column value.
     *
     * @return int
     */
    public function getPackageId()
    {
        return $this->package_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ApplicationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = ApplicationPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [application_type_id] column.
     *
     * @param int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setApplicationTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->application_type_id !== $v) {
            $this->application_type_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::APPLICATION_TYPE_ID;
        }

        if ($this->aApplicationType !== null && $this->aApplicationType->getId() !== $v) {
            $this->aApplicationType = null;
        }


        return $this;
    } // setApplicationTypeId()

    /**
     * Set the value of [router_id] column.
     *
     * @param int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setRouterId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->router_id !== $v) {
            $this->router_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::ROUTER_ID;
        }

        if ($this->aRouter !== null && $this->aRouter->getId() !== $v) {
            $this->aRouter = null;
        }


        return $this;
    } // setRouterId()

    /**
     * Set the value of [design_id] column.
     *
     * @param int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setDesignId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->design_id !== $v) {
            $this->design_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::DESIGN_ID;
        }

        if ($this->aDesign !== null && $this->aDesign->getId() !== $v) {
            $this->aDesign = null;
        }


        return $this;
    } // setDesignId()

    /**
     * Set the value of [package_id] column.
     *
     * @param int $v new value
     * @return Application The current object (for fluent API support)
     */
    public function setPackageId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->package_id !== $v) {
            $this->package_id = $v;
            $this->modifiedColumns[] = ApplicationPeer::PACKAGE_ID;
        }

        if ($this->aPackage !== null && $this->aPackage->getId() !== $v) {
            $this->aPackage = null;
        }


        return $this;
    } // setPackageId()

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
            $this->application_type_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->router_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->design_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->package_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 6; // 6 = ApplicationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Application object", $e);
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

        if ($this->aApplicationType !== null && $this->application_type_id !== $this->aApplicationType->getId()) {
            $this->aApplicationType = null;
        }
        if ($this->aRouter !== null && $this->router_id !== $this->aRouter->getId()) {
            $this->aRouter = null;
        }
        if ($this->aDesign !== null && $this->design_id !== $this->aDesign->getId()) {
            $this->aDesign = null;
        }
        if ($this->aPackage !== null && $this->package_id !== $this->aPackage->getId()) {
            $this->aPackage = null;
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
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ApplicationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aApplicationType = null;
            $this->aPackage = null;
            $this->aRouter = null;
            $this->aDesign = null;
            $this->collApplicationUris = null;

            $this->collPages = null;

            $this->collApplicationExtraPropertys = null;

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
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ApplicationQuery::create()
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
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                ApplicationPeer::addInstanceToPool($this);
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

            if ($this->aApplicationType !== null) {
                if ($this->aApplicationType->isModified() || $this->aApplicationType->isNew()) {
                    $affectedRows += $this->aApplicationType->save($con);
                }
                $this->setApplicationType($this->aApplicationType);
            }

            if ($this->aPackage !== null) {
                if ($this->aPackage->isModified() || $this->aPackage->isNew()) {
                    $affectedRows += $this->aPackage->save($con);
                }
                $this->setPackage($this->aPackage);
            }

            if ($this->aRouter !== null) {
                if ($this->aRouter->isModified() || $this->aRouter->isNew()) {
                    $affectedRows += $this->aRouter->save($con);
                }
                $this->setRouter($this->aRouter);
            }

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

            if ($this->applicationUrisScheduledForDeletion !== null) {
                if (!$this->applicationUrisScheduledForDeletion->isEmpty()) {
                    ApplicationUriQuery::create()
                        ->filterByPrimaryKeys($this->applicationUrisScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->applicationUrisScheduledForDeletion = null;
                }
            }

            if ($this->collApplicationUris !== null) {
                foreach ($this->collApplicationUris as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

            if ($this->applicationExtraPropertysScheduledForDeletion !== null) {
                if (!$this->applicationExtraPropertysScheduledForDeletion->isEmpty()) {
                    ApplicationExtraPropertyQuery::create()
                        ->filterByPrimaryKeys($this->applicationExtraPropertysScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->applicationExtraPropertysScheduledForDeletion = null;
                }
            }

            if ($this->collApplicationExtraPropertys !== null) {
                foreach ($this->collApplicationExtraPropertys as $referrerFK) {
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

        $this->modifiedColumns[] = ApplicationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ApplicationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ApplicationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ApplicationPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(ApplicationPeer::APPLICATION_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`application_type_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::ROUTER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`router_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::DESIGN_ID)) {
            $modifiedColumns[':p' . $index++]  = '`design_id`';
        }
        if ($this->isColumnModified(ApplicationPeer::PACKAGE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`package_id`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_application` (%s) VALUES (%s)',
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
                    case '`application_type_id`':
                        $stmt->bindValue($identifier, $this->application_type_id, PDO::PARAM_INT);
                        break;
                    case '`router_id`':
                        $stmt->bindValue($identifier, $this->router_id, PDO::PARAM_INT);
                        break;
                    case '`design_id`':
                        $stmt->bindValue($identifier, $this->design_id, PDO::PARAM_INT);
                        break;
                    case '`package_id`':
                        $stmt->bindValue($identifier, $this->package_id, PDO::PARAM_INT);
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

            if ($this->aApplicationType !== null) {
                if (!$this->aApplicationType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aApplicationType->getValidationFailures());
                }
            }

            if ($this->aPackage !== null) {
                if (!$this->aPackage->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPackage->getValidationFailures());
                }
            }

            if ($this->aRouter !== null) {
                if (!$this->aRouter->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aRouter->getValidationFailures());
                }
            }

            if ($this->aDesign !== null) {
                if (!$this->aDesign->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDesign->getValidationFailures());
                }
            }


            if (($retval = ApplicationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collApplicationUris !== null) {
                    foreach ($this->collApplicationUris as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPages !== null) {
                    foreach ($this->collPages as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collApplicationExtraPropertys !== null) {
                    foreach ($this->collApplicationExtraPropertys as $referrerFK) {
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
        $pos = ApplicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getApplicationTypeId();
                break;
            case 3:
                return $this->getRouterId();
                break;
            case 4:
                return $this->getDesignId();
                break;
            case 5:
                return $this->getPackageId();
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
        if (isset($alreadyDumpedObjects['Application'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Application'][$this->getPrimaryKey()] = true;
        $keys = ApplicationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getApplicationTypeId(),
            $keys[3] => $this->getRouterId(),
            $keys[4] => $this->getDesignId(),
            $keys[5] => $this->getPackageId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aApplicationType) {
                $result['ApplicationType'] = $this->aApplicationType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPackage) {
                $result['Package'] = $this->aPackage->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aRouter) {
                $result['Router'] = $this->aRouter->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDesign) {
                $result['Design'] = $this->aDesign->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collApplicationUris) {
                $result['ApplicationUris'] = $this->collApplicationUris->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPages) {
                $result['Pages'] = $this->collPages->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collApplicationExtraPropertys) {
                $result['ApplicationExtraPropertys'] = $this->collApplicationExtraPropertys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ApplicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setApplicationTypeId($value);
                break;
            case 3:
                $this->setRouterId($value);
                break;
            case 4:
                $this->setDesignId($value);
                break;
            case 5:
                $this->setPackageId($value);
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
        $keys = ApplicationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitle($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setApplicationTypeId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setRouterId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDesignId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPackageId($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ApplicationPeer::DATABASE_NAME);

        if ($this->isColumnModified(ApplicationPeer::ID)) $criteria->add(ApplicationPeer::ID, $this->id);
        if ($this->isColumnModified(ApplicationPeer::TITLE)) $criteria->add(ApplicationPeer::TITLE, $this->title);
        if ($this->isColumnModified(ApplicationPeer::APPLICATION_TYPE_ID)) $criteria->add(ApplicationPeer::APPLICATION_TYPE_ID, $this->application_type_id);
        if ($this->isColumnModified(ApplicationPeer::ROUTER_ID)) $criteria->add(ApplicationPeer::ROUTER_ID, $this->router_id);
        if ($this->isColumnModified(ApplicationPeer::DESIGN_ID)) $criteria->add(ApplicationPeer::DESIGN_ID, $this->design_id);
        if ($this->isColumnModified(ApplicationPeer::PACKAGE_ID)) $criteria->add(ApplicationPeer::PACKAGE_ID, $this->package_id);

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
        $criteria = new Criteria(ApplicationPeer::DATABASE_NAME);
        $criteria->add(ApplicationPeer::ID, $this->id);

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
     * @param object $copyObj An object of Application (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setApplicationTypeId($this->getApplicationTypeId());
        $copyObj->setRouterId($this->getRouterId());
        $copyObj->setDesignId($this->getDesignId());
        $copyObj->setPackageId($this->getPackageId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getApplicationUris() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addApplicationUri($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getApplicationExtraPropertys() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addApplicationExtraProperty($relObj->copy($deepCopy));
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
     * @return Application Clone of current object.
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
     * @return ApplicationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ApplicationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a ApplicationType object.
     *
     * @param             ApplicationType $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setApplicationType(ApplicationType $v = null)
    {
        if ($v === null) {
            $this->setApplicationTypeId(NULL);
        } else {
            $this->setApplicationTypeId($v->getId());
        }

        $this->aApplicationType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ApplicationType object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated ApplicationType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return ApplicationType The associated ApplicationType object.
     * @throws PropelException
     */
    public function getApplicationType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aApplicationType === null && ($this->application_type_id !== null) && $doQuery) {
            $this->aApplicationType = ApplicationTypeQuery::create()->findPk($this->application_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aApplicationType->addApplications($this);
             */
        }

        return $this->aApplicationType;
    }

    /**
     * Declares an association between this object and a Package object.
     *
     * @param             Package $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPackage(Package $v = null)
    {
        if ($v === null) {
            $this->setPackageId(NULL);
        } else {
            $this->setPackageId($v->getId());
        }

        $this->aPackage = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Package object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated Package object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Package The associated Package object.
     * @throws PropelException
     */
    public function getPackage(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPackage === null && ($this->package_id !== null) && $doQuery) {
            $this->aPackage = PackageQuery::create()->findPk($this->package_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPackage->addApplications($this);
             */
        }

        return $this->aPackage;
    }

    /**
     * Declares an association between this object and a Router object.
     *
     * @param             Router $v
     * @return Application The current object (for fluent API support)
     * @throws PropelException
     */
    public function setRouter(Router $v = null)
    {
        if ($v === null) {
            $this->setRouterId(NULL);
        } else {
            $this->setRouterId($v->getId());
        }

        $this->aRouter = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Router object, it will not be re-added.
        if ($v !== null) {
            $v->addApplication($this);
        }


        return $this;
    }


    /**
     * Get the associated Router object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Router The associated Router object.
     * @throws PropelException
     */
    public function getRouter(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aRouter === null && ($this->router_id !== null) && $doQuery) {
            $this->aRouter = RouterQuery::create()->findPk($this->router_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aRouter->addApplications($this);
             */
        }

        return $this->aRouter;
    }

    /**
     * Declares an association between this object and a Design object.
     *
     * @param             Design $v
     * @return Application The current object (for fluent API support)
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
            $v->addApplication($this);
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
                $this->aDesign->addApplications($this);
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
        if ('ApplicationUri' == $relationName) {
            $this->initApplicationUris();
        }
        if ('Page' == $relationName) {
            $this->initPages();
        }
        if ('ApplicationExtraProperty' == $relationName) {
            $this->initApplicationExtraPropertys();
        }
    }

    /**
     * Clears out the collApplicationUris collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Application The current object (for fluent API support)
     * @see        addApplicationUris()
     */
    public function clearApplicationUris()
    {
        $this->collApplicationUris = null; // important to set this to null since that means it is uninitialized
        $this->collApplicationUrisPartial = null;

        return $this;
    }

    /**
     * reset is the collApplicationUris collection loaded partially
     *
     * @return void
     */
    public function resetPartialApplicationUris($v = true)
    {
        $this->collApplicationUrisPartial = $v;
    }

    /**
     * Initializes the collApplicationUris collection.
     *
     * By default this just sets the collApplicationUris collection to an empty array (like clearcollApplicationUris());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initApplicationUris($overrideExisting = true)
    {
        if (null !== $this->collApplicationUris && !$overrideExisting) {
            return;
        }
        $this->collApplicationUris = new PropelObjectCollection();
        $this->collApplicationUris->setModel('ApplicationUri');
    }

    /**
     * Gets an array of ApplicationUri objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Application is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ApplicationUri[] List of ApplicationUri objects
     * @throws PropelException
     */
    public function getApplicationUris($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collApplicationUrisPartial && !$this->isNew();
        if (null === $this->collApplicationUris || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collApplicationUris) {
                // return empty collection
                $this->initApplicationUris();
            } else {
                $collApplicationUris = ApplicationUriQuery::create(null, $criteria)
                    ->filterByApplication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collApplicationUrisPartial && count($collApplicationUris)) {
                      $this->initApplicationUris(false);

                      foreach($collApplicationUris as $obj) {
                        if (false == $this->collApplicationUris->contains($obj)) {
                          $this->collApplicationUris->append($obj);
                        }
                      }

                      $this->collApplicationUrisPartial = true;
                    }

                    $collApplicationUris->getInternalIterator()->rewind();
                    return $collApplicationUris;
                }

                if($partial && $this->collApplicationUris) {
                    foreach($this->collApplicationUris as $obj) {
                        if($obj->isNew()) {
                            $collApplicationUris[] = $obj;
                        }
                    }
                }

                $this->collApplicationUris = $collApplicationUris;
                $this->collApplicationUrisPartial = false;
            }
        }

        return $this->collApplicationUris;
    }

    /**
     * Sets a collection of ApplicationUri objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $applicationUris A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Application The current object (for fluent API support)
     */
    public function setApplicationUris(PropelCollection $applicationUris, PropelPDO $con = null)
    {
        $applicationUrisToDelete = $this->getApplicationUris(new Criteria(), $con)->diff($applicationUris);

        $this->applicationUrisScheduledForDeletion = unserialize(serialize($applicationUrisToDelete));

        foreach ($applicationUrisToDelete as $applicationUriRemoved) {
            $applicationUriRemoved->setApplication(null);
        }

        $this->collApplicationUris = null;
        foreach ($applicationUris as $applicationUri) {
            $this->addApplicationUri($applicationUri);
        }

        $this->collApplicationUris = $applicationUris;
        $this->collApplicationUrisPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ApplicationUri objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ApplicationUri objects.
     * @throws PropelException
     */
    public function countApplicationUris(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collApplicationUrisPartial && !$this->isNew();
        if (null === $this->collApplicationUris || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collApplicationUris) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getApplicationUris());
            }
            $query = ApplicationUriQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByApplication($this)
                ->count($con);
        }

        return count($this->collApplicationUris);
    }

    /**
     * Method called to associate a ApplicationUri object to this object
     * through the ApplicationUri foreign key attribute.
     *
     * @param    ApplicationUri $l ApplicationUri
     * @return Application The current object (for fluent API support)
     */
    public function addApplicationUri(ApplicationUri $l)
    {
        if ($this->collApplicationUris === null) {
            $this->initApplicationUris();
            $this->collApplicationUrisPartial = true;
        }
        if (!in_array($l, $this->collApplicationUris->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddApplicationUri($l);
        }

        return $this;
    }

    /**
     * @param	ApplicationUri $applicationUri The applicationUri object to add.
     */
    protected function doAddApplicationUri($applicationUri)
    {
        $this->collApplicationUris[]= $applicationUri;
        $applicationUri->setApplication($this);
    }

    /**
     * @param	ApplicationUri $applicationUri The applicationUri object to remove.
     * @return Application The current object (for fluent API support)
     */
    public function removeApplicationUri($applicationUri)
    {
        if ($this->getApplicationUris()->contains($applicationUri)) {
            $this->collApplicationUris->remove($this->collApplicationUris->search($applicationUri));
            if (null === $this->applicationUrisScheduledForDeletion) {
                $this->applicationUrisScheduledForDeletion = clone $this->collApplicationUris;
                $this->applicationUrisScheduledForDeletion->clear();
            }
            $this->applicationUrisScheduledForDeletion[]= clone $applicationUri;
            $applicationUri->setApplication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Application is new, it will return
     * an empty collection; or if this Application has previously
     * been saved, it will retrieve related ApplicationUris from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Application.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ApplicationUri[] List of ApplicationUri objects
     */
    public function getApplicationUrisJoinLocalization($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ApplicationUriQuery::create(null, $criteria);
        $query->joinWith('Localization', $join_behavior);

        return $this->getApplicationUris($query, $con);
    }

    /**
     * Clears out the collPages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Application The current object (for fluent API support)
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
     * If this Application is new, it will return
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
                    ->filterByApplication($this)
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
     * @return Application The current object (for fluent API support)
     */
    public function setPages(PropelCollection $pages, PropelPDO $con = null)
    {
        $pagesToDelete = $this->getPages(new Criteria(), $con)->diff($pages);

        $this->pagesScheduledForDeletion = unserialize(serialize($pagesToDelete));

        foreach ($pagesToDelete as $pageRemoved) {
            $pageRemoved->setApplication(null);
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
                ->filterByApplication($this)
                ->count($con);
        }

        return count($this->collPages);
    }

    /**
     * Method called to associate a Page object to this object
     * through the Page foreign key attribute.
     *
     * @param    Page $l Page
     * @return Application The current object (for fluent API support)
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
        $page->setApplication($this);
    }

    /**
     * @param	Page $page The page object to remove.
     * @return Application The current object (for fluent API support)
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
            $page->setApplication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Application is new, it will return
     * an empty collection; or if this Application has previously
     * been saved, it will retrieve related Pages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Application.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Page[] List of Page objects
     */
    public function getPagesJoinPageRelatedByParentId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PageQuery::create(null, $criteria);
        $query->joinWith('PageRelatedByParentId', $join_behavior);

        return $this->getPages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Application is new, it will return
     * an empty collection; or if this Application has previously
     * been saved, it will retrieve related Pages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Application.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Page[] List of Page objects
     */
    public function getPagesJoinLayout($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PageQuery::create(null, $criteria);
        $query->joinWith('Layout', $join_behavior);

        return $this->getPages($query, $con);
    }

    /**
     * Clears out the collApplicationExtraPropertys collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Application The current object (for fluent API support)
     * @see        addApplicationExtraPropertys()
     */
    public function clearApplicationExtraPropertys()
    {
        $this->collApplicationExtraPropertys = null; // important to set this to null since that means it is uninitialized
        $this->collApplicationExtraPropertysPartial = null;

        return $this;
    }

    /**
     * reset is the collApplicationExtraPropertys collection loaded partially
     *
     * @return void
     */
    public function resetPartialApplicationExtraPropertys($v = true)
    {
        $this->collApplicationExtraPropertysPartial = $v;
    }

    /**
     * Initializes the collApplicationExtraPropertys collection.
     *
     * By default this just sets the collApplicationExtraPropertys collection to an empty array (like clearcollApplicationExtraPropertys());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initApplicationExtraPropertys($overrideExisting = true)
    {
        if (null !== $this->collApplicationExtraPropertys && !$overrideExisting) {
            return;
        }
        $this->collApplicationExtraPropertys = new PropelObjectCollection();
        $this->collApplicationExtraPropertys->setModel('ApplicationExtraProperty');
    }

    /**
     * Gets an array of ApplicationExtraProperty objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Application is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ApplicationExtraProperty[] List of ApplicationExtraProperty objects
     * @throws PropelException
     */
    public function getApplicationExtraPropertys($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collApplicationExtraPropertysPartial && !$this->isNew();
        if (null === $this->collApplicationExtraPropertys || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collApplicationExtraPropertys) {
                // return empty collection
                $this->initApplicationExtraPropertys();
            } else {
                $collApplicationExtraPropertys = ApplicationExtraPropertyQuery::create(null, $criteria)
                    ->filterByApplication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collApplicationExtraPropertysPartial && count($collApplicationExtraPropertys)) {
                      $this->initApplicationExtraPropertys(false);

                      foreach($collApplicationExtraPropertys as $obj) {
                        if (false == $this->collApplicationExtraPropertys->contains($obj)) {
                          $this->collApplicationExtraPropertys->append($obj);
                        }
                      }

                      $this->collApplicationExtraPropertysPartial = true;
                    }

                    $collApplicationExtraPropertys->getInternalIterator()->rewind();
                    return $collApplicationExtraPropertys;
                }

                if($partial && $this->collApplicationExtraPropertys) {
                    foreach($this->collApplicationExtraPropertys as $obj) {
                        if($obj->isNew()) {
                            $collApplicationExtraPropertys[] = $obj;
                        }
                    }
                }

                $this->collApplicationExtraPropertys = $collApplicationExtraPropertys;
                $this->collApplicationExtraPropertysPartial = false;
            }
        }

        return $this->collApplicationExtraPropertys;
    }

    /**
     * Sets a collection of ApplicationExtraProperty objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $applicationExtraPropertys A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Application The current object (for fluent API support)
     */
    public function setApplicationExtraPropertys(PropelCollection $applicationExtraPropertys, PropelPDO $con = null)
    {
        $applicationExtraPropertysToDelete = $this->getApplicationExtraPropertys(new Criteria(), $con)->diff($applicationExtraPropertys);

        $this->applicationExtraPropertysScheduledForDeletion = unserialize(serialize($applicationExtraPropertysToDelete));

        foreach ($applicationExtraPropertysToDelete as $applicationExtraPropertyRemoved) {
            $applicationExtraPropertyRemoved->setApplication(null);
        }

        $this->collApplicationExtraPropertys = null;
        foreach ($applicationExtraPropertys as $applicationExtraProperty) {
            $this->addApplicationExtraProperty($applicationExtraProperty);
        }

        $this->collApplicationExtraPropertys = $applicationExtraPropertys;
        $this->collApplicationExtraPropertysPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ApplicationExtraProperty objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ApplicationExtraProperty objects.
     * @throws PropelException
     */
    public function countApplicationExtraPropertys(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collApplicationExtraPropertysPartial && !$this->isNew();
        if (null === $this->collApplicationExtraPropertys || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collApplicationExtraPropertys) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getApplicationExtraPropertys());
            }
            $query = ApplicationExtraPropertyQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByApplication($this)
                ->count($con);
        }

        return count($this->collApplicationExtraPropertys);
    }

    /**
     * Method called to associate a ApplicationExtraProperty object to this object
     * through the ApplicationExtraProperty foreign key attribute.
     *
     * @param    ApplicationExtraProperty $l ApplicationExtraProperty
     * @return Application The current object (for fluent API support)
     */
    public function addApplicationExtraProperty(ApplicationExtraProperty $l)
    {
        if ($this->collApplicationExtraPropertys === null) {
            $this->initApplicationExtraPropertys();
            $this->collApplicationExtraPropertysPartial = true;
        }
        if (!in_array($l, $this->collApplicationExtraPropertys->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddApplicationExtraProperty($l);
        }

        return $this;
    }

    /**
     * @param	ApplicationExtraProperty $applicationExtraProperty The applicationExtraProperty object to add.
     */
    protected function doAddApplicationExtraProperty($applicationExtraProperty)
    {
        $this->collApplicationExtraPropertys[]= $applicationExtraProperty;
        $applicationExtraProperty->setApplication($this);
    }

    /**
     * @param	ApplicationExtraProperty $applicationExtraProperty The applicationExtraProperty object to remove.
     * @return Application The current object (for fluent API support)
     */
    public function removeApplicationExtraProperty($applicationExtraProperty)
    {
        if ($this->getApplicationExtraPropertys()->contains($applicationExtraProperty)) {
            $this->collApplicationExtraPropertys->remove($this->collApplicationExtraPropertys->search($applicationExtraProperty));
            if (null === $this->applicationExtraPropertysScheduledForDeletion) {
                $this->applicationExtraPropertysScheduledForDeletion = clone $this->collApplicationExtraPropertys;
                $this->applicationExtraPropertysScheduledForDeletion->clear();
            }
            $this->applicationExtraPropertysScheduledForDeletion[]= clone $applicationExtraProperty;
            $applicationExtraProperty->setApplication(null);
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
        $this->application_type_id = null;
        $this->router_id = null;
        $this->design_id = null;
        $this->package_id = null;
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
            if ($this->collApplicationUris) {
                foreach ($this->collApplicationUris as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPages) {
                foreach ($this->collPages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collApplicationExtraPropertys) {
                foreach ($this->collApplicationExtraPropertys as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aApplicationType instanceof Persistent) {
              $this->aApplicationType->clearAllReferences($deep);
            }
            if ($this->aPackage instanceof Persistent) {
              $this->aPackage->clearAllReferences($deep);
            }
            if ($this->aRouter instanceof Persistent) {
              $this->aRouter->clearAllReferences($deep);
            }
            if ($this->aDesign instanceof Persistent) {
              $this->aDesign->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collApplicationUris instanceof PropelCollection) {
            $this->collApplicationUris->clearIterator();
        }
        $this->collApplicationUris = null;
        if ($this->collPages instanceof PropelCollection) {
            $this->collPages->clearIterator();
        }
        $this->collPages = null;
        if ($this->collApplicationExtraPropertys instanceof PropelCollection) {
            $this->collApplicationExtraPropertys->clearIterator();
        }
        $this->collApplicationExtraPropertys = null;
        $this->aApplicationType = null;
        $this->aPackage = null;
        $this->aRouter = null;
        $this->aDesign = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ApplicationPeer::DEFAULT_STRING_FORMAT);
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
     * convert propertyname in method to property name
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
      $properties = $this->getApplicationExtraPropertys(null, $con);
      $propertyName = ApplicationPeer::normalizeExtraPropertyName($propertyName);
      foreach($properties as $prop)
      {
        if($prop->getPropertyName() == $propertyName)
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
     * @return Application|false
     */
    protected function setPropertyById($id, $value, PropelPDO $con = null)
    {
      $prop = $this->getPropertyObjectById($id, $con);
      if($prop instanceof ApplicationExtraProperty)
      {
        $prop->setPropertyValue(ApplicationPeer::normalizeExtraPropertyValue($value));
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
      $properties = $this->getApplicationExtraPropertys(null, $con);
      $propertyName = ApplicationPeer::normalizeExtraPropertyName($propertyName);
      foreach($properties as $prop)
      {
        if($prop->getPropertyName() == $propertyName)
        {
          $ret[$prop->getId() ? $prop->getId() : $propertyName.'_'.count($ret)] = $prop;
        }
      }
      return $ret;
    }

    /**
     * Retrieve related property with $id.
     * If property is not saved yet, id is the list index, created this way :
     * $propertyName.'_'.$index.
     *
     * @param Integer|String  $id   the id of prorty to retrieve.
     * @param PropelPDO       $con  Optional connection object
     *
     * @return ApplicationExtraProperty
     */
    protected function getPropertyObjectById($id, PropelPDO $con = null)
    {
      if(is_numeric($id))
      {
        $properties = $this->getApplicationExtraPropertys(null, $con);
        foreach($properties as $prop)
        {
          if($prop->getId() == $id)
          {
            return $prop;
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
      $prop = $this->getPropertyObjectById($id, $con);
      return $prop && $prop->getPropertyName() == ApplicationPeer::normalizeExtraPropertyName($propertyName);
    }

    /**
     * wrapped function on update{Property} callback
     *
     * @param string          $name  the property to update's type
     * @param mixed           $value the new value
     * @param integer|string  $id    the id of the property to update
     * @param PropelPDO       $con   Optional connection object
     *
     * @return Boolean|ApplicationExtraProperty
     */
    protected function setPropertyByNameAndId($name, $value, $id, PropelPDO $con = null)
    {
      if($this->isPropertyWithIdA($id, ApplicationPeer::normalizeExtraPropertyName($name), $con))
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
      $prop = $this->getPropertyObjectById($id, $con);
      if($prop instanceof ApplicationExtraProperty)
      {
        return $prop->getPropertyValue();
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
      if($this->isPropertyWithIdA($id, ApplicationPeer::normalizeExtraPropertyName($name), $con))
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
      $prop = $this->getPropertyObjectById($id, $con);
      if($prop instanceof ApplicationExtraProperty)
      {
        if(!$prop->isNew())
        {
          $prop->delete($con);
        }
        $this->collApplicationExtraPropertys->remove($this->collApplicationExtraPropertys->search($prop));
        return $prop;
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
    public function deletePropertiesByName($name, PropelPDO $con = null)
    {
      $props = $this->getPropertiesObjectsByName($name, $con);
      foreach($props as $prop)
      {
        if($prop instanceof ApplicationExtraProperty)
        {
          $prop->delete($con);
          $this->collApplicationExtraPropertys->remove($this->collApplicationExtraPropertys->search($prop));
        }
      }
      return $props;
    }
/**
 * Initializes internal state of Application object.
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
     * Returns the list of registered extra properties
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
     * @return Application
     */
    public function registerProperty($propertyName, $defaultValue = null)
    {
      $propertyName = ApplicationPeer::normalizeExtraPropertyName($propertyName);
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
     * If the property already exists, then it is ovverriden, ortherwise
     * new property is created.
     *
     * @param String    $name   the property name.
     * @param Mixed     $value  default property value.
     * @param PropelPDO $con    Optional connection object
     *
     * @return Application
     */
    public function setProperty($name, $value, PropelPDO $con = null)
    {
      $name = ApplicationPeer::normalizeExtraPropertyName($name);
      if($this->hasProperty($name, $con))
      {
        $properties = $this->getApplicationExtraPropertys(null, $con);
        foreach($properties as $prop)
        {
          if($prop->getPropertyName() == $name)
          {
            $prop->setPropertyValue(ApplicationPeer::normalizeExtraPropertyValue($value));
            return $this;
          }
        }
      }
      else
      {
        $property = new ApplicationExtraProperty();
        $property->setPropertyName($name);
        $property->setPropertyValue(ApplicationPeer::normalizeExtraPropertyValue($value));
        $this->addApplicationExtraProperty($property);
      }
      return $this;
    }

    /**
     * Get the value of an extra property that can appear only once.
     *
     * @param   String    $propertyName   the name of propertyto retrieve.
     * @param   Mixed     $defaultValue   default value if property isn't set.
     * @param   PropelPDO $con            Optional connection object
     *
     * @return  Mixed
     */
    public function getProperty($propertyName, $defaultValue = null, PropelPDO $con = null)
    {
      $properties = $this->getApplicationExtraPropertys(null, $con);
      $propertyName = ApplicationPeer::normalizeExtraPropertyName($propertyName);
      foreach($properties as $prop)
      {
        if($prop->getPropertyName() == $propertyName)
        {
          return $prop->getPropertyValue();
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
     * @return Application
     */
    public function registerMultipleProperty($propertyName, $defaultValue = null)
    {
      $propertyName = ApplicationPeer::normalizeExtraPropertyName($propertyName);
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
      $property = new ApplicationExtraProperty();
      $property->setPropertyName(ApplicationPeer::normalizeExtraPropertyName($propertyName));
      $property->setPropertyValue(ApplicationPeer::normalizeExtraPropertyValue($value));
      $this->addApplicationExtraProperty($property);
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
      foreach($properties as $key => $prop)
      {
        $ret[$key] = $prop->getPropertyValue();
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
     * @return array
     */
    public function getExtraProperties($con = null)
    {
      $ret = array();

      // init with default single and multiple properties
      $ret = array_merge($ret, $this->extraProperties);
      foreach ($this->multipleExtraProperties as $propertyName => $default) {
        $ret[$propertyName] = array();
      }

      foreach ($this->getApplicationExtraPropertys(null, $con) as $property) {
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

        if (is_callable(array('keeko\core\entities\Package', $name))) {
            if (!$delegate = $this->getPackage()) {
                $delegate = new Package();
                $this->setPackage($delegate);
            }

            return call_user_func_array(array($delegate, $name), $params);
        }
        // extra_properties behavior
        // calls the registered properties dedicated functions
        if(in_array($methodName = substr($name, 0,3), array('add', 'set', 'has', 'get')))
        {
          $propertyName = ApplicationPeer::normalizeExtraPropertyName($this->extraPropertyNameFromMethod(substr($name, 3)));
        }
        else if(in_array($methodName = substr($name, 0,5), array('count', 'clear')))
        {
          $propertyName = ApplicationPeer::normalizeExtraPropertyName($this->extraPropertyNameFromMethod(substr($name, 5)));
        }
        else if(in_array($methodName = substr($name, 0,6), array('delete', 'update')))
        {
          $propertyName = ApplicationPeer::normalizeExtraPropertyName($this->extraPropertyNameFromMethod(substr($name, 6)));
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
