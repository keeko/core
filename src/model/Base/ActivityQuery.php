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
use keeko\core\model\Activity as ChildActivity;
use keeko\core\model\ActivityQuery as ChildActivityQuery;
use keeko\core\model\Map\ActivityTableMap;

/**
 * Base class that represents a query for the 'kk_activity' table.
 *
 *
 *
 * @method     ChildActivityQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildActivityQuery orderByActorId($order = Criteria::ASC) Order by the actor_id column
 * @method     ChildActivityQuery orderByVerb($order = Criteria::ASC) Order by the verb column
 * @method     ChildActivityQuery orderByObjectId($order = Criteria::ASC) Order by the object_id column
 * @method     ChildActivityQuery orderByTargetId($order = Criteria::ASC) Order by the target_id column
 * @method     ChildActivityQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildActivityQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildActivityQuery groupById() Group by the id column
 * @method     ChildActivityQuery groupByActorId() Group by the actor_id column
 * @method     ChildActivityQuery groupByVerb() Group by the verb column
 * @method     ChildActivityQuery groupByObjectId() Group by the object_id column
 * @method     ChildActivityQuery groupByTargetId() Group by the target_id column
 * @method     ChildActivityQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildActivityQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildActivityQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildActivityQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildActivityQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildActivityQuery leftJoinActor($relationAlias = null) Adds a LEFT JOIN clause to the query using the Actor relation
 * @method     ChildActivityQuery rightJoinActor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Actor relation
 * @method     ChildActivityQuery innerJoinActor($relationAlias = null) Adds a INNER JOIN clause to the query using the Actor relation
 *
 * @method     ChildActivityQuery leftJoinObject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Object relation
 * @method     ChildActivityQuery rightJoinObject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Object relation
 * @method     ChildActivityQuery innerJoinObject($relationAlias = null) Adds a INNER JOIN clause to the query using the Object relation
 *
 * @method     ChildActivityQuery leftJoinTarget($relationAlias = null) Adds a LEFT JOIN clause to the query using the Target relation
 * @method     ChildActivityQuery rightJoinTarget($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Target relation
 * @method     ChildActivityQuery innerJoinTarget($relationAlias = null) Adds a INNER JOIN clause to the query using the Target relation
 *
 * @method     \keeko\core\model\UserQuery|\keeko\core\model\ActivityObjectQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildActivity findOne(ConnectionInterface $con = null) Return the first ChildActivity matching the query
 * @method     ChildActivity findOneOrCreate(ConnectionInterface $con = null) Return the first ChildActivity matching the query, or a new ChildActivity object populated from the query conditions when no match is found
 *
 * @method     ChildActivity findOneById(int $id) Return the first ChildActivity filtered by the id column
 * @method     ChildActivity findOneByActorId(int $actor_id) Return the first ChildActivity filtered by the actor_id column
 * @method     ChildActivity findOneByVerb(string $verb) Return the first ChildActivity filtered by the verb column
 * @method     ChildActivity findOneByObjectId(int $object_id) Return the first ChildActivity filtered by the object_id column
 * @method     ChildActivity findOneByTargetId(int $target_id) Return the first ChildActivity filtered by the target_id column
 * @method     ChildActivity findOneByCreatedAt(string $created_at) Return the first ChildActivity filtered by the created_at column
 * @method     ChildActivity findOneByUpdatedAt(string $updated_at) Return the first ChildActivity filtered by the updated_at column *

 * @method     ChildActivity requirePk($key, ConnectionInterface $con = null) Return the ChildActivity by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOne(ConnectionInterface $con = null) Return the first ChildActivity matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildActivity requireOneById(int $id) Return the first ChildActivity filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByActorId(int $actor_id) Return the first ChildActivity filtered by the actor_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByVerb(string $verb) Return the first ChildActivity filtered by the verb column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByObjectId(int $object_id) Return the first ChildActivity filtered by the object_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByTargetId(int $target_id) Return the first ChildActivity filtered by the target_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByCreatedAt(string $created_at) Return the first ChildActivity filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByUpdatedAt(string $updated_at) Return the first ChildActivity filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildActivity[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildActivity objects based on current ModelCriteria
 * @method     ChildActivity[]|ObjectCollection findById(int $id) Return ChildActivity objects filtered by the id column
 * @method     ChildActivity[]|ObjectCollection findByActorId(int $actor_id) Return ChildActivity objects filtered by the actor_id column
 * @method     ChildActivity[]|ObjectCollection findByVerb(string $verb) Return ChildActivity objects filtered by the verb column
 * @method     ChildActivity[]|ObjectCollection findByObjectId(int $object_id) Return ChildActivity objects filtered by the object_id column
 * @method     ChildActivity[]|ObjectCollection findByTargetId(int $target_id) Return ChildActivity objects filtered by the target_id column
 * @method     ChildActivity[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildActivity objects filtered by the created_at column
 * @method     ChildActivity[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildActivity objects filtered by the updated_at column
 * @method     ChildActivity[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ActivityQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\ActivityQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Activity', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildActivityQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildActivityQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildActivityQuery) {
            return $criteria;
        }
        $query = new ChildActivityQuery();
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
     * @return ChildActivity|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ActivityTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ActivityTableMap::DATABASE_NAME);
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
     * @return ChildActivity A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `actor_id`, `verb`, `object_id`, `target_id`, `created_at`, `updated_at` FROM `kk_activity` WHERE `id` = :p0';
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
            /** @var ChildActivity $obj */
            $obj = new ChildActivity();
            $obj->hydrate($row);
            ActivityTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildActivity|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ActivityTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ActivityTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the actor_id column
     *
     * Example usage:
     * <code>
     * $query->filterByActorId(1234); // WHERE actor_id = 1234
     * $query->filterByActorId(array(12, 34)); // WHERE actor_id IN (12, 34)
     * $query->filterByActorId(array('min' => 12)); // WHERE actor_id > 12
     * </code>
     *
     * @see       filterByActor()
     *
     * @param     mixed $actorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByActorId($actorId = null, $comparison = null)
    {
        if (is_array($actorId)) {
            $useMinMax = false;
            if (isset($actorId['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_ACTOR_ID, $actorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($actorId['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_ACTOR_ID, $actorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_ACTOR_ID, $actorId, $comparison);
    }

    /**
     * Filter the query on the verb column
     *
     * Example usage:
     * <code>
     * $query->filterByVerb('fooValue');   // WHERE verb = 'fooValue'
     * $query->filterByVerb('%fooValue%'); // WHERE verb LIKE '%fooValue%'
     * </code>
     *
     * @param     string $verb The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByVerb($verb = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($verb)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $verb)) {
                $verb = str_replace('*', '%', $verb);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_VERB, $verb, $comparison);
    }

    /**
     * Filter the query on the object_id column
     *
     * Example usage:
     * <code>
     * $query->filterByObjectId(1234); // WHERE object_id = 1234
     * $query->filterByObjectId(array(12, 34)); // WHERE object_id IN (12, 34)
     * $query->filterByObjectId(array('min' => 12)); // WHERE object_id > 12
     * </code>
     *
     * @see       filterByObject()
     *
     * @param     mixed $objectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByObjectId($objectId = null, $comparison = null)
    {
        if (is_array($objectId)) {
            $useMinMax = false;
            if (isset($objectId['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_OBJECT_ID, $objectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($objectId['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_OBJECT_ID, $objectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_OBJECT_ID, $objectId, $comparison);
    }

    /**
     * Filter the query on the target_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTargetId(1234); // WHERE target_id = 1234
     * $query->filterByTargetId(array(12, 34)); // WHERE target_id IN (12, 34)
     * $query->filterByTargetId(array('min' => 12)); // WHERE target_id > 12
     * </code>
     *
     * @see       filterByTarget()
     *
     * @param     mixed $targetId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByTargetId($targetId = null, $comparison = null)
    {
        if (is_array($targetId)) {
            $useMinMax = false;
            if (isset($targetId['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_TARGET_ID, $targetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($targetId['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_TARGET_ID, $targetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_TARGET_ID, $targetId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\User object
     *
     * @param \keeko\core\model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildActivityQuery The current query, for fluid interface
     */
    public function filterByActor($user, $comparison = null)
    {
        if ($user instanceof \keeko\core\model\User) {
            return $this
                ->addUsingAlias(ActivityTableMap::COL_ACTOR_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ActivityTableMap::COL_ACTOR_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByActor() only accepts arguments of type \keeko\core\model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Actor relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function joinActor($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Actor');

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
            $this->addJoinObject($join, 'Actor');
        }

        return $this;
    }

    /**
     * Use the Actor relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\UserQuery A secondary query class using the current class as primary query
     */
    public function useActorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinActor($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Actor', '\keeko\core\model\UserQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\ActivityObject object
     *
     * @param \keeko\core\model\ActivityObject|ObjectCollection $activityObject The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildActivityQuery The current query, for fluid interface
     */
    public function filterByObject($activityObject, $comparison = null)
    {
        if ($activityObject instanceof \keeko\core\model\ActivityObject) {
            return $this
                ->addUsingAlias(ActivityTableMap::COL_OBJECT_ID, $activityObject->getId(), $comparison);
        } elseif ($activityObject instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ActivityTableMap::COL_OBJECT_ID, $activityObject->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByObject() only accepts arguments of type \keeko\core\model\ActivityObject or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Object relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function joinObject($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Object');

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
            $this->addJoinObject($join, 'Object');
        }

        return $this;
    }

    /**
     * Use the Object relation ActivityObject object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ActivityObjectQuery A secondary query class using the current class as primary query
     */
    public function useObjectQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinObject($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Object', '\keeko\core\model\ActivityObjectQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\ActivityObject object
     *
     * @param \keeko\core\model\ActivityObject|ObjectCollection $activityObject The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildActivityQuery The current query, for fluid interface
     */
    public function filterByTarget($activityObject, $comparison = null)
    {
        if ($activityObject instanceof \keeko\core\model\ActivityObject) {
            return $this
                ->addUsingAlias(ActivityTableMap::COL_TARGET_ID, $activityObject->getId(), $comparison);
        } elseif ($activityObject instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ActivityTableMap::COL_TARGET_ID, $activityObject->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTarget() only accepts arguments of type \keeko\core\model\ActivityObject or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Target relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function joinTarget($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Target');

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
            $this->addJoinObject($join, 'Target');
        }

        return $this;
    }

    /**
     * Use the Target relation ActivityObject object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ActivityObjectQuery A secondary query class using the current class as primary query
     */
    public function useTargetQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTarget($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Target', '\keeko\core\model\ActivityObjectQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildActivity $activity Object to remove from the list of results
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function prune($activity = null)
    {
        if ($activity) {
            $this->addUsingAlias(ActivityTableMap::COL_ID, $activity->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_activity table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ActivityTableMap::clearInstancePool();
            ActivityTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ActivityTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ActivityTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ActivityTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildActivityQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ActivityTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildActivityQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ActivityTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildActivityQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ActivityTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildActivityQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ActivityTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildActivityQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ActivityTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildActivityQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ActivityTableMap::COL_CREATED_AT);
    }

} // ActivityQuery
