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
use keeko\core\entities\Block;
use keeko\core\entities\Design;
use keeko\core\entities\Layout;
use keeko\core\entities\LayoutPeer;
use keeko\core\entities\LayoutQuery;
use keeko\core\entities\Page;

/**
 * Base class that represents a query for the 'keeko_layout' table.
 *
 *
 *
 * @method LayoutQuery orderById($order = Criteria::ASC) Order by the id column
 * @method LayoutQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method LayoutQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method LayoutQuery orderByDesignId($order = Criteria::ASC) Order by the design_id column
 *
 * @method LayoutQuery groupById() Group by the id column
 * @method LayoutQuery groupByName() Group by the name column
 * @method LayoutQuery groupByTitle() Group by the title column
 * @method LayoutQuery groupByDesignId() Group by the design_id column
 *
 * @method LayoutQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method LayoutQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method LayoutQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method LayoutQuery leftJoinDesign($relationAlias = null) Adds a LEFT JOIN clause to the query using the Design relation
 * @method LayoutQuery rightJoinDesign($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Design relation
 * @method LayoutQuery innerJoinDesign($relationAlias = null) Adds a INNER JOIN clause to the query using the Design relation
 *
 * @method LayoutQuery leftJoinPage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Page relation
 * @method LayoutQuery rightJoinPage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Page relation
 * @method LayoutQuery innerJoinPage($relationAlias = null) Adds a INNER JOIN clause to the query using the Page relation
 *
 * @method LayoutQuery leftJoinBlock($relationAlias = null) Adds a LEFT JOIN clause to the query using the Block relation
 * @method LayoutQuery rightJoinBlock($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Block relation
 * @method LayoutQuery innerJoinBlock($relationAlias = null) Adds a INNER JOIN clause to the query using the Block relation
 *
 * @method Layout findOne(PropelPDO $con = null) Return the first Layout matching the query
 * @method Layout findOneOrCreate(PropelPDO $con = null) Return the first Layout matching the query, or a new Layout object populated from the query conditions when no match is found
 *
 * @method Layout findOneByName(string $name) Return the first Layout filtered by the name column
 * @method Layout findOneByTitle(string $title) Return the first Layout filtered by the title column
 * @method Layout findOneByDesignId(int $design_id) Return the first Layout filtered by the design_id column
 *
 * @method array findById(int $id) Return Layout objects filtered by the id column
 * @method array findByName(string $name) Return Layout objects filtered by the name column
 * @method array findByTitle(string $title) Return Layout objects filtered by the title column
 * @method array findByDesignId(int $design_id) Return Layout objects filtered by the design_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseLayoutQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseLayoutQuery object.
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
            $modelName = 'keeko\\core\\entities\\Layout';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new LayoutQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   LayoutQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return LayoutQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof LayoutQuery) {
            return $criteria;
        }
        $query = new LayoutQuery(null, null, $modelAlias);

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
     * @return   Layout|Layout[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LayoutPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(LayoutPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Layout A model object, or null if the key is not found
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
     * @return                 Layout A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `title`, `design_id` FROM `keeko_layout` WHERE `id` = :p0';
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
            $obj = new Layout();
            $obj->hydrate($row);
            LayoutPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Layout|Layout[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Layout[]|mixed the list of results, formatted by the current formatter
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
     * @return LayoutQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LayoutPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return LayoutQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LayoutPeer::ID, $keys, Criteria::IN);
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
     * @return LayoutQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LayoutPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LayoutPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LayoutPeer::ID, $id, $comparison);
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
     * @return LayoutQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LayoutPeer::NAME, $name, $comparison);
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
     * @return LayoutQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LayoutPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the design_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDesignId(1234); // WHERE design_id = 1234
     * $query->filterByDesignId(array(12, 34)); // WHERE design_id IN (12, 34)
     * $query->filterByDesignId(array('min' => 12)); // WHERE design_id >= 12
     * $query->filterByDesignId(array('max' => 12)); // WHERE design_id <= 12
     * </code>
     *
     * @see       filterByDesign()
     *
     * @param     mixed $designId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LayoutQuery The current query, for fluid interface
     */
    public function filterByDesignId($designId = null, $comparison = null)
    {
        if (is_array($designId)) {
            $useMinMax = false;
            if (isset($designId['min'])) {
                $this->addUsingAlias(LayoutPeer::DESIGN_ID, $designId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($designId['max'])) {
                $this->addUsingAlias(LayoutPeer::DESIGN_ID, $designId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LayoutPeer::DESIGN_ID, $designId, $comparison);
    }

    /**
     * Filter the query by a related Design object
     *
     * @param   Design|PropelObjectCollection $design The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 LayoutQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDesign($design, $comparison = null)
    {
        if ($design instanceof Design) {
            return $this
                ->addUsingAlias(LayoutPeer::DESIGN_ID, $design->getId(), $comparison);
        } elseif ($design instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LayoutPeer::DESIGN_ID, $design->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDesign() only accepts arguments of type Design or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Design relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LayoutQuery The current query, for fluid interface
     */
    public function joinDesign($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Design');

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
            $this->addJoinObject($join, 'Design');
        }

        return $this;
    }

    /**
     * Use the Design relation Design object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\DesignQuery A secondary query class using the current class as primary query
     */
    public function useDesignQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDesign($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Design', '\keeko\core\entities\DesignQuery');
    }

    /**
     * Filter the query by a related Page object
     *
     * @param   Page|PropelObjectCollection $page  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 LayoutQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPage($page, $comparison = null)
    {
        if ($page instanceof Page) {
            return $this
                ->addUsingAlias(LayoutPeer::ID, $page->getLayoutId(), $comparison);
        } elseif ($page instanceof PropelObjectCollection) {
            return $this
                ->usePageQuery()
                ->filterByPrimaryKeys($page->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPage() only accepts arguments of type Page or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Page relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LayoutQuery The current query, for fluid interface
     */
    public function joinPage($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Page');

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
            $this->addJoinObject($join, 'Page');
        }

        return $this;
    }

    /**
     * Use the Page relation Page object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\PageQuery A secondary query class using the current class as primary query
     */
    public function usePageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Page', '\keeko\core\entities\PageQuery');
    }

    /**
     * Filter the query by a related Block object
     *
     * @param   Block|PropelObjectCollection $block  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 LayoutQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBlock($block, $comparison = null)
    {
        if ($block instanceof Block) {
            return $this
                ->addUsingAlias(LayoutPeer::ID, $block->getLayoutId(), $comparison);
        } elseif ($block instanceof PropelObjectCollection) {
            return $this
                ->useBlockQuery()
                ->filterByPrimaryKeys($block->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBlock() only accepts arguments of type Block or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Block relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return LayoutQuery The current query, for fluid interface
     */
    public function joinBlock($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Block');

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
            $this->addJoinObject($join, 'Block');
        }

        return $this;
    }

    /**
     * Use the Block relation Block object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\BlockQuery A secondary query class using the current class as primary query
     */
    public function useBlockQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBlock($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Block', '\keeko\core\entities\BlockQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Layout $layout Object to remove from the list of results
     *
     * @return LayoutQuery The current query, for fluid interface
     */
    public function prune($layout = null)
    {
        if ($layout) {
            $this->addUsingAlias(LayoutPeer::ID, $layout->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
