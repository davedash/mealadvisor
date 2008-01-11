/*
 * Ext - JS Library 1.0 Alpha 3 - Rev 4
 * Copyright(c) 2006-2007, Jack Slocum.
 * 
 * http://www.extjs.com/license.txt
 */

Ext={};window["undefined"]=window["undefined"];Ext.apply=function(o,c,_3){if(_3){Ext.apply(o,_3);}if(o&&c){for(var p in c){o[p]=c[p];}}return o;};(function(){var _5=0;var ua=navigator.userAgent.toLowerCase();var _7=document.compatMode=="CSS1Compat",_8=ua.indexOf("opera")>-1,_9=ua.indexOf("webkit")>-1,_a=ua.indexOf("msie")>-1,_b=ua.indexOf("msie 7")>-1,_c=!_9&&ua.indexOf("gecko")>-1,_d=_a&&!_7,_e=(ua.indexOf("windows")!=-1||ua.indexOf("win32")!=-1),_f=(ua.indexOf("macintosh")!=-1||ua.indexOf("mac os x")!=-1);if(_a&&!_b){try{document.execCommand("BackgroundImageCache",false,true);}catch(e){}}Ext.apply(Ext,{isStrict:_7,SSL_SECURE_URL:"javascript:false",BLANK_IMAGE_URL:"http:/"+"/www.yui-ext.com/s.gif",emptyFn:function(){},applyIf:function(o,c){if(o&&c){for(var p in c){if(typeof o[p]=="undefined"){o[p]=c[p];}}}return o;},id:function(el,_14){_14=_14||"ext-gen";el=Ext.getDom(el);var id=_14+(++_5);return el?(el.id?el.id:(el.id=id)):id;},extend:function(){var io=function(o){for(var m in o){this[m]=o[m];}};return function(sc,sp,_1b){var F=function(){},scp,spp=sp.prototype;F.prototype=spp;scp=sc.prototype=new F();scp.constructor=sc;sc.superclass=spp;if(spp.constructor==Object.prototype.constructor){spp.constructor=sp;}sc.override=function(o){Ext.override(sc,o);};scp.override=io;Ext.override(sc,_1b);return sc;};}(),override:function(_20,_21){if(_21){var p=_20.prototype;for(var _23 in _21){p[_23]=_21[_23];}}},namespace:function(){var a=arguments,o=null,i,j,d,rt;for(i=0;i<a.length;++i){d=a[i].split(".");rt=d[0];eval("if (typeof "+rt+" == \"undefined\"){"+rt+" = {};} o = "+rt+";");for(j=1;j<d.length;++j){o[d[j]]=o[d[j]]||{};o=o[d[j]];}}},urlEncode:function(o){if(!o){return "";}var buf=[];for(var key in o){var _2d=typeof o[key];if(_2d!="function"&&_2d!="object"){buf.push(encodeURIComponent(key),"=",encodeURIComponent(o[key]),"&");}}buf.pop();return buf.join("");},each:function(_2e,fn,_30){if(typeof _2e.length=="undefined"||typeof _2e=="string"){_2e=[_2e];}for(var i=0,len=_2e.length;i<len;i++){if(fn.call(_30||_2e[i],_2e[i],i,_2e)===false){return i;}}},combine:function(){var as=arguments,l=as.length,r=[];for(var i=0;i<l;i++){var a=as[i];if(a instanceof Array){r=r.concat(a);}else{if(a.length!==undefined&&!a.substr){r=r.concat(Array.prototype.slice.call(a,0));}else{r.push(a);}}}return r;},escapeRe:function(s){return s.replace(/([.*+?^${}()|[\]\/\\])/g,"\\$1");},callback:function(cb,_3a,_3b,_3c){if(typeof cb=="function"){if(_3c){cb.defer(_3c,_3a,_3b||[]);}else{cb.apply(_3a,_3b||[]);}}},getDom:function(el){if(!el){return null;}return el.dom?el.dom:(typeof el=="string"?document.getElementById(el):el);},isOpera:_8,isSafari:_9,isIE:_a,isIE7:_b,isGecko:_c,isBorderBox:_d,isWindows:_e,isMac:_f,useShims:((_a&&!_b)||(_c&&_f))});})();Ext.namespace("Ext","Ext.util","Ext.grid","Ext.dd","Ext.tree","Ext.data","Ext.form","Ext.menu","Ext.state","Ext.lib");Ext.apply(Function.prototype,{createCallback:function(){var _3e=arguments;var _3f=this;return function(){return _3f.apply(window,_3e);};},createDelegate:function(obj,_41,_42){var _43=this;return function(){var _44=_41||arguments;if(_42===true){_44=Array.prototype.slice.call(arguments,0);_44=_44.concat(_41);}else{if(typeof _42=="number"){_44=Array.prototype.slice.call(arguments,0);var _45=[_42,0].concat(_41);Array.prototype.splice.apply(_44,_45);}}return _43.apply(obj||window,_44);};},defer:function(_46,obj,_48,_49){var fn=this.createDelegate(obj,_48,_49);if(_46){return setTimeout(fn,_46);}fn();return 0;},createSequence:function(fcn,_4c){if(typeof fcn!="function"){return this;}var _4d=this;return function(){var _4e=_4d.apply(this||window,arguments);fcn.apply(_4c||this||window,arguments);return _4e;};},createInterceptor:function(fcn,_50){if(typeof fcn!="function"){return this;}var _51=this;return function(){fcn.target=this;fcn.method=_51;if(fcn.apply(_50||this||window,arguments)===false){return;}return _51.apply(this||window,arguments);};}});Ext.applyIf(String,{escape:function(_52){return _52.replace(/('|\\)/g,"\\$1");},leftPad:function(val,_54,ch){var _56=new String(val);if(ch==null){ch=" ";}while(_56.length<_54){_56=ch+_56;}return _56;},format:function(_57){var _58=Array.prototype.slice.call(arguments,1);return _57.replace(/\{(\d+)\}/g,function(m,i){return _58[i];});}});String.prototype.toggle=function(_5b,_5c){return this==_5b?_5c:_5b;};Ext.applyIf(Number.prototype,{constrain:function(min,max){return Math.min(Math.max(this,min),max);}});Ext.applyIf(Array.prototype,{indexOf:function(o){for(var i=0,len=this.length;i<len;i++){if(this[i]==o){return i;}}return -1;},remove:function(o){var _63=this.indexOf(o);if(_63!=-1){this.splice(_63,1);}}});Date.prototype.getElapsed=function(_64){return Math.abs((_64||new Date()).getTime()-this.getTime());};

(function(){var _1;Ext.lib.Dom={getViewWidth:function(_2){return _2?this.getDocumentWidth():this.getViewportWidth();},getViewHeight:function(_3){return _3?this.getDocumentHeight():this.getViewportHeight();},getDocumentHeight:function(){var _4=(document.compatMode!="CSS1Compat")?document.body.scrollHeight:document.documentElement.scrollHeight;return Math.max(_4,this.getViewportHeight());},getDocumentWidth:function(){var _5=(document.compatMode!="CSS1Compat")?document.body.scrollWidth:document.documentElement.scrollWidth;return Math.max(_5,this.getViewportWidth());},getViewportHeight:function(){var _6=self.innerHeight;var _7=document.compatMode;if((_7||Ext.isIE)&&!Ext.isOpera){_6=(_7=="CSS1Compat")?document.documentElement.clientHeight:document.body.clientHeight;}return _6;},getViewportWidth:function(){var _8=self.innerWidth;var _9=document.compatMode;if(_9||Ext.isIE){_8=(_9=="CSS1Compat")?document.documentElement.clientWidth:document.body.clientWidth;}return _8;},isAncestor:function(p,c){p=Ext.getDom(p);c=Ext.getDom(c);if(!p||!c){return false;}if(p.contains&&!Ext.isSafari){return p.contains(c);}else{if(p.compareDocumentPosition){return !!(p.compareDocumentPosition(c)&16);}else{var _c=c.parentNode;while(_c){if(_c==p){return true;}else{if(!_c.tagName||_c.tagName.toUpperCase()=="HTML"){return false;}}_c=_c.parentNode;}return false;}}},getRegion:function(el){return Ext.lib.Region.getRegion(el);},getY:function(el){return this.getXY(el)[1];},getX:function(el){return this.getXY(el)[0];},getXY:function(el){var p,pe,b,_14,bd=document.body;el=Ext.getDom(el);if(el.getBoundingClientRect){b=el.getBoundingClientRect();_14=fly(document).getScroll();return [b.left+_14.left,b.top+_14.top];}else{var x=el.offsetLeft,y=el.offsetTop;p=el.offsetParent;var _18=false;if(p!=el){while(p){x+=p.offsetLeft;y+=p.offsetTop;if(Ext.isSafari&&!_18&&fly(p).getStyle("position")=="absolute"){_18=true;}if(Ext.isGecko){pe=fly(p);var bt=parseInt(pe.getStyle("borderTopWidth"),10)||0;var bl=parseInt(pe.getStyle("borderLeftWidth"),10)||0;x+=bl;y+=bt;if(p!=el&&pe.getStyle("overflow")!="visible"){x+=bl;y+=bt;}}p=p.offsetParent;}}if(Ext.isSafari&&(_18||fly(el).getStyle("position")=="absolute")){x-=bd.offsetLeft;y-=bd.offsetTop;}}p=el.parentNode;while(p&&p!=bd){if(!Ext.isOpera||(Ext.isOpera&&p.tagName!="TR"&&fly(p).getStyle("display")!="inline")){x-=p.scrollLeft;y-=p.scrollTop;}p=p.parentNode;}return [x,y];},setXY:function(el,xy){el=Ext.fly(el,"_setXY");el.position();var pts=el.translatePoints(xy);if(xy[0]!==false){el.dom.style.left=pts.left+"px";}if(xy[1]!==false){el.dom.style.top=pts.top+"px";}},setX:function(el,x){this.setXY(el,[x,false]);},setY:function(el,y){this.setXY(el,[false,y]);}};Ext.lib.Event={getPageX:function(e){return Event.pointerX(e.browserEvent||e);},getPageY:function(e){return Event.pointerY(e.browserEvent||e);},getXY:function(e){e=e.browserEvent||e;return [Event.pointerX(e),Event.pointerY(e)];},getTarget:function(e){return Event.element(e.browserEvent||e);},resolveTextNode:function(_26){if(_26&&3==_26.nodeType){return _26.parentNode;}else{return _26;}},getRelatedTarget:function(ev){ev=ev.browserEvent||ev;var t=ev.relatedTarget;if(!t){if(ev.type=="mouseout"){t=ev.toElement;}else{if(ev.type=="mouseover"){t=ev.fromElement;}}}return this.resolveTextNode(t);},on:function(el,_2a,fn){Event.observe(el,_2a,fn,false);},un:function(el,_2d,fn){Event.stopObserving(el,_2d,fn,false);},purgeElement:function(el){},preventDefault:function(e){e=e.browserEvent||e;if(e.preventDefault){e.preventDefault();}else{e.returnValue=false;}},stopPropagation:function(e){e=e.browserEvent||e;if(e.stopPropagation){e.stopPropagation();}else{e.cancelBubble=true;}},stopEvent:function(e){Event.stop(e.browserEvent||e);},onAvailable:function(el,fn,_35,_36){var _37=new Date(),iid;var f=function(){if(_37.getElapsed()>10000){clearInterval(iid);}var el=document.getElementById(id);if(el){clearInterval(iid);fn.call(_35||window,el);}};iid=setInterval(f,50);}};Ext.lib.Ajax=function(){var _3b=function(cb){return cb.success?function(xhr){cb.success.call(cb.scope||window,{responseText:xhr.responseText,responseXML:xhr.responseXML,argument:cb.argument});}:Ext.emptyFn;};var _3e=function(cb){return cb.failure?function(xhr){cb.failure.call(cb.scope||window,{responseText:xhr.responseText,responseXML:xhr.responseXML,argument:cb.argument});}:Ext.emptyFn;};return {request:function(_41,uri,cb,_44){new Ajax.Request(uri,{method:_41,parameters:_44||"",timeout:cb.timeout,onSuccess:_3b(cb),onFailure:_3e(cb)});},formRequest:function(_45,uri,cb,_48,_49,_4a){new Ajax.Request(uri,{method:"POST",parameters:Form.serialize(_45,true),timeout:cb.timeout,onSuccess:_3b(cb),onFailure:_3e(cb)});},isCallInProgress:function(_4b){return false;},abort:function(_4c){return false;}};}();Ext.lib.Anim=function(){var _4d={easeOut:function(pos){return 1-Math.pow(1-pos,2);},easeIn:function(pos){return 1-Math.pow(1-pos,2);}};var _50=function(cb,_52){return {stop:function(_53){this.effect.cancel();},isAnimated:function(){return this.effect.state=="running";},proxyCallback:function(){Ext.callback(cb,_52);}};};return {scroll:function(el,_55,_56,_57,cb,_59){var _5a=_50(cb,_59);el=Ext.getDom(el);el.scrollLeft=_55.to[0];el.scrollTop=_55.to[1];_5a.proxyCallback();return _5a;},motion:function(el,_5c,_5d,_5e,cb,_60){return this.run(el,_5c,_5d,_5e,cb,_60);},color:function(el,_62,_63,_64,cb,_66){return this.run(el,_62,_63,_64,cb,_66);},run:function(el,_68,_69,_6a,cb,_6c,_6d){var o={};for(var k in _68){switch(k){case "points":var by,pts,e=Ext.fly(el,"_animrun");e.position();if(by=_68.points.by){var xy=e.getXY();pts=e.translatePoints([xy[0]+by[0],xy[1]+by[1]]);}else{pts=e.translatePoints(_68.points.to);}o.left=pts.left+"px";o.top=pts.top+"px";break;case "width":o.width=_68.width.to+"px";break;case "height":o.height=_68.height.to+"px";break;case "opacity":o.opacity=String(_68.opacity.to);break;default:o[k]=String(_68[k].to);break;}}var _74=_50(cb,_6c);_74.effect=new Effect.Morph(Ext.id(el),{duration:_69,afterFinish:_74.proxyCallback,transition:_4d[_6a]||Effects.Transitions.linear,style:o});return _74;}};}();function fly(el){if(!_1){_1=new Ext.Element.Flyweight();}_1.dom=el;return _1;}Ext.lib.Region=function(t,r,b,l){this.top=t;this[1]=t;this.right=r;this.bottom=b;this.left=l;this[0]=l;};Ext.lib.Region.prototype={contains:function(_7a){return (_7a.left>=this.left&&_7a.right<=this.right&&_7a.top>=this.top&&_7a.bottom<=this.bottom);},getArea:function(){return ((this.bottom-this.top)*(this.right-this.left));},intersect:function(_7b){var t=Math.max(this.top,_7b.top);var r=Math.min(this.right,_7b.right);var b=Math.min(this.bottom,_7b.bottom);var l=Math.max(this.left,_7b.left);if(b>=t&&r>=l){return new Ext.lib.Region(t,r,b,l);}else{return null;}},union:function(_80){var t=Math.min(this.top,_80.top);var r=Math.max(this.right,_80.right);var b=Math.max(this.bottom,_80.bottom);var l=Math.min(this.left,_80.left);return new Ext.lib.Region(t,r,b,l);},adjust:function(t,l,b,r){this.top+=t;this.left+=l;this.right+=r;this.bottom+=b;return this;}};Ext.lib.Region.getRegion=function(el){var p=Ext.lib.Dom.getXY(el);var t=p[1];var r=p[0]+el.offsetWidth;var b=p[1]+el.offsetHeight;var l=p[0];return new Ext.lib.Region(t,r,b,l);};Ext.lib.Point=function(x,y){if(x instanceof Array){y=x[1];x=x[0];}this.x=this.right=this.left=this[0]=x;this.y=this.top=this.bottom=this[1]=y;};Ext.lib.Point.prototype=new Ext.lib.Region();if(Ext.isIE){Event.observe(window,"unload",function(){var p=Function.prototype;delete p.createSequence;delete p.defer;delete p.createDelegate;delete p.createCallback;delete p.createInterceptor;});}})();
