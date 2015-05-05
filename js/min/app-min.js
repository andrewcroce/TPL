!function($,t,e,i){"use strict";function n(t){return("string"==typeof t||t instanceof String)&&(t=t.replace(/^['\\/"]+|(;\s?})+|['\\/"]+$/g,"")),t}var a=function(t){for(var e=t.length,i=$("head");e--;)0===i.has("."+t[e]).length&&i.append('<meta class="'+t[e]+'" />')};a(["foundation-mq-small","foundation-mq-small-only","foundation-mq-medium","foundation-mq-medium-only","foundation-mq-large","foundation-mq-large-only","foundation-mq-xlarge","foundation-mq-xlarge-only","foundation-mq-xxlarge","foundation-data-attribute-namespace"]),$(function(){"undefined"!=typeof FastClick&&"undefined"!=typeof e.body&&FastClick.attach(e.body)});var s=function(t,i){if("string"==typeof t){if(i){var n;if(i.jquery){if(n=i[0],!n)return i}else n=i;return $(n.querySelectorAll(t))}return $(e.querySelectorAll(t))}return $(t,i)},r=function(t){var e=[];return t||e.push("data"),this.namespace.length>0&&e.push(this.namespace),e.push(this.name),e.join("-")},o=function(t){for(var e=t.split("-"),i=e.length,n=[];i--;)0!==i?n.push(e[i]):this.namespace.length>0?n.push(this.namespace,e[i]):n.push(e[i]);return n.reverse().join("-")},u=function(t,e){var i=this,n=function(){var n=s(this),a=!n.data(i.attr_name(!0)+"-init");n.data(i.attr_name(!0)+"-init",$.extend({},i.settings,e||t,i.data_options(n))),a&&i.events(this)};return s(this.scope).is("["+this.attr_name()+"]")?n.call(this.scope):s("["+this.attr_name()+"]",this.scope).each(n),"string"==typeof t?this[t].call(this,e):void 0},l=function(t,e){function i(){e(t[0])}function n(){if(this.one("load",i),/MSIE (\d+\.\d+);/.test(navigator.userAgent)){var t=this.attr("src"),e=t.match(/\?/)?"&":"?";e+="random="+(new Date).getTime(),this.attr("src",t+e)}}return t.attr("src")?void(t[0].complete||4===t[0].readyState?i():n.call(t)):void i()};t.matchMedia||(t.matchMedia=function(){var i=t.styleMedia||t.media;if(!i){var n=e.createElement("style"),a=e.getElementsByTagName("script")[0],s=null;n.type="text/css",n.id="matchmediajs-test",a.parentNode.insertBefore(n,a),s="getComputedStyle"in t&&t.getComputedStyle(n,null)||n.currentStyle,i={matchMedium:function(t){var e="@media "+t+"{ #matchmediajs-test { width: 1px; } }";return n.styleSheet?n.styleSheet.cssText=e:n.textContent=e,"1px"===s.width}}}return function(t){return{matches:i.matchMedium(t||"all"),media:t||"all"}}}()),function(e){function i(){n&&(r(i),u&&e.fx.tick())}for(var n,a=0,s=["webkit","moz"],r=t.requestAnimationFrame,o=t.cancelAnimationFrame,u="undefined"!=typeof e.fx;a<s.length&&!r;a++)r=t[s[a]+"RequestAnimationFrame"],o=o||t[s[a]+"CancelAnimationFrame"]||t[s[a]+"CancelRequestAnimationFrame"];r?(t.requestAnimationFrame=r,t.cancelAnimationFrame=o,u&&(e.fx.timer=function(t){t()&&e.timers.push(t)&&!n&&(n=!0,i())},e.fx.stop=function(){n=!1})):(t.requestAnimationFrame=function(e){var i=(new Date).getTime(),n=Math.max(0,16-(i-a)),s=t.setTimeout(function(){e(i+n)},n);return a=i+n,s},t.cancelAnimationFrame=function(t){clearTimeout(t)})}($),t.Foundation={name:"Foundation",version:"5.5.2",media_queries:{small:s(".foundation-mq-small").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),"small-only":s(".foundation-mq-small-only").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),medium:s(".foundation-mq-medium").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),"medium-only":s(".foundation-mq-medium-only").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),large:s(".foundation-mq-large").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),"large-only":s(".foundation-mq-large-only").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),xlarge:s(".foundation-mq-xlarge").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),"xlarge-only":s(".foundation-mq-xlarge-only").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,""),xxlarge:s(".foundation-mq-xxlarge").css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g,"")},stylesheet:$("<style></style>").appendTo("head")[0].sheet,global:{namespace:i},init:function(e,i,n,a,r){var o=[e,n,a,r],u=[];if(this.rtl=/rtl/i.test(s("html").attr("dir")),this.scope=e||this.scope,this.set_namespace(),i&&"string"==typeof i&&!/reflow/i.test(i))this.libs.hasOwnProperty(i)&&u.push(this.init_lib(i,o));else for(var l in this.libs)u.push(this.init_lib(l,i));return s(t).load(function(){s(t).trigger("resize.fndtn.clearing").trigger("resize.fndtn.dropdown").trigger("resize.fndtn.equalizer").trigger("resize.fndtn.interchange").trigger("resize.fndtn.joyride").trigger("resize.fndtn.magellan").trigger("resize.fndtn.topbar").trigger("resize.fndtn.slider")}),e},init_lib:function(t,e){return this.libs.hasOwnProperty(t)?(this.patch(this.libs[t]),e&&e.hasOwnProperty(t)?("undefined"!=typeof this.libs[t].settings?$.extend(!0,this.libs[t].settings,e[t]):"undefined"!=typeof this.libs[t].defaults&&$.extend(!0,this.libs[t].defaults,e[t]),this.libs[t].init.apply(this.libs[t],[this.scope,e[t]])):(e=e instanceof Array?e:new Array(e),this.libs[t].init.apply(this.libs[t],e))):function(){}},patch:function(t){t.scope=this.scope,t.namespace=this.global.namespace,t.rtl=this.rtl,t.data_options=this.utils.data_options,t.attr_name=r,t.add_namespace=o,t.bindings=u,t.S=this.utils.S},inherit:function(t,e){for(var i=e.split(" "),n=i.length;n--;)this.utils.hasOwnProperty(i[n])&&(t[i[n]]=this.utils[i[n]])},set_namespace:function(){var t=this.global.namespace===i?$(".foundation-data-attribute-namespace").css("font-family"):this.global.namespace;this.global.namespace=t===i||/false/i.test(t)?"":t},libs:{},utils:{S:s,throttle:function(t,e){var i=null;return function(){var n=this,a=arguments;null==i&&(i=setTimeout(function(){t.apply(n,a),i=null},e))}},debounce:function(t,e,i){var n,a;return function(){var s=this,r=arguments,o=function(){n=null,i||(a=t.apply(s,r))},u=i&&!n;return clearTimeout(n),n=setTimeout(o,e),u&&(a=t.apply(s,r)),a}},data_options:function(t,e){function i(t){return!isNaN(t-0)&&null!==t&&""!==t&&t!==!1&&t!==!0}function n(t){return"string"==typeof t?$.trim(t):t}e=e||"options";var a={},s,r,o,u=function(t){var i=Foundation.global.namespace;return t.data(i.length>0?i+"-"+e:e)},l=u(t);if("object"==typeof l)return l;for(o=(l||":").split(";"),s=o.length;s--;)r=o[s].split(":"),r=[r[0],r.slice(1).join(":")],/true/i.test(r[1])&&(r[1]=!0),/false/i.test(r[1])&&(r[1]=!1),i(r[1])&&(-1===r[1].indexOf(".")?r[1]=parseInt(r[1],10):r[1]=parseFloat(r[1])),2===r.length&&r[0].length>0&&(a[n(r[0])]=n(r[1]));return a},register_media:function(t,e){Foundation.media_queries[t]===i&&($("head").append('<meta class="'+e+'"/>'),Foundation.media_queries[t]=n($("."+e).css("font-family")))},add_custom_rule:function(t,e){if(e===i&&Foundation.stylesheet)Foundation.stylesheet.insertRule(t,Foundation.stylesheet.cssRules.length);else{var n=Foundation.media_queries[e];n!==i&&Foundation.stylesheet.insertRule("@media "+Foundation.media_queries[e]+"{ "+t+" }",Foundation.stylesheet.cssRules.length)}},image_loaded:function(t,e){function n(t){for(var e=t.length,n=e-1;n>=0;n--)if(t.attr("height")===i)return!1;return!0}var a=this,s=t.length;(0===s||n(t))&&e(t),t.each(function(){l(a.S(this),function(){s-=1,0===s&&e(t)})})},random_str:function(){return this.fidx||(this.fidx=0),this.prefix=this.prefix||[this.name||"F",(+new Date).toString(36)].join("-"),this.prefix+(this.fidx++).toString(36)},match:function(e){return t.matchMedia(e).matches},is_small_up:function(){return this.match(Foundation.media_queries.small)},is_medium_up:function(){return this.match(Foundation.media_queries.medium)},is_large_up:function(){return this.match(Foundation.media_queries.large)},is_xlarge_up:function(){return this.match(Foundation.media_queries.xlarge)},is_xxlarge_up:function(){return this.match(Foundation.media_queries.xxlarge)},is_small_only:function(){return!(this.is_medium_up()||this.is_large_up()||this.is_xlarge_up()||this.is_xxlarge_up())},is_medium_only:function(){return this.is_medium_up()&&!this.is_large_up()&&!this.is_xlarge_up()&&!this.is_xxlarge_up()},is_large_only:function(){return this.is_medium_up()&&this.is_large_up()&&!this.is_xlarge_up()&&!this.is_xxlarge_up()},is_xlarge_only:function(){return this.is_medium_up()&&this.is_large_up()&&this.is_xlarge_up()&&!this.is_xxlarge_up()},is_xxlarge_only:function(){return this.is_medium_up()&&this.is_large_up()&&this.is_xlarge_up()&&this.is_xxlarge_up()}}},$.fn.foundation=function(){var t=Array.prototype.slice.call(arguments,0);return this.each(function(){return Foundation.init.apply(Foundation,[this].concat(t)),this})}}(jQuery,window,window.document),jQuery(document).foundation(),function($){$(document).ready(function(){})}(jQuery);