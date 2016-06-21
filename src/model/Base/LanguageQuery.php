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
 * Base class that represents a query for the 'kk_language' table.
 *
 *
 *
 * @method     ChildLanguageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLanguageQuery orderByAlpha2($order = Criteria::ASC) Order by the alpha_2 column
 * @method     ChildLanguageQuery orderByAlpha3T($order = Criteria::ASC) Order by the alpha_3T column
 * @method     ChildLanguageQuery orderByAlpha3B($order = Criteria::ASC) Order by the alpha_3B column
 * @method     ChildLanguageQuery orderByAlpha3($order = Criteria::ASC) Order by the alpha_3 column
 * @method     ChildLanguageQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method     ChildLanguageQuery orderByMacrolanguageStatus($order = Criteria::ASC) Order by the macrolanguage_status column
 * @method     ChildLanguageQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildLanguageQuery orderByNativeName($order = Criteria::ASC) Order by the native_name column
 * @method     ChildLanguageQuery orderByCollate($order = Criteria::ASC) Order by the collate column
 * @method     ChildLanguageQuery orderBySubtag($order = Criteria::ASC) Order by the subtag column
 * @method     ChildLanguageQuery orderByPrefix($order = Criteria::ASC) Order by the prefix column
 * @method     ChildLanguageQuery orderByScopeId($order = Criteria::ASC) Order by the scope_id column
 * @method     ChildLanguageQuery orderByTypeId($order = Criteria::ASC) Order by the type_id column
 * @method     ChildLanguageQuery orderByFamilyId($order = Criteria::ASC) Order by the family_id column
 * @method     ChildLanguageQuery orderByDefaultScriptId($order = Criteria::ASC) Order by the default_script_id column
 *
 * @method     ChildLanguageQuery groupById() Group by the id column
 * @method     ChildLanguageQuery groupByAlpha2() Group by the alpha_2 column
 * @method     ChildLanguageQuery groupByAlpha3T() Group by the alpha_3T column
 * @method     ChildLanguageQuery groupByAlpha3B() Group by the alpha_3B column
 * @method     ChildLanguageQuery groupByAlpha3() Group by the alpha_3 column
 * @method     ChildLanguageQuery groupByParentId() Group by the parent_id column
 * @method     ChildLanguageQuery groupByMacrolanguageStatus() Group by the macrolanguage_status column
 * @method     ChildLanguageQuery groupByName() Group by the name column
 * @method     ChildLanguageQuery groupByNativeName() Group by the native_name column
 * @method     ChildLanguageQuery groupByCollate() Group by the collate column
 * @method     ChildLanguageQuery groupBySubtag() Group by the subtag column
 * @method     ChildLanguageQuery groupByPrefix() Group by the prefix column
 * @method     ChildLanguageQuery groupByScopeId() Group by the scope_id column
 * @method     ChildLanguageQuery groupByTypeId() Group by the type_id column
 * @method     ChildLanguageQuery groupByFamilyId() Group by the family_id column
 * @method     ChildLanguageQuery groupByDefaultScriptId() Group by the default_script_id column
 *
 * @method     ChildLanguageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLanguageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLanguageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLanguageQuery leftJoinParent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Parent relation
 * @method     ChildLanguageQuery rightJoinParent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Parent relation
 * @method     ChildLanguageQuery innerJoinParent($relationAlias = null) Adds a INNER JOIN clause to the query using the Parent relation
 *
 * @method     ChildLanguageQuery leftJoinScope($relationAlias = null) Adds a LEFT JOIN clause to the query using the Scope relation
 * @method     ChildLanguageQuery rightJoinScope($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Scope relation
 * @method     ChildLanguageQuery innerJoinScope($relationAlias = null) Adds a INNER JOIN clause to the query using the Scope relation
 *
 * @method     ChildLanguageQuery leftJoinType($relationAlias = null) Adds a LEFT JOIN clause to the query using the Type relation
 * @method     ChildLanguageQuery rightJoinType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Type relation
 * @method     ChildLanguageQuery innerJoinType($relationAlias = null) Adds a INNER JOIN clause to the query using the Type relation
 *
 * @method     ChildLanguageQuery leftJoinScript($relationAlias = null) Adds a LEFT JOIN clause to the query using the Script relation
 * @method     ChildLanguageQuery rightJoinScript($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Script relation
 * @method     ChildLanguageQuery innerJoinScript($relationAlias = null) Adds a INNER JOIN clause to the query using the Script relation
 *
 * @method     ChildLanguageQuery leftJoinFamily($relationAlias = null) Adds a LEFT JOIN clause to the query using the Family relation
 * @method     ChildLanguageQuery rightJoinFamily($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Family relation
 * @method     ChildLanguageQuery innerJoinFamily($relationAlias = null) Adds a INNER JOIN clause to the query using the Family relation
 *
 * @method     ChildLanguageQuery leftJoinSublanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Sublanguage relation
 * @method     ChildLanguageQuery rightJoinSublanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Sublanguage relation
 * @method     ChildLanguageQuery innerJoinSublanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the Sublanguage relation
 *
 * @method     ChildLanguageQuery leftJoinLocalizationRelatedByLanguageId($relationAlias = null) Adds a LEFT JOIN clause to the query using the LocalizationRelatedByLanguageId relation
 * @method     ChildLanguageQuery rightJoinLocalizationRelatedByLanguageId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LocalizationRelatedByLanguageId relation
 * @method     ChildLanguageQuery innerJoinLocalizationRelatedByLanguageId($relationAlias = null) Adds a INNER JOIN clause to the query using the LocalizationRelatedByLanguageId relation
 *
 * @method     ChildLanguageQuery leftJoinLocalizationRelatedByExtLanguageId($relationAlias = null) Adds a LEFT JOIN clause to the query using the LocalizationRelatedByExtLanguageId relation
 * @method     ChildLanguageQuery rightJoinLocalizationRelatedByExtLanguageId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LocalizationRelatedByExtLanguageId relation
 * @method     ChildLanguageQuery innerJoinLocalizationRelatedByExtLanguageId($relationAlias = null) Adds a INNER JOIN clause to the query using the LocalizationRelatedByExtLanguageId relation
 *
 * @method     \keeko\core\model\LanguageQuery|\keeko\core\model\LanguageScopeQuery|\keeko\core\model\LanguageTypeQuery|\keeko\core\model\LanguageScriptQuery|\keeko\core\model\LanguageFamilyQuery|\keeko\core\model\LocalizationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLanguage findOne(ConnectionInterface $con = null) Return the first ChildLanguage matching the query
 * @method     ChildLanguage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLanguage matching the query, or a new ChildLanguage object populated from the query conditions when no match is found
 *
 * @method     ChildLanguage findOneById(int $id) Return the first ChildLanguage filtered by the id column
 * @method     ChildLanguage findOneByAlpha2(string $alpha_2) Return the first ChildLanguage filtered by the alpha_2 column
 * @method     ChildLanguage findOneByAlpha3T(string $alpha_3T) Return the first ChildLanguage filtered by the alpha_3T column
 * @method     ChildLanguage findOneByAlpha3B(string $alpha_3B) Return the first ChildLanguage filtered by the alpha_3B column
 * @method     ChildLanguage findOneByAlpha3(string $alpha_3) Return the first ChildLanguage filtered by the alpha_3 column
 * @method     ChildLanguage findOneByParentId(int $parent_id) Return the first ChildLanguage filtered by the parent_id column
 * @method     ChildLanguage findOneByMacrolanguageStatus(string $macrolanguage_status) Return the first ChildLanguage filtered by the macrolanguage_status column
 * @method     ChildLanguage findOneByName(string $name) Return the first ChildLanguage filtered by the name column
 * @method     ChildLanguage findOneByNativeName(string $native_name) Return the first ChildLanguage filtered by the native_name column
 * @method     ChildLanguage findOneByCollate(string $collate) Return the first ChildLanguage filtered by the collate column
 * @method     ChildLanguage findOneBySubtag(string $subtag) Return the first ChildLanguage filtered by the subtag column
 * @method     ChildLanguage findOneByPrefix(string $prefix) Return the first ChildLanguage filtered by the prefix column
 * @method     ChildLanguage findOneByScopeId(int $scope_id) Return the first ChildLanguage filtered by the scope_id column
 * @method     ChildLanguage findOneByTypeId(int $type_id) Return the first ChildLanguage filtered by the type_id column
 * @method     ChildLanguage findOneByFamilyId(int $family_id) Return the first ChildLanguage filtered by the family_id column
 * @method     ChildLanguage findOneByDefaultScriptId(int $default_script_id) Return the first ChildLanguage filtered by the default_script_id column *

 * @method     ChildLanguage requirePk($key, ConnectionInterface $con = null) Return the ChildLanguage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOne(ConnectionInterface $con = null) Return the first ChildLanguage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguage requireOneById(int $id) Return the first ChildLanguage filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByAlpha2(string $alpha_2) Return the first ChildLanguage filtered by the alpha_2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByAlpha3T(string $alpha_3T) Return the first ChildLanguage filtered by the alpha_3T column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByAlpha3B(string $alpha_3B) Return the first ChildLanguage filtered by the alpha_3B column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByAlpha3(string $alpha_3) Return the first ChildLanguage filtered by the alpha_3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByParentId(int $parent_id) Return the first ChildLanguage filtered by the parent_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByMacrolanguageStatus(string $macrolanguage_status) Return the first ChildLanguage filtered by the macrolanguage_status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByName(string $name) Return the first ChildLanguage filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByNativeName(string $native_name) Return the first ChildLanguage filtered by the native_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByCollate(string $collate) Return the first ChildLanguage filtered by the collate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneBySubtag(string $subtag) Return the first ChildLanguage filtered by the subtag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByPrefix(string $prefix) Return the first ChildLanguage filtered by the prefix column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByScopeId(int $scope_id) Return the first ChildLanguage filtered by the scope_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByTypeId(int $type_id) Return the first ChildLanguage filtered by the type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByFamilyId(int $family_id) Return the first ChildLanguage filtered by the family_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByDefaultScriptId(int $default_script_id) Return the first ChildLanguage filtered by the default_script_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLanguage objects based on current ModelCriteria
 * @method     ChildLanguage[]|ObjectCollection findById(int $id) Return ChildLanguage objects filtered by the id column
 * @method     ChildLanguage[]|ObjectCollection findByAlpha2(string $alpha_2) Return ChildLanguage objects filtered by the alpha_2 column
 * @method     ChildLanguage[]|ObjectCollection findByAlpha3T(string $alpha_3T) Return ChildLanguage objects filtered by the alpha_3T column
 * @method     ChildLanguage[]|ObjectCollection findByAlpha3B(string $alpha_3B) Return ChildLanguage objects filtered by the alpha_3B column
 * @method     ChildLanguage[]|ObjectCollection findByAlpha3(string $alpha_3) Return ChildLanguage objects filtered by the alpha_3 column
 * @method     ChildLanguage[]|ObjectCollection findByParentId(int $parent_id) Return ChildLanguage objects filtered by the parent_id column
 * @method     ChildLanguage[]|ObjectCollection findByMacrolanguageStatus(string $macrolanguage_status) Return ChildLanguage objects filtered by the macrolanguage_status column
 * @method     ChildLanguage[]|ObjectCollection findByName(string $name) Return ChildLanguage objects filtered by the name column
 * @method     ChildLanguage[]|ObjectCollection findByNativeName(string $native_name) Return ChildLanguage objects filtered by the native_name column
 * @method     ChildLanguage[]|ObjectCollection findByCollate(string $collate) Return ChildLanguage objects filtered by the collate column
 * @method     ChildLanguage[]|ObjectCollection findBySubtag(string $subtag) Return ChildLanguage objects filtered by the subtag column
 * @method     ChildLanguage[]|ObjectCollection findByPrefix(string $prefix) Return ChildLanguage objects filtered by the prefix column
 * @method     ChildLanguage[]|ObjectCollection findByScopeId(int $scope_id) Return ChildLanguage objects filtered by the scope_id column
 * @method     ChildLanguage[]|ObjectCollection findByTypeId(int $type_id) Return ChildLanguage objects filtered by the type_id column
 * @method     ChildLanguage[]|ObjectCollection findByFamilyId(int $family_id) Return ChildLanguage objects filtered by the family_id column
 * @method     ChildLanguage[]|ObjectCollection findByDefaultScriptId(int $default_script_id) Return ChildLanguage objects filtered by the default_script_id column
 * @method     ChildLanguage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LanguageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

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
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLanguage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `alpha_2`, `alpha_3T`, `alpha_3B`, `alpha_3`, `parent_id`, `macrolanguage_status`, `name`, `native_name`, `collate`, `subtag`, `prefix`, `scope_id`, `type_id`, `family_id`, `default_script_id` FROM `kk_language` WHERE `id` = :p0';
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
     * Filter the query on the parent_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentId(1234); // WHERE parent_id = 1234
     * $query->filterByParentId(array(12, 34)); // WHERE parent_id IN (12, 34)
     * $query->filterByParentId(array('min' => 12)); // WHERE parent_id > 12
     * </code>
     *
     * @see       filterByParent()
     *
     * @param     mixed $parentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByParentId($parentId = null, $comparison = null)
    {
        if (is_array($parentId)) {
            $useMinMax = false;
            if (isset($parentId['min'])) {
                $this->addUsingAlias(LanguageTableMap::COL_PARENT_ID, $parentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentId['max'])) {
                $this->addUsingAlias(LanguageTableMap::COL_PARENT_ID, $parentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_PARENT_ID, $parentId, $comparison);
    }

    /**
     * Filter the query on the macrolanguage_status column
     *
     * Example usage:
     * <code>
     * $query->filterByMacrolanguageStatus('fooValue');   // WHERE macrolanguage_status = 'fooValue'
     * $query->filterByMacrolanguageStatus('%fooValue%'); // WHERE macrolanguage_status LIKE '%fooValue%'
     * </code>
     *
     * @param     string $macrolanguageStatus The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByMacrolanguageStatus($macrolanguageStatus = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($macrolanguageStatus)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $macrolanguageStatus)) {
                $macrolanguageStatus = str_replace('*', '%', $macrolanguageStatus);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_MACROLANGUAGE_STATUS, $macrolanguageStatus, $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageTableMap::COL_NATIVE_NAME, $nativeName, $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageTableMap::COL_SUBTAG, $subtag, $comparison);
    }

    /**
     * Filter the query on the prefix column
     *
     * Example usage:
     * <code>
     * $query->filterByPrefix('fooValue');   // WHERE prefix = 'fooValue'
     * $query->filterByPrefix('%fooValue%'); // WHERE prefix LIKE '%fooValue%'
     * </code>
     *
     * @param     string $prefix The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByPrefix($prefix = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($prefix)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $prefix)) {
                $prefix = str_replace('*', '%', $prefix);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_PREFIX, $prefix, $comparison);
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
     * @see       filterByScope()
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
     * @see       filterByType()
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
     * Filter the query on the family_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFamilyId(1234); // WHERE family_id = 1234
     * $query->filterByFamilyId(array(12, 34)); // WHERE family_id IN (12, 34)
     * $query->filterByFamilyId(array('min' => 12)); // WHERE family_id > 12
     * </code>
     *
     * @see       filterByFamily()
     *
     * @param     mixed $familyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByFamilyId($familyId = null, $comparison = null)
    {
        if (is_array($familyId)) {
            $useMinMax = false;
            if (isset($familyId['min'])) {
                $this->addUsingAlias(LanguageTableMap::COL_FAMILY_ID, $familyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($familyId['max'])) {
                $this->addUsingAlias(LanguageTableMap::COL_FAMILY_ID, $familyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_FAMILY_ID, $familyId, $comparison);
    }

    /**
     * Filter the query on the default_script_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultScriptId(1234); // WHERE default_script_id = 1234
     * $query->filterByDefaultScriptId(array(12, 34)); // WHERE default_script_id IN (12, 34)
     * $query->filterByDefaultScriptId(array('min' => 12)); // WHERE default_script_id > 12
     * </code>
     *
     * @see       filterByScript()
     *
     * @param     mixed $defaultScriptId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByDefaultScriptId($defaultScriptId = null, $comparison = null)
    {
        if (is_array($defaultScriptId)) {
            $useMinMax = false;
            if (isset($defaultScriptId['min'])) {
                $this->addUsingAlias(LanguageTableMap::COL_DEFAULT_SCRIPT_ID, $defaultScriptId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($defaultScriptId['max'])) {
                $this->addUsingAlias(LanguageTableMap::COL_DEFAULT_SCRIPT_ID, $defaultScriptId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_DEFAULT_SCRIPT_ID, $defaultScriptId, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Language object
     *
     * @param \keeko\core\model\Language|ObjectCollection $language The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByParent($language, $comparison = null)
    {
        if ($language instanceof \keeko\core\model\Language) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_PARENT_ID, $language->getId(), $comparison);
        } elseif ($language instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LanguageTableMap::COL_PARENT_ID, $language->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByParent() only accepts arguments of type \keeko\core\model\Language or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Parent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinParent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Parent');

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
            $this->addJoinObject($join, 'Parent');
        }

        return $this;
    }

    /**
     * Use the Parent relation Language object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageQuery A secondary query class using the current class as primary query
     */
    public function useParentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinParent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Parent', '\keeko\core\model\LanguageQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\LanguageScope object
     *
     * @param \keeko\core\model\LanguageScope|ObjectCollection $languageScope The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByScope($languageScope, $comparison = null)
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
            throw new PropelException('filterByScope() only accepts arguments of type \keeko\core\model\LanguageScope or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Scope relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinScope($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Scope');

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
            $this->addJoinObject($join, 'Scope');
        }

        return $this;
    }

    /**
     * Use the Scope relation LanguageScope object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageScopeQuery A secondary query class using the current class as primary query
     */
    public function useScopeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinScope($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Scope', '\keeko\core\model\LanguageScopeQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\LanguageType object
     *
     * @param \keeko\core\model\LanguageType|ObjectCollection $languageType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByType($languageType, $comparison = null)
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
            throw new PropelException('filterByType() only accepts arguments of type \keeko\core\model\LanguageType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Type relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * Use the Type relation LanguageType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageTypeQuery A secondary query class using the current class as primary query
     */
    public function useTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Type', '\keeko\core\model\LanguageTypeQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\LanguageScript object
     *
     * @param \keeko\core\model\LanguageScript|ObjectCollection $languageScript The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByScript($languageScript, $comparison = null)
    {
        if ($languageScript instanceof \keeko\core\model\LanguageScript) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_DEFAULT_SCRIPT_ID, $languageScript->getId(), $comparison);
        } elseif ($languageScript instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LanguageTableMap::COL_DEFAULT_SCRIPT_ID, $languageScript->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByScript() only accepts arguments of type \keeko\core\model\LanguageScript or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Script relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinScript($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Script');

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
            $this->addJoinObject($join, 'Script');
        }

        return $this;
    }

    /**
     * Use the Script relation LanguageScript object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageScriptQuery A secondary query class using the current class as primary query
     */
    public function useScriptQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinScript($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Script', '\keeko\core\model\LanguageScriptQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\LanguageFamily object
     *
     * @param \keeko\core\model\LanguageFamily|ObjectCollection $languageFamily The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByFamily($languageFamily, $comparison = null)
    {
        if ($languageFamily instanceof \keeko\core\model\LanguageFamily) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_FAMILY_ID, $languageFamily->getId(), $comparison);
        } elseif ($languageFamily instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LanguageTableMap::COL_FAMILY_ID, $languageFamily->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFamily() only accepts arguments of type \keeko\core\model\LanguageFamily or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Family relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinFamily($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Family');

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
            $this->addJoinObject($join, 'Family');
        }

        return $this;
    }

    /**
     * Use the Family relation LanguageFamily object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageFamilyQuery A secondary query class using the current class as primary query
     */
    public function useFamilyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFamily($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Family', '\keeko\core\model\LanguageFamilyQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Language object
     *
     * @param \keeko\core\model\Language|ObjectCollection $language the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterBySublanguage($language, $comparison = null)
    {
        if ($language instanceof \keeko\core\model\Language) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_ID, $language->getParentId(), $comparison);
        } elseif ($language instanceof ObjectCollection) {
            return $this
                ->useSublanguageQuery()
                ->filterByPrimaryKeys($language->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySublanguage() only accepts arguments of type \keeko\core\model\Language or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Sublanguage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinSublanguage($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Sublanguage');

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
            $this->addJoinObject($join, 'Sublanguage');
        }

        return $this;
    }

    /**
     * Use the Sublanguage relation Language object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageQuery A secondary query class using the current class as primary query
     */
    public function useSublanguageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSublanguage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Sublanguage', '\keeko\core\model\LanguageQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Localization object
     *
     * @param \keeko\core\model\Localization|ObjectCollection $localization the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByLocalizationRelatedByLanguageId($localization, $comparison = null)
    {
        if ($localization instanceof \keeko\core\model\Localization) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_ID, $localization->getLanguageId(), $comparison);
        } elseif ($localization instanceof ObjectCollection) {
            return $this
                ->useLocalizationRelatedByLanguageIdQuery()
                ->filterByPrimaryKeys($localization->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLocalizationRelatedByLanguageId() only accepts arguments of type \keeko\core\model\Localization or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LocalizationRelatedByLanguageId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinLocalizationRelatedByLanguageId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LocalizationRelatedByLanguageId');

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
            $this->addJoinObject($join, 'LocalizationRelatedByLanguageId');
        }

        return $this;
    }

    /**
     * Use the LocalizationRelatedByLanguageId relation Localization object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LocalizationQuery A secondary query class using the current class as primary query
     */
    public function useLocalizationRelatedByLanguageIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLocalizationRelatedByLanguageId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LocalizationRelatedByLanguageId', '\keeko\core\model\LocalizationQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Localization object
     *
     * @param \keeko\core\model\Localization|ObjectCollection $localization the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByLocalizationRelatedByExtLanguageId($localization, $comparison = null)
    {
        if ($localization instanceof \keeko\core\model\Localization) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_ID, $localization->getExtLanguageId(), $comparison);
        } elseif ($localization instanceof ObjectCollection) {
            return $this
                ->useLocalizationRelatedByExtLanguageIdQuery()
                ->filterByPrimaryKeys($localization->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLocalizationRelatedByExtLanguageId() only accepts arguments of type \keeko\core\model\Localization or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LocalizationRelatedByExtLanguageId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinLocalizationRelatedByExtLanguageId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LocalizationRelatedByExtLanguageId');

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
            $this->addJoinObject($join, 'LocalizationRelatedByExtLanguageId');
        }

        return $this;
    }

    /**
     * Use the LocalizationRelatedByExtLanguageId relation Localization object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LocalizationQuery A secondary query class using the current class as primary query
     */
    public function useLocalizationRelatedByExtLanguageIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLocalizationRelatedByExtLanguageId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LocalizationRelatedByExtLanguageId', '\keeko\core\model\LocalizationQuery');
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

} // LanguageQuery
