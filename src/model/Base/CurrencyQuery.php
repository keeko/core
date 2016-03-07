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
use keeko\core\model\Currency as ChildCurrency;
use keeko\core\model\CurrencyQuery as ChildCurrencyQuery;
use keeko\core\model\Map\CurrencyTableMap;

/**
 * Base class that represents a query for the 'kk_currency' table.
 *
 *
 *
 * @method     ChildCurrencyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCurrencyQuery orderByNumeric($order = Criteria::ASC) Order by the numeric column
 * @method     ChildCurrencyQuery orderByAlpha3($order = Criteria::ASC) Order by the alpha_3 column
 * @method     ChildCurrencyQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCurrencyQuery orderBySymbolLeft($order = Criteria::ASC) Order by the symbol_left column
 * @method     ChildCurrencyQuery orderBySymbolRight($order = Criteria::ASC) Order by the symbol_right column
 * @method     ChildCurrencyQuery orderByDecimalDigits($order = Criteria::ASC) Order by the decimal_digits column
 * @method     ChildCurrencyQuery orderBySubDivisor($order = Criteria::ASC) Order by the sub_divisor column
 * @method     ChildCurrencyQuery orderBySubSymbolLeft($order = Criteria::ASC) Order by the sub_symbol_left column
 * @method     ChildCurrencyQuery orderBySubSymbolRight($order = Criteria::ASC) Order by the sub_symbol_right column
 *
 * @method     ChildCurrencyQuery groupById() Group by the id column
 * @method     ChildCurrencyQuery groupByNumeric() Group by the numeric column
 * @method     ChildCurrencyQuery groupByAlpha3() Group by the alpha_3 column
 * @method     ChildCurrencyQuery groupByName() Group by the name column
 * @method     ChildCurrencyQuery groupBySymbolLeft() Group by the symbol_left column
 * @method     ChildCurrencyQuery groupBySymbolRight() Group by the symbol_right column
 * @method     ChildCurrencyQuery groupByDecimalDigits() Group by the decimal_digits column
 * @method     ChildCurrencyQuery groupBySubDivisor() Group by the sub_divisor column
 * @method     ChildCurrencyQuery groupBySubSymbolLeft() Group by the sub_symbol_left column
 * @method     ChildCurrencyQuery groupBySubSymbolRight() Group by the sub_symbol_right column
 *
 * @method     ChildCurrencyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCurrencyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCurrencyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCurrencyQuery leftJoinCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Country relation
 * @method     ChildCurrencyQuery rightJoinCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Country relation
 * @method     ChildCurrencyQuery innerJoinCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the Country relation
 *
 * @method     \keeko\core\model\CountryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCurrency findOne(ConnectionInterface $con = null) Return the first ChildCurrency matching the query
 * @method     ChildCurrency findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCurrency matching the query, or a new ChildCurrency object populated from the query conditions when no match is found
 *
 * @method     ChildCurrency findOneById(int $id) Return the first ChildCurrency filtered by the id column
 * @method     ChildCurrency findOneByNumeric(int $numeric) Return the first ChildCurrency filtered by the numeric column
 * @method     ChildCurrency findOneByAlpha3(string $alpha_3) Return the first ChildCurrency filtered by the alpha_3 column
 * @method     ChildCurrency findOneByName(string $name) Return the first ChildCurrency filtered by the name column
 * @method     ChildCurrency findOneBySymbolLeft(string $symbol_left) Return the first ChildCurrency filtered by the symbol_left column
 * @method     ChildCurrency findOneBySymbolRight(string $symbol_right) Return the first ChildCurrency filtered by the symbol_right column
 * @method     ChildCurrency findOneByDecimalDigits(int $decimal_digits) Return the first ChildCurrency filtered by the decimal_digits column
 * @method     ChildCurrency findOneBySubDivisor(int $sub_divisor) Return the first ChildCurrency filtered by the sub_divisor column
 * @method     ChildCurrency findOneBySubSymbolLeft(string $sub_symbol_left) Return the first ChildCurrency filtered by the sub_symbol_left column
 * @method     ChildCurrency findOneBySubSymbolRight(string $sub_symbol_right) Return the first ChildCurrency filtered by the sub_symbol_right column *

 * @method     ChildCurrency requirePk($key, ConnectionInterface $con = null) Return the ChildCurrency by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOne(ConnectionInterface $con = null) Return the first ChildCurrency matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCurrency requireOneById(int $id) Return the first ChildCurrency filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneByNumeric(int $numeric) Return the first ChildCurrency filtered by the numeric column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneByAlpha3(string $alpha_3) Return the first ChildCurrency filtered by the alpha_3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneByName(string $name) Return the first ChildCurrency filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneBySymbolLeft(string $symbol_left) Return the first ChildCurrency filtered by the symbol_left column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneBySymbolRight(string $symbol_right) Return the first ChildCurrency filtered by the symbol_right column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneByDecimalDigits(int $decimal_digits) Return the first ChildCurrency filtered by the decimal_digits column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneBySubDivisor(int $sub_divisor) Return the first ChildCurrency filtered by the sub_divisor column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneBySubSymbolLeft(string $sub_symbol_left) Return the first ChildCurrency filtered by the sub_symbol_left column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneBySubSymbolRight(string $sub_symbol_right) Return the first ChildCurrency filtered by the sub_symbol_right column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCurrency[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCurrency objects based on current ModelCriteria
 * @method     ChildCurrency[]|ObjectCollection findById(int $id) Return ChildCurrency objects filtered by the id column
 * @method     ChildCurrency[]|ObjectCollection findByNumeric(int $numeric) Return ChildCurrency objects filtered by the numeric column
 * @method     ChildCurrency[]|ObjectCollection findByAlpha3(string $alpha_3) Return ChildCurrency objects filtered by the alpha_3 column
 * @method     ChildCurrency[]|ObjectCollection findByName(string $name) Return ChildCurrency objects filtered by the name column
 * @method     ChildCurrency[]|ObjectCollection findBySymbolLeft(string $symbol_left) Return ChildCurrency objects filtered by the symbol_left column
 * @method     ChildCurrency[]|ObjectCollection findBySymbolRight(string $symbol_right) Return ChildCurrency objects filtered by the symbol_right column
 * @method     ChildCurrency[]|ObjectCollection findByDecimalDigits(int $decimal_digits) Return ChildCurrency objects filtered by the decimal_digits column
 * @method     ChildCurrency[]|ObjectCollection findBySubDivisor(int $sub_divisor) Return ChildCurrency objects filtered by the sub_divisor column
 * @method     ChildCurrency[]|ObjectCollection findBySubSymbolLeft(string $sub_symbol_left) Return ChildCurrency objects filtered by the sub_symbol_left column
 * @method     ChildCurrency[]|ObjectCollection findBySubSymbolRight(string $sub_symbol_right) Return ChildCurrency objects filtered by the sub_symbol_right column
 * @method     ChildCurrency[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CurrencyQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\CurrencyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Currency', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCurrencyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCurrencyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCurrencyQuery) {
            return $criteria;
        }
        $query = new ChildCurrencyQuery();
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
     * @return ChildCurrency|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CurrencyTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyTableMap::DATABASE_NAME);
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
     * @return ChildCurrency A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `numeric`, `alpha_3`, `name`, `symbol_left`, `symbol_right`, `decimal_digits`, `sub_divisor`, `sub_symbol_left`, `sub_symbol_right` FROM `kk_currency` WHERE `id` = :p0';
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
            /** @var ChildCurrency $obj */
            $obj = new ChildCurrency();
            $obj->hydrate($row);
            CurrencyTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCurrency|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CurrencyTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CurrencyTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByNumeric($numeric = null, $comparison = null)
    {
        if (is_array($numeric)) {
            $useMinMax = false;
            if (isset($numeric['min'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_NUMERIC, $numeric['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numeric['max'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_NUMERIC, $numeric['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_NUMERIC, $numeric, $comparison);
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
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CurrencyTableMap::COL_ALPHA_3, $alpha3, $comparison);
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
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CurrencyTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the symbol_left column
     *
     * Example usage:
     * <code>
     * $query->filterBySymbolLeft('fooValue');   // WHERE symbol_left = 'fooValue'
     * $query->filterBySymbolLeft('%fooValue%'); // WHERE symbol_left LIKE '%fooValue%'
     * </code>
     *
     * @param     string $symbolLeft The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterBySymbolLeft($symbolLeft = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($symbolLeft)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $symbolLeft)) {
                $symbolLeft = str_replace('*', '%', $symbolLeft);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_SYMBOL_LEFT, $symbolLeft, $comparison);
    }

    /**
     * Filter the query on the symbol_right column
     *
     * Example usage:
     * <code>
     * $query->filterBySymbolRight('fooValue');   // WHERE symbol_right = 'fooValue'
     * $query->filterBySymbolRight('%fooValue%'); // WHERE symbol_right LIKE '%fooValue%'
     * </code>
     *
     * @param     string $symbolRight The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterBySymbolRight($symbolRight = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($symbolRight)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $symbolRight)) {
                $symbolRight = str_replace('*', '%', $symbolRight);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_SYMBOL_RIGHT, $symbolRight, $comparison);
    }

    /**
     * Filter the query on the decimal_digits column
     *
     * Example usage:
     * <code>
     * $query->filterByDecimalDigits(1234); // WHERE decimal_digits = 1234
     * $query->filterByDecimalDigits(array(12, 34)); // WHERE decimal_digits IN (12, 34)
     * $query->filterByDecimalDigits(array('min' => 12)); // WHERE decimal_digits > 12
     * </code>
     *
     * @param     mixed $decimalDigits The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByDecimalDigits($decimalDigits = null, $comparison = null)
    {
        if (is_array($decimalDigits)) {
            $useMinMax = false;
            if (isset($decimalDigits['min'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_DECIMAL_DIGITS, $decimalDigits['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($decimalDigits['max'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_DECIMAL_DIGITS, $decimalDigits['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_DECIMAL_DIGITS, $decimalDigits, $comparison);
    }

    /**
     * Filter the query on the sub_divisor column
     *
     * Example usage:
     * <code>
     * $query->filterBySubDivisor(1234); // WHERE sub_divisor = 1234
     * $query->filterBySubDivisor(array(12, 34)); // WHERE sub_divisor IN (12, 34)
     * $query->filterBySubDivisor(array('min' => 12)); // WHERE sub_divisor > 12
     * </code>
     *
     * @param     mixed $subDivisor The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterBySubDivisor($subDivisor = null, $comparison = null)
    {
        if (is_array($subDivisor)) {
            $useMinMax = false;
            if (isset($subDivisor['min'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_SUB_DIVISOR, $subDivisor['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subDivisor['max'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_SUB_DIVISOR, $subDivisor['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_SUB_DIVISOR, $subDivisor, $comparison);
    }

    /**
     * Filter the query on the sub_symbol_left column
     *
     * Example usage:
     * <code>
     * $query->filterBySubSymbolLeft('fooValue');   // WHERE sub_symbol_left = 'fooValue'
     * $query->filterBySubSymbolLeft('%fooValue%'); // WHERE sub_symbol_left LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subSymbolLeft The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterBySubSymbolLeft($subSymbolLeft = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subSymbolLeft)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subSymbolLeft)) {
                $subSymbolLeft = str_replace('*', '%', $subSymbolLeft);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_SUB_SYMBOL_LEFT, $subSymbolLeft, $comparison);
    }

    /**
     * Filter the query on the sub_symbol_right column
     *
     * Example usage:
     * <code>
     * $query->filterBySubSymbolRight('fooValue');   // WHERE sub_symbol_right = 'fooValue'
     * $query->filterBySubSymbolRight('%fooValue%'); // WHERE sub_symbol_right LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subSymbolRight The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterBySubSymbolRight($subSymbolRight = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subSymbolRight)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subSymbolRight)) {
                $subSymbolRight = str_replace('*', '%', $subSymbolRight);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_SUB_SYMBOL_RIGHT, $subSymbolRight, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Country object
     *
     * @param \keeko\core\model\Country|ObjectCollection $country the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByCountry($country, $comparison = null)
    {
        if ($country instanceof \keeko\core\model\Country) {
            return $this
                ->addUsingAlias(CurrencyTableMap::COL_ID, $country->getCurrencyId(), $comparison);
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
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
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
     * @param   ChildCurrency $currency Object to remove from the list of results
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function prune($currency = null)
    {
        if ($currency) {
            $this->addUsingAlias(CurrencyTableMap::COL_ID, $currency->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

} // CurrencyQuery
