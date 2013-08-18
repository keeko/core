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
use keeko\core\entities\Application;
use keeko\core\entities\ApplicationQuery;
use keeko\core\entities\Layout;
use keeko\core\entities\LayoutQuery;
use keeko\core\entities\Page;
use keeko\core\entities\PagePeer;
use keeko\core\entities\PageQuery;
use keeko\core\entities\Route;
use keeko\core\entities\RouteQuery;

/**
 * Base class that represents a row from the 'keeko_page' table.
 *
 *
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BasePage extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'keeko\\core\\entities\\PagePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PagePeer
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
     * The value for the parent_id field.
     * @var        int
     */
    protected $parent_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the keywords field.
     * @var        string
     */
    protected $keywords;

    /**
     * The value for the layout_id field.
     * @var        int
     */
    protected $layout_id;

    /**
     * The value for the application_id field.
     * @var        int
     */
    protected $application_id;

    /**
     * @var        Page
     */
    protected $aPageRelatedByParentId;

    /**
     * @var        Layout
     */
    protected $aLayout;

    /**
     * @var        Application
     */
    protected $aApplication;

    /**
     * @var        PropelObjectCollection|Page[] Collection to store aggregation of Page objects.
     */
    protected $collPagesRelatedById;
    protected $collPagesRelatedByIdPartial;

    /**
     * @var        PropelObjectCollection|Route[] Collection to store aggregation of Route objects.
     */
    protected $collRoutes;
    protected $collRoutesPartial;

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
    protected $pagesRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $routesScheduledForDeletion = null;

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
     * Get the [parent_id] column value.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
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
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
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
     * Get the [keywords] column value.
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Get the [layout_id] column value.
     *
     * @return int
     */
    public function getLayoutId()
    {
        return $this->layout_id;
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
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Page The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PagePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [parent_id] column.
     *
     * @param int $v new value
     * @return Page The current object (for fluent API support)
     */
    public function setParentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->parent_id !== $v) {
            $this->parent_id = $v;
            $this->modifiedColumns[] = PagePeer::PARENT_ID;
        }

        if ($this->aPageRelatedByParentId !== null && $this->aPageRelatedByParentId->getId() !== $v) {
            $this->aPageRelatedByParentId = null;
        }


        return $this;
    } // setParentId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Page The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PagePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [slug] column.
     *
     * @param string $v new value
     * @return Page The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PagePeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return Page The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PagePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [keywords] column.
     *
     * @param string $v new value
     * @return Page The current object (for fluent API support)
     */
    public function setKeywords($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->keywords !== $v) {
            $this->keywords = $v;
            $this->modifiedColumns[] = PagePeer::KEYWORDS;
        }


        return $this;
    } // setKeywords()

    /**
     * Set the value of [layout_id] column.
     *
     * @param int $v new value
     * @return Page The current object (for fluent API support)
     */
    public function setLayoutId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->layout_id !== $v) {
            $this->layout_id = $v;
            $this->modifiedColumns[] = PagePeer::LAYOUT_ID;
        }

        if ($this->aLayout !== null && $this->aLayout->getId() !== $v) {
            $this->aLayout = null;
        }


        return $this;
    } // setLayoutId()

    /**
     * Set the value of [application_id] column.
     *
     * @param int $v new value
     * @return Page The current object (for fluent API support)
     */
    public function setApplicationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->application_id !== $v) {
            $this->application_id = $v;
            $this->modifiedColumns[] = PagePeer::APPLICATION_ID;
        }

        if ($this->aApplication !== null && $this->aApplication->getId() !== $v) {
            $this->aApplication = null;
        }


        return $this;
    } // setApplicationId()

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
            $this->parent_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->slug = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->keywords = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->layout_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->application_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 8; // 8 = PagePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Page object", $e);
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

        if ($this->aPageRelatedByParentId !== null && $this->parent_id !== $this->aPageRelatedByParentId->getId()) {
            $this->aPageRelatedByParentId = null;
        }
        if ($this->aLayout !== null && $this->layout_id !== $this->aLayout->getId()) {
            $this->aLayout = null;
        }
        if ($this->aApplication !== null && $this->application_id !== $this->aApplication->getId()) {
            $this->aApplication = null;
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
            $con = Propel::getConnection(PagePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PagePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPageRelatedByParentId = null;
            $this->aLayout = null;
            $this->aApplication = null;
            $this->collPagesRelatedById = null;

            $this->collRoutes = null;

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
            $con = Propel::getConnection(PagePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PageQuery::create()
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
            $con = Propel::getConnection(PagePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                PagePeer::addInstanceToPool($this);
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

            if ($this->aPageRelatedByParentId !== null) {
                if ($this->aPageRelatedByParentId->isModified() || $this->aPageRelatedByParentId->isNew()) {
                    $affectedRows += $this->aPageRelatedByParentId->save($con);
                }
                $this->setPageRelatedByParentId($this->aPageRelatedByParentId);
            }

            if ($this->aLayout !== null) {
                if ($this->aLayout->isModified() || $this->aLayout->isNew()) {
                    $affectedRows += $this->aLayout->save($con);
                }
                $this->setLayout($this->aLayout);
            }

            if ($this->aApplication !== null) {
                if ($this->aApplication->isModified() || $this->aApplication->isNew()) {
                    $affectedRows += $this->aApplication->save($con);
                }
                $this->setApplication($this->aApplication);
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

            if ($this->pagesRelatedByIdScheduledForDeletion !== null) {
                if (!$this->pagesRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->pagesRelatedByIdScheduledForDeletion as $pageRelatedById) {
                        // need to save related object because we set the relation to null
                        $pageRelatedById->save($con);
                    }
                    $this->pagesRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collPagesRelatedById !== null) {
                foreach ($this->collPagesRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->routesScheduledForDeletion !== null) {
                if (!$this->routesScheduledForDeletion->isEmpty()) {
                    foreach ($this->routesScheduledForDeletion as $route) {
                        // need to save related object because we set the relation to null
                        $route->save($con);
                    }
                    $this->routesScheduledForDeletion = null;
                }
            }

            if ($this->collRoutes !== null) {
                foreach ($this->collRoutes as $referrerFK) {
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

        $this->modifiedColumns[] = PagePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PagePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PagePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PagePeer::PARENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`parent_id`';
        }
        if ($this->isColumnModified(PagePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PagePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(PagePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PagePeer::KEYWORDS)) {
            $modifiedColumns[':p' . $index++]  = '`keywords`';
        }
        if ($this->isColumnModified(PagePeer::LAYOUT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`layout_id`';
        }
        if ($this->isColumnModified(PagePeer::APPLICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`application_id`';
        }

        $sql = sprintf(
            'INSERT INTO `keeko_page` (%s) VALUES (%s)',
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
                    case '`parent_id`':
                        $stmt->bindValue($identifier, $this->parent_id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`slug`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`keywords`':
                        $stmt->bindValue($identifier, $this->keywords, PDO::PARAM_STR);
                        break;
                    case '`layout_id`':
                        $stmt->bindValue($identifier, $this->layout_id, PDO::PARAM_INT);
                        break;
                    case '`application_id`':
                        $stmt->bindValue($identifier, $this->application_id, PDO::PARAM_INT);
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

            if ($this->aPageRelatedByParentId !== null) {
                if (!$this->aPageRelatedByParentId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPageRelatedByParentId->getValidationFailures());
                }
            }

            if ($this->aLayout !== null) {
                if (!$this->aLayout->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLayout->getValidationFailures());
                }
            }

            if ($this->aApplication !== null) {
                if (!$this->aApplication->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aApplication->getValidationFailures());
                }
            }


            if (($retval = PagePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPagesRelatedById !== null) {
                    foreach ($this->collPagesRelatedById as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collRoutes !== null) {
                    foreach ($this->collRoutes as $referrerFK) {
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
        $pos = PagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getParentId();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getSlug();
                break;
            case 4:
                return $this->getDescription();
                break;
            case 5:
                return $this->getKeywords();
                break;
            case 6:
                return $this->getLayoutId();
                break;
            case 7:
                return $this->getApplicationId();
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
        if (isset($alreadyDumpedObjects['Page'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Page'][$this->getPrimaryKey()] = true;
        $keys = PagePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getParentId(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getSlug(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getKeywords(),
            $keys[6] => $this->getLayoutId(),
            $keys[7] => $this->getApplicationId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aPageRelatedByParentId) {
                $result['PageRelatedByParentId'] = $this->aPageRelatedByParentId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLayout) {
                $result['Layout'] = $this->aLayout->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aApplication) {
                $result['Application'] = $this->aApplication->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPagesRelatedById) {
                $result['PagesRelatedById'] = $this->collPagesRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRoutes) {
                $result['Routes'] = $this->collRoutes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setParentId($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setSlug($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setKeywords($value);
                break;
            case 6:
                $this->setLayoutId($value);
                break;
            case 7:
                $this->setApplicationId($value);
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
        $keys = PagePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setParentId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSlug($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDescription($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setKeywords($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setLayoutId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setApplicationId($arr[$keys[7]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PagePeer::DATABASE_NAME);

        if ($this->isColumnModified(PagePeer::ID)) $criteria->add(PagePeer::ID, $this->id);
        if ($this->isColumnModified(PagePeer::PARENT_ID)) $criteria->add(PagePeer::PARENT_ID, $this->parent_id);
        if ($this->isColumnModified(PagePeer::TITLE)) $criteria->add(PagePeer::TITLE, $this->title);
        if ($this->isColumnModified(PagePeer::SLUG)) $criteria->add(PagePeer::SLUG, $this->slug);
        if ($this->isColumnModified(PagePeer::DESCRIPTION)) $criteria->add(PagePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PagePeer::KEYWORDS)) $criteria->add(PagePeer::KEYWORDS, $this->keywords);
        if ($this->isColumnModified(PagePeer::LAYOUT_ID)) $criteria->add(PagePeer::LAYOUT_ID, $this->layout_id);
        if ($this->isColumnModified(PagePeer::APPLICATION_ID)) $criteria->add(PagePeer::APPLICATION_ID, $this->application_id);

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
        $criteria = new Criteria(PagePeer::DATABASE_NAME);
        $criteria->add(PagePeer::ID, $this->id);

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
     * @param object $copyObj An object of Page (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setParentId($this->getParentId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setKeywords($this->getKeywords());
        $copyObj->setLayoutId($this->getLayoutId());
        $copyObj->setApplicationId($this->getApplicationId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPagesRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPageRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRoutes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRoute($relObj->copy($deepCopy));
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
     * @return Page Clone of current object.
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
     * @return PagePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PagePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Page object.
     *
     * @param             Page $v
     * @return Page The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPageRelatedByParentId(Page $v = null)
    {
        if ($v === null) {
            $this->setParentId(NULL);
        } else {
            $this->setParentId($v->getId());
        }

        $this->aPageRelatedByParentId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Page object, it will not be re-added.
        if ($v !== null) {
            $v->addPageRelatedById($this);
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
    public function getPageRelatedByParentId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPageRelatedByParentId === null && ($this->parent_id !== null) && $doQuery) {
            $this->aPageRelatedByParentId = PageQuery::create()->findPk($this->parent_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPageRelatedByParentId->addPagesRelatedById($this);
             */
        }

        return $this->aPageRelatedByParentId;
    }

    /**
     * Declares an association between this object and a Layout object.
     *
     * @param             Layout $v
     * @return Page The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLayout(Layout $v = null)
    {
        if ($v === null) {
            $this->setLayoutId(NULL);
        } else {
            $this->setLayoutId($v->getId());
        }

        $this->aLayout = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Layout object, it will not be re-added.
        if ($v !== null) {
            $v->addPage($this);
        }


        return $this;
    }


    /**
     * Get the associated Layout object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Layout The associated Layout object.
     * @throws PropelException
     */
    public function getLayout(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aLayout === null && ($this->layout_id !== null) && $doQuery) {
            $this->aLayout = LayoutQuery::create()->findPk($this->layout_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLayout->addPages($this);
             */
        }

        return $this->aLayout;
    }

    /**
     * Declares an association between this object and a Application object.
     *
     * @param             Application $v
     * @return Page The current object (for fluent API support)
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
            $v->addPage($this);
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
                $this->aApplication->addPages($this);
             */
        }

        return $this->aApplication;
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
        if ('PageRelatedById' == $relationName) {
            $this->initPagesRelatedById();
        }
        if ('Route' == $relationName) {
            $this->initRoutes();
        }
    }

    /**
     * Clears out the collPagesRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Page The current object (for fluent API support)
     * @see        addPagesRelatedById()
     */
    public function clearPagesRelatedById()
    {
        $this->collPagesRelatedById = null; // important to set this to null since that means it is uninitialized
        $this->collPagesRelatedByIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPagesRelatedById collection loaded partially
     *
     * @return void
     */
    public function resetPartialPagesRelatedById($v = true)
    {
        $this->collPagesRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collPagesRelatedById collection.
     *
     * By default this just sets the collPagesRelatedById collection to an empty array (like clearcollPagesRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPagesRelatedById($overrideExisting = true)
    {
        if (null !== $this->collPagesRelatedById && !$overrideExisting) {
            return;
        }
        $this->collPagesRelatedById = new PropelObjectCollection();
        $this->collPagesRelatedById->setModel('Page');
    }

    /**
     * Gets an array of Page objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Page is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Page[] List of Page objects
     * @throws PropelException
     */
    public function getPagesRelatedById($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPagesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collPagesRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPagesRelatedById) {
                // return empty collection
                $this->initPagesRelatedById();
            } else {
                $collPagesRelatedById = PageQuery::create(null, $criteria)
                    ->filterByPageRelatedByParentId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPagesRelatedByIdPartial && count($collPagesRelatedById)) {
                      $this->initPagesRelatedById(false);

                      foreach($collPagesRelatedById as $obj) {
                        if (false == $this->collPagesRelatedById->contains($obj)) {
                          $this->collPagesRelatedById->append($obj);
                        }
                      }

                      $this->collPagesRelatedByIdPartial = true;
                    }

                    $collPagesRelatedById->getInternalIterator()->rewind();
                    return $collPagesRelatedById;
                }

                if($partial && $this->collPagesRelatedById) {
                    foreach($this->collPagesRelatedById as $obj) {
                        if($obj->isNew()) {
                            $collPagesRelatedById[] = $obj;
                        }
                    }
                }

                $this->collPagesRelatedById = $collPagesRelatedById;
                $this->collPagesRelatedByIdPartial = false;
            }
        }

        return $this->collPagesRelatedById;
    }

    /**
     * Sets a collection of PageRelatedById objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pagesRelatedById A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Page The current object (for fluent API support)
     */
    public function setPagesRelatedById(PropelCollection $pagesRelatedById, PropelPDO $con = null)
    {
        $pagesRelatedByIdToDelete = $this->getPagesRelatedById(new Criteria(), $con)->diff($pagesRelatedById);

        $this->pagesRelatedByIdScheduledForDeletion = unserialize(serialize($pagesRelatedByIdToDelete));

        foreach ($pagesRelatedByIdToDelete as $pageRelatedByIdRemoved) {
            $pageRelatedByIdRemoved->setPageRelatedByParentId(null);
        }

        $this->collPagesRelatedById = null;
        foreach ($pagesRelatedById as $pageRelatedById) {
            $this->addPageRelatedById($pageRelatedById);
        }

        $this->collPagesRelatedById = $pagesRelatedById;
        $this->collPagesRelatedByIdPartial = false;

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
    public function countPagesRelatedById(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPagesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collPagesRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPagesRelatedById) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPagesRelatedById());
            }
            $query = PageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPageRelatedByParentId($this)
                ->count($con);
        }

        return count($this->collPagesRelatedById);
    }

    /**
     * Method called to associate a Page object to this object
     * through the Page foreign key attribute.
     *
     * @param    Page $l Page
     * @return Page The current object (for fluent API support)
     */
    public function addPageRelatedById(Page $l)
    {
        if ($this->collPagesRelatedById === null) {
            $this->initPagesRelatedById();
            $this->collPagesRelatedByIdPartial = true;
        }
        if (!in_array($l, $this->collPagesRelatedById->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPageRelatedById($l);
        }

        return $this;
    }

    /**
     * @param	PageRelatedById $pageRelatedById The pageRelatedById object to add.
     */
    protected function doAddPageRelatedById($pageRelatedById)
    {
        $this->collPagesRelatedById[]= $pageRelatedById;
        $pageRelatedById->setPageRelatedByParentId($this);
    }

    /**
     * @param	PageRelatedById $pageRelatedById The pageRelatedById object to remove.
     * @return Page The current object (for fluent API support)
     */
    public function removePageRelatedById($pageRelatedById)
    {
        if ($this->getPagesRelatedById()->contains($pageRelatedById)) {
            $this->collPagesRelatedById->remove($this->collPagesRelatedById->search($pageRelatedById));
            if (null === $this->pagesRelatedByIdScheduledForDeletion) {
                $this->pagesRelatedByIdScheduledForDeletion = clone $this->collPagesRelatedById;
                $this->pagesRelatedByIdScheduledForDeletion->clear();
            }
            $this->pagesRelatedByIdScheduledForDeletion[]= $pageRelatedById;
            $pageRelatedById->setPageRelatedByParentId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Page is new, it will return
     * an empty collection; or if this Page has previously
     * been saved, it will retrieve related PagesRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Page.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Page[] List of Page objects
     */
    public function getPagesRelatedByIdJoinLayout($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PageQuery::create(null, $criteria);
        $query->joinWith('Layout', $join_behavior);

        return $this->getPagesRelatedById($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Page is new, it will return
     * an empty collection; or if this Page has previously
     * been saved, it will retrieve related PagesRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Page.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Page[] List of Page objects
     */
    public function getPagesRelatedByIdJoinApplication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PageQuery::create(null, $criteria);
        $query->joinWith('Application', $join_behavior);

        return $this->getPagesRelatedById($query, $con);
    }

    /**
     * Clears out the collRoutes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Page The current object (for fluent API support)
     * @see        addRoutes()
     */
    public function clearRoutes()
    {
        $this->collRoutes = null; // important to set this to null since that means it is uninitialized
        $this->collRoutesPartial = null;

        return $this;
    }

    /**
     * reset is the collRoutes collection loaded partially
     *
     * @return void
     */
    public function resetPartialRoutes($v = true)
    {
        $this->collRoutesPartial = $v;
    }

    /**
     * Initializes the collRoutes collection.
     *
     * By default this just sets the collRoutes collection to an empty array (like clearcollRoutes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRoutes($overrideExisting = true)
    {
        if (null !== $this->collRoutes && !$overrideExisting) {
            return;
        }
        $this->collRoutes = new PropelObjectCollection();
        $this->collRoutes->setModel('Route');
    }

    /**
     * Gets an array of Route objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Page is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Route[] List of Route objects
     * @throws PropelException
     */
    public function getRoutes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collRoutesPartial && !$this->isNew();
        if (null === $this->collRoutes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRoutes) {
                // return empty collection
                $this->initRoutes();
            } else {
                $collRoutes = RouteQuery::create(null, $criteria)
                    ->filterByPage($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collRoutesPartial && count($collRoutes)) {
                      $this->initRoutes(false);

                      foreach($collRoutes as $obj) {
                        if (false == $this->collRoutes->contains($obj)) {
                          $this->collRoutes->append($obj);
                        }
                      }

                      $this->collRoutesPartial = true;
                    }

                    $collRoutes->getInternalIterator()->rewind();
                    return $collRoutes;
                }

                if($partial && $this->collRoutes) {
                    foreach($this->collRoutes as $obj) {
                        if($obj->isNew()) {
                            $collRoutes[] = $obj;
                        }
                    }
                }

                $this->collRoutes = $collRoutes;
                $this->collRoutesPartial = false;
            }
        }

        return $this->collRoutes;
    }

    /**
     * Sets a collection of Route objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $routes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Page The current object (for fluent API support)
     */
    public function setRoutes(PropelCollection $routes, PropelPDO $con = null)
    {
        $routesToDelete = $this->getRoutes(new Criteria(), $con)->diff($routes);

        $this->routesScheduledForDeletion = unserialize(serialize($routesToDelete));

        foreach ($routesToDelete as $routeRemoved) {
            $routeRemoved->setPage(null);
        }

        $this->collRoutes = null;
        foreach ($routes as $route) {
            $this->addRoute($route);
        }

        $this->collRoutes = $routes;
        $this->collRoutesPartial = false;

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
    public function countRoutes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collRoutesPartial && !$this->isNew();
        if (null === $this->collRoutes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRoutes) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getRoutes());
            }
            $query = RouteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPage($this)
                ->count($con);
        }

        return count($this->collRoutes);
    }

    /**
     * Method called to associate a Route object to this object
     * through the Route foreign key attribute.
     *
     * @param    Route $l Route
     * @return Page The current object (for fluent API support)
     */
    public function addRoute(Route $l)
    {
        if ($this->collRoutes === null) {
            $this->initRoutes();
            $this->collRoutesPartial = true;
        }
        if (!in_array($l, $this->collRoutes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddRoute($l);
        }

        return $this;
    }

    /**
     * @param	Route $route The route object to add.
     */
    protected function doAddRoute($route)
    {
        $this->collRoutes[]= $route;
        $route->setPage($this);
    }

    /**
     * @param	Route $route The route object to remove.
     * @return Page The current object (for fluent API support)
     */
    public function removeRoute($route)
    {
        if ($this->getRoutes()->contains($route)) {
            $this->collRoutes->remove($this->collRoutes->search($route));
            if (null === $this->routesScheduledForDeletion) {
                $this->routesScheduledForDeletion = clone $this->collRoutes;
                $this->routesScheduledForDeletion->clear();
            }
            $this->routesScheduledForDeletion[]= $route;
            $route->setPage(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Page is new, it will return
     * an empty collection; or if this Page has previously
     * been saved, it will retrieve related Routes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Page.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Route[] List of Route objects
     */
    public function getRoutesJoinRouteRelatedByRedirectId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = RouteQuery::create(null, $criteria);
        $query->joinWith('RouteRelatedByRedirectId', $join_behavior);

        return $this->getRoutes($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->parent_id = null;
        $this->title = null;
        $this->slug = null;
        $this->description = null;
        $this->keywords = null;
        $this->layout_id = null;
        $this->application_id = null;
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
            if ($this->collPagesRelatedById) {
                foreach ($this->collPagesRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRoutes) {
                foreach ($this->collRoutes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPageRelatedByParentId instanceof Persistent) {
              $this->aPageRelatedByParentId->clearAllReferences($deep);
            }
            if ($this->aLayout instanceof Persistent) {
              $this->aLayout->clearAllReferences($deep);
            }
            if ($this->aApplication instanceof Persistent) {
              $this->aApplication->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPagesRelatedById instanceof PropelCollection) {
            $this->collPagesRelatedById->clearIterator();
        }
        $this->collPagesRelatedById = null;
        if ($this->collRoutes instanceof PropelCollection) {
            $this->collRoutes->clearIterator();
        }
        $this->collRoutes = null;
        $this->aPageRelatedByParentId = null;
        $this->aLayout = null;
        $this->aApplication = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PagePeer::DEFAULT_STRING_FORMAT);
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
