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
use keeko\core\entities\Design;
use keeko\core\entities\Module;
use keeko\core\entities\Package;
use keeko\core\entities\PackagePeer;
use keeko\core\entities\PackageQuery;

/**
 * Base class that represents a query for the 'keeko_package' table.
 *
 *
 *
 * @method PackageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PackageQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PackageQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PackageQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PackageQuery orderByInstalledVersion($order = Criteria::ASC) Order by the installed_version column
 *
 * @method PackageQuery groupById() Group by the id column
 * @method PackageQuery groupByName() Group by the name column
 * @method PackageQuery groupByTitle() Group by the title column
 * @method PackageQuery groupByDescription() Group by the description column
 * @method PackageQuery groupByInstalledVersion() Group by the installed_version column
 *
 * @method PackageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PackageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PackageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PackageQuery leftJoinApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Application relation
 * @method PackageQuery rightJoinApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Application relation
 * @method PackageQuery innerJoinApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the Application relation
 *
 * @method PackageQuery leftJoinDesign($relationAlias = null) Adds a LEFT JOIN clause to the query using the Design relation
 * @method PackageQuery rightJoinDesign($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Design relation
 * @method PackageQuery innerJoinDesign($relationAlias = null) Adds a INNER JOIN clause to the query using the Design relation
 *
 * @method PackageQuery leftJoinModule($relationAlias = null) Adds a LEFT JOIN clause to the query using the Module relation
 * @method PackageQuery rightJoinModule($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Module relation
 * @method PackageQuery innerJoinModule($relationAlias = null) Adds a INNER JOIN clause to the query using the Module relation
 *
 * @method Package findOne(PropelPDO $con = null) Return the first Package matching the query
 * @method Package findOneOrCreate(PropelPDO $con = null) Return the first Package matching the query, or a new Package object populated from the query conditions when no match is found
 *
 * @method Package findOneByName(string $name) Return the first Package filtered by the name column
 * @method Package findOneByTitle(string $title) Return the first Package filtered by the title column
 * @method Package findOneByDescription(string $description) Return the first Package filtered by the description column
 * @method Package findOneByInstalledVersion(string $installed_version) Return the first Package filtered by the installed_version column
 *
 * @method array findById(int $id) Return Package objects filtered by the id column
 * @method array findByName(string $name) Return Package objects filtered by the name column
 * @method array findByTitle(string $title) Return Package objects filtered by the title column
 * @method array findByDescription(string $description) Return Package objects filtered by the description column
 * @method array findByInstalledVersion(string $installed_version) Return Package objects filtered by the installed_version column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BasePackageQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePackageQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\Package', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PackageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PackageQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PackageQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PackageQuery) {
            return $criteria;
        }
        $query = new PackageQuery();
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
     * @return   Package|Package[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PackagePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PackagePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Package A model object, or null if the key is not found
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
     * @return                 Package A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `title`, `description`, `installed_version` FROM `keeko_package` WHERE `id` = :p0';
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
            $obj = new Package();
            $obj->hydrate($row);
            PackagePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Package|Package[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Package[]|mixed the list of results, formatted by the current formatter
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
     * @return PackageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PackagePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PackageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PackagePeer::ID, $keys, Criteria::IN);
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
     * @return PackageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PackagePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PackagePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackagePeer::ID, $id, $comparison);
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
     * @return PackageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PackagePeer::NAME, $name, $comparison);
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
     * @return PackageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PackagePeer::TITLE, $title, $comparison);
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
     * @return PackageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PackagePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the installed_version column
     *
     * Example usage:
     * <code>
     * $query->filterByInstalledVersion('fooValue');   // WHERE installed_version = 'fooValue'
     * $query->filterByInstalledVersion('%fooValue%'); // WHERE installed_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $installedVersion The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PackageQuery The current query, for fluid interface
     */
    public function filterByInstalledVersion($installedVersion = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($installedVersion)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $installedVersion)) {
                $installedVersion = str_replace('*', '%', $installedVersion);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PackagePeer::INSTALLED_VERSION, $installedVersion, $comparison);
    }

    /**
     * Filter the query by a related Application object
     *
     * @param   Application|PropelObjectCollection $application  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PackageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplication($application, $comparison = null)
    {
        if ($application instanceof Application) {
            return $this
                ->addUsingAlias(PackagePeer::ID, $application->getPackageId(), $comparison);
        } elseif ($application instanceof PropelObjectCollection) {
            return $this
                ->useApplicationQuery()
                ->filterByPrimaryKeys($application->getPrimaryKeys())
                ->endUse();
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
     * @return PackageQuery The current query, for fluid interface
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
     * Filter the query by a related Design object
     *
     * @param   Design|PropelObjectCollection $design  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PackageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDesign($design, $comparison = null)
    {
        if ($design instanceof Design) {
            return $this
                ->addUsingAlias(PackagePeer::ID, $design->getPackageId(), $comparison);
        } elseif ($design instanceof PropelObjectCollection) {
            return $this
                ->useDesignQuery()
                ->filterByPrimaryKeys($design->getPrimaryKeys())
                ->endUse();
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
     * @return PackageQuery The current query, for fluid interface
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
     * Filter the query by a related Module object
     *
     * @param   Module|PropelObjectCollection $module  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PackageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByModule($module, $comparison = null)
    {
        if ($module instanceof Module) {
            return $this
                ->addUsingAlias(PackagePeer::ID, $module->getPackageId(), $comparison);
        } elseif ($module instanceof PropelObjectCollection) {
            return $this
                ->useModuleQuery()
                ->filterByPrimaryKeys($module->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByModule() only accepts arguments of type Module or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Module relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PackageQuery The current query, for fluid interface
     */
    public function joinModule($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Module');

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
            $this->addJoinObject($join, 'Module');
        }

        return $this;
    }

    /**
     * Use the Module relation Module object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\ModuleQuery A secondary query class using the current class as primary query
     */
    public function useModuleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinModule($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Module', '\keeko\core\entities\ModuleQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Package $package Object to remove from the list of results
     *
     * @return PackageQuery The current query, for fluid interface
     */
    public function prune($package = null)
    {
        if ($package) {
            $this->addUsingAlias(PackagePeer::ID, $package->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
