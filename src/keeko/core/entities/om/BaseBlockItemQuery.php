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
use keeko\core\entities\BlockContent;
use keeko\core\entities\BlockGrid;
use keeko\core\entities\BlockItem;
use keeko\core\entities\BlockItemPeer;
use keeko\core\entities\BlockItemQuery;

/**
 * Base class that represents a query for the 'keeko_block_item' table.
 *
 *
 *
 * @method BlockItemQuery orderById($order = Criteria::ASC) Order by the id column
 * @method BlockItemQuery orderByBlockId($order = Criteria::ASC) Order by the block_id column
 * @method BlockItemQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 *
 * @method BlockItemQuery groupById() Group by the id column
 * @method BlockItemQuery groupByBlockId() Group by the block_id column
 * @method BlockItemQuery groupByParentId() Group by the parent_id column
 *
 * @method BlockItemQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method BlockItemQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method BlockItemQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method BlockItemQuery leftJoinBlockGrid($relationAlias = null) Adds a LEFT JOIN clause to the query using the BlockGrid relation
 * @method BlockItemQuery rightJoinBlockGrid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BlockGrid relation
 * @method BlockItemQuery innerJoinBlockGrid($relationAlias = null) Adds a INNER JOIN clause to the query using the BlockGrid relation
 *
 * @method BlockItemQuery leftJoinBlockContent($relationAlias = null) Adds a LEFT JOIN clause to the query using the BlockContent relation
 * @method BlockItemQuery rightJoinBlockContent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BlockContent relation
 * @method BlockItemQuery innerJoinBlockContent($relationAlias = null) Adds a INNER JOIN clause to the query using the BlockContent relation
 *
 * @method BlockItem findOne(PropelPDO $con = null) Return the first BlockItem matching the query
 * @method BlockItem findOneOrCreate(PropelPDO $con = null) Return the first BlockItem matching the query, or a new BlockItem object populated from the query conditions when no match is found
 *
 * @method BlockItem findOneByBlockId(int $block_id) Return the first BlockItem filtered by the block_id column
 * @method BlockItem findOneByParentId(int $parent_id) Return the first BlockItem filtered by the parent_id column
 *
 * @method array findById(int $id) Return BlockItem objects filtered by the id column
 * @method array findByBlockId(int $block_id) Return BlockItem objects filtered by the block_id column
 * @method array findByParentId(int $parent_id) Return BlockItem objects filtered by the parent_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseBlockItemQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseBlockItemQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\BlockItem', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new BlockItemQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   BlockItemQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return BlockItemQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof BlockItemQuery) {
            return $criteria;
        }
        $query = new BlockItemQuery();
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
     * @param     PropelPDO $con an optional connection object
     *
     * @return   BlockItem|BlockItem[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BlockItemPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(BlockItemPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 BlockItem A model object, or null if the key is not found
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
     * @return                 BlockItem A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `block_id`, `parent_id` FROM `keeko_block_item` WHERE `id` = :p0';
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
            $obj = new BlockItem();
            $obj->hydrate($row);
            BlockItemPeer::addInstanceToPool($obj, (string) $key);
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
     * @return BlockItem|BlockItem[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|BlockItem[]|mixed the list of results, formatted by the current formatter
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
     * @return BlockItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BlockItemPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return BlockItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BlockItemPeer::ID, $keys, Criteria::IN);
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
     * @return BlockItemQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BlockItemPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BlockItemPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockItemPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the block_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBlockId(1234); // WHERE block_id = 1234
     * $query->filterByBlockId(array(12, 34)); // WHERE block_id IN (12, 34)
     * $query->filterByBlockId(array('min' => 12)); // WHERE block_id >= 12
     * $query->filterByBlockId(array('max' => 12)); // WHERE block_id <= 12
     * </code>
     *
     * @param     mixed $blockId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BlockItemQuery The current query, for fluid interface
     */
    public function filterByBlockId($blockId = null, $comparison = null)
    {
        if (is_array($blockId)) {
            $useMinMax = false;
            if (isset($blockId['min'])) {
                $this->addUsingAlias(BlockItemPeer::BLOCK_ID, $blockId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($blockId['max'])) {
                $this->addUsingAlias(BlockItemPeer::BLOCK_ID, $blockId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockItemPeer::BLOCK_ID, $blockId, $comparison);
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
     * @param     mixed $parentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BlockItemQuery The current query, for fluid interface
     */
    public function filterByParentId($parentId = null, $comparison = null)
    {
        if (is_array($parentId)) {
            $useMinMax = false;
            if (isset($parentId['min'])) {
                $this->addUsingAlias(BlockItemPeer::PARENT_ID, $parentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentId['max'])) {
                $this->addUsingAlias(BlockItemPeer::PARENT_ID, $parentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockItemPeer::PARENT_ID, $parentId, $comparison);
    }

    /**
     * Filter the query by a related BlockGrid object
     *
     * @param   BlockGrid|PropelObjectCollection $blockGrid  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BlockItemQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBlockGrid($blockGrid, $comparison = null)
    {
        if ($blockGrid instanceof BlockGrid) {
            return $this
                ->addUsingAlias(BlockItemPeer::ID, $blockGrid->getBlockItemId(), $comparison);
        } elseif ($blockGrid instanceof PropelObjectCollection) {
            return $this
                ->useBlockGridQuery()
                ->filterByPrimaryKeys($blockGrid->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBlockGrid() only accepts arguments of type BlockGrid or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BlockGrid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BlockItemQuery The current query, for fluid interface
     */
    public function joinBlockGrid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BlockGrid');

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
            $this->addJoinObject($join, 'BlockGrid');
        }

        return $this;
    }

    /**
     * Use the BlockGrid relation BlockGrid object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\BlockGridQuery A secondary query class using the current class as primary query
     */
    public function useBlockGridQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBlockGrid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BlockGrid', '\keeko\core\entities\BlockGridQuery');
    }

    /**
     * Filter the query by a related BlockContent object
     *
     * @param   BlockContent|PropelObjectCollection $blockContent  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BlockItemQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBlockContent($blockContent, $comparison = null)
    {
        if ($blockContent instanceof BlockContent) {
            return $this
                ->addUsingAlias(BlockItemPeer::ID, $blockContent->getBlockItemId(), $comparison);
        } elseif ($blockContent instanceof PropelObjectCollection) {
            return $this
                ->useBlockContentQuery()
                ->filterByPrimaryKeys($blockContent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBlockContent() only accepts arguments of type BlockContent or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BlockContent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BlockItemQuery The current query, for fluid interface
     */
    public function joinBlockContent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BlockContent');

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
            $this->addJoinObject($join, 'BlockContent');
        }

        return $this;
    }

    /**
     * Use the BlockContent relation BlockContent object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\BlockContentQuery A secondary query class using the current class as primary query
     */
    public function useBlockContentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBlockContent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BlockContent', '\keeko\core\entities\BlockContentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   BlockItem $blockItem Object to remove from the list of results
     *
     * @return BlockItemQuery The current query, for fluid interface
     */
    public function prune($blockItem = null)
    {
        if ($blockItem) {
            $this->addUsingAlias(BlockItemPeer::ID, $blockItem->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
