<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/model/MenuitemVersionPeer.php';

/**
 * Base class that represents a row from the 'menuitem_version' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseMenuitemVersion extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var MenuitemVersionPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var int
	 */
	protected $id;


	/**
	 * The value for the description field.
	 * @var string
	 */
	protected $description;


	/**
	 * The value for the html_description field.
	 * @var string
	 */
	protected $html_description;


	/**
	 * The value for the location_id field.
	 * @var int
	 */
	protected $location_id;


	/**
	 * The value for the menuitem_id field.
	 * @var int
	 */
	protected $menuitem_id;


	/**
	 * The value for the user_id field.
	 * @var int
	 */
	protected $user_id;


	/**
	 * The value for the price field.
	 * @var string
	 */
	protected $price;


	/**
	 * The value for the created_at field.
	 * @var int
	 */
	protected $created_at;

	/**
	 * @var Location
	 */
	protected $aLocation;

	/**
	 * @var MenuItem
	 */
	protected $aMenuItem;

	/**
	 * @var User
	 */
	protected $aUser;

	/**
	 * Collection to store aggregation of collMenuItems.
	 * @var array
	 */
	protected $collMenuItems;

	/**
	 * The criteria used to select the current contents of collMenuItems.
	 * @var Criteria
	 */
	protected $lastMenuItemCriteria = null;

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
	 * Get the [id] column value.
	 * 
	 * @return int
	 */
	public function getId()
	{

		return $this->id;
	}

	/**
	 * Get the [description] column value.
	 * 
	 * @return string
	 */
	public function getDescription()
	{

		return $this->description;
	}

	/**
	 * Get the [html_description] column value.
	 * 
	 * @return string
	 */
	public function getHtmlDescription()
	{

		return $this->html_description;
	}

	/**
	 * Get the [location_id] column value.
	 * 
	 * @return int
	 */
	public function getLocationId()
	{

		return $this->location_id;
	}

	/**
	 * Get the [menuitem_id] column value.
	 * 
	 * @return int
	 */
	public function getMenuitemId()
	{

		return $this->menuitem_id;
	}

	/**
	 * Get the [user_id] column value.
	 * 
	 * @return int
	 */
	public function getUserId()
	{

		return $this->user_id;
	}

	/**
	 * Get the [price] column value.
	 * 
	 * @return string
	 */
	public function getPrice()
	{

		return $this->price;
	}

	/**
	 * Get the [optionally formatted] [created_at] column value.
	 * 
	 * @param string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
			}
		} else {
			$ts = $this->created_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setId($v)
	{

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = MenuitemVersionPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [description] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setDescription($v)
	{

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = MenuitemVersionPeer::DESCRIPTION;
		}

	} // setDescription()

	/**
	 * Set the value of [html_description] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setHtmlDescription($v)
	{

		if ($this->html_description !== $v) {
			$this->html_description = $v;
			$this->modifiedColumns[] = MenuitemVersionPeer::HTML_DESCRIPTION;
		}

	} // setHtmlDescription()

	/**
	 * Set the value of [location_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setLocationId($v)
	{

		if ($this->location_id !== $v) {
			$this->location_id = $v;
			$this->modifiedColumns[] = MenuitemVersionPeer::LOCATION_ID;
		}

		if ($this->aLocation !== null && $this->aLocation->getId() !== $v) {
			$this->aLocation = null;
		}

	} // setLocationId()

	/**
	 * Set the value of [menuitem_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setMenuitemId($v)
	{

		if ($this->menuitem_id !== $v) {
			$this->menuitem_id = $v;
			$this->modifiedColumns[] = MenuitemVersionPeer::MENUITEM_ID;
		}

		if ($this->aMenuItem !== null && $this->aMenuItem->getId() !== $v) {
			$this->aMenuItem = null;
		}

	} // setMenuitemId()

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setUserId($v)
	{

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = MenuitemVersionPeer::USER_ID;
		}

		if ($this->aUser !== null && $this->aUser->getId() !== $v) {
			$this->aUser = null;
		}

	} // setUserId()

	/**
	 * Set the value of [price] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setPrice($v)
	{

		if ($this->price !== $v) {
			$this->price = $v;
			$this->modifiedColumns[] = MenuitemVersionPeer::PRICE;
		}

	} // setPrice()

	/**
	 * Set the value of [created_at] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setCreatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts) {
			$this->created_at = $ts;
			$this->modifiedColumns[] = MenuitemVersionPeer::CREATED_AT;
		}

	} // setCreatedAt()

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

			$this->id = $rs->getInt($startcol + 0);

			$this->description = $rs->getString($startcol + 1);

			$this->html_description = $rs->getString($startcol + 2);

			$this->location_id = $rs->getInt($startcol + 3);

			$this->menuitem_id = $rs->getInt($startcol + 4);

			$this->user_id = $rs->getInt($startcol + 5);

			$this->price = $rs->getString($startcol + 6);

			$this->created_at = $rs->getTimestamp($startcol + 7, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 8; // 8 = MenuitemVersionPeer::NUM_COLUMNS - MenuitemVersionPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating MenuitemVersion object", $e);
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
			$con = Propel::getConnection(MenuitemVersionPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			MenuitemVersionPeer::doDelete($this, $con);
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
    if ($this->isNew() && !$this->isColumnModified('created_at'))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(MenuitemVersionPeer::DATABASE_NAME);
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


			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aLocation !== null) {
				if ($this->aLocation->isModified()) {
					$affectedRows += $this->aLocation->save($con);
				}
				$this->setLocation($this->aLocation);
			}

			if ($this->aMenuItem !== null) {
				if ($this->aMenuItem->isModified()) {
					$affectedRows += $this->aMenuItem->save($con);
				}
				$this->setMenuItem($this->aMenuItem);
			}

			if ($this->aUser !== null) {
				if ($this->aUser->isModified()) {
					$affectedRows += $this->aUser->save($con);
				}
				$this->setUser($this->aUser);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = MenuitemVersionPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += MenuitemVersionPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collMenuItems !== null) {
				foreach($this->collMenuItems as $referrerFK) {
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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aLocation !== null) {
				if (!$this->aLocation->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aLocation->getValidationFailures());
				}
			}

			if ($this->aMenuItem !== null) {
				if (!$this->aMenuItem->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMenuItem->getValidationFailures());
				}
			}

			if ($this->aUser !== null) {
				if (!$this->aUser->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
				}
			}


			if (($retval = MenuitemVersionPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collMenuItems !== null) {
					foreach($this->collMenuItems as $referrerFK) {
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
		$pos = MenuitemVersionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getId();
				break;
			case 1:
				return $this->getDescription();
				break;
			case 2:
				return $this->getHtmlDescription();
				break;
			case 3:
				return $this->getLocationId();
				break;
			case 4:
				return $this->getMenuitemId();
				break;
			case 5:
				return $this->getUserId();
				break;
			case 6:
				return $this->getPrice();
				break;
			case 7:
				return $this->getCreatedAt();
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
		$keys = MenuitemVersionPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getDescription(),
			$keys[2] => $this->getHtmlDescription(),
			$keys[3] => $this->getLocationId(),
			$keys[4] => $this->getMenuitemId(),
			$keys[5] => $this->getUserId(),
			$keys[6] => $this->getPrice(),
			$keys[7] => $this->getCreatedAt(),
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
		$pos = MenuitemVersionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setId($value);
				break;
			case 1:
				$this->setDescription($value);
				break;
			case 2:
				$this->setHtmlDescription($value);
				break;
			case 3:
				$this->setLocationId($value);
				break;
			case 4:
				$this->setMenuitemId($value);
				break;
			case 5:
				$this->setUserId($value);
				break;
			case 6:
				$this->setPrice($value);
				break;
			case 7:
				$this->setCreatedAt($value);
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
		$keys = MenuitemVersionPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDescription($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setHtmlDescription($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setLocationId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setMenuitemId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setUserId($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPrice($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(MenuitemVersionPeer::DATABASE_NAME);

		if ($this->isColumnModified(MenuitemVersionPeer::ID)) $criteria->add(MenuitemVersionPeer::ID, $this->id);
		if ($this->isColumnModified(MenuitemVersionPeer::DESCRIPTION)) $criteria->add(MenuitemVersionPeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(MenuitemVersionPeer::HTML_DESCRIPTION)) $criteria->add(MenuitemVersionPeer::HTML_DESCRIPTION, $this->html_description);
		if ($this->isColumnModified(MenuitemVersionPeer::LOCATION_ID)) $criteria->add(MenuitemVersionPeer::LOCATION_ID, $this->location_id);
		if ($this->isColumnModified(MenuitemVersionPeer::MENUITEM_ID)) $criteria->add(MenuitemVersionPeer::MENUITEM_ID, $this->menuitem_id);
		if ($this->isColumnModified(MenuitemVersionPeer::USER_ID)) $criteria->add(MenuitemVersionPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(MenuitemVersionPeer::PRICE)) $criteria->add(MenuitemVersionPeer::PRICE, $this->price);
		if ($this->isColumnModified(MenuitemVersionPeer::CREATED_AT)) $criteria->add(MenuitemVersionPeer::CREATED_AT, $this->created_at);

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
		$criteria = new Criteria(MenuitemVersionPeer::DATABASE_NAME);

		$criteria->add(MenuitemVersionPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param int $key Primary key.
	 * @return void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param object $copyObj An object of MenuitemVersion (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setDescription($this->description);

		$copyObj->setHtmlDescription($this->html_description);

		$copyObj->setLocationId($this->location_id);

		$copyObj->setMenuitemId($this->menuitem_id);

		$copyObj->setUserId($this->user_id);

		$copyObj->setPrice($this->price);

		$copyObj->setCreatedAt($this->created_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getMenuItems() as $relObj) {
				$copyObj->addMenuItem($relObj->copy($deepCopy));
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a pkey column, so set to default value

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
	 * @return MenuitemVersion Clone of current object.
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
	 * @return MenuitemVersionPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new MenuitemVersionPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Location object.
	 *
	 * @param Location $v
	 * @return void
	 * @throws PropelException
	 */
	public function setLocation($v)
	{


		if ($v === null) {
			$this->setLocationId(NULL);
		} else {
			$this->setLocationId($v->getId());
		}


		$this->aLocation = $v;
	}


	/**
	 * Get the associated Location object
	 *
	 * @param Connection Optional Connection object.
	 * @return Location The associated Location object.
	 * @throws PropelException
	 */
	public function getLocation($con = null)
	{
		// include the related Peer class
		include_once 'lib/model/om/BaseLocationPeer.php';

		if ($this->aLocation === null && ($this->location_id !== null)) {

			$this->aLocation = LocationPeer::retrieveByPK($this->location_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = LocationPeer::retrieveByPK($this->location_id, $con);
			   $obj->addLocations($this);
			 */
		}
		return $this->aLocation;
	}

	/**
	 * Declares an association between this object and a MenuItem object.
	 *
	 * @param MenuItem $v
	 * @return void
	 * @throws PropelException
	 */
	public function setMenuItem($v)
	{


		if ($v === null) {
			$this->setMenuitemId(NULL);
		} else {
			$this->setMenuitemId($v->getId());
		}


		$this->aMenuItem = $v;
	}


	/**
	 * Get the associated MenuItem object
	 *
	 * @param Connection Optional Connection object.
	 * @return MenuItem The associated MenuItem object.
	 * @throws PropelException
	 */
	public function getMenuItem($con = null)
	{
		// include the related Peer class
		include_once 'lib/model/om/BaseMenuItemPeer.php';

		if ($this->aMenuItem === null && ($this->menuitem_id !== null)) {

			$this->aMenuItem = MenuItemPeer::retrieveByPK($this->menuitem_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = MenuItemPeer::retrieveByPK($this->menuitem_id, $con);
			   $obj->addMenuItems($this);
			 */
		}
		return $this->aMenuItem;
	}

	/**
	 * Declares an association between this object and a User object.
	 *
	 * @param User $v
	 * @return void
	 * @throws PropelException
	 */
	public function setUser($v)
	{


		if ($v === null) {
			$this->setUserId(NULL);
		} else {
			$this->setUserId($v->getId());
		}


		$this->aUser = $v;
	}


	/**
	 * Get the associated User object
	 *
	 * @param Connection Optional Connection object.
	 * @return User The associated User object.
	 * @throws PropelException
	 */
	public function getUser($con = null)
	{
		// include the related Peer class
		include_once 'lib/model/om/BaseUserPeer.php';

		if ($this->aUser === null && ($this->user_id !== null)) {

			$this->aUser = UserPeer::retrieveByPK($this->user_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UserPeer::retrieveByPK($this->user_id, $con);
			   $obj->addUsers($this);
			 */
		}
		return $this->aUser;
	}

	/**
	 * Temporary storage of collMenuItems to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initMenuItems()
	{
		if ($this->collMenuItems === null) {
			$this->collMenuItems = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MenuitemVersion has previously
	 * been saved, it will retrieve related MenuItems from storage.
	 * If this MenuitemVersion is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getMenuItems($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuItems === null) {
			if ($this->isNew()) {
			   $this->collMenuItems = array();
			} else {

				$criteria->add(MenuItemPeer::VERSION_ID, $this->getId());

				MenuItemPeer::addSelectColumns($criteria);
				$this->collMenuItems = MenuItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuItemPeer::VERSION_ID, $this->getId());

				MenuItemPeer::addSelectColumns($criteria);
				if (!isset($this->lastMenuItemCriteria) || !$this->lastMenuItemCriteria->equals($criteria)) {
					$this->collMenuItems = MenuItemPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMenuItemCriteria = $criteria;
		return $this->collMenuItems;
	}

	/**
	 * Returns the number of related MenuItems.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countMenuItems($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MenuItemPeer::VERSION_ID, $this->getId());

		return MenuItemPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MenuItem object to this object
	 * through the MenuItem foreign key attribute
	 *
	 * @param MenuItem $l MenuItem
	 * @return void
	 * @throws PropelException
	 */
	public function addMenuItem(MenuItem $l)
	{
		$this->collMenuItems[] = $l;
		$l->setMenuitemVersion($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MenuitemVersion is new, it will return
	 * an empty collection; or if this MenuitemVersion has previously
	 * been saved, it will retrieve related MenuItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in MenuitemVersion.
	 */
	public function getMenuItemsJoinRestaurant($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuItems === null) {
			if ($this->isNew()) {
				$this->collMenuItems = array();
			} else {

				$criteria->add(MenuItemPeer::VERSION_ID, $this->getId());

				$this->collMenuItems = MenuItemPeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuItemPeer::VERSION_ID, $this->getId());

			if (!isset($this->lastMenuItemCriteria) || !$this->lastMenuItemCriteria->equals($criteria)) {
				$this->collMenuItems = MenuItemPeer::doSelectJoinRestaurant($criteria, $con);
			}
		}
		$this->lastMenuItemCriteria = $criteria;

		return $this->collMenuItems;
	}

} // BaseMenuitemVersion
