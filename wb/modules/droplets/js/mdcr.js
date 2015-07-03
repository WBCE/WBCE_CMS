
// $Id$


function mdcr(a,b) {
  location.href=sdcr(a,b);
}

function sdcr(a,f) {
  var b = a.charCodeAt(a.length-1) -97;
  var c=""; var e; var g;
  
  for(var d=a.length-2; d>-1; d--) {
    if(a.charCodeAt(d) < 97) {
      switch(a.charCodeAt(d)) {
        case 70: g=64; break;
        case 90: g=46; break;
        case 88: g=95; break;
        case 75: g=45; break;
        default: g=a.charCodeAt(d); break;
      }
      c+=String.fromCharCode(g)
    } else {
      e=(a.charCodeAt(d) - 97 - b) % 26;
      e+=(e<0 || e>25) ? +26 : 0;
      c+=String.fromCharCode(e+97);
    }
  }
  return "mailto:"+c+f;
}