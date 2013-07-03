<?php

namespace keeko\core\entities\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'keeko_user' table.
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
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'keeko.core.entities.map.UserTableMap';

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
        $this->setName('keeko_user');
        $this->setPhpName('User');
        $this->setClassname('keeko\\core\\entities\\User');
        $this->setPackage('keeko.core.entities');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('login_name', 'LoginName', 'VARCHAR', true, 100, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 100, null);
        $this->addColumn('given_name', 'GivenName', 'VARCHAR', true, 100, null);
        $this->addColumn('family_name', 'FamilyName', 'VARCHAR', true, 100, null);
        $this->addColumn('display_name', 'DisplayName', 'VARCHAR', true, 100, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 255, null);
        $this->addForeignKey('country_iso_nr', 'CountryIsoNr', 'INTEGER', 'keeko_country', 'iso_nr', true, null, null);
        $this->addForeignKey('subdivision_id', 'SubdivisionId', 'INTEGER', 'keeko_subdivision', 'id', false, null, null);
        $this->addColumn('address', 'Address', 'LONGVARCHAR', false, null, null);
        $this->addColumn('address2', 'Address2', 'LONGVARCHAR', false, null, null);
        $this->addColumn('birthday', 'Birthday', 'DATE', true, null, null);
        $this->addColumn('sex', 'Sex', 'TINYINT', true, null, null);
        $this->addColumn('club', 'Club', 'VARCHAR', false, 100, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 128, null);
        $this->addColumn('postal_code', 'PostalCode', 'VARCHAR', false, 45, null);
        $this->addColumn('tan', 'Tan', 'VARCHAR', false, 13, null);
        $this->addColumn('password_recover_code', 'PasswordRecoverCode', 'VARCHAR', false, 32, null);
        $this->addColumn('password_recover_time', 'PasswordRecoverTime', 'TIMESTAMP', false, null, null);
        $this->addColumn('location_status', 'LocationStatus', 'TINYINT', false, 2, null);
        $this->addColumn('latitude', 'Latitude', 'FLOAT', false, 10, null);
        $this->addColumn('longitude', 'Longitude', 'FLOAT', false, 10, null);
        $this->addColumn('created', 'Created', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated', 'Updated', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', 'keeko\\core\\entities\\Country', RelationMap::MANY_TO_ONE, array('country_iso_nr' => 'iso_nr', ), null, null);
        $this->addRelation('Subdivision', 'keeko\\core\\entities\\Subdivision', RelationMap::MANY_TO_ONE, array('subdivision_id' => 'id', ), null, null);
        $this->addRelation('Group', 'keeko\\core\\entities\\Group', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'RESTRICT', null, 'Groups');
        $this->addRelation('GroupUser', 'keeko\\core\\entities\\GroupUser', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'RESTRICT', null, 'GroupUsers');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' =>  array (
  'create_column' => 'created',
  'update_column' => 'updated',
  'disable_updated_at' => 'false',
),
        );
    } // getBehaviors()

} // UserTableMap
