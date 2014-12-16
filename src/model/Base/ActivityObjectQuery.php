<?php

namespace keeko\core\model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use keeko\core\model\ActivityObject as ChildActivityObject;
use keeko\core\model\ActivityObjectQuery as ChildActivityObjectQuery;
use keeko\core\model\Map\ActivityObjectTableMap;

/**
 * Base class that represents a query for the 'kk_activity_object' table.
 *
 *
 *
 * @method     ChildActivityObjectQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildActivityObjectQuery orderByClassName($order = Criteria::ASC) Order by the class_name column
 * @method     ChildActivityObjectQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildActivityObjectQuery orderByDisplayName($order = Criteria::ASC) Order by the display_name column
 * @method     ChildActivityObjectQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method     ChildActivityObjectQuery orderByReferenceId($order = Criteria::ASC) Order by the reference_id column
 * @method     ChildActivityObjectQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildActivityObjectQuery orderByExtra($order = Criteria::ASC) Order by the extra column
 *
 * @method     ChildActivityObjectQuery groupById() Group by the id column
 * @method     ChildActivityObjectQuery groupByClassName() Group by the class_name column
 * @method     ChildActivityObjectQuery groupByType() Group by the type column
 * @method     ChildActivityObjectQuery groupByDisplayName() Group by the display_name column
 * @method     ChildActivityObjectQuery groupByUrl() Group by the url column
 * @method     ChildActivityObjectQuery groupByReferenceId() Group by the reference_id column
 * @method     ChildActivityObjectQuery groupByVersion() Group by the version column
 * @method     ChildActivityObjectQuery groupByExtra() Group by the extra column
 *
 * @method     ChildActivityObjectQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildActivityObjectQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildActivityObjectQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildActivityObjectQuery leftJoinActivityRelatedByObjectId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ActivityRelatedByObjectId relation
 * @method     ChildActivityObjectQuery rightJoinActivityRelatedByObjectId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ActivityRelatedByObjectId relation
 * @method     ChildActivityObjectQuery innerJoinActivityRelatedByObjectId($relationAlias = null) Adds a INNER JOIN clause to the query using the ActivityRelatedByObjectId relation
 *
 * @method     ChildActivityObjectQuery leftJoinActivityRelatedByTargetId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ActivityRelatedByTargetId relation
 * @method     ChildActivityObjectQuery rightJoinActivityRelatedByTargetId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ActivityRelatedByTargetId relation
 * @method     ChildActivityObjectQuery innerJoinActivityRelatedByTargetId($relationAlias = null) Adds a INNER JOIN clause to the query using the ActivityRelatedByTargetId relation
 *
 * @method     \keeko\core\model\ActivityQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildActivityObject findOne(ConnectionInterface $con = null) Return the first ChildActivityObject matching the query
 * @method     ChildActivityObject findOneOrCreate(ConnectionInterface $con = null) Return the first ChildActivityObject matching the query, or a new ChildActivityObject object populated from the query conditions when no match is found
 *
 * @method     ChildActivityObject findOneById(int $id) Return the first ChildActivityObject filtered by the id column
 * @method     ChildActivityObject findOneByClassName(string $class_name) Return the first ChildActivityObject filtered by the class_name column
 * @method     ChildActivityObject findOneByType(string $type) Return the first ChildActivityObject filtered by the type column
 * @method     ChildActivityObject findOneByDisplayName(string $display_name) Return the first ChildActivityObject filtered by the display_name column
 * @method     ChildActivityObject findOneByUrl(string $url) Return the first ChildActivityObject filtered by the url column
 * @method     ChildActivityObject findOneByReferenceId(int $reference_id) Return the first ChildActivityObject filtered by the reference_id column
 * @method     ChildActivityObject findOneByVersion(int $version) Return the first ChildActivityObject filtered by the version column
 * @method     ChildActivityObject findOneByExtra(string $extra) Return the first ChildActivityObject filtered by the extra column
 *
 * @method     ChildActivityObject[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildActivityObject objects based on current ModelCriteria
 * @method     ChildActivityObject[]|ObjectCollection findById(int $id) Return ChildActivityObject objects filtered by the id column
 * @method     ChildActivityObject[]|ObjectCollection findByClassName(string $class_name) Return ChildActivityObject objects filtered by the class_name column
 * @method     ChildActivityObject[]|ObjectCollection findByType(string $type) Return ChildActivityObject objects filtered by the type column
 * @method     ChildActivityObject[]|ObjectCollection findByDisplayName(string $display_name) Return ChildActivityObject objects filtered by the display_name column
 * @method     ChildActivityObject[]|ObjectCollection findByUrl(string $url) Return ChildActivityObject objects filtered by the url column
 * @method     ChildActivityObject[]|ObjectCollection findByReferenceId(int $reference_id) Return ChildActivityObject objects filtered by the reference_id column
 * @method     ChildActivityObject[]|ObjectCollection findByVersion(int $version) Return ChildActivityObject objects filtered by the version column
 * @method     ChildActivityObject[]|ObjectCollection findByExtra(string $extra) Return ChildActivityObject objects filtered by the extra column
 * @method     ChildActivityObject[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ActivityObjectQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \keeko\core\model\Base\ActivityObjectQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\ActivityObject', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildActivityObjectQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildActivityObjectQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildActivityObjectQuery) {
            return $criteria;
        }
        $query = new ChildActivityObjectQuery();
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildActivityObject|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ActivityObjectTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ActivityObjectTableMap::DATABASE_NAME);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildActivityObject A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `class_name`, `type`, `display_name`, `url`, `reference_id`, `version`, `extra` FROM `kk_activity_object` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildActivityObject $obj */
            $obj = new ChildActivityObject();
            $obj->hydrate($row);
            ActivityObjectTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildActivityObject|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ActivityObjectTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ActivityObjectTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ActivityObjectTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ActivityObjectTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityObjectTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ActivityObjectTableMap::COL_CLASS_NAME, $className, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $type)) {
                $type = str_replace('*', '%', $type);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ActivityObjectTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the display_name column
     *
     * Example usage:
     * <code>
     * $query->filterByDisplayName('fooValue');   // WHERE display_name = 'fooValue'
     * $query->filterByDisplayName('%fooValue%'); // WHERE display_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $displayName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByDisplayName($displayName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($displayName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $displayName)) {
                $displayName = str_replace('*', '%', $displayName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ActivityObjectTableMap::COL_DISPLAY_NAME, $displayName, $comparison);
    }

    /**
     * Filter the query on the url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE url = 'fooValue'
     * $query->filterByUrl('%fooValue%'); // WHERE url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $url)) {
                $url = str_replace('*', '%', $url);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ActivityObjectTableMap::COL_URL, $url, $comparison);
    }

    /**
     * Filter the query on the reference_id column
     *
     * Example usage:
     * <code>
     * $query->filterByReferenceId(1234); // WHERE reference_id = 1234
     * $query->filterByReferenceId(array(12, 34)); // WHERE reference_id IN (12, 34)
     * $query->filterByReferenceId(array('min' => 12)); // WHERE reference_id > 12
     * </code>
     *
     * @param     mixed $referenceId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByReferenceId($referenceId = null, $comparison = null)
    {
        if (is_array($referenceId)) {
            $useMinMax = false;
            if (isset($referenceId['min'])) {
                $this->addUsingAlias(ActivityObjectTableMap::COL_REFERENCE_ID, $referenceId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($referenceId['max'])) {
                $this->addUsingAlias(ActivityObjectTableMap::COL_REFERENCE_ID, $referenceId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityObjectTableMap::COL_REFERENCE_ID, $referenceId, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion(1234); // WHERE version = 1234
     * $query->filterByVersion(array(12, 34)); // WHERE version IN (12, 34)
     * $query->filterByVersion(array('min' => 12)); // WHERE version > 12
     * </code>
     *
     * @param     mixed $version The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(ActivityObjectTableMap::COL_VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(ActivityObjectTableMap::COL_VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityObjectTableMap::COL_VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the extra column
     *
     * Example usage:
     * <code>
     * $query->filterByExtra('fooValue');   // WHERE extra = 'fooValue'
     * $query->filterByExtra('%fooValue%'); // WHERE extra LIKE '%fooValue%'
     * </code>
     *
     * @param     string $extra The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByExtra($extra = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($extra)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $extra)) {
                $extra = str_replace('*', '%', $extra);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ActivityObjectTableMap::COL_EXTRA, $extra, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Activity object
     *
     * @param \keeko\core\model\Activity|ObjectCollection $activity  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByActivityRelatedByObjectId($activity, $comparison = null)
    {
        if ($activity instanceof \keeko\core\model\Activity) {
            return $this
                ->addUsingAlias(ActivityObjectTableMap::COL_ID, $activity->getObjectId(), $comparison);
        } elseif ($activity instanceof ObjectCollection) {
            return $this
                ->useActivityRelatedByObjectIdQuery()
                ->filterByPrimaryKeys($activity->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByActivityRelatedByObjectId() only accepts arguments of type \keeko\core\model\Activity or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ActivityRelatedByObjectId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function joinActivityRelatedByObjectId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ActivityRelatedByObjectId');

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
            $this->addJoinObject($join, 'ActivityRelatedByObjectId');
        }

        return $this;
    }

    /**
     * Use the ActivityRelatedByObjectId relation Activity object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ActivityQuery A secondary query class using the current class as primary query
     */
    public function useActivityRelatedByObjectIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinActivityRelatedByObjectId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ActivityRelatedByObjectId', '\keeko\core\model\ActivityQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Activity object
     *
     * @param \keeko\core\model\Activity|ObjectCollection $activity  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActivityObjectQuery The current query, for fluid interface
     */
    public function filterByActivityRelatedByTargetId($activity, $comparison = null)
    {
        if ($activity instanceof \keeko\core\model\Activity) {
            return $this
                ->addUsingAlias(ActivityObjectTableMap::COL_ID, $activity->getTargetId(), $comparison);
        } elseif ($activity instanceof ObjectCollection) {
            return $this
                ->useActivityRelatedByTargetIdQuery()
                ->filterByPrimaryKeys($activity->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByActivityRelatedByTargetId() only accepts arguments of type \keeko\core\model\Activity or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ActivityRelatedByTargetId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function joinActivityRelatedByTargetId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ActivityRelatedByTargetId');

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
            $this->addJoinObject($join, 'ActivityRelatedByTargetId');
        }

        return $this;
    }

    /**
     * Use the ActivityRelatedByTargetId relation Activity object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ActivityQuery A secondary query class using the current class as primary query
     */
    public function useActivityRelatedByTargetIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinActivityRelatedByTargetId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ActivityRelatedByTargetId', '\keeko\core\model\ActivityQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildActivityObject $activityObject Object to remove from the list of results
     *
     * @return $this|ChildActivityObjectQuery The current query, for fluid interface
     */
    public function prune($activityObject = null)
    {
        if ($activityObject) {
            $this->addUsingAlias(ActivityObjectTableMap::COL_ID, $activityObject->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_activity_object table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityObjectTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ActivityObjectTableMap::clearInstancePool();
            ActivityObjectTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityObjectTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ActivityObjectTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ActivityObjectTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ActivityObjectTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ActivityObjectQuery
