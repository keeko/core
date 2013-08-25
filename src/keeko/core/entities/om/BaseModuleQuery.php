<?php

namespace keeko\core\entities\om;

use \Criteria;
use \Exception;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use keeko\core\entities\Action;
use keeko\core\entities\Module;
use keeko\core\entities\ModulePeer;
use keeko\core\entities\ModuleQuery;
use keeko\core\entities\Package;
use keeko\core\entities\PackageQuery;

/**
 * Base class that represents a query for the 'keeko_module' table.
 *
 *
 *
 * @method ModuleQuery orderByClassName($order = Criteria::ASC) Order by the class_name column
 * @method ModuleQuery orderByActivatedVersion($order = Criteria::ASC) Order by the activated_version column
 * @method ModuleQuery orderByDefaultAction($order = Criteria::ASC) Order by the default_action column
 * @method ModuleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ModuleQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method ModuleQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method ModuleQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method ModuleQuery orderByInstalledVersion($order = Criteria::ASC) Order by the installed_version column
 *
 * @method ModuleQuery groupByClassName() Group by the class_name column
 * @method ModuleQuery groupByActivatedVersion() Group by the activated_version column
 * @method ModuleQuery groupByDefaultAction() Group by the default_action column
 * @method ModuleQuery groupById() Group by the id column
 * @method ModuleQuery groupByName() Group by the name column
 * @method ModuleQuery groupByTitle() Group by the title column
 * @method ModuleQuery groupByDescription() Group by the description column
 * @method ModuleQuery groupByInstalledVersion() Group by the installed_version column
 *
 * @method ModuleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ModuleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ModuleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ModuleQuery leftJoinPackage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Package relation
 * @method ModuleQuery rightJoinPackage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Package relation
 * @method ModuleQuery innerJoinPackage($relationAlias = null) Adds a INNER JOIN clause to the query using the Package relation
 *
 * @method ModuleQuery leftJoinAction($relationAlias = null) Adds a LEFT JOIN clause to the query using the Action relation
 * @method ModuleQuery rightJoinAction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Action relation
 * @method ModuleQuery innerJoinAction($relationAlias = null) Adds a INNER JOIN clause to the query using the Action relation
 *
 * @method Module findOne(PropelPDO $con = null) Return the first Module matching the query
 * @method Module findOneOrCreate(PropelPDO $con = null) Return the first Module matching the query, or a new Module object populated from the query conditions when no match is found
 *
 * @method Module findOneByClassName(string $class_name) Return the first Module filtered by the class_name column
 * @method Module findOneByActivatedVersion(string $activated_version) Return the first Module filtered by the activated_version column
 * @method Module findOneByDefaultAction(string $default_action) Return the first Module filtered by the default_action column
 * @method Module findOneByName(string $name) Return the first Module filtered by the name column
 * @method Module findOneByTitle(string $title) Return the first Module filtered by the title column
 * @method Module findOneByDescription(string $description) Return the first Module filtered by the description column
 * @method Module findOneByInstalledVersion(string $installed_version) Return the first Module filtered by the installed_version column
 *
 * @method array findByClassName(string $class_name) Return Module objects filtered by the class_name column
 * @method array findByActivatedVersion(string $activated_version) Return Module objects filtered by the activated_version column
 * @method array findByDefaultAction(string $default_action) Return Module objects filtered by the default_action column
 * @method array findById(int $id) Return Module objects filtered by the id column
 * @method array findByName(string $name) Return Module objects filtered by the name column
 * @method array findByTitle(string $title) Return Module objects filtered by the title column
 * @method array findByDescription(string $description) Return Module objects filtered by the description column
 * @method array findByInstalledVersion(string $installed_version) Return Module objects filtered by the installed_version column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseModuleQuery extends PackageQuery
{
    /**
     * Initializes internal state of BaseModuleQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\Module', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ModuleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ModuleQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ModuleQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ModuleQuery) {
            return $criteria;
        }
        $query = new ModuleQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Module|Module[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ModulePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ModulePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Module A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Module A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `class_name`, `activated_version`, `default_action`, `id`, `name`, `title`, `description`, `installed_version` FROM `keeko_module` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Module();
            $obj->hydrate($row);
            ModulePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Module|Module[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Module[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ModulePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ModulePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the class_name column
     *
     * Example usage:
     * <code>
     * $query->filterByClassName('fooValue');   // WHERE class_name = 'fooValue'
     * $query->filterByClassName('%fooValue%'); // WHERE class_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $className The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByClassName($className = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($className)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $className)) {
                $className = str_replace('*', '%', $className);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModulePeer::CLASS_NAME, $className, $comparison);
    }

    /**
     * Filter the query on the activated_version column
     *
     * Example usage:
     * <code>
     * $query->filterByActivatedVersion('fooValue');   // WHERE activated_version = 'fooValue'
     * $query->filterByActivatedVersion('%fooValue%'); // WHERE activated_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $activatedVersion The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByActivatedVersion($activatedVersion = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($activatedVersion)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $activatedVersion)) {
                $activatedVersion = str_replace('*', '%', $activatedVersion);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModulePeer::ACTIVATED_VERSION, $activatedVersion, $comparison);
    }

    /**
     * Filter the query on the default_action column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultAction('fooValue');   // WHERE default_action = 'fooValue'
     * $query->filterByDefaultAction('%fooValue%'); // WHERE default_action LIKE '%fooValue%'
     * </code>
     *
     * @param     string $defaultAction The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByDefaultAction($defaultAction = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($defaultAction)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $defaultAction)) {
                $defaultAction = str_replace('*', '%', $defaultAction);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModulePeer::DEFAULT_ACTION, $defaultAction, $comparison);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @see       filterByPackage()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ModulePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ModulePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ModulePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModulePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModulePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModulePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the installed_version column
     *
     * Example usage:
     * <code>
     * $query->filterByInstalledVersion('fooValue');   // WHERE installed_version = 'fooValue'
     * $query->filterByInstalledVersion('%fooValue%'); // WHERE installed_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $installedVersion The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function filterByInstalledVersion($installedVersion = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($installedVersion)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $installedVersion)) {
                $installedVersion = str_replace('*', '%', $installedVersion);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModulePeer::INSTALLED_VERSION, $installedVersion, $comparison);
    }

    /**
     * Filter the query by a related Package object
     *
     * @param   Package|PropelObjectCollection $package The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ModuleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPackage($package, $comparison = null)
    {
        if ($package instanceof Package) {
            return $this
                ->addUsingAlias(ModulePeer::ID, $package->getId(), $comparison);
        } elseif ($package instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ModulePeer::ID, $package->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPackage() only accepts arguments of type Package or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Package relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function joinPackage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Package');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Package');
        }

        return $this;
    }

    /**
     * Use the Package relation Package object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\PackageQuery A secondary query class using the current class as primary query
     */
    public function usePackageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPackage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Package', '\keeko\core\entities\PackageQuery');
    }

    /**
     * Filter the query by a related Action object
     *
     * @param   Action|PropelObjectCollection $action  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ModuleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAction($action, $comparison = null)
    {
        if ($action instanceof Action) {
            return $this
                ->addUsingAlias(ModulePeer::ID, $action->getModuleId(), $comparison);
        } elseif ($action instanceof PropelObjectCollection) {
            return $this
                ->useActionQuery()
                ->filterByPrimaryKeys($action->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAction() only accepts arguments of type Action or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Action relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function joinAction($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Action');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Action');
        }

        return $this;
    }

    /**
     * Use the Action relation Action object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\ActionQuery A secondary query class using the current class as primary query
     */
    public function useActionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Action', '\keeko\core\entities\ActionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Module $module Object to remove from the list of results
     *
     * @return ModuleQuery The current query, for fluid interface
     */
    public function prune($module = null)
    {
        if ($module) {
            $this->addUsingAlias(ModulePeer::ID, $module->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
