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
use keeko\core\model\LanguageScope as ChildLanguageScope;
use keeko\core\model\LanguageScopeQuery as ChildLanguageScopeQuery;
use keeko\core\model\Map\LanguageScopeTableMap;

/**
 * Base class that represents a query for the 'kk_language_scope' table.
 *
 *
 *
 * @method     ChildLanguageScopeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLanguageScopeQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildLanguageScopeQuery groupById() Group by the id column
 * @method     ChildLanguageScopeQuery groupByName() Group by the name column
 *
 * @method     ChildLanguageScopeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLanguageScopeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLanguageScopeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLanguageScopeQuery leftJoinLanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Language relation
 * @method     ChildLanguageScopeQuery rightJoinLanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Language relation
 * @method     ChildLanguageScopeQuery innerJoinLanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the Language relation
 *
 * @method     \keeko\core\model\LanguageQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLanguageScope findOne(ConnectionInterface $con = null) Return the first ChildLanguageScope matching the query
 * @method     ChildLanguageScope findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLanguageScope matching the query, or a new ChildLanguageScope object populated from the query conditions when no match is found
 *
 * @method     ChildLanguageScope findOneById(int $id) Return the first ChildLanguageScope filtered by the id column
 * @method     ChildLanguageScope findOneByName(string $name) Return the first ChildLanguageScope filtered by the name column *

 * @method     ChildLanguageScope requirePk($key, ConnectionInterface $con = null) Return the ChildLanguageScope by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageScope requireOne(ConnectionInterface $con = null) Return the first ChildLanguageScope matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguageScope requireOneById(int $id) Return the first ChildLanguageScope filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguageScope requireOneByName(string $name) Return the first ChildLanguageScope filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguageScope[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLanguageScope objects based on current ModelCriteria
 * @method     ChildLanguageScope[]|ObjectCollection findById(int $id) Return ChildLanguageScope objects filtered by the id column
 * @method     ChildLanguageScope[]|ObjectCollection findByName(string $name) Return ChildLanguageScope objects filtered by the name column
 * @method     ChildLanguageScope[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LanguageScopeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\LanguageScopeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\LanguageScope', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLanguageScopeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLanguageScopeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLanguageScopeQuery) {
            return $criteria;
        }
        $query = new ChildLanguageScopeQuery();
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
     * @return ChildLanguageScope|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LanguageScopeTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LanguageScopeTableMap::DATABASE_NAME);
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
     * @return ChildLanguageScope A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name` FROM `kk_language_scope` WHERE `id` = :p0';
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
            /** @var ChildLanguageScope $obj */
            $obj = new ChildLanguageScope();
            $obj->hydrate($row);
            LanguageScopeTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildLanguageScope|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLanguageScopeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LanguageScopeTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLanguageScopeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LanguageScopeTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLanguageScopeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LanguageScopeTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LanguageScopeTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageScopeTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLanguageScopeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LanguageScopeTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Language object
     *
     * @param \keeko\core\model\Language|ObjectCollection $language the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageScopeQuery The current query, for fluid interface
     */
    public function filterByLanguage($language, $comparison = null)
    {
        if ($language instanceof \keeko\core\model\Language) {
            return $this
                ->addUsingAlias(LanguageScopeTableMap::COL_ID, $language->getScopeId(), $comparison);
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
     * @return $this|ChildLanguageScopeQuery The current query, for fluid interface
     */
    public function joinLanguage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useLanguageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLanguage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Language', '\keeko\core\model\LanguageQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLanguageScope $languageScope Object to remove from the list of results
     *
     * @return $this|ChildLanguageScopeQuery The current query, for fluid interface
     */
    public function prune($languageScope = null)
    {
        if ($languageScope) {
            $this->addUsingAlias(LanguageScopeTableMap::COL_ID, $languageScope->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

} // LanguageScopeQuery
