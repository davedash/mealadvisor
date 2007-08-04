<?php

  #doc
  # classname:  sfFacebookFilter
  # scope:    PUBLIC
  #
  #/doc

  class myFacebookFilter extends sfFilter
  {
    public function execute ($filterChain)
    {
      
      // We want to store as much data as we can about the facebook user
      // at least get the FB id, and then we can take care of the rest.  
      if ($this->isFirstCall())
      {
      
        $user = $this->getContext()->getUser();
        // here we do a couple things
        $id  = $user->getAttribute('id', null, 'facebook');
        $fbr = FacebookProfileRelPeer::retrieveByPK($id);

        $sfgu = null;
        
        if ($fbr instanceof FacebookProfileRel)
        {
          $sfgu = $fbr->getProfile()->getsfGuardUser();
        }
        else
        {
          $p = new Profile();
          $p->setUsername('fb:'.$id);
          $p->save();
          $fbr = new FacebookProfileRel();
          $fbr->setProfile($p);
          $fbr->setFbid($id);
          $fbr->save();
          $sfgu = $p->getsfGuardUser();
        }
        
        $user->signin($sfgu);
      }


      $filterChain->execute();
    }
  }
  ###
