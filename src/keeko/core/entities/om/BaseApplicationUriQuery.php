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
use keeko\core\entities\ApplicationUri;
use keeko\core\entities\ApplicationUriPeer;
use keeko\core\entities\ApplicationUriQuery;
use keeko\core\entities\Localization;

/**
 * Base class that represents a query for the 'keeko_application_uri' table.
 *
 *
 *
 * @method ApplicationUriQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ApplicationUriQuery orderByHttphost($order = Criteria::ASC) Order by the httphost column
 * @method ApplicationUriQuery orderByBasepath($order = Criteria::ASC) Order by the basepath column
 * @method ApplicationUriQuery orderBySecure($order = Criteria::ASC) Order by the secure column
 * @method ApplicationUriQuery orderByApplicationId($order = Criteria::ASC) Order by the application_id column
 * @method ApplicationUriQuery orderByLocalizationId($order = Criteria::ASC) Order by the localization_id column
 *
 * @method ApplicationUriQuery groupById() Group by the id column
 * @method ApplicationUriQuery groupByHttphost() Group by the httphost column
 * @method ApplicationUriQuery groupByBasepath() Group by the basepath column
 * @method ApplicationUriQuery groupBySecure() Group by the secure column
 * @method ApplicationUriQuery groupByApplicationId() Group by the application_id column
 * @method ApplicationUriQuery groupByLocalizationId() Group by the localization_id column
 *
 * @method ApplicationUriQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ApplicationUriQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ApplicationUriQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ApplicationUriQuery leftJoinApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Application relation
 * @method ApplicationUriQuery rightJoinApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Application relation
 * @method ApplicationUriQuery innerJoinApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the Application relation
 *
 * @method ApplicationUriQuery leftJoinLocalization($relationAlias = null) Adds a LEFT JOIN clause to the query using the Localization relation
 * @method ApplicationUriQuery rightJoinLocalization($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Localization relation
 * @method ApplicationUriQuery innerJoinLocalization($relationAlias = null) Adds a INNER JOIN clause to the query using the Localization relation
 *
 * @method ApplicationUri findOne(PropelPDO $con = null) Return the first ApplicationUri matching the query
 * @method ApplicationUri findOneOrCreate(PropelPDO $con = null) Return the first ApplicationUri matching the query, or a new ApplicationUri object populated from the query conditions when no match is found
 *
 * @method ApplicationUri findOneByHttphost(string $httphost) Return the first ApplicationUri filtered by the httphost column
 * @method ApplicationUri findOneByBasepath(string $basepath) Return the first ApplicationUri filtered by the basepath column
 * @method ApplicationUri findOneBySecure(boolean $secure) Return the first ApplicationUri filtered by the secure column
 * @method ApplicationUri findOneByApplicationId(int $application_id) Return the first ApplicationUri filtered by the application_id column
 * @method ApplicationUri findOneByLocalizationId(int $localization_id) Return the first ApplicationUri filtered by the localization_id column
 *
 * @method array findById(int $id) Return ApplicationUri objects filtered by the id column
 * @method array findByHttphost(string $httphost) Return ApplicationUri objects filtered by the httphost column
 * @method array findByBasepath(string $basepath) Return ApplicationUri objects filtered by the basepath column
 * @method array findBySecure(boolean $secure) Return ApplicationUri objects filtered by the secure column
 * @method array findByApplicationId(int $application_id) Return ApplicationUri objects filtered by the application_id column
 * @method array findByLocalizationId(int $localization_id) Return ApplicationUri objects filtered by the localization_id column
 *
 * @package    propel.generator.keeko.core.entities.om
 */
abstract class BaseApplicationUriQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseApplicationUriQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'keeko', $modelName = 'keeko\\core\\entities\\ApplicationUri', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ApplicationUriQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ApplicationUriQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ApplicationUriQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ApplicationUriQuery) {
            return $criteria;
        }
        $query = new ApplicationUriQuery();
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
     * @return   ApplicationUri|ApplicationUri[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ApplicationUriPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ApplicationUriPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 ApplicationUri A model object, or null if the key is not found
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
     * @return                 ApplicationUri A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `httphost`, `basepath`, `secure`, `application_id`, `localization_id` FROM `keeko_application_uri` WHERE `id` = :p0';
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
            $obj = new ApplicationUri();
            $obj->hydrate($row);
            ApplicationUriPeer::addInstanceToPool($obj, (string) $key);
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
     * @return ApplicationUri|ApplicationUri[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ApplicationUri[]|mixed the list of results, formatted by the current formatter
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
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ApplicationUriPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ApplicationUriPeer::ID, $keys, Criteria::IN);
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
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ApplicationUriPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ApplicationUriPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationUriPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the httphost column
     *
     * Example usage:
     * <code>
     * $query->filterByHttphost('fooValue');   // WHERE httphost = 'fooValue'
     * $query->filterByHttphost('%fooValue%'); // WHERE httphost LIKE '%fooValue%'
     * </code>
     *
     * @param     string $httphost The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function filterByHttphost($httphost = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($httphost)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $httphost)) {
                $httphost = str_replace('*', '%', $httphost);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationUriPeer::HTTPHOST, $httphost, $comparison);
    }

    /**
     * Filter the query on the basepath column
     *
     * Example usage:
     * <code>
     * $query->filterByBasepath('fooValue');   // WHERE basepath = 'fooValue'
     * $query->filterByBasepath('%fooValue%'); // WHERE basepath LIKE '%fooValue%'
     * </code>
     *
     * @param     string $basepath The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function filterByBasepath($basepath = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($basepath)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $basepath)) {
                $basepath = str_replace('*', '%', $basepath);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ApplicationUriPeer::BASEPATH, $basepath, $comparison);
    }

    /**
     * Filter the query on the secure column
     *
     * Example usage:
     * <code>
     * $query->filterBySecure(true); // WHERE secure = true
     * $query->filterBySecure('yes'); // WHERE secure = true
     * </code>
     *
     * @param     boolean|string $secure The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function filterBySecure($secure = null, $comparison = null)
    {
        if (is_string($secure)) {
            $secure = in_array(strtolower($secure), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ApplicationUriPeer::SECURE, $secure, $comparison);
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
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function filterByApplicationId($applicationId = null, $comparison = null)
    {
        if (is_array($applicationId)) {
            $useMinMax = false;
            if (isset($applicationId['min'])) {
                $this->addUsingAlias(ApplicationUriPeer::APPLICATION_ID, $applicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($applicationId['max'])) {
                $this->addUsingAlias(ApplicationUriPeer::APPLICATION_ID, $applicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationUriPeer::APPLICATION_ID, $applicationId, $comparison);
    }

    /**
     * Filter the query on the localization_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLocalizationId(1234); // WHERE localization_id = 1234
     * $query->filterByLocalizationId(array(12, 34)); // WHERE localization_id IN (12, 34)
     * $query->filterByLocalizationId(array('min' => 12)); // WHERE localization_id >= 12
     * $query->filterByLocalizationId(array('max' => 12)); // WHERE localization_id <= 12
     * </code>
     *
     * @see       filterByLocalization()
     *
     * @param     mixed $localizationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function filterByLocalizationId($localizationId = null, $comparison = null)
    {
        if (is_array($localizationId)) {
            $useMinMax = false;
            if (isset($localizationId['min'])) {
                $this->addUsingAlias(ApplicationUriPeer::LOCALIZATION_ID, $localizationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($localizationId['max'])) {
                $this->addUsingAlias(ApplicationUriPeer::LOCALIZATION_ID, $localizationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationUriPeer::LOCALIZATION_ID, $localizationId, $comparison);
    }

    /**
     * Filter the query by a related Application object
     *
     * @param   Application|PropelObjectCollection $application The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationUriQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByApplication($application, $comparison = null)
    {
        if ($application instanceof Application) {
            return $this
                ->addUsingAlias(ApplicationUriPeer::APPLICATION_ID, $application->getId(), $comparison);
        } elseif ($application instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationUriPeer::APPLICATION_ID, $application->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ApplicationUriQuery The current query, for fluid interface
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
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\ApplicationQuery A secondary query class using the current class as primary query
     */
    public function useApplicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinApplication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Application', '\keeko\core\entities\ApplicationQuery');
    }

    /**
     * Filter the query by a related Localization object
     *
     * @param   Localization|PropelObjectCollection $localization The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ApplicationUriQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLocalization($localization, $comparison = null)
    {
        if ($localization instanceof Localization) {
            return $this
                ->addUsingAlias(ApplicationUriPeer::LOCALIZATION_ID, $localization->getId(), $comparison);
        } elseif ($localization instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationUriPeer::LOCALIZATION_ID, $localization->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLocalization() only accepts arguments of type Localization or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Localization relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function joinLocalization($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Localization');

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
            $this->addJoinObject($join, 'Localization');
        }

        return $this;
    }

    /**
     * Use the Localization relation Localization object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \keeko\core\entities\LocalizationQuery A secondary query class using the current class as primary query
     */
    public function useLocalizationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLocalization($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Localization', '\keeko\core\entities\LocalizationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ApplicationUri $applicationUri Object to remove from the list of results
     *
     * @return ApplicationUriQuery The current query, for fluid interface
     */
    public function prune($applicationUri = null)
    {
        if ($applicationUri) {
            $this->addUsingAlias(ApplicationUriPeer::ID, $applicationUri->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}