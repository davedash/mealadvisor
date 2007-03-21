<?php
/**
 * sfNiftyHelper.
 *
 * @package    symfony.sfNiftyPlugin
 * @author     Alban Creton <acreton@gmail.com>
 * @version    1.1.0
 */

/*
 * Provides a set of helpers for calling JavaScript functions and, most importantly,
 * to call remote methods using what has been labelled AJAX[http://www.adaptivepath.com/publications/essays/archives/000385.php].
 * This means that you can call actions in your controllers without reloading the page,
 * but still update certain parts of it using injections into the DOM.
 * The common use case is having a form that adds a new element to a list without reloading the page.
 *
 * To be able to use the JavaScript helpers, you must include the Prototype JavaScript Framework
 * and for some functions script.aculo.us (which both come with symfony) on your pages.
 * Choose one of these options:
 *
 * * Use <tt><?php echo javascript_include_tag :defaults ?></tt> in the HEAD section of your page (recommended):
 *   The function will return references to the JavaScript files created by the +rails+ command in your
 *   <tt>public/javascripts</tt> directory. Using it is recommended as the browser can then cache the libraries
 *   instead of fetching all the functions anew on every request.
 * * Use <tt><?php echo javascript_include_tag 'prototype' ?></tt>: As above, but will only include the Prototype core library,
 *   which means you are able to use all basic AJAX functionality. For the script.aculo.us-based JavaScript helpers,
 *   like visual effects, autocompletion, drag and drop and so on, you should use the method described above.
 * * Use <tt><?php echo define_javascript_functions ?></tt>: this will copy all the JavaScript support functions within a single
 *   script block.
 *
 * For documentation on +javascript_include_tag+ see ActionView::Helpers::AssetTagHelper.
 *
 * If you're the visual type, there's an AJAX movie[http://www.rubyonrails.com/media/video/rails-ajax.mov] demonstrating
 * the use of form_remote_tag.
 */


  /**
   * Get Js string to round an element's corners.
   *
   * @param string id of the html element
   * @param string rounding options
   *
   * @return String Js string to round the elements corner
   */  
  function nifty_round_elements( $elements, $options = "" )
  {
    
    $response = sfContext::getInstance()->getResponse();
    $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
    $response->addJavascript('/sfNiftyPlugin/js/niftycube');
    $response->addStylesheet('/sfNiftyPlugin/css/niftyCorners');   
    
    if(sfNifty::addId($elements))
    {
      return "Event.observe(window, 'load', function(){Rounded('".$elements."','".$options . "');}, false);";
    }
    else 
    {
      return "";
    }
     
  }

  
  // Compatibility
  function nifty_round_div( $elements, $options = "" )
  {
    return nifty_round_elements( $elements, $options );
  }
    
?>