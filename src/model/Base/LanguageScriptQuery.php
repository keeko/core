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
use keeko\core\model\LanguageScript as ChildLanguageScript;
use keeko\core\model\LanguageScriptQuery as ChildLanguageScriptQuery;
use keeko\core\model\Map\LanguageScriptTableMap;

/**
 * Base class that represents a query for the 'kk_language_script' table.
 *
 *
 *
 * @method     ChildLanguageScriptQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLanguageScriptQuery orderByAlpha4($order = Criteria::ASC) Order by the alpha_4 column
 * @method     ChildLanguageScriptQuery orderByNumeric($order = Criteria::ASC) Order by the numeric column
 * @method     ChildLanguageScriptQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildLanguageScriptQuery orderByAlias($order = Criteria::ASC) Order by the alias column
 * @method     ChildLanguageScriptQuery orderByDirection($order = Criteria::ASC) Order by the direction column
 *
 * @method     ChildLanguageScriptQuery groupById() Group by the id column
 * @method     ChildLanguageScriptQuery groupByAlpha4() Group by the alpha_4 column
 * @method     ChildLanguageScriptQuery groupByNumeric() Group by the numeric column
 * @method     ChildLanguageScriptQuery groupByName() Group by the name column
 * @method     ChildLanguageScriptQuery groupByAlias() Group by the alias column
 * @method     ChildLanguageScriptQuery groupByDirection() Group by the direction column
 *
 * @method     ChildLanguageScriptQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLanguageScriptQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLanguageScriptQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLanguageScriptQuery leftJoinLanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Language relation
 * @method     ChildLanguageScriptQuery rightJoinLanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Language relation
 * @method     ChildLanguageScriptQuery innerJoinLanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the Language relation
 *
 * @method     ChildLanguageScriptQuery leftJoinLocalization($relationAlias = null) Adds a LEFT JOIN clause to the query using the Localization relation
 * @method     ChildLanguageScriptQuery rightJoinLocalization($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Localization relation
 * @method     ChildLanguageScriptQuery innerJoinLocalization($relationAlias = null) Adds a INNER JOIN clause to the query using the Localization relation
 *
 * @method     \keeko\core\model\LanguageQuery|\keeko\core\model\LocalizationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLanguageScript findOne(ConnectionInterface $con = null) Return the first ChildLanguageScript matching the query
 * @method     ChildLanguageScript findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLanguageScript matching the query, or a new ChildLanguageScript object populated from the query conditions when no match is found
 *
 * @method     ChildLanguageScript findOneById(int $id) Return the first ChildLanguageScript filtered by the id column
 * @method     ChildLanguageScript findOneByAlpha4(string $alpha_4) Return the first ChildLanguageScript filtered by the alpha_4 column
 * @method     ChildLanguageScript findOneByNumeric(int $numeric) Return the first ChildLanguageScript filtered by the numeric column
 * @method     ChildLanguageScript findOneByName(string $name) Return the first ChildLanguageScript filtered by the name column
 * @method     ChildLanguageScript findOneByAlias(string $alias) Return the first ChildLanguageScript filtered by the alias column
 * @method     ChildLanguageScript findOneByDirection(string $direction) Return the first ChildLanguageScript filtered by the direction column *

 * @method     ChildLanguageScript requirePk($key, ConnectionInterface $con = null) Return the ChildLanguageScript by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageScript requireOne(ConnectionInterface $con = null) Return the first ChildLanguageScript matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguageScript requireOneById(int $id) Return the first ChildLanguageScript filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageScript requireOneByAlpha4(string $alpha_4) Return the first ChildLanguageScript filtered by the alpha_4 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageScript requireOneByNumeric(int $numeric) Return the first ChildLanguageScript filtered by the numeric column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageScript requireOneByName(string $name) Return the first ChildLanguageScript filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageScript requireOneByAlias(string $alias) Return the first ChildLanguageScript filtered by the alias column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageScript requireOneByDirection(string $direction) Return the first ChildLanguageScript filtered by the direction column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguageScript[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLanguageScript objects based on current ModelCriteria
 * @method     ChildLanguageScript[]|ObjectCollection findById(int $id) Return ChildLanguageScript objects filtered by the id column
 * @method     ChildLanguageScript[]|ObjectCollection findByAlpha4(string $alpha_4) Return ChildLanguageScript objects filtered by the alpha_4 column
 * @method     ChildLanguageScript[]|ObjectCollection findByNumeric(int $numeric) Return ChildLanguageScript objects filtered by the numeric column
 * @method     ChildLanguageScript[]|ObjectCollection findByName(string $name) Return ChildLanguageScript objects filtered by the name column
 * @method     ChildLanguageScript[]|ObjectCollection findByAlias(string $alias) Return ChildLanguageScript objects filtered by the alias column
 * @method     ChildLanguageScript[]|ObjectCollection findByDirection(string $direction) Return ChildLanguageScript objects filtered by the direction column
 * @method     ChildLanguageScript[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LanguageScriptQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\LanguageScriptQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\LanguageScript', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLanguageScriptQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLanguageScriptQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLanguageScriptQuery) {
            return $criteria;
        }
        $query = new ChildLanguageScriptQuery();
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
     * @return ChildLanguageScript|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LanguageScriptTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LanguageScriptTableMap::DATABASE_NAME);
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
     * @return ChildLanguageScript A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `alpha_4`, `numeric`, `name`, `alias`, `direction` FROM `kk_language_script` WHERE `id` = :p0';
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
            /** @var ChildLanguageScript $obj */
            $obj = new ChildLanguageScript();
            $obj->hydrate($row);
            LanguageScriptTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildLanguageScript|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LanguageScriptTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LanguageScriptTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LanguageScriptTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LanguageScriptTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageScriptTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the alpha_4 column
     *
     * Example usage:
     * <code>
     * $query->filterByAlpha4('fooValue');   // WHERE alpha_4 = 'fooValue'
     * $query->filterByAlpha4('%fooValue%'); // WHERE alpha_4 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alpha4 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterByAlpha4($alpha4 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alpha4)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alpha4)) {
                $alpha4 = str_replace('*', '%', $alpha4);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageScriptTableMap::COL_ALPHA_4, $alpha4, $comparison);
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
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterByNumeric($numeric = null, $comparison = null)
    {
        if (is_array($numeric)) {
            $useMinMax = false;
            if (isset($numeric['min'])) {
                $this->addUsingAlias(LanguageScriptTableMap::COL_NUMERIC, $numeric['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numeric['max'])) {
                $this->addUsingAlias(LanguageScriptTableMap::COL_NUMERIC, $numeric['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageScriptTableMap::COL_NUMERIC, $numeric, $comparison);
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
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageScriptTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the alias column
     *
     * Example usage:
     * <code>
     * $query->filterByAlias('fooValue');   // WHERE alias = 'fooValue'
     * $query->filterByAlias('%fooValue%'); // WHERE alias LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alias The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterByAlias($alias = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alias)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alias)) {
                $alias = str_replace('*', '%', $alias);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageScriptTableMap::COL_ALIAS, $alias, $comparison);
    }

    /**
     * Filter the query on the direction column
     *
     * Example usage:
     * <code>
     * $query->filterByDirection('fooValue');   // WHERE direction = 'fooValue'
     * $query->filterByDirection('%fooValue%'); // WHERE direction LIKE '%fooValue%'
     * </code>
     *
     * @param     string $direction The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterByDirection($direction = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($direction)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $direction)) {
                $direction = str_replace('*', '%', $direction);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LanguageScriptTableMap::COL_DIRECTION, $direction, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Language object
     *
     * @param \keeko\core\model\Language|ObjectCollection $language the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterByLanguage($language, $comparison = null)
    {
        if ($language instanceof \keeko\core\model\Language) {
            return $this
                ->addUsingAlias(LanguageScriptTableMap::COL_ID, $language->getDefaultScriptId(), $comparison);
        } elseif ($language instanceof ObjectCollection) {
            return $this
                ->useLanguageQuery()
                ->filterByPrimaryKeys($language->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
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
     * Filter the query by a related \keeko\core\model\Localization object
     *
     * @param \keeko\core\model\Localization|ObjectCollection $localization the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function filterByLocalization($localization, $comparison = null)
    {
        if ($localization instanceof \keeko\core\model\Localization) {
            return $this
                ->addUsingAlias(LanguageScriptTableMap::COL_ID, $localization->getScriptId(), $comparison);
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
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
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
     * @param   ChildLanguageScript $languageScript Object to remove from the list of results
     *
     * @return $this|ChildLanguageScriptQuery The current query, for fluid interface
     */
    public function prune($languageScript = null)
    {
        if ($languageScript) {
            $this->addUsingAlias(LanguageScriptTableMap::COL_ID, $languageScript->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_language_script table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageScriptTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LanguageScriptTableMap::clearInstancePool();
            LanguageScriptTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LanguageScriptTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LanguageScriptTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LanguageScriptTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LanguageScriptTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LanguageScriptQuery
