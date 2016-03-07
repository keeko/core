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
use keeko\core\model\RegionType as ChildRegionType;
use keeko\core\model\RegionTypeQuery as ChildRegionTypeQuery;
use keeko\core\model\Map\RegionTypeTableMap;

/**
 * Base class that represents a query for the 'kk_region_type' table.
 *
 *
 *
 * @method     ChildRegionTypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildRegionTypeQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildRegionTypeQuery orderByAreaId($order = Criteria::ASC) Order by the area_id column
 *
 * @method     ChildRegionTypeQuery groupById() Group by the id column
 * @method     ChildRegionTypeQuery groupByName() Group by the name column
 * @method     ChildRegionTypeQuery groupByAreaId() Group by the area_id column
 *
 * @method     ChildRegionTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRegionTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRegionTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRegionTypeQuery leftJoinRegionArea($relationAlias = null) Adds a LEFT JOIN clause to the query using the RegionArea relation
 * @method     ChildRegionTypeQuery rightJoinRegionArea($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RegionArea relation
 * @method     ChildRegionTypeQuery innerJoinRegionArea($relationAlias = null) Adds a INNER JOIN clause to the query using the RegionArea relation
 *
 * @method     ChildRegionTypeQuery leftJoinCountryRelatedByTypeId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CountryRelatedByTypeId relation
 * @method     ChildRegionTypeQuery rightJoinCountryRelatedByTypeId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CountryRelatedByTypeId relation
 * @method     ChildRegionTypeQuery innerJoinCountryRelatedByTypeId($relationAlias = null) Adds a INNER JOIN clause to the query using the CountryRelatedByTypeId relation
 *
 * @method     ChildRegionTypeQuery leftJoinCountryRelatedBySubtypeId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CountryRelatedBySubtypeId relation
 * @method     ChildRegionTypeQuery rightJoinCountryRelatedBySubtypeId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CountryRelatedBySubtypeId relation
 * @method     ChildRegionTypeQuery innerJoinCountryRelatedBySubtypeId($relationAlias = null) Adds a INNER JOIN clause to the query using the CountryRelatedBySubtypeId relation
 *
 * @method     ChildRegionTypeQuery leftJoinSubdivision($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subdivision relation
 * @method     ChildRegionTypeQuery rightJoinSubdivision($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subdivision relation
 * @method     ChildRegionTypeQuery innerJoinSubdivision($relationAlias = null) Adds a INNER JOIN clause to the query using the Subdivision relation
 *
 * @method     \keeko\core\model\RegionAreaQuery|\keeko\core\model\CountryQuery|\keeko\core\model\SubdivisionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildRegionType findOne(ConnectionInterface $con = null) Return the first ChildRegionType matching the query
 * @method     ChildRegionType findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRegionType matching the query, or a new ChildRegionType object populated from the query conditions when no match is found
 *
 * @method     ChildRegionType findOneById(int $id) Return the first ChildRegionType filtered by the id column
 * @method     ChildRegionType findOneByName(string $name) Return the first ChildRegionType filtered by the name column
 * @method     ChildRegionType findOneByAreaId(int $area_id) Return the first ChildRegionType filtered by the area_id column *

 * @method     ChildRegionType requirePk($key, ConnectionInterface $con = null) Return the ChildRegionType by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegionType requireOne(ConnectionInterface $con = null) Return the first ChildRegionType matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRegionType requireOneById(int $id) Return the first ChildRegionType filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegionType requireOneByName(string $name) Return the first ChildRegionType filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegionType requireOneByAreaId(int $area_id) Return the first ChildRegionType filtered by the area_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRegionType[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildRegionType objects based on current ModelCriteria
 * @method     ChildRegionType[]|ObjectCollection findById(int $id) Return ChildRegionType objects filtered by the id column
 * @method     ChildRegionType[]|ObjectCollection findByName(string $name) Return ChildRegionType objects filtered by the name column
 * @method     ChildRegionType[]|ObjectCollection findByAreaId(int $area_id) Return ChildRegionType objects filtered by the area_id column
 * @method     ChildRegionType[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class RegionTypeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\RegionTypeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\RegionType', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRegionTypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRegionTypeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildRegionTypeQuery) {
            return $criteria;
        }
        $query = new ChildRegionTypeQuery();
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
     * @return ChildRegionType|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RegionTypeTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RegionTypeTableMap::DATABASE_NAME);
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
     * @return ChildRegionType A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `area_id` FROM `kk_region_type` WHERE `id` = :p0';
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
            /** @var ChildRegionType $obj */
            $obj = new ChildRegionType();
            $obj->hydrate($row);
            RegionTypeTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildRegionType|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RegionTypeTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RegionTypeTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RegionTypeTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RegionTypeTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RegionTypeTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(RegionTypeTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the area_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAreaId(1234); // WHERE area_id = 1234
     * $query->filterByAreaId(array(12, 34)); // WHERE area_id IN (12, 34)
     * $query->filterByAreaId(array('min' => 12)); // WHERE area_id > 12
     * </code>
     *
     * @see       filterByRegionArea()
     *
     * @param     mixed $areaId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function filterByAreaId($areaId = null, $comparison = null)
    {
        if (is_array($areaId)) {
            $useMinMax = false;
            if (isset($areaId['min'])) {
                $this->addUsingAlias(RegionTypeTableMap::COL_AREA_ID, $areaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($areaId['max'])) {
                $this->addUsingAlias(RegionTypeTableMap::COL_AREA_ID, $areaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RegionTypeTableMap::COL_AREA_ID, $areaId, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\RegionArea object
     *
     * @param \keeko\core\model\RegionArea|ObjectCollection $regionArea The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildRegionTypeQuery The current query, for fluid interface
     */
    public function filterByRegionArea($regionArea, $comparison = null)
    {
        if ($regionArea instanceof \keeko\core\model\RegionArea) {
            return $this
                ->addUsingAlias(RegionTypeTableMap::COL_AREA_ID, $regionArea->getId(), $comparison);
        } elseif ($regionArea instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RegionTypeTableMap::COL_AREA_ID, $regionArea->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRegionArea() only accepts arguments of type \keeko\core\model\RegionArea or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RegionArea relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function joinRegionArea($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RegionArea');

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
            $this->addJoinObject($join, 'RegionArea');
        }

        return $this;
    }

    /**
     * Use the RegionArea relation RegionArea object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\RegionAreaQuery A secondary query class using the current class as primary query
     */
    public function useRegionAreaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRegionArea($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RegionArea', '\keeko\core\model\RegionAreaQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Country object
     *
     * @param \keeko\core\model\Country|ObjectCollection $country the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRegionTypeQuery The current query, for fluid interface
     */
    public function filterByCountryRelatedByTypeId($country, $comparison = null)
    {
        if ($country instanceof \keeko\core\model\Country) {
            return $this
                ->addUsingAlias(RegionTypeTableMap::COL_ID, $country->getTypeId(), $comparison);
        } elseif ($country instanceof ObjectCollection) {
            return $this
                ->useCountryRelatedByTypeIdQuery()
                ->filterByPrimaryKeys($country->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCountryRelatedByTypeId() only accepts arguments of type \keeko\core\model\Country or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CountryRelatedByTypeId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function joinCountryRelatedByTypeId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CountryRelatedByTypeId');

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
            $this->addJoinObject($join, 'CountryRelatedByTypeId');
        }

        return $this;
    }

    /**
     * Use the CountryRelatedByTypeId relation Country object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\CountryQuery A secondary query class using the current class as primary query
     */
    public function useCountryRelatedByTypeIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCountryRelatedByTypeId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CountryRelatedByTypeId', '\keeko\core\model\CountryQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Country object
     *
     * @param \keeko\core\model\Country|ObjectCollection $country the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRegionTypeQuery The current query, for fluid interface
     */
    public function filterByCountryRelatedBySubtypeId($country, $comparison = null)
    {
        if ($country instanceof \keeko\core\model\Country) {
            return $this
                ->addUsingAlias(RegionTypeTableMap::COL_ID, $country->getSubtypeId(), $comparison);
        } elseif ($country instanceof ObjectCollection) {
            return $this
                ->useCountryRelatedBySubtypeIdQuery()
                ->filterByPrimaryKeys($country->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCountryRelatedBySubtypeId() only accepts arguments of type \keeko\core\model\Country or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CountryRelatedBySubtypeId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function joinCountryRelatedBySubtypeId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CountryRelatedBySubtypeId');

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
            $this->addJoinObject($join, 'CountryRelatedBySubtypeId');
        }

        return $this;
    }

    /**
     * Use the CountryRelatedBySubtypeId relation Country object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\CountryQuery A secondary query class using the current class as primary query
     */
    public function useCountryRelatedBySubtypeIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCountryRelatedBySubtypeId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CountryRelatedBySubtypeId', '\keeko\core\model\CountryQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Subdivision object
     *
     * @param \keeko\core\model\Subdivision|ObjectCollection $subdivision the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRegionTypeQuery The current query, for fluid interface
     */
    public function filterBySubdivision($subdivision, $comparison = null)
    {
        if ($subdivision instanceof \keeko\core\model\Subdivision) {
            return $this
                ->addUsingAlias(RegionTypeTableMap::COL_ID, $subdivision->getTypeId(), $comparison);
        } elseif ($subdivision instanceof ObjectCollection) {
            return $this
                ->useSubdivisionQuery()
                ->filterByPrimaryKeys($subdivision->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySubdivision() only accepts arguments of type \keeko\core\model\Subdivision or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Subdivision relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function joinSubdivision($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Subdivision');

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
            $this->addJoinObject($join, 'Subdivision');
        }

        return $this;
    }

    /**
     * Use the Subdivision relation Subdivision object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\SubdivisionQuery A secondary query class using the current class as primary query
     */
    public function useSubdivisionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSubdivision($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subdivision', '\keeko\core\model\SubdivisionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRegionType $regionType Object to remove from the list of results
     *
     * @return $this|ChildRegionTypeQuery The current query, for fluid interface
     */
    public function prune($regionType = null)
    {
        if ($regionType) {
            $this->addUsingAlias(RegionTypeTableMap::COL_ID, $regionType->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

} // RegionTypeQuery
