<?php

class myUser extends myCommonUser
{
  // determines if this is a default fb account with a username fb: or not
  public function isUnlinked()
  {
    $pos = strpos($this->getUsername(), 'fb:');
    
    // if it's at the zeroth spot than this is an unlinked account
    return ($pos === 0);
  }
}
