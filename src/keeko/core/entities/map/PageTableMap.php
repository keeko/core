<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_page' table.
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
class PageTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.PageTableMap';

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
        $this->setName('keeko_page');
        $this->setPhpName('Page');
        $this->setClassname('keeko\\core\\entities\\Page');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('parent_id', 'ParentId', 'INTEGER', 'keeko_page', 'id', false, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('keywords', 'Keywords', 'VARCHAR', false, 255, null);
        $this->addForeignKey('layout_id', 'LayoutId', 'INTEGER', 'keeko_layout', 'id', false, null, null);
        $this->addForeignKey('application_id', 'ApplicationId', 'INTEGER', 'keeko_application', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PageRelatedByParentId', 'keeko\\core\\entities\\Page', RelationMap::MANY_TO_ONE, array('parent_id' => 'id', ), null, null);
        $this->addRelation('Layout', 'keeko\\core\\entities\\Layout', RelationMap::MANY_TO_ONE, array('layout_id' => 'id', ), null, null);
        $this->addRelation('Application', 'keeko\\core\\entities\\Application', RelationMap::MANY_TO_ONE, array('application_id' => 'id', ), null, null);
        $this->addRelation('PageRelatedById', 'keeko\\core\\entities\\Page', RelationMap::ONE_TO_MANY, array('id' => 'parent_id', ), null, null, 'PagesRelatedById');
        $this->addRelation('Route', 'keeko\\core\\entities\\Route', RelationMap::ONE_TO_MANY, array('id' => 'page_id', ), null, null, 'Routes');
    } // buildRelations()

} // PageTableMap
