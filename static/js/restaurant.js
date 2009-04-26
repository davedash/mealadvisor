MA.toggler = function()
{
    var e = YAHOO.util.Event, 
    d = YAHOO.util.Dom;
    
    return {
        init: function() {
            e.onDOMReady(this.setup,this, true)
        },
        
        setup: function() {
            e.on(d.get('doc4'),'click',this.handleClick,this,true);
        },
        
        handleClick: function(ev) {
            var target = e.getTarget(ev),
                toggle;

            if (d.hasClass(target, 'toggle')) {
                toggle = target.parentNode.parentNode;
                this.toggle(toggle);
            }
        },
        toggle: function(element) {
            var on  = "on", off = "off";
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
            var mp = MA.d.get('menu_page');
            if (mp) {
                MA.e.on(mp,'click',this.handleClick,this,true);
            }
        },

        handleClick: function(ev) {
            var target = MA.e.getTarget(ev),
                container = target.parentNode.parentNode.parentNode,
                handleSuccess, callback, request;
            
            if ((MA.d.hasClass(target.parentNode, 'page') || MA.d.hasClass(target.parentNode, 'navigation'))
            && (container.id == 'menu_page')) {
                
                MA.e.preventDefault(ev);
                
                // do the ajax call 
                handleSuccess = function(o) { container.innerHTML = o.responseText };
                
                callback = { 
                    success:handleSuccess
                };
                
                request = MA.c.asyncRequest('GET', target.href, callback);
            }
        }

    }
}();

MA.map = function() {
    return {
        draw: function(address, title) {
            var map, marker;
            map = new YMap(document.getElementById('restaurant_map'));  
            map.drawZoomAndCenter(address, 4);
            marker = new YMarker(address);
            marker.addAutoExpand(title);
            map.addOverlay(marker);
            map.disableKeyControls();
            map.addZoomLong();
        }
    }
}();

MA.tagger = function() {
    return {
        init: function() {
            MA.e.onDOMReady(this.setup, this, true)
        },
        setup: function() {
            var newdiv, link;
            this.tagger = MA.d.get('tag_form_container');
            
            if (this.tagger) {
                
                newdiv = document.createElement('div');
                newdiv.innerHTML = '<a href="#">Add a tag?</a>';
                this.tagger.appendChild(newdiv);
                link = newdiv.firstChild;
                MA.e.on(link, 'click', this.handleClick, this, true);
            }
            
        },
        
        handleClick: function(ev) {
            var oDS, oAC, form, tag_type;
            
            MA.e.preventDefault(ev);
            
            form = MA.d.get('tag_form');
            MA.d.setStyle(form, 'display', 'block'); 
            MA.d.setStyle(ev.target, 'display', 'none'); 
            tag_type = MA.d.get('tag_type_field').name;
            
            oDS = new YAHOO.util.XHRDataSource("/ajax/tag_ac?t="+tag_type);
            oDS.responseType = YAHOO.util.XHRDataSource.TYPE_TEXT;
            oDS.responseSchema = { recordDelim: "\n", fieldDelim: "\t" };
            oDS.maxCacheEntries = 5;

            oAC = new YAHOO.widget.AutoComplete("tag_input", "tag_listing", oDS);
            oAC.generateRequest = function(sQuery) { 
                return "&q=" + sQuery ; 
            };
            
            // handle the form's submission
            MA.e.on(form, 'submit', this.handleSubmit, this, true);
            MA.d.get('tag_input').focus();
            
        },
        
        handleSubmit: function(ev) {
            var container, handleSuccess, callback, sUrl, request;
            
            container = MA.d.get('tag_list');
            // do the ajax call 
            handleSuccess = function(o) { container.innerHTML = o.responseText };
            
            callback = { 
                success: handleSuccess
            };
            
            YAHOO.util.Connect.setForm(ev.target); 
            sUrl = ev.target.action;
            request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback);
            MA.d.get('tag_input').value = '';
            MA.e.preventDefault(ev);
        },
        
        remove: function(id) {
            var container, tag_type, sUrl, handleSuccess, callback, postData, request;
            
            container = MA.d.get('tag_list');
            tag_type = MA.d.get('tag_type_field').name;
            
            sUrl = '/ajax/tag_rm?t='+tag_type;
            handleSuccess = function(o) { container.innerHTML = o.responseText }
            
            callback = { 
                success: handleSuccess
            }
            
            postData = 'id='+id;
            request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData);
            return false;
        }
        
    }
}();

MA.toggler.init();
MA.paginator.init();
MA.tagger.init();