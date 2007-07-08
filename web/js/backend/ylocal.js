$(function() {
 $('#ylocal_add table').after('<a href="#" id="ylocal_add_toggle">Check All</a><br/>');
 $('#ylocal_add_toggle').click(
   function()
   {
     $('#ylocal_add').checkCheckboxes();
     return false;
   }
 );
});