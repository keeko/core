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
use keeko\core\entities\Gateway;
use keeko\core\entities\GatewayExtraProperty;
use keeko\core\entities\GatewayPeer;
use keeko\core\entities\GatewayQuery;
use keeko\core\entities\GatewayUri;
use keeko\core\entities\Router;

/**
 * Base class that represents a query for the 'keeko_gateway' table.
 *
 *
 *
 * @method GatewayQuery orderById($order = Criteria::ASC) Order by the id column
 * @method GatewayQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method GatewayQuery orderByApplicationId($order = Criteria::ASC) Order by the application_id column
 * @method GatewayQuery orderByRouterId($order = Criteria::ASC) Order by the router_id column
 *
 * @method GatewayQuery groupById() Group by the id column
 * @method GatewayQuery groupByTitle() Group by the title column
 * @method GatewayQuery groupByApplicationId() Group by the application_id column
 * @method GatewayQuery groupByRouterId() Group by the router_id column
 *
 * @method GatewayQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method GatewayQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method GatewayQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method GatewayQuery leftJoinApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Application relation
 * @method GatewayQuery rightJoinApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Application relation
 * @method GatewayQuery innerJoinApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the Application relation
 *
 * @method GatewayQuery leftJoinRouter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Router relation
 * @method GatewayQuery rightJoinRouter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Router relation
 * @method GatewayQuery innerJoinRouter($relationAlias = null) Adds a INNER JOIN clause to the query using the Router relation
 *
 * @method GatewayQuery leftJoinGatewayUri($relationAlias = null) Adds a LEFT JOIN clause to the query using the GatewayUri relation
 * @method GatewayQuery rightJoinGatewayUri($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GatewayUri relation
 * @method GatewayQuery innerJoinGatewayUri($relationAlias = null) Adds a INNER JOIN clause to the query using the GatewayUri relation
 *
 * @method GatewayQuery leftJoinGatewayExtraProperty($relationAlias = null) Adds a LEFT JOIN clause to the query using the GatewayExtraProperty relation
 * @method GatewayQuery rightJoinGatewayExtraProperty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GatewayExtraProperty relation
 * @method GatewayQuery innerJoinGatewayExtraProperty($relationAlias = null) Adds a INNER JOIN clause to the query using the GatewayExtraProperty relation
 *
 * @method Gateway findOne(PropelPDO $con = null) Return the first Gateway matching the query
 * @method Gateway findOneOrCreate(PropelPDO $con = null) Return the first Gateway matching the query, or a new Gateway object populated from the query conditions when no match is found
 *
 * @method Gateway findOneByTitle(string $title) Return the first Gateway filtered by the title column
 * @method Gateway findOneByApplicationId(int $application_id) Return the first Gateway filtered by the application_id column
 * @method Gateway findOneByRouterId(int $router_id) Return the first Gateway filtered by the router_id column
 *
 * @method array findById(int $id) Return Gateway objects filtered by the id column
 * @method array findByTitle(string $title) Return Gateway objects filtered by the title column
 * @method array findByApplicationId(int $application_id) Return Gateway objects filtered by the application_id column
 * @method array findByRouterId(int $router_id) Return Gateway objects filtered by the router_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseGatewayQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseGatewayQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\Gateway', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new GatewayQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   GatewayQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return GatewayQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof GatewayQuery) {
            return $criteria;
        }
        $query = new GatewayQuery();
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
     * @return   Gateway|Gateway[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = GatewayPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(GatewayPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Gateway A model object, or null if the key is not found
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
     * @return                 Gateway A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title`, `application_id`, `router_id` FROM `keeko_gateway` WHERE `id` = :p0';
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
            $obj = new Gateway();
            $obj->hydrate($row);
            GatewayPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Gateway|Gateway[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Gateway[]|mixed the list of results, formatted by the current formatter
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
     * @return GatewayQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GatewayPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return GatewayQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GatewayPeer::ID, $keys, Criteria::IN);
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
     * @return GatewayQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GatewayPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GatewayPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GatewayPeer::ID, $id, $comparison);
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
     * @return GatewayQuery The current query, for fluid interface
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

        return $this->addUsingAlias(GatewayPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the application_id column
     *
     * Example usage:
     * <code>
     * $query->filterByApplicationId(1234); // WHERE application_id = 1234
     * $query->filterByApplicationId(array(12, 34)); // WHERE application_id IN (12, 34)
     * $query->filterByApplicationId(array('min' => 12)); // WHERE application_id >= 12
     * $query->filterByApplicationId(array('max' => 12)); // WHERE application_id <= 12
     * </code>
     *
     * @see       filterByApplication()
     *
     * @param     mixed $applicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return GatewayQuery The current query, for fluid interface
     */
    public function filterByApplicationId($applicationId = null, $comparison = null)
    {
        if (is_array($applicationId)) {
            $useMinMax = false;
            if (isset($applicationId['min'])) {
                $this->addUsingAlias(GatewayPeer::APPLICATION_ID, $applicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($applicationId['max'])) {
                $this->addUsingAlias(GatewayPeer::APPLICATION_ID, $applicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GatewayPeer::APPLICATION_ID, $applicationId, $comparison);
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
     * @return GatewayQuery The current query, for fluid interface
     */
    public function filterByRouterId($routerId = null, $comparison = null)
    {
        if (is_array($routerId)) {
            $useMinMax = false;
            if (isset($routerId['min'])) {
                $this->addUsingAlias(GatewayPeer::ROUTER_ID, $routerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($routerId['max'])) {
                $this->addUsingAlias(GatewayPeer::ROUTER_ID, $routerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GatewayPeer::ROUTER_ID, $routerId, $comparison);
    }

    /**
     * Filter the query by a related Application object
     *
     * @param   Application|PropelObjectCollection $application The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 GatewayQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplication($application, $comparison = null)
    {
        if ($application instanceof Application) {
            return $this
                ->addUsingAlias(GatewayPeer::APPLICATION_ID, $application->getId(), $comparison);
        } elseif ($application instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GatewayPeer::APPLICATION_ID, $application->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByApplication() only accepts arguments of type Application or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Application relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return GatewayQuery The current query, for fluid interface
     */
    public function joinApplication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Application');

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
            $this->addJoinObject($join, 'Application');
        }

        return $this;
    }

    /**
     * Use the Application relation Application object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\ApplicationQuery A secondary query class using the current class as primary query
     */
    public function useApplicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinApplication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Application', '\keeko\core\entities\ApplicationQuery');
    }

    /**
     * Filter the query by a related Router object
     *
     * @param   Router|PropelObjectCollection $router The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 GatewayQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRouter($router, $comparison = null)
    {
        if ($router instanceof Router) {
            return $this
                ->addUsingAlias(GatewayPeer::ROUTER_ID, $router->getId(), $comparison);
        } elseif ($router instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GatewayPeer::ROUTER_ID, $router->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return GatewayQuery The current query, for fluid interface
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
     * Filter the query by a related GatewayUri object
     *
     * @param   GatewayUri|PropelObjectCollection $gatewayUri  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 GatewayQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGatewayUri($gatewayUri, $comparison = null)
    {
        if ($gatewayUri instanceof GatewayUri) {
            return $this
                ->addUsingAlias(GatewayPeer::ID, $gatewayUri->getGatewayId(), $comparison);
        } elseif ($gatewayUri instanceof PropelObjectCollection) {
            return $this
                ->useGatewayUriQuery()
                ->filterByPrimaryKeys($gatewayUri->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGatewayUri() only accepts arguments of type GatewayUri or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GatewayUri relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return GatewayQuery The current query, for fluid interface
     */
    public function joinGatewayUri($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GatewayUri');

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
            $this->addJoinObject($join, 'GatewayUri');
        }

        return $this;
    }

    /**
     * Use the GatewayUri relation GatewayUri object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\GatewayUriQuery A secondary query class using the current class as primary query
     */
    public function useGatewayUriQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGatewayUri($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GatewayUri', '\keeko\core\entities\GatewayUriQuery');
    }

    /**
     * Filter the query by a related GatewayExtraProperty object
     *
     * @param   GatewayExtraProperty|PropelObjectCollection $gatewayExtraProperty  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 GatewayQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGatewayExtraProperty($gatewayExtraProperty, $comparison = null)
    {
        if ($gatewayExtraProperty instanceof GatewayExtraProperty) {
            return $this
                ->addUsingAlias(GatewayPeer::ID, $gatewayExtraProperty->getKeekoGatewayId(), $comparison);
        } elseif ($gatewayExtraProperty instanceof PropelObjectCollection) {
            return $this
                ->useGatewayExtraPropertyQuery()
                ->filterByPrimaryKeys($gatewayExtraProperty->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGatewayExtraProperty() only accepts arguments of type GatewayExtraProperty or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GatewayExtraProperty relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return GatewayQuery The current query, for fluid interface
     */
    public function joinGatewayExtraProperty($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GatewayExtraProperty');

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
            $this->addJoinObject($join, 'GatewayExtraProperty');
        }

        return $this;
    }

    /**
     * Use the GatewayExtraProperty relation GatewayExtraProperty object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\GatewayExtraPropertyQuery A secondary query class using the current class as primary query
     */
    public function useGatewayExtraPropertyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGatewayExtraProperty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GatewayExtraProperty', '\keeko\core\entities\GatewayExtraPropertyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Gateway $gateway Object to remove from the list of results
     *
     * @return GatewayQuery The current query, for fluid interface
     */
    public function prune($gateway = null)
    {
        if ($gateway) {
            $this->addUsingAlias(GatewayPeer::ID, $gateway->getId(), Criteria::NOT_EQUAL);
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
     * @return GatewayQuery
     */
    public function filterByExtraProperty($propertyName, $propertyValue)
    {
      $propertyName = GatewayPeer::normalizeExtraPropertyName($propertyName);
      $propertyValue = GatewayPeer::normalizeExtraPropertyValue($propertyValue);

      return $this
        ->leftJoinGatewayExtraProperty($joinName = $propertyName . '_' . uniqid())
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
     * @return GatewayQuery
     */
    public function filterByExtraPropertyWithDefault($propertyName, $propertyValue, $default)
    {
      $propertyName = GatewayPeer::normalizeExtraPropertyName($propertyName);
      $propertyValue = GatewayPeer::normalizeExtraPropertyValue($propertyValue);
      $default = GatewayPeer::normalizeExtraPropertyValue($default);

      return $this
        ->leftJoinGatewayExtraProperty($joinName = $propertyName . '_' . uniqid())
        ->addJoinCondition($joinName, "{$joinName}.PropertyName = ?", $propertyName)
        ->where("COALESCE({$joinName}.PropertyValue, '{$default}') = ?", $propertyValue);
    }


}
