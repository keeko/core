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
use keeko\core\model\Country as ChildCountry;
use keeko\core\model\CountryQuery as ChildCountryQuery;
use keeko\core\model\Map\CountryTableMap;

/**
 * Base class that represents a query for the 'kk_country' table.
 *
 *
 *
 * @method     ChildCountryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCountryQuery orderByNumeric($order = Criteria::ASC) Order by the numeric column
 * @method     ChildCountryQuery orderByAlpha2($order = Criteria::ASC) Order by the alpha_2 column
 * @method     ChildCountryQuery orderByAlpha3($order = Criteria::ASC) Order by the alpha_3 column
 * @method     ChildCountryQuery orderByShortName($order = Criteria::ASC) Order by the short_name column
 * @method     ChildCountryQuery orderByIoc($order = Criteria::ASC) Order by the ioc column
 * @method     ChildCountryQuery orderByTld($order = Criteria::ASC) Order by the tld column
 * @method     ChildCountryQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method     ChildCountryQuery orderByCapital($order = Criteria::ASC) Order by the capital column
 * @method     ChildCountryQuery orderByPostalCodeFormat($order = Criteria::ASC) Order by the postal_code_format column
 * @method     ChildCountryQuery orderByPostalCodeRegex($order = Criteria::ASC) Order by the postal_code_regex column
 * @method     ChildCountryQuery orderByContinentId($order = Criteria::ASC) Order by the continent_id column
 * @method     ChildCountryQuery orderByCurrencyId($order = Criteria::ASC) Order by the currency_id column
 * @method     ChildCountryQuery orderByTypeId($order = Criteria::ASC) Order by the type_id column
 * @method     ChildCountryQuery orderBySubtypeId($order = Criteria::ASC) Order by the subtype_id column
 * @method     ChildCountryQuery orderBySovereignityId($order = Criteria::ASC) Order by the sovereignity_id column
 * @method     ChildCountryQuery orderByFormalName($order = Criteria::ASC) Order by the formal_name column
 * @method     ChildCountryQuery orderByFormalNativeName($order = Criteria::ASC) Order by the formal_native_name column
 * @method     ChildCountryQuery orderByShortNativeName($order = Criteria::ASC) Order by the short_native_name column
 * @method     ChildCountryQuery orderByBboxSwLat($order = Criteria::ASC) Order by the bbox_sw_lat column
 * @method     ChildCountryQuery orderByBboxSwLng($order = Criteria::ASC) Order by the bbox_sw_lng column
 * @method     ChildCountryQuery orderByBboxNeLat($order = Criteria::ASC) Order by the bbox_ne_lat column
 * @method     ChildCountryQuery orderByBboxNeLng($order = Criteria::ASC) Order by the bbox_ne_lng column
 *
 * @method     ChildCountryQuery groupById() Group by the id column
 * @method     ChildCountryQuery groupByNumeric() Group by the numeric column
 * @method     ChildCountryQuery groupByAlpha2() Group by the alpha_2 column
 * @method     ChildCountryQuery groupByAlpha3() Group by the alpha_3 column
 * @method     ChildCountryQuery groupByShortName() Group by the short_name column
 * @method     ChildCountryQuery groupByIoc() Group by the ioc column
 * @method     ChildCountryQuery groupByTld() Group by the tld column
 * @method     ChildCountryQuery groupByPhone() Group by the phone column
 * @method     ChildCountryQuery groupByCapital() Group by the capital column
 * @method     ChildCountryQuery groupByPostalCodeFormat() Group by the postal_code_format column
 * @method     ChildCountryQuery groupByPostalCodeRegex() Group by the postal_code_regex column
 * @method     ChildCountryQuery groupByContinentId() Group by the continent_id column
 * @method     ChildCountryQuery groupByCurrencyId() Group by the currency_id column
 * @method     ChildCountryQuery groupByTypeId() Group by the type_id column
 * @method     ChildCountryQuery groupBySubtypeId() Group by the subtype_id column
 * @method     ChildCountryQuery groupBySovereignityId() Group by the sovereignity_id column
 * @method     ChildCountryQuery groupByFormalName() Group by the formal_name column
 * @method     ChildCountryQuery groupByFormalNativeName() Group by the formal_native_name column
 * @method     ChildCountryQuery groupByShortNativeName() Group by the short_native_name column
 * @method     ChildCountryQuery groupByBboxSwLat() Group by the bbox_sw_lat column
 * @method     ChildCountryQuery groupByBboxSwLng() Group by the bbox_sw_lng column
 * @method     ChildCountryQuery groupByBboxNeLat() Group by the bbox_ne_lat column
 * @method     ChildCountryQuery groupByBboxNeLng() Group by the bbox_ne_lng column
 *
 * @method     ChildCountryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCountryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCountryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCountryQuery leftJoinContinent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Continent relation
 * @method     ChildCountryQuery rightJoinContinent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Continent relation
 * @method     ChildCountryQuery innerJoinContinent($relationAlias = null) Adds a INNER JOIN clause to the query using the Continent relation
 *
 * @method     ChildCountryQuery leftJoinCurrency($relationAlias = null) Adds a LEFT JOIN clause to the query using the Currency relation
 * @method     ChildCountryQuery rightJoinCurrency($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Currency relation
 * @method     ChildCountryQuery innerJoinCurrency($relationAlias = null) Adds a INNER JOIN clause to the query using the Currency relation
 *
 * @method     ChildCountryQuery leftJoinType($relationAlias = null) Adds a LEFT JOIN clause to the query using the Type relation
 * @method     ChildCountryQuery rightJoinType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Type relation
 * @method     ChildCountryQuery innerJoinType($relationAlias = null) Adds a INNER JOIN clause to the query using the Type relation
 *
 * @method     ChildCountryQuery leftJoinSubtype($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subtype relation
 * @method     ChildCountryQuery rightJoinSubtype($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subtype relation
 * @method     ChildCountryQuery innerJoinSubtype($relationAlias = null) Adds a INNER JOIN clause to the query using the Subtype relation
 *
 * @method     ChildCountryQuery leftJoinCountryRelatedBySovereignityId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CountryRelatedBySovereignityId relation
 * @method     ChildCountryQuery rightJoinCountryRelatedBySovereignityId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CountryRelatedBySovereignityId relation
 * @method     ChildCountryQuery innerJoinCountryRelatedBySovereignityId($relationAlias = null) Adds a INNER JOIN clause to the query using the CountryRelatedBySovereignityId relation
 *
 * @method     ChildCountryQuery leftJoinSubordinate($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subordinate relation
 * @method     ChildCountryQuery rightJoinSubordinate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subordinate relation
 * @method     ChildCountryQuery innerJoinSubordinate($relationAlias = null) Adds a INNER JOIN clause to the query using the Subordinate relation
 *
 * @method     ChildCountryQuery leftJoinSubdivision($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subdivision relation
 * @method     ChildCountryQuery rightJoinSubdivision($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subdivision relation
 * @method     ChildCountryQuery innerJoinSubdivision($relationAlias = null) Adds a INNER JOIN clause to the query using the Subdivision relation
 *
 * @method     \keeko\core\model\ContinentQuery|\keeko\core\model\CurrencyQuery|\keeko\core\model\RegionTypeQuery|\keeko\core\model\CountryQuery|\keeko\core\model\SubdivisionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCountry findOne(ConnectionInterface $con = null) Return the first ChildCountry matching the query
 * @method     ChildCountry findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCountry matching the query, or a new ChildCountry object populated from the query conditions when no match is found
 *
 * @method     ChildCountry findOneById(int $id) Return the first ChildCountry filtered by the id column
 * @method     ChildCountry findOneByNumeric(int $numeric) Return the first ChildCountry filtered by the numeric column
 * @method     ChildCountry findOneByAlpha2(string $alpha_2) Return the first ChildCountry filtered by the alpha_2 column
 * @method     ChildCountry findOneByAlpha3(string $alpha_3) Return the first ChildCountry filtered by the alpha_3 column
 * @method     ChildCountry findOneByShortName(string $short_name) Return the first ChildCountry filtered by the short_name column
 * @method     ChildCountry findOneByIoc(string $ioc) Return the first ChildCountry filtered by the ioc column
 * @method     ChildCountry findOneByTld(string $tld) Return the first ChildCountry filtered by the tld column
 * @method     ChildCountry findOneByPhone(string $phone) Return the first ChildCountry filtered by the phone column
 * @method     ChildCountry findOneByCapital(string $capital) Return the first ChildCountry filtered by the capital column
 * @method     ChildCountry findOneByPostalCodeFormat(string $postal_code_format) Return the first ChildCountry filtered by the postal_code_format column
 * @method     ChildCountry findOneByPostalCodeRegex(string $postal_code_regex) Return the first ChildCountry filtered by the postal_code_regex column
 * @method     ChildCountry findOneByContinentId(int $continent_id) Return the first ChildCountry filtered by the continent_id column
 * @method     ChildCountry findOneByCurrencyId(int $currency_id) Return the first ChildCountry filtered by the currency_id column
 * @method     ChildCountry findOneByTypeId(int $type_id) Return the first ChildCountry filtered by the type_id column
 * @method     ChildCountry findOneBySubtypeId(int $subtype_id) Return the first ChildCountry filtered by the subtype_id column
 * @method     ChildCountry findOneBySovereignityId(int $sovereignity_id) Return the first ChildCountry filtered by the sovereignity_id column
 * @method     ChildCountry findOneByFormalName(string $formal_name) Return the first ChildCountry filtered by the formal_name column
 * @method     ChildCountry findOneByFormalNativeName(string $formal_native_name) Return the first ChildCountry filtered by the formal_native_name column
 * @method     ChildCountry findOneByShortNativeName(string $short_native_name) Return the first ChildCountry filtered by the short_native_name column
 * @method     ChildCountry findOneByBboxSwLat(double $bbox_sw_lat) Return the first ChildCountry filtered by the bbox_sw_lat column
 * @method     ChildCountry findOneByBboxSwLng(double $bbox_sw_lng) Return the first ChildCountry filtered by the bbox_sw_lng column
 * @method     ChildCountry findOneByBboxNeLat(double $bbox_ne_lat) Return the first ChildCountry filtered by the bbox_ne_lat column
 * @method     ChildCountry findOneByBboxNeLng(double $bbox_ne_lng) Return the first ChildCountry filtered by the bbox_ne_lng column *

 * @method     ChildCountry requirePk($key, ConnectionInterface $con = null) Return the ChildCountry by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOne(ConnectionInterface $con = null) Return the first ChildCountry matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCountry requireOneById(int $id) Return the first ChildCountry filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByNumeric(int $numeric) Return the first ChildCountry filtered by the numeric column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByAlpha2(string $alpha_2) Return the first ChildCountry filtered by the alpha_2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByAlpha3(string $alpha_3) Return the first ChildCountry filtered by the alpha_3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByShortName(string $short_name) Return the first ChildCountry filtered by the short_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByIoc(string $ioc) Return the first ChildCountry filtered by the ioc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByTld(string $tld) Return the first ChildCountry filtered by the tld column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByPhone(string $phone) Return the first ChildCountry filtered by the phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByCapital(string $capital) Return the first ChildCountry filtered by the capital column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByPostalCodeFormat(string $postal_code_format) Return the first ChildCountry filtered by the postal_code_format column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByPostalCodeRegex(string $postal_code_regex) Return the first ChildCountry filtered by the postal_code_regex column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByContinentId(int $continent_id) Return the first ChildCountry filtered by the continent_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByCurrencyId(int $currency_id) Return the first ChildCountry filtered by the currency_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByTypeId(int $type_id) Return the first ChildCountry filtered by the type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneBySubtypeId(int $subtype_id) Return the first ChildCountry filtered by the subtype_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneBySovereignityId(int $sovereignity_id) Return the first ChildCountry filtered by the sovereignity_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByFormalName(string $formal_name) Return the first ChildCountry filtered by the formal_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByFormalNativeName(string $formal_native_name) Return the first ChildCountry filtered by the formal_native_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByShortNativeName(string $short_native_name) Return the first ChildCountry filtered by the short_native_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByBboxSwLat(double $bbox_sw_lat) Return the first ChildCountry filtered by the bbox_sw_lat column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByBboxSwLng(double $bbox_sw_lng) Return the first ChildCountry filtered by the bbox_sw_lng column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByBboxNeLat(double $bbox_ne_lat) Return the first ChildCountry filtered by the bbox_ne_lat column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByBboxNeLng(double $bbox_ne_lng) Return the first ChildCountry filtered by the bbox_ne_lng column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCountry[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCountry objects based on current ModelCriteria
 * @method     ChildCountry[]|ObjectCollection findById(int $id) Return ChildCountry objects filtered by the id column
 * @method     ChildCountry[]|ObjectCollection findByNumeric(int $numeric) Return ChildCountry objects filtered by the numeric column
 * @method     ChildCountry[]|ObjectCollection findByAlpha2(string $alpha_2) Return ChildCountry objects filtered by the alpha_2 column
 * @method     ChildCountry[]|ObjectCollection findByAlpha3(string $alpha_3) Return ChildCountry objects filtered by the alpha_3 column
 * @method     ChildCountry[]|ObjectCollection findByShortName(string $short_name) Return ChildCountry objects filtered by the short_name column
 * @method     ChildCountry[]|ObjectCollection findByIoc(string $ioc) Return ChildCountry objects filtered by the ioc column
 * @method     ChildCountry[]|ObjectCollection findByTld(string $tld) Return ChildCountry objects filtered by the tld column
 * @method     ChildCountry[]|ObjectCollection findByPhone(string $phone) Return ChildCountry objects filtered by the phone column
 * @method     ChildCountry[]|ObjectCollection findByCapital(string $capital) Return ChildCountry objects filtered by the capital column
 * @method     ChildCountry[]|ObjectCollection findByPostalCodeFormat(string $postal_code_format) Return ChildCountry objects filtered by the postal_code_format column
 * @method     ChildCountry[]|ObjectCollection findByPostalCodeRegex(string $postal_code_regex) Return ChildCountry objects filtered by the postal_code_regex column
 * @method     ChildCountry[]|ObjectCollection findByContinentId(int $continent_id) Return ChildCountry objects filtered by the continent_id column
 * @method     ChildCountry[]|ObjectCollection findByCurrencyId(int $currency_id) Return ChildCountry objects filtered by the currency_id column
 * @method     ChildCountry[]|ObjectCollection findByTypeId(int $type_id) Return ChildCountry objects filtered by the type_id column
 * @method     ChildCountry[]|ObjectCollection findBySubtypeId(int $subtype_id) Return ChildCountry objects filtered by the subtype_id column
 * @method     ChildCountry[]|ObjectCollection findBySovereignityId(int $sovereignity_id) Return ChildCountry objects filtered by the sovereignity_id column
 * @method     ChildCountry[]|ObjectCollection findByFormalName(string $formal_name) Return ChildCountry objects filtered by the formal_name column
 * @method     ChildCountry[]|ObjectCollection findByFormalNativeName(string $formal_native_name) Return ChildCountry objects filtered by the formal_native_name column
 * @method     ChildCountry[]|ObjectCollection findByShortNativeName(string $short_native_name) Return ChildCountry objects filtered by the short_native_name column
 * @method     ChildCountry[]|ObjectCollection findByBboxSwLat(double $bbox_sw_lat) Return ChildCountry objects filtered by the bbox_sw_lat column
 * @method     ChildCountry[]|ObjectCollection findByBboxSwLng(double $bbox_sw_lng) Return ChildCountry objects filtered by the bbox_sw_lng column
 * @method     ChildCountry[]|ObjectCollection findByBboxNeLat(double $bbox_ne_lat) Return ChildCountry objects filtered by the bbox_ne_lat column
 * @method     ChildCountry[]|ObjectCollection findByBboxNeLng(double $bbox_ne_lng) Return ChildCountry objects filtered by the bbox_ne_lng column
 * @method     ChildCountry[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CountryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\CountryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Country', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCountryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCountryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCountryQuery) {
            return $criteria;
        }
        $query = new ChildCountryQuery();
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
     * @return ChildCountry|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CountryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CountryTableMap::DATABASE_NAME);
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
     * @return ChildCountry A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `numeric`, `alpha_2`, `alpha_3`, `short_name`, `ioc`, `tld`, `phone`, `capital`, `postal_code_format`, `postal_code_regex`, `continent_id`, `currency_id`, `type_id`, `subtype_id`, `sovereignity_id`, `formal_name`, `formal_native_name`, `short_native_name`, `bbox_sw_lat`, `bbox_sw_lng`, `bbox_ne_lat`, `bbox_ne_lng` FROM `kk_country` WHERE `id` = :p0';
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
            /** @var ChildCountry $obj */
            $obj = new ChildCountry();
            $obj->hydrate($row);
            CountryTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCountry|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CountryTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CountryTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the numeric column
     *
     * Example usage:
     * <code>
     * $query->filterByNumeric(1234); // WHERE numeric = 1234
     * $query->filterByNumeric(array(12, 34)); // WHERE numeric IN (12, 34)
     * $query->filterByNumeric(array('min' => 12)); // WHERE numeric > 12
     * </code>
     *
     * @param     mixed $numeric The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByNumeric($numeric = null, $comparison = null)
    {
        if (is_array($numeric)) {
            $useMinMax = false;
            if (isset($numeric['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_NUMERIC, $numeric['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numeric['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_NUMERIC, $numeric['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_NUMERIC, $numeric, $comparison);
    }

    /**
     * Filter the query on the alpha_2 column
     *
     * Example usage:
     * <code>
     * $query->filterByAlpha2('fooValue');   // WHERE alpha_2 = 'fooValue'
     * $query->filterByAlpha2('%fooValue%'); // WHERE alpha_2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alpha2 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByAlpha2($alpha2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alpha2)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alpha2)) {
                $alpha2 = str_replace('*', '%', $alpha2);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_ALPHA_2, $alpha2, $comparison);
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
     * @return $this|ChildCountryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CountryTableMap::COL_ALPHA_3, $alpha3, $comparison);
    }

    /**
     * Filter the query on the short_name column
     *
     * Example usage:
     * <code>
     * $query->filterByShortName('fooValue');   // WHERE short_name = 'fooValue'
     * $query->filterByShortName('%fooValue%'); // WHERE short_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shortName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByShortName($shortName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shortName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $shortName)) {
                $shortName = str_replace('*', '%', $shortName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_SHORT_NAME, $shortName, $comparison);
    }

    /**
     * Filter the query on the ioc column
     *
     * Example usage:
     * <code>
     * $query->filterByIoc('fooValue');   // WHERE ioc = 'fooValue'
     * $query->filterByIoc('%fooValue%'); // WHERE ioc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ioc The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByIoc($ioc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ioc)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ioc)) {
                $ioc = str_replace('*', '%', $ioc);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_IOC, $ioc, $comparison);
    }

    /**
     * Filter the query on the tld column
     *
     * Example usage:
     * <code>
     * $query->filterByTld('fooValue');   // WHERE tld = 'fooValue'
     * $query->filterByTld('%fooValue%'); // WHERE tld LIKE '%fooValue%'
     * </code>
     *
     * @param     string $tld The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByTld($tld = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($tld)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $tld)) {
                $tld = str_replace('*', '%', $tld);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_TLD, $tld, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the capital column
     *
     * Example usage:
     * <code>
     * $query->filterByCapital('fooValue');   // WHERE capital = 'fooValue'
     * $query->filterByCapital('%fooValue%'); // WHERE capital LIKE '%fooValue%'
     * </code>
     *
     * @param     string $capital The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByCapital($capital = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($capital)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $capital)) {
                $capital = str_replace('*', '%', $capital);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_CAPITAL, $capital, $comparison);
    }

    /**
     * Filter the query on the postal_code_format column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCodeFormat('fooValue');   // WHERE postal_code_format = 'fooValue'
     * $query->filterByPostalCodeFormat('%fooValue%'); // WHERE postal_code_format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postalCodeFormat The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByPostalCodeFormat($postalCodeFormat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCodeFormat)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $postalCodeFormat)) {
                $postalCodeFormat = str_replace('*', '%', $postalCodeFormat);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_POSTAL_CODE_FORMAT, $postalCodeFormat, $comparison);
    }

    /**
     * Filter the query on the postal_code_regex column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCodeRegex('fooValue');   // WHERE postal_code_regex = 'fooValue'
     * $query->filterByPostalCodeRegex('%fooValue%'); // WHERE postal_code_regex LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postalCodeRegex The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByPostalCodeRegex($postalCodeRegex = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCodeRegex)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $postalCodeRegex)) {
                $postalCodeRegex = str_replace('*', '%', $postalCodeRegex);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_POSTAL_CODE_REGEX, $postalCodeRegex, $comparison);
    }

    /**
     * Filter the query on the continent_id column
     *
     * Example usage:
     * <code>
     * $query->filterByContinentId(1234); // WHERE continent_id = 1234
     * $query->filterByContinentId(array(12, 34)); // WHERE continent_id IN (12, 34)
     * $query->filterByContinentId(array('min' => 12)); // WHERE continent_id > 12
     * </code>
     *
     * @see       filterByContinent()
     *
     * @param     mixed $continentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByContinentId($continentId = null, $comparison = null)
    {
        if (is_array($continentId)) {
            $useMinMax = false;
            if (isset($continentId['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_CONTINENT_ID, $continentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($continentId['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_CONTINENT_ID, $continentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_CONTINENT_ID, $continentId, $comparison);
    }

    /**
     * Filter the query on the currency_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrencyId(1234); // WHERE currency_id = 1234
     * $query->filterByCurrencyId(array(12, 34)); // WHERE currency_id IN (12, 34)
     * $query->filterByCurrencyId(array('min' => 12)); // WHERE currency_id > 12
     * </code>
     *
     * @see       filterByCurrency()
     *
     * @param     mixed $currencyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByCurrencyId($currencyId = null, $comparison = null)
    {
        if (is_array($currencyId)) {
            $useMinMax = false;
            if (isset($currencyId['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_CURRENCY_ID, $currencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currencyId['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_CURRENCY_ID, $currencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_CURRENCY_ID, $currencyId, $comparison);
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
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByTypeId($typeId = null, $comparison = null)
    {
        if (is_array($typeId)) {
            $useMinMax = false;
            if (isset($typeId['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_TYPE_ID, $typeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($typeId['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_TYPE_ID, $typeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_TYPE_ID, $typeId, $comparison);
    }

    /**
     * Filter the query on the subtype_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubtypeId(1234); // WHERE subtype_id = 1234
     * $query->filterBySubtypeId(array(12, 34)); // WHERE subtype_id IN (12, 34)
     * $query->filterBySubtypeId(array('min' => 12)); // WHERE subtype_id > 12
     * </code>
     *
     * @see       filterBySubtype()
     *
     * @param     mixed $subtypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterBySubtypeId($subtypeId = null, $comparison = null)
    {
        if (is_array($subtypeId)) {
            $useMinMax = false;
            if (isset($subtypeId['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_SUBTYPE_ID, $subtypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subtypeId['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_SUBTYPE_ID, $subtypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_SUBTYPE_ID, $subtypeId, $comparison);
    }

    /**
     * Filter the query on the sovereignity_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySovereignityId(1234); // WHERE sovereignity_id = 1234
     * $query->filterBySovereignityId(array(12, 34)); // WHERE sovereignity_id IN (12, 34)
     * $query->filterBySovereignityId(array('min' => 12)); // WHERE sovereignity_id > 12
     * </code>
     *
     * @see       filterByCountryRelatedBySovereignityId()
     *
     * @param     mixed $sovereignityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterBySovereignityId($sovereignityId = null, $comparison = null)
    {
        if (is_array($sovereignityId)) {
            $useMinMax = false;
            if (isset($sovereignityId['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_SOVEREIGNITY_ID, $sovereignityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sovereignityId['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_SOVEREIGNITY_ID, $sovereignityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_SOVEREIGNITY_ID, $sovereignityId, $comparison);
    }

    /**
     * Filter the query on the formal_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFormalName('fooValue');   // WHERE formal_name = 'fooValue'
     * $query->filterByFormalName('%fooValue%'); // WHERE formal_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $formalName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByFormalName($formalName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($formalName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $formalName)) {
                $formalName = str_replace('*', '%', $formalName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_FORMAL_NAME, $formalName, $comparison);
    }

    /**
     * Filter the query on the formal_native_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFormalNativeName('fooValue');   // WHERE formal_native_name = 'fooValue'
     * $query->filterByFormalNativeName('%fooValue%'); // WHERE formal_native_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $formalNativeName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByFormalNativeName($formalNativeName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($formalNativeName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $formalNativeName)) {
                $formalNativeName = str_replace('*', '%', $formalNativeName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_FORMAL_NATIVE_NAME, $formalNativeName, $comparison);
    }

    /**
     * Filter the query on the short_native_name column
     *
     * Example usage:
     * <code>
     * $query->filterByShortNativeName('fooValue');   // WHERE short_native_name = 'fooValue'
     * $query->filterByShortNativeName('%fooValue%'); // WHERE short_native_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shortNativeName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByShortNativeName($shortNativeName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shortNativeName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $shortNativeName)) {
                $shortNativeName = str_replace('*', '%', $shortNativeName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_SHORT_NATIVE_NAME, $shortNativeName, $comparison);
    }

    /**
     * Filter the query on the bbox_sw_lat column
     *
     * Example usage:
     * <code>
     * $query->filterByBboxSwLat(1234); // WHERE bbox_sw_lat = 1234
     * $query->filterByBboxSwLat(array(12, 34)); // WHERE bbox_sw_lat IN (12, 34)
     * $query->filterByBboxSwLat(array('min' => 12)); // WHERE bbox_sw_lat > 12
     * </code>
     *
     * @param     mixed $bboxSwLat The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByBboxSwLat($bboxSwLat = null, $comparison = null)
    {
        if (is_array($bboxSwLat)) {
            $useMinMax = false;
            if (isset($bboxSwLat['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_BBOX_SW_LAT, $bboxSwLat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bboxSwLat['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_BBOX_SW_LAT, $bboxSwLat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_BBOX_SW_LAT, $bboxSwLat, $comparison);
    }

    /**
     * Filter the query on the bbox_sw_lng column
     *
     * Example usage:
     * <code>
     * $query->filterByBboxSwLng(1234); // WHERE bbox_sw_lng = 1234
     * $query->filterByBboxSwLng(array(12, 34)); // WHERE bbox_sw_lng IN (12, 34)
     * $query->filterByBboxSwLng(array('min' => 12)); // WHERE bbox_sw_lng > 12
     * </code>
     *
     * @param     mixed $bboxSwLng The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByBboxSwLng($bboxSwLng = null, $comparison = null)
    {
        if (is_array($bboxSwLng)) {
            $useMinMax = false;
            if (isset($bboxSwLng['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_BBOX_SW_LNG, $bboxSwLng['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bboxSwLng['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_BBOX_SW_LNG, $bboxSwLng['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_BBOX_SW_LNG, $bboxSwLng, $comparison);
    }

    /**
     * Filter the query on the bbox_ne_lat column
     *
     * Example usage:
     * <code>
     * $query->filterByBboxNeLat(1234); // WHERE bbox_ne_lat = 1234
     * $query->filterByBboxNeLat(array(12, 34)); // WHERE bbox_ne_lat IN (12, 34)
     * $query->filterByBboxNeLat(array('min' => 12)); // WHERE bbox_ne_lat > 12
     * </code>
     *
     * @param     mixed $bboxNeLat The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByBboxNeLat($bboxNeLat = null, $comparison = null)
    {
        if (is_array($bboxNeLat)) {
            $useMinMax = false;
            if (isset($bboxNeLat['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_BBOX_NE_LAT, $bboxNeLat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bboxNeLat['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_BBOX_NE_LAT, $bboxNeLat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_BBOX_NE_LAT, $bboxNeLat, $comparison);
    }

    /**
     * Filter the query on the bbox_ne_lng column
     *
     * Example usage:
     * <code>
     * $query->filterByBboxNeLng(1234); // WHERE bbox_ne_lng = 1234
     * $query->filterByBboxNeLng(array(12, 34)); // WHERE bbox_ne_lng IN (12, 34)
     * $query->filterByBboxNeLng(array('min' => 12)); // WHERE bbox_ne_lng > 12
     * </code>
     *
     * @param     mixed $bboxNeLng The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByBboxNeLng($bboxNeLng = null, $comparison = null)
    {
        if (is_array($bboxNeLng)) {
            $useMinMax = false;
            if (isset($bboxNeLng['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_BBOX_NE_LNG, $bboxNeLng['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bboxNeLng['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_BBOX_NE_LNG, $bboxNeLng['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_BBOX_NE_LNG, $bboxNeLng, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Continent object
     *
     * @param \keeko\core\model\Continent|ObjectCollection $continent The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterByContinent($continent, $comparison = null)
    {
        if ($continent instanceof \keeko\core\model\Continent) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_CONTINENT_ID, $continent->getId(), $comparison);
        } elseif ($continent instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CountryTableMap::COL_CONTINENT_ID, $continent->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByContinent() only accepts arguments of type \keeko\core\model\Continent or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Continent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function joinContinent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Continent');

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
            $this->addJoinObject($join, 'Continent');
        }

        return $this;
    }

    /**
     * Use the Continent relation Continent object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ContinentQuery A secondary query class using the current class as primary query
     */
    public function useContinentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinContinent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Continent', '\keeko\core\model\ContinentQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Currency object
     *
     * @param \keeko\core\model\Currency|ObjectCollection $currency The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterByCurrency($currency, $comparison = null)
    {
        if ($currency instanceof \keeko\core\model\Currency) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_CURRENCY_ID, $currency->getId(), $comparison);
        } elseif ($currency instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CountryTableMap::COL_CURRENCY_ID, $currency->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrency() only accepts arguments of type \keeko\core\model\Currency or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Currency relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function joinCurrency($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Currency');

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
            $this->addJoinObject($join, 'Currency');
        }

        return $this;
    }

    /**
     * Use the Currency relation Currency object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\CurrencyQuery A secondary query class using the current class as primary query
     */
    public function useCurrencyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrency($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Currency', '\keeko\core\model\CurrencyQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\RegionType object
     *
     * @param \keeko\core\model\RegionType|ObjectCollection $regionType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterByType($regionType, $comparison = null)
    {
        if ($regionType instanceof \keeko\core\model\RegionType) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_TYPE_ID, $regionType->getId(), $comparison);
        } elseif ($regionType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CountryTableMap::COL_TYPE_ID, $regionType->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildCountryQuery The current query, for fluid interface
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
     * Filter the query by a related \keeko\core\model\RegionType object
     *
     * @param \keeko\core\model\RegionType|ObjectCollection $regionType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterBySubtype($regionType, $comparison = null)
    {
        if ($regionType instanceof \keeko\core\model\RegionType) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_SUBTYPE_ID, $regionType->getId(), $comparison);
        } elseif ($regionType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CountryTableMap::COL_SUBTYPE_ID, $regionType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySubtype() only accepts arguments of type \keeko\core\model\RegionType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Subtype relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function joinSubtype($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Subtype');

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
            $this->addJoinObject($join, 'Subtype');
        }

        return $this;
    }

    /**
     * Use the Subtype relation RegionType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\RegionTypeQuery A secondary query class using the current class as primary query
     */
    public function useSubtypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubtype($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subtype', '\keeko\core\model\RegionTypeQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Country object
     *
     * @param \keeko\core\model\Country|ObjectCollection $country The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterByCountryRelatedBySovereignityId($country, $comparison = null)
    {
        if ($country instanceof \keeko\core\model\Country) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_SOVEREIGNITY_ID, $country->getId(), $comparison);
        } elseif ($country instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CountryTableMap::COL_SOVEREIGNITY_ID, $country->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCountryRelatedBySovereignityId() only accepts arguments of type \keeko\core\model\Country or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CountryRelatedBySovereignityId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function joinCountryRelatedBySovereignityId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CountryRelatedBySovereignityId');

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
            $this->addJoinObject($join, 'CountryRelatedBySovereignityId');
        }

        return $this;
    }

    /**
     * Use the CountryRelatedBySovereignityId relation Country object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\CountryQuery A secondary query class using the current class as primary query
     */
    public function useCountryRelatedBySovereignityIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCountryRelatedBySovereignityId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CountryRelatedBySovereignityId', '\keeko\core\model\CountryQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Country object
     *
     * @param \keeko\core\model\Country|ObjectCollection $country the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterBySubordinate($country, $comparison = null)
    {
        if ($country instanceof \keeko\core\model\Country) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_ID, $country->getSovereignityId(), $comparison);
        } elseif ($country instanceof ObjectCollection) {
            return $this
                ->useSubordinateQuery()
                ->filterByPrimaryKeys($country->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySubordinate() only accepts arguments of type \keeko\core\model\Country or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Subordinate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function joinSubordinate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Subordinate');

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
            $this->addJoinObject($join, 'Subordinate');
        }

        return $this;
    }

    /**
     * Use the Subordinate relation Country object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\CountryQuery A secondary query class using the current class as primary query
     */
    public function useSubordinateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubordinate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subordinate', '\keeko\core\model\CountryQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Subdivision object
     *
     * @param \keeko\core\model\Subdivision|ObjectCollection $subdivision the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterBySubdivision($subdivision, $comparison = null)
    {
        if ($subdivision instanceof \keeko\core\model\Subdivision) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_ID, $subdivision->getCountryId(), $comparison);
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
     * @return $this|ChildCountryQuery The current query, for fluid interface
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
     * @param   ChildCountry $country Object to remove from the list of results
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function prune($country = null)
    {
        if ($country) {
            $this->addUsingAlias(CountryTableMap::COL_ID, $country->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

} // CountryQuery
