Ext.menu.MenuMgr=function(){var f,d,c={},a=false,k=new Date();function m(){f={};d=new Ext.util.MixedCollection();Ext.getDoc().addKeyListener(27,function(){if(d.length>0){h()}})}function h(){if(d&&d.length>0){var n=d.clone();n.each(function(o){o.hide()})}}function e(n){d.remove(n);if(d.length<1){Ext.getDoc().un("mousedown",l);a=false}}function j(n){var o=d.last();k=new Date();d.add(n);if(!a){Ext.getDoc().on("mousedown",l);a=true}if(n.parentMenu){n.getEl().setZIndex(parseInt(n.parentMenu.getEl().getStyle("z-index"),10)+3);n.parentMenu.activeChild=n}else{if(o&&o.isVisible()){n.getEl().setZIndex(parseInt(o.getEl().getStyle("z-index"),10)+3)}}}function b(n){if(n.activeChild){n.activeChild.hide()}if(n.autoHideTimer){clearTimeout(n.autoHideTimer);delete n.autoHideTimer}}function g(n){var o=n.parentMenu;if(!o&&!n.allowOtherMenus){h()}else{if(o&&o.activeChild){o.activeChild.hide()}}}function l(n){if(k.getElapsed()>50&&d.length>0&&!n.getTarget(".x-menu")){h()}}function i(o,r){if(r){var q=c[o.group];for(var p=0,n=q.length;p<n;p++){if(q[p]!=o){q[p].setChecked(false)}}}}return{hideAll:function(){h()},register:function(o){if(!f){m()}f[o.id]=o;o.on("beforehide",b);o.on("hide",e);o.on("beforeshow",g);o.on("show",j);var n=o.group;if(n&&o.events.checkchange){if(!c[n]){c[n]=[]}c[n].push(o);o.on("checkchange",onCheck)}},get:function(n){if(typeof n=="string"){if(!f){return null}return f[n]}else{if(n.events){return n}else{if(typeof n.length=="number"){return new Ext.menu.Menu({items:n})}else{return new Ext.menu.Menu(n)}}}},unregister:function(o){delete f[o.id];o.un("beforehide",b);o.un("hide",e);o.un("beforeshow",g);o.un("show",j);var n=o.group;if(n&&o.events.checkchange){c[n].remove(o);o.un("checkchange",onCheck)}},registerCheckable:function(n){var o=n.group;if(o){if(!c[o]){c[o]=[]}c[o].push(n);n.on("beforecheckchange",i)}},unregisterCheckable:function(n){var o=n.group;if(o){c[o].remove(n);n.un("beforecheckchange",i)}},getCheckedItem:function(p){var q=c[p];if(q){for(var o=0,n=q.length;o<n;o++){if(q[o].checked){return q[o]}}}return null},setCheckedItem:function(p,r){var q=c[p];if(q){for(var o=0,n=q.length;o<n;o++){if(q[o].id==r){q[o].setChecked(true)}}}return null}}}();