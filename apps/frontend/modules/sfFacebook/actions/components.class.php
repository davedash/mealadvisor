<?php

#doc
# classname:  sfFacebookComponents
# scope:    PUBLIC
#
#/doc

class sfFacebookComponents extends sfComponents
{
public function executeReviews()
{
  if ($this->profile instanceof Profile)
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(MenuItemNotePeer::CREATED_AT);
    $c->setLimit(5);
    $mcomments = $this->profile->getMenuItemNotes($c);
    $this->comments = $mcomments;
    //$rcomments = $this->profile->getRestaurantNotes();
  }
}

}
###