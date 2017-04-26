// This jQuery plugin will be called on demand from the backend_body.js of this WebsiteBaker Module


/*
 * Dialog plugin Version 1.5
 * Copyright (c) 2008 Chris Winberry
 * Email: transistech@gmail.com
 *
 * Original Design: Michael Leigeber
 * http://www.leigeber.com/2008/04/custom-javascript-dialog-boxes/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

/* Notes on usage:
// ----------------
// Dialog Types:  error, warning, success, prompt, message, query
// width/height = null : Default Size
// width/height = 0    : Auto Size to contents
// buttons == 0 : No Buttons
// buttons == 1 : "Ok"
// buttons == 2 : "Yes"/"No"
// buttons == 3 : "Accept"/"Cancel"
// buttons == 4 : "User defined 1"/"User defined 2" (button 2 is optional)
// Function Parameters:
// --------------------
// showDialog(title, message, type, width, height, autohide, atcursor, modal, buttons, button1, button2)
*/

// global variables //
var TIMER = 5;
var SPEED = 1000;
var WRAPPER = '';  // defaults to document.body
var DLGRESULT = null;
var ALLOWHIDE = true;  // Support for a persistent dialog
var DEFAULT_WIDTH = 425;
var DEFAULT_CONTENT_HEIGHT = 160;

// sniff browser, (content shrink-wrapping doesn't work for IE)
var isIE = (navigator.appName == 'Microsoft Internet Explorer');

// Add an onMouseMove event to track mouse position
if (window.attachEvent) document.attachEvent("onmousemove",MouseMv);
else document.addEventListener("mousemove",MouseMv,false);

function MouseMv(e)
{
 if (!e) e = window.event;
 if (typeof e.pageX == "number") MouseMv.X = e.pageX;
 else MouseMv.X = e.clientX;

 if (typeof e.pageY == "number") MouseMv.Y = e.pageY;
 else MouseMv.Y = e.clientY;
}
MouseMv.X = 0;
MouseMv.Y = 0;

// check if argument is null, not defined, or of the specified type
function isDef(arg, argtype)
{
 try
 {
  if (argtype === null || typeof(argtype) == 'undefined') return (arg !== null && typeof(arg) != 'undefined');
  return (arg !== null && typeof(arg) == argtype);
 }
 catch (e) {}
 return false;
}

// wrapper for getElementById
function el(id)
{
 try
 {
  if (!isDef(id, 'string') || id == '') return null;
  return document.getElementById(id);
 }
 catch(e) {}
 return null;
}

// get an element's rendered visible content's width
function getWidth(elem)
{
 try
 {
  if (!isDef(elem)) return 0;
  if (elem.style.display == 'none') return 0;
  return elem.innerWidth ? elem.innerWidth :elem.clientWidth ? elem.clientWidth : elem.offsetWidth;
 }
 catch(e) {}

 return 0;
}

// get an element's rendered visible content's height
function getHeight(elem)
{
 try
 {
  if (!isDef(elem)) return 0;
  if (elem.style.display == 'none') return 0;
  return elem.innerHeight ? elem.innerHeight :elem.clientHeight ? elem.clientHeight : elem.offsetHeight;
 }
 catch(e) {}

 return 0;
}

// get scrolled page size
function getScrollSize()
{
 try
 {
  var db = document.body;
  var dde = document.documentElement;

  var scrollHeight = Math.max(db.scrollHeight, dde.scrollHeight, db.offsetHeight, dde.offsetHeight, db.clientHeight, dde.clientHeight)
  var scrollWidth = Math.max(db.scrollWidth, dde.scrollWidth, db.offsetWidth, dde.offsetWidth, db.clientWidth, dde.clientWidth)

  return {x: scrollWidth, y: scrollHeight};
 }
 catch(e) {}

 return {x: 0, y: 0};
}

// get scrolled page position
function getScrollPos()
{
 try
 {
  if (typeof(pageXOffset) != 'undefined') {return {x: pageXOffset, y: pageYOffset};}  // IE throws an exception if I use "!isDef(pageXOffset)"
  else
  {
   var db = document.body;
   var dde = document.documentElement;
   return ((dde.clientHeight) ? {x: dde.scrollLeft, y: dde.scrollTop} : {x: db.scrollLeft, y: db.scrollTop});
  }
 }
 catch(e) {}

 return {x: 0, y: 0};
}

// build/show the dialog box, populate the data and call the fadeDialog function //
function showDialog(title, message, type, width, height, autohide, atcursor, modal, buttons, button1, button2)
{
 var dialog;
 var dialogheader;
 var dialogclose;
 var dialogtitle;
 var dialogbody;
 var dialogcontent;
 var dialogbar;
 var dialogmask;
 DLGRESULT = null;  // Set this as soon as possible to avoid a background polling function reading a previously set value

 if(!isDef(type)) {type = 'error';}
// if(!isDef(width)) {width = 0;}
// if(!isDef(height)) {height = 0;}
 if(!isDef(autohide)) {autohide = false;}
 if(!isDef(atcursor)) {atcursor = false;}
 if(!isDef(buttons)) {buttons = 0;}
 if(!isDef(modal)) {modal = true;}

 switch (buttons)
 {
  case 1: button1 = 'Ok'; break;
  case 2: button1 = 'Yes'; button2 = 'No'; break;
  case 3: button1 = 'Accept'; button2 = 'Cancel'; break;
  case 4: if (!isDef(button1)) {button1 = 'Ok'; buttons = 1;} if (!isDef(button2)) {buttons = 1;} break;  // Accepts a single user-defined button, (defaults to "Ok")
  default: button1 = 'Yes'; button2 = 'No'; break;
 }


 if (!el('dialog'))
 {
  dialog = document.createElement('div');
  dialog.id = 'dialog';
  dialogheader = document.createElement('div');
  dialogheader.id = 'dialog_header';
  dialogtitle = document.createElement('div');
  dialogtitle.id = 'dialog_title';
  dialogclose = document.createElement('div');
  dialogclose.id = 'dialog_close'
  dialogbody = document.createElement('div');
  dialogbody.id = 'dialog_body';
  dialogcontent = document.createElement('div');
  dialogcontent.id = 'dialog_content';
  dialogbar = document.createElement('div');
  dialogbar.id = 'dialog_bar';
  dialogmask = document.createElement('iframe');  // Fix for IE bug not overlaying <select> tags
  dialogmask.id = 'dialog_mask';
  dialogmask.style.display = 'none';  // Hide the mask before appending it to prevent flicker
  document.body.appendChild(dialogmask);
  document.body.appendChild(dialog);
  dialog.appendChild(dialogheader);
  dialogheader.appendChild(dialogtitle);
  dialogheader.appendChild(dialogclose);
  dialog.appendChild(dialogbody);
  dialogbody.appendChild(dialogcontent);
  dialogbody.appendChild(dialogbar);

  dialogclose.setAttribute('onclick','hideDialog()');
  dialogclose.onclick = hideDialog;

  dialogmask.setAttribute('frameborder', 0);
  var doc = dialogmask.contentDocument || dialogmask.contentWindow.document;
  doc.open();
  doc.writeln('<html><head></head><body style="margin: 0px"></body></html>');  // Create some mask content so that it can support an onclick event handler
  doc.close();
  doc.body.onclick = hideDialog;
 }
 else
 {
  dialog = el('dialog');
  dialogheader = el('dialog_header');
  dialogtitle = el('dialog_title');
  dialogclose = el('dialog_close');
  dialogbody = el('dialog_body');
  dialogcontent = el('dialog_content');
  dialogbar = el('dialog_bar');
  dialogmask = el('dialog_mask');
  dialog.style.visibility = "visible";
 }

  if (modal) dialogmask.style.display = 'block';

 dialogbar.style.display = ((buttons>0)?'block':'none');

 dialog.style.opacity = .00;
 dialog.style.filter = 'alpha(opacity=0)';
 dialog.alpha = 0;

 var content = el(WRAPPER);
 if (!isDef(content)) // WRAPPER should contain a valid id of a div or similar element which contains the page content
 {
  var docElem = document.documentElement;
  var docBody = document.body;
  content = ((docElem.clientHeight) ? docElem : docBody);
 }

 dialog.className = type + "border";

 dialogheader.className = type + "header";
 dialogtitle.innerHTML = title;

 dialogbody.className = type + 'body';

 dialogbar.className = type + "border";

 dialogcontent.innerHTML = message;
 if (type == 'message') dialogcontent.style.overflow = 'auto';

 if (buttons > 0)
 {
  if (buttons == 1) {dialogbar.style.textAlign = 'center';}
  else {dialogbar.style.textAlign = 'right';}
  dialogbar.innerHTML = '<button type="button" onclick="hideDialog(1);">'+button1+'</button>';
  if (buttons > 1) dialogbar.innerHTML += '&nbsp;&nbsp;<button type="button" onclick="hideDialog(2);">'+button2+'</button>';
 }

 if (!isDef(width) || (isIE && width == 0)) dialog.style.width = DEFAULT_WIDTH + 'px';  // default setting (IE auto doesn't work so set to default)
 else
 {
  if (width == 0) dialog.style.width = 'auto';  // auto setting
  else dialog.style.width = width + 'px';
 }

 if (!isDef(height) || (isIE && height == 0))  // default setting (IE auto doesn't work so set to default)
 {
  dialogcontent.style.height = DEFAULT_CONTENT_HEIGHT + 'px';
  dialog.style.height = (getHeight(dialogheader) + DEFAULT_CONTENT_HEIGHT + (isIE?0:13) + getHeight(dialogbar)) + 'px';  // + 2 * 6px content padding + 2 * 1px bar border
 }
 else
 {
  if (height == 0)  // auto setting
  {
   dialogcontent.style.height = 'auto';
   dialog.style.height = 'auto';
  }
  else
  {
   dialogcontent.style.height = (height - getHeight(dialogheader) - getHeight(dialogbar)) + 'px';
   dialog.style.height = (height + (isIE?0:13)) + 'px';  // + 2 * 6px content padding + 1px bar border
  }
 }

 var dialogtop, dialogleft;
 var contentwidth = getWidth(content), contentheight = getHeight(content);
 var dialogwidth = getWidth(dialog), dialogheight = getHeight(dialog);
 var scrollPos = getScrollPos();

 if (atcursor)
 {
  dialogtop = MouseMv.Y;   // Absolute to scrolled page
  dialogleft = MouseMv.X;  // Absolute to scrolled page

  if (!isIE)
  {
   dialogtop -= scrollPos.y;   // Relative to scrolled page
   dialogleft -= scrollPos.x;  // Relative to scrolled page
  }
 }
 else
 {
  dialogtop = ((contentheight - dialogheight) / 2);  // Position in center of WRAPPER
  dialogleft = ((contentwidth - dialogwidth) / 2);
 }

 if ((dialogtop + dialogheight) > contentheight - 2) dialogtop = contentheight - dialogheight - 2;  // Prevent dialog from being clipped by content bottom border
 if ((dialogleft + dialogwidth) > contentwidth - 2) dialogleft = contentwidth - dialogwidth - 2;    // Prevent dialog from being clipped by content right border
 dialogtop += scrollPos.y;
 dialogleft += scrollPos.x;

 dialog.style.top =  dialogtop + 'px';
 dialog.style.left = dialogleft + 'px';

 if (modal)
 {
  var scrollSize = getScrollSize();

  dialogmask.style.height = scrollSize.y + 'px';
  dialogmask.style.width = scrollSize.x + 'px';
  var doc = dialogmask.contentDocument || dialogmask.contentWindow.document;
  doc.body.style.height = dialogmask.style.height;  // Resize dialog mask body to full height of window so that it will capture an onclick event
 }

 dialog.timer = setInterval("fadeDialog(1)", TIMER);

 if(autohide)
 {
//  dialogclose.style.visibility = "hidden";
  window.setTimeout("hideDialog()", (autohide * 1000));
 }
// else {dialogclose.style.visibility = "visible";}
}

// hide the dialog box //
function hideDialog(buttonId)
{
 var dialog = el('dialog');
 if (!isDef(buttonId)) buttonId = 0;
 DLGRESULT = buttonId;
 if (ALLOWHIDE || buttonId != 1)  // In some browsers buttonId is passed to the "hideDialog()" function with the onclick event value, otherwise it will default to 0
 {
 clearInterval(dialog.timer);
 dialog.timer = setInterval("fadeDialog(0)", TIMER);
 }
}

// fade-in the dialog box //
function fadeDialog(flag)
{
 var dialog = el('dialog');
 var value;

 if (!isDef(flag)) {flag = 1;}

 if(flag == 1) {value = dialog.alpha + SPEED;}
 else {value = dialog.alpha - SPEED;}

 dialog.alpha = value;
 dialog.style.opacity = (value / 100);
 dialog.style.filter = 'alpha(opacity=' + value + ')';

 if(value >= 99)
 {
  clearInterval(dialog.timer);
  dialog.timer = null;
 }
 else if(value <= 1)
 {
  dialog.style.visibility = "hidden";
  if (el('dialog_mask')) el('dialog_mask').style.display = 'none';
  clearInterval(dialog.timer);
 }
}

/* define some message-functions */
function opf_message(title, text, type, button1, button2, action) {
        DLGRESULT = null;
        showDialog(title,text,type, 450, 180, false, false, true, 4, button1, button2);
        //<!-- showDialog(title, message, type, width, height, autohide, atcursor, modal, buttons, button1, button2)-->
        if(action!=0) {
                setTimeout("opf_message_helper('"+action+"')",100);
        }
}
function opf_message_helper(action) {
        if(DLGRESULT==null)
                { setTimeout("opf_message_helper('"+action+"')", 100); }
        else {
                if(DLGRESULT==2) {
                        document.getElementById('outputfilter').action=action;
                        document.getElementById('outputfilter').submit();
                }
        }
}

if(typeof opf_message_show!='undefined') {
        opf_message(opf_message_title, opf_message_text, opf_message_type, opf_message_button1, opf_message_button2, opf_message_action2);
}

