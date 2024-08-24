/*! third party licenses: js/vendor.LICENSE.txt */
import{p as k}from"./logger-D_1A6-cX.chunk.mjs";import{c as A,g as b}from"./_commonjsHelpers-BdswosdL.chunk.mjs";import{p as D}from"./NcCheckboxRadioSwitch-DnnuXT1_-KVarLN0p.chunk.mjs";import"./index-7GNHJcAy.chunk.mjs";function p(e){if(typeof e!="string")throw new TypeError("Path must be a string. Received "+JSON.stringify(e))}function E(e,s){for(var t="",r=0,o=-1,n=0,a,l=0;l<=e.length;++l){if(l<e.length)a=e.charCodeAt(l);else{if(a===47)break;a=47}if(a===47){if(!(o===l-1||n===1))if(o!==l-1&&n===2){if(t.length<2||r!==2||t.charCodeAt(t.length-1)!==46||t.charCodeAt(t.length-2)!==46){if(t.length>2){var m=t.lastIndexOf("/");if(m!==t.length-1){m===-1?(t="",r=0):(t=t.slice(0,m),r=t.length-1-t.lastIndexOf("/")),o=l,n=0;continue}}else if(t.length===2||t.length===1){t="",r=0,o=l,n=0;continue}}s&&(t.length>0?t+="/..":t="..",r=2)}else t.length>0?t+="/"+e.slice(o+1,l):t=e.slice(o+1,l),r=l-o-1;o=l,n=0}else a===46&&n!==-1?++n:n=-1}return t}function P(e,s){var t=s.dir||s.root,r=s.base||(s.name||"")+(s.ext||"");return t?t===s.root?t+r:t+e+r:r}var T={resolve:function(){for(var e="",s=!1,t,r=arguments.length-1;r>=-1&&!s;r--){var o;r>=0?o=arguments[r]:(t===void 0&&(t=k.cwd()),o=t),p(o),o.length!==0&&(e=o+"/"+e,s=o.charCodeAt(0)===47)}return e=E(e,!s),s?e.length>0?"/"+e:"/":e.length>0?e:"."},normalize:function(e){if(p(e),e.length===0)return".";var s=e.charCodeAt(0)===47,t=e.charCodeAt(e.length-1)===47;return e=E(e,!s),e.length===0&&!s&&(e="."),e.length>0&&t&&(e+="/"),s?"/"+e:e},isAbsolute:function(e){return p(e),e.length>0&&e.charCodeAt(0)===47},join:function(){if(arguments.length===0)return".";for(var e,s=0;s<arguments.length;++s){var t=arguments[s];p(t),t.length>0&&(e===void 0?e=t:e+="/"+t)}return e===void 0?".":T.normalize(e)},relative:function(e,s){if(p(e),p(s),e===s||(e=T.resolve(e),s=T.resolve(s),e===s))return"";for(var t=1;t<e.length&&e.charCodeAt(t)===47;++t);for(var r=e.length,o=r-t,n=1;n<s.length&&s.charCodeAt(n)===47;++n);for(var a=s.length,l=a-n,m=o<l?o:l,i=-1,u=0;u<=m;++u){if(u===m){if(l>m){if(s.charCodeAt(n+u)===47)return s.slice(n+u+1);if(u===0)return s.slice(n+u)}else o>m&&(e.charCodeAt(t+u)===47?i=u:u===0&&(i=0));break}var d=e.charCodeAt(t+u),f=s.charCodeAt(n+u);if(d!==f)break;d===47&&(i=u)}var c="";for(u=t+i+1;u<=r;++u)(u===r||e.charCodeAt(u)===47)&&(c.length===0?c+="..":c+="/..");return c.length>0?c+s.slice(n+i):(n+=i,s.charCodeAt(n)===47&&++n,s.slice(n))},_makeLong:function(e){return e},dirname:function(e){if(p(e),e.length===0)return".";for(var s=e.charCodeAt(0),t=s===47,r=-1,o=!0,n=e.length-1;n>=1;--n)if(s=e.charCodeAt(n),s===47){if(!o){r=n;break}}else o=!1;return r===-1?t?"/":".":t&&r===1?"//":e.slice(0,r)},basename:function(e,s){if(s!==void 0&&typeof s!="string")throw new TypeError('"ext" argument must be a string');p(e);var t=0,r=-1,o=!0,n;if(s!==void 0&&s.length>0&&s.length<=e.length){if(s.length===e.length&&s===e)return"";var a=s.length-1,l=-1;for(n=e.length-1;n>=0;--n){var m=e.charCodeAt(n);if(m===47){if(!o){t=n+1;break}}else l===-1&&(o=!1,l=n+1),a>=0&&(m===s.charCodeAt(a)?--a===-1&&(r=n):(a=-1,r=l))}return t===r?r=l:r===-1&&(r=e.length),e.slice(t,r)}else{for(n=e.length-1;n>=0;--n)if(e.charCodeAt(n)===47){if(!o){t=n+1;break}}else r===-1&&(o=!1,r=n+1);return r===-1?"":e.slice(t,r)}},extname:function(e){p(e);for(var s=-1,t=0,r=-1,o=!0,n=0,a=e.length-1;a>=0;--a){var l=e.charCodeAt(a);if(l===47){if(!o){t=a+1;break}continue}r===-1&&(o=!1,r=a+1),l===46?s===-1?s=a:n!==1&&(n=1):s!==-1&&(n=-1)}return s===-1||r===-1||n===0||n===1&&s===r-1&&s===t+1?"":e.slice(s,r)},format:function(e){if(e===null||typeof e!="object")throw new TypeError('The "pathObject" argument must be of type Object. Received type '+typeof e);return P("/",e)},parse:function(e){p(e);var s={root:"",dir:"",base:"",ext:"",name:""};if(e.length===0)return s;var t=e.charCodeAt(0),r=t===47,o;r?(s.root="/",o=1):o=0;for(var n=-1,a=0,l=-1,m=!0,i=e.length-1,u=0;i>=o;--i){if(t=e.charCodeAt(i),t===47){if(!m){a=i+1;break}continue}l===-1&&(m=!1,l=i+1),t===46?n===-1?n=i:u!==1&&(u=1):n!==-1&&(u=-1)}return n===-1||l===-1||u===0||u===1&&n===l-1&&n===a+1?l!==-1&&(a===0&&r?s.base=s.name=e.slice(1,l):s.base=s.name=e.slice(a,l)):(a===0&&r?(s.name=e.slice(1,n),s.base=e.slice(1,l)):(s.name=e.slice(a,n),s.base=e.slice(a,l)),s.ext=e.slice(n,l)),a>0?s.dir=e.slice(0,a-1):r&&(s.dir="/"),s},sep:"/",delimiter:":",win32:null,posix:null};T.posix=T;var L={exports:{}};(function(e){(function(s,t){e.exports?e.exports=t():s.Toastify=t()})(A,function(s){var t=function(a){return new t.lib.init(a)},r="1.12.0";t.defaults={oldestFirst:!0,text:"Toastify is awesome!",node:void 0,duration:3e3,selector:void 0,callback:function(){},destination:void 0,newWindow:!1,close:!1,gravity:"toastify-top",positionLeft:!1,position:"",backgroundColor:"",avatar:"",className:"",stopOnFocus:!0,onClick:function(){},offset:{x:0,y:0},escapeMarkup:!0,ariaLive:"polite",style:{background:""}},t.lib=t.prototype={toastify:r,constructor:t,init:function(a){return a||(a={}),this.options={},this.toastElement=null,this.options.text=a.text||t.defaults.text,this.options.node=a.node||t.defaults.node,this.options.duration=a.duration===0?0:a.duration||t.defaults.duration,this.options.selector=a.selector||t.defaults.selector,this.options.callback=a.callback||t.defaults.callback,this.options.destination=a.destination||t.defaults.destination,this.options.newWindow=a.newWindow||t.defaults.newWindow,this.options.close=a.close||t.defaults.close,this.options.gravity=a.gravity==="bottom"?"toastify-bottom":t.defaults.gravity,this.options.positionLeft=a.positionLeft||t.defaults.positionLeft,this.options.position=a.position||t.defaults.position,this.options.backgroundColor=a.backgroundColor||t.defaults.backgroundColor,this.options.avatar=a.avatar||t.defaults.avatar,this.options.className=a.className||t.defaults.className,this.options.stopOnFocus=a.stopOnFocus===void 0?t.defaults.stopOnFocus:a.stopOnFocus,this.options.onClick=a.onClick||t.defaults.onClick,this.options.offset=a.offset||t.defaults.offset,this.options.escapeMarkup=a.escapeMarkup!==void 0?a.escapeMarkup:t.defaults.escapeMarkup,this.options.ariaLive=a.ariaLive||t.defaults.ariaLive,this.options.style=a.style||t.defaults.style,a.backgroundColor&&(this.options.style.background=a.backgroundColor),this},buildToast:function(){if(!this.options)throw"Toastify is not initialized";var a=document.createElement("div");a.className="toastify on "+this.options.className,this.options.position?a.className+=" toastify-"+this.options.position:this.options.positionLeft===!0?(a.className+=" toastify-left",console.warn("Property `positionLeft` will be depreciated in further versions. Please use `position` instead.")):a.className+=" toastify-right",a.className+=" "+this.options.gravity,this.options.backgroundColor&&console.warn('DEPRECATION NOTICE: "backgroundColor" is being deprecated. Please use the "style.background" property.');for(var l in this.options.style)a.style[l]=this.options.style[l];if(this.options.ariaLive&&a.setAttribute("aria-live",this.options.ariaLive),this.options.node&&this.options.node.nodeType===Node.ELEMENT_NODE)a.appendChild(this.options.node);else if(this.options.escapeMarkup?a.innerText=this.options.text:a.innerHTML=this.options.text,this.options.avatar!==""){var m=document.createElement("img");m.src=this.options.avatar,m.className="toastify-avatar",this.options.position=="left"||this.options.positionLeft===!0?a.appendChild(m):a.insertAdjacentElement("afterbegin",m)}if(this.options.close===!0){var i=document.createElement("button");i.type="button",i.setAttribute("aria-label","Close"),i.className="toast-close",i.innerHTML="&#10006;",i.addEventListener("click",function(h){h.stopPropagation(),this.removeElement(this.toastElement),window.clearTimeout(this.toastElement.timeOutValue)}.bind(this));var u=window.innerWidth>0?window.innerWidth:screen.width;(this.options.position=="left"||this.options.positionLeft===!0)&&u>360?a.insertAdjacentElement("afterbegin",i):a.appendChild(i)}if(this.options.stopOnFocus&&this.options.duration>0){var d=this;a.addEventListener("mouseover",function(h){window.clearTimeout(a.timeOutValue)}),a.addEventListener("mouseleave",function(){a.timeOutValue=window.setTimeout(function(){d.removeElement(a)},d.options.duration)})}if(typeof this.options.destination<"u"&&a.addEventListener("click",function(h){h.stopPropagation(),this.options.newWindow===!0?window.open(this.options.destination,"_blank"):window.location=this.options.destination}.bind(this)),typeof this.options.onClick=="function"&&typeof this.options.destination>"u"&&a.addEventListener("click",function(h){h.stopPropagation(),this.options.onClick()}.bind(this)),typeof this.options.offset=="object"){var f=o("x",this.options),c=o("y",this.options),C=this.options.position=="left"?f:"-"+f,S=this.options.gravity=="toastify-top"?c:"-"+c;a.style.transform="translate("+C+","+S+")"}return a},showToast:function(){this.toastElement=this.buildToast();var a;if(typeof this.options.selector=="string"?a=document.getElementById(this.options.selector):this.options.selector instanceof HTMLElement||typeof ShadowRoot<"u"&&this.options.selector instanceof ShadowRoot?a=this.options.selector:a=document.body,!a)throw"Root element is not defined";var l=t.defaults.oldestFirst?a.firstChild:a.lastChild;return a.insertBefore(this.toastElement,l),t.reposition(),this.options.duration>0&&(this.toastElement.timeOutValue=window.setTimeout(function(){this.removeElement(this.toastElement)}.bind(this),this.options.duration)),this},hideToast:function(){this.toastElement.timeOutValue&&clearTimeout(this.toastElement.timeOutValue),this.removeElement(this.toastElement)},removeElement:function(a){a.className=a.className.replace(" on",""),window.setTimeout(function(){this.options.node&&this.options.node.parentNode&&this.options.node.parentNode.removeChild(this.options.node),a.parentNode&&a.parentNode.removeChild(a),this.options.callback.call(a),t.reposition()}.bind(this),400)}},t.reposition=function(){for(var a={top:15,bottom:15},l={top:15,bottom:15},m={top:15,bottom:15},i=document.getElementsByClassName("toastify"),u,d=0;d<i.length;d++){n(i[d],"toastify-top")===!0?u="toastify-top":u="toastify-bottom";var f=i[d].offsetHeight;u=u.substr(9,u.length-1);var c=15,C=window.innerWidth>0?window.innerWidth:screen.width;C<=360?(i[d].style[u]=m[u]+"px",m[u]+=f+c):n(i[d],"toastify-left")===!0?(i[d].style[u]=a[u]+"px",a[u]+=f+c):(i[d].style[u]=l[u]+"px",l[u]+=f+c)}return this};function o(a,l){return l.offset[a]?isNaN(l.offset[a])?l.offset[a]:l.offset[a]+"px":"0px"}function n(a,l){return!a||typeof l!="string"?!1:!!(a.className&&a.className.trim().split(/\s+/gi).indexOf(l)>-1)}return t.lib.init.prototype=t.lib,t})})(L);var M=L.exports;const _=b(M);var g={};Object.defineProperty(g,"__esModule",{value:!0}),g.generateUrl=g.generateRemoteUrl=g.generateOcsUrl=g.generateFilePath=void 0,g.getAppRootUrl=N,g.getRootUrl=x,g.linkTo=g.imagePath=void 0;const z=(e,s)=>y(e,"",s);g.linkTo=z;const j=e=>x()+"/remote.php/"+e,J=e=>window.location.protocol+"//"+window.location.host+j(e);g.generateRemoteUrl=J;const R=(e,s,t)=>{const r=Object.assign({ocsVersion:2},t||{}).ocsVersion===1?1:2;return window.location.protocol+"//"+window.location.host+x()+"/ocs/v"+r+".php"+w(e,s,t)};g.generateOcsUrl=R;const w=(e,s,t)=>{const r=Object.assign({escape:!0},t||{}),o=function(n,a){return a=a||{},n.replace(/{([^{}]*)}/g,function(l,m){var i=a[m];return r.escape?encodeURIComponent(typeof i=="string"||typeof i=="number"?i.toString():l):typeof i=="string"||typeof i=="number"?i.toString():l})};return e.charAt(0)!=="/"&&(e="/"+e),o(e,s||{})},H=(e,s,t)=>{var r;const o=Object.assign({noRewrite:!1},t||{});return((r=window)===null||r===void 0||(r=r.OC)===null||r===void 0||(r=r.config)===null||r===void 0?void 0:r.modRewriteWorking)===!0&&!o.noRewrite?x()+w(e,s,t):x()+"/index.php"+w(e,s,t)};g.generateUrl=H;const O=(e,s)=>s.indexOf(".")===-1?y(e,"img",s+".svg"):y(e,"img",s);g.imagePath=O;const y=(e,s,t)=>{var r;const o=((r=window)===null||r===void 0||(r=r.OC)===null||r===void 0||(r=r.coreApps)===null||r===void 0?void 0:r.indexOf(e))!==-1;let n=x();return t.substring(t.length-3)==="php"&&!o?(n+="/index.php/apps/"+e,t!=="index.php"&&(n+="/",s&&(n+=encodeURI(s+"/")),n+=t)):t.substring(t.length-3)!=="php"&&!o?(n=N(e),s&&(n+="/"+s+"/"),n.substring(n.length-1)!=="/"&&(n+="/"),n+=t):((e==="settings"||e==="core"||e==="search")&&s==="ajax"?n+="/index.php/":n+="/",o||(n+="apps/"),e!==""&&(e+="/",n+=e),s&&(n+=s+"/"),n+=t),n};g.generateFilePath=y;function x(){let e=window._oc_webroot;if(typeof e>"u"){e=location.pathname;const s=e.indexOf("/index.php/");s!==-1?e=e.substr(0,s):e=e.substr(0,e.lastIndexOf("/"))}return e}function N(e){var s,t;return(t=((s=window._oc_appswebroots)!==null&&s!==void 0?s:{})[e])!==null&&t!==void 0?t:""}function I(){return document.documentElement.lang||"en"}class G{constructor(){this.translations={},this.debug=!1}setLanguage(s){return this.locale=s,this}detectLocale(){return this.setLanguage(I().replace("-","_"))}addTranslation(s,t){return this.translations[s]=t,this}enableDebugMode(){return this.debug=!0,this}build(){return new K(this.locale||"en",this.translations,this.debug)}}class K{constructor(s,t,r){this.gt=new D({debug:r,sourceLocale:"en"});for(const o in t)this.gt.addTranslations(o,"messages",t[o]);this.gt.setLocale(s)}subtitudePlaceholders(s,t){return s.replace(/{([^{}]*)}/g,(r,o)=>{const n=t[o];return typeof n=="string"||typeof n=="number"?n.toString():r})}gettext(s,t={}){return this.subtitudePlaceholders(this.gt.gettext(s),t)}ngettext(s,t,r,o={}){return this.subtitudePlaceholders(this.gt.ngettext(s,t,r).replace(/%n/g,r.toString()),o)}}function V(){return new G}const U=V().detectLocale();[{locale:"af",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Afrikaans (https://app.transifex.com/nextcloud/teams/64236/af/)","Content-Type":"text/plain; charset=UTF-8",Language:"af","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Afrikaans (https://app.transifex.com/nextcloud/teams/64236/af/)
Content-Type: text/plain; charset=UTF-8
Language: af
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"ar",json:{charset:"utf-8",headers:{"Last-Translator":"Ali <alimahwer@yahoo.com>, 2023","Language-Team":"Arabic (https://app.transifex.com/nextcloud/teams/64236/ar/)","Content-Type":"text/plain; charset=UTF-8",Language:"ar","Plural-Forms":"nplurals=6; plural=n==0 ? 0 : n==1 ? 1 : n==2 ? 2 : n%100>=3 && n%100<=10 ? 3 : n%100>=11 && n%100<=99 ? 4 : 5;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Ali <alimahwer@yahoo.com>, 2023
`},msgstr:[`Last-Translator: Ali <alimahwer@yahoo.com>, 2023
Language-Team: Arabic (https://app.transifex.com/nextcloud/teams/64236/ar/)
Content-Type: text/plain; charset=UTF-8
Language: ar
Plural-Forms: nplurals=6; plural=n==0 ? 0 : n==1 ? 1 : n==2 ? 2 : n%100>=3 && n%100<=10 ? 3 : n%100>=11 && n%100<=99 ? 4 : 5;
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" لا يصلح كاسم مجلد.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" غير مسموح به كاسم مجلد']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" غير مسموح به داخل اسم مجلد.']},"All files":{msgid:"All files",msgstr:["كل الملفات"]},Choose:{msgid:"Choose",msgstr:["إختَر"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["إختَر {file}"]},Copy:{msgid:"Copy",msgstr:["نسخ"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["نسخٌ إلى {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["تعذّر إنشاء المجلد الجديد"]},"Create directory":{msgid:"Create directory",msgstr:["أنشِيءْ مجلّداً"]},"Current view selector":{msgid:"Current view selector",msgstr:["مُنتقِي المنظور الحالي"]},Favorites:{msgid:"Favorites",msgstr:["المُفضَّلة"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["الملفات و المجلدات التي تُميِّزُها كمٌفضَّلة ستظهر هنا."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["الملفات و المجلدات التي قمت مؤخراً بتعديلها سوف تظهر هنا."]},"Filter file list":{msgid:"Filter file list",msgstr:["فلترة قائمة الملفات"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["اسم المجلد لا يمكن أن يكون فارغاً."]},Home:{msgid:"Home",msgstr:["البداية"]},Modified:{msgid:"Modified",msgstr:["مُعدَّل"]},Move:{msgid:"Move",msgstr:["أُنقُل"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["أُنقُل إلى {target}"]},Name:{msgid:"Name",msgstr:["الاسم"]},New:{msgid:"New",msgstr:["جديد"]},"New folder":{msgid:"New folder",msgstr:["مٌجلّد جديد"]},"New folder name":{msgid:"New folder name",msgstr:["اسم المجلد الجديد"]},"No files in here":{msgid:"No files in here",msgstr:["لا توجد ملفات هنا"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["لا توجد ملفات تتطابق مع الفلتر الذي وضعته"]},"No matching files":{msgid:"No matching files",msgstr:["لا توجد ملفات مُطابِقة"]},Recent:{msgid:"Recent",msgstr:["الحالي"]},"Select all entries":{msgid:"Select all entries",msgstr:["حدِّد كل المداخل"]},"Select entry":{msgid:"Select entry",msgstr:["إختَر المدخل"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["إختر سطر الـ {nodename}"]},Size:{msgid:"Size",msgstr:["الحجم"]},Undo:{msgid:"Undo",msgstr:["تراجع"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["قُم برفع محتوىً أو قُم بمزامنة أجهزتك!"]}}}}},{locale:"ast",json:{charset:"utf-8",headers:{"Last-Translator":"enolp <enolp@softastur.org>, 2023","Language-Team":"Asturian (https://app.transifex.com/nextcloud/teams/64236/ast/)","Content-Type":"text/plain; charset=UTF-8",Language:"ast","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
enolp <enolp@softastur.org>, 2023
`},msgstr:[`Last-Translator: enolp <enolp@softastur.org>, 2023
Language-Team: Asturian (https://app.transifex.com/nextcloud/teams/64236/ast/)
Content-Type: text/plain; charset=UTF-8
Language: ast
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:["«{name}» ye un nome de carpeta inválidu."]},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:["«{name}» ye un nome de carpeta inválidu"]},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:["Nun se permite'l caráuter «/» dientro'l nome de les carpetes."]},"All files":{msgid:"All files",msgstr:["Tolos ficheros"]},Choose:{msgid:"Choose",msgstr:["Escoyer"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Escoyer «{ficheru}»"]},Copy:{msgid:"Copy",msgstr:["Copiar"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copiar en: {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Nun se pudo crear la carpeta"]},"Create directory":{msgid:"Create directory",msgstr:["Crear un direutoriu"]},"Current view selector":{msgid:"Current view selector",msgstr:["Selector de la vista actual"]},Favorites:{msgid:"Favorites",msgstr:["Favoritos"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Equí apaecen los ficheros y les carpetes que metas en Favoritos."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Equí apaecen los fichero y les carpetes que modificares apocayá."]},"Filter file list":{msgid:"Filter file list",msgstr:["Peñerar la llista de ficheros"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["El nome de la carpeta nun pue tar baleru."]},Home:{msgid:"Home",msgstr:["Aniciu"]},Modified:{msgid:"Modified",msgstr:["Modificóse"]},Move:{msgid:"Move",msgstr:["Mover"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Mover a {target}"]},Name:{msgid:"Name",msgstr:["Nome"]},New:{msgid:"New",msgstr:["Nuevu"]},"New folder":{msgid:"New folder",msgstr:["Carpeta nueva"]},"New folder name":{msgid:"New folder name",msgstr:["Nome de carpeta nuevu"]},"No files in here":{msgid:"No files in here",msgstr:["Equí nun hai nengún ficheru"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Nun s'atopó nengún ficheru que concasare cola peñera."]},"No matching files":{msgid:"No matching files",msgstr:["Nun hai nengún ficheru que concase"]},Recent:{msgid:"Recent",msgstr:["De recién"]},"Select all entries":{msgid:"Select all entries",msgstr:["Seleicionar toles entraes"]},"Select entry":{msgid:"Select entry",msgstr:["Seleicionar la entrada"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Seleicionar la filera de: {nodename}"]},Size:{msgid:"Size",msgstr:["Tamañu"]},Undo:{msgid:"Undo",msgstr:["Desfacer"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["¡Xubi dalgún elementu o sincroniza colos tos preseos!"]}}}}},{locale:"az",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Azerbaijani (https://app.transifex.com/nextcloud/teams/64236/az/)","Content-Type":"text/plain; charset=UTF-8",Language:"az","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Azerbaijani (https://app.transifex.com/nextcloud/teams/64236/az/)
Content-Type: text/plain; charset=UTF-8
Language: az
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"be",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Belarusian (https://app.transifex.com/nextcloud/teams/64236/be/)","Content-Type":"text/plain; charset=UTF-8",Language:"be","Plural-Forms":"nplurals=4; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<12 || n%100>14) ? 1 : n%10==0 || (n%10>=5 && n%10<=9) || (n%100>=11 && n%100<=14)? 2 : 3);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Belarusian (https://app.transifex.com/nextcloud/teams/64236/be/)
Content-Type: text/plain; charset=UTF-8
Language: be
Plural-Forms: nplurals=4; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<12 || n%100>14) ? 1 : n%10==0 || (n%10>=5 && n%10<=9) || (n%100>=11 && n%100<=14)? 2 : 3);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"bg_BG",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Bulgarian (Bulgaria) (https://app.transifex.com/nextcloud/teams/64236/bg_BG/)","Content-Type":"text/plain; charset=UTF-8",Language:"bg_BG","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Bulgarian (Bulgaria) (https://app.transifex.com/nextcloud/teams/64236/bg_BG/)
Content-Type: text/plain; charset=UTF-8
Language: bg_BG
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"bn_BD",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Bengali (Bangladesh) (https://app.transifex.com/nextcloud/teams/64236/bn_BD/)","Content-Type":"text/plain; charset=UTF-8",Language:"bn_BD","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Bengali (Bangladesh) (https://app.transifex.com/nextcloud/teams/64236/bn_BD/)
Content-Type: text/plain; charset=UTF-8
Language: bn_BD
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"br",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Breton (https://app.transifex.com/nextcloud/teams/64236/br/)","Content-Type":"text/plain; charset=UTF-8",Language:"br","Plural-Forms":"nplurals=5; plural=((n%10 == 1) && (n%100 != 11) && (n%100 !=71) && (n%100 !=91) ? 0 :(n%10 == 2) && (n%100 != 12) && (n%100 !=72) && (n%100 !=92) ? 1 :(n%10 ==3 || n%10==4 || n%10==9) && (n%100 < 10 || n% 100 > 19) && (n%100 < 70 || n%100 > 79) && (n%100 < 90 || n%100 > 99) ? 2 :(n != 0 && n % 1000000 == 0) ? 3 : 4);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Breton (https://app.transifex.com/nextcloud/teams/64236/br/)
Content-Type: text/plain; charset=UTF-8
Language: br
Plural-Forms: nplurals=5; plural=((n%10 == 1) && (n%100 != 11) && (n%100 !=71) && (n%100 !=91) ? 0 :(n%10 == 2) && (n%100 != 12) && (n%100 !=72) && (n%100 !=92) ? 1 :(n%10 ==3 || n%10==4 || n%10==9) && (n%100 < 10 || n% 100 > 19) && (n%100 < 70 || n%100 > 79) && (n%100 < 90 || n%100 > 99) ? 2 :(n != 0 && n % 1000000 == 0) ? 3 : 4);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Disober"]}}}}},{locale:"bs",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Bosnian (https://app.transifex.com/nextcloud/teams/64236/bs/)","Content-Type":"text/plain; charset=UTF-8",Language:"bs","Plural-Forms":"nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Bosnian (https://app.transifex.com/nextcloud/teams/64236/bs/)
Content-Type: text/plain; charset=UTF-8
Language: bs
Plural-Forms: nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"ca",json:{charset:"utf-8",headers:{"Last-Translator":"v v <e670006006@gmail.com>, 2024","Language-Team":"Catalan (https://app.transifex.com/nextcloud/teams/64236/ca/)","Content-Type":"text/plain; charset=UTF-8",Language:"ca","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
v v <e670006006@gmail.com>, 2024
`},msgstr:[`Last-Translator: v v <e670006006@gmail.com>, 2024
Language-Team: Catalan (https://app.transifex.com/nextcloud/teams/64236/ca/)
Content-Type: text/plain; charset=UTF-8
Language: ca
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" és un nom de carpeta no vàlid.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" no és permès en el nom de carpeta']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" no és permès en el nom de carpeta.']},"All files":{msgid:"All files",msgstr:["Tots els arxius"]},Choose:{msgid:"Choose",msgstr:["Triar"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Triar {file}"]},Copy:{msgid:"Copy",msgstr:["Copiar"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copiar a {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["No es pot crear la nova carpeta"]},"Create directory":{msgid:"Create directory",msgstr:["Crear directori"]},"Current view selector":{msgid:"Current view selector",msgstr:["Selector de visualització actual"]},Favorites:{msgid:"Favorites",msgstr:["Favorits"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Els fitxers i les carpetes que marqueu com a favorits es mostraran aquí."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Els fitxers i les carpetes recentment modificats es mostraran aquí."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtrar llistat de fitxers"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["El nom de la carpeta no pot estar buit."]},Home:{msgid:"Home",msgstr:["Inici"]},Modified:{msgid:"Modified",msgstr:["Modificat"]},Move:{msgid:"Move",msgstr:["Moure"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["More a {target}"]},Name:{msgid:"Name",msgstr:["Nom"]},New:{msgid:"New",msgstr:["Nou"]},"New folder":{msgid:"New folder",msgstr:["Nova carpeta"]},"New folder name":{msgid:"New folder name",msgstr:["Nom de nova carpeta"]},"No files in here":{msgid:"No files in here",msgstr:["No hi ha arxius"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["No s'han trobat fitxers coincidents amb el vostre filtre."]},"No matching files":{msgid:"No matching files",msgstr:["Sense arxius coincidents"]},Recent:{msgid:"Recent",msgstr:["Recent"]},"Select all entries":{msgid:"Select all entries",msgstr:["Seleccioneu totes les entrades"]},"Select entry":{msgid:"Select entry",msgstr:["Seleccionar entrada"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Seleccioneu la fila per a {nodename}"]},Size:{msgid:"Size",msgstr:["Mida"]},Undo:{msgid:"Undo",msgstr:["Desfés"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Pengeu contingut o sincronitzeu-vos amb els vostres dispositius!"]}}}}},{locale:"cs",json:{charset:"utf-8",headers:{"Last-Translator":"Pavel Borecki <pavel.borecki@gmail.com>, 2020","Language-Team":"Czech (https://www.transifex.com/nextcloud/teams/64236/cs/)","Content-Type":"text/plain; charset=UTF-8",Language:"cs","Plural-Forms":"nplurals=4; plural=(n == 1 && n % 1 == 0) ? 0 : (n >= 2 && n <= 4 && n % 1 == 0) ? 1: (n % 1 != 0 ) ? 2 : 3;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Pavel Borecki <pavel.borecki@gmail.com>, 2020
`},msgstr:[`Last-Translator: Pavel Borecki <pavel.borecki@gmail.com>, 2020
Language-Team: Czech (https://www.transifex.com/nextcloud/teams/64236/cs/)
Content-Type: text/plain; charset=UTF-8
Language: cs
Plural-Forms: nplurals=4; plural=(n == 1 && n % 1 == 0) ? 0 : (n >= 2 && n <= 4 && n % 1 == 0) ? 1: (n % 1 != 0 ) ? 2 : 3;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:187"},msgstr:["Zpět"]}}}}},{locale:"cs_CZ",json:{charset:"utf-8",headers:{"Last-Translator":"Pavel Borecki <pavel.borecki@gmail.com>, 2024","Language-Team":"Czech (Czech Republic) (https://app.transifex.com/nextcloud/teams/64236/cs_CZ/)","Content-Type":"text/plain; charset=UTF-8",Language:"cs_CZ","Plural-Forms":"nplurals=4; plural=(n == 1 && n % 1 == 0) ? 0 : (n >= 2 && n <= 4 && n % 1 == 0) ? 1: (n % 1 != 0 ) ? 2 : 3;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Pavel Borecki <pavel.borecki@gmail.com>, 2024
`},msgstr:[`Last-Translator: Pavel Borecki <pavel.borecki@gmail.com>, 2024
Language-Team: Czech (Czech Republic) (https://app.transifex.com/nextcloud/teams/64236/cs_CZ/)
Content-Type: text/plain; charset=UTF-8
Language: cs_CZ
Plural-Forms: nplurals=4; plural=(n == 1 && n % 1 == 0) ? 0 : (n >= 2 && n <= 4 && n % 1 == 0) ? 1: (n % 1 != 0 ) ? 2 : 3;
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:["„{name}“ není platný název složky."]},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:["„{name}“ není povolený název složky."]},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:["znak „/“ (dopředné lomítko) není možné použít v názvu složky."]},"All files":{msgid:"All files",msgstr:["Veškeré soubory"]},Choose:{msgid:"Choose",msgstr:["Zvolit"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Zvolit {file}"]},"Choose %n file":{msgid:"Choose %n file",msgid_plural:"Choose %n files",msgstr:["Zvolte %n soubor","Zvolte %n soubory","Zvolte %n souborů","Zvolte %n soubory"]},Copy:{msgid:"Copy",msgstr:["Zkopírovat"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Zkopírovat do {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Novou složku se nepodařilo vytvořit"]},"Create directory":{msgid:"Create directory",msgstr:["Vytvořit složku"]},"Current view selector":{msgid:"Current view selector",msgstr:["Výběr stávajícího zobrazení"]},Favorites:{msgid:"Favorites",msgstr:["Oblíbené"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Zde se zobrazí soubory a složky, které označíte jako oblíbené."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Zde se zobrazí soubory a složky, které jste nedávno pozměnili."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtrovat seznam souborů"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Složku je třeba nějak nazvat."]},Home:{msgid:"Home",msgstr:["Domů"]},Modified:{msgid:"Modified",msgstr:["Změněno"]},Move:{msgid:"Move",msgstr:["Přesounout"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Přesunout do {target}"]},Name:{msgid:"Name",msgstr:["Název"]},New:{msgid:"New",msgstr:["Nové"]},"New folder":{msgid:"New folder",msgstr:["Nová složka"]},"New folder name":{msgid:"New folder name",msgstr:["Název pro novou složku"]},"No files in here":{msgid:"No files in here",msgstr:["Nejsou zde žádné soubory"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Nenalezeny žádné soubory odpovídající vašemu filtru"]},"No matching files":{msgid:"No matching files",msgstr:["Žádné odpovídající soubory"]},Recent:{msgid:"Recent",msgstr:["Nedávné"]},"Select all entries":{msgid:"Select all entries",msgstr:["Vybrat všechny položky"]},"Select entry":{msgid:"Select entry",msgstr:["Vybrat položku"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Vybrat řádek pro {nodename}"]},Size:{msgid:"Size",msgstr:["Velikost"]},Undo:{msgid:"Undo",msgstr:["Zpět"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Nahrajte nějaký obsah nebo proveďte synchronizaci se svými zařízeními!"]}}}}},{locale:"cy_GB",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Welsh (United Kingdom) (https://app.transifex.com/nextcloud/teams/64236/cy_GB/)","Content-Type":"text/plain; charset=UTF-8",Language:"cy_GB","Plural-Forms":"nplurals=4; plural=(n==1) ? 0 : (n==2) ? 1 : (n != 8 && n != 11) ? 2 : 3;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Welsh (United Kingdom) (https://app.transifex.com/nextcloud/teams/64236/cy_GB/)
Content-Type: text/plain; charset=UTF-8
Language: cy_GB
Plural-Forms: nplurals=4; plural=(n==1) ? 0 : (n==2) ? 1 : (n != 8 && n != 11) ? 2 : 3;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"da",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Danish (https://app.transifex.com/nextcloud/teams/64236/da/)","Content-Type":"text/plain; charset=UTF-8",Language:"da","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Danish (https://app.transifex.com/nextcloud/teams/64236/da/)
Content-Type: text/plain; charset=UTF-8
Language: da
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Fortryd"]}}}}},{locale:"de",json:{charset:"utf-8",headers:{"Last-Translator":"Joachim Sokolowski, 2023","Language-Team":"German (https://app.transifex.com/nextcloud/teams/64236/de/)","Content-Type":"text/plain; charset=UTF-8",Language:"de","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Mario Siegmann <mario_siegmann@web.de>, 2023
Markus Eckstein, 2023
Andy Scherzinger <info@andy-scherzinger.de>, 2023
Joachim Sokolowski, 2023
`},msgstr:[`Last-Translator: Joachim Sokolowski, 2023
Language-Team: German (https://app.transifex.com/nextcloud/teams/64236/de/)
Content-Type: text/plain; charset=UTF-8
Language: de
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" ist ein ungültiger Ordnername.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" ist kein zulässiger Ordnername']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" ist innerhalb eines Ordnernamens nicht zulässig.']},"All files":{msgid:"All files",msgstr:["Alle Dateien"]},Choose:{msgid:"Choose",msgstr:["Auswählen"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["{file} auswählen"]},Copy:{msgid:"Copy",msgstr:["Kopieren"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Nach {target} kopieren"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Der neue Ordner konnte nicht erstellt werden."]},"Create directory":{msgid:"Create directory",msgstr:["Verzeichnis erstellen"]},"Current view selector":{msgid:"Current view selector",msgstr:["Aktuelle Ansichtsauswahl"]},Favorites:{msgid:"Favorites",msgstr:["Favoriten"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Dateien und Ordner, die du als Favorit markierst, werden hier angezeigt."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Dateien und Ordner, die du kürzlich geändert hast, werden hier angezeigt."]},"Filter file list":{msgid:"Filter file list",msgstr:["Dateiliste filtern"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Der Ordnername darf nicht leer sein."]},Home:{msgid:"Home",msgstr:["Startseite"]},Modified:{msgid:"Modified",msgstr:["Geändert"]},Move:{msgid:"Move",msgstr:["Verschieben"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Nach {target} verschieben"]},Name:{msgid:"Name",msgstr:["Name"]},New:{msgid:"New",msgstr:["Neu"]},"New folder":{msgid:"New folder",msgstr:["Neuer Ordner"]},"New folder name":{msgid:"New folder name",msgstr:["Neuer Ordnername"]},"No files in here":{msgid:"No files in here",msgstr:["Keine Dateien vorhanden"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Es wurden keine Dateien gefunden, die deinem Filter entsprechen."]},"No matching files":{msgid:"No matching files",msgstr:["Keine passenden Dateien"]},Recent:{msgid:"Recent",msgstr:["Jüngste"]},"Select all entries":{msgid:"Select all entries",msgstr:["Alle Einträge auswählen"]},"Select entry":{msgid:"Select entry",msgstr:["Eintrag auswählen"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Die Zeile für {nodename} auswählen."]},Size:{msgid:"Size",msgstr:["Größe"]},Undo:{msgid:"Undo",msgstr:["Rückgängig"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Lade Inhalte hoch oder synchronisieren diese mit deinen Geräten!"]}}}}},{locale:"de_DE",json:{charset:"utf-8",headers:{"Last-Translator":"Mario Siegmann <mario_siegmann@web.de>, 2024","Language-Team":"German (Germany) (https://app.transifex.com/nextcloud/teams/64236/de_DE/)","Content-Type":"text/plain; charset=UTF-8",Language:"de_DE","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Mark Ziegler <mark.ziegler@rakekniven.de>, 2023
Mario Siegmann <mario_siegmann@web.de>, 2024
`},msgstr:[`Last-Translator: Mario Siegmann <mario_siegmann@web.de>, 2024
Language-Team: German (Germany) (https://app.transifex.com/nextcloud/teams/64236/de_DE/)
Content-Type: text/plain; charset=UTF-8
Language: de_DE
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" ist ein ungültiger Ordnername.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" ist kein zulässiger Ordnername']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" ist innerhalb eines Ordnernamens nicht zulässig.']},"All files":{msgid:"All files",msgstr:["Alle Dateien"]},Choose:{msgid:"Choose",msgstr:["Auswählen"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["{file} auswählen"]},"Choose %n file":{msgid:"Choose %n file",msgid_plural:"Choose %n files",msgstr:["%n Datei auswählen","%n Dateien auswählen"]},Copy:{msgid:"Copy",msgstr:["Kopieren"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Nach {target} kopieren"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Der neue Ordner konnte nicht erstellt werden"]},"Create directory":{msgid:"Create directory",msgstr:["Verzeichnis erstellen"]},"Current view selector":{msgid:"Current view selector",msgstr:["Aktuelle Ansichtsauswahl"]},Favorites:{msgid:"Favorites",msgstr:["Favoriten"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Dateien und Ordner, die Sie als Favorit markieren, werden hier angezeigt."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Dateien und Ordner, die Sie kürzlich geändert haben, werden hier angezeigt."]},"Filter file list":{msgid:"Filter file list",msgstr:["Dateiliste filtern"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Der Ordnername darf nicht leer sein."]},Home:{msgid:"Home",msgstr:["Home"]},Modified:{msgid:"Modified",msgstr:["Geändert"]},Move:{msgid:"Move",msgstr:["Verschieben"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Nach {target} verschieben"]},Name:{msgid:"Name",msgstr:["Name"]},New:{msgid:"New",msgstr:["Neu"]},"New folder":{msgid:"New folder",msgstr:["Neuer Ordner"]},"New folder name":{msgid:"New folder name",msgstr:["Neuer Ordnername"]},"No files in here":{msgid:"No files in here",msgstr:["Hier sind keine Dateien"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Es wurden keine Dateien gefunden, die Ihrem Filter entsprechen."]},"No matching files":{msgid:"No matching files",msgstr:["Keine passenden Dateien"]},Recent:{msgid:"Recent",msgstr:["Neueste"]},"Select all entries":{msgid:"Select all entries",msgstr:["Alle Einträge auswählen"]},"Select entry":{msgid:"Select entry",msgstr:["Eintrag auswählen"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Die Zeile für {nodename} auswählen."]},Size:{msgid:"Size",msgstr:["Größe"]},Undo:{msgid:"Undo",msgstr:["Rückgängig machen"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Laden Sie Inhalte hoch oder synchronisieren Sie diese mit Ihren Geräten!"]}}}}},{locale:"el",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Greek (https://app.transifex.com/nextcloud/teams/64236/el/)","Content-Type":"text/plain; charset=UTF-8",Language:"el","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Greek (https://app.transifex.com/nextcloud/teams/64236/el/)
Content-Type: text/plain; charset=UTF-8
Language: el
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Αναίρεση"]}}}}},{locale:"en_GB",json:{charset:"utf-8",headers:{"Last-Translator":"Café Tango, 2023","Language-Team":"English (United Kingdom) (https://app.transifex.com/nextcloud/teams/64236/en_GB/)","Content-Type":"text/plain; charset=UTF-8",Language:"en_GB","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Andi Chandler <andi@gowling.com>, 2023
Café Tango, 2023
`},msgstr:[`Last-Translator: Café Tango, 2023
Language-Team: English (United Kingdom) (https://app.transifex.com/nextcloud/teams/64236/en_GB/)
Content-Type: text/plain; charset=UTF-8
Language: en_GB
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid file name.':{msgid:'"{name}" is an invalid file name.',msgstr:['"{name}" is an invalid file name.']},'"{name}" is not an allowed filetype':{msgid:'"{name}" is not an allowed filetype',msgstr:['"{name}" is not an allowed filetype']},'"/" is not allowed inside a file name.':{msgid:'"/" is not allowed inside a file name.',msgstr:['"/" is not allowed inside a file name.']},"All files":{msgid:"All files",msgstr:["All files"]},Choose:{msgid:"Choose",msgstr:["Choose"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Choose {file}"]},Copy:{msgid:"Copy",msgstr:["Copy"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copy to {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Could not create the new folder"]},"Create directory":{msgid:"Create directory",msgstr:["Create directory"]},"Current view selector":{msgid:"Current view selector",msgstr:["Current view selector"]},Favorites:{msgid:"Favorites",msgstr:["Favourites"]},"File name cannot be empty.":{msgid:"File name cannot be empty.",msgstr:["File name cannot be empty."]},"Filepicker sections":{msgid:"Filepicker sections",msgstr:["Filepicker sections"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Files and folders you mark as favourite will show up here."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Files and folders you recently modified will show up here."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filter file list"]},Home:{msgid:"Home",msgstr:["Home"]},"MIME type {mime}":{msgid:"MIME type {mime}",msgstr:["MIME type {mime}"]},Modified:{msgid:"Modified",msgstr:["Modified"]},Move:{msgid:"Move",msgstr:["Move"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Move to {target}"]},Name:{msgid:"Name",msgstr:["Name"]},New:{msgid:"New",msgstr:["New"]},"New folder":{msgid:"New folder",msgstr:["New folder"]},"New folder name":{msgid:"New folder name",msgstr:["New folder name"]},"No files in here":{msgid:"No files in here",msgstr:["No files in here"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["No files matching your filter were found."]},"No matching files":{msgid:"No matching files",msgstr:["No matching files"]},Recent:{msgid:"Recent",msgstr:["Recent"]},"Select all entries":{msgid:"Select all entries",msgstr:["Select all entries"]},"Select entry":{msgid:"Select entry",msgstr:["Select entry"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Select the row for {nodename}"]},Size:{msgid:"Size",msgstr:["Size"]},Undo:{msgid:"Undo",msgstr:["Undo"]},unknown:{msgid:"unknown",msgstr:["unknown"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Upload some content or sync with your devices!"]}}}}},{locale:"eo",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Esperanto (https://app.transifex.com/nextcloud/teams/64236/eo/)","Content-Type":"text/plain; charset=UTF-8",Language:"eo","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Esperanto (https://app.transifex.com/nextcloud/teams/64236/eo/)
Content-Type: text/plain; charset=UTF-8
Language: eo
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Malfari"]}}}}},{locale:"es",json:{charset:"utf-8",headers:{"Last-Translator":"Julio C. Ortega, 2024","Language-Team":"Spanish (https://app.transifex.com/nextcloud/teams/64236/es/)","Content-Type":"text/plain; charset=UTF-8",Language:"es","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
FranciscoFJ <dev-ooo@satel-sa.com>, 2023
Julio C. Ortega, 2024
`},msgstr:[`Last-Translator: Julio C. Ortega, 2024
Language-Team: Spanish (https://app.transifex.com/nextcloud/teams/64236/es/)
Content-Type: text/plain; charset=UTF-8
Language: es
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{nombre}" es un nombre de carpeta no válido.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" no es un nombre de carpeta permitido']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" no está permitido dentro del nombre de una carpeta.']},"All files":{msgid:"All files",msgstr:["Todos los archivos"]},Choose:{msgid:"Choose",msgstr:["Escoger"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Escoger {file}"]},"Choose %n file":{msgid:"Choose %n file",msgid_plural:"Choose %n files",msgstr:["Elige %n archivo","Elige %n archivos","Elige %n archivos"]},Copy:{msgid:"Copy",msgstr:["Copiar"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copiar a {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["No se pudo crear la carpeta nueva"]},"Create directory":{msgid:"Create directory",msgstr:["Crear directorio"]},"Current view selector":{msgid:"Current view selector",msgstr:["Selector de vista actual"]},Favorites:{msgid:"Favorites",msgstr:["Favoritos"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Los archivos y carpetas que marque como favoritos aparecerán aquí."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Los archivos y carpetas que modificó recientemente aparecerán aquí."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtrar lista de archivos"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["El nombre de la carpeta no puede estar vacío."]},Home:{msgid:"Home",msgstr:["Inicio"]},Modified:{msgid:"Modified",msgstr:["Modificado"]},Move:{msgid:"Move",msgstr:["Mover"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Mover a {target}"]},Name:{msgid:"Name",msgstr:["Nombre"]},New:{msgid:"New",msgstr:["Nuevo"]},"New folder":{msgid:"New folder",msgstr:[" Nueva carpeta"]},"New folder name":{msgid:"New folder name",msgstr:["Nuevo nombre de carpeta"]},"No files in here":{msgid:"No files in here",msgstr:["No hay archivos aquí"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["No se encontraron archivos que coincidiesen con su filtro."]},"No matching files":{msgid:"No matching files",msgstr:["No hay archivos coincidentes"]},Recent:{msgid:"Recent",msgstr:["Reciente"]},"Select all entries":{msgid:"Select all entries",msgstr:["Seleccionar todas las entradas"]},"Select entry":{msgid:"Select entry",msgstr:["Seleccionar entrada"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Seleccione la fila para {nodename}"]},Size:{msgid:"Size",msgstr:["Tamaño"]},Undo:{msgid:"Undo",msgstr:["Deshacer"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["¡Cargue algún contenido o sincronice con sus dispositivos!"]}}}}},{locale:"es_419",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Latin America) (https://app.transifex.com/nextcloud/teams/64236/es_419/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_419","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Latin America) (https://app.transifex.com/nextcloud/teams/64236/es_419/)
Content-Type: text/plain; charset=UTF-8
Language: es_419
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_AR",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Spanish (Argentina) (https://app.transifex.com/nextcloud/teams/64236/es_AR/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_AR","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Spanish (Argentina) (https://app.transifex.com/nextcloud/teams/64236/es_AR/)
Content-Type: text/plain; charset=UTF-8
Language: es_AR
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Deshacer"]}}}}},{locale:"es_CL",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Chile) (https://app.transifex.com/nextcloud/teams/64236/es_CL/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_CL","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Chile) (https://app.transifex.com/nextcloud/teams/64236/es_CL/)
Content-Type: text/plain; charset=UTF-8
Language: es_CL
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_CO",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Colombia) (https://app.transifex.com/nextcloud/teams/64236/es_CO/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_CO","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Colombia) (https://app.transifex.com/nextcloud/teams/64236/es_CO/)
Content-Type: text/plain; charset=UTF-8
Language: es_CO
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_CR",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Costa Rica) (https://app.transifex.com/nextcloud/teams/64236/es_CR/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_CR","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Costa Rica) (https://app.transifex.com/nextcloud/teams/64236/es_CR/)
Content-Type: text/plain; charset=UTF-8
Language: es_CR
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_DO",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Dominican Republic) (https://app.transifex.com/nextcloud/teams/64236/es_DO/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_DO","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Dominican Republic) (https://app.transifex.com/nextcloud/teams/64236/es_DO/)
Content-Type: text/plain; charset=UTF-8
Language: es_DO
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_EC",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Ecuador) (https://app.transifex.com/nextcloud/teams/64236/es_EC/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_EC","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Ecuador) (https://app.transifex.com/nextcloud/teams/64236/es_EC/)
Content-Type: text/plain; charset=UTF-8
Language: es_EC
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_GT",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Guatemala) (https://app.transifex.com/nextcloud/teams/64236/es_GT/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_GT","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Guatemala) (https://app.transifex.com/nextcloud/teams/64236/es_GT/)
Content-Type: text/plain; charset=UTF-8
Language: es_GT
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_HN",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Honduras) (https://app.transifex.com/nextcloud/teams/64236/es_HN/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_HN","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Honduras) (https://app.transifex.com/nextcloud/teams/64236/es_HN/)
Content-Type: text/plain; charset=UTF-8
Language: es_HN
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_MX",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Spanish (Mexico) (https://app.transifex.com/nextcloud/teams/64236/es_MX/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_MX","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Spanish (Mexico) (https://app.transifex.com/nextcloud/teams/64236/es_MX/)
Content-Type: text/plain; charset=UTF-8
Language: es_MX
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Deshacer"]}}}}},{locale:"es_NI",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Nicaragua) (https://app.transifex.com/nextcloud/teams/64236/es_NI/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_NI","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Nicaragua) (https://app.transifex.com/nextcloud/teams/64236/es_NI/)
Content-Type: text/plain; charset=UTF-8
Language: es_NI
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_PA",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Panama) (https://app.transifex.com/nextcloud/teams/64236/es_PA/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_PA","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Panama) (https://app.transifex.com/nextcloud/teams/64236/es_PA/)
Content-Type: text/plain; charset=UTF-8
Language: es_PA
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_PE",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Peru) (https://app.transifex.com/nextcloud/teams/64236/es_PE/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_PE","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Peru) (https://app.transifex.com/nextcloud/teams/64236/es_PE/)
Content-Type: text/plain; charset=UTF-8
Language: es_PE
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_PR",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Puerto Rico) (https://app.transifex.com/nextcloud/teams/64236/es_PR/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_PR","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Puerto Rico) (https://app.transifex.com/nextcloud/teams/64236/es_PR/)
Content-Type: text/plain; charset=UTF-8
Language: es_PR
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_PY",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Paraguay) (https://app.transifex.com/nextcloud/teams/64236/es_PY/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_PY","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Paraguay) (https://app.transifex.com/nextcloud/teams/64236/es_PY/)
Content-Type: text/plain; charset=UTF-8
Language: es_PY
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_SV",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (El Salvador) (https://app.transifex.com/nextcloud/teams/64236/es_SV/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_SV","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (El Salvador) (https://app.transifex.com/nextcloud/teams/64236/es_SV/)
Content-Type: text/plain; charset=UTF-8
Language: es_SV
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"es_UY",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Spanish (Uruguay) (https://app.transifex.com/nextcloud/teams/64236/es_UY/)","Content-Type":"text/plain; charset=UTF-8",Language:"es_UY","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Spanish (Uruguay) (https://app.transifex.com/nextcloud/teams/64236/es_UY/)
Content-Type: text/plain; charset=UTF-8
Language: es_UY
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"et_EE",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Estonian (Estonia) (https://app.transifex.com/nextcloud/teams/64236/et_EE/)","Content-Type":"text/plain; charset=UTF-8",Language:"et_EE","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Estonian (Estonia) (https://app.transifex.com/nextcloud/teams/64236/et_EE/)
Content-Type: text/plain; charset=UTF-8
Language: et_EE
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"eu",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Basque (https://app.transifex.com/nextcloud/teams/64236/eu/)","Content-Type":"text/plain; charset=UTF-8",Language:"eu","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Basque (https://app.transifex.com/nextcloud/teams/64236/eu/)
Content-Type: text/plain; charset=UTF-8
Language: eu
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Desegin"]}}}}},{locale:"fa",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Persian (https://app.transifex.com/nextcloud/teams/64236/fa/)","Content-Type":"text/plain; charset=UTF-8",Language:"fa","Plural-Forms":"nplurals=2; plural=(n > 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Persian (https://app.transifex.com/nextcloud/teams/64236/fa/)
Content-Type: text/plain; charset=UTF-8
Language: fa
Plural-Forms: nplurals=2; plural=(n > 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["بازگردانی"]}}}}},{locale:"fi_FI",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Finnish (Finland) (https://app.transifex.com/nextcloud/teams/64236/fi_FI/)","Content-Type":"text/plain; charset=UTF-8",Language:"fi_FI","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Finnish (Finland) (https://app.transifex.com/nextcloud/teams/64236/fi_FI/)
Content-Type: text/plain; charset=UTF-8
Language: fi_FI
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Kumoa"]}}}}},{locale:"fo",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Faroese (https://app.transifex.com/nextcloud/teams/64236/fo/)","Content-Type":"text/plain; charset=UTF-8",Language:"fo","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Faroese (https://app.transifex.com/nextcloud/teams/64236/fo/)
Content-Type: text/plain; charset=UTF-8
Language: fo
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"fr",json:{charset:"utf-8",headers:{"Last-Translator":"fleopaul thp, 2023","Language-Team":"French (https://app.transifex.com/nextcloud/teams/64236/fr/)","Content-Type":"text/plain; charset=UTF-8",Language:"fr","Plural-Forms":"nplurals=3; plural=(n == 0 || n == 1) ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Rémi LEBLOND, 2023
Mordecai, 2023
fleopaul thp, 2023
`},msgstr:[`Last-Translator: fleopaul thp, 2023
Language-Team: French (https://app.transifex.com/nextcloud/teams/64236/fr/)
Content-Type: text/plain; charset=UTF-8
Language: fr
Plural-Forms: nplurals=3; plural=(n == 0 || n == 1) ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:["« {name} » n'est pas un nom de dossier valide."]},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:["« {name} » n'est pas un nom de dossier autorisé."]},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:["Le caractère « / » n'est pas autorisé dans un nom de dossier."]},"All files":{msgid:"All files",msgstr:["Tous les fichiers"]},Choose:{msgid:"Choose",msgstr:["Choisir"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Choisir {file}"]},Copy:{msgid:"Copy",msgstr:["Copier"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copier vers {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Impossible de créer le nouveau dossier"]},"Create directory":{msgid:"Create directory",msgstr:["Créer un répertoire"]},"Current view selector":{msgid:"Current view selector",msgstr:["Sélecteur de vue courante"]},Favorites:{msgid:"Favorites",msgstr:["Favoris"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Les fichiers et répertoires marqués en favoris apparaîtront ici."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Les fichiers et répertoires modifiés récemment apparaîtront ici."]},"Filter file list":{msgid:"Filter file list",msgstr:["Liste de filtre de fichiers"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Le nom du dossier ne peut pas être vide."]},Home:{msgid:"Home",msgstr:["Accueil"]},Modified:{msgid:"Modified",msgstr:["Modifié"]},Move:{msgid:"Move",msgstr:["Déplacer"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Déplacer vers {target}"]},Name:{msgid:"Name",msgstr:["Nom"]},New:{msgid:"New",msgstr:["Nouveau"]},"New folder":{msgid:"New folder",msgstr:["Nouveau répertoire"]},"New folder name":{msgid:"New folder name",msgstr:["Nom du nouveau répertoire"]},"No files in here":{msgid:"No files in here",msgstr:["Aucun fichier ici"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Aucun fichier trouvé correspondant à votre filtre."]},"No matching files":{msgid:"No matching files",msgstr:["Aucun fichier trouvé"]},Recent:{msgid:"Recent",msgstr:["Récents"]},"Select all entries":{msgid:"Select all entries",msgstr:["Tous sélectionner"]},"Select entry":{msgid:"Select entry",msgstr:["Sélectionner une entrée"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Sélectionner l'enregistrement pour {nodename}"]},Size:{msgid:"Size",msgstr:["Taille"]},Undo:{msgid:"Undo",msgstr:["Rétablir"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Charger du contenu ou synchroniser avec vos équipements !"]}}}}},{locale:"gd",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Gaelic, Scottish (https://app.transifex.com/nextcloud/teams/64236/gd/)","Content-Type":"text/plain; charset=UTF-8",Language:"gd","Plural-Forms":"nplurals=4; plural=(n==1 || n==11) ? 0 : (n==2 || n==12) ? 1 : (n > 2 && n < 20) ? 2 : 3;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Gaelic, Scottish (https://app.transifex.com/nextcloud/teams/64236/gd/)
Content-Type: text/plain; charset=UTF-8
Language: gd
Plural-Forms: nplurals=4; plural=(n==1 || n==11) ? 0 : (n==2 || n==12) ? 1 : (n > 2 && n < 20) ? 2 : 3;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"gl",json:{charset:"utf-8",headers:{"Last-Translator":"Miguel Anxo Bouzada <mbouzada@gmail.com>, 2023","Language-Team":"Galician (https://app.transifex.com/nextcloud/teams/64236/gl/)","Content-Type":"text/plain; charset=UTF-8",Language:"gl","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Miguel Anxo Bouzada <mbouzada@gmail.com>, 2023
`},msgstr:[`Last-Translator: Miguel Anxo Bouzada <mbouzada@gmail.com>, 2023
Language-Team: Galician (https://app.transifex.com/nextcloud/teams/64236/gl/)
Content-Type: text/plain; charset=UTF-8
Language: gl
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:["«{name}» non é un nome de cartafol válido."]},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:["«{name}» non é un nome de cartafol permitido"]},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:["A «/» non está permitida no nome dun cartafol."]},"All files":{msgid:"All files",msgstr:["Todos os ficheiros"]},Choose:{msgid:"Choose",msgstr:["Escoller"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Escoller {file}"]},Copy:{msgid:"Copy",msgstr:["Copiar"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copiar en  {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Non foi posíbel crear o novo cartafol"]},"Create directory":{msgid:"Create directory",msgstr:["Crear un directorio"]},"Current view selector":{msgid:"Current view selector",msgstr:["Selector de vista actual"]},Favorites:{msgid:"Favorites",msgstr:["Favoritos"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Os ficheiros e cartafoles que marque como favoritos aparecerán aquí."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Os ficheiros e cartafoles que modificou recentemente aparecerán aquí."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtrar a lista de ficheiros"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["O nome do cartafol non pode estar baleiro."]},Home:{msgid:"Home",msgstr:["Inicio"]},Modified:{msgid:"Modified",msgstr:["Modificado"]},Move:{msgid:"Move",msgstr:["Mover"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Mover cara a {target}"]},Name:{msgid:"Name",msgstr:["Nome"]},New:{msgid:"New",msgstr:["Novo"]},"New folder":{msgid:"New folder",msgstr:["Novo cartafol"]},"New folder name":{msgid:"New folder name",msgstr:["Novo nome do cartafol"]},"No files in here":{msgid:"No files in here",msgstr:["Aquí non hai ficheiros"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Non se atopou ningún ficheiro que coincida co filtro."]},"No matching files":{msgid:"No matching files",msgstr:["Non hai ficheiros coincidentes"]},Recent:{msgid:"Recent",msgstr:["Recente"]},"Select all entries":{msgid:"Select all entries",msgstr:["Seleccionar todas as entradas"]},"Select entry":{msgid:"Select entry",msgstr:["Seleccionar a entrada"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Seleccionar a fila para {nodename}"]},Size:{msgid:"Size",msgstr:["Tamaño"]},Undo:{msgid:"Undo",msgstr:["Desfacer"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Enviar algún contido ou sincronizalo cos seus dispositivos!"]}}}}},{locale:"he",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Hebrew (https://app.transifex.com/nextcloud/teams/64236/he/)","Content-Type":"text/plain; charset=UTF-8",Language:"he","Plural-Forms":"nplurals=4; plural=(n == 1 && n % 1 == 0) ? 0 : (n == 2 && n % 1 == 0) ? 1: (n % 10 == 0 && n % 1 == 0 && n > 10) ? 2 : 3;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Hebrew (https://app.transifex.com/nextcloud/teams/64236/he/)
Content-Type: text/plain; charset=UTF-8
Language: he
Plural-Forms: nplurals=4; plural=(n == 1 && n % 1 == 0) ? 0 : (n == 2 && n % 1 == 0) ? 1: (n % 10 == 0 && n % 1 == 0 && n > 10) ? 2 : 3;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["ביטול"]}}}}},{locale:"hi_IN",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Hindi (India) (https://app.transifex.com/nextcloud/teams/64236/hi_IN/)","Content-Type":"text/plain; charset=UTF-8",Language:"hi_IN","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Hindi (India) (https://app.transifex.com/nextcloud/teams/64236/hi_IN/)
Content-Type: text/plain; charset=UTF-8
Language: hi_IN
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"hr",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Croatian (https://app.transifex.com/nextcloud/teams/64236/hr/)","Content-Type":"text/plain; charset=UTF-8",Language:"hr","Plural-Forms":"nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Croatian (https://app.transifex.com/nextcloud/teams/64236/hr/)
Content-Type: text/plain; charset=UTF-8
Language: hr
Plural-Forms: nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"hsb",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Upper Sorbian (https://app.transifex.com/nextcloud/teams/64236/hsb/)","Content-Type":"text/plain; charset=UTF-8",Language:"hsb","Plural-Forms":"nplurals=4; plural=(n%100==1 ? 0 : n%100==2 ? 1 : n%100==3 || n%100==4 ? 2 : 3);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Upper Sorbian (https://app.transifex.com/nextcloud/teams/64236/hsb/)
Content-Type: text/plain; charset=UTF-8
Language: hsb
Plural-Forms: nplurals=4; plural=(n%100==1 ? 0 : n%100==2 ? 1 : n%100==3 || n%100==4 ? 2 : 3);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"hu_HU",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Hungarian (Hungary) (https://app.transifex.com/nextcloud/teams/64236/hu_HU/)","Content-Type":"text/plain; charset=UTF-8",Language:"hu_HU","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Hungarian (Hungary) (https://app.transifex.com/nextcloud/teams/64236/hu_HU/)
Content-Type: text/plain; charset=UTF-8
Language: hu_HU
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Visszavonás"]}}}}},{locale:"hy",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Armenian (https://app.transifex.com/nextcloud/teams/64236/hy/)","Content-Type":"text/plain; charset=UTF-8",Language:"hy","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Armenian (https://app.transifex.com/nextcloud/teams/64236/hy/)
Content-Type: text/plain; charset=UTF-8
Language: hy
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"ia",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Interlingua (https://app.transifex.com/nextcloud/teams/64236/ia/)","Content-Type":"text/plain; charset=UTF-8",Language:"ia","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Interlingua (https://app.transifex.com/nextcloud/teams/64236/ia/)
Content-Type: text/plain; charset=UTF-8
Language: ia
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"id",json:{charset:"utf-8",headers:{"Last-Translator":"Linerly <linerly@proton.me>, 2023","Language-Team":"Indonesian (https://app.transifex.com/nextcloud/teams/64236/id/)","Content-Type":"text/plain; charset=UTF-8",Language:"id","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Linerly <linerly@proton.me>, 2023
`},msgstr:[`Last-Translator: Linerly <linerly@proton.me>, 2023
Language-Team: Indonesian (https://app.transifex.com/nextcloud/teams/64236/id/)
Content-Type: text/plain; charset=UTF-8
Language: id
Plural-Forms: nplurals=1; plural=0;
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" merupakan nama folder yang tidak valid.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" merupakan nama folder yang tidak diperbolehkan']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" tidak diperbolehkan di dalam nama folder.']},"All files":{msgid:"All files",msgstr:["Semua berkas"]},Choose:{msgid:"Choose",msgstr:["Pilih"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Pilih {file}"]},Copy:{msgid:"Copy",msgstr:["Salin"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Salin ke {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Tidak dapat membuat folder baru"]},"Create directory":{msgid:"Create directory",msgstr:["Buat direktori"]},"Current view selector":{msgid:"Current view selector",msgstr:["Pemilih tampilan saat ini"]},Favorites:{msgid:"Favorites",msgstr:["Favorit"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Berkas dan folder yang Anda tandai sebagai favorit akan muncul di sini."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Berkas dan folder yang Anda ubah baru-baru ini akan muncul di sini."]},"Filter file list":{msgid:"Filter file list",msgstr:["Saring daftar berkas"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Name berkas tidak dapat kosong."]},Home:{msgid:"Home",msgstr:["Beranda"]},Modified:{msgid:"Modified",msgstr:["Diubah"]},Move:{msgid:"Move",msgstr:["Pindahkan"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Pindahkan ke {target}"]},Name:{msgid:"Name",msgstr:["Nama"]},New:{msgid:"New",msgstr:["Baru"]},"New folder":{msgid:"New folder",msgstr:["Folder baru"]},"New folder name":{msgid:"New folder name",msgstr:["Nama folder baru"]},"No files in here":{msgid:"No files in here",msgstr:["Tidak ada berkas di sini"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Tidak ada berkas yang cocok dengan penyaringan Anda."]},"No matching files":{msgid:"No matching files",msgstr:["Tidak ada berkas yang cocok"]},Recent:{msgid:"Recent",msgstr:["Terkini"]},"Select all entries":{msgid:"Select all entries",msgstr:["Pilih semua entri"]},"Select entry":{msgid:"Select entry",msgstr:["Pilih entri"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Pilih baris untuk {nodename}"]},Size:{msgid:"Size",msgstr:["Ukuran"]},Undo:{msgid:"Undo",msgstr:["Tidak jadi"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Unggah beberapa konten atau sinkron dengan perangkat Anda!"]}}}}},{locale:"ig",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Igbo (https://app.transifex.com/nextcloud/teams/64236/ig/)","Content-Type":"text/plain; charset=UTF-8",Language:"ig","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Igbo (https://app.transifex.com/nextcloud/teams/64236/ig/)
Content-Type: text/plain; charset=UTF-8
Language: ig
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"is",json:{charset:"utf-8",headers:{"Last-Translator":"Sveinn í Felli <sv1@fellsnet.is>, 2023","Language-Team":"Icelandic (https://app.transifex.com/nextcloud/teams/64236/is/)","Content-Type":"text/plain; charset=UTF-8",Language:"is","Plural-Forms":"nplurals=2; plural=(n % 10 != 1 || n % 100 == 11);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Sveinn í Felli <sv1@fellsnet.is>, 2023
`},msgstr:[`Last-Translator: Sveinn í Felli <sv1@fellsnet.is>, 2023
Language-Team: Icelandic (https://app.transifex.com/nextcloud/teams/64236/is/)
Content-Type: text/plain; charset=UTF-8
Language: is
Plural-Forms: nplurals=2; plural=(n % 10 != 1 || n % 100 == 11);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" er ógilt möppuheiti.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" er ekki leyfilegt möppuheiti']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" er er ekki leyfilegt innan í skráarheiti.']},"All files":{msgid:"All files",msgstr:["Allar skrár"]},Choose:{msgid:"Choose",msgstr:["Veldu"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Veldu {file}"]},Copy:{msgid:"Copy",msgstr:["Afrita"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Afrita í {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Get ekki búið til nýju möppuna"]},"Create directory":{msgid:"Create directory",msgstr:["Búa til möppu"]},"Current view selector":{msgid:"Current view selector",msgstr:["Núverandi val sýnar"]},Favorites:{msgid:"Favorites",msgstr:["Eftirlæti"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Skrár og möppur sem þú merkir sem eftirlæti birtast hér."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Skrár og möppur sem þú breyttir nýlega birtast hér."]},"Filter file list":{msgid:"Filter file list",msgstr:["Sía skráalista"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Möppuheiti má ekki vera tómt."]},Home:{msgid:"Home",msgstr:["Heim"]},Modified:{msgid:"Modified",msgstr:["Breytt"]},Move:{msgid:"Move",msgstr:["Færa"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Færa í {target}"]},Name:{msgid:"Name",msgstr:["Heiti"]},New:{msgid:"New",msgstr:["Nýtt"]},"New folder":{msgid:"New folder",msgstr:["Ný mappa"]},"New folder name":{msgid:"New folder name",msgstr:["Heiti nýrrar möppu"]},"No files in here":{msgid:"No files in here",msgstr:["Engar skrár hér"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Engar skrár fundust sem passa við síuna."]},"No matching files":{msgid:"No matching files",msgstr:["Engar samsvarandi skrár"]},Recent:{msgid:"Recent",msgstr:["Nýlegt"]},"Select all entries":{msgid:"Select all entries",msgstr:["Velja allar færslur"]},"Select entry":{msgid:"Select entry",msgstr:["Velja færslu"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Veldu röðina fyrir {nodename}"]},Size:{msgid:"Size",msgstr:["Stærð"]},Undo:{msgid:"Undo",msgstr:["Afturkalla"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Sendu inn eitthvað efni eða samstilltu við tækin þín!"]}}}}},{locale:"it",json:{charset:"utf-8",headers:{"Last-Translator":"Raffaele Silano <raffaelone@gmail.com>, 2024","Language-Team":"Italian (https://app.transifex.com/nextcloud/teams/64236/it/)","Content-Type":"text/plain; charset=UTF-8",Language:"it","Plural-Forms":"nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Claudio Scandella, 2023
Raffaele Silano <raffaelone@gmail.com>, 2024
`},msgstr:[`Last-Translator: Raffaele Silano <raffaelone@gmail.com>, 2024
Language-Team: Italian (https://app.transifex.com/nextcloud/teams/64236/it/)
Content-Type: text/plain; charset=UTF-8
Language: it
Plural-Forms: nplurals=3; plural=n == 1 ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" non è un nome di cartella valido.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}"  non è un nome di cartella ammesso']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:[`"/" non è ammesso all'interno del nome di una cartella.`]},"All files":{msgid:"All files",msgstr:["Tutti i file"]},Choose:{msgid:"Choose",msgstr:["Scegli"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Scegli {file}"]},Copy:{msgid:"Copy",msgstr:["Copia"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copia in {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Impossibile creare la nuova cartella"]},"Create directory":{msgid:"Create directory",msgstr:["Crea directory"]},"Current view selector":{msgid:"Current view selector",msgstr:["Selettore della vista corrente"]},Favorites:{msgid:"Favorites",msgstr:["Preferiti"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["I file e le cartelle contrassegnate come preferite saranno mostrate qui."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["I file e le cartelle che hai modificato di recente saranno mostrate qui."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtra elenco file"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Il nome della cartella non può essere vuoto."]},Home:{msgid:"Home",msgstr:["Home"]},Modified:{msgid:"Modified",msgstr:["Modificato"]},Move:{msgid:"Move",msgstr:["Sposta"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Sposta in {target}"]},Name:{msgid:"Name",msgstr:["Nome"]},New:{msgid:"New",msgstr:["Nuovo"]},"New folder":{msgid:"New folder",msgstr:["Nuova cartella"]},"New folder name":{msgid:"New folder name",msgstr:["Nuovo nome cartella"]},"No files in here":{msgid:"No files in here",msgstr:["Nessun file qui"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Nessun file che corrisponde al tuo filtro è stato trovato."]},"No matching files":{msgid:"No matching files",msgstr:["Nessun file corrispondente"]},Recent:{msgid:"Recent",msgstr:["Recente"]},"Select all entries":{msgid:"Select all entries",msgstr:["Scegli tutte le voci"]},"Select entry":{msgid:"Select entry",msgstr:["Seleziona la voce"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Seleziona la riga per {nodename}"]},Size:{msgid:"Size",msgstr:["Taglia/dimensioni"]},Undo:{msgid:"Undo",msgstr:["Annulla"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Carica qualche contenuto o sincronizza con i tuoi dispositivi!"]}}}}},{locale:"ja_JP",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Japanese (Japan) (https://app.transifex.com/nextcloud/teams/64236/ja_JP/)","Content-Type":"text/plain; charset=UTF-8",Language:"ja_JP","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Japanese (Japan) (https://app.transifex.com/nextcloud/teams/64236/ja_JP/)
Content-Type: text/plain; charset=UTF-8
Language: ja_JP
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["元に戻す"]}}}}},{locale:"ka",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Georgian (https://app.transifex.com/nextcloud/teams/64236/ka/)","Content-Type":"text/plain; charset=UTF-8",Language:"ka","Plural-Forms":"nplurals=2; plural=(n!=1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Georgian (https://app.transifex.com/nextcloud/teams/64236/ka/)
Content-Type: text/plain; charset=UTF-8
Language: ka
Plural-Forms: nplurals=2; plural=(n!=1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"ka_GE",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Georgian (Georgia) (https://app.transifex.com/nextcloud/teams/64236/ka_GE/)","Content-Type":"text/plain; charset=UTF-8",Language:"ka_GE","Plural-Forms":"nplurals=2; plural=(n!=1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Georgian (Georgia) (https://app.transifex.com/nextcloud/teams/64236/ka_GE/)
Content-Type: text/plain; charset=UTF-8
Language: ka_GE
Plural-Forms: nplurals=2; plural=(n!=1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"kab",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Kabyle (https://app.transifex.com/nextcloud/teams/64236/kab/)","Content-Type":"text/plain; charset=UTF-8",Language:"kab","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Kabyle (https://app.transifex.com/nextcloud/teams/64236/kab/)
Content-Type: text/plain; charset=UTF-8
Language: kab
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Sefsex"]}}}}},{locale:"kk",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Kazakh (https://app.transifex.com/nextcloud/teams/64236/kk/)","Content-Type":"text/plain; charset=UTF-8",Language:"kk","Plural-Forms":"nplurals=2; plural=(n!=1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Kazakh (https://app.transifex.com/nextcloud/teams/64236/kk/)
Content-Type: text/plain; charset=UTF-8
Language: kk
Plural-Forms: nplurals=2; plural=(n!=1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"km",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Khmer (https://app.transifex.com/nextcloud/teams/64236/km/)","Content-Type":"text/plain; charset=UTF-8",Language:"km","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Khmer (https://app.transifex.com/nextcloud/teams/64236/km/)
Content-Type: text/plain; charset=UTF-8
Language: km
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"kn",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Kannada (https://app.transifex.com/nextcloud/teams/64236/kn/)","Content-Type":"text/plain; charset=UTF-8",Language:"kn","Plural-Forms":"nplurals=2; plural=(n > 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Kannada (https://app.transifex.com/nextcloud/teams/64236/kn/)
Content-Type: text/plain; charset=UTF-8
Language: kn
Plural-Forms: nplurals=2; plural=(n > 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"ko",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Korean (https://app.transifex.com/nextcloud/teams/64236/ko/)","Content-Type":"text/plain; charset=UTF-8",Language:"ko","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Korean (https://app.transifex.com/nextcloud/teams/64236/ko/)
Content-Type: text/plain; charset=UTF-8
Language: ko
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["되돌리기"]}}}}},{locale:"la",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Latin (https://app.transifex.com/nextcloud/teams/64236/la/)","Content-Type":"text/plain; charset=UTF-8",Language:"la","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Latin (https://app.transifex.com/nextcloud/teams/64236/la/)
Content-Type: text/plain; charset=UTF-8
Language: la
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"lb",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Luxembourgish (https://app.transifex.com/nextcloud/teams/64236/lb/)","Content-Type":"text/plain; charset=UTF-8",Language:"lb","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Luxembourgish (https://app.transifex.com/nextcloud/teams/64236/lb/)
Content-Type: text/plain; charset=UTF-8
Language: lb
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"lo",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Lao (https://app.transifex.com/nextcloud/teams/64236/lo/)","Content-Type":"text/plain; charset=UTF-8",Language:"lo","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Lao (https://app.transifex.com/nextcloud/teams/64236/lo/)
Content-Type: text/plain; charset=UTF-8
Language: lo
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"lt_LT",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Lithuanian (Lithuania) (https://app.transifex.com/nextcloud/teams/64236/lt_LT/)","Content-Type":"text/plain; charset=UTF-8",Language:"lt_LT","Plural-Forms":"nplurals=4; plural=(n % 10 == 1 && (n % 100 > 19 || n % 100 < 11) ? 0 : (n % 10 >= 2 && n % 10 <=9) && (n % 100 > 19 || n % 100 < 11) ? 1 : n % 1 != 0 ? 2: 3);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Lithuanian (Lithuania) (https://app.transifex.com/nextcloud/teams/64236/lt_LT/)
Content-Type: text/plain; charset=UTF-8
Language: lt_LT
Plural-Forms: nplurals=4; plural=(n % 10 == 1 && (n % 100 > 19 || n % 100 < 11) ? 0 : (n % 10 >= 2 && n % 10 <=9) && (n % 100 > 19 || n % 100 < 11) ? 1 : n % 1 != 0 ? 2: 3);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Atšaukti"]}}}}},{locale:"lv",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Latvian (https://app.transifex.com/nextcloud/teams/64236/lv/)","Content-Type":"text/plain; charset=UTF-8",Language:"lv","Plural-Forms":"nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n != 0 ? 1 : 2);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Latvian (https://app.transifex.com/nextcloud/teams/64236/lv/)
Content-Type: text/plain; charset=UTF-8
Language: lv
Plural-Forms: nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n != 0 ? 1 : 2);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"mk",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Macedonian (https://app.transifex.com/nextcloud/teams/64236/mk/)","Content-Type":"text/plain; charset=UTF-8",Language:"mk","Plural-Forms":"nplurals=2; plural=(n % 10 == 1 && n % 100 != 11) ? 0 : 1;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Macedonian (https://app.transifex.com/nextcloud/teams/64236/mk/)
Content-Type: text/plain; charset=UTF-8
Language: mk
Plural-Forms: nplurals=2; plural=(n % 10 == 1 && n % 100 != 11) ? 0 : 1;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Врати"]}}}}},{locale:"mn",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Mongolian (https://app.transifex.com/nextcloud/teams/64236/mn/)","Content-Type":"text/plain; charset=UTF-8",Language:"mn","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Mongolian (https://app.transifex.com/nextcloud/teams/64236/mn/)
Content-Type: text/plain; charset=UTF-8
Language: mn
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Буцаах"]}}}}},{locale:"mr",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Marathi (https://app.transifex.com/nextcloud/teams/64236/mr/)","Content-Type":"text/plain; charset=UTF-8",Language:"mr","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Marathi (https://app.transifex.com/nextcloud/teams/64236/mr/)
Content-Type: text/plain; charset=UTF-8
Language: mr
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["पूर्ववत करा"]}}}}},{locale:"ms_MY",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Malay (Malaysia) (https://app.transifex.com/nextcloud/teams/64236/ms_MY/)","Content-Type":"text/plain; charset=UTF-8",Language:"ms_MY","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Malay (Malaysia) (https://app.transifex.com/nextcloud/teams/64236/ms_MY/)
Content-Type: text/plain; charset=UTF-8
Language: ms_MY
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"my",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Burmese (https://app.transifex.com/nextcloud/teams/64236/my/)","Content-Type":"text/plain; charset=UTF-8",Language:"my","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Burmese (https://app.transifex.com/nextcloud/teams/64236/my/)
Content-Type: text/plain; charset=UTF-8
Language: my
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["နဂိုအတိုင်းပြန်ထားရန်"]}}}}},{locale:"nb_NO",json:{charset:"utf-8",headers:{"Last-Translator":"D PE, 2023","Language-Team":"Norwegian Bokmål (Norway) (https://app.transifex.com/nextcloud/teams/64236/nb_NO/)","Content-Type":"text/plain; charset=UTF-8",Language:"nb_NO","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
D PE, 2023
`},msgstr:[`Last-Translator: D PE, 2023
Language-Team: Norwegian Bokmål (Norway) (https://app.transifex.com/nextcloud/teams/64236/nb_NO/)
Content-Type: text/plain; charset=UTF-8
Language: nb_NO
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" er et ugyldig mappenavn.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" er ikke et tillatt mappenavn.']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" er ikke tillatt inne i et mappenavn.']},"All files":{msgid:"All files",msgstr:["Alle filer"]},Choose:{msgid:"Choose",msgstr:["Velg"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Velg {fil}"]},Copy:{msgid:"Copy",msgstr:["Kopier"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Kopier til {destinasjon}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Kunne ikke opprette den nye mappen"]},"Create directory":{msgid:"Create directory",msgstr:["Opprett mappe"]},"Current view selector":{msgid:"Current view selector",msgstr:["Nåværende visningsvelger"]},Favorites:{msgid:"Favorites",msgstr:["Favoritter"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Filer og mapper du markerer som favoritter vil vises her."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Filer og mapper du nylig har endret, vil vises her."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtrer filliste"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Mappenavn kan ikke være tomt."]},Home:{msgid:"Home",msgstr:["Hjem"]},Modified:{msgid:"Modified",msgstr:["Modifisert"]},Move:{msgid:"Move",msgstr:["Flytt"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Flytt til {destinasjon}"]},Name:{msgid:"Name",msgstr:["Navn"]},New:{msgid:"New",msgstr:["Ny"]},"New folder":{msgid:"New folder",msgstr:["Ny mappe"]},"New folder name":{msgid:"New folder name",msgstr:["Nytt mappenavn"]},"No files in here":{msgid:"No files in here",msgstr:["Ingen filer her"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Ingen filer funnet med ditt filter."]},"No matching files":{msgid:"No matching files",msgstr:["Ingen treffende filer"]},Recent:{msgid:"Recent",msgstr:["Nylig"]},"Select all entries":{msgid:"Select all entries",msgstr:["Velg alle oppføringer"]},"Select entry":{msgid:"Select entry",msgstr:["Velg oppføring"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Velg raden for {nodenavn}"]},Size:{msgid:"Size",msgstr:["Størrelse"]},Undo:{msgid:"Undo",msgstr:["Angre"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Last opp innhold eller synkroniser med enhetene dine!"]}}}}},{locale:"ne",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Nepali (https://app.transifex.com/nextcloud/teams/64236/ne/)","Content-Type":"text/plain; charset=UTF-8",Language:"ne","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Nepali (https://app.transifex.com/nextcloud/teams/64236/ne/)
Content-Type: text/plain; charset=UTF-8
Language: ne
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"nl",json:{charset:"utf-8",headers:{"Last-Translator":"Jeroen Gui, 2023","Language-Team":"Dutch (https://app.transifex.com/nextcloud/teams/64236/nl/)","Content-Type":"text/plain; charset=UTF-8",Language:"nl","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Joost <joho500@hotmail.com>, 2023
Jeroen Gui, 2023
`},msgstr:[`Last-Translator: Jeroen Gui, 2023
Language-Team: Dutch (https://app.transifex.com/nextcloud/teams/64236/nl/)
Content-Type: text/plain; charset=UTF-8
Language: nl
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" is een ongeldige mapnaam.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" is geen toegestane mapnaam']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" is niet toegestaan binnen een bestandsnaam']},"All files":{msgid:"All files",msgstr:["Alle bestanden"]},Choose:{msgid:"Choose",msgstr:["Kies"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Kies {file}"]},Copy:{msgid:"Copy",msgstr:["Kopieer"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Kopieer naar {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Kon de nieuwe map niet maken"]},"Create directory":{msgid:"Create directory",msgstr:["Maak map"]},"Current view selector":{msgid:"Current view selector",msgstr:["Huidige weergave keuze"]},Favorites:{msgid:"Favorites",msgstr:["Favorieten"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Bestanden en mappen die je favoriet maakt, worden hier getoond."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Bestanden en mappen die je recent hebt gewijzigd, worden hier getoond."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filter bestandslijst"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Mapnaam mag niet leeg zijn."]},Home:{msgid:"Home",msgstr:["Home"]},Modified:{msgid:"Modified",msgstr:["Gewijzigd"]},Move:{msgid:"Move",msgstr:["Verplaatsen"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Verplaats naar {target}"]},Name:{msgid:"Name",msgstr:["Naam"]},New:{msgid:"New",msgstr:["Nieuw"]},"New folder":{msgid:"New folder",msgstr:["Nieuwe map"]},"New folder name":{msgid:"New folder name",msgstr:["Nieuwe mapnaam"]},"No files in here":{msgid:"No files in here",msgstr:["Geen bestanden hier"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Geen bestanden gevonden die voldoen aan je filter."]},"No matching files":{msgid:"No matching files",msgstr:["Geen gevonden bestanden"]},Recent:{msgid:"Recent",msgstr:["Recent"]},"Select all entries":{msgid:"Select all entries",msgstr:["Selecteer alle invoer"]},"Select entry":{msgid:"Select entry",msgstr:["Selecteer invoer"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Selecteer de rij voor {nodename}"]},Size:{msgid:"Size",msgstr:["Grootte"]},Undo:{msgid:"Undo",msgstr:["Ongedaan maken"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Upload inhoud of synchroniseer met je apparaten!"]}}}}},{locale:"nn_NO",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Norwegian Nynorsk (Norway) (https://app.transifex.com/nextcloud/teams/64236/nn_NO/)","Content-Type":"text/plain; charset=UTF-8",Language:"nn_NO","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Norwegian Nynorsk (Norway) (https://app.transifex.com/nextcloud/teams/64236/nn_NO/)
Content-Type: text/plain; charset=UTF-8
Language: nn_NO
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"oc",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Occitan (post 1500) (https://app.transifex.com/nextcloud/teams/64236/oc/)","Content-Type":"text/plain; charset=UTF-8",Language:"oc","Plural-Forms":"nplurals=2; plural=(n > 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Occitan (post 1500) (https://app.transifex.com/nextcloud/teams/64236/oc/)
Content-Type: text/plain; charset=UTF-8
Language: oc
Plural-Forms: nplurals=2; plural=(n > 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Anullar"]}}}}},{locale:"pl",json:{charset:"utf-8",headers:{"Last-Translator":"Valdnet, 2024","Language-Team":"Polish (https://app.transifex.com/nextcloud/teams/64236/pl/)","Content-Type":"text/plain; charset=UTF-8",Language:"pl","Plural-Forms":"nplurals=4; plural=(n==1 ? 0 : (n%10>=2 && n%10<=4) && (n%100<12 || n%100>14) ? 1 : n!=1 && (n%10>=0 && n%10<=1) || (n%10>=5 && n%10<=9) || (n%100>=12 && n%100<=14) ? 2 : 3);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
JUJER wtf, 2023
M H <haincu@o2.pl>, 2023
Valdnet, 2024
`},msgstr:[`Last-Translator: Valdnet, 2024
Language-Team: Polish (https://app.transifex.com/nextcloud/teams/64236/pl/)
Content-Type: text/plain; charset=UTF-8
Language: pl
Plural-Forms: nplurals=4; plural=(n==1 ? 0 : (n%10>=2 && n%10<=4) && (n%100<12 || n%100>14) ? 1 : n!=1 && (n%10>=0 && n%10<=1) || (n%10>=5 && n%10<=9) || (n%100>=12 && n%100<=14) ? 2 : 3);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" jest nieprawidłową nazwą folderu']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" nie jest dozwoloną nazwą folderu']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['Znak "/" nie jest dozwolony w nazwie folderu']},"All files":{msgid:"All files",msgstr:["Wszystkie pliki"]},Choose:{msgid:"Choose",msgstr:["Wybierz"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Wybierz {file}"]},Copy:{msgid:"Copy",msgstr:["Kopiuj"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Skopiuj do {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Nie można utworzyć nowego folderu"]},"Create directory":{msgid:"Create directory",msgstr:["Utwórz katalog"]},"Current view selector":{msgid:"Current view selector",msgstr:["Bieżący selektor widoku"]},Favorites:{msgid:"Favorites",msgstr:["Ulubione"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Pliki i foldery które oznaczysz jako ulubione będą wyświetlały się tutaj"]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Pliki i foldery które ostatnio modyfikowałeś będą wyświetlały się tutaj"]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtruj listę plików"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Nazwa folderu nie może być pusta"]},Home:{msgid:"Home",msgstr:["Strona główna"]},Modified:{msgid:"Modified",msgstr:["Zmodyfikowano"]},Move:{msgid:"Move",msgstr:["Przenieś"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Przejdź do {target}"]},Name:{msgid:"Name",msgstr:["Nazwa"]},New:{msgid:"New",msgstr:["Nowy"]},"New folder":{msgid:"New folder",msgstr:["Nowy folder"]},"New folder name":{msgid:"New folder name",msgstr:["Nowa nazwa folderu"]},"No files in here":{msgid:"No files in here",msgstr:["Brak plików"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Nie znaleziono plików spełniających warunki filtru"]},"No matching files":{msgid:"No matching files",msgstr:["Brak pasujących plików"]},Recent:{msgid:"Recent",msgstr:["Ostatni"]},"Select all entries":{msgid:"Select all entries",msgstr:["Wybierz wszystkie wpisy"]},"Select entry":{msgid:"Select entry",msgstr:["Wybierz wpis"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Wybierz wiersz dla {nodename}"]},Size:{msgid:"Size",msgstr:["Rozmiar"]},Undo:{msgid:"Undo",msgstr:["Cofnij"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Wyślij zawartość lub zsynchronizuj ze swoimi urządzeniami!"]}}}}},{locale:"ps",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Pashto (https://app.transifex.com/nextcloud/teams/64236/ps/)","Content-Type":"text/plain; charset=UTF-8",Language:"ps","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Pashto (https://app.transifex.com/nextcloud/teams/64236/ps/)
Content-Type: text/plain; charset=UTF-8
Language: ps
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"pt_BR",json:{charset:"utf-8",headers:{"Last-Translator":"Flávio Veras <flaviove@gmail.com>, 2023","Language-Team":"Portuguese (Brazil) (https://app.transifex.com/nextcloud/teams/64236/pt_BR/)","Content-Type":"text/plain; charset=UTF-8",Language:"pt_BR","Plural-Forms":"nplurals=3; plural=(n == 0 || n == 1) ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Flávio Veras <flaviove@gmail.com>, 2023
`},msgstr:[`Last-Translator: Flávio Veras <flaviove@gmail.com>, 2023
Language-Team: Portuguese (Brazil) (https://app.transifex.com/nextcloud/teams/64236/pt_BR/)
Content-Type: text/plain; charset=UTF-8
Language: pt_BR
Plural-Forms: nplurals=3; plural=(n == 0 || n == 1) ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" é um nome de pasta inválido.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" não é um nome de pasta permitido']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" não é permitido dentro de um nome de pasta.']},"All files":{msgid:"All files",msgstr:["Todos os arquivos"]},Choose:{msgid:"Choose",msgstr:["Escolher"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Escolher arquivo}"]},Copy:{msgid:"Copy",msgstr:["Copiar"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copiar para {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Não foi possível criar a nova pasta"]},"Create directory":{msgid:"Create directory",msgstr:["Criar diretório"]},"Current view selector":{msgid:"Current view selector",msgstr:["Seletor de visualização atual"]},Favorites:{msgid:"Favorites",msgstr:["Favoritos"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Os arquivos e pastas marcados como favoritos aparecerão aqui."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Arquivos e pastas que você modificou recentemente aparecerão aqui."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtrar lista de arquivos"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["O nome da pasta não pode ficar vazio."]},Home:{msgid:"Home",msgstr:["Home"]},Modified:{msgid:"Modified",msgstr:["Modificado"]},Move:{msgid:"Move",msgstr:["Mover"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Mover para {target}"]},Name:{msgid:"Name",msgstr:["Nome"]},New:{msgid:"New",msgstr:["Novo"]},"New folder":{msgid:"New folder",msgstr:["Nova pasta"]},"New folder name":{msgid:"New folder name",msgstr:["Novo nome de pasta"]},"No files in here":{msgid:"No files in here",msgstr:["Nenhum arquivo aqui"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Nenhum arquivo correspondente ao seu filtro foi encontrado."]},"No matching files":{msgid:"No matching files",msgstr:["Nenhum arquivo correspondente"]},Recent:{msgid:"Recent",msgstr:["Recente"]},"Select all entries":{msgid:"Select all entries",msgstr:["Selecione todas as entradas"]},"Select entry":{msgid:"Select entry",msgstr:["Selecione a entrada"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Selecione a linha para {nodename}"]},Size:{msgid:"Size",msgstr:["Tamanho"]},Undo:{msgid:"Undo",msgstr:["Desfazer"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Carregue algum conteúdo ou sincronize com seus dispositivos!"]}}}}},{locale:"pt_PT",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Portuguese (Portugal) (https://app.transifex.com/nextcloud/teams/64236/pt_PT/)","Content-Type":"text/plain; charset=UTF-8",Language:"pt_PT","Plural-Forms":"nplurals=3; plural=(n == 0 || n == 1) ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Portuguese (Portugal) (https://app.transifex.com/nextcloud/teams/64236/pt_PT/)
Content-Type: text/plain; charset=UTF-8
Language: pt_PT
Plural-Forms: nplurals=3; plural=(n == 0 || n == 1) ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Anular"]}}}}},{locale:"ro",json:{charset:"utf-8",headers:{"Last-Translator":"Daniel MD <dmihaidumitru@gmail.com>, 2023","Language-Team":"Romanian (https://app.transifex.com/nextcloud/teams/64236/ro/)","Content-Type":"text/plain; charset=UTF-8",Language:"ro","Plural-Forms":"nplurals=3; plural=(n==1?0:(((n%100>19)||((n%100==0)&&(n!=0)))?2:1));"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Daniel MD <dmihaidumitru@gmail.com>, 2023
`},msgstr:[`Last-Translator: Daniel MD <dmihaidumitru@gmail.com>, 2023
Language-Team: Romanian (https://app.transifex.com/nextcloud/teams/64236/ro/)
Content-Type: text/plain; charset=UTF-8
Language: ro
Plural-Forms: nplurals=3; plural=(n==1?0:(((n%100>19)||((n%100==0)&&(n!=0)))?2:1));
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" este un nume de director invalid.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" nu este un nume de director permis']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" nu este permis în numele unui director.']},"All files":{msgid:"All files",msgstr:["Toate fișierele"]},Choose:{msgid:"Choose",msgstr:["Alege"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Alege {file}"]},Copy:{msgid:"Copy",msgstr:["Copiază"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Copiază în {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Nu s-a putut crea noul director"]},"Create directory":{msgid:"Create directory",msgstr:["Creează director"]},"Current view selector":{msgid:"Current view selector",msgstr:["Selectorul curent al vizualizării"]},Favorites:{msgid:"Favorites",msgstr:["Favorite"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Fișiere și directoare pe care le marcați ca favorite vor apărea aici."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Fișiere și directoare pe care le-ați modificat recent vor apărea aici."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtrează lista de fișiere"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Numele de director nu poate fi necompletat."]},Home:{msgid:"Home",msgstr:["Acasă"]},Modified:{msgid:"Modified",msgstr:["Modificat"]},Move:{msgid:"Move",msgstr:["Mută"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Mută către {target}"]},Name:{msgid:"Name",msgstr:["Nume"]},New:{msgid:"New",msgstr:["Nou"]},"New folder":{msgid:"New folder",msgstr:["Director nou"]},"New folder name":{msgid:"New folder name",msgstr:["Numele noului director"]},"No files in here":{msgid:"No files in here",msgstr:["Nu există fișiere"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Nu există fișiere potrivite pentru filtrul selectat"]},"No matching files":{msgid:"No matching files",msgstr:["Nu există fișiere potrivite"]},Recent:{msgid:"Recent",msgstr:["Recente"]},"Select all entries":{msgid:"Select all entries",msgstr:["Selectează toate înregistrările"]},"Select entry":{msgid:"Select entry",msgstr:["Selectează înregistrarea"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Selectează rândul pentru {nodename}"]},Size:{msgid:"Size",msgstr:["Mărime"]},Undo:{msgid:"Undo",msgstr:["Anulează"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Încărcați conținut sau sincronizați cu dispozitivele dumneavoastră!"]}}}}},{locale:"ru",json:{charset:"utf-8",headers:{"Last-Translator":"Alex <kekcuha@gmail.com>, 2024","Language-Team":"Russian (https://app.transifex.com/nextcloud/teams/64236/ru/)","Content-Type":"text/plain; charset=UTF-8",Language:"ru","Plural-Forms":"nplurals=4; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<12 || n%100>14) ? 1 : n%10==0 || (n%10>=5 && n%10<=9) || (n%100>=11 && n%100<=14)? 2 : 3);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Max Smith <sevinfolds@gmail.com>, 2023
ashed <craysy@gmail.com>, 2023
Alex <kekcuha@gmail.com>, 2024
`},msgstr:[`Last-Translator: Alex <kekcuha@gmail.com>, 2024
Language-Team: Russian (https://app.transifex.com/nextcloud/teams/64236/ru/)
Content-Type: text/plain; charset=UTF-8
Language: ru
Plural-Forms: nplurals=4; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<12 || n%100>14) ? 1 : n%10==0 || (n%10>=5 && n%10<=9) || (n%100>=11 && n%100<=14)? 2 : 3);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:["«{name}» — недопустимое имя папки."]},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:["«{name}» не является разрешенным именем папки"]},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:["Символ «/» не допускается внутри имени папки."]},"All files":{msgid:"All files",msgstr:["Все файлы"]},Choose:{msgid:"Choose",msgstr:["Выбрать"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Выбрать {file}"]},Copy:{msgid:"Copy",msgstr:["Копировать"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Копировать в «{target}»"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Не удалось создать новую папку"]},"Create directory":{msgid:"Create directory",msgstr:["Создать папку"]},"Current view selector":{msgid:"Current view selector",msgstr:["Переключатель текущего вида"]},Favorites:{msgid:"Favorites",msgstr:["Избранное"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Здесь появятся файлы и папки, которые вы пометили как избранные."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Здесь будут отображаться файлы и папки, которые вы недавно изменили."]},"Filter file list":{msgid:"Filter file list",msgstr:["Фильтровать список файлов"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Имя папки не может быть пустым."]},Home:{msgid:"Home",msgstr:["Home"]},Modified:{msgid:"Modified",msgstr:["Модифицированный"]},Move:{msgid:"Move",msgstr:["Переместить"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Перейти к {target}"]},Name:{msgid:"Name",msgstr:["Имя"]},New:{msgid:"New",msgstr:["Новый"]},"New folder":{msgid:"New folder",msgstr:["Новая папка"]},"New folder name":{msgid:"New folder name",msgstr:["Новое имя папки"]},"No files in here":{msgid:"No files in here",msgstr:["Здесь нет файлов"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Файлы, соответствующие вашему фильтру, не найдены."]},"No matching files":{msgid:"No matching files",msgstr:["Нет подходящих файлов"]},Recent:{msgid:"Recent",msgstr:["Недавний"]},"Select all entries":{msgid:"Select all entries",msgstr:["Выбрать все записи"]},"Select entry":{msgid:"Select entry",msgstr:["Выберите запись"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Выберите строку для {nodename}"]},Size:{msgid:"Size",msgstr:["Размер"]},Undo:{msgid:"Undo",msgstr:["Отменить"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Загрузите контент или синхронизируйте его со своими устройствами!"]}}}}},{locale:"sc",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Sardinian (https://app.transifex.com/nextcloud/teams/64236/sc/)","Content-Type":"text/plain; charset=UTF-8",Language:"sc","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Sardinian (https://app.transifex.com/nextcloud/teams/64236/sc/)
Content-Type: text/plain; charset=UTF-8
Language: sc
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"si",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Sinhala (https://app.transifex.com/nextcloud/teams/64236/si/)","Content-Type":"text/plain; charset=UTF-8",Language:"si","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Sinhala (https://app.transifex.com/nextcloud/teams/64236/si/)
Content-Type: text/plain; charset=UTF-8
Language: si
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["පෙරසේ"]}}}}},{locale:"sk_SK",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Slovak (Slovakia) (https://app.transifex.com/nextcloud/teams/64236/sk_SK/)","Content-Type":"text/plain; charset=UTF-8",Language:"sk_SK","Plural-Forms":"nplurals=4; plural=(n % 1 == 0 && n == 1 ? 0 : n % 1 == 0 && n >= 2 && n <= 4 ? 1 : n % 1 != 0 ? 2: 3);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Slovak (Slovakia) (https://app.transifex.com/nextcloud/teams/64236/sk_SK/)
Content-Type: text/plain; charset=UTF-8
Language: sk_SK
Plural-Forms: nplurals=4; plural=(n % 1 == 0 && n == 1 ? 0 : n % 1 == 0 && n >= 2 && n <= 4 ? 1 : n % 1 != 0 ? 2: 3);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Späť"]}}}}},{locale:"sl",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Slovenian (https://app.transifex.com/nextcloud/teams/64236/sl/)","Content-Type":"text/plain; charset=UTF-8",Language:"sl","Plural-Forms":"nplurals=4; plural=(n%100==1 ? 0 : n%100==2 ? 1 : n%100==3 || n%100==4 ? 2 : 3);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Slovenian (https://app.transifex.com/nextcloud/teams/64236/sl/)
Content-Type: text/plain; charset=UTF-8
Language: sl
Plural-Forms: nplurals=4; plural=(n%100==1 ? 0 : n%100==2 ? 1 : n%100==3 || n%100==4 ? 2 : 3);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Razveljavi"]}}}}},{locale:"sq",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Albanian (https://app.transifex.com/nextcloud/teams/64236/sq/)","Content-Type":"text/plain; charset=UTF-8",Language:"sq","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Albanian (https://app.transifex.com/nextcloud/teams/64236/sq/)
Content-Type: text/plain; charset=UTF-8
Language: sq
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"sr",json:{charset:"utf-8",headers:{"Last-Translator":"Иван Пешић, 2024","Language-Team":"Serbian (https://app.transifex.com/nextcloud/teams/64236/sr/)","Content-Type":"text/plain; charset=UTF-8",Language:"sr","Plural-Forms":"nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Иван Пешић, 2024
`},msgstr:[`Last-Translator: Иван Пешић, 2024
Language-Team: Serbian (https://app.transifex.com/nextcloud/teams/64236/sr/)
Content-Type: text/plain; charset=UTF-8
Language: sr
Plural-Forms: nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:["„{name}” није исправно име фолдера."]},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:["„{name}” није дозвољено име за фолдер."]},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:["„/” није дозвољено унутар имена фолдера."]},"All files":{msgid:"All files",msgstr:["Сви фајлови"]},Choose:{msgid:"Choose",msgstr:["Изаберите"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Изаберите {file}"]},"Choose %n file":{msgid:"Choose %n file",msgid_plural:"Choose %n files",msgstr:["Изаберите %n фајл","Изаберите %n фајла","Изаберите %n фајлова"]},Copy:{msgid:"Copy",msgstr:["Копирај"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Копирај у {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Није могао да се креира нови фолдер"]},"Create directory":{msgid:"Create directory",msgstr:["Креирај директоријум"]},"Current view selector":{msgid:"Current view selector",msgstr:["Бирач тренутног приказа"]},Favorites:{msgid:"Favorites",msgstr:["Омиљено"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Овде ће се појавити фајлови и фолдери које сте означили као омиљене."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Овде ће се појавити фајлови и фолдери који се се недавно изменили."]},"Filter file list":{msgid:"Filter file list",msgstr:["Фитрирање листе фајлова"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Име фолдера не може бити празно."]},Home:{msgid:"Home",msgstr:["Почетак"]},Modified:{msgid:"Modified",msgstr:["Измењено"]},Move:{msgid:"Move",msgstr:["Премести"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Премести у {target}"]},Name:{msgid:"Name",msgstr:["Име"]},New:{msgid:"New",msgstr:["Ново"]},"New folder":{msgid:"New folder",msgstr:["Нови фолдер"]},"New folder name":{msgid:"New folder name",msgstr:["Име новог фолдера"]},"No files in here":{msgid:"No files in here",msgstr:["Овде нема фајлова"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Није пронађен ниједан фајл који задовољава ваш филтер."]},"No matching files":{msgid:"No matching files",msgstr:["Нема таквих фајлова"]},Recent:{msgid:"Recent",msgstr:["Скорашње"]},"Select all entries":{msgid:"Select all entries",msgstr:["Изаберите све ставке"]},"Select entry":{msgid:"Select entry",msgstr:["Изаберите ставку"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Изаберите ред за {nodename}"]},Size:{msgid:"Size",msgstr:["Величина"]},Undo:{msgid:"Undo",msgstr:["Поништи"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Отпремите нешто или синхронизујте са својим уређајима!"]}}}}},{locale:"sr@latin",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Serbian (Latin) (https://app.transifex.com/nextcloud/teams/64236/sr@latin/)","Content-Type":"text/plain; charset=UTF-8",Language:"sr@latin","Plural-Forms":"nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Serbian (Latin) (https://app.transifex.com/nextcloud/teams/64236/sr@latin/)
Content-Type: text/plain; charset=UTF-8
Language: sr@latin
Plural-Forms: nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"sv",json:{charset:"utf-8",headers:{"Last-Translator":"Magnus Höglund, 2024","Language-Team":"Swedish (https://app.transifex.com/nextcloud/teams/64236/sv/)","Content-Type":"text/plain; charset=UTF-8",Language:"sv","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Magnus Höglund, 2024
`},msgstr:[`Last-Translator: Magnus Höglund, 2024
Language-Team: Swedish (https://app.transifex.com/nextcloud/teams/64236/sv/)
Content-Type: text/plain; charset=UTF-8
Language: sv
Plural-Forms: nplurals=2; plural=(n != 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" är ett ogiltigt mappnamn.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" är inte ett tillåtet mappnamn']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" är inte tillåtet i ett mappnamn.']},"All files":{msgid:"All files",msgstr:["Alla filer"]},Choose:{msgid:"Choose",msgstr:["Välj"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Välj {file}"]},"Choose %n file":{msgid:"Choose %n file",msgid_plural:"Choose %n files",msgstr:["Välj %n fil","Välj %n filer"]},Copy:{msgid:"Copy",msgstr:["Kopiera"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Kopiera till {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Kunde inte skapa den nya mappen"]},"Create directory":{msgid:"Create directory",msgstr:["Skapa katalog"]},"Current view selector":{msgid:"Current view selector",msgstr:["Aktuell vyväljare"]},Favorites:{msgid:"Favorites",msgstr:["Favoriter"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Filer och mappar som du markerar som favorit kommer att visas här."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Filer och mappar som du nyligen ändrat kommer att visas här."]},"Filter file list":{msgid:"Filter file list",msgstr:["Filtrera fillistan"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Mappnamnet får inte vara tomt."]},Home:{msgid:"Home",msgstr:["Hem"]},Modified:{msgid:"Modified",msgstr:["Ändrad"]},Move:{msgid:"Move",msgstr:["Flytta"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Flytta till {target}"]},Name:{msgid:"Name",msgstr:["Namn"]},New:{msgid:"New",msgstr:["Ny"]},"New folder":{msgid:"New folder",msgstr:["Ny mapp"]},"New folder name":{msgid:"New folder name",msgstr:["Nytt mappnamn"]},"No files in here":{msgid:"No files in here",msgstr:["Inga filer här"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Inga filer som matchar ditt filter hittades."]},"No matching files":{msgid:"No matching files",msgstr:["Inga matchande filer"]},Recent:{msgid:"Recent",msgstr:["Nyligen"]},"Select all entries":{msgid:"Select all entries",msgstr:["Välj alla poster"]},"Select entry":{msgid:"Select entry",msgstr:["Välj post"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Välj raden för {nodename}"]},Size:{msgid:"Size",msgstr:["Storlek"]},Undo:{msgid:"Undo",msgstr:["Ångra"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Ladda upp lite innehåll eller synkronisera med dina enheter!"]}}}}},{locale:"sw",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Swahili (https://app.transifex.com/nextcloud/teams/64236/sw/)","Content-Type":"text/plain; charset=UTF-8",Language:"sw","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Swahili (https://app.transifex.com/nextcloud/teams/64236/sw/)
Content-Type: text/plain; charset=UTF-8
Language: sw
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"ta",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Tamil (https://app.transifex.com/nextcloud/teams/64236/ta/)","Content-Type":"text/plain; charset=UTF-8",Language:"ta","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Tamil (https://app.transifex.com/nextcloud/teams/64236/ta/)
Content-Type: text/plain; charset=UTF-8
Language: ta
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["செயல்தவிர்"]}}}}},{locale:"th_TH",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Thai (Thailand) (https://app.transifex.com/nextcloud/teams/64236/th_TH/)","Content-Type":"text/plain; charset=UTF-8",Language:"th_TH","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Thai (Thailand) (https://app.transifex.com/nextcloud/teams/64236/th_TH/)
Content-Type: text/plain; charset=UTF-8
Language: th_TH
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["เลิกทำ"]}}}}},{locale:"tk",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Turkmen (https://app.transifex.com/nextcloud/teams/64236/tk/)","Content-Type":"text/plain; charset=UTF-8",Language:"tk","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Turkmen (https://app.transifex.com/nextcloud/teams/64236/tk/)
Content-Type: text/plain; charset=UTF-8
Language: tk
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"tr",json:{charset:"utf-8",headers:{"Last-Translator":"Kaya Zeren <kayazeren@gmail.com>, 2024","Language-Team":"Turkish (https://app.transifex.com/nextcloud/teams/64236/tr/)","Content-Type":"text/plain; charset=UTF-8",Language:"tr","Plural-Forms":"nplurals=2; plural=(n > 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Kaya Zeren <kayazeren@gmail.com>, 2024
`},msgstr:[`Last-Translator: Kaya Zeren <kayazeren@gmail.com>, 2024
Language-Team: Turkish (https://app.transifex.com/nextcloud/teams/64236/tr/)
Content-Type: text/plain; charset=UTF-8
Language: tr
Plural-Forms: nplurals=2; plural=(n > 1);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" geçersiz bir klasör adı.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" izin verilen bir klasör adı değil']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" karakteri klasör adında kullanılamaz.']},"All files":{msgid:"All files",msgstr:["Tüm dosyalar"]},Choose:{msgid:"Choose",msgstr:["Seçin"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["{file} seçin"]},"Choose %n file":{msgid:"Choose %n file",msgid_plural:"Choose %n files",msgstr:["%n dosya seçin","%n dosya seçin"]},Copy:{msgid:"Copy",msgstr:["Kopyala"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["{target} üzerine kopyala"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Yeni klasör oluşturulamadı"]},"Create directory":{msgid:"Create directory",msgstr:["Klasör oluştur"]},"Current view selector":{msgid:"Current view selector",msgstr:["Geçerli görünüm seçici"]},Favorites:{msgid:"Favorites",msgstr:["Sık kullanılanlar"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Sık kullanılan olarak seçtiğiniz dosyalar burada görüntülenir."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Son zamanlarda değiştirdiğiniz dosya ve klasörler burada görüntülenir."]},"Filter file list":{msgid:"Filter file list",msgstr:["Dosya listesini süz"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Klasör adı boş olamaz."]},Home:{msgid:"Home",msgstr:["Giriş"]},Modified:{msgid:"Modified",msgstr:["Değiştirilme"]},Move:{msgid:"Move",msgstr:["Taşı"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["{target} üzerine taşı"]},Name:{msgid:"Name",msgstr:["Ad"]},New:{msgid:"New",msgstr:["Yeni"]},"New folder":{msgid:"New folder",msgstr:["Yeni klasör"]},"New folder name":{msgid:"New folder name",msgstr:["Yeni klasör adı"]},"No files in here":{msgid:"No files in here",msgstr:["Burada herhangi bir dosya yok"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Süzgece uyan bir dosya bulunamadı."]},"No matching files":{msgid:"No matching files",msgstr:["Eşleşen bir dosya yok"]},Recent:{msgid:"Recent",msgstr:["Son kullanılanlar"]},"Select all entries":{msgid:"Select all entries",msgstr:["Tüm kayıtları seç"]},"Select entry":{msgid:"Select entry",msgstr:["Kaydı seç"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["{nodename} satırını seçin"]},Size:{msgid:"Size",msgstr:["Boyut"]},Undo:{msgid:"Undo",msgstr:["Geri al"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Bazı içerikler yükleyin ya da aygıtlarınızla eşitleyin!"]}}}}},{locale:"ug",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Uyghur (https://app.transifex.com/nextcloud/teams/64236/ug/)","Content-Type":"text/plain; charset=UTF-8",Language:"ug","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Uyghur (https://app.transifex.com/nextcloud/teams/64236/ug/)
Content-Type: text/plain; charset=UTF-8
Language: ug
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"uk",json:{charset:"utf-8",headers:{"Last-Translator":"O St <oleksiy.stasevych@gmail.com>, 2024","Language-Team":"Ukrainian (https://app.transifex.com/nextcloud/teams/64236/uk/)","Content-Type":"text/plain; charset=UTF-8",Language:"uk","Plural-Forms":"nplurals=4; plural=(n % 1 == 0 && n % 10 == 1 && n % 100 != 11 ? 0 : n % 1 == 0 && n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 12 || n % 100 > 14) ? 1 : n % 1 == 0 && (n % 10 ==0 || (n % 10 >=5 && n % 10 <=9) || (n % 100 >=11 && n % 100 <=14 )) ? 2: 3);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
O St <oleksiy.stasevych@gmail.com>, 2024
`},msgstr:[`Last-Translator: O St <oleksiy.stasevych@gmail.com>, 2024
Language-Team: Ukrainian (https://app.transifex.com/nextcloud/teams/64236/uk/)
Content-Type: text/plain; charset=UTF-8
Language: uk
Plural-Forms: nplurals=4; plural=(n % 1 == 0 && n % 10 == 1 && n % 100 != 11 ? 0 : n % 1 == 0 && n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 12 || n % 100 > 14) ? 1 : n % 1 == 0 && (n % 10 ==0 || (n % 10 >=5 && n % 10 <=9) || (n % 100 >=11 && n % 100 <=14 )) ? 2: 3);
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:['"{name}" є недійсною назвою для каталогу.']},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:['"{name}" не є дозволеною назвою для каталогу.']},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:['"/" не дозволено у назві каталогу.']},"All files":{msgid:"All files",msgstr:["Всі файли"]},Choose:{msgid:"Choose",msgstr:["Вибрати"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["Вибрати {file}"]},"Choose %n file":{msgid:"Choose %n file",msgid_plural:"Choose %n files",msgstr:["Вибрати %n файл","Вибрати %n файли","Вибрати %n файлів","Вибрати %n файлів"]},Copy:{msgid:"Copy",msgstr:["Копіювати"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["Копіювати до {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["Не вдалося створити новий каталог"]},"Create directory":{msgid:"Create directory",msgstr:["Створити каталог"]},"Current view selector":{msgid:"Current view selector",msgstr:["Вибір подання"]},Favorites:{msgid:"Favorites",msgstr:["Із зірочкою"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["Тут показуватимуться файли та каталоги, які ви позначите зірочкою."]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["Тут показуватимуться файли та каталоги, які було нещодавно змінено."]},"Filter file list":{msgid:"Filter file list",msgstr:["Фільтрувати список файлів"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["Ім'я каталогу не може бути порожнім."]},Home:{msgid:"Home",msgstr:["Домівка"]},Modified:{msgid:"Modified",msgstr:["Змінено"]},Move:{msgid:"Move",msgstr:["Перемістити"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["Перемістити до {target}"]},Name:{msgid:"Name",msgstr:["Ім'я"]},New:{msgid:"New",msgstr:["Новий"]},"New folder":{msgid:"New folder",msgstr:["Новий каталог"]},"New folder name":{msgid:"New folder name",msgstr:["Ім'я нового каталогу"]},"No files in here":{msgid:"No files in here",msgstr:["Тут відсутні файли"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["Відсутні збіги за фільтром."]},"No matching files":{msgid:"No matching files",msgstr:["Відсутні збіги файлів."]},Recent:{msgid:"Recent",msgstr:["Останні"]},"Select all entries":{msgid:"Select all entries",msgstr:["Вибрати всі записи"]},"Select entry":{msgid:"Select entry",msgstr:["Вибрати запис"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["Вибрати рядок для {nodename}"]},Size:{msgid:"Size",msgstr:["Розмір"]},Undo:{msgid:"Undo",msgstr:["Повернути"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["Завантажте вміст або синхронізуйте з вашим пристроєм!"]}}}}},{locale:"ur_PK",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Urdu (Pakistan) (https://app.transifex.com/nextcloud/teams/64236/ur_PK/)","Content-Type":"text/plain; charset=UTF-8",Language:"ur_PK","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Urdu (Pakistan) (https://app.transifex.com/nextcloud/teams/64236/ur_PK/)
Content-Type: text/plain; charset=UTF-8
Language: ur_PK
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"uz",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Uzbek (https://app.transifex.com/nextcloud/teams/64236/uz/)","Content-Type":"text/plain; charset=UTF-8",Language:"uz","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Uzbek (https://app.transifex.com/nextcloud/teams/64236/uz/)
Content-Type: text/plain; charset=UTF-8
Language: uz
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}},{locale:"vi",json:{charset:"utf-8",headers:{"Last-Translator":"Joas Schilling, 2023","Language-Team":"Vietnamese (https://app.transifex.com/nextcloud/teams/64236/vi/)","Content-Type":"text/plain; charset=UTF-8",Language:"vi","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Joas Schilling, 2023
`},msgstr:[`Last-Translator: Joas Schilling, 2023
Language-Team: Vietnamese (https://app.transifex.com/nextcloud/teams/64236/vi/)
Content-Type: text/plain; charset=UTF-8
Language: vi
Plural-Forms: nplurals=1; plural=0;
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:["Hoàn tác"]}}}}},{locale:"zh_CN",json:{charset:"utf-8",headers:{"Last-Translator":"Eric, 2023","Language-Team":"Chinese (China) (https://app.transifex.com/nextcloud/teams/64236/zh_CN/)","Content-Type":"text/plain; charset=UTF-8",Language:"zh_CN","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
ken, 2023
Eric, 2023
`},msgstr:[`Last-Translator: Eric, 2023
Language-Team: Chinese (China) (https://app.transifex.com/nextcloud/teams/64236/zh_CN/)
Content-Type: text/plain; charset=UTF-8
Language: zh_CN
Plural-Forms: nplurals=1; plural=0;
`]},'"{name}" is an invalid folder name.':{msgid:'"{name}" is an invalid folder name.',msgstr:["“{name}”是无效的文件夹名称。"]},'"{name}" is not an allowed folder name':{msgid:'"{name}" is not an allowed folder name',msgstr:["“{name}”不是允许的文件夹名称"]},'"/" is not allowed inside a folder name.':{msgid:'"/" is not allowed inside a folder name.',msgstr:["文件夹名称中不允许包含“/”。"]},"All files":{msgid:"All files",msgstr:["所有文件"]},Choose:{msgid:"Choose",msgstr:["选择"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["选择 {file}"]},Copy:{msgid:"Copy",msgstr:["复制"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["复制到 {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["无法创建新文件夹"]},"Create directory":{msgid:"Create directory",msgstr:["创建目录"]},"Current view selector":{msgid:"Current view selector",msgstr:["当前视图选择器"]},Favorites:{msgid:"Favorites",msgstr:["最爱"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["您标记为最爱的文件与文件夹会显示在这里"]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["您最近修改的文件与文件夹会显示在这里"]},"Filter file list":{msgid:"Filter file list",msgstr:["过滤文件列表"]},"Folder name cannot be empty.":{msgid:"Folder name cannot be empty.",msgstr:["文件夹名称不能为空。"]},Home:{msgid:"Home",msgstr:["主目录"]},Modified:{msgid:"Modified",msgstr:["已修改"]},Move:{msgid:"Move",msgstr:["移动"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["移动至 {target}"]},Name:{msgid:"Name",msgstr:["名称"]},New:{msgid:"New",msgstr:["新"]},"New folder":{msgid:"New folder",msgstr:["新文件夹"]},"New folder name":{msgid:"New folder name",msgstr:["新文件夹名称"]},"No files in here":{msgid:"No files in here",msgstr:["此处无文件"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["找不到符合您过滤条件的文件"]},"No matching files":{msgid:"No matching files",msgstr:["无符合的文件"]},Recent:{msgid:"Recent",msgstr:["最近"]},"Select all entries":{msgid:"Select all entries",msgstr:["选择所有条目"]},"Select entry":{msgid:"Select entry",msgstr:["选择条目"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["选择 {nodename} 的列"]},Size:{msgid:"Size",msgstr:["大小"]},Undo:{msgid:"Undo",msgstr:[" 撤消"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["上传一些项目或与您的设备同步！"]}}}}},{locale:"zh_HK",json:{charset:"utf-8",headers:{"Last-Translator":"Café Tango, 2023","Language-Team":"Chinese (Hong Kong) (https://app.transifex.com/nextcloud/teams/64236/zh_HK/)","Content-Type":"text/plain; charset=UTF-8",Language:"zh_HK","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
Café Tango, 2023
`},msgstr:[`Last-Translator: Café Tango, 2023
Language-Team: Chinese (Hong Kong) (https://app.transifex.com/nextcloud/teams/64236/zh_HK/)
Content-Type: text/plain; charset=UTF-8
Language: zh_HK
Plural-Forms: nplurals=1; plural=0;
`]},'"{name}" is an invalid file name.':{msgid:'"{name}" is an invalid file name.',msgstr:["「{name}」是無效的檔案名稱。"]},'"{name}" is not an allowed filetype':{msgid:'"{name}" is not an allowed filetype',msgstr:["「{name}」並非允許的檔案類型"]},'"/" is not allowed inside a file name.':{msgid:'"/" is not allowed inside a file name.',msgstr:['檔案名稱中不允許使用 "/"。']},"All files":{msgid:"All files",msgstr:["所有檔案"]},Choose:{msgid:"Choose",msgstr:["選擇"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["選擇 {file}"]},Copy:{msgid:"Copy",msgstr:["複製"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["複製到 {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["無法建立新資料夾"]},"Create directory":{msgid:"Create directory",msgstr:["建立目錄"]},"Current view selector":{msgid:"Current view selector",msgstr:["目前檢視選取器"]},Favorites:{msgid:"Favorites",msgstr:["最愛"]},"File name cannot be empty.":{msgid:"File name cannot be empty.",msgstr:["檔案名稱不能為空。"]},"Filepicker sections":{msgid:"Filepicker sections",msgstr:["檔案挑選器部分"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["您標記為最愛的檔案與資料夾將會顯示在此處。"]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["您最近修改的檔案與資料夾將會顯示在此處。"]},"Filter file list":{msgid:"Filter file list",msgstr:["過濾檔案清單"]},Home:{msgid:"Home",msgstr:["首頁"]},"MIME type {mime}":{msgid:"MIME type {mime}",msgstr:["MIME 類型 {mime}"]},Modified:{msgid:"Modified",msgstr:["已修改"]},Move:{msgid:"Move",msgstr:["移動"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["移動至 {target}"]},Name:{msgid:"Name",msgstr:["名稱"]},New:{msgid:"New",msgstr:["新"]},"New folder":{msgid:"New folder",msgstr:["新資料夾"]},"New folder name":{msgid:"New folder name",msgstr:["新資料夾名稱"]},"No files in here":{msgid:"No files in here",msgstr:["此處無檔案"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["找不到符合您過濾條件的檔案。"]},"No matching files":{msgid:"No matching files",msgstr:["無符合的檔案"]},Recent:{msgid:"Recent",msgstr:["最近"]},"Select all entries":{msgid:"Select all entries",msgstr:["選取所有條目"]},"Select entry":{msgid:"Select entry",msgstr:["選取條目"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["選取 {nodename} 的列"]},Size:{msgid:"Size",msgstr:["大小"]},Undo:{msgid:"Undo",msgstr:["還原"]},unknown:{msgid:"unknown",msgstr:["不詳"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["上傳一些內容或與您的裝置同步"]}}}}},{locale:"zh_TW",json:{charset:"utf-8",headers:{"Last-Translator":"黃柏諺 <s8321414@gmail.com>, 2023","Language-Team":"Chinese (Taiwan) (https://app.transifex.com/nextcloud/teams/64236/zh_TW/)","Content-Type":"text/plain; charset=UTF-8",Language:"zh_TW","Plural-Forms":"nplurals=1; plural=0;"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
John Molakvoæ <skjnldsv@protonmail.com>, 2023
黃柏諺 <s8321414@gmail.com>, 2023
`},msgstr:[`Last-Translator: 黃柏諺 <s8321414@gmail.com>, 2023
Language-Team: Chinese (Taiwan) (https://app.transifex.com/nextcloud/teams/64236/zh_TW/)
Content-Type: text/plain; charset=UTF-8
Language: zh_TW
Plural-Forms: nplurals=1; plural=0;
`]},'"{name}" is an invalid file name.':{msgid:'"{name}" is an invalid file name.',msgstr:["「{name}」是無效的檔案名稱。"]},'"{name}" is not an allowed filetype':{msgid:'"{name}" is not an allowed filetype',msgstr:["「{name}」並非允許的檔案類型"]},'"/" is not allowed inside a file name.':{msgid:'"/" is not allowed inside a file name.',msgstr:["檔案名稱中不允許使用「/」。"]},"All files":{msgid:"All files",msgstr:["所有檔案"]},Choose:{msgid:"Choose",msgstr:["選擇"]},"Choose {file}":{msgid:"Choose {file}",msgstr:["選擇 {file}"]},Copy:{msgid:"Copy",msgstr:["複製"]},"Copy to {target}":{msgid:"Copy to {target}",msgstr:["複製到 {target}"]},"Could not create the new folder":{msgid:"Could not create the new folder",msgstr:["無法建立新資料夾"]},"Create directory":{msgid:"Create directory",msgstr:["建立目錄"]},"Current view selector":{msgid:"Current view selector",msgstr:["目前檢視選取器"]},Favorites:{msgid:"Favorites",msgstr:["最愛"]},"File name cannot be empty.":{msgid:"File name cannot be empty.",msgstr:["檔案名稱不能為空。"]},"Filepicker sections":{msgid:"Filepicker sections",msgstr:["檔案挑選器選取"]},"Files and folders you mark as favorite will show up here.":{msgid:"Files and folders you mark as favorite will show up here.",msgstr:["您標記為最愛的檔案與資料夾將會顯示在此處。"]},"Files and folders you recently modified will show up here.":{msgid:"Files and folders you recently modified will show up here.",msgstr:["您最近修改的檔案與資料夾將會顯示在此處。"]},"Filter file list":{msgid:"Filter file list",msgstr:["過濾檔案清單"]},Home:{msgid:"Home",msgstr:["家"]},"Mime type {mime}":{msgid:"Mime type {mime}",msgstr:["Mime type {mime}"]},Modified:{msgid:"Modified",msgstr:["已修改"]},Move:{msgid:"Move",msgstr:["移動"]},"Move to {target}":{msgid:"Move to {target}",msgstr:["移動至 {target}"]},Name:{msgid:"Name",msgstr:["名稱"]},New:{msgid:"New",msgstr:["新"]},"New folder":{msgid:"New folder",msgstr:["新資料夾"]},"New folder name":{msgid:"New folder name",msgstr:["新資料夾名稱"]},"No files in here":{msgid:"No files in here",msgstr:["此處無檔案"]},"No files matching your filter were found.":{msgid:"No files matching your filter were found.",msgstr:["找不到符合您過濾條件的檔案。"]},"No matching files":{msgid:"No matching files",msgstr:["無符合的檔案"]},Recent:{msgid:"Recent",msgstr:["最近"]},"Select all entries":{msgid:"Select all entries",msgstr:["選取所有條目"]},"Select entry":{msgid:"Select entry",msgstr:["選取條目"]},"Select the row for {nodename}":{msgid:"Select the row for {nodename}",msgstr:["選取 {nodename} 的列"]},Size:{msgid:"Size",msgstr:["大小"]},Undo:{msgid:"Undo",msgstr:["復原"]},unknown:{msgid:"unknown",msgstr:["未知"]},"Upload some content or sync with your devices!":{msgid:"Upload some content or sync with your devices!",msgstr:["上傳一些內容或與您的裝置同步"]}}}}},{locale:"zu_ZA",json:{charset:"utf-8",headers:{"Last-Translator":"Transifex Bot <>, 2023","Language-Team":"Zulu (South Africa) (https://app.transifex.com/nextcloud/teams/64236/zu_ZA/)","Content-Type":"text/plain; charset=UTF-8",Language:"zu_ZA","Plural-Forms":"nplurals=2; plural=(n != 1);"},translations:{"":{"":{msgid:"",comments:{translator:`
Translators:
Transifex Bot <>, 2023
`},msgstr:[`Last-Translator: Transifex Bot <>, 2023
Language-Team: Zulu (South Africa) (https://app.transifex.com/nextcloud/teams/64236/zu_ZA/)
Content-Type: text/plain; charset=UTF-8
Language: zu_ZA
Plural-Forms: nplurals=2; plural=(n != 1);
`]},Undo:{msgid:"Undo",comments:{reference:"lib/toast.ts:223"},msgstr:[""]}}}}}].map(e=>U.addTranslation(e.locale,e.json));const F=U.build();F.ngettext.bind(F),F.gettext.bind(F);const q="off",Z="polite",W="assertive";var v=(e=>(e[e.OFF=q]="OFF",e[e.POLITE=Z]="POLITE",e[e.ASSERTIVE=W]="ASSERTIVE",e))(v||{});const Y=7e3;function B(e,s){if(s=Object.assign({timeout:Y,isHTML:!1,type:void 0,selector:void 0,onRemove:()=>{},onClick:void 0,close:!0},s),typeof e=="string"&&!s.isHTML){const a=document.createElement("div");a.innerHTML=e,e=a.innerText}let t=s.type??"";typeof s.onClick=="function"&&(t+=" toast-with-click ");const r=e instanceof Node;let o=v.POLITE;s.ariaLive?o=s.ariaLive:(s.type==="toast-error"||s.type==="toast-undo")&&(o=v.ASSERTIVE);const n=_({[r?"node":"text"]:e,duration:s.timeout,callback:s.onRemove,onClick:s.onClick,close:s.close,gravity:"top",selector:s.selector,position:"right",backgroundColor:"",className:"dialogs "+t,escapeMarkup:!s.isHTML,ariaLive:o});return n.showToast(),n}function se(e,s){return B(e,{...s,type:"toast-error"})}function te(e,s){return B(e,{...s,type:"toast-success"})}export{te as S,se as v};
