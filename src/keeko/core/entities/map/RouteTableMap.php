<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_route' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.keeko.core.entities.map
 */
class RouteTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.RouteTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('keeko_route');
        $this->setPhpName('Route');
        $this->setClassname('keeko\\core\\entities\\Route');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        $this->addForeignKey('redirect_id', 'RedirectId', 'INTEGER', 'keeko_route', 'id', false, null, null);
        $this->addForeignKey('page_id', 'PageId', 'INTEGER', 'keeko_page', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('RouteRelatedByRedirectId', 'keeko\\core\\entities\\Route', RelationMap::MANY_TO_ONE, array('redirect_id' => 'id', ), null, null);
        $this->addRelation('Page', 'keeko\\core\\entities\\Page', RelationMap::MANY_TO_ONE, array('page_id' => 'id', ), null, null);
        $this->addRelation('RouteRelatedById', 'keeko\\core\\entities\\Route', RelationMap::ONE_TO_MANY, array('id' => 'redirect_id', ), null, null, 'RoutesRelatedById');
    } // buildRelations()

} // RouteTableMap
