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
use keeko\core\model\Module as ChildModule;
use keeko\core\model\ModuleQuery as ChildModuleQuery;
use keeko\core\model\PackageQuery as ChildPackageQuery;
use keeko\core\model\Map\ModuleTableMap;

/**
 * Base class that represents a query for the 'kk_module' table.
 *
 *
 *
 * @method     ChildModuleQuery orderByClassName($order = Criteria::ASC) Order by the class_name column
 * @method     ChildModuleQuery orderByActivatedVersion($order = Criteria::ASC) Order by the activated_version column
 * @method     ChildModuleQuery orderByDefaultAction($order = Criteria::ASC) Order by the default_action column
 * @method     ChildModuleQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method     ChildModuleQuery orderByApi($order = Criteria::ASC) Order by the has_api column
 * @method     ChildModuleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildModuleQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildModuleQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildModuleQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildModuleQuery orderByInstalledVersion($order = Criteria::ASC) Order by the installed_version column
 *
 * @method     ChildModuleQuery groupByClassName() Group by the class_name column
 * @method     ChildModuleQuery groupByActivatedVersion() Group by the activated_version column
 * @method     ChildModuleQuery groupByDefaultAction() Group by the default_action column
 * @method     ChildModuleQuery groupBySlug() Group by the slug column
 * @method     ChildModuleQuery groupByApi() Group by the has_api column
 * @method     ChildModuleQuery groupById() Group by the id column
 * @method     ChildModuleQuery groupByName() Group by the name column
 * @method     ChildModuleQuery groupByTitle() Group by the title column
 * @method     ChildModuleQuery groupByDescription() Group by the description column
 * @method     ChildModuleQuery groupByInstalledVersion() Group by the installed_version column
 *
 * @method     ChildModuleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildModuleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildModuleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildModuleQuery leftJoinPackage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Package relation
 * @method     ChildModuleQuery rightJoinPackage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Package relation
 * @method     ChildModuleQuery innerJoinPackage($relationAlias = null) Adds a INNER JOIN clause to the query using the Package relation
 *
 * @method     ChildModuleQuery leftJoinAction($relationAlias = null) Adds a LEFT JOIN clause to the query using the Action relation
 * @method     ChildModuleQuery rightJoinAction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Action relation
 * @method     ChildModuleQuery innerJoinAction($relationAlias = null) Adds a INNER JOIN clause to the query using the Action relation
 *
 * @method     \keeko\core\model\PackageQuery|\keeko\core\model\ActionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildModule findOne(ConnectionInterface $con = null) Return the first ChildModule matching the query
 * @method     ChildModule findOneOrCreate(ConnectionInterface $con = null) Return the first ChildModule matching the query, or a new ChildModule object populated from the query conditions when no match is found
 *
 * @method     ChildModule findOneByClassName(string $class_name) Return the first ChildModule filtered by the class_name column
 * @method     ChildModule findOneByActivatedVersion(string $activated_version) Return the first ChildModule filtered by the activated_version column
 * @method     ChildModule findOneByDefaultAction(string $default_action) Return the first ChildModule filtered by the default_action column
 * @method     ChildModule findOneBySlug(string $slug) Return the first ChildModule filtered by the slug column
 * @method     ChildModule findOneByApi(boolean $has_api) Return the first ChildModule filtered by the has_api column
 * @method     ChildModule findOneById(int $id) Return the first ChildModule filtered by the id column
 * @method     ChildModule findOneByName(string $name) Return the first ChildModule filtered by the name column
 * @method     ChildModule findOneByTitle(string $title) Return the first ChildModule filtered by the title column
 * @method     ChildModule findOneByDescription(string $description) Return the first ChildModule filtered by the description column
 * @method     ChildModule findOneByInstalledVersion(string $installed_version) Return the first ChildModule filtered by the installed_version column *

 * @method     ChildModule requirePk($key, ConnectionInterface $con = null) Return the ChildModule by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOne(ConnectionInterface $con = null) Return the first ChildModule matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildModule requireOneByClassName(string $class_name) Return the first ChildModule filtered by the class_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneByActivatedVersion(string $activated_version) Return the first ChildModule filtered by the activated_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneByDefaultAction(string $default_action) Return the first ChildModule filtered by the default_action column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneBySlug(string $slug) Return the first ChildModule filtered by the slug column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneByApi(boolean $has_api) Return the first ChildModule filtered by the has_api column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneById(int $id) Return the first ChildModule filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneByName(string $name) Return the first ChildModule filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneByTitle(string $title) Return the first ChildModule filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneByDescription(string $description) Return the first ChildModule filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildModule requireOneByInstalledVersion(string $installed_version) Return the first ChildModule filtered by the installed_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildModule[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildModule objects based on current ModelCriteria
 * @method     ChildModule[]|ObjectCollection findByClassName(string $class_name) Return ChildModule objects filtered by the class_name column
 * @method     ChildModule[]|ObjectCollection findByActivatedVersion(string $activated_version) Return ChildModule objects filtered by the activated_version column
 * @method     ChildModule[]|ObjectCollection findByDefaultAction(string $default_action) Return ChildModule objects filtered by the default_action column
 * @method     ChildModule[]|ObjectCollection findBySlug(string $slug) Return ChildModule objects filtered by the slug column
 * @method     ChildModule[]|ObjectCollection findByApi(boolean $has_api) Return ChildModule objects filtered by the has_api column
 * @method     ChildModule[]|ObjectCollection findById(int $id) Return ChildModule objects filtered by the id column
 * @method     ChildModule[]|ObjectCollection findByName(string $name) Return ChildModule objects filtered by the name column
 * @method     ChildModule[]|ObjectCollection findByTitle(string $title) Return ChildModule objects filtered by the title column
 * @method     ChildModule[]|ObjectCollection findByDescription(string $description) Return ChildModule objects filtered by the description column
 * @method     ChildModule[]|ObjectCollection findByInstalledVersion(string $installed_version) Return ChildModule objects filtered by the installed_version column
 * @method     ChildModule[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ModuleQuery extends ChildPackageQuery
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \keeko\core\model\Base\ModuleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = '\\keeko\\core\\model\\Module', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildModuleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildModuleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildModuleQuery) {
            return $criteria;
        }
        $query = new ChildModuleQuery();
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
     * @return ChildModule|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ModuleTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ModuleTableMap::DATABASE_NAME);
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
     * @return ChildModule A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `class_name`, `activated_version`, `default_action`, `slug`, `has_api`, `id`, `name`, `title`, `description`, `installed_version` FROM `kk_module` WHERE `id` = :p0';
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
            /** @var ChildModule $obj */
            $obj = new ChildModule();
            $obj->hydrate($row);
            ModuleTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildModule|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ModuleTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ModuleTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the class_name column
     *
     * Example usage:
     * <code>
     * $query->filterByClassName('fooValue');   // WHERE class_name = 'fooValue'
     * $query->filterByClassName('%fooValue%'); // WHERE class_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $className The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function filterByClassName($className = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($className)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $className)) {
                $className = str_replace('*', '%', $className);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModuleTableMap::COL_CLASS_NAME, $className, $comparison);
    }

    /**
     * Filter the query on the activated_version column
     *
     * Example usage:
     * <code>
     * $query->filterByActivatedVersion('fooValue');   // WHERE activated_version = 'fooValue'
     * $query->filterByActivatedVersion('%fooValue%'); // WHERE activated_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $activatedVersion The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function filterByActivatedVersion($activatedVersion = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($activatedVersion)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $activatedVersion)) {
                $activatedVersion = str_replace('*', '%', $activatedVersion);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModuleTableMap::COL_ACTIVATED_VERSION, $activatedVersion, $comparison);
    }

    /**
     * Filter the query on the default_action column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultAction('fooValue');   // WHERE default_action = 'fooValue'
     * $query->filterByDefaultAction('%fooValue%'); // WHERE default_action LIKE '%fooValue%'
     * </code>
     *
     * @param     string $defaultAction The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function filterByDefaultAction($defaultAction = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($defaultAction)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $defaultAction)) {
                $defaultAction = str_replace('*', '%', $defaultAction);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ModuleTableMap::COL_DEFAULT_ACTION, $defaultAction, $comparison);
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
     * @return $this|ChildModuleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ModuleTableMap::COL_SLUG, $slug, $comparison);
    }

    /**
     * Filter the query on the has_api column
     *
     * Example usage:
     * <code>
     * $query->filterByApi(true); // WHERE has_api = true
     * $query->filterByApi('yes'); // WHERE has_api = true
     * </code>
     *
     * @param     boolean|string $api The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function filterByApi($api = null, $comparison = null)
    {
        if (is_string($api)) {
            $api = in_array(strtolower($api), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ModuleTableMap::COL_HAS_API, $api, $comparison);
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
     * @see       filterByPackage()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ModuleTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ModuleTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ModuleTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildModuleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ModuleTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildModuleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ModuleTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildModuleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ModuleTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildModuleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ModuleTableMap::COL_INSTALLED_VERSION, $installedVersion, $comparison);
    }

    /**
     * Filter the query by a related \keeko\core\model\Package object
     *
     * @param \keeko\core\model\Package|ObjectCollection $package The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildModuleQuery The current query, for fluid interface
     */
    public function filterByPackage($package, $comparison = null)
    {
        if ($package instanceof \keeko\core\model\Package) {
            return $this
                ->addUsingAlias(ModuleTableMap::COL_ID, $package->getId(), $comparison);
        } elseif ($package instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ModuleTableMap::COL_ID, $package->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPackage() only accepts arguments of type \keeko\core\model\Package or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Package relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function joinPackage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Package');

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
            $this->addJoinObject($join, 'Package');
        }

        return $this;
    }

    /**
     * Use the Package relation Package object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\PackageQuery A secondary query class using the current class as primary query
     */
    public function usePackageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPackage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Package', '\keeko\core\model\PackageQuery');
    }

    /**
     * Filter the query by a related \keeko\core\model\Action object
     *
     * @param \keeko\core\model\Action|ObjectCollection $action the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildModuleQuery The current query, for fluid interface
     */
    public function filterByAction($action, $comparison = null)
    {
        if ($action instanceof \keeko\core\model\Action) {
            return $this
                ->addUsingAlias(ModuleTableMap::COL_ID, $action->getModuleId(), $comparison);
        } elseif ($action instanceof ObjectCollection) {
            return $this
                ->useActionQuery()
                ->filterByPrimaryKeys($action->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAction() only accepts arguments of type \keeko\core\model\Action or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Action relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function joinAction($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Action');

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
            $this->addJoinObject($join, 'Action');
        }

        return $this;
    }

    /**
     * Use the Action relation Action object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \keeko\core\model\ActionQuery A secondary query class using the current class as primary query
     */
    public function useActionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Action', '\keeko\core\model\ActionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildModule $module Object to remove from the list of results
     *
     * @return $this|ChildModuleQuery The current query, for fluid interface
     */
    public function prune($module = null)
    {
        if ($module) {
            $this->addUsingAlias(ModuleTableMap::COL_ID, $module->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kk_module table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ModuleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ModuleTableMap::clearInstancePool();
            ModuleTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ModuleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ModuleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ModuleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ModuleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ModuleQuery
