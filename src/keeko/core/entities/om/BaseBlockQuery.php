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
use keeko\core\entities\BlockPeer;
use keeko\core\entities\BlockQuery;
use keeko\core\entities\Layout;

/**
 * Base class that represents a query for the 'keeko_block' table.
 *
 *
 *
 * @method BlockQuery orderById($order = Criteria::ASC) Order by the id column
 * @method BlockQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method BlockQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method BlockQuery orderByLayoutId($order = Criteria::ASC) Order by the layout_id column
 *
 * @method BlockQuery groupById() Group by the id column
 * @method BlockQuery groupByName() Group by the name column
 * @method BlockQuery groupByTitle() Group by the title column
 * @method BlockQuery groupByLayoutId() Group by the layout_id column
 *
 * @method BlockQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method BlockQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method BlockQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method BlockQuery leftJoinLayout($relationAlias = null) Adds a LEFT JOIN clause to the query using the Layout relation
 * @method BlockQuery rightJoinLayout($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Layout relation
 * @method BlockQuery innerJoinLayout($relationAlias = null) Adds a INNER JOIN clause to the query using the Layout relation
 *
 * @method Block findOne(PropelPDO $con = null) Return the first Block matching the query
 * @method Block findOneOrCreate(PropelPDO $con = null) Return the first Block matching the query, or a new Block object populated from the query conditions when no match is found
 *
 * @method Block findOneByName(string $name) Return the first Block filtered by the name column
 * @method Block findOneByTitle(string $title) Return the first Block filtered by the title column
 * @method Block findOneByLayoutId(int $layout_id) Return the first Block filtered by the layout_id column
 *
 * @method array findById(int $id) Return Block objects filtered by the id column
 * @method array findByName(string $name) Return Block objects filtered by the name column
 * @method array findByTitle(string $title) Return Block objects filtered by the title column
 * @method array findByLayoutId(int $layout_id) Return Block objects filtered by the layout_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseBlockQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseBlockQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\Block', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new BlockQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   BlockQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return BlockQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof BlockQuery) {
            return $criteria;
        }
        $query = new BlockQuery();
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
     * @return   Block|Block[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BlockPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(BlockPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Block A model object, or null if the key is not found
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
     * @return                 Block A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `title`, `layout_id` FROM `keeko_block` WHERE `id` = :p0';
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
            $obj = new Block();
            $obj->hydrate($row);
            BlockPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Block|Block[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Block[]|mixed the list of results, formatted by the current formatter
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
     * @return BlockQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BlockPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return BlockQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BlockPeer::ID, $keys, Criteria::IN);
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
     * @return BlockQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BlockPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BlockPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockPeer::ID, $id, $comparison);
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
     * @return BlockQuery The current query, for fluid interface
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

        return $this->addUsingAlias(BlockPeer::NAME, $name, $comparison);
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
     * @return BlockQuery The current query, for fluid interface
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

        return $this->addUsingAlias(BlockPeer::TITLE, $title, $comparison);
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
     * @return BlockQuery The current query, for fluid interface
     */
    public function filterByLayoutId($layoutId = null, $comparison = null)
    {
        if (is_array($layoutId)) {
            $useMinMax = false;
            if (isset($layoutId['min'])) {
                $this->addUsingAlias(BlockPeer::LAYOUT_ID, $layoutId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($layoutId['max'])) {
                $this->addUsingAlias(BlockPeer::LAYOUT_ID, $layoutId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BlockPeer::LAYOUT_ID, $layoutId, $comparison);
    }

    /**
     * Filter the query by a related Layout object
     *
     * @param   Layout|PropelObjectCollection $layout The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BlockQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLayout($layout, $comparison = null)
    {
        if ($layout instanceof Layout) {
            return $this
                ->addUsingAlias(BlockPeer::LAYOUT_ID, $layout->getId(), $comparison);
        } elseif ($layout instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BlockPeer::LAYOUT_ID, $layout->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return BlockQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   Block $block Object to remove from the list of results
     *
     * @return BlockQuery The current query, for fluid interface
     */
    public function prune($block = null)
    {
        if ($block) {
            $this->addUsingAlias(BlockPeer::ID, $block->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
