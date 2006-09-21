<?php
use_helper('Text');

function pluralize($count, $singular, $plural = false)
{
   if (!$plural) $plural = $singular . 's';

  return ($count == 1 ? $singular : $plural) ;
}

function brif ( $value )
{
	if ($value) return $value . '<br/>';
}

function textif($value, $text)
{
	return $value ? $value . $text : null;
}
?>