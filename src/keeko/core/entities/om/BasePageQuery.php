<?php

namespace keeko\core\entities\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use keeko\core\entities\Application;
use keeko\core\entities\Layout;
use keeko\core\entities\Page;
use keeko\core\entities\PagePeer;
use keeko\core\entities\PageQuery;
use keeko\core\entities\Route;

/**
 * Base class that represents a query for the 'keeko_page' table.
 *
 *
 *
 * @method PageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PageQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method PageQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PageQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PageQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PageQuery orderByKeywords($order = Criteria::ASC) Order by the keywords column
 * @method PageQuery orderByLayoutId($order = Criteria::ASC) Order by the layout_id column
 * @method PageQuery orderByApplicationId($order = Criteria::ASC) Order by the application_id column
 *
 * @method PageQuery groupById() Group by the id column
 * @method PageQuery groupByParentId() Group by the parent_id column
 * @method PageQuery groupByTitle() Group by the title column
 * @method PageQuery groupBySlug() Group by the slug column
 * @method PageQuery groupByDescription() Group by the description column
 * @method PageQuery groupByKeywords() Group by the keywords column
 * @method PageQuery groupByLayoutId() Group by the layout_id column
 * @method PageQuery groupByApplicationId() Group by the application_id column
 *
 * @method PageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PageQuery leftJoinPageRelatedByParentId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PageRelatedByParentId relation
 * @method PageQuery rightJoinPageRelatedByParentId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PageRelatedByParentId relation
 * @method PageQuery innerJoinPageRelatedByParentId($relationAlias = null) Adds a INNER JOIN clause to the query using the PageRelatedByParentId relation
 *
 * @method PageQuery leftJoinLayout($relationAlias = null) Adds a LEFT JOIN clause to the query using the Layout relation
 * @method PageQuery rightJoinLayout($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Layout relation
 * @method PageQuery innerJoinLayout($relationAlias = null) Adds a INNER JOIN clause to the query using the Layout relation
 *
 * @method PageQuery leftJoinApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Application relation
 * @method PageQuery rightJoinApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Application relation
 * @method PageQuery innerJoinApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the Application relation
 *
 * @method PageQuery leftJoinPageRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the PageRelatedById relation
 * @method PageQuery rightJoinPageRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PageRelatedById relation
 * @method PageQuery innerJoinPageRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the PageRelatedById relation
 *
 * @method PageQuery leftJoinRoute($relationAlias = null) Adds a LEFT JOIN clause to the query using the Route relation
 * @method PageQuery rightJoinRoute($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Route relation
 * @method PageQuery innerJoinRoute($relationAlias = null) Adds a INNER JOIN clause to the query using the Route relation
 *
 * @method Page findOne(PropelPDO $con = null) Return the first Page matching the query
 * @method Page findOneOrCreate(PropelPDO $con = null) Return the first Page matching the query, or a new Page object populated from the query conditions when no match is found
 *
 * @method Page findOneByParentId(int $parent_id) Return the first Page filtered by the parent_id column
 * @method Page findOneByTitle(string $title) Return the first Page filtered by the title column
 * @method Page findOneBySlug(string $slug) Return the first Page filtered by the slug column
 * @method Page findOneByDescription(string $description) Return the first Page filtered by the description column
 * @method Page findOneByKeywords(string $keywords) Return the first Page filtered by the keywords column
 * @method Page findOneByLayoutId(int $layout_id) Return the first Page filtered by the layout_id column
 * @method Page findOneByApplicationId(int $application_id) Return the first Page filtered by the application_id column
 *
 * @method array findById(int $id) Return Page objects filtered by the id column
 * @method array findByParentId(int $parent_id) Return Page objects filtered by the parent_id column
 * @method array findByTitle(string $title) Return Page objects filtered by the title column
 * @method array findBySlug(string $slug) Return Page objects filtered by the slug column
 * @method array findByDescription(string $description) Return Page objects filtered by the description column
 * @method array findByKeywords(string $keywords) Return Page objects filtered by the keywords column
 * @method array findByLayoutId(int $layout_id) Return Page objects filtered by the layout_id column
 * @method array findByApplicationId(int $application_id) Return Page objects filtered by the application_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BasePageQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePageQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'keeko';
        }
        if (null === $modelName) {
            $modelName = 'keeko\\core\\entities\\Page';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PageQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PageQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PageQuery) {
            return $criteria;
        }
        $query = new PageQuery(null, null, $modelAlias);

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
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Page|Page[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PagePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PagePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Page A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Page A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `parent_id`, `title`, `slug`, `description`, `keywords`, `layout_id`, `application_id` FROM `keeko_page` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Page();
            $obj->hydrate($row);
            PagePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Page|Page[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Page[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PagePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PagePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PagePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PagePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PagePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the parent_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentId(1234); // WHERE parent_id = 1234
     * $query->filterByParentId(array(12, 34)); // WHERE parent_id IN (12, 34)
     * $query->filterByParentId(array('min' => 12)); // WHERE parent_id >= 12
     * $query->filterByParentId(array('max' => 12)); // WHERE parent_id <= 12
     * </code>
     *
     * @see       filterByPageRelatedByParentId()
     *
     * @param     mixed $parentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterByParentId($parentId = null, $comparison = null)
    {
        if (is_array($parentId)) {
            $useMinMax = false;
            if (isset($parentId['min'])) {
                $this->addUsingAlias(PagePeer::PARENT_ID, $parentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentId['max'])) {
                $this->addUsingAlias(PagePeer::PARENT_ID, $parentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PagePeer::PARENT_ID, $parentId, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PagePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%'); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slug)) {
                $slug = str_replace('*', '%', $slug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PagePeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PagePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the keywords column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywords('fooValue');   // WHERE keywords = 'fooValue'
     * $query->filterByKeywords('%fooValue%'); // WHERE keywords LIKE '%fooValue%'
     * </code>
     *
     * @param     string $keywords The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterByKeywords($keywords = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($keywords)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $keywords)) {
                $keywords = str_replace('*', '%', $keywords);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PagePeer::KEYWORDS, $keywords, $comparison);
    }

    /**
     * Filter the query on the layout_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLayoutId(1234); // WHERE layout_id = 1234
     * $query->filterByLayoutId(array(12, 34)); // WHERE layout_id IN (12, 34)
     * $query->filterByLayoutId(array('min' => 12)); // WHERE layout_id >= 12
     * $query->filterByLayoutId(array('max' => 12)); // WHERE layout_id <= 12
     * </code>
     *
     * @see       filterByLayout()
     *
     * @param     mixed $layoutId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterByLayoutId($layoutId = null, $comparison = null)
    {
        if (is_array($layoutId)) {
            $useMinMax = false;
            if (isset($layoutId['min'])) {
                $this->addUsingAlias(PagePeer::LAYOUT_ID, $layoutId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($layoutId['max'])) {
                $this->addUsingAlias(PagePeer::LAYOUT_ID, $layoutId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PagePeer::LAYOUT_ID, $layoutId, $comparison);
    }

    /**
     * Filter the query on the application_id column
     *
     * Example usage:
     * <code>
     * $query->filterByApplicationId(1234); // WHERE application_id = 1234
     * $query->filterByApplicationId(array(12, 34)); // WHERE application_id IN (12, 34)
     * $query->filterByApplicationId(array('min' => 12)); // WHERE application_id >= 12
     * $query->filterByApplicationId(array('max' => 12)); // WHERE application_id <= 12
     * </code>
     *
     * @see       filterByApplication()
     *
     * @param     mixed $applicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function filterByApplicationId($applicationId = null, $comparison = null)
    {
        if (is_array($applicationId)) {
            $useMinMax = false;
            if (isset($applicationId['min'])) {
                $this->addUsingAlias(PagePeer::APPLICATION_ID, $applicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($applicationId['max'])) {
                $this->addUsingAlias(PagePeer::APPLICATION_ID, $applicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PagePeer::APPLICATION_ID, $applicationId, $comparison);
    }

    /**
     * Filter the query by a related Page object
     *
     * @param   Page|PropelObjectCollection $page The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPageRelatedByParentId($page, $comparison = null)
    {
        if ($page instanceof Page) {
            return $this
                ->addUsingAlias(PagePeer::PARENT_ID, $page->getId(), $comparison);
        } elseif ($page instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PagePeer::PARENT_ID, $page->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPageRelatedByParentId() only accepts arguments of type Page or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PageRelatedByParentId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function joinPageRelatedByParentId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PageRelatedByParentId');

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
            $this->addJoinObject($join, 'PageRelatedByParentId');
        }

        return $this;
    }

    /**
     * Use the PageRelatedByParentId relation Page object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\PageQuery A secondary query class using the current class as primary query
     */
    public function usePageRelatedByParentIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPageRelatedByParentId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PageRelatedByParentId', '\keeko\core\entities\PageQuery');
    }

    /**
     * Filter the query by a related Layout object
     *
     * @param   Layout|PropelObjectCollection $layout The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLayout($layout, $comparison = null)
    {
        if ($layout instanceof Layout) {
            return $this
                ->addUsingAlias(PagePeer::LAYOUT_ID, $layout->getId(), $comparison);
        } elseif ($layout instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PagePeer::LAYOUT_ID, $layout->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLayout() only accepts arguments of type Layout or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Layout relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function joinLayout($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Layout');

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
            $this->addJoinObject($join, 'Layout');
        }

        return $this;
    }

    /**
     * Use the Layout relation Layout object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\LayoutQuery A secondary query class using the current class as primary query
     */
    public function useLayoutQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLayout($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Layout', '\keeko\core\entities\LayoutQuery');
    }

    /**
     * Filter the query by a related Application object
     *
     * @param   Application|PropelObjectCollection $application The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplication($application, $comparison = null)
    {
        if ($application instanceof Application) {
            return $this
                ->addUsingAlias(PagePeer::APPLICATION_ID, $application->getId(), $comparison);
        } elseif ($application instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PagePeer::APPLICATION_ID, $application->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByApplication() only accepts arguments of type Application or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Application relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function joinApplication($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Application');

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
            $this->addJoinObject($join, 'Application');
        }

        return $this;
    }

    /**
     * Use the Application relation Application object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\ApplicationQuery A secondary query class using the current class as primary query
     */
    public function useApplicationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinApplication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Application', '\keeko\core\entities\ApplicationQuery');
    }

    /**
     * Filter the query by a related Page object
     *
     * @param   Page|PropelObjectCollection $page  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPageRelatedById($page, $comparison = null)
    {
        if ($page instanceof Page) {
            return $this
                ->addUsingAlias(PagePeer::ID, $page->getParentId(), $comparison);
        } elseif ($page instanceof PropelObjectCollection) {
            return $this
                ->usePageRelatedByIdQuery()
                ->filterByPrimaryKeys($page->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPageRelatedById() only accepts arguments of type Page or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PageRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function joinPageRelatedById($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PageRelatedById');

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
            $this->addJoinObject($join, 'PageRelatedById');
        }

        return $this;
    }

    /**
     * Use the PageRelatedById relation Page object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\PageQuery A secondary query class using the current class as primary query
     */
    public function usePageRelatedByIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPageRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PageRelatedById', '\keeko\core\entities\PageQuery');
    }

    /**
     * Filter the query by a related Route object
     *
     * @param   Route|PropelObjectCollection $route  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRoute($route, $comparison = null)
    {
        if ($route instanceof Route) {
            return $this
                ->addUsingAlias(PagePeer::ID, $route->getPageId(), $comparison);
        } elseif ($route instanceof PropelObjectCollection) {
            return $this
                ->useRouteQuery()
                ->filterByPrimaryKeys($route->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRoute() only accepts arguments of type Route or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Route relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function joinRoute($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Route');

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
            $this->addJoinObject($join, 'Route');
        }

        return $this;
    }

    /**
     * Use the Route relation Route object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\RouteQuery A secondary query class using the current class as primary query
     */
    public function useRouteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRoute($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Route', '\keeko\core\entities\RouteQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Page $page Object to remove from the list of results
     *
     * @return PageQuery The current query, for fluid interface
     */
    public function prune($page = null)
    {
        if ($page) {
            $this->addUsingAlias(PagePeer::ID, $page->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
