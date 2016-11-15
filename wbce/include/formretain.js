//Time in days to save form fields values after last visit
//Set to different value to reset cookie (ie: "101 days" instead of "100 days"):
var memoryduration="60"

function setformobjects(){
var theforms=document.forms
memorizearray=new Array()
for (i=0; i< theforms.length; i++){
for (j=0; j< theforms[i].elements.length; j++){
if (theforms[i].elements[j].className.indexOf("memorize")!=-1 && theforms[i].elements[j].type=="text")
memorizearray[memorizearray.length]=theforms[i].elements[j]
}
}
var retrievedvalues=get_cookie("mvalue"+window.location.pathname)
if (retrievedvalues!=""){
retrievedvalues=retrievedvalues.split("|")
if (retrievedvalues[retrievedvalues.length-1]!=parseInt(memoryduration)) //reset cookie if var memoryduration has changed
resetcookie("mvalue"+window.location.pathname)
else{
for (i=0; i<memorizearray.length; i++){
if (retrievedvalues[i]!="empty_value")
memorizearray[i].value=retrievedvalues[i]
}
}
}
}

function get_cookie(Name) {
  var search = Name + "="
  var returnvalue = "";
  if (document.cookie.length > 0) {
    offset = document.cookie.indexOf(search)
    if (offset != -1) { // if cookie exists
      offset += search.length
      end = document.cookie.indexOf(";", offset);
      if (end == -1)
         end = document.cookie.length;
      returnvalue=unescape(document.cookie.substring(offset, end))
      }
   }
  return returnvalue;
}

function resetcookie(id){
var expireDate = new Date()
expireDate.setDate(expireDate.getDate()-10)
document.cookie = id+"=;path=/;expires=" + expireDate.toGMTString()
}

function saveformvalues(){
var formvalues=new Array(), temp
for (i=0; i<memorizearray.length; i++){
temp=memorizearray[i].value!=""? memorizearray[i].value : "empty_value"
formvalues[formvalues.length]=escape(temp)
}
formvalues[formvalues.length]=parseInt(memoryduration)
formvalues=formvalues.join("|")
var expireDate = new Date()
expireDate.setSeconds(expireDate.getSeconds()+parseInt(memoryduration))
document.cookie = "mvalue"+window.location.pathname+"="+formvalues+"; path=/;expires=" + expireDate.toGMTString()
}

if (window.addEventListener)
window.addEventListener("load", setformobjects, false)
else if (window.attachEvent)
window.attachEvent("onload", setformobjects)
else if (document.getElementById)
window.onload=setformobjects
if (document.getElementById)
window.onunload=saveformvalues