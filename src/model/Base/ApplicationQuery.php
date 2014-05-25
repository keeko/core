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
use keeko\core\model\Application as ChildApplication;
use keeko\core\model\ApplicationQuery as ChildApplicationQuery;
use keeko\core\model\PackageQuery as ChildPackageQuery;
use keeko\core\model\Map\ApplicationTableMap;

/**
 * Base class that represents a query for the 'keeko_application' table.
 *
 *
 *
 * @method     ChildApplicationQuery orderByClassName($order = Criteria::ASC) Order by the class_name column
 * @method     ChildApplicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildApplicationQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildApplicationQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildApplicationQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildApplicationQuery orderByInstalledVersion($order = Criteria::ASC) Order by the installed_version column
 *
 * @method     ChildApplicationQuery groupByClassName() Group by the class_name column
 * @method     ChildApplicationQuery groupById() Group by the id column
 * @method     ChildApplicationQuery groupByName() Group by the name column
 * @method     ChildApplicationQuery groupByTitle() Group by the title column
 * @method     ChildApplicationQuery groupByDescription() Group by the description column
 * @method     ChildApplicationQuery groupByInstalledVersion() Group by the installed_version column
 *
 * @method     ChildApplicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildApplicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildApplicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildApplicationQuery leftJoinPackage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Package relation
 * @method     ChildApplicationQuery rightJoinPackage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Package relation
 * @method     ChildApplicationQuery innerJoinPackage($relationAlias = null) Adds a INNER JOIN clause to the query using the Package relation
 *
 * @method     ChildApplicationQuery leftJoinApplicationUri($relationAlias = null) Adds a LEFT JOIN clause to the query using the ApplicationUri relation
 * @method     ChildApplicationQuery rightJoinApplicationUri($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ApplicationUri relation
 * @method     ChildApplicationQuery innerJoinApplicationUri($relationAlias = null) Adds a INNER JOIN clause to the query using the ApplicationUri relation
 *
 * @method     \keeko\core\model\PackageQuery|\keeko\core\model\ApplicationUriQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildApplication findOne(ConnectionInterface $con = null) Return the first ChildApplication matching the query
 * @method     ChildApplication findOneOrCreate(ConnectionInterface $con = null) Return the first ChildApplication matching the query, or a new ChildApplication object populated from the query conditions when no match is found
 *
 * @method     ChildApplication findOneByClassName(string $class_name) Return the first ChildApplication filtered by the class_name column
 * @method     ChildApplication findOneById(int $id) Return the first ChildApplication filtered by the id column
 * @method     ChildApplication findOneByName(string $name) Return the first ChildApplication filtered by the name column
 * @method     ChildApplication findOneByTitle(string $title) Return the first ChildApplication filtered by the title column
 * @method     ChildApplication findOneByDescription(string $description) Return the first ChildApplication filtered by the description column
 * @method     ChildApplication findOneByInstalledVersion(string $installed_version) Return the first ChildApplication filtered by the installed_version column
 *
 * @method     ChildApplication[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildApplication objects based on current ModelCriteria
 * @method     ChildApplication[]|ObjectCollection findByClassName(string $class_name) Return ChildApplication objects filtered by the class_name column
 * @method     ChildApplication[]|ObjectCollection findById(int $id) Return ChildApplication objects filtered by the id column
 * @method     ChildApplication[]|ObjectCollection findByName(string $name) Return ChildApplication objects filtered by the name column
 * @method     ChildApplication[]|ObjectCollection findByTitle(string $title) Return ChildApplication objects filtered by the title column
 * @method     ChildApplication[]|ObjectCollection findByDescription(string $description) Return ChildApplication objects filtered by the description column
 * @method     ChildApplication[]|ObjectCollection findByInstalledVersion(string $installed_version) Return ChildApplication objects filtered by the installed_version column
 * @method     ChildApplication[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ApplicationQuery extends ChildPackageQuery
{

    /**
     * Initializes internal state of \keeko\core\model\Base\ApplicationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Application', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildApplicationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildApplicationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildApplicationQuery) {
            return $criteria;
        }
        $query = new ChildApplicationQuery();
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
     * @return ChildApplication|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ApplicationTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ApplicationTableMap::DATABASE_NAME);
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
     * @return ChildApplication A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT CLASS_NAME, ID, NAME, TITLE, DESCRIPTION, INSTALLED_VERSION FROM keeko_application WHERE ID = :p0';
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
            /** @var ChildApplication $obj */
            $obj = new ChildApplication();
            $obj->hydrate($row);
            ApplicationTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildApplication|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ApplicationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ApplicationTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationTableMap::COL_CLASS_NAME, $className, $comparison);
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
     * @see       filterByPackage()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ApplicationTableMap::COL_INSTALLED_VERSION, $installedVersion, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Package object
     *
     * @param \keeko\core\model\Package|ObjectCollection $package The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByPackage($package, $comparison = null)
    {
        if ($package instanceof \keeko\core\model\Package) {
            return $this
                ->addUsingAlias(ApplicationTableMap::COL_ID, $package->getId(), $comparison);
        } elseif ($package instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationTableMap::COL_ID, $package->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPackage() only accepts arguments of type \keeko\core\model\Package or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Package relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
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
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\PackageQuery A secondary query class using the current class as primary query
     */
    public function usePackageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPackage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Package', '\keeko\core\model\PackageQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\ApplicationUri object
     *
     * @param \keeko\core\model\ApplicationUri|ObjectCollection $applicationUri  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByApplicationUri($applicationUri, $comparison = null)
    {
        if ($applicationUri instanceof \keeko\core\model\ApplicationUri) {
            return $this
                ->addUsingAlias(ApplicationTableMap::COL_ID, $applicationUri->getApplicationId(), $comparison);
        } elseif ($applicationUri instanceof ObjectCollection) {
            return $this
                ->useApplicationUriQuery()
                ->filterByPrimaryKeys($applicationUri->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByApplicationUri() only accepts arguments of type \keeko\core\model\ApplicationUri or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ApplicationUri relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
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
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ApplicationUriQuery A secondary query class using the current class as primary query
     */
    public function useApplicationUriQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinApplicationUri($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ApplicationUri', '\keeko\core\model\ApplicationUriQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildApplication $application Object to remove from the list of results
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function prune($application = null)
    {
        if ($application) {
            $this->addUsingAlias(ApplicationTableMap::COL_ID, $application->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the keeko_application table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ApplicationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ApplicationTableMap::clearInstancePool();
            ApplicationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ApplicationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ApplicationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ApplicationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ApplicationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ApplicationQuery
