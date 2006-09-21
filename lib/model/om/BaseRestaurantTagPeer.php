<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by RestaurantTagPeer::getOMClass()
include_once 'lib/model/RestaurantTag.php';

/**
 * Base static class for performing query and update operations on the 'restaurant_tag' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseRestaurantTagPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'restaurant_tag';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'model.RestaurantTag';

	/** The total number of columns. */
	const NUM_COLUMNS = 5;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the RESTAURANT_ID field */
	const RESTAURANT_ID = 'restaurant_tag.RESTAURANT_ID';

	/** the column name for the USER_ID field */
	const USER_ID = 'restaurant_tag.USER_ID';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'restaurant_tag.CREATED_AT';

	/** the column name for the TAG field */
	const TAG = 'restaurant_tag.TAG';

	/** the column name for the NORMALIZED_TAG field */
	const NORMALIZED_TAG = 'restaurant_tag.NORMALIZED_TAG';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('RestaurantId', 'UserId', 'CreatedAt', 'Tag', 'NormalizedTag', ),
		BasePeer::TYPE_COLNAME => array (RestaurantTagPeer::RESTAURANT_ID, RestaurantTagPeer::USER_ID, RestaurantTagPeer::CREATED_AT, RestaurantTagPeer::TAG, RestaurantTagPeer::NORMALIZED_TAG, ),
		BasePeer::TYPE_FIELDNAME => array ('restaurant_id', 'user_id', 'created_at', 'tag', 'normalized_tag', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('RestaurantId' => 0, 'UserId' => 1, 'CreatedAt' => 2, 'Tag' => 3, 'NormalizedTag' => 4, ),
		BasePeer::TYPE_COLNAME => array (RestaurantTagPeer::RESTAURANT_ID => 0, RestaurantTagPeer::USER_ID => 1, RestaurantTagPeer::CREATED_AT => 2, RestaurantTagPeer::TAG => 3, RestaurantTagPeer::NORMALIZED_TAG => 4, ),
		BasePeer::TYPE_FIELDNAME => array ('restaurant_id' => 0, 'user_id' => 1, 'created_at' => 2, 'tag' => 3, 'normalized_tag' => 4, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, )
	);

	/**
	 * @return MapBuilder the map builder for this peer
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'lib/model/map/RestaurantTagMapBuilder.php';
		return BasePeer::getMapBuilder('model.map.RestaurantTagMapBuilder');
	}
	/**
	 * Gets a map (hash) of PHP names to DB column names.
	 *
	 * @return array The PHP to DB name map for this peer
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @deprecated Use the getFieldNames() and translateFieldName() methods instead of this.
	 */
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = RestaurantTagPeer::getTableMap();
			$columns = $map->getColumns();
			$nameMap = array();
			foreach ($columns as $column) {
				$nameMap[$column->getPhpName()] = $column->getColumnName();
			}
			self::$phpNameMap = $nameMap;
		}
		return self::$phpNameMap;
	}
	/**
	 * Translates a fieldname to another type
	 *
	 * @param string $name field name
	 * @param string $fromType One of the class type constants TYPE_PHPNAME,
	 *                         TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @param string $toType   One of the class type constants
	 * @return string translated name of the field.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of of field names.
	 *
	 * @param  string $type The type of fieldnames to return:
	 *                      One of the class type constants TYPE_PHPNAME,
	 *                      TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param string $alias The alias for the current table.
	 * @param string $column The column name for current table. (i.e. RestaurantTagPeer::COLUMN_NAME).
	 * @return string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(RestaurantTagPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param criteria object containing the columns to add.
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(RestaurantTagPeer::RESTAURANT_ID);

		$criteria->addSelectColumn(RestaurantTagPeer::USER_ID);

		$criteria->addSelectColumn(RestaurantTagPeer::CREATED_AT);

		$criteria->addSelectColumn(RestaurantTagPeer::TAG);

		$criteria->addSelectColumn(RestaurantTagPeer::NORMALIZED_TAG);

	}

	const COUNT = 'COUNT(restaurant_tag.RESTAURANT_ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT restaurant_tag.RESTAURANT_ID)';

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param Connection $con
	 * @return int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = RestaurantTagPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param Criteria $criteria object used to create the SELECT statement.
	 * @param Connection $con
	 * @return RestaurantTag
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = RestaurantTagPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param Connection $con
	 * @return array Array of selected Objects
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return RestaurantTagPeer::populateObjects(RestaurantTagPeer::doSelectRS($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect()
	 * method to get a ResultSet.
	 *
	 * Use this method directly if you want to just get the resultset
	 * (instead of an array of objects).
	 *
	 * @param Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param Connection $con the connection to use
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return ResultSet The resultset object with numerically-indexed fields.
	 * @see BasePeer::doSelect()
	 */
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			RestaurantTagPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a Creole ResultSet, set to return
		// rows indexed numerically.
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = RestaurantTagPeer::getOMClass();
		$cls = Propel::import($cls);
		// populate the object(s)
		while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related Restaurant table
	 *
	 * @param Criteria $c
	 * @param boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param Connection $con
	 * @return int Number of matching rows.
	 */
	public static function doCountJoinRestaurant(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;
		
		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT);
		}
		
		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(RestaurantTagPeer::RESTAURANT_ID, RestaurantPeer::ID);

		$rs = RestaurantTagPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Returns the number of rows matching criteria, joining the related User table
	 *
	 * @param Criteria $c
	 * @param boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param Connection $con
	 * @return int Number of matching rows.
	 */
	public static function doCountJoinUser(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;
		
		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT);
		}
		
		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(RestaurantTagPeer::USER_ID, UserPeer::ID);

		$rs = RestaurantTagPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of RestaurantTag objects pre-filled with their Restaurant objects.
	 *
	 * @return array Array of RestaurantTag objects.
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinRestaurant(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		RestaurantTagPeer::addSelectColumns($c);
		$startcol = (RestaurantTagPeer::NUM_COLUMNS - RestaurantTagPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		RestaurantPeer::addSelectColumns($c);

		$c->addJoin(RestaurantTagPeer::RESTAURANT_ID, RestaurantPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = RestaurantTagPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = RestaurantPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getRestaurant(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addRestaurantTag($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initRestaurantTags();
				$obj2->addRestaurantTag($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Selects a collection of RestaurantTag objects pre-filled with their User objects.
	 *
	 * @return array Array of RestaurantTag objects.
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinUser(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		RestaurantTagPeer::addSelectColumns($c);
		$startcol = (RestaurantTagPeer::NUM_COLUMNS - RestaurantTagPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		UserPeer::addSelectColumns($c);

		$c->addJoin(RestaurantTagPeer::USER_ID, UserPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = RestaurantTagPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = UserPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getUser(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addRestaurantTag($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initRestaurantTags();
				$obj2->addRestaurantTag($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param Criteria $c
	 * @param boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param Connection $con
	 * @return int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT);
		}
		
		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(RestaurantTagPeer::RESTAURANT_ID, RestaurantPeer::ID);

		$criteria->addJoin(RestaurantTagPeer::USER_ID, UserPeer::ID);

		$rs = RestaurantTagPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of RestaurantTag objects pre-filled with all related objects.
	 *
	 * @return array Array of RestaurantTag objects.
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		RestaurantTagPeer::addSelectColumns($c);
		$startcol2 = (RestaurantTagPeer::NUM_COLUMNS - RestaurantTagPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		RestaurantPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + RestaurantPeer::NUM_COLUMNS;

		UserPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + UserPeer::NUM_COLUMNS;

		$c->addJoin(RestaurantTagPeer::RESTAURANT_ID, RestaurantPeer::ID);

		$c->addJoin(RestaurantTagPeer::USER_ID, UserPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();
		
		while($rs->next()) {

			$omClass = RestaurantTagPeer::getOMClass();

			
			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

				
				// Add objects for joined Restaurant rows
	
			$omClass = RestaurantPeer::getOMClass();

	
			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);
			
			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getRestaurant(); // CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addRestaurantTag($obj1); // CHECKME
					break;
				}
			}
			
			if ($newObject) {
				$obj2->initRestaurantTags();
				$obj2->addRestaurantTag($obj1);
			}

				
				// Add objects for joined User rows
	
			$omClass = UserPeer::getOMClass();

	
			$cls = Propel::import($omClass);
			$obj3 = new $cls();
			$obj3->hydrate($rs, $startcol3);
			
			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj3 = $temp_obj1->getUser(); // CHECKME
				if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
					$newObject = false;
					$temp_obj3->addRestaurantTag($obj1); // CHECKME
					break;
				}
			}
			
			if ($newObject) {
				$obj3->initRestaurantTags();
				$obj3->addRestaurantTag($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Restaurant table
	 *
	 * @param Criteria $c
	 * @param boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param Connection $con
	 * @return int Number of matching rows.
	 */
	public static function doCountJoinAllExceptRestaurant(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;
		
		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT);
		}
		
		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(RestaurantTagPeer::USER_ID, UserPeer::ID);

		$rs = RestaurantTagPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Returns the number of rows matching criteria, joining the related User table
	 *
	 * @param Criteria $c
	 * @param boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param Connection $con
	 * @return int Number of matching rows.
	 */
	public static function doCountJoinAllExceptUser(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;
		
		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(RestaurantTagPeer::COUNT);
		}
		
		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(RestaurantTagPeer::RESTAURANT_ID, RestaurantPeer::ID);

		$rs = RestaurantTagPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of RestaurantTag objects pre-filled with all related objects except Restaurant.
	 *
	 * @return array Array of RestaurantTag objects.
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptRestaurant(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		RestaurantTagPeer::addSelectColumns($c);
		$startcol2 = (RestaurantTagPeer::NUM_COLUMNS - RestaurantTagPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		UserPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + UserPeer::NUM_COLUMNS;

		$c->addJoin(RestaurantTagPeer::USER_ID, UserPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();
		
		while($rs->next()) {

			$omClass = RestaurantTagPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);		

			$omClass = UserPeer::getOMClass();

	
			$cls = Propel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);
			
			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getUser(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addRestaurantTag($obj1);
					break;
				}
			}
			
			if ($newObject) {
				$obj2->initRestaurantTags();
				$obj2->addRestaurantTag($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Selects a collection of RestaurantTag objects pre-filled with all related objects except User.
	 *
	 * @return array Array of RestaurantTag objects.
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptUser(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		RestaurantTagPeer::addSelectColumns($c);
		$startcol2 = (RestaurantTagPeer::NUM_COLUMNS - RestaurantTagPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		RestaurantPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + RestaurantPeer::NUM_COLUMNS;

		$c->addJoin(RestaurantTagPeer::RESTAURANT_ID, RestaurantPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();
		
		while($rs->next()) {

			$omClass = RestaurantTagPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);		

			$omClass = RestaurantPeer::getOMClass();

	
			$cls = Propel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);
			
			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getRestaurant(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addRestaurantTag($obj1);
					break;
				}
			}
			
			if ($newObject) {
				$obj2->initRestaurantTags();
				$obj2->addRestaurantTag($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}

	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return TableMap
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * This uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @return string path.to.ClassName
	 */
	public static function getOMClass()
	{
		return RestaurantTagPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a RestaurantTag or Criteria object.
	 *
	 * @param mixed $values Criteria or RestaurantTag object containing data that is used to create the INSERT statement.
	 * @param Connection $con the connection to use
	 * @return mixed The new primary key.
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from RestaurantTag object
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a RestaurantTag or Criteria object.
	 *
	 * @param mixed $values Criteria or RestaurantTag object containing data that is used to create the UPDATE statement.
	 * @param Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return int The number of affected rows (if supported by underlying database driver).
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(RestaurantTagPeer::RESTAURANT_ID);
			$selectCriteria->add(RestaurantTagPeer::RESTAURANT_ID, $criteria->remove(RestaurantTagPeer::RESTAURANT_ID), $comparison);

			$comparison = $criteria->getComparison(RestaurantTagPeer::USER_ID);
			$selectCriteria->add(RestaurantTagPeer::USER_ID, $criteria->remove(RestaurantTagPeer::USER_ID), $comparison);

			$comparison = $criteria->getComparison(RestaurantTagPeer::NORMALIZED_TAG);
			$selectCriteria->add(RestaurantTagPeer::NORMALIZED_TAG, $criteria->remove(RestaurantTagPeer::NORMALIZED_TAG), $comparison);

		} else { // $values is RestaurantTag object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the restaurant_tag table.
	 *
	 * @return int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			$affectedRows += BasePeer::doDeleteAll(RestaurantTagPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a RestaurantTag or Criteria object OR a primary key value.
	 *
	 * @param mixed $values Criteria or RestaurantTag object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param Connection $con the connection to use
	 * @return int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(RestaurantTagPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof RestaurantTag) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			// primary key is composite; we therefore, expect
			// the primary key passed to be an array of pkey
			// values
			if(count($values) == count($values, COUNT_RECURSIVE))
			{
				// array is not multi-dimensional
				$values = array($values);
			}
			$vals = array();
			foreach($values as $value)
			{

				$vals[0][] = $value[0];
				$vals[1][] = $value[1];
				$vals[2][] = $value[2];
			}

			$criteria->add(RestaurantTagPeer::RESTAURANT_ID, $vals[0], Criteria::IN);
			$criteria->add(RestaurantTagPeer::USER_ID, $vals[1], Criteria::IN);
			$criteria->add(RestaurantTagPeer::NORMALIZED_TAG, $vals[2], Criteria::IN);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given RestaurantTag object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param RestaurantTag $obj The object to validate.
	 * @param mixed $cols Column name or array of column names.
	 *
	 * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(RestaurantTag $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(RestaurantTagPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(RestaurantTagPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(RestaurantTagPeer::DATABASE_NAME, RestaurantTagPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = RestaurantTagPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	/**
	 * Retrieve object using using composite pkey values.
	 * @param int $restaurant_id
	   @param int $user_id
	   @param string $normalized_tag
	   
	 * @param Connection $con
	 * @return RestaurantTag
	 */
	public static function retrieveByPK( $restaurant_id, $user_id, $normalized_tag, $con = null) {
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$criteria = new Criteria();
		$criteria->add(RestaurantTagPeer::RESTAURANT_ID, $restaurant_id);
		$criteria->add(RestaurantTagPeer::USER_ID, $user_id);
		$criteria->add(RestaurantTagPeer::NORMALIZED_TAG, $normalized_tag);
		$v = RestaurantTagPeer::doSelect($criteria, $con);

		return !empty($v) ? $v[0] : null;
	}
} // BaseRestaurantTagPeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseRestaurantTagPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'lib/model/map/RestaurantTagMapBuilder.php';
	Propel::registerMapBuilder('model.map.RestaurantTagMapBuilder');
}
