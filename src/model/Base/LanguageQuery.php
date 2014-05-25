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
use keeko\core\model\Language as ChildLanguage;
use keeko\core\model\LanguageQuery as ChildLanguageQuery;
use keeko\core\model\Map\LanguageTableMap;

/**
 * Base class that represents a query for the 'keeko_language' table.
 *
 *
 *
 * @method     ChildLanguageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLanguageQuery orderByAlpha2($order = Criteria::ASC) Order by the alpha_2 column
 * @method     ChildLanguageQuery orderByAlpha3T($order = Criteria::ASC) Order by the alpha_3T column
 * @method     ChildLanguageQuery orderByAlpha3B($order = Criteria::ASC) Order by the alpha_3B column
 * @method     ChildLanguageQuery orderByAlpha3($order = Criteria::ASC) Order by the alpha_3 column
 * @method     ChildLanguageQuery orderByLocalName($order = Criteria::ASC) Order by the local_name column
 * @method     ChildLanguageQuery orderByEnName($order = Criteria::ASC) Order by the en_name column
 * @method     ChildLanguageQuery orderByCollate($order = Criteria::ASC) Order by the collate column
 * @method     ChildLanguageQuery orderByScopeId($order = Criteria::ASC) Order by the scope_id column
 * @method     ChildLanguageQuery orderByTypeId($order = Criteria::ASC) Order by the type_id column
 *
 * @method     ChildLanguageQuery groupById() Group by the id column
 * @method     ChildLanguageQuery groupByAlpha2() Group by the alpha_2 column
 * @method     ChildLanguageQuery groupByAlpha3T() Group by the alpha_3T column
 * @method     ChildLanguageQuery groupByAlpha3B() Group by the alpha_3B column
 * @method     ChildLanguageQuery groupByAlpha3() Group by the alpha_3 column
 * @method     ChildLanguageQuery groupByLocalName() Group by the local_name column
 * @method     ChildLanguageQuery groupByEnName() Group by the en_name column
 * @method     ChildLanguageQuery groupByCollate() Group by the collate column
 * @method     ChildLanguageQuery groupByScopeId() Group by the scope_id column
 * @method     ChildLanguageQuery groupByTypeId() Group by the type_id column
 *
 * @method     ChildLanguageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLanguageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLanguageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLanguageQuery leftJoinLanguageScope($relationAlias = null) Adds a LEFT JOIN clause to the query using the LanguageScope relation
 * @method     ChildLanguageQuery rightJoinLanguageScope($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LanguageScope relation
 * @method     ChildLanguageQuery innerJoinLanguageScope($relationAlias = null) Adds a INNER JOIN clause to the query using the LanguageScope relation
 *
 * @method     ChildLanguageQuery leftJoinLanguageType($relationAlias = null) Adds a LEFT JOIN clause to the query using the LanguageType relation
 * @method     ChildLanguageQuery rightJoinLanguageType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LanguageType relation
 * @method     ChildLanguageQuery innerJoinLanguageType($relationAlias = null) Adds a INNER JOIN clause to the query using the LanguageType relation
 *
 * @method     ChildLanguageQuery leftJoinLocalization($relationAlias = null) Adds a LEFT JOIN clause to the query using the Localization relation
 * @method     ChildLanguageQuery rightJoinLocalization($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Localization relation
 * @method     ChildLanguageQuery innerJoinLocalization($relationAlias = null) Adds a INNER JOIN clause to the query using the Localization relation
 *
 * @method     \keeko\core\model\LanguageScopeQuery|\keeko\core\model\LanguageTypeQuery|\keeko\core\model\LocalizationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLanguage findOne(ConnectionInterface $con = null) Return the first ChildLanguage matching the query
 * @method     ChildLanguage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLanguage matching the query, or a new ChildLanguage object populated from the query conditions when no match is found
 *
 * @method     ChildLanguage findOneById(int $id) Return the first ChildLanguage filtered by the id column
 * @method     ChildLanguage findOneByAlpha2(string $alpha_2) Return the first ChildLanguage filtered by the alpha_2 column
 * @method     ChildLanguage findOneByAlpha3T(string $alpha_3T) Return the first ChildLanguage filtered by the alpha_3T column
 * @method     ChildLanguage findOneByAlpha3B(string $alpha_3B) Return the first ChildLanguage filtered by the alpha_3B column
 * @method     ChildLanguage findOneByAlpha3(string $alpha_3) Return the first ChildLanguage filtered by the alpha_3 column
 * @method     ChildLanguage findOneByLocalName(string $local_name) Return the first ChildLanguage filtered by the local_name column
 * @method     ChildLanguage findOneByEnName(string $en_name) Return the first ChildLanguage filtered by the en_name column
 * @method     ChildLanguage findOneByCollate(string $collate) Return the first ChildLanguage filtered by the collate column
 * @method     ChildLanguage findOneByScopeId(int $scope_id) Return the first ChildLanguage filtered by the scope_id column
 * @method     ChildLanguage findOneByTypeId(int $type_id) Return the first ChildLanguage filtered by the type_id column
 *
 * @method     ChildLanguage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLanguage objects based on current ModelCriteria
 * @method     ChildLanguage[]|ObjectCollection findById(int $id) Return ChildLanguage objects filtered by the id column
 * @method     ChildLanguage[]|ObjectCollection findByAlpha2(string $alpha_2) Return ChildLanguage objects filtered by the alpha_2 column
 * @method     ChildLanguage[]|ObjectCollection findByAlpha3T(string $alpha_3T) Return ChildLanguage objects filtered by the alpha_3T column
 * @method     ChildLanguage[]|ObjectCollection findByAlpha3B(string $alpha_3B) Return ChildLanguage objects filtered by the alpha_3B column
 * @method     ChildLanguage[]|ObjectCollection findByAlpha3(string $alpha_3) Return ChildLanguage objects filtered by the alpha_3 column
 * @method     ChildLanguage[]|ObjectCollection findByLocalName(string $local_name) Return ChildLanguage objects filtered by the local_name column
 * @method     ChildLanguage[]|ObjectCollection findByEnName(string $en_name) Return ChildLanguage objects filtered by the en_name column
 * @method     ChildLanguage[]|ObjectCollection findByCollate(string $collate) Return ChildLanguage objects filtered by the collate column
 * @method     ChildLanguage[]|ObjectCollection findByScopeId(int $scope_id) Return ChildLanguage objects filtered by the scope_id column
 * @method     ChildLanguage[]|ObjectCollection findByTypeId(int $type_id) Return ChildLanguage objects filtered by the type_id column
 * @method     ChildLanguage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LanguageQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \keeko\core\model\Base\LanguageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Language', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLanguageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLanguageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLanguageQuery) {
            return $criteria;
        }
        $query = new ChildLanguageQuery();
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
     * @return ChildLanguage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LanguageTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LanguageTableMap::DATABASE_NAME);
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
     * @return ChildLanguage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ID, ALPHA_2, ALPHA_3T, ALPHA_3B, ALPHA_3, LOCAL_NAME, EN_NAME, COLLATE, SCOPE_ID, TYPE_ID FROM keeko_language WHERE ID = :p0';
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
            /** @var ChildLanguage $obj */
            $obj = new ChildLanguage();
            $obj->hydrate($row);
            LanguageTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildLanguage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LanguageTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LanguageTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LanguageTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LanguageTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageTableMap::COL_ALPHA_2, $alpha2, $comparison);
    }

    /**
     * Filter the query on the alpha_3T column
     *
     * Example usage:
     * <code>
     * $query->filterByAlpha3T('fooValue');   // WHERE alpha_3T = 'fooValue'
     * $query->filterByAlpha3T('%fooValue%'); // WHERE alpha_3T LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alpha3T The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByAlpha3T($alpha3T = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alpha3T)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alpha3T)) {
                $alpha3T = str_replace('*', '%', $alpha3T);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_ALPHA_3T, $alpha3T, $comparison);
    }

    /**
     * Filter the query on the alpha_3B column
     *
     * Example usage:
     * <code>
     * $query->filterByAlpha3B('fooValue');   // WHERE alpha_3B = 'fooValue'
     * $query->filterByAlpha3B('%fooValue%'); // WHERE alpha_3B LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alpha3B The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByAlpha3B($alpha3B = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alpha3B)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alpha3B)) {
                $alpha3B = str_replace('*', '%', $alpha3B);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_ALPHA_3B, $alpha3B, $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageTableMap::COL_ALPHA_3, $alpha3, $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageTableMap::COL_LOCAL_NAME, $localName, $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageTableMap::COL_EN_NAME, $enName, $comparison);
    }

    /**
     * Filter the query on the collate column
     *
     * Example usage:
     * <code>
     * $query->filterByCollate('fooValue');   // WHERE collate = 'fooValue'
     * $query->filterByCollate('%fooValue%'); // WHERE collate LIKE '%fooValue%'
     * </code>
     *
     * @param     string $collate The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByCollate($collate = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($collate)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $collate)) {
                $collate = str_replace('*', '%', $collate);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_COLLATE, $collate, $comparison);
    }

    /**
     * Filter the query on the scope_id column
     *
     * Example usage:
     * <code>
     * $query->filterByScopeId(1234); // WHERE scope_id = 1234
     * $query->filterByScopeId(array(12, 34)); // WHERE scope_id IN (12, 34)
     * $query->filterByScopeId(array('min' => 12)); // WHERE scope_id > 12
     * </code>
     *
     * @see       filterByLanguageScope()
     *
     * @param     mixed $scopeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByScopeId($scopeId = null, $comparison = null)
    {
        if (is_array($scopeId)) {
            $useMinMax = false;
            if (isset($scopeId['min'])) {
                $this->addUsingAlias(LanguageTableMap::COL_SCOPE_ID, $scopeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($scopeId['max'])) {
                $this->addUsingAlias(LanguageTableMap::COL_SCOPE_ID, $scopeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_SCOPE_ID, $scopeId, $comparison);
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
     * @see       filterByLanguageType()
     *
     * @param     mixed $typeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByTypeId($typeId = null, $comparison = null)
    {
        if (is_array($typeId)) {
            $useMinMax = false;
            if (isset($typeId['min'])) {
                $this->addUsingAlias(LanguageTableMap::COL_TYPE_ID, $typeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($typeId['max'])) {
                $this->addUsingAlias(LanguageTableMap::COL_TYPE_ID, $typeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_TYPE_ID, $typeId, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\LanguageScope object
     *
     * @param \keeko\core\model\LanguageScope|ObjectCollection $languageScope The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByLanguageScope($languageScope, $comparison = null)
    {
        if ($languageScope instanceof \keeko\core\model\LanguageScope) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_SCOPE_ID, $languageScope->getId(), $comparison);
        } elseif ($languageScope instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LanguageTableMap::COL_SCOPE_ID, $languageScope->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLanguageScope() only accepts arguments of type \keeko\core\model\LanguageScope or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LanguageScope relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinLanguageScope($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LanguageScope');

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
            $this->addJoinObject($join, 'LanguageScope');
        }

        return $this;
    }

    /**
     * Use the LanguageScope relation LanguageScope object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageScopeQuery A secondary query class using the current class as primary query
     */
    public function useLanguageScopeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLanguageScope($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LanguageScope', '\keeko\core\model\LanguageScopeQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\LanguageType object
     *
     * @param \keeko\core\model\LanguageType|ObjectCollection $languageType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByLanguageType($languageType, $comparison = null)
    {
        if ($languageType instanceof \keeko\core\model\LanguageType) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_TYPE_ID, $languageType->getId(), $comparison);
        } elseif ($languageType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LanguageTableMap::COL_TYPE_ID, $languageType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLanguageType() only accepts arguments of type \keeko\core\model\LanguageType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LanguageType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinLanguageType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LanguageType');

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
            $this->addJoinObject($join, 'LanguageType');
        }

        return $this;
    }

    /**
     * Use the LanguageType relation LanguageType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageTypeQuery A secondary query class using the current class as primary query
     */
    public function useLanguageTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLanguageType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LanguageType', '\keeko\core\model\LanguageTypeQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Localization object
     *
     * @param \keeko\core\model\Localization|ObjectCollection $localization  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByLocalization($localization, $comparison = null)
    {
        if ($localization instanceof \keeko\core\model\Localization) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_ID, $localization->getLanguageId(), $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildLanguage $language Object to remove from the list of results
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function prune($language = null)
    {
        if ($language) {
            $this->addUsingAlias(LanguageTableMap::COL_ID, $language->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the keeko_language table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LanguageTableMap::clearInstancePool();
            LanguageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LanguageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LanguageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LanguageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LanguageQuery
