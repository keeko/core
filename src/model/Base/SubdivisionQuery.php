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
 * @method     ChildSubdivisionQuery orderByIso($order = Criteria::ASC) Order by the iso column
 * @method     ChildSubdivisionQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildSubdivisionQuery orderByLocalName($order = Criteria::ASC) Order by the local_name column
 * @method     ChildSubdivisionQuery orderByEnName($order = Criteria::ASC) Order by the en_name column
 * @method     ChildSubdivisionQuery orderByAltNames($order = Criteria::ASC) Order by the alt_names column
 * @method     ChildSubdivisionQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method     ChildSubdivisionQuery orderByCountryIsoNr($order = Criteria::ASC) Order by the country_iso_nr column
 * @method     ChildSubdivisionQuery orderBySubdivisionTypeId($order = Criteria::ASC) Order by the subdivision_type_id column
 *
 * @method     ChildSubdivisionQuery groupById() Group by the id column
 * @method     ChildSubdivisionQuery groupByIso() Group by the iso column
 * @method     ChildSubdivisionQuery groupByName() Group by the name column
 * @method     ChildSubdivisionQuery groupByLocalName() Group by the local_name column
 * @method     ChildSubdivisionQuery groupByEnName() Group by the en_name column
 * @method     ChildSubdivisionQuery groupByAltNames() Group by the alt_names column
 * @method     ChildSubdivisionQuery groupByParentId() Group by the parent_id column
 * @method     ChildSubdivisionQuery groupByCountryIsoNr() Group by the country_iso_nr column
 * @method     ChildSubdivisionQuery groupBySubdivisionTypeId() Group by the subdivision_type_id column
 *
 * @method     ChildSubdivisionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSubdivisionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSubdivisionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSubdivisionQuery leftJoinCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Country relation
 * @method     ChildSubdivisionQuery rightJoinCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Country relation
 * @method     ChildSubdivisionQuery innerJoinCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the Country relation
 *
 * @method     ChildSubdivisionQuery leftJoinSubdivisionType($relationAlias = null) Adds a LEFT JOIN clause to the query using the SubdivisionType relation
 * @method     ChildSubdivisionQuery rightJoinSubdivisionType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SubdivisionType relation
 * @method     ChildSubdivisionQuery innerJoinSubdivisionType($relationAlias = null) Adds a INNER JOIN clause to the query using the SubdivisionType relation
 *
 * @method     \keeko\core\model\CountryQuery|\keeko\core\model\SubdivisionTypeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSubdivision findOne(ConnectionInterface $con = null) Return the first ChildSubdivision matching the query
 * @method     ChildSubdivision findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSubdivision matching the query, or a new ChildSubdivision object populated from the query conditions when no match is found
 *
 * @method     ChildSubdivision findOneById(int $id) Return the first ChildSubdivision filtered by the id column
 * @method     ChildSubdivision findOneByIso(string $iso) Return the first ChildSubdivision filtered by the iso column
 * @method     ChildSubdivision findOneByName(string $name) Return the first ChildSubdivision filtered by the name column
 * @method     ChildSubdivision findOneByLocalName(string $local_name) Return the first ChildSubdivision filtered by the local_name column
 * @method     ChildSubdivision findOneByEnName(string $en_name) Return the first ChildSubdivision filtered by the en_name column
 * @method     ChildSubdivision findOneByAltNames(string $alt_names) Return the first ChildSubdivision filtered by the alt_names column
 * @method     ChildSubdivision findOneByParentId(int $parent_id) Return the first ChildSubdivision filtered by the parent_id column
 * @method     ChildSubdivision findOneByCountryIsoNr(int $country_iso_nr) Return the first ChildSubdivision filtered by the country_iso_nr column
 * @method     ChildSubdivision findOneBySubdivisionTypeId(int $subdivision_type_id) Return the first ChildSubdivision filtered by the subdivision_type_id column *

 * @method     ChildSubdivision requirePk($key, ConnectionInterface $con = null) Return the ChildSubdivision by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOne(ConnectionInterface $con = null) Return the first ChildSubdivision matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubdivision requireOneById(int $id) Return the first ChildSubdivision filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByIso(string $iso) Return the first ChildSubdivision filtered by the iso column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByName(string $name) Return the first ChildSubdivision filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByLocalName(string $local_name) Return the first ChildSubdivision filtered by the local_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByEnName(string $en_name) Return the first ChildSubdivision filtered by the en_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByAltNames(string $alt_names) Return the first ChildSubdivision filtered by the alt_names column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByParentId(int $parent_id) Return the first ChildSubdivision filtered by the parent_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneByCountryIsoNr(int $country_iso_nr) Return the first ChildSubdivision filtered by the country_iso_nr column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubdivision requireOneBySubdivisionTypeId(int $subdivision_type_id) Return the first ChildSubdivision filtered by the subdivision_type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubdivision[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSubdivision objects based on current ModelCriteria
 * @method     ChildSubdivision[]|ObjectCollection findById(int $id) Return ChildSubdivision objects filtered by the id column
 * @method     ChildSubdivision[]|ObjectCollection findByIso(string $iso) Return ChildSubdivision objects filtered by the iso column
 * @method     ChildSubdivision[]|ObjectCollection findByName(string $name) Return ChildSubdivision objects filtered by the name column
 * @method     ChildSubdivision[]|ObjectCollection findByLocalName(string $local_name) Return ChildSubdivision objects filtered by the local_name column
 * @method     ChildSubdivision[]|ObjectCollection findByEnName(string $en_name) Return ChildSubdivision objects filtered by the en_name column
 * @method     ChildSubdivision[]|ObjectCollection findByAltNames(string $alt_names) Return ChildSubdivision objects filtered by the alt_names column
 * @method     ChildSubdivision[]|ObjectCollection findByParentId(int $parent_id) Return ChildSubdivision objects filtered by the parent_id column
 * @method     ChildSubdivision[]|ObjectCollection findByCountryIsoNr(int $country_iso_nr) Return ChildSubdivision objects filtered by the country_iso_nr column
 * @method     ChildSubdivision[]|ObjectCollection findBySubdivisionTypeId(int $subdivision_type_id) Return ChildSubdivision objects filtered by the subdivision_type_id column
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
        $sql = 'SELECT `id`, `iso`, `name`, `local_name`, `en_name`, `alt_names`, `parent_id`, `country_iso_nr`, `subdivision_type_id` FROM `kk_subdivision` WHERE `id` = :p0';
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
     * Filter the query on the iso column
     *
     * Example usage:
     * <code>
     * $query->filterByIso('fooValue');   // WHERE iso = 'fooValue'
     * $query->filterByIso('%fooValue%'); // WHERE iso LIKE '%fooValue%'
     * </code>
     *
     * @param     string $iso The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByIso($iso = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($iso)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $iso)) {
                $iso = str_replace('*', '%', $iso);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_ISO, $iso, $comparison);
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
     * Filter the query on the local_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLocalName('fooValue');   // WHERE local_name = 'fooValue'
     * $query->filterByLocalName('%fooValue%'); // WHERE local_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $localName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByLocalName($localName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($localName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $localName)) {
                $localName = str_replace('*', '%', $localName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_LOCAL_NAME, $localName, $comparison);
    }

    /**
     * Filter the query on the en_name column
     *
     * Example usage:
     * <code>
     * $query->filterByEnName('fooValue');   // WHERE en_name = 'fooValue'
     * $query->filterByEnName('%fooValue%'); // WHERE en_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $enName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByEnName($enName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($enName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $enName)) {
                $enName = str_replace('*', '%', $enName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_EN_NAME, $enName, $comparison);
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
     * Filter the query on the country_iso_nr column
     *
     * Example usage:
     * <code>
     * $query->filterByCountryIsoNr(1234); // WHERE country_iso_nr = 1234
     * $query->filterByCountryIsoNr(array(12, 34)); // WHERE country_iso_nr IN (12, 34)
     * $query->filterByCountryIsoNr(array('min' => 12)); // WHERE country_iso_nr > 12
     * </code>
     *
     * @see       filterByCountry()
     *
     * @param     mixed $countryIsoNr The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterByCountryIsoNr($countryIsoNr = null, $comparison = null)
    {
        if (is_array($countryIsoNr)) {
            $useMinMax = false;
            if (isset($countryIsoNr['min'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ISO_NR, $countryIsoNr['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryIsoNr['max'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ISO_NR, $countryIsoNr['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ISO_NR, $countryIsoNr, $comparison);
    }

    /**
     * Filter the query on the subdivision_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubdivisionTypeId(1234); // WHERE subdivision_type_id = 1234
     * $query->filterBySubdivisionTypeId(array(12, 34)); // WHERE subdivision_type_id IN (12, 34)
     * $query->filterBySubdivisionTypeId(array('min' => 12)); // WHERE subdivision_type_id > 12
     * </code>
     *
     * @see       filterBySubdivisionType()
     *
     * @param     mixed $subdivisionTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterBySubdivisionTypeId($subdivisionTypeId = null, $comparison = null)
    {
        if (is_array($subdivisionTypeId)) {
            $useMinMax = false;
            if (isset($subdivisionTypeId['min'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID, $subdivisionTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subdivisionTypeId['max'])) {
                $this->addUsingAlias(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID, $subdivisionTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID, $subdivisionTypeId, $comparison);
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
                ->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ISO_NR, $country->getIsoNr(), $comparison);
        } elseif ($country instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubdivisionTableMap::COL_COUNTRY_ISO_NR, $country->toKeyValue('PrimaryKey', 'IsoNr'), $comparison);
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
     * Filter the query by a related \keeko\core\model\SubdivisionType object
     *
     * @param \keeko\core\model\SubdivisionType|ObjectCollection $subdivisionType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubdivisionQuery The current query, for fluid interface
     */
    public function filterBySubdivisionType($subdivisionType, $comparison = null)
    {
        if ($subdivisionType instanceof \keeko\core\model\SubdivisionType) {
            return $this
                ->addUsingAlias(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID, $subdivisionType->getId(), $comparison);
        } elseif ($subdivisionType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubdivisionTableMap::COL_SUBDIVISION_TYPE_ID, $subdivisionType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySubdivisionType() only accepts arguments of type \keeko\core\model\SubdivisionType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SubdivisionType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSubdivisionQuery The current query, for fluid interface
     */
    public function joinSubdivisionType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SubdivisionType');

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
            $this->addJoinObject($join, 'SubdivisionType');
        }

        return $this;
    }

    /**
     * Use the SubdivisionType relation SubdivisionType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\SubdivisionTypeQuery A secondary query class using the current class as primary query
     */
    public function useSubdivisionTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubdivisionType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SubdivisionType', '\keeko\core\model\SubdivisionTypeQuery');
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

    /**
     * Deletes all rows from the kk_subdivision table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubdivisionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SubdivisionTableMap::clearInstancePool();
            SubdivisionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SubdivisionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SubdivisionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SubdivisionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SubdivisionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SubdivisionQuery
