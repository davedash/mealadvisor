<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/model/UserPeer.php';

/**
 * Base class that represents a row from the 'user' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseUser extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var UserPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var int
	 */
	protected $id;


	/**
	 * The value for the userid field.
	 * @var string
	 */
	protected $userid;


	/**
	 * The value for the email field.
	 * @var string
	 */
	protected $email;


	/**
	 * The value for the password_md5 field.
	 * @var string
	 */
	protected $password_md5;


	/**
	 * The value for the open_id field.
	 * @var boolean
	 */
	protected $open_id;


	/**
	 * The value for the updated_at field.
	 * @var int
	 */
	protected $updated_at;


	/**
	 * The value for the created_at field.
	 * @var int
	 */
	protected $created_at;

	/**
	 * Collection to store aggregation of collRestaurantVersions.
	 * @var array
	 */
	protected $collRestaurantVersions;

	/**
	 * The criteria used to select the current contents of collRestaurantVersions.
	 * @var Criteria
	 */
	protected $lastRestaurantVersionCriteria = null;

	/**
	 * Collection to store aggregation of collMenuitemVersions.
	 * @var array
	 */
	protected $collMenuitemVersions;

	/**
	 * The criteria used to select the current contents of collMenuitemVersions.
	 * @var Criteria
	 */
	protected $lastMenuitemVersionCriteria = null;

	/**
	 * Collection to store aggregation of collRestaurantNotes.
	 * @var array
	 */
	protected $collRestaurantNotes;

	/**
	 * The criteria used to select the current contents of collRestaurantNotes.
	 * @var Criteria
	 */
	protected $lastRestaurantNoteCriteria = null;

	/**
	 * Collection to store aggregation of collMenuItemNotes.
	 * @var array
	 */
	protected $collMenuItemNotes;

	/**
	 * The criteria used to select the current contents of collMenuItemNotes.
	 * @var Criteria
	 */
	protected $lastMenuItemNoteCriteria = null;

	/**
	 * Collection to store aggregation of collRestaurantRatings.
	 * @var array
	 */
	protected $collRestaurantRatings;

	/**
	 * The criteria used to select the current contents of collRestaurantRatings.
	 * @var Criteria
	 */
	protected $lastRestaurantRatingCriteria = null;

	/**
	 * Collection to store aggregation of collMenuItemRatings.
	 * @var array
	 */
	protected $collMenuItemRatings;

	/**
	 * The criteria used to select the current contents of collMenuItemRatings.
	 * @var Criteria
	 */
	protected $lastMenuItemRatingCriteria = null;

	/**
	 * Collection to store aggregation of collMenuitemTags.
	 * @var array
	 */
	protected $collMenuitemTags;

	/**
	 * The criteria used to select the current contents of collMenuitemTags.
	 * @var Criteria
	 */
	protected $lastMenuitemTagCriteria = null;

	/**
	 * Collection to store aggregation of collMenuItemImages.
	 * @var array
	 */
	protected $collMenuItemImages;

	/**
	 * The criteria used to select the current contents of collMenuItemImages.
	 * @var Criteria
	 */
	protected $lastMenuItemImageCriteria = null;

	/**
	 * Collection to store aggregation of collRestaurantTags.
	 * @var array
	 */
	protected $collRestaurantTags;

	/**
	 * The criteria used to select the current contents of collRestaurantTags.
	 * @var Criteria
	 */
	protected $lastRestaurantTagCriteria = null;

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
	 * Get the [userid] column value.
	 * 
	 * @return string
	 */
	public function getUserid()
	{

		return $this->userid;
	}

	/**
	 * Get the [email] column value.
	 * 
	 * @return string
	 */
	public function getEmail()
	{

		return $this->email;
	}

	/**
	 * Get the [password_md5] column value.
	 * 
	 * @return string
	 */
	public function getPasswordMd5()
	{

		return $this->password_md5;
	}

	/**
	 * Get the [open_id] column value.
	 * 
	 * @return boolean
	 */
	public function getOpenId()
	{

		return $this->open_id;
	}

	/**
	 * Get the [optionally formatted] [updated_at] column value.
	 * 
	 * @param string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->updated_at === null || $this->updated_at === '') {
			return null;
		} elseif (!is_int($this->updated_at)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->updated_at);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [updated_at] as date/time value: " . var_export($this->updated_at, true));
			}
		} else {
			$ts = $this->updated_at;
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
			$this->modifiedColumns[] = UserPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [userid] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setUserid($v)
	{

		if ($this->userid !== $v) {
			$this->userid = $v;
			$this->modifiedColumns[] = UserPeer::USERID;
		}

	} // setUserid()

	/**
	 * Set the value of [email] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setEmail($v)
	{

		if ($this->email !== $v) {
			$this->email = $v;
			$this->modifiedColumns[] = UserPeer::EMAIL;
		}

	} // setEmail()

	/**
	 * Set the value of [password_md5] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setPasswordMd5($v)
	{

		if ($this->password_md5 !== $v) {
			$this->password_md5 = $v;
			$this->modifiedColumns[] = UserPeer::PASSWORD_MD5;
		}

	} // setPasswordMd5()

	/**
	 * Set the value of [open_id] column.
	 * 
	 * @param boolean $v new value
	 * @return void
	 */
	public function setOpenId($v)
	{

		if ($this->open_id !== $v) {
			$this->open_id = $v;
			$this->modifiedColumns[] = UserPeer::OPEN_ID;
		}

	} // setOpenId()

	/**
	 * Set the value of [updated_at] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setUpdatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [updated_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->updated_at !== $ts) {
			$this->updated_at = $ts;
			$this->modifiedColumns[] = UserPeer::UPDATED_AT;
		}

	} // setUpdatedAt()

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
			$this->modifiedColumns[] = UserPeer::CREATED_AT;
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

			$this->userid = $rs->getString($startcol + 1);

			$this->email = $rs->getString($startcol + 2);

			$this->password_md5 = $rs->getString($startcol + 3);

			$this->open_id = $rs->getBoolean($startcol + 4);

			$this->updated_at = $rs->getTimestamp($startcol + 5, null);

			$this->created_at = $rs->getTimestamp($startcol + 6, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 7; // 7 = UserPeer::NUM_COLUMNS - UserPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating User object", $e);
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
			$con = Propel::getConnection(UserPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UserPeer::doDelete($this, $con);
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
    if ($this->isModified() && !$this->isColumnModified('updated_at'))
    {
      $this->setUpdatedAt(time());
    }

    if ($this->isNew() && !$this->isColumnModified('created_at'))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UserPeer::DATABASE_NAME);
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
					$pk = UserPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UserPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collRestaurantVersions !== null) {
				foreach($this->collRestaurantVersions as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMenuitemVersions !== null) {
				foreach($this->collMenuitemVersions as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collRestaurantNotes !== null) {
				foreach($this->collRestaurantNotes as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMenuItemNotes !== null) {
				foreach($this->collMenuItemNotes as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collRestaurantRatings !== null) {
				foreach($this->collRestaurantRatings as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMenuItemRatings !== null) {
				foreach($this->collMenuItemRatings as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMenuitemTags !== null) {
				foreach($this->collMenuitemTags as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMenuItemImages !== null) {
				foreach($this->collMenuItemImages as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collRestaurantTags !== null) {
				foreach($this->collRestaurantTags as $referrerFK) {
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


			if (($retval = UserPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collRestaurantVersions !== null) {
					foreach($this->collRestaurantVersions as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMenuitemVersions !== null) {
					foreach($this->collMenuitemVersions as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collRestaurantNotes !== null) {
					foreach($this->collRestaurantNotes as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMenuItemNotes !== null) {
					foreach($this->collMenuItemNotes as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collRestaurantRatings !== null) {
					foreach($this->collRestaurantRatings as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMenuItemRatings !== null) {
					foreach($this->collMenuItemRatings as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMenuitemTags !== null) {
					foreach($this->collMenuitemTags as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMenuItemImages !== null) {
					foreach($this->collMenuItemImages as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collRestaurantTags !== null) {
					foreach($this->collRestaurantTags as $referrerFK) {
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
		$pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getUserid();
				break;
			case 2:
				return $this->getEmail();
				break;
			case 3:
				return $this->getPasswordMd5();
				break;
			case 4:
				return $this->getOpenId();
				break;
			case 5:
				return $this->getUpdatedAt();
				break;
			case 6:
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
		$keys = UserPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUserid(),
			$keys[2] => $this->getEmail(),
			$keys[3] => $this->getPasswordMd5(),
			$keys[4] => $this->getOpenId(),
			$keys[5] => $this->getUpdatedAt(),
			$keys[6] => $this->getCreatedAt(),
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
		$pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setUserid($value);
				break;
			case 2:
				$this->setEmail($value);
				break;
			case 3:
				$this->setPasswordMd5($value);
				break;
			case 4:
				$this->setOpenId($value);
				break;
			case 5:
				$this->setUpdatedAt($value);
				break;
			case 6:
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
		$keys = UserPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUserid($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setEmail($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPasswordMd5($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setOpenId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setUpdatedAt($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCreatedAt($arr[$keys[6]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UserPeer::DATABASE_NAME);

		if ($this->isColumnModified(UserPeer::ID)) $criteria->add(UserPeer::ID, $this->id);
		if ($this->isColumnModified(UserPeer::USERID)) $criteria->add(UserPeer::USERID, $this->userid);
		if ($this->isColumnModified(UserPeer::EMAIL)) $criteria->add(UserPeer::EMAIL, $this->email);
		if ($this->isColumnModified(UserPeer::PASSWORD_MD5)) $criteria->add(UserPeer::PASSWORD_MD5, $this->password_md5);
		if ($this->isColumnModified(UserPeer::OPEN_ID)) $criteria->add(UserPeer::OPEN_ID, $this->open_id);
		if ($this->isColumnModified(UserPeer::UPDATED_AT)) $criteria->add(UserPeer::UPDATED_AT, $this->updated_at);
		if ($this->isColumnModified(UserPeer::CREATED_AT)) $criteria->add(UserPeer::CREATED_AT, $this->created_at);

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
		$criteria = new Criteria(UserPeer::DATABASE_NAME);

		$criteria->add(UserPeer::ID, $this->id);

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
	 * @param object $copyObj An object of User (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUserid($this->userid);

		$copyObj->setEmail($this->email);

		$copyObj->setPasswordMd5($this->password_md5);

		$copyObj->setOpenId($this->open_id);

		$copyObj->setUpdatedAt($this->updated_at);

		$copyObj->setCreatedAt($this->created_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getRestaurantVersions() as $relObj) {
				$copyObj->addRestaurantVersion($relObj->copy($deepCopy));
			}

			foreach($this->getMenuitemVersions() as $relObj) {
				$copyObj->addMenuitemVersion($relObj->copy($deepCopy));
			}

			foreach($this->getRestaurantNotes() as $relObj) {
				$copyObj->addRestaurantNote($relObj->copy($deepCopy));
			}

			foreach($this->getMenuItemNotes() as $relObj) {
				$copyObj->addMenuItemNote($relObj->copy($deepCopy));
			}

			foreach($this->getRestaurantRatings() as $relObj) {
				$copyObj->addRestaurantRating($relObj->copy($deepCopy));
			}

			foreach($this->getMenuItemRatings() as $relObj) {
				$copyObj->addMenuItemRating($relObj->copy($deepCopy));
			}

			foreach($this->getMenuitemTags() as $relObj) {
				$copyObj->addMenuitemTag($relObj->copy($deepCopy));
			}

			foreach($this->getMenuItemImages() as $relObj) {
				$copyObj->addMenuItemImage($relObj->copy($deepCopy));
			}

			foreach($this->getRestaurantTags() as $relObj) {
				$copyObj->addRestaurantTag($relObj->copy($deepCopy));
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
	 * @return User Clone of current object.
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
	 * @return UserPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UserPeer();
		}
		return self::$peer;
	}

	/**
	 * Temporary storage of collRestaurantVersions to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initRestaurantVersions()
	{
		if ($this->collRestaurantVersions === null) {
			$this->collRestaurantVersions = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related RestaurantVersions from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getRestaurantVersions($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantVersionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantVersions === null) {
			if ($this->isNew()) {
			   $this->collRestaurantVersions = array();
			} else {

				$criteria->add(RestaurantVersionPeer::USER_ID, $this->getId());

				RestaurantVersionPeer::addSelectColumns($criteria);
				$this->collRestaurantVersions = RestaurantVersionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantVersionPeer::USER_ID, $this->getId());

				RestaurantVersionPeer::addSelectColumns($criteria);
				if (!isset($this->lastRestaurantVersionCriteria) || !$this->lastRestaurantVersionCriteria->equals($criteria)) {
					$this->collRestaurantVersions = RestaurantVersionPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastRestaurantVersionCriteria = $criteria;
		return $this->collRestaurantVersions;
	}

	/**
	 * Returns the number of related RestaurantVersions.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countRestaurantVersions($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantVersionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(RestaurantVersionPeer::USER_ID, $this->getId());

		return RestaurantVersionPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a RestaurantVersion object to this object
	 * through the RestaurantVersion foreign key attribute
	 *
	 * @param RestaurantVersion $l RestaurantVersion
	 * @return void
	 * @throws PropelException
	 */
	public function addRestaurantVersion(RestaurantVersion $l)
	{
		$this->collRestaurantVersions[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RestaurantVersions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getRestaurantVersionsJoinRestaurant($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantVersionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantVersions === null) {
			if ($this->isNew()) {
				$this->collRestaurantVersions = array();
			} else {

				$criteria->add(RestaurantVersionPeer::USER_ID, $this->getId());

				$this->collRestaurantVersions = RestaurantVersionPeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantVersionPeer::USER_ID, $this->getId());

			if (!isset($this->lastRestaurantVersionCriteria) || !$this->lastRestaurantVersionCriteria->equals($criteria)) {
				$this->collRestaurantVersions = RestaurantVersionPeer::doSelectJoinRestaurant($criteria, $con);
			}
		}
		$this->lastRestaurantVersionCriteria = $criteria;

		return $this->collRestaurantVersions;
	}

	/**
	 * Temporary storage of collMenuitemVersions to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initMenuitemVersions()
	{
		if ($this->collMenuitemVersions === null) {
			$this->collMenuitemVersions = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getMenuitemVersions($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemVersionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuitemVersions === null) {
			if ($this->isNew()) {
			   $this->collMenuitemVersions = array();
			} else {

				$criteria->add(MenuitemVersionPeer::USER_ID, $this->getId());

				MenuitemVersionPeer::addSelectColumns($criteria);
				$this->collMenuitemVersions = MenuitemVersionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuitemVersionPeer::USER_ID, $this->getId());

				MenuitemVersionPeer::addSelectColumns($criteria);
				if (!isset($this->lastMenuitemVersionCriteria) || !$this->lastMenuitemVersionCriteria->equals($criteria)) {
					$this->collMenuitemVersions = MenuitemVersionPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMenuitemVersionCriteria = $criteria;
		return $this->collMenuitemVersions;
	}

	/**
	 * Returns the number of related MenuitemVersions.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countMenuitemVersions($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemVersionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MenuitemVersionPeer::USER_ID, $this->getId());

		return MenuitemVersionPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MenuitemVersion object to this object
	 * through the MenuitemVersion foreign key attribute
	 *
	 * @param MenuitemVersion $l MenuitemVersion
	 * @return void
	 * @throws PropelException
	 */
	public function addMenuitemVersion(MenuitemVersion $l)
	{
		$this->collMenuitemVersions[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getMenuitemVersionsJoinLocation($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemVersionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuitemVersions === null) {
			if ($this->isNew()) {
				$this->collMenuitemVersions = array();
			} else {

				$criteria->add(MenuitemVersionPeer::USER_ID, $this->getId());

				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinLocation($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuitemVersionPeer::USER_ID, $this->getId());

			if (!isset($this->lastMenuitemVersionCriteria) || !$this->lastMenuitemVersionCriteria->equals($criteria)) {
				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinLocation($criteria, $con);
			}
		}
		$this->lastMenuitemVersionCriteria = $criteria;

		return $this->collMenuitemVersions;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getMenuitemVersionsJoinMenuItem($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemVersionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuitemVersions === null) {
			if ($this->isNew()) {
				$this->collMenuitemVersions = array();
			} else {

				$criteria->add(MenuitemVersionPeer::USER_ID, $this->getId());

				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinMenuItem($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuitemVersionPeer::USER_ID, $this->getId());

			if (!isset($this->lastMenuitemVersionCriteria) || !$this->lastMenuitemVersionCriteria->equals($criteria)) {
				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinMenuItem($criteria, $con);
			}
		}
		$this->lastMenuitemVersionCriteria = $criteria;

		return $this->collMenuitemVersions;
	}

	/**
	 * Temporary storage of collRestaurantNotes to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initRestaurantNotes()
	{
		if ($this->collRestaurantNotes === null) {
			$this->collRestaurantNotes = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getRestaurantNotes($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantNotePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantNotes === null) {
			if ($this->isNew()) {
			   $this->collRestaurantNotes = array();
			} else {

				$criteria->add(RestaurantNotePeer::USER_ID, $this->getId());

				RestaurantNotePeer::addSelectColumns($criteria);
				$this->collRestaurantNotes = RestaurantNotePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantNotePeer::USER_ID, $this->getId());

				RestaurantNotePeer::addSelectColumns($criteria);
				if (!isset($this->lastRestaurantNoteCriteria) || !$this->lastRestaurantNoteCriteria->equals($criteria)) {
					$this->collRestaurantNotes = RestaurantNotePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastRestaurantNoteCriteria = $criteria;
		return $this->collRestaurantNotes;
	}

	/**
	 * Returns the number of related RestaurantNotes.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countRestaurantNotes($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantNotePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(RestaurantNotePeer::USER_ID, $this->getId());

		return RestaurantNotePeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a RestaurantNote object to this object
	 * through the RestaurantNote foreign key attribute
	 *
	 * @param RestaurantNote $l RestaurantNote
	 * @return void
	 * @throws PropelException
	 */
	public function addRestaurantNote(RestaurantNote $l)
	{
		$this->collRestaurantNotes[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getRestaurantNotesJoinRestaurant($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantNotePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantNotes === null) {
			if ($this->isNew()) {
				$this->collRestaurantNotes = array();
			} else {

				$criteria->add(RestaurantNotePeer::USER_ID, $this->getId());

				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantNotePeer::USER_ID, $this->getId());

			if (!isset($this->lastRestaurantNoteCriteria) || !$this->lastRestaurantNoteCriteria->equals($criteria)) {
				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinRestaurant($criteria, $con);
			}
		}
		$this->lastRestaurantNoteCriteria = $criteria;

		return $this->collRestaurantNotes;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getRestaurantNotesJoinLocation($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantNotePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantNotes === null) {
			if ($this->isNew()) {
				$this->collRestaurantNotes = array();
			} else {

				$criteria->add(RestaurantNotePeer::USER_ID, $this->getId());

				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinLocation($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantNotePeer::USER_ID, $this->getId());

			if (!isset($this->lastRestaurantNoteCriteria) || !$this->lastRestaurantNoteCriteria->equals($criteria)) {
				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinLocation($criteria, $con);
			}
		}
		$this->lastRestaurantNoteCriteria = $criteria;

		return $this->collRestaurantNotes;
	}

	/**
	 * Temporary storage of collMenuItemNotes to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initMenuItemNotes()
	{
		if ($this->collMenuItemNotes === null) {
			$this->collMenuItemNotes = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related MenuItemNotes from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getMenuItemNotes($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemNotePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuItemNotes === null) {
			if ($this->isNew()) {
			   $this->collMenuItemNotes = array();
			} else {

				$criteria->add(MenuItemNotePeer::USER_ID, $this->getId());

				MenuItemNotePeer::addSelectColumns($criteria);
				$this->collMenuItemNotes = MenuItemNotePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuItemNotePeer::USER_ID, $this->getId());

				MenuItemNotePeer::addSelectColumns($criteria);
				if (!isset($this->lastMenuItemNoteCriteria) || !$this->lastMenuItemNoteCriteria->equals($criteria)) {
					$this->collMenuItemNotes = MenuItemNotePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMenuItemNoteCriteria = $criteria;
		return $this->collMenuItemNotes;
	}

	/**
	 * Returns the number of related MenuItemNotes.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countMenuItemNotes($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemNotePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MenuItemNotePeer::USER_ID, $this->getId());

		return MenuItemNotePeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MenuItemNote object to this object
	 * through the MenuItemNote foreign key attribute
	 *
	 * @param MenuItemNote $l MenuItemNote
	 * @return void
	 * @throws PropelException
	 */
	public function addMenuItemNote(MenuItemNote $l)
	{
		$this->collMenuItemNotes[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related MenuItemNotes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getMenuItemNotesJoinMenuItem($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemNotePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuItemNotes === null) {
			if ($this->isNew()) {
				$this->collMenuItemNotes = array();
			} else {

				$criteria->add(MenuItemNotePeer::USER_ID, $this->getId());

				$this->collMenuItemNotes = MenuItemNotePeer::doSelectJoinMenuItem($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuItemNotePeer::USER_ID, $this->getId());

			if (!isset($this->lastMenuItemNoteCriteria) || !$this->lastMenuItemNoteCriteria->equals($criteria)) {
				$this->collMenuItemNotes = MenuItemNotePeer::doSelectJoinMenuItem($criteria, $con);
			}
		}
		$this->lastMenuItemNoteCriteria = $criteria;

		return $this->collMenuItemNotes;
	}

	/**
	 * Temporary storage of collRestaurantRatings to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initRestaurantRatings()
	{
		if ($this->collRestaurantRatings === null) {
			$this->collRestaurantRatings = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getRestaurantRatings($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantRatingPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantRatings === null) {
			if ($this->isNew()) {
			   $this->collRestaurantRatings = array();
			} else {

				$criteria->add(RestaurantRatingPeer::USER_ID, $this->getId());

				RestaurantRatingPeer::addSelectColumns($criteria);
				$this->collRestaurantRatings = RestaurantRatingPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantRatingPeer::USER_ID, $this->getId());

				RestaurantRatingPeer::addSelectColumns($criteria);
				if (!isset($this->lastRestaurantRatingCriteria) || !$this->lastRestaurantRatingCriteria->equals($criteria)) {
					$this->collRestaurantRatings = RestaurantRatingPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastRestaurantRatingCriteria = $criteria;
		return $this->collRestaurantRatings;
	}

	/**
	 * Returns the number of related RestaurantRatings.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countRestaurantRatings($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantRatingPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(RestaurantRatingPeer::USER_ID, $this->getId());

		return RestaurantRatingPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a RestaurantRating object to this object
	 * through the RestaurantRating foreign key attribute
	 *
	 * @param RestaurantRating $l RestaurantRating
	 * @return void
	 * @throws PropelException
	 */
	public function addRestaurantRating(RestaurantRating $l)
	{
		$this->collRestaurantRatings[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getRestaurantRatingsJoinRestaurant($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantRatingPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantRatings === null) {
			if ($this->isNew()) {
				$this->collRestaurantRatings = array();
			} else {

				$criteria->add(RestaurantRatingPeer::USER_ID, $this->getId());

				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantRatingPeer::USER_ID, $this->getId());

			if (!isset($this->lastRestaurantRatingCriteria) || !$this->lastRestaurantRatingCriteria->equals($criteria)) {
				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinRestaurant($criteria, $con);
			}
		}
		$this->lastRestaurantRatingCriteria = $criteria;

		return $this->collRestaurantRatings;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getRestaurantRatingsJoinLocation($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantRatingPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantRatings === null) {
			if ($this->isNew()) {
				$this->collRestaurantRatings = array();
			} else {

				$criteria->add(RestaurantRatingPeer::USER_ID, $this->getId());

				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinLocation($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantRatingPeer::USER_ID, $this->getId());

			if (!isset($this->lastRestaurantRatingCriteria) || !$this->lastRestaurantRatingCriteria->equals($criteria)) {
				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinLocation($criteria, $con);
			}
		}
		$this->lastRestaurantRatingCriteria = $criteria;

		return $this->collRestaurantRatings;
	}

	/**
	 * Temporary storage of collMenuItemRatings to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initMenuItemRatings()
	{
		if ($this->collMenuItemRatings === null) {
			$this->collMenuItemRatings = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related MenuItemRatings from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getMenuItemRatings($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemRatingPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuItemRatings === null) {
			if ($this->isNew()) {
			   $this->collMenuItemRatings = array();
			} else {

				$criteria->add(MenuItemRatingPeer::USER_ID, $this->getId());

				MenuItemRatingPeer::addSelectColumns($criteria);
				$this->collMenuItemRatings = MenuItemRatingPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuItemRatingPeer::USER_ID, $this->getId());

				MenuItemRatingPeer::addSelectColumns($criteria);
				if (!isset($this->lastMenuItemRatingCriteria) || !$this->lastMenuItemRatingCriteria->equals($criteria)) {
					$this->collMenuItemRatings = MenuItemRatingPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMenuItemRatingCriteria = $criteria;
		return $this->collMenuItemRatings;
	}

	/**
	 * Returns the number of related MenuItemRatings.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countMenuItemRatings($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemRatingPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MenuItemRatingPeer::USER_ID, $this->getId());

		return MenuItemRatingPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MenuItemRating object to this object
	 * through the MenuItemRating foreign key attribute
	 *
	 * @param MenuItemRating $l MenuItemRating
	 * @return void
	 * @throws PropelException
	 */
	public function addMenuItemRating(MenuItemRating $l)
	{
		$this->collMenuItemRatings[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related MenuItemRatings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getMenuItemRatingsJoinMenuItem($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemRatingPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuItemRatings === null) {
			if ($this->isNew()) {
				$this->collMenuItemRatings = array();
			} else {

				$criteria->add(MenuItemRatingPeer::USER_ID, $this->getId());

				$this->collMenuItemRatings = MenuItemRatingPeer::doSelectJoinMenuItem($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuItemRatingPeer::USER_ID, $this->getId());

			if (!isset($this->lastMenuItemRatingCriteria) || !$this->lastMenuItemRatingCriteria->equals($criteria)) {
				$this->collMenuItemRatings = MenuItemRatingPeer::doSelectJoinMenuItem($criteria, $con);
			}
		}
		$this->lastMenuItemRatingCriteria = $criteria;

		return $this->collMenuItemRatings;
	}

	/**
	 * Temporary storage of collMenuitemTags to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initMenuitemTags()
	{
		if ($this->collMenuitemTags === null) {
			$this->collMenuitemTags = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related MenuitemTags from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getMenuitemTags($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemTagPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuitemTags === null) {
			if ($this->isNew()) {
			   $this->collMenuitemTags = array();
			} else {

				$criteria->add(MenuitemTagPeer::USER_ID, $this->getId());

				MenuitemTagPeer::addSelectColumns($criteria);
				$this->collMenuitemTags = MenuitemTagPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuitemTagPeer::USER_ID, $this->getId());

				MenuitemTagPeer::addSelectColumns($criteria);
				if (!isset($this->lastMenuitemTagCriteria) || !$this->lastMenuitemTagCriteria->equals($criteria)) {
					$this->collMenuitemTags = MenuitemTagPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMenuitemTagCriteria = $criteria;
		return $this->collMenuitemTags;
	}

	/**
	 * Returns the number of related MenuitemTags.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countMenuitemTags($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemTagPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MenuitemTagPeer::USER_ID, $this->getId());

		return MenuitemTagPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MenuitemTag object to this object
	 * through the MenuitemTag foreign key attribute
	 *
	 * @param MenuitemTag $l MenuitemTag
	 * @return void
	 * @throws PropelException
	 */
	public function addMenuitemTag(MenuitemTag $l)
	{
		$this->collMenuitemTags[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related MenuitemTags from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getMenuitemTagsJoinMenuItem($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemTagPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuitemTags === null) {
			if ($this->isNew()) {
				$this->collMenuitemTags = array();
			} else {

				$criteria->add(MenuitemTagPeer::USER_ID, $this->getId());

				$this->collMenuitemTags = MenuitemTagPeer::doSelectJoinMenuItem($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuitemTagPeer::USER_ID, $this->getId());

			if (!isset($this->lastMenuitemTagCriteria) || !$this->lastMenuitemTagCriteria->equals($criteria)) {
				$this->collMenuitemTags = MenuitemTagPeer::doSelectJoinMenuItem($criteria, $con);
			}
		}
		$this->lastMenuitemTagCriteria = $criteria;

		return $this->collMenuitemTags;
	}

	/**
	 * Temporary storage of collMenuItemImages to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initMenuItemImages()
	{
		if ($this->collMenuItemImages === null) {
			$this->collMenuItemImages = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related MenuItemImages from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getMenuItemImages($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemImagePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuItemImages === null) {
			if ($this->isNew()) {
			   $this->collMenuItemImages = array();
			} else {

				$criteria->add(MenuItemImagePeer::USER_ID, $this->getId());

				MenuItemImagePeer::addSelectColumns($criteria);
				$this->collMenuItemImages = MenuItemImagePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuItemImagePeer::USER_ID, $this->getId());

				MenuItemImagePeer::addSelectColumns($criteria);
				if (!isset($this->lastMenuItemImageCriteria) || !$this->lastMenuItemImageCriteria->equals($criteria)) {
					$this->collMenuItemImages = MenuItemImagePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMenuItemImageCriteria = $criteria;
		return $this->collMenuItemImages;
	}

	/**
	 * Returns the number of related MenuItemImages.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countMenuItemImages($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemImagePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MenuItemImagePeer::USER_ID, $this->getId());

		return MenuItemImagePeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MenuItemImage object to this object
	 * through the MenuItemImage foreign key attribute
	 *
	 * @param MenuItemImage $l MenuItemImage
	 * @return void
	 * @throws PropelException
	 */
	public function addMenuItemImage(MenuItemImage $l)
	{
		$this->collMenuItemImages[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related MenuItemImages from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getMenuItemImagesJoinMenuItem($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuItemImagePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuItemImages === null) {
			if ($this->isNew()) {
				$this->collMenuItemImages = array();
			} else {

				$criteria->add(MenuItemImagePeer::USER_ID, $this->getId());

				$this->collMenuItemImages = MenuItemImagePeer::doSelectJoinMenuItem($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuItemImagePeer::USER_ID, $this->getId());

			if (!isset($this->lastMenuItemImageCriteria) || !$this->lastMenuItemImageCriteria->equals($criteria)) {
				$this->collMenuItemImages = MenuItemImagePeer::doSelectJoinMenuItem($criteria, $con);
			}
		}
		$this->lastMenuItemImageCriteria = $criteria;

		return $this->collMenuItemImages;
	}

	/**
	 * Temporary storage of collRestaurantTags to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initRestaurantTags()
	{
		if ($this->collRestaurantTags === null) {
			$this->collRestaurantTags = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User has previously
	 * been saved, it will retrieve related RestaurantTags from storage.
	 * If this User is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getRestaurantTags($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantTagPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantTags === null) {
			if ($this->isNew()) {
			   $this->collRestaurantTags = array();
			} else {

				$criteria->add(RestaurantTagPeer::USER_ID, $this->getId());

				RestaurantTagPeer::addSelectColumns($criteria);
				$this->collRestaurantTags = RestaurantTagPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantTagPeer::USER_ID, $this->getId());

				RestaurantTagPeer::addSelectColumns($criteria);
				if (!isset($this->lastRestaurantTagCriteria) || !$this->lastRestaurantTagCriteria->equals($criteria)) {
					$this->collRestaurantTags = RestaurantTagPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastRestaurantTagCriteria = $criteria;
		return $this->collRestaurantTags;
	}

	/**
	 * Returns the number of related RestaurantTags.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countRestaurantTags($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantTagPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(RestaurantTagPeer::USER_ID, $this->getId());

		return RestaurantTagPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a RestaurantTag object to this object
	 * through the RestaurantTag foreign key attribute
	 *
	 * @param RestaurantTag $l RestaurantTag
	 * @return void
	 * @throws PropelException
	 */
	public function addRestaurantTag(RestaurantTag $l)
	{
		$this->collRestaurantTags[] = $l;
		$l->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RestaurantTags from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getRestaurantTagsJoinRestaurant($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantTagPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantTags === null) {
			if ($this->isNew()) {
				$this->collRestaurantTags = array();
			} else {

				$criteria->add(RestaurantTagPeer::USER_ID, $this->getId());

				$this->collRestaurantTags = RestaurantTagPeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantTagPeer::USER_ID, $this->getId());

			if (!isset($this->lastRestaurantTagCriteria) || !$this->lastRestaurantTagCriteria->equals($criteria)) {
				$this->collRestaurantTags = RestaurantTagPeer::doSelectJoinRestaurant($criteria, $con);
			}
		}
		$this->lastRestaurantTagCriteria = $criteria;

		return $this->collRestaurantTags;
	}

} // BaseUser
