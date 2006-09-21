<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/model/RestaurantPeer.php';

/**
 * Base class that represents a row from the 'restaurant' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseRestaurant extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var RestaurantPeer
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
	 * The value for the stripped_title field.
	 * @var string
	 */
	protected $stripped_title;


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
	 * The value for the version_id field.
	 * @var int
	 */
	protected $version_id;

	/**
	 * @var RestaurantVersion
	 */
	protected $aRestaurantVersion;

	/**
	 * Collection to store aggregation of collRestaurantSearchIndexs.
	 * @var array
	 */
	protected $collRestaurantSearchIndexs;

	/**
	 * The criteria used to select the current contents of collRestaurantSearchIndexs.
	 * @var Criteria
	 */
	protected $lastRestaurantSearchIndexCriteria = null;

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
	 * Get the [name] column value.
	 * 
	 * @return string
	 */
	public function getName()
	{

		return $this->name;
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
	 * Get the [version_id] column value.
	 * 
	 * @return int
	 */
	public function getVersionId()
	{

		return $this->version_id;
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
			$this->modifiedColumns[] = RestaurantPeer::ID;
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
			$this->modifiedColumns[] = RestaurantPeer::NAME;
		}

	} // setName()

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
			$this->modifiedColumns[] = RestaurantPeer::STRIPPED_TITLE;
		}

	} // setStrippedTitle()

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
			$this->modifiedColumns[] = RestaurantPeer::APPROVED;
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
			$this->modifiedColumns[] = RestaurantPeer::AVERAGE_RATING;
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
			$this->modifiedColumns[] = RestaurantPeer::NUM_RATINGS;
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
			$this->modifiedColumns[] = RestaurantPeer::UPDATED_AT;
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
			$this->modifiedColumns[] = RestaurantPeer::CREATED_AT;
		}

	} // setCreatedAt()

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
			$this->modifiedColumns[] = RestaurantPeer::VERSION_ID;
		}

		if ($this->aRestaurantVersion !== null && $this->aRestaurantVersion->getId() !== $v) {
			$this->aRestaurantVersion = null;
		}

	} // setVersionId()

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

			$this->stripped_title = $rs->getString($startcol + 2);

			$this->approved = $rs->getBoolean($startcol + 3);

			$this->average_rating = $rs->getFloat($startcol + 4);

			$this->num_ratings = $rs->getInt($startcol + 5);

			$this->updated_at = $rs->getTimestamp($startcol + 6, null);

			$this->created_at = $rs->getTimestamp($startcol + 7, null);

			$this->version_id = $rs->getInt($startcol + 8);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 9; // 9 = RestaurantPeer::NUM_COLUMNS - RestaurantPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Restaurant object", $e);
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
			$con = Propel::getConnection(RestaurantPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			RestaurantPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(RestaurantPeer::DATABASE_NAME);
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

			if ($this->aRestaurantVersion !== null) {
				if ($this->aRestaurantVersion->isModified()) {
					$affectedRows += $this->aRestaurantVersion->save($con);
				}
				$this->setRestaurantVersion($this->aRestaurantVersion);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = RestaurantPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += RestaurantPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collRestaurantSearchIndexs !== null) {
				foreach($this->collRestaurantSearchIndexs as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collRestaurantVersions !== null) {
				foreach($this->collRestaurantVersions as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collLocations !== null) {
				foreach($this->collLocations as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMenuImages !== null) {
				foreach($this->collMenuImages as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMenuItems !== null) {
				foreach($this->collMenuItems as $referrerFK) {
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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aRestaurantVersion !== null) {
				if (!$this->aRestaurantVersion->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aRestaurantVersion->getValidationFailures());
				}
			}


			if (($retval = RestaurantPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collRestaurantSearchIndexs !== null) {
					foreach($this->collRestaurantSearchIndexs as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collRestaurantVersions !== null) {
					foreach($this->collRestaurantVersions as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collLocations !== null) {
					foreach($this->collLocations as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMenuImages !== null) {
					foreach($this->collMenuImages as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMenuItems !== null) {
					foreach($this->collMenuItems as $referrerFK) {
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
		$pos = RestaurantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getStrippedTitle();
				break;
			case 3:
				return $this->getApproved();
				break;
			case 4:
				return $this->getAverageRating();
				break;
			case 5:
				return $this->getNumRatings();
				break;
			case 6:
				return $this->getUpdatedAt();
				break;
			case 7:
				return $this->getCreatedAt();
				break;
			case 8:
				return $this->getVersionId();
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
		$keys = RestaurantPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getName(),
			$keys[2] => $this->getStrippedTitle(),
			$keys[3] => $this->getApproved(),
			$keys[4] => $this->getAverageRating(),
			$keys[5] => $this->getNumRatings(),
			$keys[6] => $this->getUpdatedAt(),
			$keys[7] => $this->getCreatedAt(),
			$keys[8] => $this->getVersionId(),
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
		$pos = RestaurantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setStrippedTitle($value);
				break;
			case 3:
				$this->setApproved($value);
				break;
			case 4:
				$this->setAverageRating($value);
				break;
			case 5:
				$this->setNumRatings($value);
				break;
			case 6:
				$this->setUpdatedAt($value);
				break;
			case 7:
				$this->setCreatedAt($value);
				break;
			case 8:
				$this->setVersionId($value);
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
		$keys = RestaurantPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setStrippedTitle($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setApproved($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setAverageRating($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setNumRatings($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setUpdatedAt($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setVersionId($arr[$keys[8]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(RestaurantPeer::DATABASE_NAME);

		if ($this->isColumnModified(RestaurantPeer::ID)) $criteria->add(RestaurantPeer::ID, $this->id);
		if ($this->isColumnModified(RestaurantPeer::NAME)) $criteria->add(RestaurantPeer::NAME, $this->name);
		if ($this->isColumnModified(RestaurantPeer::STRIPPED_TITLE)) $criteria->add(RestaurantPeer::STRIPPED_TITLE, $this->stripped_title);
		if ($this->isColumnModified(RestaurantPeer::APPROVED)) $criteria->add(RestaurantPeer::APPROVED, $this->approved);
		if ($this->isColumnModified(RestaurantPeer::AVERAGE_RATING)) $criteria->add(RestaurantPeer::AVERAGE_RATING, $this->average_rating);
		if ($this->isColumnModified(RestaurantPeer::NUM_RATINGS)) $criteria->add(RestaurantPeer::NUM_RATINGS, $this->num_ratings);
		if ($this->isColumnModified(RestaurantPeer::UPDATED_AT)) $criteria->add(RestaurantPeer::UPDATED_AT, $this->updated_at);
		if ($this->isColumnModified(RestaurantPeer::CREATED_AT)) $criteria->add(RestaurantPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(RestaurantPeer::VERSION_ID)) $criteria->add(RestaurantPeer::VERSION_ID, $this->version_id);

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
		$criteria = new Criteria(RestaurantPeer::DATABASE_NAME);

		$criteria->add(RestaurantPeer::ID, $this->id);

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
	 * @param object $copyObj An object of Restaurant (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setName($this->name);

		$copyObj->setStrippedTitle($this->stripped_title);

		$copyObj->setApproved($this->approved);

		$copyObj->setAverageRating($this->average_rating);

		$copyObj->setNumRatings($this->num_ratings);

		$copyObj->setUpdatedAt($this->updated_at);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setVersionId($this->version_id);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getRestaurantSearchIndexs() as $relObj) {
				$copyObj->addRestaurantSearchIndex($relObj->copy($deepCopy));
			}

			foreach($this->getRestaurantVersions() as $relObj) {
				$copyObj->addRestaurantVersion($relObj->copy($deepCopy));
			}

			foreach($this->getLocations() as $relObj) {
				$copyObj->addLocation($relObj->copy($deepCopy));
			}

			foreach($this->getMenuImages() as $relObj) {
				$copyObj->addMenuImage($relObj->copy($deepCopy));
			}

			foreach($this->getMenuItems() as $relObj) {
				$copyObj->addMenuItem($relObj->copy($deepCopy));
			}

			foreach($this->getRestaurantNotes() as $relObj) {
				$copyObj->addRestaurantNote($relObj->copy($deepCopy));
			}

			foreach($this->getRestaurantRatings() as $relObj) {
				$copyObj->addRestaurantRating($relObj->copy($deepCopy));
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
	 * @return Restaurant Clone of current object.
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
	 * @return RestaurantPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new RestaurantPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a RestaurantVersion object.
	 *
	 * @param RestaurantVersion $v
	 * @return void
	 * @throws PropelException
	 */
	public function setRestaurantVersion($v)
	{


		if ($v === null) {
			$this->setVersionId(NULL);
		} else {
			$this->setVersionId($v->getId());
		}


		$this->aRestaurantVersion = $v;
	}


	/**
	 * Get the associated RestaurantVersion object
	 *
	 * @param Connection Optional Connection object.
	 * @return RestaurantVersion The associated RestaurantVersion object.
	 * @throws PropelException
	 */
	public function getRestaurantVersion($con = null)
	{
		// include the related Peer class
		include_once 'lib/model/om/BaseRestaurantVersionPeer.php';

		if ($this->aRestaurantVersion === null && ($this->version_id !== null)) {

			$this->aRestaurantVersion = RestaurantVersionPeer::retrieveByPK($this->version_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = RestaurantVersionPeer::retrieveByPK($this->version_id, $con);
			   $obj->addRestaurantVersions($this);
			 */
		}
		return $this->aRestaurantVersion;
	}

	/**
	 * Temporary storage of collRestaurantSearchIndexs to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return void
	 */
	public function initRestaurantSearchIndexs()
	{
		if ($this->collRestaurantSearchIndexs === null) {
			$this->collRestaurantSearchIndexs = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantSearchIndexs from storage.
	 * If this Restaurant is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param Connection $con
	 * @param Criteria $criteria
	 * @throws PropelException
	 */
	public function getRestaurantSearchIndexs($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantSearchIndexPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRestaurantSearchIndexs === null) {
			if ($this->isNew()) {
			   $this->collRestaurantSearchIndexs = array();
			} else {

				$criteria->add(RestaurantSearchIndexPeer::RESTAURANT_ID, $this->getId());

				RestaurantSearchIndexPeer::addSelectColumns($criteria);
				$this->collRestaurantSearchIndexs = RestaurantSearchIndexPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantSearchIndexPeer::RESTAURANT_ID, $this->getId());

				RestaurantSearchIndexPeer::addSelectColumns($criteria);
				if (!isset($this->lastRestaurantSearchIndexCriteria) || !$this->lastRestaurantSearchIndexCriteria->equals($criteria)) {
					$this->collRestaurantSearchIndexs = RestaurantSearchIndexPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastRestaurantSearchIndexCriteria = $criteria;
		return $this->collRestaurantSearchIndexs;
	}

	/**
	 * Returns the number of related RestaurantSearchIndexs.
	 *
	 * @param Criteria $criteria
	 * @param boolean $distinct
	 * @param Connection $con
	 * @throws PropelException
	 */
	public function countRestaurantSearchIndexs($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseRestaurantSearchIndexPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(RestaurantSearchIndexPeer::RESTAURANT_ID, $this->getId());

		return RestaurantSearchIndexPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a RestaurantSearchIndex object to this object
	 * through the RestaurantSearchIndex foreign key attribute
	 *
	 * @param RestaurantSearchIndex $l RestaurantSearchIndex
	 * @return void
	 * @throws PropelException
	 */
	public function addRestaurantSearchIndex(RestaurantSearchIndex $l)
	{
		$this->collRestaurantSearchIndexs[] = $l;
		$l->setRestaurant($this);
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
	 * Otherwise if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantVersions from storage.
	 * If this Restaurant is new, it will return
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

				$criteria->add(RestaurantVersionPeer::RESTAURANT_ID, $this->getId());

				RestaurantVersionPeer::addSelectColumns($criteria);
				$this->collRestaurantVersions = RestaurantVersionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantVersionPeer::RESTAURANT_ID, $this->getId());

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

		$criteria->add(RestaurantVersionPeer::RESTAURANT_ID, $this->getId());

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
		$l->setRestaurant($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantVersions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
	 */
	public function getRestaurantVersionsJoinUser($criteria = null, $con = null)
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

				$criteria->add(RestaurantVersionPeer::RESTAURANT_ID, $this->getId());

				$this->collRestaurantVersions = RestaurantVersionPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantVersionPeer::RESTAURANT_ID, $this->getId());

			if (!isset($this->lastRestaurantVersionCriteria) || !$this->lastRestaurantVersionCriteria->equals($criteria)) {
				$this->collRestaurantVersions = RestaurantVersionPeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastRestaurantVersionCriteria = $criteria;

		return $this->collRestaurantVersions;
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
	 * Otherwise if this Restaurant has previously
	 * been saved, it will retrieve related Locations from storage.
	 * If this Restaurant is new, it will return
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

				$criteria->add(LocationPeer::RESTAURANT_ID, $this->getId());

				LocationPeer::addSelectColumns($criteria);
				$this->collLocations = LocationPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(LocationPeer::RESTAURANT_ID, $this->getId());

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

		$criteria->add(LocationPeer::RESTAURANT_ID, $this->getId());

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
		$l->setRestaurant($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related Locations from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
	 */
	public function getLocationsJoinCountry($criteria = null, $con = null)
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

				$criteria->add(LocationPeer::RESTAURANT_ID, $this->getId());

				$this->collLocations = LocationPeer::doSelectJoinCountry($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(LocationPeer::RESTAURANT_ID, $this->getId());

			if (!isset($this->lastLocationCriteria) || !$this->lastLocationCriteria->equals($criteria)) {
				$this->collLocations = LocationPeer::doSelectJoinCountry($criteria, $con);
			}
		}
		$this->lastLocationCriteria = $criteria;

		return $this->collLocations;
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
	 * Otherwise if this Restaurant has previously
	 * been saved, it will retrieve related MenuImages from storage.
	 * If this Restaurant is new, it will return
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

				$criteria->add(MenuImagePeer::RESTAURANT_ID, $this->getId());

				MenuImagePeer::addSelectColumns($criteria);
				$this->collMenuImages = MenuImagePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuImagePeer::RESTAURANT_ID, $this->getId());

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

		$criteria->add(MenuImagePeer::RESTAURANT_ID, $this->getId());

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
		$l->setRestaurant($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related MenuImages from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
	 */
	public function getMenuImagesJoinLocation($criteria = null, $con = null)
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

				$criteria->add(MenuImagePeer::RESTAURANT_ID, $this->getId());

				$this->collMenuImages = MenuImagePeer::doSelectJoinLocation($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuImagePeer::RESTAURANT_ID, $this->getId());

			if (!isset($this->lastMenuImageCriteria) || !$this->lastMenuImageCriteria->equals($criteria)) {
				$this->collMenuImages = MenuImagePeer::doSelectJoinLocation($criteria, $con);
			}
		}
		$this->lastMenuImageCriteria = $criteria;

		return $this->collMenuImages;
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
	 * Otherwise if this Restaurant has previously
	 * been saved, it will retrieve related MenuItems from storage.
	 * If this Restaurant is new, it will return
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

				$criteria->add(MenuItemPeer::RESTAURANT_ID, $this->getId());

				MenuItemPeer::addSelectColumns($criteria);
				$this->collMenuItems = MenuItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MenuItemPeer::RESTAURANT_ID, $this->getId());

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

		$criteria->add(MenuItemPeer::RESTAURANT_ID, $this->getId());

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
		$l->setRestaurant($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related MenuItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
	 */
	public function getMenuItemsJoinMenuitemVersion($criteria = null, $con = null)
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

				$criteria->add(MenuItemPeer::RESTAURANT_ID, $this->getId());

				$this->collMenuItems = MenuItemPeer::doSelectJoinMenuitemVersion($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MenuItemPeer::RESTAURANT_ID, $this->getId());

			if (!isset($this->lastMenuItemCriteria) || !$this->lastMenuItemCriteria->equals($criteria)) {
				$this->collMenuItems = MenuItemPeer::doSelectJoinMenuitemVersion($criteria, $con);
			}
		}
		$this->lastMenuItemCriteria = $criteria;

		return $this->collMenuItems;
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
	 * Otherwise if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 * If this Restaurant is new, it will return
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

				$criteria->add(RestaurantNotePeer::RESTAURANT_ID, $this->getId());

				RestaurantNotePeer::addSelectColumns($criteria);
				$this->collRestaurantNotes = RestaurantNotePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantNotePeer::RESTAURANT_ID, $this->getId());

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

		$criteria->add(RestaurantNotePeer::RESTAURANT_ID, $this->getId());

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
		$l->setRestaurant($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
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

				$criteria->add(RestaurantNotePeer::RESTAURANT_ID, $this->getId());

				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantNotePeer::RESTAURANT_ID, $this->getId());

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
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantNotes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
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

				$criteria->add(RestaurantNotePeer::RESTAURANT_ID, $this->getId());

				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinLocation($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantNotePeer::RESTAURANT_ID, $this->getId());

			if (!isset($this->lastRestaurantNoteCriteria) || !$this->lastRestaurantNoteCriteria->equals($criteria)) {
				$this->collRestaurantNotes = RestaurantNotePeer::doSelectJoinLocation($criteria, $con);
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
	 * Otherwise if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 * If this Restaurant is new, it will return
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

				$criteria->add(RestaurantRatingPeer::RESTAURANT_ID, $this->getId());

				RestaurantRatingPeer::addSelectColumns($criteria);
				$this->collRestaurantRatings = RestaurantRatingPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantRatingPeer::RESTAURANT_ID, $this->getId());

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

		$criteria->add(RestaurantRatingPeer::RESTAURANT_ID, $this->getId());

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
		$l->setRestaurant($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
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

				$criteria->add(RestaurantRatingPeer::RESTAURANT_ID, $this->getId());

				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinLocation($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantRatingPeer::RESTAURANT_ID, $this->getId());

			if (!isset($this->lastRestaurantRatingCriteria) || !$this->lastRestaurantRatingCriteria->equals($criteria)) {
				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinLocation($criteria, $con);
			}
		}
		$this->lastRestaurantRatingCriteria = $criteria;

		return $this->collRestaurantRatings;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantRatings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
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

				$criteria->add(RestaurantRatingPeer::RESTAURANT_ID, $this->getId());

				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantRatingPeer::RESTAURANT_ID, $this->getId());

			if (!isset($this->lastRestaurantRatingCriteria) || !$this->lastRestaurantRatingCriteria->equals($criteria)) {
				$this->collRestaurantRatings = RestaurantRatingPeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastRestaurantRatingCriteria = $criteria;

		return $this->collRestaurantRatings;
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
	 * Otherwise if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantTags from storage.
	 * If this Restaurant is new, it will return
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

				$criteria->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId());

				RestaurantTagPeer::addSelectColumns($criteria);
				$this->collRestaurantTags = RestaurantTagPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId());

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

		$criteria->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId());

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
		$l->setRestaurant($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Restaurant is new, it will return
	 * an empty collection; or if this Restaurant has previously
	 * been saved, it will retrieve related RestaurantTags from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Restaurant.
	 */
	public function getRestaurantTagsJoinUser($criteria = null, $con = null)
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

				$criteria->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId());

				$this->collRestaurantTags = RestaurantTagPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId());

			if (!isset($this->lastRestaurantTagCriteria) || !$this->lastRestaurantTagCriteria->equals($criteria)) {
				$this->collRestaurantTags = RestaurantTagPeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastRestaurantTagCriteria = $criteria;

		return $this->collRestaurantTags;
	}

} // BaseRestaurant
