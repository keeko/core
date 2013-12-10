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
use keeko\core\entities\Page;
use keeko\core\entities\Route;
use keeko\core\entities\RoutePeer;
use keeko\core\entities\RouteQuery;

/**
 * Base class that represents a query for the 'keeko_route' table.
 *
 *
 *
 * @method RouteQuery orderById($order = Criteria::ASC) Order by the id column
 * @method RouteQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method RouteQuery orderByRedirectId($order = Criteria::ASC) Order by the redirect_id column
 * @method RouteQuery orderByPageId($order = Criteria::ASC) Order by the page_id column
 *
 * @method RouteQuery groupById() Group by the id column
 * @method RouteQuery groupBySlug() Group by the slug column
 * @method RouteQuery groupByRedirectId() Group by the redirect_id column
 * @method RouteQuery groupByPageId() Group by the page_id column
 *
 * @method RouteQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method RouteQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method RouteQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method RouteQuery leftJoinRouteRelatedByRedirectId($relationAlias = null) Adds a LEFT JOIN clause to the query using the RouteRelatedByRedirectId relation
 * @method RouteQuery rightJoinRouteRelatedByRedirectId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RouteRelatedByRedirectId relation
 * @method RouteQuery innerJoinRouteRelatedByRedirectId($relationAlias = null) Adds a INNER JOIN clause to the query using the RouteRelatedByRedirectId relation
 *
 * @method RouteQuery leftJoinPage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Page relation
 * @method RouteQuery rightJoinPage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Page relation
 * @method RouteQuery innerJoinPage($relationAlias = null) Adds a INNER JOIN clause to the query using the Page relation
 *
 * @method RouteQuery leftJoinRouteRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the RouteRelatedById relation
 * @method RouteQuery rightJoinRouteRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RouteRelatedById relation
 * @method RouteQuery innerJoinRouteRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the RouteRelatedById relation
 *
 * @method Route findOne(PropelPDO $con = null) Return the first Route matching the query
 * @method Route findOneOrCreate(PropelPDO $con = null) Return the first Route matching the query, or a new Route object populated from the query conditions when no match is found
 *
 * @method Route findOneBySlug(string $slug) Return the first Route filtered by the slug column
 * @method Route findOneByRedirectId(int $redirect_id) Return the first Route filtered by the redirect_id column
 * @method Route findOneByPageId(int $page_id) Return the first Route filtered by the page_id column
 *
 * @method array findById(int $id) Return Route objects filtered by the id column
 * @method array findBySlug(string $slug) Return Route objects filtered by the slug column
 * @method array findByRedirectId(int $redirect_id) Return Route objects filtered by the redirect_id column
 * @method array findByPageId(int $page_id) Return Route objects filtered by the page_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseRouteQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseRouteQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'keeko';
        }
        if (null === $modelName) {
            $modelName = 'keeko\\core\\entities\\Route';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new RouteQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   RouteQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return RouteQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof RouteQuery) {
            return $criteria;
        }
        $query = new RouteQuery(null, null, $modelAlias);

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
     * @return   Route|Route[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RoutePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(RoutePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Route A model object, or null if the key is not found
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
     * @return                 Route A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `slug`, `redirect_id`, `page_id` FROM `keeko_route` WHERE `id` = :p0';
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
            $obj = new Route();
            $obj->hydrate($row);
            RoutePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Route|Route[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Route[]|mixed the list of results, formatted by the current formatter
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
     * @return RouteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RoutePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return RouteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RoutePeer::ID, $keys, Criteria::IN);
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
     * @return RouteQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RoutePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RoutePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RoutePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%'); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RouteQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slug)) {
                $slug = str_replace('*', '%', $slug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(RoutePeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query on the redirect_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRedirectId(1234); // WHERE redirect_id = 1234
     * $query->filterByRedirectId(array(12, 34)); // WHERE redirect_id IN (12, 34)
     * $query->filterByRedirectId(array('min' => 12)); // WHERE redirect_id >= 12
     * $query->filterByRedirectId(array('max' => 12)); // WHERE redirect_id <= 12
     * </code>
     *
     * @see       filterByRouteRelatedByRedirectId()
     *
     * @param     mixed $redirectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RouteQuery The current query, for fluid interface
     */
    public function filterByRedirectId($redirectId = null, $comparison = null)
    {
        if (is_array($redirectId)) {
            $useMinMax = false;
            if (isset($redirectId['min'])) {
                $this->addUsingAlias(RoutePeer::REDIRECT_ID, $redirectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($redirectId['max'])) {
                $this->addUsingAlias(RoutePeer::REDIRECT_ID, $redirectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RoutePeer::REDIRECT_ID, $redirectId, $comparison);
    }

    /**
     * Filter the query on the page_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPageId(1234); // WHERE page_id = 1234
     * $query->filterByPageId(array(12, 34)); // WHERE page_id IN (12, 34)
     * $query->filterByPageId(array('min' => 12)); // WHERE page_id >= 12
     * $query->filterByPageId(array('max' => 12)); // WHERE page_id <= 12
     * </code>
     *
     * @see       filterByPage()
     *
     * @param     mixed $pageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RouteQuery The current query, for fluid interface
     */
    public function filterByPageId($pageId = null, $comparison = null)
    {
        if (is_array($pageId)) {
            $useMinMax = false;
            if (isset($pageId['min'])) {
                $this->addUsingAlias(RoutePeer::PAGE_ID, $pageId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pageId['max'])) {
                $this->addUsingAlias(RoutePeer::PAGE_ID, $pageId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RoutePeer::PAGE_ID, $pageId, $comparison);
    }

    /**
     * Filter the query by a related Route object
     *
     * @param   Route|PropelObjectCollection $route The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RouteQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRouteRelatedByRedirectId($route, $comparison = null)
    {
        if ($route instanceof Route) {
            return $this
                ->addUsingAlias(RoutePeer::REDIRECT_ID, $route->getId(), $comparison);
        } elseif ($route instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RoutePeer::REDIRECT_ID, $route->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRouteRelatedByRedirectId() only accepts arguments of type Route or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RouteRelatedByRedirectId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return RouteQuery The current query, for fluid interface
     */
    public function joinRouteRelatedByRedirectId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RouteRelatedByRedirectId');

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
            $this->addJoinObject($join, 'RouteRelatedByRedirectId');
        }

        return $this;
    }

    /**
     * Use the RouteRelatedByRedirectId relation Route object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\RouteQuery A secondary query class using the current class as primary query
     */
    public function useRouteRelatedByRedirectIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRouteRelatedByRedirectId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RouteRelatedByRedirectId', '\keeko\core\entities\RouteQuery');
    }

    /**
     * Filter the query by a related Page object
     *
     * @param   Page|PropelObjectCollection $page The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RouteQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPage($page, $comparison = null)
    {
        if ($page instanceof Page) {
            return $this
                ->addUsingAlias(RoutePeer::PAGE_ID, $page->getId(), $comparison);
        } elseif ($page instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RoutePeer::PAGE_ID, $page->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPage() only accepts arguments of type Page or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Page relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return RouteQuery The current query, for fluid interface
     */
    public function joinPage($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Page');

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
            $this->addJoinObject($join, 'Page');
        }

        return $this;
    }

    /**
     * Use the Page relation Page object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\PageQuery A secondary query class using the current class as primary query
     */
    public function usePageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Page', '\keeko\core\entities\PageQuery');
    }

    /**
     * Filter the query by a related Route object
     *
     * @param   Route|PropelObjectCollection $route  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RouteQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRouteRelatedById($route, $comparison = null)
    {
        if ($route instanceof Route) {
            return $this
                ->addUsingAlias(RoutePeer::ID, $route->getRedirectId(), $comparison);
        } elseif ($route instanceof PropelObjectCollection) {
            return $this
                ->useRouteRelatedByIdQuery()
                ->filterByPrimaryKeys($route->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRouteRelatedById() only accepts arguments of type Route or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RouteRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return RouteQuery The current query, for fluid interface
     */
    public function joinRouteRelatedById($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RouteRelatedById');

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
            $this->addJoinObject($join, 'RouteRelatedById');
        }

        return $this;
    }

    /**
     * Use the RouteRelatedById relation Route object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\RouteQuery A secondary query class using the current class as primary query
     */
    public function useRouteRelatedByIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRouteRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RouteRelatedById', '\keeko\core\entities\RouteQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Route $route Object to remove from the list of results
     *
     * @return RouteQuery The current query, for fluid interface
     */
    public function prune($route = null)
    {
        if ($route) {
            $this->addUsingAlias(RoutePeer::ID, $route->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
