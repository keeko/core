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
 * Base class that represents a query for the 'keeko_country' table.
 *
 *
 *
 * @method     ChildCountryQuery orderByIsoNr($order = Criteria::ASC) Order by the iso_nr column
 * @method     ChildCountryQuery orderByAlpha2($order = Criteria::ASC) Order by the alpha_2 column
 * @method     ChildCountryQuery orderByAlpha3($order = Criteria::ASC) Order by the alpha_3 column
 * @method     ChildCountryQuery orderByIoc($order = Criteria::ASC) Order by the ioc column
 * @method     ChildCountryQuery orderByCapital($order = Criteria::ASC) Order by the capital column
 * @method     ChildCountryQuery orderByTld($order = Criteria::ASC) Order by the tld column
 * @method     ChildCountryQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method     ChildCountryQuery orderByTerritoryIsoNr($order = Criteria::ASC) Order by the territory_iso_nr column
 * @method     ChildCountryQuery orderByCurrencyIsoNr($order = Criteria::ASC) Order by the currency_iso_nr column
 * @method     ChildCountryQuery orderByOfficialLocalName($order = Criteria::ASC) Order by the official_local_name column
 * @method     ChildCountryQuery orderByOfficialEnName($order = Criteria::ASC) Order by the official_en_name column
 * @method     ChildCountryQuery orderByShortLocalName($order = Criteria::ASC) Order by the short_local_name column
 * @method     ChildCountryQuery orderByShortEnName($order = Criteria::ASC) Order by the short_en_name column
 * @method     ChildCountryQuery orderByBboxSwLat($order = Criteria::ASC) Order by the bbox_sw_lat column
 * @method     ChildCountryQuery orderByBboxSwLng($order = Criteria::ASC) Order by the bbox_sw_lng column
 * @method     ChildCountryQuery orderByBboxNeLat($order = Criteria::ASC) Order by the bbox_ne_lat column
 * @method     ChildCountryQuery orderByBboxNeLng($order = Criteria::ASC) Order by the bbox_ne_lng column
 *
 * @method     ChildCountryQuery groupByIsoNr() Group by the iso_nr column
 * @method     ChildCountryQuery groupByAlpha2() Group by the alpha_2 column
 * @method     ChildCountryQuery groupByAlpha3() Group by the alpha_3 column
 * @method     ChildCountryQuery groupByIoc() Group by the ioc column
 * @method     ChildCountryQuery groupByCapital() Group by the capital column
 * @method     ChildCountryQuery groupByTld() Group by the tld column
 * @method     ChildCountryQuery groupByPhone() Group by the phone column
 * @method     ChildCountryQuery groupByTerritoryIsoNr() Group by the territory_iso_nr column
 * @method     ChildCountryQuery groupByCurrencyIsoNr() Group by the currency_iso_nr column
 * @method     ChildCountryQuery groupByOfficialLocalName() Group by the official_local_name column
 * @method     ChildCountryQuery groupByOfficialEnName() Group by the official_en_name column
 * @method     ChildCountryQuery groupByShortLocalName() Group by the short_local_name column
 * @method     ChildCountryQuery groupByShortEnName() Group by the short_en_name column
 * @method     ChildCountryQuery groupByBboxSwLat() Group by the bbox_sw_lat column
 * @method     ChildCountryQuery groupByBboxSwLng() Group by the bbox_sw_lng column
 * @method     ChildCountryQuery groupByBboxNeLat() Group by the bbox_ne_lat column
 * @method     ChildCountryQuery groupByBboxNeLng() Group by the bbox_ne_lng column
 *
 * @method     ChildCountryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCountryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCountryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCountryQuery leftJoinTerritory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Territory relation
 * @method     ChildCountryQuery rightJoinTerritory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Territory relation
 * @method     ChildCountryQuery innerJoinTerritory($relationAlias = null) Adds a INNER JOIN clause to the query using the Territory relation
 *
 * @method     ChildCountryQuery leftJoinCurrency($relationAlias = null) Adds a LEFT JOIN clause to the query using the Currency relation
 * @method     ChildCountryQuery rightJoinCurrency($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Currency relation
 * @method     ChildCountryQuery innerJoinCurrency($relationAlias = null) Adds a INNER JOIN clause to the query using the Currency relation
 *
 * @method     ChildCountryQuery leftJoinLocalization($relationAlias = null) Adds a LEFT JOIN clause to the query using the Localization relation
 * @method     ChildCountryQuery rightJoinLocalization($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Localization relation
 * @method     ChildCountryQuery innerJoinLocalization($relationAlias = null) Adds a INNER JOIN clause to the query using the Localization relation
 *
 * @method     ChildCountryQuery leftJoinSubdivision($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subdivision relation
 * @method     ChildCountryQuery rightJoinSubdivision($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subdivision relation
 * @method     ChildCountryQuery innerJoinSubdivision($relationAlias = null) Adds a INNER JOIN clause to the query using the Subdivision relation
 *
 * @method     ChildCountryQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildCountryQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildCountryQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     \keeko\core\model\TerritoryQuery|\keeko\core\model\CurrencyQuery|\keeko\core\model\LocalizationQuery|\keeko\core\model\SubdivisionQuery|\keeko\core\model\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCountry findOne(ConnectionInterface $con = null) Return the first ChildCountry matching the query
 * @method     ChildCountry findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCountry matching the query, or a new ChildCountry object populated from the query conditions when no match is found
 *
 * @method     ChildCountry findOneByIsoNr(int $iso_nr) Return the first ChildCountry filtered by the iso_nr column
 * @method     ChildCountry findOneByAlpha2(string $alpha_2) Return the first ChildCountry filtered by the alpha_2 column
 * @method     ChildCountry findOneByAlpha3(string $alpha_3) Return the first ChildCountry filtered by the alpha_3 column
 * @method     ChildCountry findOneByIoc(string $ioc) Return the first ChildCountry filtered by the ioc column
 * @method     ChildCountry findOneByCapital(string $capital) Return the first ChildCountry filtered by the capital column
 * @method     ChildCountry findOneByTld(string $tld) Return the first ChildCountry filtered by the tld column
 * @method     ChildCountry findOneByPhone(string $phone) Return the first ChildCountry filtered by the phone column
 * @method     ChildCountry findOneByTerritoryIsoNr(int $territory_iso_nr) Return the first ChildCountry filtered by the territory_iso_nr column
 * @method     ChildCountry findOneByCurrencyIsoNr(int $currency_iso_nr) Return the first ChildCountry filtered by the currency_iso_nr column
 * @method     ChildCountry findOneByOfficialLocalName(string $official_local_name) Return the first ChildCountry filtered by the official_local_name column
 * @method     ChildCountry findOneByOfficialEnName(string $official_en_name) Return the first ChildCountry filtered by the official_en_name column
 * @method     ChildCountry findOneByShortLocalName(string $short_local_name) Return the first ChildCountry filtered by the short_local_name column
 * @method     ChildCountry findOneByShortEnName(string $short_en_name) Return the first ChildCountry filtered by the short_en_name column
 * @method     ChildCountry findOneByBboxSwLat(double $bbox_sw_lat) Return the first ChildCountry filtered by the bbox_sw_lat column
 * @method     ChildCountry findOneByBboxSwLng(double $bbox_sw_lng) Return the first ChildCountry filtered by the bbox_sw_lng column
 * @method     ChildCountry findOneByBboxNeLat(double $bbox_ne_lat) Return the first ChildCountry filtered by the bbox_ne_lat column
 * @method     ChildCountry findOneByBboxNeLng(double $bbox_ne_lng) Return the first ChildCountry filtered by the bbox_ne_lng column
 *
 * @method     ChildCountry[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCountry objects based on current ModelCriteria
 * @method     ChildCountry[]|ObjectCollection findByIsoNr(int $iso_nr) Return ChildCountry objects filtered by the iso_nr column
 * @method     ChildCountry[]|ObjectCollection findByAlpha2(string $alpha_2) Return ChildCountry objects filtered by the alpha_2 column
 * @method     ChildCountry[]|ObjectCollection findByAlpha3(string $alpha_3) Return ChildCountry objects filtered by the alpha_3 column
 * @method     ChildCountry[]|ObjectCollection findByIoc(string $ioc) Return ChildCountry objects filtered by the ioc column
 * @method     ChildCountry[]|ObjectCollection findByCapital(string $capital) Return ChildCountry objects filtered by the capital column
 * @method     ChildCountry[]|ObjectCollection findByTld(string $tld) Return ChildCountry objects filtered by the tld column
 * @method     ChildCountry[]|ObjectCollection findByPhone(string $phone) Return ChildCountry objects filtered by the phone column
 * @method     ChildCountry[]|ObjectCollection findByTerritoryIsoNr(int $territory_iso_nr) Return ChildCountry objects filtered by the territory_iso_nr column
 * @method     ChildCountry[]|ObjectCollection findByCurrencyIsoNr(int $currency_iso_nr) Return ChildCountry objects filtered by the currency_iso_nr column
 * @method     ChildCountry[]|ObjectCollection findByOfficialLocalName(string $official_local_name) Return ChildCountry objects filtered by the official_local_name column
 * @method     ChildCountry[]|ObjectCollection findByOfficialEnName(string $official_en_name) Return ChildCountry objects filtered by the official_en_name column
 * @method     ChildCountry[]|ObjectCollection findByShortLocalName(string $short_local_name) Return ChildCountry objects filtered by the short_local_name column
 * @method     ChildCountry[]|ObjectCollection findByShortEnName(string $short_en_name) Return ChildCountry objects filtered by the short_en_name column
 * @method     ChildCountry[]|ObjectCollection findByBboxSwLat(double $bbox_sw_lat) Return ChildCountry objects filtered by the bbox_sw_lat column
 * @method     ChildCountry[]|ObjectCollection findByBboxSwLng(double $bbox_sw_lng) Return ChildCountry objects filtered by the bbox_sw_lng column
 * @method     ChildCountry[]|ObjectCollection findByBboxNeLat(double $bbox_ne_lat) Return ChildCountry objects filtered by the bbox_ne_lat column
 * @method     ChildCountry[]|ObjectCollection findByBboxNeLng(double $bbox_ne_lng) Return ChildCountry objects filtered by the bbox_ne_lng column
 * @method     ChildCountry[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CountryQuery extends ModelCriteria
{

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
     * @return ChildCountry A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ISO_NR, ALPHA_2, ALPHA_3, IOC, CAPITAL, TLD, PHONE, TERRITORY_ISO_NR, CURRENCY_ISO_NR, OFFICIAL_LOCAL_NAME, OFFICIAL_EN_NAME, SHORT_LOCAL_NAME, SHORT_EN_NAME, BBOX_SW_LAT, BBOX_SW_LNG, BBOX_NE_LAT, BBOX_NE_LNG FROM keeko_country WHERE ISO_NR = :p0';
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

        return $this->addUsingAlias(CountryTableMap::COL_ISO_NR, $key, Criteria::EQUAL);
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

        return $this->addUsingAlias(CountryTableMap::COL_ISO_NR, $keys, Criteria::IN);
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
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByIsoNr($isoNr = null, $comparison = null)
    {
        if (is_array($isoNr)) {
            $useMinMax = false;
            if (isset($isoNr['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_ISO_NR, $isoNr['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($isoNr['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_ISO_NR, $isoNr['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_ISO_NR, $isoNr, $comparison);
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
     * Filter the query on the territory_iso_nr column
     *
     * Example usage:
     * <code>
     * $query->filterByTerritoryIsoNr(1234); // WHERE territory_iso_nr = 1234
     * $query->filterByTerritoryIsoNr(array(12, 34)); // WHERE territory_iso_nr IN (12, 34)
     * $query->filterByTerritoryIsoNr(array('min' => 12)); // WHERE territory_iso_nr > 12
     * </code>
     *
     * @see       filterByTerritory()
     *
     * @param     mixed $territoryIsoNr The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByTerritoryIsoNr($territoryIsoNr = null, $comparison = null)
    {
        if (is_array($territoryIsoNr)) {
            $useMinMax = false;
            if (isset($territoryIsoNr['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_TERRITORY_ISO_NR, $territoryIsoNr['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($territoryIsoNr['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_TERRITORY_ISO_NR, $territoryIsoNr['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_TERRITORY_ISO_NR, $territoryIsoNr, $comparison);
    }

    /**
     * Filter the query on the currency_iso_nr column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrencyIsoNr(1234); // WHERE currency_iso_nr = 1234
     * $query->filterByCurrencyIsoNr(array(12, 34)); // WHERE currency_iso_nr IN (12, 34)
     * $query->filterByCurrencyIsoNr(array('min' => 12)); // WHERE currency_iso_nr > 12
     * </code>
     *
     * @see       filterByCurrency()
     *
     * @param     mixed $currencyIsoNr The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByCurrencyIsoNr($currencyIsoNr = null, $comparison = null)
    {
        if (is_array($currencyIsoNr)) {
            $useMinMax = false;
            if (isset($currencyIsoNr['min'])) {
                $this->addUsingAlias(CountryTableMap::COL_CURRENCY_ISO_NR, $currencyIsoNr['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currencyIsoNr['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_CURRENCY_ISO_NR, $currencyIsoNr['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_CURRENCY_ISO_NR, $currencyIsoNr, $comparison);
    }

    /**
     * Filter the query on the official_local_name column
     *
     * Example usage:
     * <code>
     * $query->filterByOfficialLocalName('fooValue');   // WHERE official_local_name = 'fooValue'
     * $query->filterByOfficialLocalName('%fooValue%'); // WHERE official_local_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $officialLocalName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByOfficialLocalName($officialLocalName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($officialLocalName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $officialLocalName)) {
                $officialLocalName = str_replace('*', '%', $officialLocalName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_OFFICIAL_LOCAL_NAME, $officialLocalName, $comparison);
    }

    /**
     * Filter the query on the official_en_name column
     *
     * Example usage:
     * <code>
     * $query->filterByOfficialEnName('fooValue');   // WHERE official_en_name = 'fooValue'
     * $query->filterByOfficialEnName('%fooValue%'); // WHERE official_en_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $officialEnName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByOfficialEnName($officialEnName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($officialEnName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $officialEnName)) {
                $officialEnName = str_replace('*', '%', $officialEnName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_OFFICIAL_EN_NAME, $officialEnName, $comparison);
    }

    /**
     * Filter the query on the short_local_name column
     *
     * Example usage:
     * <code>
     * $query->filterByShortLocalName('fooValue');   // WHERE short_local_name = 'fooValue'
     * $query->filterByShortLocalName('%fooValue%'); // WHERE short_local_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shortLocalName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByShortLocalName($shortLocalName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shortLocalName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $shortLocalName)) {
                $shortLocalName = str_replace('*', '%', $shortLocalName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_SHORT_LOCAL_NAME, $shortLocalName, $comparison);
    }

    /**
     * Filter the query on the short_en_name column
     *
     * Example usage:
     * <code>
     * $query->filterByShortEnName('fooValue');   // WHERE short_en_name = 'fooValue'
     * $query->filterByShortEnName('%fooValue%'); // WHERE short_en_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shortEnName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function filterByShortEnName($shortEnName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shortEnName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $shortEnName)) {
                $shortEnName = str_replace('*', '%', $shortEnName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CountryTableMap::COL_SHORT_EN_NAME, $shortEnName, $comparison);
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
     * Filter the query by a related \keeko\core\model\Territory object
     *
     * @param \keeko\core\model\Territory|ObjectCollection $territory The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterByTerritory($territory, $comparison = null)
    {
        if ($territory instanceof \keeko\core\model\Territory) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_TERRITORY_ISO_NR, $territory->getIsoNr(), $comparison);
        } elseif ($territory instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CountryTableMap::COL_TERRITORY_ISO_NR, $territory->toKeyValue('PrimaryKey', 'IsoNr'), $comparison);
        } else {
            throw new PropelException('filterByTerritory() only accepts arguments of type \keeko\core\model\Territory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Territory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function joinTerritory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Territory');

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
            $this->addJoinObject($join, 'Territory');
        }

        return $this;
    }

    /**
     * Use the Territory relation Territory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\TerritoryQuery A secondary query class using the current class as primary query
     */
    public function useTerritoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTerritory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Territory', '\keeko\core\model\TerritoryQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Currency object
     *
     * @param \keeko\core\model\Currency|ObjectCollection $currency The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterByCurrency($currency, $comparison = null)
    {
        if ($currency instanceof \keeko\core\model\Currency) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_CURRENCY_ISO_NR, $currency->getIsoNr(), $comparison);
        } elseif ($currency instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CountryTableMap::COL_CURRENCY_ISO_NR, $currency->toKeyValue('PrimaryKey', 'IsoNr'), $comparison);
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
     * Filter the query by a related \keeko\core\model\Localization object
     *
     * @param \keeko\core\model\Localization|ObjectCollection $localization  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterByLocalization($localization, $comparison = null)
    {
        if ($localization instanceof \keeko\core\model\Localization) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_ISO_NR, $localization->getCountryIsoNr(), $comparison);
        } elseif ($localization instanceof ObjectCollection) {
            return $this
                ->useLocalizationQuery()
                ->filterByPrimaryKeys($localization->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function joinLocalization($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useLocalizationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLocalization($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Localization', '\keeko\core\model\LocalizationQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Subdivision object
     *
     * @param \keeko\core\model\Subdivision|ObjectCollection $subdivision  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterBySubdivision($subdivision, $comparison = null)
    {
        if ($subdivision instanceof \keeko\core\model\Subdivision) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_ISO_NR, $subdivision->getCountryIsoNr(), $comparison);
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
     * Filter the query by a related \keeko\core\model\User object
     *
     * @param \keeko\core\model\User|ObjectCollection $user  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCountryQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \keeko\core\model\User) {
            return $this
                ->addUsingAlias(CountryTableMap::COL_ISO_NR, $user->getCountryIsoNr(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useUserQuery()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \keeko\core\model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCountryQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\keeko\core\model\UserQuery');
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
            $this->addUsingAlias(CountryTableMap::COL_ISO_NR, $country->getIsoNr(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the keeko_country table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CountryTableMap::clearInstancePool();
            CountryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CountryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CountryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CountryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CountryQuery
