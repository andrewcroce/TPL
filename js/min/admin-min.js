!function(t){"use strict";var e=function(t){return t},n=function(e){return t.isArray(e)},i=function(t){return!n(t)&&t instanceof Object},r=function(e,n){return t.inArray(n,e)},u=function(t,e){return-1!==r(t,e)},a=function(t,e){for(var n in t)t.hasOwnProperty(n)&&e(t[n],n,t)},c=function(t){return t[t.length-1]},o=function(t,e){var n=[];return a(t,function(t,i,r){n.push(e(t,i,r))}),n},l=function(t,e,n){var i={};return a(t,function(t,r,u){r=n?n(r,t):r,i[r]=e(t,r,u)}),i},f=function(t,e,i){return n(t)?o(t,e):l(t,e,i)},s=function(t,e,n){return f(t,function(t,i){return t[e].apply(t,n||[])})},p=function(t){t=t||{};var e={};return t.publish=function(t,n){a(e[t],function(t){t(n)})},t.subscribe=function(t,n){e[t]=e[t]||[],e[t].push(n)},t.unsubscribe=function(t){a(e,function(e){var n=r(e,t);-1!==n&&e.splice(n,1)})},t};!function(t){var e=function(t,e){var n=p(),i=t.$;return n.getType=function(){throw'implement me (return type. "text", "radio", etc.)'},n.$=function(t){return t?i.find(t):i},n.disable=function(){n.$().prop("disabled",!0),n.publish("isEnabled",!1)},n.enable=function(){n.$().prop("disabled",!1),n.publish("isEnabled",!0)},e.equalTo=function(t,e){return t===e},e.publishChange=function(){var t;return function(i,r){var u=n.get();e.equalTo(u,t)||n.publish("change",{e:i,domElement:r}),t=u}}(),n},o=function(t,n){var i=e(t,n);return i.get=function(){return i.$().val()},i.set=function(t){i.$().val(t)},i.clear=function(){i.set("")},n.buildSetter=function(t){return function(e){t.call(i,e)}},i},l=function(t,e){t=n(t)?t:[t],e=n(e)?e:[e];var i=!0;return t.length!==e.length?i=!1:a(t,function(t){u(e,t)||(i=!1)}),i},f=function(t){var e={},n=o(t,e);return n.getType=function(){return"button"},n.$().on("change",function(t){e.publishChange(t,this)}),n},h=function(e){var i={},r=o(e,i);return r.getType=function(){return"checkbox"},r.get=function(){var e=[];return r.$().filter(":checked").each(function(){e.push(t(this).val())}),e},r.set=function(e){e=n(e)?e:[e],r.$().each(function(){t(this).prop("checked",!1)}),a(e,function(t){r.$().filter('[value="'+t+'"]').prop("checked",!0)})},i.equalTo=l,r.$().change(function(t){i.publishChange(t,this)}),r},d=function(t){var e={},n=x(t,e);return n.getType=function(){return"email"},n},g=function(n){var i={},r=e(n,i);return r.getType=function(){return"file"},r.get=function(){return c(r.$().val().split("\\"))},r.clear=function(){this.$().each(function(){t(this).wrap("<form>").closest("form").get(0).reset(),t(this).unwrap()})},r.$().change(function(t){i.publishChange(t,this)}),r},y=function(t){var e={},n=o(t,e);return n.getType=function(){return"hidden"},n.$().change(function(t){e.publishChange(t,this)}),n},v=function(n){var i={},r=e(n,i);return r.getType=function(){return"file[multiple]"},r.get=function(){var t,e=r.$().get(0).files||[],n=[];for(t=0;t<(e.length||0);t+=1)n.push(e[t].name);return n},r.clear=function(){this.$().each(function(){t(this).wrap("<form>").closest("form").get(0).reset(),t(this).unwrap()})},r.$().change(function(t){i.publishChange(t,this)}),r},m=function(t){var e={},i=o(t,e);return i.getType=function(){return"select[multiple]"},i.get=function(){return i.$().val()||[]},i.set=function(t){i.$().val(""===t?[]:n(t)?t:[t])},e.equalTo=l,i.$().change(function(t){e.publishChange(t,this)}),i},b=function(t){var e={},n=x(t,e);return n.getType=function(){return"password"},n},$=function(e){var n={},i=o(e,n);return i.getType=function(){return"radio"},i.get=function(){return i.$().filter(":checked").val()||null},i.set=function(e){e?i.$().filter('[value="'+e+'"]').prop("checked",!0):i.$().each(function(){t(this).prop("checked",!1)})},i.$().change(function(t){n.publishChange(t,this)}),i},T=function(t){var e={},n=o(t,e);return n.getType=function(){return"range"},n.$().change(function(t){e.publishChange(t,this)}),n},k=function(t){var e={},n=o(t,e);return n.getType=function(){return"select"},n.$().change(function(t){e.publishChange(t,this)}),n},x=function(t){var e={},n=o(t,e);return n.getType=function(){return"text"},n.$().on("change keyup keydown",function(t){e.publishChange(t,this)}),n},w=function(t){var e={},n=o(t,e);return n.getType=function(){return"textarea"},n.$().on("change keyup keydown",function(t){e.publishChange(t,this)}),n},C=function(t){var e={},n=x(t,e);return n.getType=function(){return"url"},n},j=function(e){var n={},u=e.$,c=e.constructorOverride||{button:f,text:x,url:C,email:d,password:b,range:T,textarea:w,select:k,"select[multiple]":m,radio:$,checkbox:h,file:g,"file[multiple]":v,hidden:y},o=function(e,r){var a=i(r)?r:u.find(r);a.each(function(){var i=t(this).attr("name");n[i]=c[e]({$:t(this)})})},l=function(e,o){var l=[],f=i(o)?o:u.find(o);i(o)?n[f.attr("name")]=c[e]({$:f}):(f.each(function(){-1===r(l,t(this).attr("name"))&&l.push(t(this).attr("name"))}),a(l,function(t){n[t]=c[e]({$:u.find('input[name="'+t+'"]')})}))};if(u.is("input, select, textarea"))if(u.is('input[type="button"], button, input[type="submit"]'))o("button",u);else if(u.is("textarea"))o("textarea",u);else if(u.is('input[type="text"]')||u.is("input")&&!u.attr("type"))o("text",u);else if(u.is('input[type="password"]'))o("password",u);else if(u.is('input[type="email"]'))o("email",u);else if(u.is('input[type="url"]'))o("url",u);else if(u.is('input[type="range"]'))o("range",u);else if(u.is("select"))u.is("[multiple]")?o("select[multiple]",u):o("select",u);else if(u.is('input[type="file"]'))u.is("[multiple]")?o("file[multiple]",u):o("file",u);else if(u.is('input[type="hidden"]'))o("hidden",u);else if(u.is('input[type="radio"]'))l("radio",u);else{if(!u.is('input[type="checkbox"]'))throw"invalid input type";l("checkbox",u)}else o("button",'input[type="button"], button, input[type="submit"]'),o("text",'input[type="text"]'),o("password",'input[type="password"]'),o("email",'input[type="email"]'),o("url",'input[type="url"]'),o("range",'input[type="range"]'),o("textarea","textarea"),o("select","select:not([multiple])"),o("select[multiple]","select[multiple]"),o("file",'input[type="file"]:not([multiple])'),o("file[multiple]",'input[type="file"][multiple]'),o("hidden",'input[type="hidden"]'),l("radio",'input[type="radio"]'),l("checkbox",'input[type="checkbox"]');return n};t.fn.inputVal=function(e){var n=t(this),i=j({$:n});return n.is("input, textarea, select")?"undefined"==typeof e?i[n.attr("name")].get():(i[n.attr("name")].set(e),n):"undefined"==typeof e?s(i,"get"):(a(e,function(t,e){i[e].set(t)}),n)},t.fn.inputOnChange=function(e){var n=t(this),i=j({$:n});return a(i,function(t){t.subscribe("change",function(t){e.call(t.domElement,t.e)})}),n},t.fn.inputDisable=function(){var e=t(this);return s(j({$:e}),"disable"),e},t.fn.inputEnable=function(){var e=t(this);return s(j({$:e}),"enable"),e},t.fn.inputClear=function(){var e=t(this);return s(j({$:e}),"clear"),e}}(jQuery),t.fn.repeater=function(n){return n=n||{},t(this).each(function(){var i=t(this),r=n.show||function(){t(this).show()},u=n.hide||function(t){t()},a=i.find("[data-repeater-list]"),o=a.find("[data-repeater-item]").first().clone().hide(),l=t(this).find("[data-repeater-item]").first().find("[data-repeater-delete]");n.isFirstItemUndeletable&&l&&l.remove();var s=a.data("repeater-list"),p=function(){a.find("[data-repeater-item]").each(function(e){t(this).find("[name]").each(function(){var n=t(this).attr("name").match(/\[[^\]]+\]/g),i=n?c(n).replace(/\[|\]/g,""):t(this).attr("name"),r=s+"["+e+"]["+i+"]"+(t(this).is(":checkbox")?"[]":"");t(this).attr("name",r)})}),a.find("input[name][checked]").removeAttr("checked").prop("checked",!0)};p();var h=function(t,n){var i;i=t.find("[name]").first().attr("name").match(/\[([0-9]*)\]/)[1],t.inputVal(f(n,e,function(e){var n=s+"["+i+"]["+e+"]";return t.find('[name="'+n+'"]').length?n:n+"[]"}))},d=function(){var e=function(e){var i=n.defaultValues;e.find("[name]").each(function(){t(this).inputClear()}),i&&h(e,i)};return function(t){a.append(t),p(),e(t)}}();i.find("[data-repeater-create]").click(function(){var t=o.clone();d(t),r.call(t.get(0))}),a.on("click","[data-repeater-delete]",function(){var e=t(this).closest("[data-repeater-item]").get(0);u.call(e,function(){t(e).remove(),p()})})}),this}}(jQuery),function(t){t.displayToggle={init:function(){t(".display-toggle").each(function(){t.displayToggle.toggle(t(this)),t(this).on("change",function(){t.displayToggle.toggle(t(this))})})},toggle:function(e){var n=t('.display-toggleable[data-toggle-control="'+e.attr("id")+'"]');e.is(":checked")&&e.is(":visible")?n.show():n.hide();var i=n.find(".display-toggle");i.length&&t.displayToggle.toggle(i)}}}(jQuery),function(t){t(document).ready(function(){t(".repeater").repeater({isFirstItemUndeletable:!1,defaultValues:{crop:"soft",crop_y:"center",crop_x:"center"}}),t(".display-toggle").length&&t.displayToggle.init()})}(jQuery);