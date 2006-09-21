<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'lib/model/RestaurantSearchIndexPeer.php';

/**
 * Base class that represents a row from the 'restaurant_search_index' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseRestaurantSearchIndex extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var RestaurantSearchIndexPeer
	 */
	protected static $peer;


	/**
	 * The value for the restaurant_id field.
	 * @var int
	 */
	protected $restaurant_id;


	/**
	 * The value for the word field.
	 * @var string
	 */
	protected $word;


	/**
	 * The value for the weight field.
	 * @var int
	 */
	protected $weight;

	/**
	 * @var Restaurant
	 */
	protected $aRestaurant;

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
	 * Get the [restaurant_id] column value.
	 * 
	 * @return int
	 */
	public function getRestaurantId()
	{

		return $this->restaurant_id;
	}

	/**
	 * Get the [word] column value.
	 * 
	 * @return string
	 */
	public function getWord()
	{

		return $this->word;
	}

	/**
	 * Get the [weight] column value.
	 * 
	 * @return int
	 */
	public function getWeight()
	{

		return $this->weight;
	}

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
			$this->modifiedColumns[] = RestaurantSearchIndexPeer::RESTAURANT_ID;
		}

		if ($this->aRestaurant !== null && $this->aRestaurant->getId() !== $v) {
			$this->aRestaurant = null;
		}

	} // setRestaurantId()

	/**
	 * Set the value of [word] column.
	 * 
	 * @param string $v new value
	 * @return void
	 */
	public function setWord($v)
	{

		if ($this->word !== $v) {
			$this->word = $v;
			$this->modifiedColumns[] = RestaurantSearchIndexPeer::WORD;
		}

	} // setWord()

	/**
	 * Set the value of [weight] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setWeight($v)
	{

		if ($this->weight !== $v) {
			$this->weight = $v;
			$this->modifiedColumns[] = RestaurantSearchIndexPeer::WEIGHT;
		}

	} // setWeight()

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

			$this->restaurant_id = $rs->getInt($startcol + 0);

			$this->word = $rs->getString($startcol + 1);

			$this->weight = $rs->getInt($startcol + 2);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 3; // 3 = RestaurantSearchIndexPeer::NUM_COLUMNS - RestaurantSearchIndexPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating RestaurantSearchIndex object", $e);
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
			$con = Propel::getConnection(RestaurantSearchIndexPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			RestaurantSearchIndexPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(RestaurantSearchIndexPeer::DATABASE_NAME);
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


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = RestaurantSearchIndexPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += RestaurantSearchIndexPeer::doUpdate($this, $con);
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

			if ($this->aRestaurant !== null) {
				if (!$this->aRestaurant->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aRestaurant->getValidationFailures());
				}
			}


			if (($retval = RestaurantSearchIndexPeer::doValidate($this, $columns)) !== true) {
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
		$pos = RestaurantSearchIndexPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getRestaurantId();
				break;
			case 1:
				return $this->getWord();
				break;
			case 2:
				return $this->getWeight();
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
		$keys = RestaurantSearchIndexPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getRestaurantId(),
			$keys[1] => $this->getWord(),
			$keys[2] => $this->getWeight(),
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
		$pos = RestaurantSearchIndexPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setRestaurantId($value);
				break;
			case 1:
				$this->setWord($value);
				break;
			case 2:
				$this->setWeight($value);
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
		$keys = RestaurantSearchIndexPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setRestaurantId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setWord($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setWeight($arr[$keys[2]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(RestaurantSearchIndexPeer::DATABASE_NAME);

		if ($this->isColumnModified(RestaurantSearchIndexPeer::RESTAURANT_ID)) $criteria->add(RestaurantSearchIndexPeer::RESTAURANT_ID, $this->restaurant_id);
		if ($this->isColumnModified(RestaurantSearchIndexPeer::WORD)) $criteria->add(RestaurantSearchIndexPeer::WORD, $this->word);
		if ($this->isColumnModified(RestaurantSearchIndexPeer::WEIGHT)) $criteria->add(RestaurantSearchIndexPeer::WEIGHT, $this->weight);

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
		$criteria = new Criteria(RestaurantSearchIndexPeer::DATABASE_NAME);


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
	 * @param object $copyObj An object of RestaurantSearchIndex (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setRestaurantId($this->restaurant_id);

		$copyObj->setWord($this->word);

		$copyObj->setWeight($this->weight);


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
	 * @return RestaurantSearchIndex Clone of current object.
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
	 * @return RestaurantSearchIndexPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new RestaurantSearchIndexPeer();
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

} // BaseRestaurantSearchIndex
