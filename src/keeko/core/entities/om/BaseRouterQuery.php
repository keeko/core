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
use keeko\core\entities\Router;
use keeko\core\entities\RouterPeer;
use keeko\core\entities\RouterQuery;

/**
 * Base class that represents a query for the 'keeko_router' table.
 *
 *
 *
 * @method RouterQuery orderById($order = Criteria::ASC) Order by the id column
 * @method RouterQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method RouterQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method RouterQuery orderByClassname($order = Criteria::ASC) Order by the classname column
 *
 * @method RouterQuery groupById() Group by the id column
 * @method RouterQuery groupByName() Group by the name column
 * @method RouterQuery groupByTitle() Group by the title column
 * @method RouterQuery groupByClassname() Group by the classname column
 *
 * @method RouterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method RouterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method RouterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method RouterQuery leftJoinApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Application relation
 * @method RouterQuery rightJoinApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Application relation
 * @method RouterQuery innerJoinApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the Application relation
 *
 * @method Router findOne(PropelPDO $con = null) Return the first Router matching the query
 * @method Router findOneOrCreate(PropelPDO $con = null) Return the first Router matching the query, or a new Router object populated from the query conditions when no match is found
 *
 * @method Router findOneByName(string $name) Return the first Router filtered by the name column
 * @method Router findOneByTitle(string $title) Return the first Router filtered by the title column
 * @method Router findOneByClassname(string $classname) Return the first Router filtered by the classname column
 *
 * @method array findById(int $id) Return Router objects filtered by the id column
 * @method array findByName(string $name) Return Router objects filtered by the name column
 * @method array findByTitle(string $title) Return Router objects filtered by the title column
 * @method array findByClassname(string $classname) Return Router objects filtered by the classname column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseRouterQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseRouterQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\Router', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new RouterQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   RouterQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return RouterQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof RouterQuery) {
            return $criteria;
        }
        $query = new RouterQuery();
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
     * @return   Router|Router[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RouterPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(RouterPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Router A model object, or null if the key is not found
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
     * @return                 Router A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `title`, `classname` FROM `keeko_router` WHERE `id` = :p0';
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
            $obj = new Router();
            $obj->hydrate($row);
            RouterPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Router|Router[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Router[]|mixed the list of results, formatted by the current formatter
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
     * @return RouterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RouterPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return RouterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RouterPeer::ID, $keys, Criteria::IN);
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
     * @return RouterQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RouterPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RouterPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RouterPeer::ID, $id, $comparison);
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
     * @return RouterQuery The current query, for fluid interface
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

        return $this->addUsingAlias(RouterPeer::NAME, $name, $comparison);
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
     * @return RouterQuery The current query, for fluid interface
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

        return $this->addUsingAlias(RouterPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the classname column
     *
     * Example usage:
     * <code>
     * $query->filterByClassname('fooValue');   // WHERE classname = 'fooValue'
     * $query->filterByClassname('%fooValue%'); // WHERE classname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $classname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RouterQuery The current query, for fluid interface
     */
    public function filterByClassname($classname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($classname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $classname)) {
                $classname = str_replace('*', '%', $classname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(RouterPeer::CLASSNAME, $classname, $comparison);
    }

    /**
     * Filter the query by a related Application object
     *
     * @param   Application|PropelObjectCollection $application  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RouterQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplication($application, $comparison = null)
    {
        if ($application instanceof Application) {
            return $this
                ->addUsingAlias(RouterPeer::ID, $application->getRouterId(), $comparison);
        } elseif ($application instanceof PropelObjectCollection) {
            return $this
                ->useApplicationQuery()
                ->filterByPrimaryKeys($application->getPrimaryKeys())
                ->endUse();
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
     * @return RouterQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   Router $router Object to remove from the list of results
     *
     * @return RouterQuery The current query, for fluid interface
     */
    public function prune($router = null)
    {
        if ($router) {
            $this->addUsingAlias(RouterPeer::ID, $router->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
