var MA={};MA.e=YAHOO.util.Event;MA.d=YAHOO.util.Dom;MA.c=YAHOO.util.Connect;MA.is_authenticated=function(b){var a=MA.d.hasClass(MA.d.get("doc4").parentNode,"authenticated");if(!a&&b){alert(b)}return a};MA.autoclearfield=function(){var a="Search for food";return{init:function(){MA.e.onAvailable("q",this.fnHandler)},fnHandler:function(b){MA.e.addListener(this,"focus",function(){if(this.value==this.defaultValue){this.value=""}});MA.e.addListener(this,"blur",function(){if(!this.value.length){this.value=this.defaultValue}})}}}();MA.star_rater=function(){return{init:function(){MA.e.onDOMReady(this.setup,this,true)},setup:function(){MA.e.on(MA.d.get("doc4"),"click",this.handleClick,this,true)},handleClick:function(a){var b=MA.e.getTarget(a);if(MA.d.hasClass(b,"star")&&MA.d.hasClass(b.parentNode.parentNode.parentNode.parentNode,"joint_star_rater")){this.rate(b)}},rate:function(e){if(MA.is_authenticated("Please sign in before rating =)")){var b,g,c,d,h,a,f;b=e.parentNode.parentNode.parentNode.parentNode;g=e.parentNode.parentNode.parentNode.action;c=MA.d.getFirstChildBy(e,function(i){return(i.tagName=="input"||i.tagName=="INPUT")});this.value=c.value;d="value="+this.value;a=function(i){b.innerHTML=i.responseText};h={success:a};f=MA.c.asyncRequest("POST",g,h,d)}}}}();MA.star_rater.init();MA.autoclearfield.init();MA.autocomplete=function(){var b=YAHOO.util.Event;var a=YAHOO.widget;return{init:function(){b.onAvailable("restaurantInput",this.fnHandler)},fnHandler:function(){var d=new a.DS_XHR("/ajax/restaurant/list",["ResultSet.Result","Title"]),c;d.maxCacheEntries=60;d.queryMatchContains=true;c=new a.AutoComplete("restaurantInput","restaurantACContainer",d);c.formatResult=function(e,f){return e[1].Title};c.forceSelection=true;c.allowBrowserAutocomplete=false;c.itemSelectEvent.subscribe(function(g,f){var e=f[2];document.getElementById("restaurant_id").value=f[2][1]["Id"]})}}}();MA.autocomplete.init();MA.toggler=function(){var a=YAHOO.util.Event;var b=YAHOO.util.Dom;return{init:function(){a.onDOMReady(this.setup,this,true)},setup:function(){a.on(b.get("doc4"),"click",this.handleClick,this,true)},handleClick:function(d){var e=a.getTarget(d);if(b.hasClass(e,"toggle")){var c=e.parentNode.parentNode;this.toggle(c)}},toggle:function(d){var c="on";var e="off";if(b.hasClass(d,c)){b.removeClass(d,c);b.addClass(d,e)}else{b.removeClass(d,e);b.addClass(d,c)}}}}();MA.paginator=function(){return{init:function(){MA.e.onDOMReady(this.setup,this,true)},setup:function(){var a=MA.d.get("menu_page");if(a){MA.e.on(a,"click",this.handleClick,this,true)}},handleClick:function(c){var d=MA.e.getTarget(c);container=d.parentNode.parentNode.parentNode;if((MA.d.hasClass(d.parentNode,"page")||MA.d.hasClass(d.parentNode,"navigation"))&&(container.id=="menu_page")){MA.e.preventDefault(c);var a=function(f){container.innerHTML=f.responseText};var e={success:a};var b=MA.c.asyncRequest("GET",d.href,e)}}}}();MA.map=function(){return{draw:function(b,d){var c=new YMap(document.getElementById("restaurant_map"));c.drawZoomAndCenter(b,4);var a=new YMarker(b);a.addAutoExpand(d);c.addOverlay(a);c.disableKeyControls();c.addZoomLong()}}}();MA.tagger=function(){return{init:function(){MA.e.onDOMReady(this.setup,this,true)},setup:function(){this.tagger=MA.d.get("tag_form_container");if(this.tagger){var b=document.createElement("div");b.innerHTML='<a href="#">Add a tag?</a>';this.tagger.appendChild(b);var a=b.firstChild;MA.e.on(a,"click",this.handleClick,this,true)}},handleClick:function(c){MA.e.preventDefault(c);form=MA.d.get("tag_form");MA.d.setStyle(form,"display","block");MA.d.setStyle(c.target,"display","none");tag_type=MA.d.get("tag_type_field").name;var b=new YAHOO.util.XHRDataSource("/ajax/tag_ac?t="+tag_type);b.responseType=YAHOO.util.XHRDataSource.TYPE_TEXT;b.responseSchema={recordDelim:"\n",fieldDelim:"\t"};b.maxCacheEntries=5;var a=new YAHOO.widget.AutoComplete("tag_input","tag_listing",b);a.generateRequest=function(d){return"&q="+d};MA.e.on(form,"submit",this.handleSubmit,this,true)},handleSubmit:function(d){var a=MA.d.get("tag_list");handleSuccess=function(f){a.innerHTML=f.responseText};var e={success:handleSuccess};YAHOO.util.Connect.setForm(d.target);var c=d.target.action;var b=YAHOO.util.Connect.asyncRequest("POST",c,e);MA.d.get("tag_input").value="";MA.e.preventDefault(d)},remove:function(d){container=MA.d.get("tag_list");tag_type=MA.d.get("tag_type_field").name;var c="/ajax/tag_rm?t="+tag_type;handleSuccess=function(e){container.innerHTML=e.responseText};callback={success:handleSuccess};var a="id="+d;var b=YAHOO.util.Connect.asyncRequest("POST",c,callback,a);return false}}}();MA.toggler.init();MA.paginator.init();MA.tagger.init();