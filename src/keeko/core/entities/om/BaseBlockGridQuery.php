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
use keeko\core\entities\BlockGrid;
use keeko\core\entities\BlockGridExtraProperty;
use keeko\core\entities\BlockGridPeer;
use keeko\core\entities\BlockGridQuery;
use keeko\core\entities\BlockItem;

/**
 * Base class that represents a query for the 'keeko_block_grid' table.
 *
 *
 *
 * @method BlockGridQuery orderById($order = Criteria::ASC) Order by the id column
 * @method BlockGridQuery orderByBlockItemId($order = Criteria::ASC) Order by the block_item_id column
 * @method BlockGridQuery orderBySpan($order = Criteria::ASC) Order by the span column
 *
 * @method BlockGridQuery groupById() Group by the id column
 * @method BlockGridQuery groupByBlockItemId() Group by the block_item_id column
 * @method BlockGridQuery groupBySpan() Group by the span column
 *
 * @method BlockGridQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method BlockGridQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method BlockGridQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method BlockGridQuery leftJoinBlockItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the BlockItem relation
 * @method BlockGridQuery rightJoinBlockItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BlockItem relation
 * @method BlockGridQuery innerJoinBlockItem($relationAlias = null) Adds a INNER JOIN clause to the query using the BlockItem relation
 *
 * @method BlockGridQuery leftJoinBlockGridExtraProperty($relationAlias = null) Adds a LEFT JOIN clause to the query using the BlockGridExtraProperty relation
 * @method BlockGridQuery rightJoinBlockGridExtraProperty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BlockGridExtraProperty relation
 * @method BlockGridQuery innerJoinBlockGridExtraProperty($relationAlias = null) Adds a INNER JOIN clause to the query using the BlockGridExtraProperty relation
 *
 * @method BlockGrid findOne(PropelPDO $con = null) Return the first BlockGrid matching the query
 * @method BlockGrid findOneOrCreate(PropelPDO $con = null) Return the first BlockGrid matching the query, or a new BlockGrid object populated from the query conditions when no match is found
 *
 * @method BlockGrid findOneByBlockItemId(int $block_item_id) Return the first BlockGrid filtered by the block_item_id column
 * @method BlockGrid findOneBySpan(int $span) Return the first BlockGrid filtered by the span column
 *
 * @method array findById(int $id) Return BlockGrid objects filtered by the id column
 * @method array findByBlockItemId(int $block_item_id) Return BlockGrid objects filtered by the block_item_id column
 * @method array findBySpan(int $span) Return BlockGrid objects filtered by the span column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseBlockGridQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseBlockGridQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\BlockGrid', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new BlockGridQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   BlockGridQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return BlockGridQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof BlockGridQuery) {
            return $criteria;
        }
        $query = new BlockGridQuery();
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
     * @return   BlockGrid|BlockGrid[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BlockGridPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(BlockGridPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 BlockGrid A model object, or null if the key is not found
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
     * @return                 BlockGrid A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `block_item_id`, `span` FROM `keeko_block_grid` WHERE `id` = :p0';
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
            $obj = new BlockGrid();
            $obj->hydrate($row);
            BlockGridPeer::addInstanceToPool($obj, (string) $key);
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
     * @return BlockGrid|BlockGrid[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|BlockGrid[]|mixed the list of results, formatted by the current formatter
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
     * @return BlockGridQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BlockGridPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return BlockGridQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BlockGridPeer::ID, $keys, Criteria::IN);
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
     * @return BlockGridQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BlockGridPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BlockGridPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockGridPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the block_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBlockItemId(1234); // WHERE block_item_id = 1234
     * $query->filterByBlockItemId(array(12, 34)); // WHERE block_item_id IN (12, 34)
     * $query->filterByBlockItemId(array('min' => 12)); // WHERE block_item_id >= 12
     * $query->filterByBlockItemId(array('max' => 12)); // WHERE block_item_id <= 12
     * </code>
     *
     * @see       filterByBlockItem()
     *
     * @param     mixed $blockItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BlockGridQuery The current query, for fluid interface
     */
    public function filterByBlockItemId($blockItemId = null, $comparison = null)
    {
        if (is_array($blockItemId)) {
            $useMinMax = false;
            if (isset($blockItemId['min'])) {
                $this->addUsingAlias(BlockGridPeer::BLOCK_ITEM_ID, $blockItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($blockItemId['max'])) {
                $this->addUsingAlias(BlockGridPeer::BLOCK_ITEM_ID, $blockItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockGridPeer::BLOCK_ITEM_ID, $blockItemId, $comparison);
    }

    /**
     * Filter the query on the span column
     *
     * Example usage:
     * <code>
     * $query->filterBySpan(1234); // WHERE span = 1234
     * $query->filterBySpan(array(12, 34)); // WHERE span IN (12, 34)
     * $query->filterBySpan(array('min' => 12)); // WHERE span >= 12
     * $query->filterBySpan(array('max' => 12)); // WHERE span <= 12
     * </code>
     *
     * @param     mixed $span The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BlockGridQuery The current query, for fluid interface
     */
    public function filterBySpan($span = null, $comparison = null)
    {
        if (is_array($span)) {
            $useMinMax = false;
            if (isset($span['min'])) {
                $this->addUsingAlias(BlockGridPeer::SPAN, $span['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($span['max'])) {
                $this->addUsingAlias(BlockGridPeer::SPAN, $span['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockGridPeer::SPAN, $span, $comparison);
    }

    /**
     * Filter the query by a related BlockItem object
     *
     * @param   BlockItem|PropelObjectCollection $blockItem The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BlockGridQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBlockItem($blockItem, $comparison = null)
    {
        if ($blockItem instanceof BlockItem) {
            return $this
                ->addUsingAlias(BlockGridPeer::BLOCK_ITEM_ID, $blockItem->getId(), $comparison);
        } elseif ($blockItem instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BlockGridPeer::BLOCK_ITEM_ID, $blockItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBlockItem() only accepts arguments of type BlockItem or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BlockItem relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BlockGridQuery The current query, for fluid interface
     */
    public function joinBlockItem($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BlockItem');

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
            $this->addJoinObject($join, 'BlockItem');
        }

        return $this;
    }

    /**
     * Use the BlockItem relation BlockItem object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\BlockItemQuery A secondary query class using the current class as primary query
     */
    public function useBlockItemQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBlockItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BlockItem', '\keeko\core\entities\BlockItemQuery');
    }

    /**
     * Filter the query by a related BlockGridExtraProperty object
     *
     * @param   BlockGridExtraProperty|PropelObjectCollection $blockGridExtraProperty  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BlockGridQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBlockGridExtraProperty($blockGridExtraProperty, $comparison = null)
    {
        if ($blockGridExtraProperty instanceof BlockGridExtraProperty) {
            return $this
                ->addUsingAlias(BlockGridPeer::ID, $blockGridExtraProperty->getKeekoBlockGridId(), $comparison);
        } elseif ($blockGridExtraProperty instanceof PropelObjectCollection) {
            return $this
                ->useBlockGridExtraPropertyQuery()
                ->filterByPrimaryKeys($blockGridExtraProperty->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBlockGridExtraProperty() only accepts arguments of type BlockGridExtraProperty or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BlockGridExtraProperty relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BlockGridQuery The current query, for fluid interface
     */
    public function joinBlockGridExtraProperty($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BlockGridExtraProperty');

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
            $this->addJoinObject($join, 'BlockGridExtraProperty');
        }

        return $this;
    }

    /**
     * Use the BlockGridExtraProperty relation BlockGridExtraProperty object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\BlockGridExtraPropertyQuery A secondary query class using the current class as primary query
     */
    public function useBlockGridExtraPropertyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinBlockGridExtraProperty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BlockGridExtraProperty', '\keeko\core\entities\BlockGridExtraPropertyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   BlockGrid $blockGrid Object to remove from the list of results
     *
     * @return BlockGridQuery The current query, for fluid interface
     */
    public function prune($blockGrid = null)
    {
        if ($blockGrid) {
            $this->addUsingAlias(BlockGridPeer::ID, $blockGrid->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // extra_properties behavior
    /**
     * Filter based on an extra property
     *
     * If the property is not set for a particular object it will be present in the results
     *
     * @var string $propertyName The name of the property to filter on
     * @var mixed $propertyValue The value of the property to filter on
     *
     * @return BlockGridQuery
     */
    public function filterByExtraProperty($propertyName, $propertyValue)
    {
      $propertyName = BlockGridPeer::normalizeExtraPropertyName($propertyName);
      $propertyValue = BlockGridPeer::normalizeExtraPropertyValue($propertyValue);

      return $this
        ->leftJoinBlockGridExtraProperty($joinName = $propertyName . '_' . uniqid())
        ->addJoinCondition($joinName, "{$joinName}.PropertyName = ?", $propertyName)
        ->where("{$joinName}.PropertyValue = ?", $propertyValue);
    }

    /**
     * Filter based on an extra property
     *
     * If the property is not set for a particular object it it will be assumed
     * to have a value of $default
     *
     * @var string $propertyName The name of the property to filter on
     * @var mixed $propertyValue The value of the property to filter on
     * @var mixed $default The value that will be assumed as default if an object
     *                     does not have the property set
     *
     * @return BlockGridQuery
     */
    public function filterByExtraPropertyWithDefault($propertyName, $propertyValue, $default)
    {
      $propertyName = BlockGridPeer::normalizeExtraPropertyName($propertyName);
      $propertyValue = BlockGridPeer::normalizeExtraPropertyValue($propertyValue);
      $default = BlockGridPeer::normalizeExtraPropertyValue($default);

      return $this
        ->leftJoinBlockGridExtraProperty($joinName = $propertyName . '_' . uniqid())
        ->addJoinCondition($joinName, "{$joinName}.PropertyName = ?", $propertyName)
        ->where("COALESCE({$joinName}.PropertyValue, '{$default}') = ?", $propertyValue);
    }


}
