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
use keeko\core\model\LanguageVariant as ChildLanguageVariant;
use keeko\core\model\LanguageVariantQuery as ChildLanguageVariantQuery;
use keeko\core\model\Map\LanguageVariantTableMap;

/**
 * Base class that represents a query for the 'kk_language_variant' table.
 *
 *
 *
 * @method     ChildLanguageVariantQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLanguageVariantQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildLanguageVariantQuery orderBySubtag($order = Criteria::ASC) Order by the subtag column
 * @method     ChildLanguageVariantQuery orderByPrefixes($order = Criteria::ASC) Order by the prefixes column
 * @method     ChildLanguageVariantQuery orderByComment($order = Criteria::ASC) Order by the comment column
 *
 * @method     ChildLanguageVariantQuery groupById() Group by the id column
 * @method     ChildLanguageVariantQuery groupByName() Group by the name column
 * @method     ChildLanguageVariantQuery groupBySubtag() Group by the subtag column
 * @method     ChildLanguageVariantQuery groupByPrefixes() Group by the prefixes column
 * @method     ChildLanguageVariantQuery groupByComment() Group by the comment column
 *
 * @method     ChildLanguageVariantQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLanguageVariantQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLanguageVariantQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLanguageVariantQuery leftJoinLocalizationVariant($relationAlias = null) Adds a LEFT JOIN clause to the query using the LocalizationVariant relation
 * @method     ChildLanguageVariantQuery rightJoinLocalizationVariant($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LocalizationVariant relation
 * @method     ChildLanguageVariantQuery innerJoinLocalizationVariant($relationAlias = null) Adds a INNER JOIN clause to the query using the LocalizationVariant relation
 *
 * @method     \keeko\core\model\LocalizationVariantQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLanguageVariant findOne(ConnectionInterface $con = null) Return the first ChildLanguageVariant matching the query
 * @method     ChildLanguageVariant findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLanguageVariant matching the query, or a new ChildLanguageVariant object populated from the query conditions when no match is found
 *
 * @method     ChildLanguageVariant findOneById(int $id) Return the first ChildLanguageVariant filtered by the id column
 * @method     ChildLanguageVariant findOneByName(string $name) Return the first ChildLanguageVariant filtered by the name column
 * @method     ChildLanguageVariant findOneBySubtag(string $subtag) Return the first ChildLanguageVariant filtered by the subtag column
 * @method     ChildLanguageVariant findOneByPrefixes(string $prefixes) Return the first ChildLanguageVariant filtered by the prefixes column
 * @method     ChildLanguageVariant findOneByComment(string $comment) Return the first ChildLanguageVariant filtered by the comment column *

 * @method     ChildLanguageVariant requirePk($key, ConnectionInterface $con = null) Return the ChildLanguageVariant by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageVariant requireOne(ConnectionInterface $con = null) Return the first ChildLanguageVariant matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguageVariant requireOneById(int $id) Return the first ChildLanguageVariant filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageVariant requireOneByName(string $name) Return the first ChildLanguageVariant filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageVariant requireOneBySubtag(string $subtag) Return the first ChildLanguageVariant filtered by the subtag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageVariant requireOneByPrefixes(string $prefixes) Return the first ChildLanguageVariant filtered by the prefixes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageVariant requireOneByComment(string $comment) Return the first ChildLanguageVariant filtered by the comment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguageVariant[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLanguageVariant objects based on current ModelCriteria
 * @method     ChildLanguageVariant[]|ObjectCollection findById(int $id) Return ChildLanguageVariant objects filtered by the id column
 * @method     ChildLanguageVariant[]|ObjectCollection findByName(string $name) Return ChildLanguageVariant objects filtered by the name column
 * @method     ChildLanguageVariant[]|ObjectCollection findBySubtag(string $subtag) Return ChildLanguageVariant objects filtered by the subtag column
 * @method     ChildLanguageVariant[]|ObjectCollection findByPrefixes(string $prefixes) Return ChildLanguageVariant objects filtered by the prefixes column
 * @method     ChildLanguageVariant[]|ObjectCollection findByComment(string $comment) Return ChildLanguageVariant objects filtered by the comment column
 * @method     ChildLanguageVariant[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LanguageVariantQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\LanguageVariantQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\LanguageVariant', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLanguageVariantQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLanguageVariantQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLanguageVariantQuery) {
            return $criteria;
        }
        $query = new ChildLanguageVariantQuery();
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
     * @return ChildLanguageVariant|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LanguageVariantTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LanguageVariantTableMap::DATABASE_NAME);
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
     * @return ChildLanguageVariant A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `subtag`, `prefixes`, `comment` FROM `kk_language_variant` WHERE `id` = :p0';
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
            /** @var ChildLanguageVariant $obj */
            $obj = new ChildLanguageVariant();
            $obj->hydrate($row);
            LanguageVariantTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildLanguageVariant|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LanguageVariantTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LanguageVariantTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LanguageVariantTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LanguageVariantTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageVariantTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageVariantTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the subtag column
     *
     * Example usage:
     * <code>
     * $query->filterBySubtag('fooValue');   // WHERE subtag = 'fooValue'
     * $query->filterBySubtag('%fooValue%'); // WHERE subtag LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subtag The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function filterBySubtag($subtag = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subtag)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subtag)) {
                $subtag = str_replace('*', '%', $subtag);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageVariantTableMap::COL_SUBTAG, $subtag, $comparison);
    }

    /**
     * Filter the query on the prefixes column
     *
     * Example usage:
     * <code>
     * $query->filterByPrefixes('fooValue');   // WHERE prefixes = 'fooValue'
     * $query->filterByPrefixes('%fooValue%'); // WHERE prefixes LIKE '%fooValue%'
     * </code>
     *
     * @param     string $prefixes The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function filterByPrefixes($prefixes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($prefixes)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $prefixes)) {
                $prefixes = str_replace('*', '%', $prefixes);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageVariantTableMap::COL_PREFIXES, $prefixes, $comparison);
    }

    /**
     * Filter the query on the comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE comment = 'fooValue'
     * $query->filterByComment('%fooValue%'); // WHERE comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function filterByComment($comment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $comment)) {
                $comment = str_replace('*', '%', $comment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageVariantTableMap::COL_COMMENT, $comment, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\LocalizationVariant object
     *
     * @param \keeko\core\model\LocalizationVariant|ObjectCollection $localizationVariant the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function filterByLocalizationVariant($localizationVariant, $comparison = null)
    {
        if ($localizationVariant instanceof \keeko\core\model\LocalizationVariant) {
            return $this
                ->addUsingAlias(LanguageVariantTableMap::COL_ID, $localizationVariant->getVariantId(), $comparison);
        } elseif ($localizationVariant instanceof ObjectCollection) {
            return $this
                ->useLocalizationVariantQuery()
                ->filterByPrimaryKeys($localizationVariant->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLocalizationVariant() only accepts arguments of type \keeko\core\model\LocalizationVariant or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LocalizationVariant relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function joinLocalizationVariant($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LocalizationVariant');

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
            $this->addJoinObject($join, 'LocalizationVariant');
        }

        return $this;
    }

    /**
     * Use the LocalizationVariant relation LocalizationVariant object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LocalizationVariantQuery A secondary query class using the current class as primary query
     */
    public function useLocalizationVariantQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLocalizationVariant($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LocalizationVariant', '\keeko\core\model\LocalizationVariantQuery');
    }

    /**
     * Filter the query by a related Localization object
     * using the kk_localization_variant table as cross reference
     *
     * @param Localization $localization the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function filterByLocalization($localization, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useLocalizationVariantQuery()
            ->filterByLocalization($localization, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLanguageVariant $languageVariant Object to remove from the list of results
     *
     * @return $this|ChildLanguageVariantQuery The current query, for fluid interface
     */
    public function prune($languageVariant = null)
    {
        if ($languageVariant) {
            $this->addUsingAlias(LanguageVariantTableMap::COL_ID, $languageVariant->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_language_variant table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageVariantTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LanguageVariantTableMap::clearInstancePool();
            LanguageVariantTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageVariantTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LanguageVariantTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LanguageVariantTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LanguageVariantTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LanguageVariantQuery
