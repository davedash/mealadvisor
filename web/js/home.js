MA.autocomplete = function()
{
  var e = YAHOO.util.Event;
  var w = YAHOO.widget;

  return {
    init: function()
    {
       e.onAvailable("restaurantInput", this.fnHandler);
    },
    fnHandler: function()
    {
      var rDS = new w.DS_XHR("/ajax/restaurant/list", ["ResultSet.Result","Title"]);

      rDS.maxCacheEntries    = 60; 
      rDS.queryMatchContains = true;

      var rAC = new w.AutoComplete("restaurantInput","restaurantACContainer", rDS); 

      rAC.formatResult = function(item, query) 
      {
        return item[1].Title;
      };
  
      rAC.forceSelection           = true; 
      rAC.allowBrowserAutocomplete = false; 
  
      rAC.itemSelectEvent.subscribe(
        function(sType, aArgs) 
        { 
          var data = aArgs[2];
          document.getElementById("restaurant_id").value = aArgs[2][1]['Id'];
        }
      ); 
    },

  }
}();

MA.autocomplete.init();
