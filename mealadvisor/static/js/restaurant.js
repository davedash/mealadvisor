MA.toggler = function()
{
    var e = YAHOO.util.Event; 
    var d = YAHOO.util.Dom;
    
    return {
        init: function() {
            e.onDOMReady(this.setup,this, true)
        },
        
        setup: function() {
            e.on(d.get('doc4'),'click',this.handleClick,this,true);
        },
        
        handleClick: function(ev) {
            var target = e.getTarget(ev);
            if (d.hasClass(target, 'toggle')) {
                var toggle = target.parentNode.parentNode;
                this.toggle(toggle);
            }
        },
        toggle: function(element) {
            var on  = "on";
            var off = "off";
            if (d.hasClass(element, on)){
                d.removeClass(element, on);
                d.addClass(element, off);
            } else {
                d.removeClass(element, off);
                d.addClass(element, on);
            }
        }
    }
}();

MA.toggler.init();