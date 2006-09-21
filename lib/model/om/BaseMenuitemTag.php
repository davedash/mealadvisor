<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/model/MenuitemTagPeer.php';

/**
 * Base class that represents a row from the 'menuitem_tag' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseMenuitemTag extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var MenuitemTagPeer
	 */
	protected static $peer;


	/**
	 * The value for the menu_item_id field.
	 * @var int
	 */
	protected $menu_item_id;


	/**
	 * The value for the user_id field.
	 * @var int
	 */
	protected $user_id;


	/**
	 * The value for the tag field.
	 * @var string
	 */
	protected $tag;


	/**
	 * The value for the normalized_tag field.
	 * @var string
	 */
	protected $normalized_tag;


	/**
	 * The value for the created_at field.
	 * @var int
	 */
	protected $created_at;

	/**
	 * @var MenuItem
	 */
	protected $aMenuItem;

	/**
	 * @var User
	 */
	protected $aUser;

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
	 * Get the [menu_item_id] column value.
	 * 
	 * @return int
	 */
	public function getMenuItemId()
	{

		return $this->menu_item_id;
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
	 * Get the [tag] column value.
	 * 
	 * @return string
	 */
	public function getTag()
	{

		return $this->tag;
	}

	/**
	 * Get the [normalized_tag] column value.
	 * 
	 * @return string
	 */
	public function getNormalizedTag()
	{

		return $this->normalized_tag;
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
	 * Set the value of [menu_item_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setMenuItemId($v)
	{

		if ($this->menu_item_id !== $v) {
			$this->menu_item_id = $v;
			$this->modifiedColumns[] = MenuitemTagPeer::MENU_ITEM_ID;
		}

		if ($this->aMenuItem !== null && $this->aMenuItem->getId() !== $v) {
			$this->aMenuItem = null;
		}

	} // setMenuItemId()

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
			$this->modifiedColumns[] = MenuitemTagPeer::USER_ID;
		}

		if ($this->aUser !== null && $this->aUser->getId() !== $v) {
			$this->aUser = null;
		}

	} // setUserId()

	/**
	 * Set the value of [tag] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setTag($v)
	{

		if ($this->tag !== $v) {
			$this->tag = $v;
			$this->modifiedColumns[] = MenuitemTagPeer::TAG;
		}

	} // setTag()

	/**
	 * Set the value of [normalized_tag] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setNormalizedTag($v)
	{

		if ($this->normalized_tag !== $v) {
			$this->normalized_tag = $v;
			$this->modifiedColumns[] = MenuitemTagPeer::NORMALIZED_TAG;
		}

	} // setNormalizedTag()

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
			$this->modifiedColumns[] = MenuitemTagPeer::CREATED_AT;
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

			$this->menu_item_id = $rs->getInt($startcol + 0);

			$this->user_id = $rs->getInt($startcol + 1);

			$this->tag = $rs->getString($startcol + 2);

			$this->normalized_tag = $rs->getString($startcol + 3);

			$this->created_at = $rs->getTimestamp($startcol + 4, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = MenuitemTagPeer::NUM_COLUMNS - MenuitemTagPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating MenuitemTag object", $e);
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
			$con = Propel::getConnection(MenuitemTagPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			MenuitemTagPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(MenuitemTagPeer::DATABASE_NAME);
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
					$pk = MenuitemTagPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += MenuitemTagPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
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


			if (($retval = MenuitemTagPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
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
		$pos = MenuitemTagPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getMenuItemId();
				break;
			case 1:
				return $this->getUserId();
				break;
			case 2:
				return $this->getTag();
				break;
			case 3:
				return $this->getNormalizedTag();
				break;
			case 4:
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
		$keys = MenuitemTagPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getMenuItemId(),
			$keys[1] => $this->getUserId(),
			$keys[2] => $this->getTag(),
			$keys[3] => $this->getNormalizedTag(),
			$keys[4] => $this->getCreatedAt(),
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
		$pos = MenuitemTagPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setMenuItemId($value);
				break;
			case 1:
				$this->setUserId($value);
				break;
			case 2:
				$this->setTag($value);
				break;
			case 3:
				$this->setNormalizedTag($value);
				break;
			case 4:
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
		$keys = MenuitemTagPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setMenuItemId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setTag($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setNormalizedTag($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setCreatedAt($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(MenuitemTagPeer::DATABASE_NAME);

		if ($this->isColumnModified(MenuitemTagPeer::MENU_ITEM_ID)) $criteria->add(MenuitemTagPeer::MENU_ITEM_ID, $this->menu_item_id);
		if ($this->isColumnModified(MenuitemTagPeer::USER_ID)) $criteria->add(MenuitemTagPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(MenuitemTagPeer::TAG)) $criteria->add(MenuitemTagPeer::TAG, $this->tag);
		if ($this->isColumnModified(MenuitemTagPeer::NORMALIZED_TAG)) $criteria->add(MenuitemTagPeer::NORMALIZED_TAG, $this->normalized_tag);
		if ($this->isColumnModified(MenuitemTagPeer::CREATED_AT)) $criteria->add(MenuitemTagPeer::CREATED_AT, $this->created_at);

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
		$criteria = new Criteria(MenuitemTagPeer::DATABASE_NAME);


		return $criteria;
	}

	/**
	 * Returns NULL since this table doesn't have a primary key.
	 * This method exists only for BC and is deprecated!
	 * @return null
	 */
	public function getPrimaryKey()
	{
		return null;
	}

	/**
	 * Dummy primary key setter.
	 *
	 * This function only exists to preserve backwards compatibility.  It is no longer
	 * needed or required by the Persistent interface.  It will be removed in next BC-breaking
	 * release of Propel.
	 *
	 * @deprecated
	 */
	 public function setPrimaryKey($pk)
	 {
		 // do nothing, because this object doesn't have any primary keys
	 }

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param object $copyObj An object of MenuitemTag (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setMenuItemId($this->menu_item_id);

		$copyObj->setUserId($this->user_id);

		$copyObj->setTag($this->tag);

		$copyObj->setNormalizedTag($this->normalized_tag);

		$copyObj->setCreatedAt($this->created_at);


		$copyObj->setNew(true);

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
	 * @return MenuitemTag Clone of current object.
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
	 * @return MenuitemTagPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new MenuitemTagPeer();
		}
		return self::$peer;
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
			$this->setMenuItemId(NULL);
		} else {
			$this->setMenuItemId($v->getId());
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

		if ($this->aMenuItem === null && ($this->menu_item_id !== null)) {

			$this->aMenuItem = MenuItemPeer::retrieveByPK($this->menu_item_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = MenuItemPeer::retrieveByPK($this->menu_item_id, $con);
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

} // BaseMenuitemTag
