<?php

namespace keeko\core\model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use keeko\core\model\Preference as ChildPreference;
use keeko\core\model\PreferenceQuery as ChildPreferenceQuery;
use keeko\core\model\Map\PreferenceTableMap;

/**
 * Base class that represents a query for the 'kk_preference' table.
 *
 *
 *
 * @method     ChildPreferenceQuery orderByKey($order = Criteria::ASC) Order by the key column
 * @method     ChildPreferenceQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method     ChildPreferenceQuery orderByModuleId($order = Criteria::ASC) Order by the module_id column
 *
 * @method     ChildPreferenceQuery groupByKey() Group by the key column
 * @method     ChildPreferenceQuery groupByValue() Group by the value column
 * @method     ChildPreferenceQuery groupByModuleId() Group by the module_id column
 *
 * @method     ChildPreferenceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPreferenceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPreferenceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPreference findOne(ConnectionInterface $con = null) Return the first ChildPreference matching the query
 * @method     ChildPreference findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPreference matching the query, or a new ChildPreference object populated from the query conditions when no match is found
 *
 * @method     ChildPreference findOneByKey(string $key) Return the first ChildPreference filtered by the key column
 * @method     ChildPreference findOneByValue(string $value) Return the first ChildPreference filtered by the value column
 * @method     ChildPreference findOneByModuleId(int $module_id) Return the first ChildPreference filtered by the module_id column
 *
 * @method     ChildPreference[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPreference objects based on current ModelCriteria
 * @method     ChildPreference[]|ObjectCollection findByKey(string $key) Return ChildPreference objects filtered by the key column
 * @method     ChildPreference[]|ObjectCollection findByValue(string $value) Return ChildPreference objects filtered by the value column
 * @method     ChildPreference[]|ObjectCollection findByModuleId(int $module_id) Return ChildPreference objects filtered by the module_id column
 * @method     ChildPreference[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PreferenceQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \keeko\core\model\Base\PreferenceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Preference', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPreferenceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPreferenceQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPreferenceQuery) {
            return $criteria;
        }
        $query = new ChildPreferenceQuery();
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
     * @return ChildPreference|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PreferenceTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PreferenceTableMap::DATABASE_NAME);
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
     * @return ChildPreference A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `key`, `value`, `module_id` FROM `kk_preference` WHERE `key` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPreference $obj */
            $obj = new ChildPreference();
            $obj->hydrate($row);
            PreferenceTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildPreference|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPreferenceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PreferenceTableMap::COL_KEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPreferenceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PreferenceTableMap::COL_KEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the key column
     *
     * Example usage:
     * <code>
     * $query->filterByKey('fooValue');   // WHERE key = 'fooValue'
     * $query->filterByKey('%fooValue%'); // WHERE key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $key The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPreferenceQuery The current query, for fluid interface
     */
    public function filterByKey($key = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($key)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $key)) {
                $key = str_replace('*', '%', $key);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PreferenceTableMap::COL_KEY, $key, $comparison);
    }

    /**
     * Filter the query on the value column
     *
     * Example usage:
     * <code>
     * $query->filterByValue('fooValue');   // WHERE value = 'fooValue'
     * $query->filterByValue('%fooValue%'); // WHERE value LIKE '%fooValue%'
     * </code>
     *
     * @param     string $value The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPreferenceQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($value)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $value)) {
                $value = str_replace('*', '%', $value);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PreferenceTableMap::COL_VALUE, $value, $comparison);
    }

    /**
     * Filter the query on the module_id column
     *
     * Example usage:
     * <code>
     * $query->filterByModuleId(1234); // WHERE module_id = 1234
     * $query->filterByModuleId(array(12, 34)); // WHERE module_id IN (12, 34)
     * $query->filterByModuleId(array('min' => 12)); // WHERE module_id > 12
     * </code>
     *
     * @param     mixed $moduleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPreferenceQuery The current query, for fluid interface
     */
    public function filterByModuleId($moduleId = null, $comparison = null)
    {
        if (is_array($moduleId)) {
            $useMinMax = false;
            if (isset($moduleId['min'])) {
                $this->addUsingAlias(PreferenceTableMap::COL_MODULE_ID, $moduleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($moduleId['max'])) {
                $this->addUsingAlias(PreferenceTableMap::COL_MODULE_ID, $moduleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PreferenceTableMap::COL_MODULE_ID, $moduleId, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPreference $preference Object to remove from the list of results
     *
     * @return $this|ChildPreferenceQuery The current query, for fluid interface
     */
    public function prune($preference = null)
    {
        if ($preference) {
            $this->addUsingAlias(PreferenceTableMap::COL_KEY, $preference->getKey(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_preference table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PreferenceTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PreferenceTableMap::clearInstancePool();
            PreferenceTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PreferenceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PreferenceTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PreferenceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PreferenceTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PreferenceQuery
