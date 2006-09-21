<?php
class myLoginValidator extends sfValidator
{    
  public function initialize ($context, $parameters = null)
  {
    // initialize parent
    parent::initialize($context);
 
    // set defaults
    $this->getParameterHolder()->set('login_error', 'Invalid input');
 
    $this->getParameterHolder()->add($parameters);
 
    return true;
  }
 
  public function execute (&$value, &$error)
  {
    $password_param = $this->getParameterHolder()->get('password');
    $password = $this->getContext()->getRequest()->getParameter($password_param);
 
    $login = $value;
 
    // anonymous is not a real user
    if ($login == 'anonymous')
    {
      $error = $this->getParameterHolder()->get('login_error');
      return false;
    }
 
    $c = new Criteria();
    $c->add(UserPeer::USERID, $login);
    $user = UserPeer::doSelectOne($c);
 
    // username exists?
    if ($user)
    {
      // password is OK?
      if (md5($password) == $user->getPasswordMd5())
      {   
		// this logs the user in... let's instead use some magic
		$this->getContext()->getUser()->loginAs($user);
        return true;
      }
    }
 
    $error = $this->getParameterHolder()->get('login_error');
    return false;
  }
}
?>
