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
use keeko\core\model\Territory as ChildTerritory;
use keeko\core\model\TerritoryQuery as ChildTerritoryQuery;
use keeko\core\model\Map\TerritoryTableMap;

/**
 * Base class that represents a query for the 'kk_territory' table.
 *
 *
 *
 * @method     ChildTerritoryQuery orderByIsoNr($order = Criteria::ASC) Order by the iso_nr column
 * @method     ChildTerritoryQuery orderByParentIsoNr($order = Criteria::ASC) Order by the parent_iso_nr column
 * @method     ChildTerritoryQuery orderByNameEn($order = Criteria::ASC) Order by the name_en column
 *
 * @method     ChildTerritoryQuery groupByIsoNr() Group by the iso_nr column
 * @method     ChildTerritoryQuery groupByParentIsoNr() Group by the parent_iso_nr column
 * @method     ChildTerritoryQuery groupByNameEn() Group by the name_en column
 *
 * @method     ChildTerritoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTerritoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTerritoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTerritoryQuery leftJoinCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Country relation
 * @method     ChildTerritoryQuery rightJoinCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Country relation
 * @method     ChildTerritoryQuery innerJoinCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the Country relation
 *
 * @method     \keeko\core\model\CountryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTerritory findOne(ConnectionInterface $con = null) Return the first ChildTerritory matching the query
 * @method     ChildTerritory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTerritory matching the query, or a new ChildTerritory object populated from the query conditions when no match is found
 *
 * @method     ChildTerritory findOneByIsoNr(int $iso_nr) Return the first ChildTerritory filtered by the iso_nr column
 * @method     ChildTerritory findOneByParentIsoNr(int $parent_iso_nr) Return the first ChildTerritory filtered by the parent_iso_nr column
 * @method     ChildTerritory findOneByNameEn(string $name_en) Return the first ChildTerritory filtered by the name_en column
 *
 * @method     ChildTerritory[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTerritory objects based on current ModelCriteria
 * @method     ChildTerritory[]|ObjectCollection findByIsoNr(int $iso_nr) Return ChildTerritory objects filtered by the iso_nr column
 * @method     ChildTerritory[]|ObjectCollection findByParentIsoNr(int $parent_iso_nr) Return ChildTerritory objects filtered by the parent_iso_nr column
 * @method     ChildTerritory[]|ObjectCollection findByNameEn(string $name_en) Return ChildTerritory objects filtered by the name_en column
 * @method     ChildTerritory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TerritoryQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \keeko\core\model\Base\TerritoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Territory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTerritoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTerritoryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTerritoryQuery) {
            return $criteria;
        }
        $query = new ChildTerritoryQuery();
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
     * @return ChildTerritory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TerritoryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TerritoryTableMap::DATABASE_NAME);
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
     * @return ChildTerritory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `iso_nr`, `parent_iso_nr`, `name_en` FROM `kk_territory` WHERE `iso_nr` = :p0';
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
            /** @var ChildTerritory $obj */
            $obj = new ChildTerritory();
            $obj->hydrate($row);
            TerritoryTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildTerritory|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTerritoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TerritoryTableMap::COL_ISO_NR, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTerritoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TerritoryTableMap::COL_ISO_NR, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the iso_nr column
     *
     * Example usage:
     * <code>
     * $query->filterByIsoNr(1234); // WHERE iso_nr = 1234
     * $query->filterByIsoNr(array(12, 34)); // WHERE iso_nr IN (12, 34)
     * $query->filterByIsoNr(array('min' => 12)); // WHERE iso_nr > 12
     * </code>
     *
     * @param     mixed $isoNr The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTerritoryQuery The current query, for fluid interface
     */
    public function filterByIsoNr($isoNr = null, $comparison = null)
    {
        if (is_array($isoNr)) {
            $useMinMax = false;
            if (isset($isoNr['min'])) {
                $this->addUsingAlias(TerritoryTableMap::COL_ISO_NR, $isoNr['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($isoNr['max'])) {
                $this->addUsingAlias(TerritoryTableMap::COL_ISO_NR, $isoNr['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TerritoryTableMap::COL_ISO_NR, $isoNr, $comparison);
    }

    /**
     * Filter the query on the parent_iso_nr column
     *
     * Example usage:
     * <code>
     * $query->filterByParentIsoNr(1234); // WHERE parent_iso_nr = 1234
     * $query->filterByParentIsoNr(array(12, 34)); // WHERE parent_iso_nr IN (12, 34)
     * $query->filterByParentIsoNr(array('min' => 12)); // WHERE parent_iso_nr > 12
     * </code>
     *
     * @param     mixed $parentIsoNr The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTerritoryQuery The current query, for fluid interface
     */
    public function filterByParentIsoNr($parentIsoNr = null, $comparison = null)
    {
        if (is_array($parentIsoNr)) {
            $useMinMax = false;
            if (isset($parentIsoNr['min'])) {
                $this->addUsingAlias(TerritoryTableMap::COL_PARENT_ISO_NR, $parentIsoNr['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentIsoNr['max'])) {
                $this->addUsingAlias(TerritoryTableMap::COL_PARENT_ISO_NR, $parentIsoNr['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TerritoryTableMap::COL_PARENT_ISO_NR, $parentIsoNr, $comparison);
    }

    /**
     * Filter the query on the name_en column
     *
     * Example usage:
     * <code>
     * $query->filterByNameEn('fooValue');   // WHERE name_en = 'fooValue'
     * $query->filterByNameEn('%fooValue%'); // WHERE name_en LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameEn The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTerritoryQuery The current query, for fluid interface
     */
    public function filterByNameEn($nameEn = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameEn)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameEn)) {
                $nameEn = str_replace('*', '%', $nameEn);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TerritoryTableMap::COL_NAME_EN, $nameEn, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Country object
     *
     * @param \keeko\core\model\Country|ObjectCollection $country  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTerritoryQuery The current query, for fluid interface
     */
    public function filterByCountry($country, $comparison = null)
    {
        if ($country instanceof \keeko\core\model\Country) {
            return $this
                ->addUsingAlias(TerritoryTableMap::COL_ISO_NR, $country->getTerritoryIsoNr(), $comparison);
        } elseif ($country instanceof ObjectCollection) {
            return $this
                ->useCountryQuery()
                ->filterByPrimaryKeys($country->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCountry() only accepts arguments of type \keeko\core\model\Country or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Country relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTerritoryQuery The current query, for fluid interface
     */
    public function joinCountry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Country');

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
            $this->addJoinObject($join, 'Country');
        }

        return $this;
    }

    /**
     * Use the Country relation Country object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\CountryQuery A secondary query class using the current class as primary query
     */
    public function useCountryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCountry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Country', '\keeko\core\model\CountryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTerritory $territory Object to remove from the list of results
     *
     * @return $this|ChildTerritoryQuery The current query, for fluid interface
     */
    public function prune($territory = null)
    {
        if ($territory) {
            $this->addUsingAlias(TerritoryTableMap::COL_ISO_NR, $territory->getIsoNr(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_territory table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TerritoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TerritoryTableMap::clearInstancePool();
            TerritoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TerritoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TerritoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TerritoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TerritoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TerritoryQuery
