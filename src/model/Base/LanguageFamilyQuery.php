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
use keeko\core\model\LanguageFamily as ChildLanguageFamily;
use keeko\core\model\LanguageFamilyQuery as ChildLanguageFamilyQuery;
use keeko\core\model\Map\LanguageFamilyTableMap;

/**
 * Base class that represents a query for the 'kk_language_family' table.
 *
 *
 *
 * @method     ChildLanguageFamilyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLanguageFamilyQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method     ChildLanguageFamilyQuery orderByAlpha3($order = Criteria::ASC) Order by the alpha_3 column
 * @method     ChildLanguageFamilyQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildLanguageFamilyQuery groupById() Group by the id column
 * @method     ChildLanguageFamilyQuery groupByParentId() Group by the parent_id column
 * @method     ChildLanguageFamilyQuery groupByAlpha3() Group by the alpha_3 column
 * @method     ChildLanguageFamilyQuery groupByName() Group by the name column
 *
 * @method     ChildLanguageFamilyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLanguageFamilyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLanguageFamilyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLanguageFamilyQuery leftJoinLanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Language relation
 * @method     ChildLanguageFamilyQuery rightJoinLanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Language relation
 * @method     ChildLanguageFamilyQuery innerJoinLanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the Language relation
 *
 * @method     \keeko\core\model\LanguageQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLanguageFamily findOne(ConnectionInterface $con = null) Return the first ChildLanguageFamily matching the query
 * @method     ChildLanguageFamily findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLanguageFamily matching the query, or a new ChildLanguageFamily object populated from the query conditions when no match is found
 *
 * @method     ChildLanguageFamily findOneById(int $id) Return the first ChildLanguageFamily filtered by the id column
 * @method     ChildLanguageFamily findOneByParentId(int $parent_id) Return the first ChildLanguageFamily filtered by the parent_id column
 * @method     ChildLanguageFamily findOneByAlpha3(string $alpha_3) Return the first ChildLanguageFamily filtered by the alpha_3 column
 * @method     ChildLanguageFamily findOneByName(string $name) Return the first ChildLanguageFamily filtered by the name column *

 * @method     ChildLanguageFamily requirePk($key, ConnectionInterface $con = null) Return the ChildLanguageFamily by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageFamily requireOne(ConnectionInterface $con = null) Return the first ChildLanguageFamily matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguageFamily requireOneById(int $id) Return the first ChildLanguageFamily filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageFamily requireOneByParentId(int $parent_id) Return the first ChildLanguageFamily filtered by the parent_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageFamily requireOneByAlpha3(string $alpha_3) Return the first ChildLanguageFamily filtered by the alpha_3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageFamily requireOneByName(string $name) Return the first ChildLanguageFamily filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguageFamily[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLanguageFamily objects based on current ModelCriteria
 * @method     ChildLanguageFamily[]|ObjectCollection findById(int $id) Return ChildLanguageFamily objects filtered by the id column
 * @method     ChildLanguageFamily[]|ObjectCollection findByParentId(int $parent_id) Return ChildLanguageFamily objects filtered by the parent_id column
 * @method     ChildLanguageFamily[]|ObjectCollection findByAlpha3(string $alpha_3) Return ChildLanguageFamily objects filtered by the alpha_3 column
 * @method     ChildLanguageFamily[]|ObjectCollection findByName(string $name) Return ChildLanguageFamily objects filtered by the name column
 * @method     ChildLanguageFamily[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LanguageFamilyQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\LanguageFamilyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\LanguageFamily', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLanguageFamilyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLanguageFamilyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLanguageFamilyQuery) {
            return $criteria;
        }
        $query = new ChildLanguageFamilyQuery();
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
     * @return ChildLanguageFamily|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LanguageFamilyTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LanguageFamilyTableMap::DATABASE_NAME);
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
     * @return ChildLanguageFamily A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `parent_id`, `alpha_3`, `name` FROM `kk_language_family` WHERE `id` = :p0';
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
            /** @var ChildLanguageFamily $obj */
            $obj = new ChildLanguageFamily();
            $obj->hydrate($row);
            LanguageFamilyTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildLanguageFamily|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLanguageFamilyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LanguageFamilyTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLanguageFamilyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LanguageFamilyTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLanguageFamilyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LanguageFamilyTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LanguageFamilyTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageFamilyTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the parent_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentId(1234); // WHERE parent_id = 1234
     * $query->filterByParentId(array(12, 34)); // WHERE parent_id IN (12, 34)
     * $query->filterByParentId(array('min' => 12)); // WHERE parent_id > 12
     * </code>
     *
     * @param     mixed $parentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageFamilyQuery The current query, for fluid interface
     */
    public function filterByParentId($parentId = null, $comparison = null)
    {
        if (is_array($parentId)) {
            $useMinMax = false;
            if (isset($parentId['min'])) {
                $this->addUsingAlias(LanguageFamilyTableMap::COL_PARENT_ID, $parentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentId['max'])) {
                $this->addUsingAlias(LanguageFamilyTableMap::COL_PARENT_ID, $parentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageFamilyTableMap::COL_PARENT_ID, $parentId, $comparison);
    }

    /**
     * Filter the query on the alpha_3 column
     *
     * Example usage:
     * <code>
     * $query->filterByAlpha3('fooValue');   // WHERE alpha_3 = 'fooValue'
     * $query->filterByAlpha3('%fooValue%'); // WHERE alpha_3 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alpha3 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageFamilyQuery The current query, for fluid interface
     */
    public function filterByAlpha3($alpha3 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alpha3)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alpha3)) {
                $alpha3 = str_replace('*', '%', $alpha3);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageFamilyTableMap::COL_ALPHA_3, $alpha3, $comparison);
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
     * @return $this|ChildLanguageFamilyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageFamilyTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Language object
     *
     * @param \keeko\core\model\Language|ObjectCollection $language the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageFamilyQuery The current query, for fluid interface
     */
    public function filterByLanguage($language, $comparison = null)
    {
        if ($language instanceof \keeko\core\model\Language) {
            return $this
                ->addUsingAlias(LanguageFamilyTableMap::COL_ID, $language->getFamilyId(), $comparison);
        } elseif ($language instanceof ObjectCollection) {
            return $this
                ->useLanguageQuery()
                ->filterByPrimaryKeys($language->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLanguage() only accepts arguments of type \keeko\core\model\Language or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Language relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageFamilyQuery The current query, for fluid interface
     */
    public function joinLanguage($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Language');

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
            $this->addJoinObject($join, 'Language');
        }

        return $this;
    }

    /**
     * Use the Language relation Language object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageQuery A secondary query class using the current class as primary query
     */
    public function useLanguageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLanguage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Language', '\keeko\core\model\LanguageQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLanguageFamily $languageFamily Object to remove from the list of results
     *
     * @return $this|ChildLanguageFamilyQuery The current query, for fluid interface
     */
    public function prune($languageFamily = null)
    {
        if ($languageFamily) {
            $this->addUsingAlias(LanguageFamilyTableMap::COL_ID, $languageFamily->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_language_family table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageFamilyTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LanguageFamilyTableMap::clearInstancePool();
            LanguageFamilyTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageFamilyTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LanguageFamilyTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LanguageFamilyTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LanguageFamilyTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LanguageFamilyQuery
