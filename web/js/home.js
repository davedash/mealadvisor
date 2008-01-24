(function($) { // hide the namespace
  $(function() {
    $("input#restaurant").autocomplete('/restaurant/ajax/list');    
  });
})
(jQuery);