var MA = {}; // MA namespace

MA.autoclearfield = function()
{
	var e = YAHOO.util.Event;

	return {
		init: function() {
			 e.onAvailable("q", this.fnHandler);
		},
		fnHandler: function(message) {
      e.addListener(this, "focus", function() 
      {
        if( this.value  == this.defaultValue ) 
        {
          this.value    = "";
        }
      });

      e.addListener(this, "blur", function() 
      {
        if( !this.value.length ) 
        {
          this.value    = this.defaultValue;
        }
      });
		},
	}
}();

MA.autoclearfield.init();