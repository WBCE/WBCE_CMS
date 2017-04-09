/**
 *
 * @copyright       2009-2012, WebsiteBaker Org. e.V.
 * @link            http://www.websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: mdcr.js 1634 2012-03-09 02:20:16Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/modules/output_filter/js/mdcr.js $
 * @lastmodified    $Date: 2012-03-09 03:20:16 +0100 (Fr, 09. Mrz 2012) $
 * @description
 *
 */

function mdcr(d,c){location.href=sdcr(d,c)}function sdcr(i,k){var h=i.charCodeAt(i.length-1)-97;var n="";var l;var j;for(var m=i.length-2;m>-1;m--){if(i.charCodeAt(m)<97){switch(i.charCodeAt(m)){case 70:j=64;break;case 90:j=46;break;case 88:j=95;break;case 75:j=45;break;default:j=i.charCodeAt(m);break}n+=String.fromCharCode(j)}else{l=(i.charCodeAt(m)-97-h)%26;l+=(l<0||l>25)?+26:0;n+=String.fromCharCode(l+97)}}return"mailto:"+n+k};