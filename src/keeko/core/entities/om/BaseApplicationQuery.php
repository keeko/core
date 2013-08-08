<?php

namespace keeko\core\entities\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use keeko\core\entities\Application;
use keeko\core\entities\ApplicationExtraProperty;
use keeko\core\entities\ApplicationPeer;
use keeko\core\entities\ApplicationQuery;
use keeko\core\entities\ApplicationType;
use keeko\core\entities\ApplicationUri;
use keeko\core\entities\Router;

/**
 * Base class that represents a query for the 'keeko_application' table.
 *
 *
 *
 * @method ApplicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ApplicationQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method ApplicationQuery orderByApplicationTypeId($order = Criteria::ASC) Order by the application_type_id column
 * @method ApplicationQuery orderByRouterId($order = Criteria::ASC) Order by the router_id column
 *
 * @method ApplicationQuery groupById() Group by the id column
 * @method ApplicationQuery groupByTitle() Group by the title column
 * @method ApplicationQuery groupByApplicationTypeId() Group by the application_type_id column
 * @method ApplicationQuery groupByRouterId() Group by the router_id column
 *
 * @method ApplicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ApplicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ApplicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ApplicationQuery leftJoinApplicationType($relationAlias = null) Adds a LEFT JOIN clause to the query using the ApplicationType relation
 * @method ApplicationQuery rightJoinApplicationType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ApplicationType relation
 * @method ApplicationQuery innerJoinApplicationType($relationAlias = null) Adds a INNER JOIN clause to the query using the ApplicationType relation
 *
 * @method ApplicationQuery leftJoinRouter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Router relation
 * @method ApplicationQuery rightJoinRouter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Router relation
 * @method ApplicationQuery innerJoinRouter($relationAlias = null) Adds a INNER JOIN clause to the query using the Router relation
 *
 * @method ApplicationQuery leftJoinApplicationUri($relationAlias = null) Adds a LEFT JOIN clause to the query using the ApplicationUri relation
 * @method ApplicationQuery rightJoinApplicationUri($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ApplicationUri relation
 * @method ApplicationQuery innerJoinApplicationUri($relationAlias = null) Adds a INNER JOIN clause to the query using the ApplicationUri relation
 *
 * @method ApplicationQuery leftJoinApplicationExtraProperty($relationAlias = null) Adds a LEFT JOIN clause to the query using the ApplicationExtraProperty relation
 * @method ApplicationQuery rightJoinApplicationExtraProperty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ApplicationExtraProperty relation
 * @method ApplicationQuery innerJoinApplicationExtraProperty($relationAlias = null) Adds a INNER JOIN clause to the query using the ApplicationExtraProperty relation
 *
 * @method Application findOne(PropelPDO $con = null) Return the first Application matching the query
 * @method Application findOneOrCreate(PropelPDO $con = null) Return the first Application matching the query, or a new Application object populated from the query conditions when no match is found
 *
 * @method Application findOneByTitle(string $title) Return the first Application filtered by the title column
 * @method Application findOneByApplicationTypeId(int $application_type_id) Return the first Application filtered by the application_type_id column
 * @method Application findOneByRouterId(int $router_id) Return the first Application filtered by the router_id column
 *
 * @method array findById(int $id) Return Application objects filtered by the id column
 * @method array findByTitle(string $title) Return Application objects filtered by the title column
 * @method array findByApplicationTypeId(int $application_type_id) Return Application objects filtered by the application_type_id column
 * @method array findByRouterId(int $router_id) Return Application objects filtered by the router_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseApplicationQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseApplicationQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\Application', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ApplicationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ApplicationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ApplicationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ApplicationQuery) {
            return $criteria;
        }
        $query = new ApplicationQuery();
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
     * @return   Application|Application[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ApplicationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ApplicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Application A model object, or null if the key is not found
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
     * @return                 Application A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title`, `application_type_id`, `router_id` FROM `keeko_application` WHERE `id` = :p0';
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
            $obj = new Application();
            $obj->hydrate($row);
            ApplicationPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Application|Application[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Application[]|mixed the list of results, formatted by the current formatter
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
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ApplicationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ApplicationPeer::ID, $keys, Criteria::IN);
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
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ApplicationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ApplicationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::ID, $id, $comparison);
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
     * @return ApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the application_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByApplicationTypeId(1234); // WHERE application_type_id = 1234
     * $query->filterByApplicationTypeId(array(12, 34)); // WHERE application_type_id IN (12, 34)
     * $query->filterByApplicationTypeId(array('min' => 12)); // WHERE application_type_id >= 12
     * $query->filterByApplicationTypeId(array('max' => 12)); // WHERE application_type_id <= 12
     * </code>
     *
     * @see       filterByApplicationType()
     *
     * @param     mixed $applicationTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByApplicationTypeId($applicationTypeId = null, $comparison = null)
    {
        if (is_array($applicationTypeId)) {
            $useMinMax = false;
            if (isset($applicationTypeId['min'])) {
                $this->addUsingAlias(ApplicationPeer::APPLICATION_TYPE_ID, $applicationTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($applicationTypeId['max'])) {
                $this->addUsingAlias(ApplicationPeer::APPLICATION_TYPE_ID, $applicationTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::APPLICATION_TYPE_ID, $applicationTypeId, $comparison);
    }

    /**
     * Filter the query on the router_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRouterId(1234); // WHERE router_id = 1234
     * $query->filterByRouterId(array(12, 34)); // WHERE router_id IN (12, 34)
     * $query->filterByRouterId(array('min' => 12)); // WHERE router_id >= 12
     * $query->filterByRouterId(array('max' => 12)); // WHERE router_id <= 12
     * </code>
     *
     * @see       filterByRouter()
     *
     * @param     mixed $routerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function filterByRouterId($routerId = null, $comparison = null)
    {
        if (is_array($routerId)) {
            $useMinMax = false;
            if (isset($routerId['min'])) {
                $this->addUsingAlias(ApplicationPeer::ROUTER_ID, $routerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($routerId['max'])) {
                $this->addUsingAlias(ApplicationPeer::ROUTER_ID, $routerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationPeer::ROUTER_ID, $routerId, $comparison);
    }

    /**
     * Filter the query by a related ApplicationType object
     *
     * @param   ApplicationType|PropelObjectCollection $applicationType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplicationType($applicationType, $comparison = null)
    {
        if ($applicationType instanceof ApplicationType) {
            return $this
                ->addUsingAlias(ApplicationPeer::APPLICATION_TYPE_ID, $applicationType->getId(), $comparison);
        } elseif ($applicationType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::APPLICATION_TYPE_ID, $applicationType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByApplicationType() only accepts arguments of type ApplicationType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ApplicationType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinApplicationType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ApplicationType');

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
            $this->addJoinObject($join, 'ApplicationType');
        }

        return $this;
    }

    /**
     * Use the ApplicationType relation ApplicationType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\ApplicationTypeQuery A secondary query class using the current class as primary query
     */
    public function useApplicationTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinApplicationType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ApplicationType', '\keeko\core\entities\ApplicationTypeQuery');
    }

    /**
     * Filter the query by a related Router object
     *
     * @param   Router|PropelObjectCollection $router The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRouter($router, $comparison = null)
    {
        if ($router instanceof Router) {
            return $this
                ->addUsingAlias(ApplicationPeer::ROUTER_ID, $router->getId(), $comparison);
        } elseif ($router instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationPeer::ROUTER_ID, $router->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRouter() only accepts arguments of type Router or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Router relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinRouter($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Router');

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
            $this->addJoinObject($join, 'Router');
        }

        return $this;
    }

    /**
     * Use the Router relation Router object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\RouterQuery A secondary query class using the current class as primary query
     */
    public function useRouterQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRouter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Router', '\keeko\core\entities\RouterQuery');
    }

    /**
     * Filter the query by a related ApplicationUri object
     *
     * @param   ApplicationUri|PropelObjectCollection $applicationUri  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplicationUri($applicationUri, $comparison = null)
    {
        if ($applicationUri instanceof ApplicationUri) {
            return $this
                ->addUsingAlias(ApplicationPeer::ID, $applicationUri->getApplicationId(), $comparison);
        } elseif ($applicationUri instanceof PropelObjectCollection) {
            return $this
                ->useApplicationUriQuery()
                ->filterByPrimaryKeys($applicationUri->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByApplicationUri() only accepts arguments of type ApplicationUri or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ApplicationUri relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinApplicationUri($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ApplicationUri');

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
            $this->addJoinObject($join, 'ApplicationUri');
        }

        return $this;
    }

    /**
     * Use the ApplicationUri relation ApplicationUri object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\ApplicationUriQuery A secondary query class using the current class as primary query
     */
    public function useApplicationUriQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinApplicationUri($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ApplicationUri', '\keeko\core\entities\ApplicationUriQuery');
    }

    /**
     * Filter the query by a related ApplicationExtraProperty object
     *
     * @param   ApplicationExtraProperty|PropelObjectCollection $applicationExtraProperty  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplicationExtraProperty($applicationExtraProperty, $comparison = null)
    {
        if ($applicationExtraProperty instanceof ApplicationExtraProperty) {
            return $this
                ->addUsingAlias(ApplicationPeer::ID, $applicationExtraProperty->getKeekoApplicationId(), $comparison);
        } elseif ($applicationExtraProperty instanceof PropelObjectCollection) {
            return $this
                ->useApplicationExtraPropertyQuery()
                ->filterByPrimaryKeys($applicationExtraProperty->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByApplicationExtraProperty() only accepts arguments of type ApplicationExtraProperty or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ApplicationExtraProperty relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function joinApplicationExtraProperty($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ApplicationExtraProperty');

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
            $this->addJoinObject($join, 'ApplicationExtraProperty');
        }

        return $this;
    }

    /**
     * Use the ApplicationExtraProperty relation ApplicationExtraProperty object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\ApplicationExtraPropertyQuery A secondary query class using the current class as primary query
     */
    public function useApplicationExtraPropertyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinApplicationExtraProperty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ApplicationExtraProperty', '\keeko\core\entities\ApplicationExtraPropertyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Application $application Object to remove from the list of results
     *
     * @return ApplicationQuery The current query, for fluid interface
     */
    public function prune($application = null)
    {
        if ($application) {
            $this->addUsingAlias(ApplicationPeer::ID, $application->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // extra_properties behavior
    /**
     * Filter based on an extra property
     *
     * If the property is not set for a particular object it will be present in the results
     *
     * @var string $propertyName The name of the property to filter on
     * @var mixed $propertyValue The value of the property to filter on
     *
     * @return ApplicationQuery
     */
    public function filterByExtraProperty($propertyName, $propertyValue)
    {
      $propertyName = ApplicationPeer::normalizeExtraPropertyName($propertyName);
      $propertyValue = ApplicationPeer::normalizeExtraPropertyValue($propertyValue);

      return $this
        ->leftJoinApplicationExtraProperty($joinName = $propertyName . '_' . uniqid())
        ->addJoinCondition($joinName, "{$joinName}.PropertyName = ?", $propertyName)
        ->where("{$joinName}.PropertyValue = ?", $propertyValue);
    }

    /**
     * Filter based on an extra property
     *
     * If the property is not set for a particular object it it will be assumed
     * to have a value of $default
     *
     * @var string $propertyName The name of the property to filter on
     * @var mixed $propertyValue The value of the property to filter on
     * @var mixed $default The value that will be assumed as default if an object
     *                     does not have the property set
     *
     * @return ApplicationQuery
     */
    public function filterByExtraPropertyWithDefault($propertyName, $propertyValue, $default)
    {
      $propertyName = ApplicationPeer::normalizeExtraPropertyName($propertyName);
      $propertyValue = ApplicationPeer::normalizeExtraPropertyValue($propertyValue);
      $default = ApplicationPeer::normalizeExtraPropertyValue($default);

      return $this
        ->leftJoinApplicationExtraProperty($joinName = $propertyName . '_' . uniqid())
        ->addJoinCondition($joinName, "{$joinName}.PropertyName = ?", $propertyName)
        ->where("COALESCE({$joinName}.PropertyValue, '{$default}') = ?", $propertyValue);
    }


}
