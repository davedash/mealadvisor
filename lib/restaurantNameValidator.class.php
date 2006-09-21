<?php

	class restaurantNameValidator extends sfValidator
	{

	  public function execute (&$value, &$error)
	  {
	    /*
	    Check to see if the username already exists in the database.
	    */
	    
		$c = new Criteria();
	    $c1 = $c->getNewCriterion(RestaurantPeer::NAME, $value);
		$c2 = $c->getNewCriterion(RestaurantPeer::STRIPPED_TITLE, myTools::stripText($value));
		$c1->addOr($c2);
		$c->add($c1);
	    $restaurant = RestaurantPeer::doSelectOne($c);

		// username exists?
		if ($restaurant instanceof Restaurant)
	    {
			$error = $this->getParameterHolder()->get('name_error');
			return false;
		}

		return true;
	  }

	  public function initialize ($context, $parameters = null)
	  {
  
	    // initialize parent
	    parent::initialize($context, $parameters);
	    return true;
	  }
	}

?>