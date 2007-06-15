<?php

#doc
# classname:  MyFacebook
# scope:    PUBLIC
#
#/doc

class MyFacebook
{

  static public function renderProfileBox ($fb_user)
  {
    sfLoader::loadHelpers('Partial');
    
    $f = FacebookProfileRelPeer::retrieveByPK($fb_user);
    $content = null;
    if ( $f instanceof FacebookProfileRel )
    {
      $content = get_partial('sfFacebook/profileBox', array('profile' => $f->getProfile() ));
    }
    return $content;
  }

}
###