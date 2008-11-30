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

MA.paginator = function() {

    return {
        init: function() {
            MA.e.onDOMReady(this.setup,this, true)
        },

        setup: function() {
            MA.e.on(MA.d.get('doc4'),'click',this.handleClick,this,true);
        },

        handleClick: function(ev) {
            var target = MA.e.getTarget(ev);
            container = target.parentNode.parentNode.parentNode;
            
            if ((MA.d.hasClass(target.parentNode, 'page') || MA.d.hasClass(target.parentNode, 'navigation'))
            && (container.id == 'menu_page')) {
                
                MA.e.preventDefault(ev);
                
                // do the ajax call 
                handleSuccess = function(o) { container.innerHTML = o.responseText }
                
                callback = { 
                    success:handleSuccess,
                }
                
                var request = MA.c.asyncRequest('GET', target.pathname, callback);
            }
        },

    }
}();

MA.map = function() {
    return {
        draw: function(address, title) {
            var map = new YMap(document.getElementById('restaurant_map'));  
            map.drawZoomAndCenter(address, 4);
            var marker = new YMarker(address);
            marker.addAutoExpand(title);
            map.addOverlay(marker);
            map.disableKeyControls();
            map.addZoomLong();
        },
    }
}();

MA.toggler.init();
MA.paginator.init();