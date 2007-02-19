<?php


function print_binary ($title, $binary)
{
   print "DUMP OF ".$title." (length: ".strlen($binary)." octents) <br>";

   $ascii = strtoupper(bin2hex($binary));
   $ascii_length = strlen($ascii);

   $offset = 0;
   $linelen = 0;
   $binary_offset = 0;
   $printbuf = sprintf("<b>%08d</b> ", $binary_offset);

   print "<font face=\"Courier New\">";

   while ($offset < $ascii_length){
      $printbuf = $printbuf.substr($ascii, $offset, 8);  
      $offset += 8;
      $linelen += 8;

      if ($linelen < 64){
         if ($offset < $ascii_length)
             $printbuf = $printbuf.'-';
      }
      else {
         $printbuf = $printbuf."<br>";
         print $printbuf;
         $binary_offset += 32;
         $printbuf = sprintf("<b>%08d</b> ", $binary_offset);
         $linelen = 0;
      }
   }

   if ($linelen > 0)
      print $printbuf;
   print "</font><br><br>";
}

