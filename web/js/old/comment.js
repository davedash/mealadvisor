PeriodicalExecuter.prototype.registerCallback = function() {
    this.intervalID = setInterval(this.onTimerEvent.bind(this), this.frequency * 1000);
}

PeriodicalExecuter.prototype.stop = function() {
    clearInterval(this.intervalID);
}

var editor;
var pe;


makeEditable = function(id, url, textUrl, time) {
  var div = $("review_text_" + id);
  
  pe = new PeriodicalExecuter(function() { updateTime(id); }, 1);

  //div.className = div.className + ' editable';
  Element.addClassName($('comment_' + id), 'editable');
  new Insertion.Bottom(div, 
    '<div class="edit_control" id="edit_control_'+id+'">Edit Comment (<span id="time_'+id+'">'+time+' seconds</span>)</div>');
  editor = new Ajax.InPlaceEditor(div, url, { externalControl: 'edit_control_'+id, rows:6, okText: 'Save', cancelText: 'Cancel', 
  loadTextURL: textUrl, onComplete: function() { makeUneditable(id) } });
}

makeUneditable = function(id) {
  var div = $("review_text_" + id);
  
  Element.removeClassName($('comment_' + id), 'editable');
  div.title = null;
  editor.dispose();
  var editLink = $('edit_control_'+id);
  editLink.parentNode.removeChild(editLink);
  
}

updateTime = function(id) {
  var div = $("time_"+id);
  if (div) {
    var time =  parseInt(div.innerHTML) - 1;
    div.innerHTML = time;
  }
  if (time < 1) {
    pe.stop();
    var editLink = $('edit_control_'+id);
    
    if (Element.visible(editLink)) {
      makeUneditable(id);

      editLink.parentNode.removeChild(editLink);
    }
  }
}