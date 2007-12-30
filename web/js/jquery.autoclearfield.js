(function($) { // hide the namespace
  $.fn.autoclearfield = function() {
    return this.focus(function() 
    {
      if( this.value  == this.defaultValue ) {
        this.value    = "";
      }
    }).blur(function() 
    {
      if( !this.value.length ) {
        this.value    = this.defaultValue;
      }
    });
  };
})
(jQuery);