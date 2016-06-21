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
use keeko\core\model\LocalizationVariant as ChildLocalizationVariant;
use keeko\core\model\LocalizationVariantQuery as ChildLocalizationVariantQuery;
use keeko\core\model\Map\LocalizationVariantTableMap;

/**
 * Base class that represents a query for the 'kk_localization_variant' table.
 *
 *
 *
 * @method     ChildLocalizationVariantQuery orderByLocalizationId($order = Criteria::ASC) Order by the localization_id column
 * @method     ChildLocalizationVariantQuery orderByVariantId($order = Criteria::ASC) Order by the variant_id column
 *
 * @method     ChildLocalizationVariantQuery groupByLocalizationId() Group by the localization_id column
 * @method     ChildLocalizationVariantQuery groupByVariantId() Group by the variant_id column
 *
 * @method     ChildLocalizationVariantQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLocalizationVariantQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLocalizationVariantQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLocalizationVariantQuery leftJoinLocalization($relationAlias = null) Adds a LEFT JOIN clause to the query using the Localization relation
 * @method     ChildLocalizationVariantQuery rightJoinLocalization($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Localization relation
 * @method     ChildLocalizationVariantQuery innerJoinLocalization($relationAlias = null) Adds a INNER JOIN clause to the query using the Localization relation
 *
 * @method     ChildLocalizationVariantQuery leftJoinLanguageVariant($relationAlias = null) Adds a LEFT JOIN clause to the query using the LanguageVariant relation
 * @method     ChildLocalizationVariantQuery rightJoinLanguageVariant($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LanguageVariant relation
 * @method     ChildLocalizationVariantQuery innerJoinLanguageVariant($relationAlias = null) Adds a INNER JOIN clause to the query using the LanguageVariant relation
 *
 * @method     \keeko\core\model\LocalizationQuery|\keeko\core\model\LanguageVariantQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLocalizationVariant findOne(ConnectionInterface $con = null) Return the first ChildLocalizationVariant matching the query
 * @method     ChildLocalizationVariant findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLocalizationVariant matching the query, or a new ChildLocalizationVariant object populated from the query conditions when no match is found
 *
 * @method     ChildLocalizationVariant findOneByLocalizationId(int $localization_id) Return the first ChildLocalizationVariant filtered by the localization_id column
 * @method     ChildLocalizationVariant findOneByVariantId(int $variant_id) Return the first ChildLocalizationVariant filtered by the variant_id column *

 * @method     ChildLocalizationVariant requirePk($key, ConnectionInterface $con = null) Return the ChildLocalizationVariant by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalizationVariant requireOne(ConnectionInterface $con = null) Return the first ChildLocalizationVariant matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocalizationVariant requireOneByLocalizationId(int $localization_id) Return the first ChildLocalizationVariant filtered by the localization_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalizationVariant requireOneByVariantId(int $variant_id) Return the first ChildLocalizationVariant filtered by the variant_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocalizationVariant[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLocalizationVariant objects based on current ModelCriteria
 * @method     ChildLocalizationVariant[]|ObjectCollection findByLocalizationId(int $localization_id) Return ChildLocalizationVariant objects filtered by the localization_id column
 * @method     ChildLocalizationVariant[]|ObjectCollection findByVariantId(int $variant_id) Return ChildLocalizationVariant objects filtered by the variant_id column
 * @method     ChildLocalizationVariant[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LocalizationVariantQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\LocalizationVariantQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\LocalizationVariant', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLocalizationVariantQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLocalizationVariantQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLocalizationVariantQuery) {
            return $criteria;
        }
        $query = new ChildLocalizationVariantQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$localization_id, $variant_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLocalizationVariant|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LocalizationVariantTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LocalizationVariantTableMap::DATABASE_NAME);
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
     * @return ChildLocalizationVariant A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `localization_id`, `variant_id` FROM `kk_localization_variant` WHERE `localization_id` = :p0 AND `variant_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLocalizationVariant $obj */
            $obj = new ChildLocalizationVariant();
            $obj->hydrate($row);
            LocalizationVariantTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildLocalizationVariant|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(LocalizationVariantTableMap::COL_LOCALIZATION_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(LocalizationVariantTableMap::COL_VARIANT_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(LocalizationVariantTableMap::COL_LOCALIZATION_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(LocalizationVariantTableMap::COL_VARIANT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the localization_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLocalizationId(1234); // WHERE localization_id = 1234
     * $query->filterByLocalizationId(array(12, 34)); // WHERE localization_id IN (12, 34)
     * $query->filterByLocalizationId(array('min' => 12)); // WHERE localization_id > 12
     * </code>
     *
     * @see       filterByLocalization()
     *
     * @param     mixed $localizationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function filterByLocalizationId($localizationId = null, $comparison = null)
    {
        if (is_array($localizationId)) {
            $useMinMax = false;
            if (isset($localizationId['min'])) {
                $this->addUsingAlias(LocalizationVariantTableMap::COL_LOCALIZATION_ID, $localizationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($localizationId['max'])) {
                $this->addUsingAlias(LocalizationVariantTableMap::COL_LOCALIZATION_ID, $localizationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocalizationVariantTableMap::COL_LOCALIZATION_ID, $localizationId, $comparison);
    }

    /**
     * Filter the query on the variant_id column
     *
     * Example usage:
     * <code>
     * $query->filterByVariantId(1234); // WHERE variant_id = 1234
     * $query->filterByVariantId(array(12, 34)); // WHERE variant_id IN (12, 34)
     * $query->filterByVariantId(array('min' => 12)); // WHERE variant_id > 12
     * </code>
     *
     * @see       filterByLanguageVariant()
     *
     * @param     mixed $variantId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function filterByVariantId($variantId = null, $comparison = null)
    {
        if (is_array($variantId)) {
            $useMinMax = false;
            if (isset($variantId['min'])) {
                $this->addUsingAlias(LocalizationVariantTableMap::COL_VARIANT_ID, $variantId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($variantId['max'])) {
                $this->addUsingAlias(LocalizationVariantTableMap::COL_VARIANT_ID, $variantId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocalizationVariantTableMap::COL_VARIANT_ID, $variantId, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Localization object
     *
     * @param \keeko\core\model\Localization|ObjectCollection $localization The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function filterByLocalization($localization, $comparison = null)
    {
        if ($localization instanceof \keeko\core\model\Localization) {
            return $this
                ->addUsingAlias(LocalizationVariantTableMap::COL_LOCALIZATION_ID, $localization->getId(), $comparison);
        } elseif ($localization instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocalizationVariantTableMap::COL_LOCALIZATION_ID, $localization->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLocalization() only accepts arguments of type \keeko\core\model\Localization or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Localization relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function joinLocalization($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Localization');

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
            $this->addJoinObject($join, 'Localization');
        }

        return $this;
    }

    /**
     * Use the Localization relation Localization object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LocalizationQuery A secondary query class using the current class as primary query
     */
    public function useLocalizationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLocalization($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Localization', '\keeko\core\model\LocalizationQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\LanguageVariant object
     *
     * @param \keeko\core\model\LanguageVariant|ObjectCollection $languageVariant The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function filterByLanguageVariant($languageVariant, $comparison = null)
    {
        if ($languageVariant instanceof \keeko\core\model\LanguageVariant) {
            return $this
                ->addUsingAlias(LocalizationVariantTableMap::COL_VARIANT_ID, $languageVariant->getId(), $comparison);
        } elseif ($languageVariant instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocalizationVariantTableMap::COL_VARIANT_ID, $languageVariant->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLanguageVariant() only accepts arguments of type \keeko\core\model\LanguageVariant or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LanguageVariant relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function joinLanguageVariant($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LanguageVariant');

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
            $this->addJoinObject($join, 'LanguageVariant');
        }

        return $this;
    }

    /**
     * Use the LanguageVariant relation LanguageVariant object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageVariantQuery A secondary query class using the current class as primary query
     */
    public function useLanguageVariantQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLanguageVariant($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LanguageVariant', '\keeko\core\model\LanguageVariantQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLocalizationVariant $localizationVariant Object to remove from the list of results
     *
     * @return $this|ChildLocalizationVariantQuery The current query, for fluid interface
     */
    public function prune($localizationVariant = null)
    {
        if ($localizationVariant) {
            $this->addCond('pruneCond0', $this->getAliasedColName(LocalizationVariantTableMap::COL_LOCALIZATION_ID), $localizationVariant->getLocalizationId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(LocalizationVariantTableMap::COL_VARIANT_ID), $localizationVariant->getVariantId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_localization_variant table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocalizationVariantTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LocalizationVariantTableMap::clearInstancePool();
            LocalizationVariantTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LocalizationVariantTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LocalizationVariantTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LocalizationVariantTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LocalizationVariantTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LocalizationVariantQuery
