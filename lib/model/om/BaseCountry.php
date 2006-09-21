<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/model/CountryPeer.php';

/**
 * Base class that represents a row from the 'country' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseCountry extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var CountryPeer
	 */
	protected static $peer;


	/**
	 * The value for the iso field.
	 * @var string
	 */
	protected $iso;


	/**
	 * The value for the name field.
	 * @var string
	 */
	protected $name;


	/**
	 * The value for the printable_name field.
	 * @var string
	 */
	protected $printable_name;


	/**
	 * The value for the iso3 field.
	 * @var string
	 */
	protected $iso3;


	/**
	 * The value for the numcode field.
	 * @var int
	 */
	protected $numcode;

	/**
	 * Collection to store aggregation of collLocations.
	 * @var array
	 */
	protected $collLocations;

	/**
	 * The criteria used to select the current contents of collLocations.
	 * @var Criteria
	 */
	protected $lastLocationCriteria = null;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [iso] column value.
	 * 
	 * @return string
	 */
	public function getIso()
	{

		return $this->iso;
	}

	/**
	 * Get the [name] column value.
	 * 
	 * @return string
	 */
	public function getName()
	{

		return $this->name;
	}

	/**
	 * Get the [printable_name] column value.
	 * 
	 * @return string
	 */
	public function getPrintableName()
	{

		return $this->printable_name;
	}

	/**
	 * Get the [iso3] column value.
	 * 
	 * @return string
	 */
	public function getIso3()
	{

		return $this->iso3;
	}

	/**
	 * Get the [numcode] column value.
	 * 
	 * @return int
	 */
	public function getNumcode()
	{

		return $this->numcode;
	}

	/**
	 * Set the value of [iso] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setIso($v)
	{

		if ($this->iso !== $v) {
			$this->iso = $v;
			$this->modifiedColumns[] = CountryPeer::ISO;
		}

	} // setIso()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setName($v)
	{

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = CountryPeer::NAME;
		}

	} // setName()

	/**
	 * Set the value of [printable_name] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setPrintableName($v)
	{

		if ($this->printable_name !== $v) {
			$this->printable_name = $v;
			$this->modifiedColumns[] = CountryPeer::PRINTABLE_NAME;
		}

	} // setPrintableName()

	/**
	 * Set the value of [iso3] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setIso3($v)
	{

		if ($this->iso3 !== $v) {
			$this->iso3 = $v;
			$this->modifiedColumns[] = CountryPeer::ISO3;
		}

	} // setIso3()

	/**
	 * Set the value of [numcode] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setNumcode($v)
	{

		if ($this->numcode !== $v) {
			$this->numcode = $v;
			$this->modifiedColumns[] = CountryPeer::NUMCODE;
		}

	} // setNumcode()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (1-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
	 * @param int $startcol 1-based offset column which indicates which restultset column to start with.
	 * @return int next starting column
	 * @throws PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->iso = $rs->getString($startcol + 0);

			$this->name = $rs->getString($startcol + 1);

			$this->printable_name = $rs->getString($startcol + 2);

			$this->iso3 = $rs->getString($startcol + 3);

			$this->numcode = $rs->getInt($startcol + 4);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = CountryPeer::NUM_COLUMNS - CountryPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Country object", $e);
		}
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param Connection $con
	 * @return void
	 * @throws PropelException
	 * @see BaseObject::setDeleted()
	 * @see BaseObject::isDeleted()
	 */
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(CountryPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			CountryPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.  If the object is new,
	 * it inserts it; otherwise an update is performed.  This method
	 * wraps the doSave() worker method in a transaction.
	 *
	 * @param Connection $con
	 * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws PropelException
	 * @see doSave()
	 */
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(CountryPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param Connection $con
	 * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws PropelException
	 * @see save()
	 */
	protected function doSave($con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = CountryPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += CountryPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collLocations !== null) {
				foreach($this->collLocations as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return array ValidationFailed[]
	 * @see validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param mixed $columns Column name or an array of column names.
	 * @return boolean Whether all columns pass validation.
	 * @see doValidate()
	 * @see getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param array $columns Array of column names to validate.
	 * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = CountryPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collLocations !== null) {
					foreach($this->collLocations as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param string $name name
	 * @param string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = CountryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param int $pos position in xml schema
	 * @return mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getIso();
				break;
			case 1:
				return $this->getName();
				break;
			case 2:
				return $this->getPrintableName();
				break;
			case 3:
				return $this->getIso3();
				break;
			case 4:
				return $this->getNumcode();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param string $keyType One of the class type constants TYPE_PHPNAME,
	 *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = CountryPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getIso(),
			$keys[1] => $this->getName(),
			$keys[2] => $this->getPrintableName(),
			$keys[3] => $this->getIso3(),
			$keys[4] => $this->getNumcode(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param string $name peer name
	 * @param mixed $value field value
	 * @param string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = CountryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param int $pos position in xml schema
	 * @param mixed $value field value
	 * @return void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setIso($value);
				break;
			case 1:
				$this->setName($value);
				break;
			case 2:
				$this->setPrintableName($value);
				break;
			case 3:
				$this->setIso3($value);
				break;
			case 4:
				$this->setNumcode($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
	 * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
	 *
	 * @param array  $arr     An array to populate the object from.
	 * @param string $keyType The type of keys the array uses.
	 * @return void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = CountryPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setIso($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPrintableName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setIso3($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setNumcode($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(CountryPeer::DATABASE_NAME);

		if ($this->isColumnModified(CountryPeer::ISO)) $criteria->add(CountryPeer::ISO, $this->iso);
		if ($this->isColumnModified(CountryPeer::NAME)) $criteria->add(CountryPeer::NAME, $this->name);
		if ($this->isColumnModified(CountryPeer::PRINTABLE_NAME)) $criteria->add(CountryPeer::PRINTABLE_NAME, $this->printable_name);
		if ($this->isColumnModified(CountryPeer::ISO3)) $criteria->add(CountryPeer::ISO3, $this->iso3);
		if ($this->isColumnModified(CountryPeer::NUMCODE)) $criteria->add(CountryPeer::NUMCODE, $this->numcode);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(CountryPeer::DATABASE_NAME);

		$criteria->add(CountryPeer::ISO, $this->iso);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return string
	 */
	public function getPrimaryKey()
	{
		return $this->getIso();
	}

	/**
	 * Generic method to set the primary key (iso column).
	 *
	 * @param string $key Primary key.
	 * @return void
	 */
	public function setPrimaryKey($key)
	{
		$this->setIso($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param object $copyObj An object of Country (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setName($this->name);

		$copyObj->setPrintableName($this->printable_name);

		$copyObj->setIso3($this->iso3);

		$copyObj->setNumcode($this->numcode);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getLocations() as $relObj) {
				$copyObj->addLocation($relObj->copy($deepCopy));
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setIso(NULL); // this is a pkey column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return Country Clone of current object.
	 * @throws PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return CountryPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new CountryPeer();
		}
		return self::$peer;
	}

	/**
	 * Temporary storage of collLocations to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initLocations()
	{
		if ($this->collLocations === null) {
			$this->collLocations = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Country has previously
	 * been saved, it will retrieve related Locations from storage.
	 * If this Country is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getLocations($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseLocationPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLocations === null) {
			if ($this->isNew()) {
			   $this->collLocations = array();
			} else {

				$criteria->add(LocationPeer::COUNTRY_ID, $this->getIso());

				LocationPeer::addSelectColumns($criteria);
				$this->collLocations = LocationPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(LocationPeer::COUNTRY_ID, $this->getIso());

				LocationPeer::addSelectColumns($criteria);
				if (!isset($this->lastLocationCriteria) || !$this->lastLocationCriteria->equals($criteria)) {
					$this->collLocations = LocationPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastLocationCriteria = $criteria;
		return $this->collLocations;
	}

	/**
	 * Returns the number of related Locations.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countLocations($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseLocationPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(LocationPeer::COUNTRY_ID, $this->getIso());

		return LocationPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a Location object to this object
	 * through the Location foreign key attribute
	 *
	 * @param Location $l Location
	 * @return void
	 * @throws PropelException
	 */
	public function addLocation(Location $l)
	{
		$this->collLocations[] = $l;
		$l->setCountry($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Country is new, it will return
	 * an empty collection; or if this Country has previously
	 * been saved, it will retrieve related Locations from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Country.
	 */
	public function getLocationsJoinRestaurant($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseLocationPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLocations === null) {
			if ($this->isNew()) {
				$this->collLocations = array();
			} else {

				$criteria->add(LocationPeer::COUNTRY_ID, $this->getIso());

				$this->collLocations = LocationPeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(LocationPeer::COUNTRY_ID, $this->getIso());

			if (!isset($this->lastLocationCriteria) || !$this->lastLocationCriteria->equals($criteria)) {
				$this->collLocations = LocationPeer::doSelectJoinRestaurant($criteria, $con);
			}
		}
		$this->lastLocationCriteria = $criteria;

		return $this->collLocations;
	}

} // BaseCountry
