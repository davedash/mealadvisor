<?php
require_once(sfConfig::get('sf_plugins_dir').'/sfFacebookPlugin/modules/sfFacebook/lib/BasesfFacebookActions.class.php');


class sfFacebookActions extends BasesfFacebookActions
{
  public function preExecute()
  {
    parent::preExecute();
    if ($this->user && $this->getUser()->isAnonymous())
    {
      $f = FacebookProfileRelPeer::retrieveByPK($this->user);
      if ($f instanceof FacebookProfileRel)
      {
        $this->getUser()->signin($f->getProfile()->getsfGuardUser());
        $this->profile = $f->getProfile();
      }
    }
  }
  
  public function executeIndex()
  {
    if ( $this->profile instanceof Profile )
    {
      $this->renderProfileBox();
    }

  }
    
    public function renderProfileBox()
    {
      $fbml = MyFacebook::renderProfileBox($this->user);
      $this->facebook->api_client->profile_setFBML($fbml, $this->user);
      
    }
  public function executeConfirm()
  {
    $p = $this->getUser()->getProfile();
    $rels = $p->getFacebookProfileRels();
    foreach ($rels AS $rel)
    {
      $rel->delete();
    }
    $f = new FacebookProfileRel();
    $f->setFbid($this->user);
    $f->setProfile($this->getUser()->getProfile());
    $f->save();
  }
}
###
