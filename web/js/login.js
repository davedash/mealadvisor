// LoginDialog

var LoginDialog = function(){
    
    var dialog, showBtn;
    

    // return a public interface
    return {
        init : function(){
             showBtn = Ext.get('login_button');
             // attach to click event
             if (showBtn) {
               showBtn.on('click', this.showDialog, this);
             }
           },
        hide : function() { dialog.hide() },
        showDialog : function(){
            if(!dialog){ // lazy initialize the dialog and only create it once
                dialog = new Ext.LayoutDialog("login-dlg", { 
                  modal:true,
                  width:600,
                  height:400,
                  shadow:true,
                  minWidth:300,
                  minHeight:300,
                  center: {
                    autoScroll:true,
                    tabPosition: 'bottom',
                    closeOnTab: true,
                    alwaysShowTabs: true
                  }
                });
                dialog.addKeyListener(27, dialog.hide, dialog);
                dialog.addButton('Cancel', dialog.hide, dialog);
                
                var layout = dialog.getLayout();
                dialog.beginUpdate();
  	            
  	            layout.add('center', new Ext.ContentPanel('login_dlg_register', {title: 'Register'}));
  	            layout.add('center', new Ext.ContentPanel('login_dlg_openid', {title: 'Open ID'}));
                layout.add('center', new Ext.ContentPanel('login_dlg_standard', {title: 'Sign In'}));

                  // generate some other tabs
  	            layout.endUpdate();
                dialog.endUpdate();
              }
              dialog.show(showBtn.dom);
              return false;
              
            }
          };
          }();

// using onDocumentReady instead of window.onload initializes the application
// when the DOM is ready, without waiting for images and other resources to load
Ext.EventManager.onDocumentReady(LoginDialog.init, LoginDialog, true);