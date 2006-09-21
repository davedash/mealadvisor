<?php
		
require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'location' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an 
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive 
 * (i.e. if it's a text column type).
 *
 * @package model.map
 */	
class LocationMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'model.map.LocationMapBuilder';	

    /**
     * The database map.
     */
    private $dbMap;

	/**
     * Tells us if this DatabaseMapBuilder is built so that we
     * don't have to re-build it every time.
     *
     * @return boolean true if this DatabaseMapBuilder is built, false otherwise.
     */
    public function isBuilt()
    {
        return ($this->dbMap !== null);
    }

	/**
     * Gets the databasemap this map builder built.
     *
     * @return the databasemap
     */
    public function getDatabaseMap()
    {
        return $this->dbMap;
    }

    /**
     * The doBuild() method builds the DatabaseMap
     *
	 * @return void
     * @throws PropelException
     */
    public function doBuild()
    {
		$this->dbMap = Propel::getDatabaseMap('propel');
		
		$tMap = $this->dbMap->addTable('location');
		$tMap->setPhpName('Location');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('RESTAURANT_ID', 'RestaurantId', 'int', CreoleTypes::INTEGER, 'restaurant', 'ID', false, null);

		$tMap->addColumn('STRIPPED_TITLE', 'StrippedTitle', 'string', CreoleTypes::VARCHAR, false);

		$tMap->addColumn('NAME', 'Name', 'string', CreoleTypes::VARCHAR, false);

		$tMap->addColumn('ADDRESS', 'Address', 'string', CreoleTypes::VARCHAR, false);

		$tMap->addColumn('CITY', 'City', 'string', CreoleTypes::VARCHAR, false);

		$tMap->addColumn('STATE', 'State', 'string', CreoleTypes::VARCHAR, false);

		$tMap->addColumn('ZIP', 'Zip', 'string', CreoleTypes::VARCHAR, false);

		$tMap->addForeignKey('COUNTRY_ID', 'CountryId', 'string', CreoleTypes::CHAR, 'country', 'ISO', false, 2);

		$tMap->addColumn('LATITUDE', 'Latitude', 'double', CreoleTypes::FLOAT, false);

		$tMap->addColumn('LONGITUDE', 'Longitude', 'double', CreoleTypes::FLOAT, false);

		$tMap->addColumn('PHONE', 'Phone', 'string', CreoleTypes::VARCHAR, false);

		$tMap->addColumn('APPROVED', 'Approved', 'boolean', CreoleTypes::BOOLEAN, false);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false);
				
    } // doBuild()

} // LocationMapBuilder
