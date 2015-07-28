// Copyright 2006 Stepan Riha
// www.nonplus.net
// $Id: jsadmin.js 2 2006-04-18 03:04:39Z stepan $

// Initialize JsAdmin when page loads
JsAdmin.loadHandler = function(ev, self) {
	if(self.init_tool) {
		self.init_tool();
	}
	if(self.restore_toggled) {
		self.restore_toggled();
		YAHOO.util.Event.addListener(window, 'unload', JsAdmin.unloadHandler, self, false);
	}
	if(self.init_drag_drop) {
		self.init_drag_drop();
	}
};

// Store JsAdmin cookies when page unloads
JsAdmin.unloadHandler = function(ev, self) {
	self.save_toggled();
};

JsAdmin.rowMouseOverHandler = function(ev, tr) {
	YAHOO.util.Dom.setStyle(tr, 'background' ,'#fea');
};

JsAdmin.rowMouseOutHandler = function(ev, tr) {
	YAHOO.util.Dom.setStyle(tr, 'background' ,'');
};

YAHOO.util.Event.addListener(window, 'load', JsAdmin.loadHandler, JsAdmin, false);

JsAdmin.util = {
	createCookie : function(name,value,days)
	{
		if (days)
		{
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	},

	readCookie : function(name)
	{
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++)
		{
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	},

	eraseCookie : function(name)
	{
		this.createCookie(name,"",-1);
	},

	next_id : 1,
	getUniqueId : function () {
		var id;
		do {
			id = 'jsadmin_id_' + this.next_id++;
		} while(YAHOO.util.Dom.get(id));
		return id;
	},
	
	isNodeType : function(elt, type) {
		if(elt) {
			return elt.nodeName.toUpperCase() == type.toUpperCase();
		}
		return false;
	},

	getItemIndex : function (elt) {
		var type = elt.nodeName;
		var index = 0;
		for(var sib = elt.previousSibling; sib; sib = sib.previousSibling) {
			if(sib.nodeName == type) {
				index++;
			}
		}
		return index;
	},
	
	getAncestorNode : function(elt, type) {
		if(elt) {
			if(type) {
				type = type.toUpperCase();
			} else {
				type = elt.nodeName.toUpperCase();
			}
			elt = elt.parentNode;
			while(elt && elt.nodeName.toUpperCase() != type) {
				elt = elt.parentNode;
			}
		}
		return elt;
	},
	
	getNextSiblingNode : function (elt, type) {
		if(elt) {
			if(type) {
				type = type.toUpperCase();
			} else {
				type = elt.nodeName.toUpperCase();
			}
			elt = elt.nextSibling;
			while(elt && elt.nodeName.toUpperCase() != type) {
				elt = elt.nextSibling;
			}
		}
		return elt;
	},

	matchNextSibling : function (elt, re) {
		if(elt) {
			elt = elt.nextSibling;
			while(elt && !elt.nodeName.match(re)) {
				elt = elt.nextSibling;
			}
		}
		return elt;
	},

	getPreviousSiblingNode : function (elt, type) {
		if(elt) {
			if(type) {
				type = type.toUpperCase();
			} else {
				type = elt.nodeName.toUpperCase();
			}
			elt = elt.previousSibling;
			while(elt && elt.nodeName.toUpperCase() != type) {
				elt = elt.previousSibling;
			}
		}
		return elt;
	},

	insertBefore : function(parent, newChild, beforeChild) {
		if(beforeChild) {
			parent.insertBefore(newChild, beforeChild);
		} else {
			parent.appendChild(newChild);
		}
	},

	insertAfter : function(parent, newChild, afterChild) {
		if(afterChild) {
			this.insertBefore(parent, newChild, afterChild.nextSibling);
		} else {
			this.insertBefore(parent, newChild, parent.firstChild);
		}
	}
};
