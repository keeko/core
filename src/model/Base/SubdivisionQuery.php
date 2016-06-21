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
use keeko\core\model\Subdivision as ChildSubdivision;
use keeko\core\model\SubdivisionQuery as ChildSubdivisionQuery;
use keeko\core\model\Map\SubdivisionTableMap;

/**
 * Base class that represents a query for the 'kk_subdivision' table.
 *
 *
 *
 * @method     ChildSubdivisionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSubdivisionQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildSubdivisionQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildSubdivisionQuery orderByNativeName($order = Criteria::ASC) Order by the native_name column
 * @method     ChildSubdivisionQuery orderByAltNames($order = Criteria::ASC) Order by the alt_names column
 * @method     ChildSubdivisionQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method     ChildSubdivisionQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method     ChildSubdivisionQuery orderByTypeId($order = Criteria::ASC) Order by the type_id column
 *
 * @method     ChildSubdivisionQuery groupById() Group by the id column
 * @method     ChildSubdivisionQuery groupByCode() Group by the code column
 * @method     ChildSubdivisionQuery groupByName() Group by the name column
 * @method     ChildSubdivisionQuery groupByNativeName() Group by the native_name column
 * @method     ChildSubdivisionQuery groupByAltNames() Group by the alt_names column
 * @method     ChildSubdivisionQuery groupByParentId() Group by the parent_id column
 * @method     ChildSubdivisionQuery groupByCountryId() Group by the country_id column
 * @method     ChildSubdivisionQuery groupByTypeId() Group by the type_id column
 *
 * @method     ChildSubdivisionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSubdivisionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSubdivisionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSubdivisionQuery leftJoinCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Country relation
 * @method     ChildSubdivisionQuery rightJoinCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Country relation
 * @method     ChildSubdivisionQuery innerJoinCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the Country relation
 *
 * @method     ChildSubdivisionQuery leftJoinType($relationAlias = null) Adds a LEFT JOIN clause to the query using the Type relation
 * @method     ChildSubdivisionQuery rightJoinType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Type relation
 * @method     ChildSubdivisionQuery innerJoinType($relationAlias = null) Adds a INNER JOIN clause to the query using the Type relation
 *
 * @method     \keeko\core\model\CountryQuery|\keeko\core\model\RegionTypeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSubdivision findOne(ConnectionInterface $con = null) Return the first ChildSubdivision matching the query
 * @method     ChildSubdivision findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSubdivision matching the query, or a new ChildSubdivision object populated from the query conditions when no match is found
 *
 * @method     ChildSubdivision findOneById(int $id) Return the first ChildSubdivision filtered by the id column
 * @method     ChildSubdivision findOneByCode(string $code) Return the first ChildSubdivision filtered by the code column
 * @method     ChildSubdivision findOneByName(string $name) Return the first ChildSubdivision filtered by the name column
 * @method     ChildSubdivision findOneByNativeName(string $native_name) Return the first ChildSubdivision filtered by the native_name column
 * @method     ChildSubdivision findOneByAltNames(string $alt_names) Return the first ChildSubdivision filtered by the alt_names column
 * @method     ChildSubdivision findOneByParentId(int $parent_id) Return the first ChildSubdivision filtered by the parent_id column
 * @method     ChildSubdivision findOneByCountryId(int $country_id) Return the first ChildSubdivision filtered by the country_id column
 * @method     ChildSubdivision findOneByTypeId(int $type_id) Return the first ChildSubdivision filtered by the type_id column *

 * @method     ChildSubdivision requirePk($key, ConnectionInterface $con = null) Return the ChildSubdivision by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOne(ConnectionInterface $con = null) Return the first ChildSubdivision matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubdivision requireOneById(int $id) Return the first ChildSubdivision filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByCode(string $code) Return the first ChildSubdivision filtered by the code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByName(string $name) Return the first ChildSubdivision filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByNativeName(string $native_name) Return the first ChildSubdivision filtered by the native_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByAltNames(string $alt_names) Return the first ChildSubdivision filtered by the alt_names column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByParentId(int $parent_id) Return the first ChildSubdivision filtered by the parent_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByCountryId(int $country_id) Return the first ChildSubdivision filtered by the country_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByTypeId(int $type_id) Return the first ChildSubdivision filtered by the type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubdivision[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSubdivision objects based on current ModelCriteria
 * @method     ChildSubdivision[]|ObjectCollection findById(int $id) Return ChildSubdivision objects filtered by the id column
 * @method     ChildSubdivision[]|ObjectCollection findByCode(string $code) Return ChildSubdivision objects filtered by the code column
 * @method     ChildSubdivision[]|ObjectCollection findByName(string $name) Return ChildSubdivision objects filtered by the name column
 * @method     ChildSubdivision[]|ObjectCollection findByNativeName(string $native_name) Return ChildSubdivision objects filtered by the native_name column
 * @method     ChildSubdivision[]|ObjectCollection findByAltNames(string $alt_names) Return ChildSubdivision objects filtered by the alt_names column
 * @method     ChildSubdivision[]|ObjectCollection findByParentId(int $parent_id) Return ChildSubdivision objects filtered by the parent_id column
 * @method     ChildSubdivision[]|ObjectCollection findByCountryId(int $country_id) Return ChildSubdivision objects filtered by the country_id column
 * @method     ChildSubdivision[]|ObjectCollection findByTypeId(int $type_id) Return ChildSubdivision objects filtered by the type_id column
 * @method     ChildSubdivision[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SubdivisionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\SubdivisionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Subdivision', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSubdivisionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSubdivisionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSubdivisionQuery) {
            return $criteria;
        }
        $query = new ChildSubdivisionQuery();
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
     * @return ChildSubdivision|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SubdivisionTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SubdivisionTableMap::DATABASE_NAME);
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
     * @return ChildSubdivision A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `code`, `name`, `native_name`, `alt_names`, `parent_id`, `country_id`, `type_id` FROM `kk_subdivision` WHERE `id` = :p0';
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
            /** @var ChildSubdivision $obj */
            $obj = new ChildSubdivision();
            $obj->hydrate($row);
            SubdivisionTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSubdivision|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SubdivisionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SubdivisionTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_CODE, $code, $comparison);
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
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SubdivisionTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the native_name column
     *
     * Example usage:
     * <code>
     * $query->filterByNativeName('fooValue');   // WHERE native_name = 'fooValue'
     * $query->filterByNativeName('%fooValue%'); // WHERE native_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nativeName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByNativeName($nativeName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nativeName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nativeName)) {
                $nativeName = str_replace('*', '%', $nativeName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_NATIVE_NAME, $nativeName, $comparison);
    }

    /**
     * Filter the query on the alt_names column
     *
     * Example usage:
     * <code>
     * $query->filterByAltNames('fooValue');   // WHERE alt_names = 'fooValue'
     * $query->filterByAltNames('%fooValue%'); // WHERE alt_names LIKE '%fooValue%'
     * </code>
     *
     * @param     string $altNames The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByAltNames($altNames = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($altNames)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $altNames)) {
                $altNames = str_replace('*', '%', $altNames);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_ALT_NAMES, $altNames, $comparison);
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
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByParentId($parentId = null, $comparison = null)
    {
        if (is_array($parentId)) {
            $useMinMax = false;
            if (isset($parentId['min'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_PARENT_ID, $parentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentId['max'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_PARENT_ID, $parentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_PARENT_ID, $parentId, $comparison);
    }

    /**
     * Filter the query on the country_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCountryId(1234); // WHERE country_id = 1234
     * $query->filterByCountryId(array(12, 34)); // WHERE country_id IN (12, 34)
     * $query->filterByCountryId(array('min' => 12)); // WHERE country_id > 12
     * </code>
     *
     * @see       filterByCountry()
     *
     * @param     mixed $countryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ID, $countryId, $comparison);
    }

    /**
     * Filter the query on the type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTypeId(1234); // WHERE type_id = 1234
     * $query->filterByTypeId(array(12, 34)); // WHERE type_id IN (12, 34)
     * $query->filterByTypeId(array('min' => 12)); // WHERE type_id > 12
     * </code>
     *
     * @see       filterByType()
     *
     * @param     mixed $typeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByTypeId($typeId = null, $comparison = null)
    {
        if (is_array($typeId)) {
            $useMinMax = false;
            if (isset($typeId['min'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_TYPE_ID, $typeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($typeId['max'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_TYPE_ID, $typeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_TYPE_ID, $typeId, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Country object
     *
     * @param \keeko\core\model\Country|ObjectCollection $country The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByCountry($country, $comparison = null)
    {
        if ($country instanceof \keeko\core\model\Country) {
            return $this
                ->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ID, $country->getId(), $comparison);
        } elseif ($country instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ID, $country->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
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
     * Filter the query by a related \keeko\core\model\RegionType object
     *
     * @param \keeko\core\model\RegionType|ObjectCollection $regionType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByType($regionType, $comparison = null)
    {
        if ($regionType instanceof \keeko\core\model\RegionType) {
            return $this
                ->addUsingAlias(SubdivisionTableMap::COL_TYPE_ID, $regionType->getId(), $comparison);
        } elseif ($regionType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubdivisionTableMap::COL_TYPE_ID, $regionType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByType() only accepts arguments of type \keeko\core\model\RegionType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Type relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function joinType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Type');

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
            $this->addJoinObject($join, 'Type');
        }

        return $this;
    }

    /**
     * Use the Type relation RegionType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\RegionTypeQuery A secondary query class using the current class as primary query
     */
    public function useTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Type', '\keeko\core\model\RegionTypeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSubdivision $subdivision Object to remove from the list of results
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function prune($subdivision = null)
    {
        if ($subdivision) {
            $this->addUsingAlias(SubdivisionTableMap::COL_ID, $subdivision->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

} // SubdivisionQuery
