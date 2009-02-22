var MA = {}; // MA namespace
MA.e = YAHOO.util.Event;
MA.d = YAHOO.util.Dom;
MA.c = YAHOO.util.Connect;

MA.is_authenticated = function(msg) {
    var status = MA.d.hasClass(MA.d.get('doc4').parentNode, 'authenticated');

    if (!status && msg)
    {
        alert(msg);
    }
    return status;
}

MA.autoclearfield = function() {
  var defaultValue = 'Search for food';
  
  return {
    init: function() {
      MA.e.onAvailable("q", this.fnHandler);
    },
    fnHandler: function(message) {
      MA.e.addListener(this, "focus", function() 
      {
        if( this.value == this.defaultValue ) 
        {
          this.value = "";
        }
      });
 
      MA.e.addListener(this, "blur", function() 
      {
        if( !this.value.length ) 
        {
          this.value = this.defaultValue;
        }
      });
    }
  }
}();


MA.star_rater = function() {
    return {
        init: function() {
            MA.e.onDOMReady(this.setup,this, true)
        },
        
        setup: function() {
            MA.e.on(MA.d.get('doc4'),'click',this.handleClick,this,true);
        },
        
        handleClick: function(ev) {
            var target = MA.e.getTarget(ev);
            if (MA.d.hasClass(target, 'star') 
            && MA.d.hasClass(target.parentNode.parentNode.parentNode.parentNode, 'joint_star_rater')) {
                this.rate(target);
            }
        },
        
        rate: function(el) {
            if (MA.is_authenticated('Please sign in before rating =)')) {
                
                var root       = el.parentNode.parentNode.parentNode.parentNode;
                var action     = el.parentNode.parentNode.parentNode.action;
                var input      = MA.d.getFirstChildBy(el, function(d) {return (d.tagName == 'input'||d.tagName=='INPUT')});
                this.value = input.value;
                var postdata   = "value="+this.value;
                
                var handleSuccess = function(o) { root.innerHTML = o.responseText };
                var callback = { 
                    success:handleSuccess
                };
                
                
                var request = MA.c.asyncRequest('POST', action, callback, postdata);   
                // construct a connection object to this and use it to make a post
                // retrieve the post and then replace it with the original span
            }
        }
          
    }
}();

MA.star_rater.init();

MA.autoclearfield.init();
