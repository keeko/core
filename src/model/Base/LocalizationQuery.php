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
use keeko\core\model\Localization as ChildLocalization;
use keeko\core\model\LocalizationQuery as ChildLocalizationQuery;
use keeko\core\model\Map\LocalizationTableMap;

/**
 * Base class that represents a query for the 'kk_localization' table.
 *
 *
 *
 * @method     ChildLocalizationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLocalizationQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method     ChildLocalizationQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildLocalizationQuery orderByLocale($order = Criteria::ASC) Order by the locale column
 * @method     ChildLocalizationQuery orderByLanguageId($order = Criteria::ASC) Order by the language_id column
 * @method     ChildLocalizationQuery orderByExtLanguageId($order = Criteria::ASC) Order by the ext_language_id column
 * @method     ChildLocalizationQuery orderByRegion($order = Criteria::ASC) Order by the region column
 * @method     ChildLocalizationQuery orderByScriptId($order = Criteria::ASC) Order by the script_id column
 * @method     ChildLocalizationQuery orderByIsDefault($order = Criteria::ASC) Order by the is_default column
 *
 * @method     ChildLocalizationQuery groupById() Group by the id column
 * @method     ChildLocalizationQuery groupByParentId() Group by the parent_id column
 * @method     ChildLocalizationQuery groupByName() Group by the name column
 * @method     ChildLocalizationQuery groupByLocale() Group by the locale column
 * @method     ChildLocalizationQuery groupByLanguageId() Group by the language_id column
 * @method     ChildLocalizationQuery groupByExtLanguageId() Group by the ext_language_id column
 * @method     ChildLocalizationQuery groupByRegion() Group by the region column
 * @method     ChildLocalizationQuery groupByScriptId() Group by the script_id column
 * @method     ChildLocalizationQuery groupByIsDefault() Group by the is_default column
 *
 * @method     ChildLocalizationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLocalizationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLocalizationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLocalizationQuery leftJoinParent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Parent relation
 * @method     ChildLocalizationQuery rightJoinParent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Parent relation
 * @method     ChildLocalizationQuery innerJoinParent($relationAlias = null) Adds a INNER JOIN clause to the query using the Parent relation
 *
 * @method     ChildLocalizationQuery leftJoinLanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Language relation
 * @method     ChildLocalizationQuery rightJoinLanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Language relation
 * @method     ChildLocalizationQuery innerJoinLanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the Language relation
 *
 * @method     ChildLocalizationQuery leftJoinExtLang($relationAlias = null) Adds a LEFT JOIN clause to the query using the ExtLang relation
 * @method     ChildLocalizationQuery rightJoinExtLang($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ExtLang relation
 * @method     ChildLocalizationQuery innerJoinExtLang($relationAlias = null) Adds a INNER JOIN clause to the query using the ExtLang relation
 *
 * @method     ChildLocalizationQuery leftJoinScript($relationAlias = null) Adds a LEFT JOIN clause to the query using the Script relation
 * @method     ChildLocalizationQuery rightJoinScript($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Script relation
 * @method     ChildLocalizationQuery innerJoinScript($relationAlias = null) Adds a INNER JOIN clause to the query using the Script relation
 *
 * @method     ChildLocalizationQuery leftJoinLocalizationRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the LocalizationRelatedById relation
 * @method     ChildLocalizationQuery rightJoinLocalizationRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LocalizationRelatedById relation
 * @method     ChildLocalizationQuery innerJoinLocalizationRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the LocalizationRelatedById relation
 *
 * @method     ChildLocalizationQuery leftJoinLocalizationVariant($relationAlias = null) Adds a LEFT JOIN clause to the query using the LocalizationVariant relation
 * @method     ChildLocalizationQuery rightJoinLocalizationVariant($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LocalizationVariant relation
 * @method     ChildLocalizationQuery innerJoinLocalizationVariant($relationAlias = null) Adds a INNER JOIN clause to the query using the LocalizationVariant relation
 *
 * @method     ChildLocalizationQuery leftJoinApplicationUri($relationAlias = null) Adds a LEFT JOIN clause to the query using the ApplicationUri relation
 * @method     ChildLocalizationQuery rightJoinApplicationUri($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ApplicationUri relation
 * @method     ChildLocalizationQuery innerJoinApplicationUri($relationAlias = null) Adds a INNER JOIN clause to the query using the ApplicationUri relation
 *
 * @method     \keeko\core\model\LocalizationQuery|\keeko\core\model\LanguageQuery|\keeko\core\model\LanguageScriptQuery|\keeko\core\model\LocalizationVariantQuery|\keeko\core\model\ApplicationUriQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLocalization findOne(ConnectionInterface $con = null) Return the first ChildLocalization matching the query
 * @method     ChildLocalization findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLocalization matching the query, or a new ChildLocalization object populated from the query conditions when no match is found
 *
 * @method     ChildLocalization findOneById(int $id) Return the first ChildLocalization filtered by the id column
 * @method     ChildLocalization findOneByParentId(int $parent_id) Return the first ChildLocalization filtered by the parent_id column
 * @method     ChildLocalization findOneByName(string $name) Return the first ChildLocalization filtered by the name column
 * @method     ChildLocalization findOneByLocale(string $locale) Return the first ChildLocalization filtered by the locale column
 * @method     ChildLocalization findOneByLanguageId(int $language_id) Return the first ChildLocalization filtered by the language_id column
 * @method     ChildLocalization findOneByExtLanguageId(int $ext_language_id) Return the first ChildLocalization filtered by the ext_language_id column
 * @method     ChildLocalization findOneByRegion(string $region) Return the first ChildLocalization filtered by the region column
 * @method     ChildLocalization findOneByScriptId(int $script_id) Return the first ChildLocalization filtered by the script_id column
 * @method     ChildLocalization findOneByIsDefault(boolean $is_default) Return the first ChildLocalization filtered by the is_default column *

 * @method     ChildLocalization requirePk($key, ConnectionInterface $con = null) Return the ChildLocalization by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOne(ConnectionInterface $con = null) Return the first ChildLocalization matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocalization requireOneById(int $id) Return the first ChildLocalization filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOneByParentId(int $parent_id) Return the first ChildLocalization filtered by the parent_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOneByName(string $name) Return the first ChildLocalization filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOneByLocale(string $locale) Return the first ChildLocalization filtered by the locale column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOneByLanguageId(int $language_id) Return the first ChildLocalization filtered by the language_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOneByExtLanguageId(int $ext_language_id) Return the first ChildLocalization filtered by the ext_language_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOneByRegion(string $region) Return the first ChildLocalization filtered by the region column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOneByScriptId(int $script_id) Return the first ChildLocalization filtered by the script_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocalization requireOneByIsDefault(boolean $is_default) Return the first ChildLocalization filtered by the is_default column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocalization[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLocalization objects based on current ModelCriteria
 * @method     ChildLocalization[]|ObjectCollection findById(int $id) Return ChildLocalization objects filtered by the id column
 * @method     ChildLocalization[]|ObjectCollection findByParentId(int $parent_id) Return ChildLocalization objects filtered by the parent_id column
 * @method     ChildLocalization[]|ObjectCollection findByName(string $name) Return ChildLocalization objects filtered by the name column
 * @method     ChildLocalization[]|ObjectCollection findByLocale(string $locale) Return ChildLocalization objects filtered by the locale column
 * @method     ChildLocalization[]|ObjectCollection findByLanguageId(int $language_id) Return ChildLocalization objects filtered by the language_id column
 * @method     ChildLocalization[]|ObjectCollection findByExtLanguageId(int $ext_language_id) Return ChildLocalization objects filtered by the ext_language_id column
 * @method     ChildLocalization[]|ObjectCollection findByRegion(string $region) Return ChildLocalization objects filtered by the region column
 * @method     ChildLocalization[]|ObjectCollection findByScriptId(int $script_id) Return ChildLocalization objects filtered by the script_id column
 * @method     ChildLocalization[]|ObjectCollection findByIsDefault(boolean $is_default) Return ChildLocalization objects filtered by the is_default column
 * @method     ChildLocalization[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LocalizationQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\LocalizationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Localization', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLocalizationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLocalizationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLocalizationQuery) {
            return $criteria;
        }
        $query = new ChildLocalizationQuery();
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
     * @return ChildLocalization|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LocalizationTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LocalizationTableMap::DATABASE_NAME);
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
     * @return ChildLocalization A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `parent_id`, `name`, `locale`, `language_id`, `ext_language_id`, `region`, `script_id`, `is_default` FROM `kk_localization` WHERE `id` = :p0';
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
            /** @var ChildLocalization $obj */
            $obj = new ChildLocalization();
            $obj->hydrate($row);
            LocalizationTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildLocalization|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LocalizationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LocalizationTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocalizationTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByParentId($parentId = null, $comparison = null)
    {
        if (is_array($parentId)) {
            $useMinMax = false;
            if (isset($parentId['min'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_PARENT_ID, $parentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentId['max'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_PARENT_ID, $parentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocalizationTableMap::COL_PARENT_ID, $parentId, $comparison);
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
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LocalizationTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the locale column
     *
     * Example usage:
     * <code>
     * $query->filterByLocale('fooValue');   // WHERE locale = 'fooValue'
     * $query->filterByLocale('%fooValue%'); // WHERE locale LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locale The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByLocale($locale = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locale)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $locale)) {
                $locale = str_replace('*', '%', $locale);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LocalizationTableMap::COL_LOCALE, $locale, $comparison);
    }

    /**
     * Filter the query on the language_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLanguageId(1234); // WHERE language_id = 1234
     * $query->filterByLanguageId(array(12, 34)); // WHERE language_id IN (12, 34)
     * $query->filterByLanguageId(array('min' => 12)); // WHERE language_id > 12
     * </code>
     *
     * @see       filterByLanguage()
     *
     * @param     mixed $languageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByLanguageId($languageId = null, $comparison = null)
    {
        if (is_array($languageId)) {
            $useMinMax = false;
            if (isset($languageId['min'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_LANGUAGE_ID, $languageId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($languageId['max'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_LANGUAGE_ID, $languageId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocalizationTableMap::COL_LANGUAGE_ID, $languageId, $comparison);
    }

    /**
     * Filter the query on the ext_language_id column
     *
     * Example usage:
     * <code>
     * $query->filterByExtLanguageId(1234); // WHERE ext_language_id = 1234
     * $query->filterByExtLanguageId(array(12, 34)); // WHERE ext_language_id IN (12, 34)
     * $query->filterByExtLanguageId(array('min' => 12)); // WHERE ext_language_id > 12
     * </code>
     *
     * @see       filterByExtLang()
     *
     * @param     mixed $extLanguageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByExtLanguageId($extLanguageId = null, $comparison = null)
    {
        if (is_array($extLanguageId)) {
            $useMinMax = false;
            if (isset($extLanguageId['min'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_EXT_LANGUAGE_ID, $extLanguageId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($extLanguageId['max'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_EXT_LANGUAGE_ID, $extLanguageId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocalizationTableMap::COL_EXT_LANGUAGE_ID, $extLanguageId, $comparison);
    }

    /**
     * Filter the query on the region column
     *
     * Example usage:
     * <code>
     * $query->filterByRegion('fooValue');   // WHERE region = 'fooValue'
     * $query->filterByRegion('%fooValue%'); // WHERE region LIKE '%fooValue%'
     * </code>
     *
     * @param     string $region The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByRegion($region = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($region)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $region)) {
                $region = str_replace('*', '%', $region);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LocalizationTableMap::COL_REGION, $region, $comparison);
    }

    /**
     * Filter the query on the script_id column
     *
     * Example usage:
     * <code>
     * $query->filterByScriptId(1234); // WHERE script_id = 1234
     * $query->filterByScriptId(array(12, 34)); // WHERE script_id IN (12, 34)
     * $query->filterByScriptId(array('min' => 12)); // WHERE script_id > 12
     * </code>
     *
     * @see       filterByScript()
     *
     * @param     mixed $scriptId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByScriptId($scriptId = null, $comparison = null)
    {
        if (is_array($scriptId)) {
            $useMinMax = false;
            if (isset($scriptId['min'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_SCRIPT_ID, $scriptId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($scriptId['max'])) {
                $this->addUsingAlias(LocalizationTableMap::COL_SCRIPT_ID, $scriptId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocalizationTableMap::COL_SCRIPT_ID, $scriptId, $comparison);
    }

    /**
     * Filter the query on the is_default column
     *
     * Example usage:
     * <code>
     * $query->filterByIsDefault(true); // WHERE is_default = true
     * $query->filterByIsDefault('yes'); // WHERE is_default = true
     * </code>
     *
     * @param     boolean|string $isDefault The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByIsDefault($isDefault = null, $comparison = null)
    {
        if (is_string($isDefault)) {
            $isDefault = in_array(strtolower($isDefault), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LocalizationTableMap::COL_IS_DEFAULT, $isDefault, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Localization object
     *
     * @param \keeko\core\model\Localization|ObjectCollection $localization The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByParent($localization, $comparison = null)
    {
        if ($localization instanceof \keeko\core\model\Localization) {
            return $this
                ->addUsingAlias(LocalizationTableMap::COL_PARENT_ID, $localization->getId(), $comparison);
        } elseif ($localization instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocalizationTableMap::COL_PARENT_ID, $localization->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByParent() only accepts arguments of type \keeko\core\model\Localization or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Parent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
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
     * Use the Parent relation Localization object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LocalizationQuery A secondary query class using the current class as primary query
     */
    public function useParentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinParent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Parent', '\keeko\core\model\LocalizationQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Language object
     *
     * @param \keeko\core\model\Language|ObjectCollection $language The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByLanguage($language, $comparison = null)
    {
        if ($language instanceof \keeko\core\model\Language) {
            return $this
                ->addUsingAlias(LocalizationTableMap::COL_LANGUAGE_ID, $language->getId(), $comparison);
        } elseif ($language instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocalizationTableMap::COL_LANGUAGE_ID, $language->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLanguage() only accepts arguments of type \keeko\core\model\Language or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Language relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function joinLanguage($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Language');

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
            $this->addJoinObject($join, 'Language');
        }

        return $this;
    }

    /**
     * Use the Language relation Language object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageQuery A secondary query class using the current class as primary query
     */
    public function useLanguageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLanguage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Language', '\keeko\core\model\LanguageQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Language object
     *
     * @param \keeko\core\model\Language|ObjectCollection $language The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByExtLang($language, $comparison = null)
    {
        if ($language instanceof \keeko\core\model\Language) {
            return $this
                ->addUsingAlias(LocalizationTableMap::COL_EXT_LANGUAGE_ID, $language->getId(), $comparison);
        } elseif ($language instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocalizationTableMap::COL_EXT_LANGUAGE_ID, $language->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByExtLang() only accepts arguments of type \keeko\core\model\Language or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ExtLang relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function joinExtLang($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ExtLang');

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
            $this->addJoinObject($join, 'ExtLang');
        }

        return $this;
    }

    /**
     * Use the ExtLang relation Language object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LanguageQuery A secondary query class using the current class as primary query
     */
    public function useExtLangQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinExtLang($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ExtLang', '\keeko\core\model\LanguageQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\LanguageScript object
     *
     * @param \keeko\core\model\LanguageScript|ObjectCollection $languageScript The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByScript($languageScript, $comparison = null)
    {
        if ($languageScript instanceof \keeko\core\model\LanguageScript) {
            return $this
                ->addUsingAlias(LocalizationTableMap::COL_SCRIPT_ID, $languageScript->getId(), $comparison);
        } elseif ($languageScript instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocalizationTableMap::COL_SCRIPT_ID, $languageScript->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
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
     * Filter the query by a related \keeko\core\model\Localization object
     *
     * @param \keeko\core\model\Localization|ObjectCollection $localization the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByLocalizationRelatedById($localization, $comparison = null)
    {
        if ($localization instanceof \keeko\core\model\Localization) {
            return $this
                ->addUsingAlias(LocalizationTableMap::COL_ID, $localization->getParentId(), $comparison);
        } elseif ($localization instanceof ObjectCollection) {
            return $this
                ->useLocalizationRelatedByIdQuery()
                ->filterByPrimaryKeys($localization->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLocalizationRelatedById() only accepts arguments of type \keeko\core\model\Localization or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LocalizationRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function joinLocalizationRelatedById($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LocalizationRelatedById');

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
            $this->addJoinObject($join, 'LocalizationRelatedById');
        }

        return $this;
    }

    /**
     * Use the LocalizationRelatedById relation Localization object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LocalizationQuery A secondary query class using the current class as primary query
     */
    public function useLocalizationRelatedByIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLocalizationRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LocalizationRelatedById', '\keeko\core\model\LocalizationQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\LocalizationVariant object
     *
     * @param \keeko\core\model\LocalizationVariant|ObjectCollection $localizationVariant the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByLocalizationVariant($localizationVariant, $comparison = null)
    {
        if ($localizationVariant instanceof \keeko\core\model\LocalizationVariant) {
            return $this
                ->addUsingAlias(LocalizationTableMap::COL_ID, $localizationVariant->getLocalizationId(), $comparison);
        } elseif ($localizationVariant instanceof ObjectCollection) {
            return $this
                ->useLocalizationVariantQuery()
                ->filterByPrimaryKeys($localizationVariant->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLocalizationVariant() only accepts arguments of type \keeko\core\model\LocalizationVariant or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LocalizationVariant relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function joinLocalizationVariant($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LocalizationVariant');

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
            $this->addJoinObject($join, 'LocalizationVariant');
        }

        return $this;
    }

    /**
     * Use the LocalizationVariant relation LocalizationVariant object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\LocalizationVariantQuery A secondary query class using the current class as primary query
     */
    public function useLocalizationVariantQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLocalizationVariant($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LocalizationVariant', '\keeko\core\model\LocalizationVariantQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\ApplicationUri object
     *
     * @param \keeko\core\model\ApplicationUri|ObjectCollection $applicationUri the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByApplicationUri($applicationUri, $comparison = null)
    {
        if ($applicationUri instanceof \keeko\core\model\ApplicationUri) {
            return $this
                ->addUsingAlias(LocalizationTableMap::COL_ID, $applicationUri->getLocalizationId(), $comparison);
        } elseif ($applicationUri instanceof ObjectCollection) {
            return $this
                ->useApplicationUriQuery()
                ->filterByPrimaryKeys($applicationUri->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByApplicationUri() only accepts arguments of type \keeko\core\model\ApplicationUri or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ApplicationUri relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function joinApplicationUri($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ApplicationUri');

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
            $this->addJoinObject($join, 'ApplicationUri');
        }

        return $this;
    }

    /**
     * Use the ApplicationUri relation ApplicationUri object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ApplicationUriQuery A secondary query class using the current class as primary query
     */
    public function useApplicationUriQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinApplicationUri($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ApplicationUri', '\keeko\core\model\ApplicationUriQuery');
    }

    /**
     * Filter the query by a related LanguageVariant object
     * using the kk_localization_variant table as cross reference
     *
     * @param LanguageVariant $languageVariant the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocalizationQuery The current query, for fluid interface
     */
    public function filterByLanguageVariant($languageVariant, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useLocalizationVariantQuery()
            ->filterByLanguageVariant($languageVariant, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLocalization $localization Object to remove from the list of results
     *
     * @return $this|ChildLocalizationQuery The current query, for fluid interface
     */
    public function prune($localization = null)
    {
        if ($localization) {
            $this->addUsingAlias(LocalizationTableMap::COL_ID, $localization->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_localization table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocalizationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LocalizationTableMap::clearInstancePool();
            LocalizationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LocalizationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LocalizationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LocalizationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LocalizationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LocalizationQuery
