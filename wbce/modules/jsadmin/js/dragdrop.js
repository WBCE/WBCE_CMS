// Copyright 2006 Stepan Riha
// www.nonplus.net
// $Id: dragdrop.js 2 2006-04-18 03:04:39Z stepan $
/**
* -----------------------------------------------------------------------------------------
*  MODIFICATON FOR THE JSADMIN MODULE
* -----------------------------------------------------------------------------------------
*	MODIFICATION HISTORY:
*   Swen Uth; 01/24/2008
*   +INCLUDE VARIABLE buttonCell FOR ADAPTATION TO LATER LAYOUTS
*
**/
JsAdmin.DD = {};
JsAdmin.movable_rows = {};

JsAdmin.init_drag_drop = function() {

	// There seems to be many different ways the ordering is set up
	//		pages/index.php has UL/LI containing tables with single row
	//		pages/sections.php has a TABLE with many rows
	//		pages/modify.php for manuals is completely weird...
	// So we only want to deal with pages & sections...

	var page_type = '';
	var is_tree = false;

	if(document.URL.indexOf(JsAdmin.ADMIN_URL + "/pages/index.php") > -1) {
		page_type = 'pages';
		is_tree = true;

		// This page uses duplicate IDs and incorrectly nested lists:
		// <ul id="p1">
		//		<li id="p1"><table /></li>
		//		<ul>... sub items ...</ul>
		// </ul>
		//
		// We need to fix that to the following:
		// <ul id="p1">
		//		<li id="uniqueID"><table />
		//		<ul>... sub items ...</ul>
		//		</li>
		// </ul>

		// Stash all UL ids
		var ids = {};
		var lists = document.getElementsByTagName('ul');
		for(var i = 0; i < lists.length; i++) {
			if(lists[i].id) {
				ids[lists[i].id] = true;
			}
		}

		// Now fix all LIs
		var items = document.getElementsByTagName('li');
 		for(var i = 0; i < items.length; i++) {
			var item = items[i];

			// Fix duplicate ID
			if(ids[item.id]) {
				item.id =  JsAdmin.util.getUniqueId();
			}

			// Fix UL parented by UL
			var ul = JsAdmin.util.getNextSiblingNode(item, 'ul');
			if(ul) {
				var lis = ul.getElementsByTagName('li');
 				if(!lis || lis.length == 0) {
					// Remove list without items
					ul.parentNode.removeChild(ul);
				} else {
					// Make list child of list item
					item.appendChild(ul);
				}
			}
		}

	} else if(document.URL.indexOf("/admin/pages/sections.php") > 0) {
		page_type = 'sections';
	} else if(document.URL.indexOf("/admin/pages/modify.php") > 0) {
		page_type = 'modules';
		is_tree = true;
		// Stash all UL ids
		var ids = {};
		var lists = document.getElementsByTagName('ul');
		for(var i = 0; i < lists.length; i++) {
			if(lists[i].id) {
				ids[lists[i].id] = true;
			}
		}

		// Now fix all LIs
		var items = document.getElementsByTagName('li');
 		for(var i = 0; i < items.length; i++) {
			var item = items[i];

			// Fix duplicate ID
			if(ids[item.id]) {
				item.id =  JsAdmin.util.getUniqueId();
			}

			// Fix UL parented by UL
			var ul = JsAdmin.util.getNextSiblingNode(item, 'ul');
			if(ul) {
				var lis = ul.getElementsByTagName('li');
 				if(!lis || lis.length == 0) {
					// Remove list without items
					ul.parentNode.removeChild(ul);
				} else {
					// Make list child of list item
					item.appendChild(ul);
				}
			}
		}

	} else {
		// We don't do any other pages
		return;
		// page_type = 'modules';
	}

	var links = document.getElementsByTagName('a');
	var reImg = /(.*)move_(down|up)\.php(.*)/;

	for(var i = 0; i < links.length; i++) {
		var link = links[i];
		var href = link.href || '';
		var match = href.match(reImg);
		if(!match) {
			continue;
		}
		var url = match[1];
		var op = match[2];
		var params = match[3];
		var tr = JsAdmin.util.getAncestorNode(link, 'tr');
		var item = is_tree ? JsAdmin.util.getAncestorNode(tr, 'li') : tr;
		if(!item) {
			continue;
		}

		// Make sure we have a unique id
		if(!item.id || YAHOO.util.Dom.get(item.id) != item) {
			item.id = JsAdmin.util.getUniqueId();
		}

		if(is_tree) {
			var parent = JsAdmin.util.getAncestorNode(item, 'ul');
			new JsAdmin.DD.liDDSwap(item.id, (parent && parent.id) ? parent.id : 'top');
		} else {
			new JsAdmin.DD.trDDSwap(item.id);
		}
		item.className += " jsadmin_drag";

		this.movable_rows[item.id] = { item: item, tr : tr, url : url, params : params };
	}
};

//==========================================================================
// Drag-drop utils
//==========================================================================

JsAdmin.DD.dragee = null;

JsAdmin.DD.addMoveButton = function(tr, cell, op) {
	if(op == 'down') {
		cell++;
	}
	var item = JsAdmin.movable_rows[tr.id];
	if(!JsAdmin.util.isNodeType(tr, 'tr')) {
		var rows = tr.getElementsByTagName('tr');
		tr = rows[0];
	}

	var html = '<a href="' + item.url + 'move_' + op + '.php' + item.params
				+ '"><img src="' + JsAdminTheme.THEME_URL + '/images/' + op
				+ '_16.png" border="0" alt="' + op + '" /></a>';
	tr.cells[cell].innerHTML = html;
};

JsAdmin.DD.deleteMoveButton = function(tr, cell, op) {
	if(op == 'down') {
		cell++;
	}
	if(!JsAdmin.util.isNodeType(tr, 'tr')) {
		var rows = tr.getElementsByTagName('tr');
		tr = rows[0];
	}
	
	tr.cells[cell].innerHTML = "";
};

//==========================================================================
// Drag-drop handling for table rows
//==========================================================================

JsAdmin.DD.trDDSwap = function(id, sGroup) {
    this.init(id, sGroup);
	this.addInvalidHandleType('a');
	this.addInvalidHandleType('input');
	this.addInvalidHandleType('select');
    this.initFrame();
	this.buttonCell = buttonCell;//, by Swen Uth
	
	// For Connection
	this.scope = this;
};

JsAdmin.DD.trDDSwap.prototype = new YAHOO.util.DDProxy();

JsAdmin.DD.trDDSwap.prototype.startDrag = function(x, y) {
	if (JsAdmin.DD.dragee != this) {
		this.rowIndex = this.getEl().rowIndex;
		this.numRows = this.getEl().parentNode.rows.length;
		this.opacity = YAHOO.util.Dom.getStyle(this.getEl(), "opacity");
		this.background = YAHOO.util.Dom.getStyle(this.getEl(), "background");
		YAHOO.util.Dom.setStyle(this.getEl(), "opacity", 0.5);
		YAHOO.util.Dom.setStyle(this.getEl(), "background", "transparent");
	}
	JsAdmin.DD.dragee = this;
};

JsAdmin.DD.trDDSwap.prototype.onDragEnter = function(e, id) {
  var elt = id ? YAHOO.util.Dom.get(id) : null;
	var item = JsAdmin.movable_rows[this.getEl().id];
	var rows = item.tr.parentNode.rows;
	var wasFirst = item.tr.rowIndex == 1;
	var wasLast = item.tr.rowIndex == this.numRows - 2;
	if(elt.rowIndex < item.tr.rowIndex) {
		elt.parentNode.insertBefore(item.tr, elt);
	} else {
		elt.parentNode.insertBefore(elt, item.tr);
	}
	// Fixup buttons
	var isFirst = item.tr.rowIndex == 1;
	var isLast = item.tr.rowIndex == this.numRows - 2;

	if(wasFirst != isFirst) {
		if(isFirst) {
			JsAdmin.DD.deleteMoveButton(item.tr, this.buttonCell, 'up');
			JsAdmin.DD.addMoveButton(JsAdmin.util.getNextSiblingNode(item.tr), this.buttonCell, 'up');
		} else {
			JsAdmin.DD.addMoveButton(item.tr, this.buttonCell, 'up');
			JsAdmin.DD.deleteMoveButton(rows[1], this.buttonCell, 'up');
		}
	}
	if(wasLast != isLast) {
		if(isLast) {
			JsAdmin.DD.deleteMoveButton(item.tr, this.buttonCell, 'down');
			JsAdmin.DD.addMoveButton(JsAdmin.util.getPreviousSiblingNode(item.tr), this.buttonCell, 'down');
		} else {
			JsAdmin.DD.addMoveButton(item.tr, this.buttonCell, 'down');
			JsAdmin.DD.deleteMoveButton(rows[rows.length-2], this.buttonCell, 'down');
		}
	}

	this.DDM.refreshCache(this.groups);
};

JsAdmin.DD.trDDSwap.prototype.endDrag = function(e) {
	YAHOO.util.Dom.setStyle(this.getEl(), "opacity", this.opacity);
	YAHOO.util.Dom.setStyle(this.getEl(), "background", "#f0f0f0");
	
	JsAdmin.DD.dragee = null;

	var newIndex = this.getEl().rowIndex;
	if(newIndex != this.rowIndex) {
		var url = JsAdmin.WB_URL + "/modules/jsadmin/move_to.php";
		url += JsAdmin.movable_rows[this.getEl().id].params + "&position=" + newIndex;
		document.body.className = String(document.body.className).replace(/(\s*)jsadmin_([a-z]+)/g, "$1") + " jsadmin_busy";
		YAHOO.util.Connect.asyncRequest('GET', url, this, null);
	}
};

JsAdmin.DD.trDDSwap.prototype.success = function(o) {
	document.body.className = String(document.body.className).replace(/(\s*)jsadmin_([a-z]+)/g, "$1") + " jsadmin_success";
};

JsAdmin.DD.trDDSwap.prototype.failure = function(o) {
	document.body.className = String(document.body.className).replace(/(\s*)jsadmin_([a-z]+)/, "$1") + " jsadmin_failure";
};

//==========================================================================
// Drag-drop handling for list items
//==========================================================================

JsAdmin.DD.liDDSwap = function(id, sGroup) {
    this.init(id, sGroup);
	this.addInvalidHandleType('a');
	this.addInvalidHandleType('input');
	this.addInvalidHandleType('select');
    this.initFrame();
 this.buttonCell = buttonCell;//, by Swen Uth
	this.counter = 0;
};

JsAdmin.DD.liDDSwap.prototype = new YAHOO.util.DDProxy();

JsAdmin.DD.liDDSwap.prototype.startDrag = function(x, y) {
	// On IE, startDrag is sometimes called twice
	if(JsAdmin.DD.dragee && JsAdmin.DD.dragee != this) {
		JsAdmin.DD.dragee.endDrag(null);
	}
	if(JsAdmin.DD.dragee != this) {
		this.rowIndex = JsAdmin.util.getItemIndex(this.getEl());
		this.opacity = YAHOO.util.Dom.getStyle(this.getEl(), "opacity");
		this.background = YAHOO.util.Dom.getStyle(this.getEl(), "background");
		YAHOO.util.Dom.setStyle(this.getEl(), "opacity", 0.5);

		this.list = JsAdmin.util.getAncestorNode(this.getEl(), "ul");
		this.list.className += " jsadmin_drag_area";
	}
	JsAdmin.DD.dragee = this;
};

JsAdmin.DD.liDDSwap.prototype.onDragEnter = function(e, id) {
	// Swap with other element
	var elt = id ? YAHOO.util.Dom.get(id) : null;
	var item = JsAdmin.movable_rows[this.getEl().id];
	var eltRowIndex = JsAdmin.util.getItemIndex(elt);
	var rowIndex = JsAdmin.util.getItemIndex(this.getEl());
	var wasFirst = !JsAdmin.util.getPreviousSiblingNode(this.getEl());
	var wasLast = !JsAdmin.util.getNextSiblingNode(this.getEl());

	if(eltRowIndex < rowIndex) {
		elt.parentNode.insertBefore(this.getEl(), elt);
	} else {
		elt.parentNode.insertBefore(elt, this.getEl());
	}
	// Fixup buttons
	var isFirst = !JsAdmin.util.getPreviousSiblingNode(this.getEl());
	var isLast = !JsAdmin.util.getNextSiblingNode(this.getEl());

	if(wasFirst != isFirst) {
		if(isFirst) {
			JsAdmin.DD.deleteMoveButton(item.tr, this.buttonCell, 'up');
			JsAdmin.DD.addMoveButton(JsAdmin.util.getNextSiblingNode(item.item), this.buttonCell, 'up');
		} else {
			JsAdmin.DD.addMoveButton(item.item, this.buttonCell, 'up');
			var first, prev = JsAdmin.util.getPreviousSiblingNode(item.item);
			while(prev) {
				first = prev;
				prev = JsAdmin.util.getPreviousSiblingNode(prev);
			}
			JsAdmin.DD.deleteMoveButton(JsAdmin.movable_rows[first.id].tr, this.buttonCell, 'up');
		}
	}
	if(wasLast != isLast) {
		if(isLast) {
			JsAdmin.DD.deleteMoveButton(item.tr, this.buttonCell, 'down');
			JsAdmin.DD.addMoveButton(JsAdmin.util.getPreviousSiblingNode(item.item), this.buttonCell, 'down');
		} else {
			JsAdmin.DD.addMoveButton(item.item, this.buttonCell, 'down');
			var last, next = JsAdmin.util.getNextSiblingNode(item.item);
			while(next) {
				last = next;
				next = JsAdmin.util.getNextSiblingNode(next);
			}
			JsAdmin.DD.deleteMoveButton(JsAdmin.movable_rows[last.id].tr, this.buttonCell, 'down');
		}
	}

	this.DDM.refreshCache(this.groups);
};

JsAdmin.DD.liDDSwap.prototype.endDrag = function(e) {
	YAHOO.util.Dom.setStyle(this.getEl(), "opacity", this.opacity);
	this.list.className = String(this.list.className).replace(/(\s*)jsadmin_([a-z]+)/g, "$1");
	JsAdmin.DD.dragee = null;
	var newIndex = JsAdmin.util.getItemIndex(this.getEl());
	if(newIndex != this.rowIndex) {
		var url = JsAdmin.WB_URL + "/modules/jsadmin/move_to.php";
		url += JsAdmin.movable_rows[this.getEl().id].params + "&position=" + (newIndex+1);
		document.body.className = String(document.body.className).replace(/(\s*)jsadmin_([a-z]+)/g, "$1") + " jsadmin_busy";
		YAHOO.util.Connect.asyncRequest('GET', url, this, null);
	}
};

JsAdmin.DD.liDDSwap.prototype.success = function(o) {
	document.body.className = String(document.body.className).replace(/(\s*)jsadmin_([a-z]+)/g, "$1") + " jsadmin_success";
};

JsAdmin.DD.liDDSwap.prototype.failure = function(o) {
	document.body.className = String(document.body.className).replace(/(\s*)jsadmin_([a-z]+)/, "$1") + " jsadmin_failure";
};
