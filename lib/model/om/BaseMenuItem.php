<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/model/MenuItemPeer.php';

/**
 * Base class that represents a row from the 'menu_item' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseMenuItem extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var MenuItemPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var int
	 */
	protected $id;


	/**
	 * The value for the name field.
	 * @var string
	 */
	protected $name;


	/**
	 * The value for the url field.
	 * @var string
	 */
	protected $url;


	/**
	 * The value for the version_id field.
	 * @var int
	 */
	protected $version_id;


	/**
	 * The value for the restaurant_id field.
	 * @var int
	 */
	protected $restaurant_id;


	/**
	 * The value for the approved field.
	 * @var boolean
	 */
	protected $approved;


	/**
	 * The value for the average_rating field.
	 * @var double
	 */
	protected $average_rating;


	/**
	 * The value for the num_ratings field.
	 * @var int
	 */
	protected $num_ratings;


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
	 * @var MenuitemVersion
	 */
	protected $aMenuitemVersion;

	/**
	 * @var Restaurant
	 */
	protected $aRestaurant;

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
	 * Collection to store aggregation of collMenuitemSearchIndexs.
	 * @var array
	 */
	protected $collMenuitemSearchIndexs;

	/**
	 * The criteria used to select the current contents of collMenuitemSearchIndexs.
	 * @var Criteria
	 */
	protected $lastMenuitemSearchIndexCriteria = null;

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
	 * Get the [name] column value.
	 * 
	 * @return string
	 */
	public function getName()
	{

		return $this->name;
	}

	/**
	 * Get the [url] column value.
	 * 
	 * @return string
	 */
	public function getUrl()
	{

		return $this->url;
	}

	/**
	 * Get the [version_id] column value.
	 * 
	 * @return int
	 */
	public function getVersionId()
	{

		return $this->version_id;
	}

	/**
	 * Get the [restaurant_id] column value.
	 * 
	 * @return int
	 */
	public function getRestaurantId()
	{

		return $this->restaurant_id;
	}

	/**
	 * Get the [approved] column value.
	 * 
	 * @return boolean
	 */
	public function getApproved()
	{

		return $this->approved;
	}

	/**
	 * Get the [average_rating] column value.
	 * 
	 * @return double
	 */
	public function getAverageRating()
	{

		return $this->average_rating;
	}

	/**
	 * Get the [num_ratings] column value.
	 * 
	 * @return int
	 */
	public function getNumRatings()
	{

		return $this->num_ratings;
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
			$this->modifiedColumns[] = MenuItemPeer::ID;
		}

	} // setId()

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
			$this->modifiedColumns[] = MenuItemPeer::NAME;
		}

	} // setName()

	/**
	 * Set the value of [url] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setUrl($v)
	{

		if ($this->url !== $v) {
			$this->url = $v;
			$this->modifiedColumns[] = MenuItemPeer::URL;
		}

	} // setUrl()

	/**
	 * Set the value of [version_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setVersionId($v)
	{

		if ($this->version_id !== $v) {
			$this->version_id = $v;
			$this->modifiedColumns[] = MenuItemPeer::VERSION_ID;
		}

		if ($this->aMenuitemVersion !== null && $this->aMenuitemVersion->getId() !== $v) {
			$this->aMenuitemVersion = null;
		}

	} // setVersionId()

	/**
	 * Set the value of [restaurant_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setRestaurantId($v)
	{

		if ($this->restaurant_id !== $v) {
			$this->restaurant_id = $v;
			$this->modifiedColumns[] = MenuItemPeer::RESTAURANT_ID;
		}

		if ($this->aRestaurant !== null && $this->aRestaurant->getId() !== $v) {
			$this->aRestaurant = null;
		}

	} // setRestaurantId()

	/**
	 * Set the value of [approved] column.
	 * 
	 * @param boolean $v new value
	 * @return void
	 */
	public function setApproved($v)
	{

		if ($this->approved !== $v) {
			$this->approved = $v;
			$this->modifiedColumns[] = MenuItemPeer::APPROVED;
		}

	} // setApproved()

	/**
	 * Set the value of [average_rating] column.
	 * 
	 * @param double $v new value
	 * @return void
	 */
	public function setAverageRating($v)
	{

		if ($this->average_rating !== $v) {
			$this->average_rating = $v;
			$this->modifiedColumns[] = MenuItemPeer::AVERAGE_RATING;
		}

	} // setAverageRating()

	/**
	 * Set the value of [num_ratings] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setNumRatings($v)
	{

		if ($this->num_ratings !== $v) {
			$this->num_ratings = $v;
			$this->modifiedColumns[] = MenuItemPeer::NUM_RATINGS;
		}

	} // setNumRatings()

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
			$this->modifiedColumns[] = MenuItemPeer::UPDATED_AT;
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
			$this->modifiedColumns[] = MenuItemPeer::CREATED_AT;
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

			$this->name = $rs->getString($startcol + 1);

			$this->url = $rs->getString($startcol + 2);

			$this->version_id = $rs->getInt($startcol + 3);

			$this->restaurant_id = $rs->getInt($startcol + 4);

			$this->approved = $rs->getBoolean($startcol + 5);

			$this->average_rating = $rs->getFloat($startcol + 6);

			$this->num_ratings = $rs->getInt($startcol + 7);

			$this->updated_at = $rs->getTimestamp($startcol + 8, null);

			$this->created_at = $rs->getTimestamp($startcol + 9, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 10; // 10 = MenuItemPeer::NUM_COLUMNS - MenuItemPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating MenuItem object", $e);
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
			$con = Propel::getConnection(MenuItemPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			MenuItemPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(MenuItemPeer::DATABASE_NAME);
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

			if ($this->aMenuitemVersion !== null) {
				if ($this->aMenuitemVersion->isModified()) {
					$affectedRows += $this->aMenuitemVersion->save($con);
				}
				$this->setMenuitemVersion($this->aMenuitemVersion);
			}

			if ($this->aRestaurant !== null) {
				if ($this->aRestaurant->isModified()) {
					$affectedRows += $this->aRestaurant->save($con);
				}
				$this->setRestaurant($this->aRestaurant);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = MenuItemPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += MenuItemPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collMenuitemVersions !== null) {
				foreach($this->collMenuitemVersions as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMenuitemSearchIndexs !== null) {
				foreach($this->collMenuitemSearchIndexs as $referrerFK) {
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

			if ($this->aMenuitemVersion !== null) {
				if (!$this->aMenuitemVersion->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMenuitemVersion->getValidationFailures());
				}
			}

			if ($this->aRestaurant !== null) {
				if (!$this->aRestaurant->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aRestaurant->getValidationFailures());
				}
			}


			if (($retval = MenuItemPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collMenuitemVersions !== null) {
					foreach($this->collMenuitemVersions as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMenuitemSearchIndexs !== null) {
					foreach($this->collMenuitemSearchIndexs as $referrerFK) {
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
		$pos = MenuItemPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getName();
				break;
			case 2:
				return $this->getUrl();
				break;
			case 3:
				return $this->getVersionId();
				break;
			case 4:
				return $this->getRestaurantId();
				break;
			case 5:
				return $this->getApproved();
				break;
			case 6:
				return $this->getAverageRating();
				break;
			case 7:
				return $this->getNumRatings();
				break;
			case 8:
				return $this->getUpdatedAt();
				break;
			case 9:
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
		$keys = MenuItemPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getName(),
			$keys[2] => $this->getUrl(),
			$keys[3] => $this->getVersionId(),
			$keys[4] => $this->getRestaurantId(),
			$keys[5] => $this->getApproved(),
			$keys[6] => $this->getAverageRating(),
			$keys[7] => $this->getNumRatings(),
			$keys[8] => $this->getUpdatedAt(),
			$keys[9] => $this->getCreatedAt(),
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
		$pos = MenuItemPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setName($value);
				break;
			case 2:
				$this->setUrl($value);
				break;
			case 3:
				$this->setVersionId($value);
				break;
			case 4:
				$this->setRestaurantId($value);
				break;
			case 5:
				$this->setApproved($value);
				break;
			case 6:
				$this->setAverageRating($value);
				break;
			case 7:
				$this->setNumRatings($value);
				break;
			case 8:
				$this->setUpdatedAt($value);
				break;
			case 9:
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
		$keys = MenuItemPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUrl($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setVersionId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setRestaurantId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setApproved($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setAverageRating($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setNumRatings($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setUpdatedAt($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(MenuItemPeer::DATABASE_NAME);

		if ($this->isColumnModified(MenuItemPeer::ID)) $criteria->add(MenuItemPeer::ID, $this->id);
		if ($this->isColumnModified(MenuItemPeer::NAME)) $criteria->add(MenuItemPeer::NAME, $this->name);
		if ($this->isColumnModified(MenuItemPeer::URL)) $criteria->add(MenuItemPeer::URL, $this->url);
		if ($this->isColumnModified(MenuItemPeer::VERSION_ID)) $criteria->add(MenuItemPeer::VERSION_ID, $this->version_id);
		if ($this->isColumnModified(MenuItemPeer::RESTAURANT_ID)) $criteria->add(MenuItemPeer::RESTAURANT_ID, $this->restaurant_id);
		if ($this->isColumnModified(MenuItemPeer::APPROVED)) $criteria->add(MenuItemPeer::APPROVED, $this->approved);
		if ($this->isColumnModified(MenuItemPeer::AVERAGE_RATING)) $criteria->add(MenuItemPeer::AVERAGE_RATING, $this->average_rating);
		if ($this->isColumnModified(MenuItemPeer::NUM_RATINGS)) $criteria->add(MenuItemPeer::NUM_RATINGS, $this->num_ratings);
		if ($this->isColumnModified(MenuItemPeer::UPDATED_AT)) $criteria->add(MenuItemPeer::UPDATED_AT, $this->updated_at);
		if ($this->isColumnModified(MenuItemPeer::CREATED_AT)) $criteria->add(MenuItemPeer::CREATED_AT, $this->created_at);

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
		$criteria = new Criteria(MenuItemPeer::DATABASE_NAME);

		$criteria->add(MenuItemPeer::ID, $this->id);

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
	 * @param object $copyObj An object of MenuItem (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setName($this->name);

		$copyObj->setUrl($this->url);

		$copyObj->setVersionId($this->version_id);

		$copyObj->setRestaurantId($this->restaurant_id);

		$copyObj->setApproved($this->approved);

		$copyObj->setAverageRating($this->average_rating);

		$copyObj->setNumRatings($this->num_ratings);

		$copyObj->setUpdatedAt($this->updated_at);

		$copyObj->setCreatedAt($this->created_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getMenuitemVersions() as $relObj) {
				$copyObj->addMenuitemVersion($relObj->copy($deepCopy));
			}

			foreach($this->getMenuitemSearchIndexs() as $relObj) {
				$copyObj->addMenuitemSearchIndex($relObj->copy($deepCopy));
			}

			foreach($this->getMenuItemNotes() as $relObj) {
				$copyObj->addMenuItemNote($relObj->copy($deepCopy));
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
	 * @return MenuItem Clone of current object.
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
	 * @return MenuItemPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new MenuItemPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a MenuitemVersion object.
	 *
	 * @param MenuitemVersion $v
	 * @return void
	 * @throws PropelException
	 */
	public function setMenuitemVersion($v)
	{


		if ($v === null) {
			$this->setVersionId(NULL);
		} else {
			$this->setVersionId($v->getId());
		}


		$this->aMenuitemVersion = $v;
	}


	/**
	 * Get the associated MenuitemVersion object
	 *
	 * @param Connection Optional Connection object.
	 * @return MenuitemVersion The associated MenuitemVersion object.
	 * @throws PropelException
	 */
	public function getMenuitemVersion($con = null)
	{
		// include the related Peer class
		include_once 'lib/model/om/BaseMenuitemVersionPeer.php';

		if ($this->aMenuitemVersion === null && ($this->version_id !== null)) {

			$this->aMenuitemVersion = MenuitemVersionPeer::retrieveByPK($this->version_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = MenuitemVersionPeer::retrieveByPK($this->version_id, $con);
			   $obj->addMenuitemVersions($this);
			 */
		}
		return $this->aMenuitemVersion;
	}

	/**
	 * Declares an association between this object and a Restaurant object.
	 *
	 * @param Restaurant $v
	 * @return void
	 * @throws PropelException
	 */
	public function setRestaurant($v)
	{


		if ($v === null) {
			$this->setRestaurantId(NULL);
		} else {
			$this->setRestaurantId($v->getId());
		}


		$this->aRestaurant = $v;
	}


	/**
	 * Get the associated Restaurant object
	 *
	 * @param Connection Optional Connection object.
	 * @return Restaurant The associated Restaurant object.
	 * @throws PropelException
	 */
	public function getRestaurant($con = null)
	{
		// include the related Peer class
		include_once 'lib/model/om/BaseRestaurantPeer.php';

		if ($this->aRestaurant === null && ($this->restaurant_id !== null)) {

			$this->aRestaurant = RestaurantPeer::retrieveByPK($this->restaurant_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = RestaurantPeer::retrieveByPK($this->restaurant_id, $con);
			   $obj->addRestaurants($this);
			 */
		}
		return $this->aRestaurant;
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
	 * Otherwise if this MenuItem has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 * If this MenuItem is new, it will return
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

				$criteria->add(MenuitemVersionPeer::MENUITEM_ID, $this->getId());

				MenuitemVersionPeer::addSelectColumns($criteria);
				$this->collMenuitemVersions = MenuitemVersionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuitemVersionPeer::MENUITEM_ID, $this->getId());

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

		$criteria->add(MenuitemVersionPeer::MENUITEM_ID, $this->getId());

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
		$l->setMenuItem($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MenuItem is new, it will return
	 * an empty collection; or if this MenuItem has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in MenuItem.
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

				$criteria->add(MenuitemVersionPeer::MENUITEM_ID, $this->getId());

				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinLocation($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuitemVersionPeer::MENUITEM_ID, $this->getId());

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
	 * Otherwise if this MenuItem is new, it will return
	 * an empty collection; or if this MenuItem has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in MenuItem.
	 */
	public function getMenuitemVersionsJoinUser($criteria = null, $con = null)
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

				$criteria->add(MenuitemVersionPeer::MENUITEM_ID, $this->getId());

				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuitemVersionPeer::MENUITEM_ID, $this->getId());

			if (!isset($this->lastMenuitemVersionCriteria) || !$this->lastMenuitemVersionCriteria->equals($criteria)) {
				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastMenuitemVersionCriteria = $criteria;

		return $this->collMenuitemVersions;
	}

	/**
	 * Temporary storage of collMenuitemSearchIndexs to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initMenuitemSearchIndexs()
	{
		if ($this->collMenuitemSearchIndexs === null) {
			$this->collMenuitemSearchIndexs = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MenuItem has previously
	 * been saved, it will retrieve related MenuitemSearchIndexs from storage.
	 * If this MenuItem is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getMenuitemSearchIndexs($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemSearchIndexPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuitemSearchIndexs === null) {
			if ($this->isNew()) {
			   $this->collMenuitemSearchIndexs = array();
			} else {

				$criteria->add(MenuitemSearchIndexPeer::MENUITEM_ID, $this->getId());

				MenuitemSearchIndexPeer::addSelectColumns($criteria);
				$this->collMenuitemSearchIndexs = MenuitemSearchIndexPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuitemSearchIndexPeer::MENUITEM_ID, $this->getId());

				MenuitemSearchIndexPeer::addSelectColumns($criteria);
				if (!isset($this->lastMenuitemSearchIndexCriteria) || !$this->lastMenuitemSearchIndexCriteria->equals($criteria)) {
					$this->collMenuitemSearchIndexs = MenuitemSearchIndexPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMenuitemSearchIndexCriteria = $criteria;
		return $this->collMenuitemSearchIndexs;
	}

	/**
	 * Returns the number of related MenuitemSearchIndexs.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countMenuitemSearchIndexs($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuitemSearchIndexPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MenuitemSearchIndexPeer::MENUITEM_ID, $this->getId());

		return MenuitemSearchIndexPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MenuitemSearchIndex object to this object
	 * through the MenuitemSearchIndex foreign key attribute
	 *
	 * @param MenuitemSearchIndex $l MenuitemSearchIndex
	 * @return void
	 * @throws PropelException
	 */
	public function addMenuitemSearchIndex(MenuitemSearchIndex $l)
	{
		$this->collMenuitemSearchIndexs[] = $l;
		$l->setMenuItem($this);
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
	 * Otherwise if this MenuItem has previously
	 * been saved, it will retrieve related MenuItemNotes from storage.
	 * If this MenuItem is new, it will return
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

				$criteria->add(MenuItemNotePeer::MENU_ITEM_ID, $this->getId());

				MenuItemNotePeer::addSelectColumns($criteria);
				$this->collMenuItemNotes = MenuItemNotePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuItemNotePeer::MENU_ITEM_ID, $this->getId());

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

		$criteria->add(MenuItemNotePeer::MENU_ITEM_ID, $this->getId());

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
		$l->setMenuItem($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MenuItem is new, it will return
	 * an empty collection; or if this MenuItem has previously
	 * been saved, it will retrieve related MenuItemNotes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in MenuItem.
	 */
	public function getMenuItemNotesJoinUser($criteria = null, $con = null)
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

				$criteria->add(MenuItemNotePeer::MENU_ITEM_ID, $this->getId());

				$this->collMenuItemNotes = MenuItemNotePeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuItemNotePeer::MENU_ITEM_ID, $this->getId());

			if (!isset($this->lastMenuItemNoteCriteria) || !$this->lastMenuItemNoteCriteria->equals($criteria)) {
				$this->collMenuItemNotes = MenuItemNotePeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastMenuItemNoteCriteria = $criteria;

		return $this->collMenuItemNotes;
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
	 * Otherwise if this MenuItem has previously
	 * been saved, it will retrieve related MenuItemRatings from storage.
	 * If this MenuItem is new, it will return
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

				$criteria->add(MenuItemRatingPeer::MENU_ITEM_ID, $this->getId());

				MenuItemRatingPeer::addSelectColumns($criteria);
				$this->collMenuItemRatings = MenuItemRatingPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuItemRatingPeer::MENU_ITEM_ID, $this->getId());

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

		$criteria->add(MenuItemRatingPeer::MENU_ITEM_ID, $this->getId());

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
		$l->setMenuItem($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MenuItem is new, it will return
	 * an empty collection; or if this MenuItem has previously
	 * been saved, it will retrieve related MenuItemRatings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in MenuItem.
	 */
	public function getMenuItemRatingsJoinUser($criteria = null, $con = null)
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

				$criteria->add(MenuItemRatingPeer::MENU_ITEM_ID, $this->getId());

				$this->collMenuItemRatings = MenuItemRatingPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuItemRatingPeer::MENU_ITEM_ID, $this->getId());

			if (!isset($this->lastMenuItemRatingCriteria) || !$this->lastMenuItemRatingCriteria->equals($criteria)) {
				$this->collMenuItemRatings = MenuItemRatingPeer::doSelectJoinUser($criteria, $con);
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
	 * Otherwise if this MenuItem has previously
	 * been saved, it will retrieve related MenuitemTags from storage.
	 * If this MenuItem is new, it will return
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

				$criteria->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId());

				MenuitemTagPeer::addSelectColumns($criteria);
				$this->collMenuitemTags = MenuitemTagPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId());

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

		$criteria->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId());

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
		$l->setMenuItem($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MenuItem is new, it will return
	 * an empty collection; or if this MenuItem has previously
	 * been saved, it will retrieve related MenuitemTags from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in MenuItem.
	 */
	public function getMenuitemTagsJoinUser($criteria = null, $con = null)
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

				$criteria->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId());

				$this->collMenuitemTags = MenuitemTagPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId());

			if (!isset($this->lastMenuitemTagCriteria) || !$this->lastMenuitemTagCriteria->equals($criteria)) {
				$this->collMenuitemTags = MenuitemTagPeer::doSelectJoinUser($criteria, $con);
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
	 * Otherwise if this MenuItem has previously
	 * been saved, it will retrieve related MenuItemImages from storage.
	 * If this MenuItem is new, it will return
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

				$criteria->add(MenuItemImagePeer::MENU_ITEM_ID, $this->getId());

				MenuItemImagePeer::addSelectColumns($criteria);
				$this->collMenuItemImages = MenuItemImagePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuItemImagePeer::MENU_ITEM_ID, $this->getId());

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

		$criteria->add(MenuItemImagePeer::MENU_ITEM_ID, $this->getId());

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
		$l->setMenuItem($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MenuItem is new, it will return
	 * an empty collection; or if this MenuItem has previously
	 * been saved, it will retrieve related MenuItemImages from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in MenuItem.
	 */
	public function getMenuItemImagesJoinUser($criteria = null, $con = null)
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

				$criteria->add(MenuItemImagePeer::MENU_ITEM_ID, $this->getId());

				$this->collMenuItemImages = MenuItemImagePeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuItemImagePeer::MENU_ITEM_ID, $this->getId());

			if (!isset($this->lastMenuItemImageCriteria) || !$this->lastMenuItemImageCriteria->equals($criteria)) {
				$this->collMenuItemImages = MenuItemImagePeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastMenuItemImageCriteria = $criteria;

		return $this->collMenuItemImages;
	}

} // BaseMenuItem
