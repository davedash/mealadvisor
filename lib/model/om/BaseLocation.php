<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/model/LocationPeer.php';

/**
 * Base class that represents a row from the 'location' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseLocation extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var LocationPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var int
	 */
	protected $id;


	/**
	 * The value for the restaurant_id field.
	 * @var int
	 */
	protected $restaurant_id;


	/**
	 * The value for the stripped_title field.
	 * @var string
	 */
	protected $stripped_title;


	/**
	 * The value for the name field.
	 * @var string
	 */
	protected $name;


	/**
	 * The value for the address field.
	 * @var string
	 */
	protected $address;


	/**
	 * The value for the city field.
	 * @var string
	 */
	protected $city;


	/**
	 * The value for the state field.
	 * @var string
	 */
	protected $state;


	/**
	 * The value for the zip field.
	 * @var string
	 */
	protected $zip;


	/**
	 * The value for the country_id field.
	 * @var string
	 */
	protected $country_id;


	/**
	 * The value for the latitude field.
	 * @var double
	 */
	protected $latitude;


	/**
	 * The value for the longitude field.
	 * @var double
	 */
	protected $longitude;


	/**
	 * The value for the phone field.
	 * @var string
	 */
	protected $phone;


	/**
	 * The value for the approved field.
	 * @var boolean
	 */
	protected $approved;


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
	 * @var Restaurant
	 */
	protected $aRestaurant;

	/**
	 * @var Country
	 */
	protected $aCountry;

	/**
	 * Collection to store aggregation of collMenuImages.
	 * @var array
	 */
	protected $collMenuImages;

	/**
	 * The criteria used to select the current contents of collMenuImages.
	 * @var Criteria
	 */
	protected $lastMenuImageCriteria = null;

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
	 * Get the [restaurant_id] column value.
	 * 
	 * @return int
	 */
	public function getRestaurantId()
	{

		return $this->restaurant_id;
	}

	/**
	 * Get the [stripped_title] column value.
	 * 
	 * @return string
	 */
	public function getStrippedTitle()
	{

		return $this->stripped_title;
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
	 * Get the [address] column value.
	 * 
	 * @return string
	 */
	public function getAddress()
	{

		return $this->address;
	}

	/**
	 * Get the [city] column value.
	 * 
	 * @return string
	 */
	public function getCity()
	{

		return $this->city;
	}

	/**
	 * Get the [state] column value.
	 * 
	 * @return string
	 */
	public function getState()
	{

		return $this->state;
	}

	/**
	 * Get the [zip] column value.
	 * 
	 * @return string
	 */
	public function getZip()
	{

		return $this->zip;
	}

	/**
	 * Get the [country_id] column value.
	 * 
	 * @return string
	 */
	public function getCountryId()
	{

		return $this->country_id;
	}

	/**
	 * Get the [latitude] column value.
	 * 
	 * @return double
	 */
	public function getLatitude()
	{

		return $this->latitude;
	}

	/**
	 * Get the [longitude] column value.
	 * 
	 * @return double
	 */
	public function getLongitude()
	{

		return $this->longitude;
	}

	/**
	 * Get the [phone] column value.
	 * 
	 * @return string
	 */
	public function getPhone()
	{

		return $this->phone;
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
			$this->modifiedColumns[] = LocationPeer::ID;
		}

	} // setId()

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
			$this->modifiedColumns[] = LocationPeer::RESTAURANT_ID;
		}

		if ($this->aRestaurant !== null && $this->aRestaurant->getId() !== $v) {
			$this->aRestaurant = null;
		}

	} // setRestaurantId()

	/**
	 * Set the value of [stripped_title] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setStrippedTitle($v)
	{

		if ($this->stripped_title !== $v) {
			$this->stripped_title = $v;
			$this->modifiedColumns[] = LocationPeer::STRIPPED_TITLE;
		}

	} // setStrippedTitle()

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
			$this->modifiedColumns[] = LocationPeer::NAME;
		}

	} // setName()

	/**
	 * Set the value of [address] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setAddress($v)
	{

		if ($this->address !== $v) {
			$this->address = $v;
			$this->modifiedColumns[] = LocationPeer::ADDRESS;
		}

	} // setAddress()

	/**
	 * Set the value of [city] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setCity($v)
	{

		if ($this->city !== $v) {
			$this->city = $v;
			$this->modifiedColumns[] = LocationPeer::CITY;
		}

	} // setCity()

	/**
	 * Set the value of [state] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setState($v)
	{

		if ($this->state !== $v) {
			$this->state = $v;
			$this->modifiedColumns[] = LocationPeer::STATE;
		}

	} // setState()

	/**
	 * Set the value of [zip] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setZip($v)
	{

		if ($this->zip !== $v) {
			$this->zip = $v;
			$this->modifiedColumns[] = LocationPeer::ZIP;
		}

	} // setZip()

	/**
	 * Set the value of [country_id] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setCountryId($v)
	{

		if ($this->country_id !== $v) {
			$this->country_id = $v;
			$this->modifiedColumns[] = LocationPeer::COUNTRY_ID;
		}

		if ($this->aCountry !== null && $this->aCountry->getIso() !== $v) {
			$this->aCountry = null;
		}

	} // setCountryId()

	/**
	 * Set the value of [latitude] column.
	 * 
	 * @param double $v new value
	 * @return void
	 */
	public function setLatitude($v)
	{

		if ($this->latitude !== $v) {
			$this->latitude = $v;
			$this->modifiedColumns[] = LocationPeer::LATITUDE;
		}

	} // setLatitude()

	/**
	 * Set the value of [longitude] column.
	 * 
	 * @param double $v new value
	 * @return void
	 */
	public function setLongitude($v)
	{

		if ($this->longitude !== $v) {
			$this->longitude = $v;
			$this->modifiedColumns[] = LocationPeer::LONGITUDE;
		}

	} // setLongitude()

	/**
	 * Set the value of [phone] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setPhone($v)
	{

		if ($this->phone !== $v) {
			$this->phone = $v;
			$this->modifiedColumns[] = LocationPeer::PHONE;
		}

	} // setPhone()

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
			$this->modifiedColumns[] = LocationPeer::APPROVED;
		}

	} // setApproved()

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
			$this->modifiedColumns[] = LocationPeer::UPDATED_AT;
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
			$this->modifiedColumns[] = LocationPeer::CREATED_AT;
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

			$this->restaurant_id = $rs->getInt($startcol + 1);

			$this->stripped_title = $rs->getString($startcol + 2);

			$this->name = $rs->getString($startcol + 3);

			$this->address = $rs->getString($startcol + 4);

			$this->city = $rs->getString($startcol + 5);

			$this->state = $rs->getString($startcol + 6);

			$this->zip = $rs->getString($startcol + 7);

			$this->country_id = $rs->getString($startcol + 8);

			$this->latitude = $rs->getFloat($startcol + 9);

			$this->longitude = $rs->getFloat($startcol + 10);

			$this->phone = $rs->getString($startcol + 11);

			$this->approved = $rs->getBoolean($startcol + 12);

			$this->updated_at = $rs->getTimestamp($startcol + 13, null);

			$this->created_at = $rs->getTimestamp($startcol + 14, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 15; // 15 = LocationPeer::NUM_COLUMNS - LocationPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Location object", $e);
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
			$con = Propel::getConnection(LocationPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			LocationPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(LocationPeer::DATABASE_NAME);
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

			if ($this->aRestaurant !== null) {
				if ($this->aRestaurant->isModified()) {
					$affectedRows += $this->aRestaurant->save($con);
				}
				$this->setRestaurant($this->aRestaurant);
			}

			if ($this->aCountry !== null) {
				if ($this->aCountry->isModified()) {
					$affectedRows += $this->aCountry->save($con);
				}
				$this->setCountry($this->aCountry);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = LocationPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += LocationPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collMenuImages !== null) {
				foreach($this->collMenuImages as $referrerFK) {
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

			if ($this->collRestaurantRatings !== null) {
				foreach($this->collRestaurantRatings as $referrerFK) {
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

			if ($this->aRestaurant !== null) {
				if (!$this->aRestaurant->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aRestaurant->getValidationFailures());
				}
			}

			if ($this->aCountry !== null) {
				if (!$this->aCountry->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCountry->getValidationFailures());
				}
			}


			if (($retval = LocationPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collMenuImages !== null) {
					foreach($this->collMenuImages as $referrerFK) {
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

				if ($this->collRestaurantRatings !== null) {
					foreach($this->collRestaurantRatings as $referrerFK) {
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
		$pos = LocationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getRestaurantId();
				break;
			case 2:
				return $this->getStrippedTitle();
				break;
			case 3:
				return $this->getName();
				break;
			case 4:
				return $this->getAddress();
				break;
			case 5:
				return $this->getCity();
				break;
			case 6:
				return $this->getState();
				break;
			case 7:
				return $this->getZip();
				break;
			case 8:
				return $this->getCountryId();
				break;
			case 9:
				return $this->getLatitude();
				break;
			case 10:
				return $this->getLongitude();
				break;
			case 11:
				return $this->getPhone();
				break;
			case 12:
				return $this->getApproved();
				break;
			case 13:
				return $this->getUpdatedAt();
				break;
			case 14:
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
		$keys = LocationPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getRestaurantId(),
			$keys[2] => $this->getStrippedTitle(),
			$keys[3] => $this->getName(),
			$keys[4] => $this->getAddress(),
			$keys[5] => $this->getCity(),
			$keys[6] => $this->getState(),
			$keys[7] => $this->getZip(),
			$keys[8] => $this->getCountryId(),
			$keys[9] => $this->getLatitude(),
			$keys[10] => $this->getLongitude(),
			$keys[11] => $this->getPhone(),
			$keys[12] => $this->getApproved(),
			$keys[13] => $this->getUpdatedAt(),
			$keys[14] => $this->getCreatedAt(),
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
		$pos = LocationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setRestaurantId($value);
				break;
			case 2:
				$this->setStrippedTitle($value);
				break;
			case 3:
				$this->setName($value);
				break;
			case 4:
				$this->setAddress($value);
				break;
			case 5:
				$this->setCity($value);
				break;
			case 6:
				$this->setState($value);
				break;
			case 7:
				$this->setZip($value);
				break;
			case 8:
				$this->setCountryId($value);
				break;
			case 9:
				$this->setLatitude($value);
				break;
			case 10:
				$this->setLongitude($value);
				break;
			case 11:
				$this->setPhone($value);
				break;
			case 12:
				$this->setApproved($value);
				break;
			case 13:
				$this->setUpdatedAt($value);
				break;
			case 14:
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
		$keys = LocationPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setRestaurantId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setStrippedTitle($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setName($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setAddress($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setCity($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setState($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setZip($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCountryId($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setLatitude($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setLongitude($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setPhone($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setApproved($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUpdatedAt($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setCreatedAt($arr[$keys[14]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(LocationPeer::DATABASE_NAME);

		if ($this->isColumnModified(LocationPeer::ID)) $criteria->add(LocationPeer::ID, $this->id);
		if ($this->isColumnModified(LocationPeer::RESTAURANT_ID)) $criteria->add(LocationPeer::RESTAURANT_ID, $this->restaurant_id);
		if ($this->isColumnModified(LocationPeer::STRIPPED_TITLE)) $criteria->add(LocationPeer::STRIPPED_TITLE, $this->stripped_title);
		if ($this->isColumnModified(LocationPeer::NAME)) $criteria->add(LocationPeer::NAME, $this->name);
		if ($this->isColumnModified(LocationPeer::ADDRESS)) $criteria->add(LocationPeer::ADDRESS, $this->address);
		if ($this->isColumnModified(LocationPeer::CITY)) $criteria->add(LocationPeer::CITY, $this->city);
		if ($this->isColumnModified(LocationPeer::STATE)) $criteria->add(LocationPeer::STATE, $this->state);
		if ($this->isColumnModified(LocationPeer::ZIP)) $criteria->add(LocationPeer::ZIP, $this->zip);
		if ($this->isColumnModified(LocationPeer::COUNTRY_ID)) $criteria->add(LocationPeer::COUNTRY_ID, $this->country_id);
		if ($this->isColumnModified(LocationPeer::LATITUDE)) $criteria->add(LocationPeer::LATITUDE, $this->latitude);
		if ($this->isColumnModified(LocationPeer::LONGITUDE)) $criteria->add(LocationPeer::LONGITUDE, $this->longitude);
		if ($this->isColumnModified(LocationPeer::PHONE)) $criteria->add(LocationPeer::PHONE, $this->phone);
		if ($this->isColumnModified(LocationPeer::APPROVED)) $criteria->add(LocationPeer::APPROVED, $this->approved);
		if ($this->isColumnModified(LocationPeer::UPDATED_AT)) $criteria->add(LocationPeer::UPDATED_AT, $this->updated_at);
		if ($this->isColumnModified(LocationPeer::CREATED_AT)) $criteria->add(LocationPeer::CREATED_AT, $this->created_at);

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
		$criteria = new Criteria(LocationPeer::DATABASE_NAME);

		$criteria->add(LocationPeer::ID, $this->id);

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
	 * @param object $copyObj An object of Location (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setRestaurantId($this->restaurant_id);

		$copyObj->setStrippedTitle($this->stripped_title);

		$copyObj->setName($this->name);

		$copyObj->setAddress($this->address);

		$copyObj->setCity($this->city);

		$copyObj->setState($this->state);

		$copyObj->setZip($this->zip);

		$copyObj->setCountryId($this->country_id);

		$copyObj->setLatitude($this->latitude);

		$copyObj->setLongitude($this->longitude);

		$copyObj->setPhone($this->phone);

		$copyObj->setApproved($this->approved);

		$copyObj->setUpdatedAt($this->updated_at);

		$copyObj->setCreatedAt($this->created_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getMenuImages() as $relObj) {
				$copyObj->addMenuImage($relObj->copy($deepCopy));
			}

			foreach($this->getMenuitemVersions() as $relObj) {
				$copyObj->addMenuitemVersion($relObj->copy($deepCopy));
			}

			foreach($this->getRestaurantNotes() as $relObj) {
				$copyObj->addRestaurantNote($relObj->copy($deepCopy));
			}

			foreach($this->getRestaurantRatings() as $relObj) {
				$copyObj->addRestaurantRating($relObj->copy($deepCopy));
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
	 * @return Location Clone of current object.
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
	 * @return LocationPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new LocationPeer();
		}
		return self::$peer;
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
	 * Declares an association between this object and a Country object.
	 *
	 * @param Country $v
	 * @return void
	 * @throws PropelException
	 */
	public function setCountry($v)
	{


		if ($v === null) {
			$this->setCountryId(NULL);
		} else {
			$this->setCountryId($v->getIso());
		}


		$this->aCountry = $v;
	}


	/**
	 * Get the associated Country object
	 *
	 * @param Connection Optional Connection object.
	 * @return Country The associated Country object.
	 * @throws PropelException
	 */
	public function getCountry($con = null)
	{
		// include the related Peer class
		include_once 'lib/model/om/BaseCountryPeer.php';

		if ($this->aCountry === null && (($this->country_id !== "" && $this->country_id !== null))) {

			$this->aCountry = CountryPeer::retrieveByPK($this->country_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = CountryPeer::retrieveByPK($this->country_id, $con);
			   $obj->addCountrys($this);
			 */
		}
		return $this->aCountry;
	}

	/**
	 * Temporary storage of collMenuImages to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initMenuImages()
	{
		if ($this->collMenuImages === null) {
			$this->collMenuImages = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Location has previously
	 * been saved, it will retrieve related MenuImages from storage.
	 * If this Location is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getMenuImages($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuImagePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuImages === null) {
			if ($this->isNew()) {
			   $this->collMenuImages = array();
			} else {

				$criteria->add(MenuImagePeer::LOCATION_ID, $this->getId());

				MenuImagePeer::addSelectColumns($criteria);
				$this->collMenuImages = MenuImagePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuImagePeer::LOCATION_ID, $this->getId());

				MenuImagePeer::addSelectColumns($criteria);
				if (!isset($this->lastMenuImageCriteria) || !$this->lastMenuImageCriteria->equals($criteria)) {
					$this->collMenuImages = MenuImagePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMenuImageCriteria = $criteria;
		return $this->collMenuImages;
	}

	/**
	 * Returns the number of related MenuImages.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countMenuImages($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuImagePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MenuImagePeer::LOCATION_ID, $this->getId());

		return MenuImagePeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MenuImage object to this object
	 * through the MenuImage foreign key attribute
	 *
	 * @param MenuImage $l MenuImage
	 * @return void
	 * @throws PropelException
	 */
	public function addMenuImage(MenuImage $l)
	{
		$this->collMenuImages[] = $l;
		$l->setLocation($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Location is new, it will return
	 * an empty collection; or if this Location has previously
	 * been saved, it will retrieve related MenuImages from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Location.
	 */
	public function getMenuImagesJoinRestaurant($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMenuImagePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMenuImages === null) {
			if ($this->isNew()) {
				$this->collMenuImages = array();
			} else {

				$criteria->add(MenuImagePeer::LOCATION_ID, $this->getId());

				$this->collMenuImages = MenuImagePeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuImagePeer::LOCATION_ID, $this->getId());

			if (!isset($this->lastMenuImageCriteria) || !$this->lastMenuImageCriteria->equals($criteria)) {
				$this->collMenuImages = MenuImagePeer::doSelectJoinRestaurant($criteria, $con);
			}
		}
		$this->lastMenuImageCriteria = $criteria;

		return $this->collMenuImages;
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
	 * Otherwise if this Location has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 * If this Location is new, it will return
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

				$criteria->add(MenuitemVersionPeer::LOCATION_ID, $this->getId());

				MenuitemVersionPeer::addSelectColumns($criteria);
				$this->collMenuitemVersions = MenuitemVersionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuitemVersionPeer::LOCATION_ID, $this->getId());

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

		$criteria->add(MenuitemVersionPeer::LOCATION_ID, $this->getId());

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
		$l->setLocation($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Location is new, it will return
	 * an empty collection; or if this Location has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Location.
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

				$criteria->add(MenuitemVersionPeer::LOCATION_ID, $this->getId());

				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinMenuItem($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuitemVersionPeer::LOCATION_ID, $this->getId());

			if (!isset($this->lastMenuitemVersionCriteria) || !$this->lastMenuitemVersionCriteria->equals($criteria)) {
				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinMenuItem($criteria, $con);
			}
		}
		$this->lastMenuitemVersionCriteria = $criteria;

		return $this->collMenuitemVersions;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Location is new, it will return
	 * an empty collection; or if this Location has previously
	 * been saved, it will retrieve related MenuitemVersions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Location.
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

				$criteria->add(MenuitemVersionPeer::LOCATION_ID, $this->getId());

				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuitemVersionPeer::LOCATION_ID, $this->getId());

			if (!isset($this->lastMenuitemVersionCriteria) || !$this->lastMenuitemVersionCriteria->equals($criteria)) {
				$this->collMenuitemVersions = MenuitemVersionPeer::doSelectJoinUser($criteria, $con);
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
	 * Otherwise if this Location has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 * If this Location is new, it will return
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

				$criteria->add(RestaurantNotePeer::LOCATION_ID, $this->getId());

				RestaurantNotePeer::addSelectColumns($criteria);
				$this->collRestaurantNotes = RestaurantNotePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantNotePeer::LOCATION_ID, $this->getId());

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

		$criteria->add(RestaurantNotePeer::LOCATION_ID, $this->getId());

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
		$l->setLocation($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Location is new, it will return
	 * an empty collection; or if this Location has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Location.
	 */
	public function getRestaurantNotesJoinUser($criteria = null, $con = null)
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

				$criteria->add(RestaurantNotePeer::LOCATION_ID, $this->getId());

				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantNotePeer::LOCATION_ID, $this->getId());

			if (!isset($this->lastRestaurantNoteCriteria) || !$this->lastRestaurantNoteCriteria->equals($criteria)) {
				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastRestaurantNoteCriteria = $criteria;

		return $this->collRestaurantNotes;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Location is new, it will return
	 * an empty collection; or if this Location has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Location.
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

				$criteria->add(RestaurantNotePeer::LOCATION_ID, $this->getId());

				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantNotePeer::LOCATION_ID, $this->getId());

			if (!isset($this->lastRestaurantNoteCriteria) || !$this->lastRestaurantNoteCriteria->equals($criteria)) {
				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinRestaurant($criteria, $con);
			}
		}
		$this->lastRestaurantNoteCriteria = $criteria;

		return $this->collRestaurantNotes;
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
	 * Otherwise if this Location has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 * If this Location is new, it will return
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

				$criteria->add(RestaurantRatingPeer::LOCATION_ID, $this->getId());

				RestaurantRatingPeer::addSelectColumns($criteria);
				$this->collRestaurantRatings = RestaurantRatingPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantRatingPeer::LOCATION_ID, $this->getId());

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

		$criteria->add(RestaurantRatingPeer::LOCATION_ID, $this->getId());

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
		$l->setLocation($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Location is new, it will return
	 * an empty collection; or if this Location has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Location.
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

				$criteria->add(RestaurantRatingPeer::LOCATION_ID, $this->getId());

				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinRestaurant($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantRatingPeer::LOCATION_ID, $this->getId());

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
	 * Otherwise if this Location is new, it will return
	 * an empty collection; or if this Location has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Location.
	 */
	public function getRestaurantRatingsJoinUser($criteria = null, $con = null)
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

				$criteria->add(RestaurantRatingPeer::LOCATION_ID, $this->getId());

				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantRatingPeer::LOCATION_ID, $this->getId());

			if (!isset($this->lastRestaurantRatingCriteria) || !$this->lastRestaurantRatingCriteria->equals($criteria)) {
				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastRestaurantRatingCriteria = $criteria;

		return $this->collRestaurantRatings;
	}

} // BaseLocation
