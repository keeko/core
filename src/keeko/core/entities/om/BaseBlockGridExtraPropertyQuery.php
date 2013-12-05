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
use keeko\core\entities\BlockGridExtraPropertyPeer;
use keeko\core\entities\BlockGridExtraPropertyQuery;

/**
 * Base class that represents a query for the 'keeko_block_grid_properties' table.
 *
 *
 *
 * @method BlockGridExtraPropertyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method BlockGridExtraPropertyQuery orderByPropertyName($order = Criteria::ASC) Order by the property_name column
 * @method BlockGridExtraPropertyQuery orderByPropertyValue($order = Criteria::ASC) Order by the property_value column
 * @method BlockGridExtraPropertyQuery orderByKeekoBlockGridId($order = Criteria::ASC) Order by the keeko_block_grid_id column
 *
 * @method BlockGridExtraPropertyQuery groupById() Group by the id column
 * @method BlockGridExtraPropertyQuery groupByPropertyName() Group by the property_name column
 * @method BlockGridExtraPropertyQuery groupByPropertyValue() Group by the property_value column
 * @method BlockGridExtraPropertyQuery groupByKeekoBlockGridId() Group by the keeko_block_grid_id column
 *
 * @method BlockGridExtraPropertyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method BlockGridExtraPropertyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method BlockGridExtraPropertyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method BlockGridExtraPropertyQuery leftJoinBlockGrid($relationAlias = null) Adds a LEFT JOIN clause to the query using the BlockGrid relation
 * @method BlockGridExtraPropertyQuery rightJoinBlockGrid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BlockGrid relation
 * @method BlockGridExtraPropertyQuery innerJoinBlockGrid($relationAlias = null) Adds a INNER JOIN clause to the query using the BlockGrid relation
 *
 * @method BlockGridExtraProperty findOne(PropelPDO $con = null) Return the first BlockGridExtraProperty matching the query
 * @method BlockGridExtraProperty findOneOrCreate(PropelPDO $con = null) Return the first BlockGridExtraProperty matching the query, or a new BlockGridExtraProperty object populated from the query conditions when no match is found
 *
 * @method BlockGridExtraProperty findOneByPropertyName(string $property_name) Return the first BlockGridExtraProperty filtered by the property_name column
 * @method BlockGridExtraProperty findOneByPropertyValue(string $property_value) Return the first BlockGridExtraProperty filtered by the property_value column
 * @method BlockGridExtraProperty findOneByKeekoBlockGridId(int $keeko_block_grid_id) Return the first BlockGridExtraProperty filtered by the keeko_block_grid_id column
 *
 * @method array findById(int $id) Return BlockGridExtraProperty objects filtered by the id column
 * @method array findByPropertyName(string $property_name) Return BlockGridExtraProperty objects filtered by the property_name column
 * @method array findByPropertyValue(string $property_value) Return BlockGridExtraProperty objects filtered by the property_value column
 * @method array findByKeekoBlockGridId(int $keeko_block_grid_id) Return BlockGridExtraProperty objects filtered by the keeko_block_grid_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseBlockGridExtraPropertyQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseBlockGridExtraPropertyQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\BlockGridExtraProperty', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new BlockGridExtraPropertyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   BlockGridExtraPropertyQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return BlockGridExtraPropertyQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof BlockGridExtraPropertyQuery) {
            return $criteria;
        }
        $query = new BlockGridExtraPropertyQuery();
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
     * @return   BlockGridExtraProperty|BlockGridExtraProperty[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BlockGridExtraPropertyPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(BlockGridExtraPropertyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 BlockGridExtraProperty A model object, or null if the key is not found
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
     * @return                 BlockGridExtraProperty A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `property_name`, `property_value`, `keeko_block_grid_id` FROM `keeko_block_grid_properties` WHERE `id` = :p0';
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
            $obj = new BlockGridExtraProperty();
            $obj->hydrate($row);
            BlockGridExtraPropertyPeer::addInstanceToPool($obj, (string) $key);
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
     * @return BlockGridExtraProperty|BlockGridExtraProperty[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|BlockGridExtraProperty[]|mixed the list of results, formatted by the current formatter
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
     * @return BlockGridExtraPropertyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BlockGridExtraPropertyPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return BlockGridExtraPropertyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BlockGridExtraPropertyPeer::ID, $keys, Criteria::IN);
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
     * @return BlockGridExtraPropertyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BlockGridExtraPropertyPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BlockGridExtraPropertyPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockGridExtraPropertyPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the property_name column
     *
     * Example usage:
     * <code>
     * $query->filterByPropertyName('fooValue');   // WHERE property_name = 'fooValue'
     * $query->filterByPropertyName('%fooValue%'); // WHERE property_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $propertyName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BlockGridExtraPropertyQuery The current query, for fluid interface
     */
    public function filterByPropertyName($propertyName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($propertyName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $propertyName)) {
                $propertyName = str_replace('*', '%', $propertyName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BlockGridExtraPropertyPeer::PROPERTY_NAME, $propertyName, $comparison);
    }

    /**
     * Filter the query on the property_value column
     *
     * Example usage:
     * <code>
     * $query->filterByPropertyValue('fooValue');   // WHERE property_value = 'fooValue'
     * $query->filterByPropertyValue('%fooValue%'); // WHERE property_value LIKE '%fooValue%'
     * </code>
     *
     * @param     string $propertyValue The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BlockGridExtraPropertyQuery The current query, for fluid interface
     */
    public function filterByPropertyValue($propertyValue = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($propertyValue)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $propertyValue)) {
                $propertyValue = str_replace('*', '%', $propertyValue);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BlockGridExtraPropertyPeer::PROPERTY_VALUE, $propertyValue, $comparison);
    }

    /**
     * Filter the query on the keeko_block_grid_id column
     *
     * Example usage:
     * <code>
     * $query->filterByKeekoBlockGridId(1234); // WHERE keeko_block_grid_id = 1234
     * $query->filterByKeekoBlockGridId(array(12, 34)); // WHERE keeko_block_grid_id IN (12, 34)
     * $query->filterByKeekoBlockGridId(array('min' => 12)); // WHERE keeko_block_grid_id >= 12
     * $query->filterByKeekoBlockGridId(array('max' => 12)); // WHERE keeko_block_grid_id <= 12
     * </code>
     *
     * @see       filterByBlockGrid()
     *
     * @param     mixed $keekoBlockGridId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BlockGridExtraPropertyQuery The current query, for fluid interface
     */
    public function filterByKeekoBlockGridId($keekoBlockGridId = null, $comparison = null)
    {
        if (is_array($keekoBlockGridId)) {
            $useMinMax = false;
            if (isset($keekoBlockGridId['min'])) {
                $this->addUsingAlias(BlockGridExtraPropertyPeer::KEEKO_BLOCK_GRID_ID, $keekoBlockGridId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($keekoBlockGridId['max'])) {
                $this->addUsingAlias(BlockGridExtraPropertyPeer::KEEKO_BLOCK_GRID_ID, $keekoBlockGridId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockGridExtraPropertyPeer::KEEKO_BLOCK_GRID_ID, $keekoBlockGridId, $comparison);
    }

    /**
     * Filter the query by a related BlockGrid object
     *
     * @param   BlockGrid|PropelObjectCollection $blockGrid The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BlockGridExtraPropertyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBlockGrid($blockGrid, $comparison = null)
    {
        if ($blockGrid instanceof BlockGrid) {
            return $this
                ->addUsingAlias(BlockGridExtraPropertyPeer::KEEKO_BLOCK_GRID_ID, $blockGrid->getId(), $comparison);
        } elseif ($blockGrid instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BlockGridExtraPropertyPeer::KEEKO_BLOCK_GRID_ID, $blockGrid->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return BlockGridExtraPropertyQuery The current query, for fluid interface
     */
    public function joinBlockGrid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useBlockGridQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinBlockGrid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BlockGrid', '\keeko\core\entities\BlockGridQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   BlockGridExtraProperty $blockGridExtraProperty Object to remove from the list of results
     *
     * @return BlockGridExtraPropertyQuery The current query, for fluid interface
     */
    public function prune($blockGridExtraProperty = null)
    {
        if ($blockGridExtraProperty) {
            $this->addUsingAlias(BlockGridExtraPropertyPeer::ID, $blockGridExtraProperty->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}