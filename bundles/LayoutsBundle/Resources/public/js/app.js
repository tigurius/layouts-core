!function(t){var e={};function r(n){if(e[n])return e[n].exports;var o=e[n]={i:n,l:!1,exports:{}};return t[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=t,r.c=e,r.d=function(t,e,n){r.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},r.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},r.t=function(t,e){if(1&e&&(t=r(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)r.d(n,o,function(e){return t[e]}.bind(null,o));return n},r.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(e,"a",e),e},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r.p="",r(r.s=2)}([function(t,e){!function(){if("function"==typeof window.CustomEvent)return!1;function t(t,e){e=e||{bubbles:!1,cancelable:!1,detail:void 0};var r=document.createEvent("CustomEvent");return r.initCustomEvent(t,e.bubbles,e.cancelable,e.detail),r}t.prototype=window.Event.prototype,window.CustomEvent=t}()},function(t,e,r){},function(t,e,r){"use strict";r.r(e);var n={searchParams:"URLSearchParams"in self,iterable:"Symbol"in self&&"iterator"in Symbol,blob:"FileReader"in self&&"Blob"in self&&function(){try{return new Blob,!0}catch(t){return!1}}(),formData:"FormData"in self,arrayBuffer:"ArrayBuffer"in self};if(n.arrayBuffer)var o=["[object Int8Array]","[object Uint8Array]","[object Uint8ClampedArray]","[object Int16Array]","[object Uint16Array]","[object Int32Array]","[object Uint32Array]","[object Float32Array]","[object Float64Array]"],i=ArrayBuffer.isView||function(t){return t&&o.indexOf(Object.prototype.toString.call(t))>-1};function a(t){if("string"!=typeof t&&(t=String(t)),/[^a-z0-9\-#$%&'*+.^_`|~]/i.test(t))throw new TypeError("Invalid character in header field name");return t.toLowerCase()}function s(t){return"string"!=typeof t&&(t=String(t)),t}function u(t){var e={next:function(){var e=t.shift();return{done:void 0===e,value:e}}};return n.iterable&&(e[Symbol.iterator]=function(){return e}),e}function l(t){this.map={},t instanceof l?t.forEach(function(t,e){this.append(e,t)},this):Array.isArray(t)?t.forEach(function(t){this.append(t[0],t[1])},this):t&&Object.getOwnPropertyNames(t).forEach(function(e){this.append(e,t[e])},this)}function c(t){if(t.bodyUsed)return Promise.reject(new TypeError("Already read"));t.bodyUsed=!0}function f(t){return new Promise(function(e,r){t.onload=function(){e(t.result)},t.onerror=function(){r(t.error)}})}function h(t){var e=new FileReader,r=f(e);return e.readAsArrayBuffer(t),r}function d(t){if(t.slice)return t.slice(0);var e=new Uint8Array(t.byteLength);return e.set(new Uint8Array(t)),e.buffer}function p(){return this.bodyUsed=!1,this._initBody=function(t){var e;this._bodyInit=t,t?"string"==typeof t?this._bodyText=t:n.blob&&Blob.prototype.isPrototypeOf(t)?this._bodyBlob=t:n.formData&&FormData.prototype.isPrototypeOf(t)?this._bodyFormData=t:n.searchParams&&URLSearchParams.prototype.isPrototypeOf(t)?this._bodyText=t.toString():n.arrayBuffer&&n.blob&&((e=t)&&DataView.prototype.isPrototypeOf(e))?(this._bodyArrayBuffer=d(t.buffer),this._bodyInit=new Blob([this._bodyArrayBuffer])):n.arrayBuffer&&(ArrayBuffer.prototype.isPrototypeOf(t)||i(t))?this._bodyArrayBuffer=d(t):this._bodyText=t=Object.prototype.toString.call(t):this._bodyText="",this.headers.get("content-type")||("string"==typeof t?this.headers.set("content-type","text/plain;charset=UTF-8"):this._bodyBlob&&this._bodyBlob.type?this.headers.set("content-type",this._bodyBlob.type):n.searchParams&&URLSearchParams.prototype.isPrototypeOf(t)&&this.headers.set("content-type","application/x-www-form-urlencoded;charset=UTF-8"))},n.blob&&(this.blob=function(){var t=c(this);if(t)return t;if(this._bodyBlob)return Promise.resolve(this._bodyBlob);if(this._bodyArrayBuffer)return Promise.resolve(new Blob([this._bodyArrayBuffer]));if(this._bodyFormData)throw new Error("could not read FormData body as blob");return Promise.resolve(new Blob([this._bodyText]))},this.arrayBuffer=function(){return this._bodyArrayBuffer?c(this)||Promise.resolve(this._bodyArrayBuffer):this.blob().then(h)}),this.text=function(){var t,e,r,n=c(this);if(n)return n;if(this._bodyBlob)return t=this._bodyBlob,e=new FileReader,r=f(e),e.readAsText(t),r;if(this._bodyArrayBuffer)return Promise.resolve(function(t){for(var e=new Uint8Array(t),r=new Array(e.length),n=0;n<e.length;n++)r[n]=String.fromCharCode(e[n]);return r.join("")}(this._bodyArrayBuffer));if(this._bodyFormData)throw new Error("could not read FormData body as text");return Promise.resolve(this._bodyText)},n.formData&&(this.formData=function(){return this.text().then(g)}),this.json=function(){return this.text().then(JSON.parse)},this}l.prototype.append=function(t,e){t=a(t),e=s(e);var r=this.map[t];this.map[t]=r?r+", "+e:e},l.prototype.delete=function(t){delete this.map[a(t)]},l.prototype.get=function(t){return t=a(t),this.has(t)?this.map[t]:null},l.prototype.has=function(t){return this.map.hasOwnProperty(a(t))},l.prototype.set=function(t,e){this.map[a(t)]=s(e)},l.prototype.forEach=function(t,e){for(var r in this.map)this.map.hasOwnProperty(r)&&t.call(e,this.map[r],r,this)},l.prototype.keys=function(){var t=[];return this.forEach(function(e,r){t.push(r)}),u(t)},l.prototype.values=function(){var t=[];return this.forEach(function(e){t.push(e)}),u(t)},l.prototype.entries=function(){var t=[];return this.forEach(function(e,r){t.push([r,e])}),u(t)},n.iterable&&(l.prototype[Symbol.iterator]=l.prototype.entries);var y=["DELETE","GET","HEAD","OPTIONS","POST","PUT"];function b(t,e){var r,n,o=(e=e||{}).body;if(t instanceof b){if(t.bodyUsed)throw new TypeError("Already read");this.url=t.url,this.credentials=t.credentials,e.headers||(this.headers=new l(t.headers)),this.method=t.method,this.mode=t.mode,this.signal=t.signal,o||null==t._bodyInit||(o=t._bodyInit,t.bodyUsed=!0)}else this.url=String(t);if(this.credentials=e.credentials||this.credentials||"same-origin",!e.headers&&this.headers||(this.headers=new l(e.headers)),this.method=(r=e.method||this.method||"GET",n=r.toUpperCase(),y.indexOf(n)>-1?n:r),this.mode=e.mode||this.mode||null,this.signal=e.signal||this.signal,this.referrer=null,("GET"===this.method||"HEAD"===this.method)&&o)throw new TypeError("Body not allowed for GET or HEAD requests");this._initBody(o)}function g(t){var e=new FormData;return t.trim().split("&").forEach(function(t){if(t){var r=t.split("="),n=r.shift().replace(/\+/g," "),o=r.join("=").replace(/\+/g," ");e.append(decodeURIComponent(n),decodeURIComponent(o))}}),e}function v(t,e){e||(e={}),this.type="default",this.status=void 0===e.status?200:e.status,this.ok=this.status>=200&&this.status<300,this.statusText="statusText"in e?e.statusText:"OK",this.headers=new l(e.headers),this.url=e.url||"",this._initBody(t)}b.prototype.clone=function(){return new b(this,{body:this._bodyInit})},p.call(b.prototype),p.call(v.prototype),v.prototype.clone=function(){return new v(this._bodyInit,{status:this.status,statusText:this.statusText,headers:new l(this.headers),url:this.url})},v.error=function(){var t=new v(null,{status:0,statusText:""});return t.type="error",t};var m=[301,302,303,307,308];v.redirect=function(t,e){if(-1===m.indexOf(e))throw new RangeError("Invalid status code");return new v(null,{status:e,headers:{location:t}})};var w=self.DOMException;try{new w}catch(t){(w=function(t,e){this.message=t,this.name=e;var r=Error(t);this.stack=r.stack}).prototype=Object.create(Error.prototype),w.prototype.constructor=w}function E(t,e){return new Promise(function(r,o){var i=new b(t,e);if(i.signal&&i.signal.aborted)return o(new w("Aborted","AbortError"));var a=new XMLHttpRequest;function s(){a.abort()}a.onload=function(){var t,e,n={status:a.status,statusText:a.statusText,headers:(t=a.getAllResponseHeaders()||"",e=new l,t.replace(/\r?\n[\t ]+/g," ").split(/\r?\n/).forEach(function(t){var r=t.split(":"),n=r.shift().trim();if(n){var o=r.join(":").trim();e.append(n,o)}}),e)};n.url="responseURL"in a?a.responseURL:n.headers.get("X-Request-URL");var o="response"in a?a.response:a.responseText;r(new v(o,n))},a.onerror=function(){o(new TypeError("Network request failed"))},a.ontimeout=function(){o(new TypeError("Network request failed"))},a.onabort=function(){o(new w("Aborted","AbortError"))},a.open(i.method,i.url,!0),"include"===i.credentials?a.withCredentials=!0:"omit"===i.credentials&&(a.withCredentials=!1),"responseType"in a&&n.blob&&(a.responseType="blob"),i.headers.forEach(function(t,e){a.setRequestHeader(e,t)}),i.signal&&(i.signal.addEventListener("abort",s),a.onreadystatechange=function(){4===a.readyState&&i.signal.removeEventListener("abort",s)}),a.send(void 0===i._bodyInit?null:i._bodyInit)})}E.polyfill=!0,self.fetch||(self.fetch=E,self.Headers=l,self.Request=b,self.Response=v);r(0);function A(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}function P(t){return function(t){if(Array.isArray(t)){for(var e=0,r=new Array(t.length);e<t.length;e++)r[e]=t[e];return r}}(t)||function(t){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t))return Array.from(t)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}function x(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=[],n=!0,o=!1,i=void 0;try{for(var a,s=t[Symbol.iterator]();!(n=(a=s.next()).done)&&(r.push(a.value),!e||r.length!==e);n=!0);}catch(t){o=!0,i=t}finally{try{n||null==s.return||s.return()}finally{if(o)throw i}}return r}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance")}()}function _(t,e){for(var r=0;r<e.length;r++){var n=e[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}var j=function(){function t(e){if(function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),!e.dataset.pagingRendered){this.el=e;var r=x(e.getElementsByClassName("ajax-container"),1);this.container=r[0],this.nav=P(e.getElementsByClassName("ajax-navigation")),this.loadInitial=this.el.hasAttribute("data-load-initial"),this.baseUrl=this.el.dataset.baseUrl,this.init()}}var e,r,n;return e=t,(r=[{key:"init",value:function(){this.loadInitial&&this.getPage(this.baseUrl),this.nav.length&&this.initPaging(),this.el.dataset.pagingRendered=!0}},{key:"initPaging",value:function(){this.pagerData=function(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{},n=Object.keys(r);"function"==typeof Object.getOwnPropertySymbols&&(n=n.concat(Object.getOwnPropertySymbols(r).filter(function(t){return Object.getOwnPropertyDescriptor(r,t).enumerable}))),n.forEach(function(e){A(t,e,r[e])})}return t}({},this.nav[0].dataset),this.page=parseInt(this.pagerData.page,10),this.totalPages=parseInt(this.pagerData.totalPages,10),this.totalPages>1?this.renderNavigation():this.nav.forEach(function(t){t.removeAttribute("data-template")}),this.setupEvents()}},{key:"renderNavigation",value:function(){var t=this;this.nav.forEach(function(e){e.removeAttribute("data-template"),e.innerHTML=function(t,e){for(var r,n=/<%=([^%>]+)?%>/g,o=/(^( )?(if|for|else|switch|case|break|{|}))(.*)?/g,i="var r=[];\n",a=0,s=function t(e,r){return i+=r?e.match(o)?"".concat(e,"\n"):"r.push(".concat(e,");\n"):""!==e?'r.push("'.concat(e.replace(/"/g,'\\"'),'");\n'):"",t};r=n.exec(t);)s(t.slice(a,r.index))(r[1],!0),a=r.index+r[0].length;return s(t.substr(a,t.length-a)),i+='return r.join("");',new Function(i.replace(/[\r\t\n]/g,"")).apply(e)}(t.pagerData.template,{pages:t.totalPages,page:t.page,url:t.generateUrl.bind(t)})})}},{key:"generateUrl",value:function(t){return"".concat(this.baseUrl,"&page=").concat(t)}},{key:"setupEvents",value:function(){var t=this;this.nav.forEach(function(e){e.addEventListener("click",function(e){"A"===e.target.tagName&&(e.preventDefault(),t.getPage(e.target.href))})})}},{key:"getPage",value:function(t){var e=this;this.loadingStart();var r=/&page=\d+/.exec(t),n=r?parseInt(r[0].replace(/&page=/,""),10):1;fetch(t,{credentials:"same-origin"}).then(function(t){if(t.ok)return t.text();throw new Error("Looks like there was a problem. Status Code: ".concat(t.status))}).then(function(t){e.loadingStop(),r&&e.setNextPage(n),e.renderNewPage(t)}).catch(function(t){e.loadingStop(),console.log("Fetch Error :-S",t)})}},{key:"setNextPage",value:function(t){this.page=t,this.renderNavigation()}},{key:"renderNewPage",value:function(t){switch(this.pagerData.type){case"load_more":this.container.insertAdjacentHTML("beforeend",t);break;default:this.container.innerHTML=t}this.el.dispatchEvent(new CustomEvent("ajax-paging-added",{bubbles:!0,cancelable:!0,detail:{instance:this}}))}},{key:"loadingStart",value:function(){this.el.classList.add("ajax-loading")}},{key:"loadingStop",value:function(){this.el.classList.remove("ajax-loading")}}])&&_(e.prototype,r),n&&_(e,n),t}(),T=function(){P(document.getElementsByClassName("ajax-collection")).forEach(function(t){return new j(t)})};window.addEventListener("load",function(){T()}),window.addEventListener("renderAjaxPaging",function(){T()});r(1)}]);