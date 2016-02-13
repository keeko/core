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
use keeko\core\model\Package as ChildPackage;
use keeko\core\model\PackageQuery as ChildPackageQuery;
use keeko\core\model\Map\PackageTableMap;

/**
 * Base class that represents a query for the 'kk_package' table.
 *
 *
 *
 * @method     ChildPackageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPackageQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildPackageQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildPackageQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildPackageQuery orderByInstalledVersion($order = Criteria::ASC) Order by the installed_version column
 * @method     ChildPackageQuery orderByDescendantClass($order = Criteria::ASC) Order by the descendant_class column
 *
 * @method     ChildPackageQuery groupById() Group by the id column
 * @method     ChildPackageQuery groupByName() Group by the name column
 * @method     ChildPackageQuery groupByTitle() Group by the title column
 * @method     ChildPackageQuery groupByDescription() Group by the description column
 * @method     ChildPackageQuery groupByInstalledVersion() Group by the installed_version column
 * @method     ChildPackageQuery groupByDescendantClass() Group by the descendant_class column
 *
 * @method     ChildPackageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPackageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPackageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPackageQuery leftJoinExtension($relationAlias = null) Adds a LEFT JOIN clause to the query using the Extension relation
 * @method     ChildPackageQuery rightJoinExtension($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Extension relation
 * @method     ChildPackageQuery innerJoinExtension($relationAlias = null) Adds a INNER JOIN clause to the query using the Extension relation
 *
 * @method     ChildPackageQuery leftJoinApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Application relation
 * @method     ChildPackageQuery rightJoinApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Application relation
 * @method     ChildPackageQuery innerJoinApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the Application relation
 *
 * @method     ChildPackageQuery leftJoinModule($relationAlias = null) Adds a LEFT JOIN clause to the query using the Module relation
 * @method     ChildPackageQuery rightJoinModule($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Module relation
 * @method     ChildPackageQuery innerJoinModule($relationAlias = null) Adds a INNER JOIN clause to the query using the Module relation
 *
 * @method     \keeko\core\model\ExtensionQuery|\keeko\core\model\ApplicationQuery|\keeko\core\model\ModuleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPackage findOne(ConnectionInterface $con = null) Return the first ChildPackage matching the query
 * @method     ChildPackage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPackage matching the query, or a new ChildPackage object populated from the query conditions when no match is found
 *
 * @method     ChildPackage findOneById(int $id) Return the first ChildPackage filtered by the id column
 * @method     ChildPackage findOneByName(string $name) Return the first ChildPackage filtered by the name column
 * @method     ChildPackage findOneByTitle(string $title) Return the first ChildPackage filtered by the title column
 * @method     ChildPackage findOneByDescription(string $description) Return the first ChildPackage filtered by the description column
 * @method     ChildPackage findOneByInstalledVersion(string $installed_version) Return the first ChildPackage filtered by the installed_version column
 * @method     ChildPackage findOneByDescendantClass(string $descendant_class) Return the first ChildPackage filtered by the descendant_class column *

 * @method     ChildPackage requirePk($key, ConnectionInterface $con = null) Return the ChildPackage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackage requireOne(ConnectionInterface $con = null) Return the first ChildPackage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPackage requireOneById(int $id) Return the first ChildPackage filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackage requireOneByName(string $name) Return the first ChildPackage filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackage requireOneByTitle(string $title) Return the first ChildPackage filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackage requireOneByDescription(string $description) Return the first ChildPackage filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackage requireOneByInstalledVersion(string $installed_version) Return the first ChildPackage filtered by the installed_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackage requireOneByDescendantClass(string $descendant_class) Return the first ChildPackage filtered by the descendant_class column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPackage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPackage objects based on current ModelCriteria
 * @method     ChildPackage[]|ObjectCollection findById(int $id) Return ChildPackage objects filtered by the id column
 * @method     ChildPackage[]|ObjectCollection findByName(string $name) Return ChildPackage objects filtered by the name column
 * @method     ChildPackage[]|ObjectCollection findByTitle(string $title) Return ChildPackage objects filtered by the title column
 * @method     ChildPackage[]|ObjectCollection findByDescription(string $description) Return ChildPackage objects filtered by the description column
 * @method     ChildPackage[]|ObjectCollection findByInstalledVersion(string $installed_version) Return ChildPackage objects filtered by the installed_version column
 * @method     ChildPackage[]|ObjectCollection findByDescendantClass(string $descendant_class) Return ChildPackage objects filtered by the descendant_class column
 * @method     ChildPackage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PackageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\PackageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Package', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPackageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPackageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPackageQuery) {
            return $criteria;
        }
        $query = new ChildPackageQuery();
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
     * @return ChildPackage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PackageTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PackageTableMap::DATABASE_NAME);
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
     * @return ChildPackage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `title`, `description`, `installed_version`, `descendant_class` FROM `kk_package` WHERE `id` = :p0';
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
            /** @var ChildPackage $obj */
            $obj = new ChildPackage();
            $obj->hydrate($row);
            PackageTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildPackage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPackageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PackageTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPackageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PackageTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPackageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PackageTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PackageTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackageTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPackageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PackageTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildPackageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PackageTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildPackageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PackageTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildPackageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PackageTableMap::COL_INSTALLED_VERSION, $installedVersion, $comparison);
    }

    /**
     * Filter the query on the descendant_class column
     *
     * Example usage:
     * <code>
     * $query->filterByDescendantClass('fooValue');   // WHERE descendant_class = 'fooValue'
     * $query->filterByDescendantClass('%fooValue%'); // WHERE descendant_class LIKE '%fooValue%'
     * </code>
     *
     * @param     string $descendantClass The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackageQuery The current query, for fluid interface
     */
    public function filterByDescendantClass($descendantClass = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($descendantClass)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $descendantClass)) {
                $descendantClass = str_replace('*', '%', $descendantClass);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PackageTableMap::COL_DESCENDANT_CLASS, $descendantClass, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Extension object
     *
     * @param \keeko\core\model\Extension|ObjectCollection $extension the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPackageQuery The current query, for fluid interface
     */
    public function filterByExtension($extension, $comparison = null)
    {
        if ($extension instanceof \keeko\core\model\Extension) {
            return $this
                ->addUsingAlias(PackageTableMap::COL_ID, $extension->getPackageId(), $comparison);
        } elseif ($extension instanceof ObjectCollection) {
            return $this
                ->useExtensionQuery()
                ->filterByPrimaryKeys($extension->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByExtension() only accepts arguments of type \keeko\core\model\Extension or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Extension relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackageQuery The current query, for fluid interface
     */
    public function joinExtension($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Extension');

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
            $this->addJoinObject($join, 'Extension');
        }

        return $this;
    }

    /**
     * Use the Extension relation Extension object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ExtensionQuery A secondary query class using the current class as primary query
     */
    public function useExtensionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinExtension($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Extension', '\keeko\core\model\ExtensionQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Application object
     *
     * @param \keeko\core\model\Application|ObjectCollection $application the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPackageQuery The current query, for fluid interface
     */
    public function filterByApplication($application, $comparison = null)
    {
        if ($application instanceof \keeko\core\model\Application) {
            return $this
                ->addUsingAlias(PackageTableMap::COL_ID, $application->getId(), $comparison);
        } elseif ($application instanceof ObjectCollection) {
            return $this
                ->useApplicationQuery()
                ->filterByPrimaryKeys($application->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByApplication() only accepts arguments of type \keeko\core\model\Application or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Application relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackageQuery The current query, for fluid interface
     */
    public function joinApplication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ApplicationQuery A secondary query class using the current class as primary query
     */
    public function useApplicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinApplication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Application', '\keeko\core\model\ApplicationQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Module object
     *
     * @param \keeko\core\model\Module|ObjectCollection $module the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPackageQuery The current query, for fluid interface
     */
    public function filterByModule($module, $comparison = null)
    {
        if ($module instanceof \keeko\core\model\Module) {
            return $this
                ->addUsingAlias(PackageTableMap::COL_ID, $module->getId(), $comparison);
        } elseif ($module instanceof ObjectCollection) {
            return $this
                ->useModuleQuery()
                ->filterByPrimaryKeys($module->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByModule() only accepts arguments of type \keeko\core\model\Module or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Module relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackageQuery The current query, for fluid interface
     */
    public function joinModule($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ModuleQuery A secondary query class using the current class as primary query
     */
    public function useModuleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinModule($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Module', '\keeko\core\model\ModuleQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPackage $package Object to remove from the list of results
     *
     * @return $this|ChildPackageQuery The current query, for fluid interface
     */
    public function prune($package = null)
    {
        if ($package) {
            $this->addUsingAlias(PackageTableMap::COL_ID, $package->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_package table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PackageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PackageTableMap::clearInstancePool();
            PackageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PackageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PackageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PackageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PackageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PackageQuery
