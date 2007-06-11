<?php
use_helper('Text');

function pluralize($count, $singular, $plural = false)
{
   if (!$plural) $plural = $singular . 's';

  return ($count == 1 ? $singular : $plural) ;
}

function brif ( $test, $value = null )
{
	if ($value === null) $value = $test;
	
	if ($value) return $value . '<br/>';
}

function textif($value, $text)
{
	return $value ? $value . $text : null;
}

