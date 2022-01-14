var app=function(){"use strict";function _(){}function ae(t){return t()}function se(){return Object.create(null)}function N(t){t.forEach(ae)}function Ue(t){return typeof t=="function"}function Le(t,e){return t!=t?e==e:t!==e||t&&typeof t=="object"||typeof t=="function"}function Be(t){return Object.keys(t).length===0}function ke(t,e,r){t.insertBefore(e,r||null)}function ie(t){t.parentNode.removeChild(t)}function je(t){return document.createElement(t)}function De(t,e,r){r==null?t.removeAttribute(e):t.getAttribute(e)!==r&&t.setAttribute(e,r)}function qe(t){return Array.from(t.childNodes)}let H;function C(t){H=t}const O=[],oe=[],T=[],ue=[],Ie=Promise.resolve();let M=!1;function Fe(){M||(M=!0,Ie.then(fe))}function J(t){T.push(t)}const z=new Set;let P=0;function fe(){const t=H;do{for(;P<O.length;){const e=O[P];P++,C(e),He(e.$$)}for(C(null),O.length=0,P=0;oe.length;)oe.pop()();for(let e=0;e<T.length;e+=1){const r=T[e];z.has(r)||(z.add(r),r())}T.length=0}while(O.length);for(;ue.length;)ue.pop()();M=!1,z.clear(),C(t)}function He(t){if(t.fragment!==null){t.update(),N(t.before_update);const e=t.dirty;t.dirty=[-1],t.fragment&&t.fragment.p(t.ctx,e),t.after_update.forEach(J)}}const Me=new Set;function Je(t,e){t&&t.i&&(Me.delete(t),t.i(e))}function ze(t,e,r,a){const{fragment:n,on_mount:s,on_destroy:o,after_update:u}=t.$$;n&&n.m(e,r),a||J(()=>{const f=s.map(ae).filter(Ue);o?o.push(...f):N(f),t.$$.on_mount=[]}),u.forEach(J)}function Ve(t,e){const r=t.$$;r.fragment!==null&&(N(r.on_destroy),r.fragment&&r.fragment.d(e),r.on_destroy=r.fragment=null,r.ctx=[])}function We(t,e){t.$$.dirty[0]===-1&&(O.push(t),Fe(),t.$$.dirty.fill(0)),t.$$.dirty[e/31|0]|=1<<e%31}function Xe(t,e,r,a,n,s,o,u=[-1]){const f=H;C(t);const c=t.$$={fragment:null,ctx:null,props:s,update:_,not_equal:n,bound:se(),on_mount:[],on_destroy:[],on_disconnect:[],before_update:[],after_update:[],context:new Map(e.context||(f?f.$$.context:[])),callbacks:se(),dirty:u,skip_bound:!1,root:e.target||f.$$.root};o&&o(c.root);let i=!1;if(c.ctx=r?r(t,e.props||{},(l,h,...b)=>{const A=b.length?b[0]:h;return c.ctx&&n(c.ctx[l],c.ctx[l]=A)&&(!c.skip_bound&&c.bound[l]&&c.bound[l](A),i&&We(t,l)),h}):[],c.update(),i=!0,N(c.before_update),c.fragment=a?a(c.ctx):!1,e.target){if(e.hydrate){const l=qe(e.target);c.fragment&&c.fragment.l(l),l.forEach(ie)}else c.fragment&&c.fragment.c();e.intro&&Je(t.$$.fragment),ze(t,e.target,e.anchor,e.customElement),fe()}C(f)}class Ke{$destroy(){Ve(this,1),this.$destroy=_}$on(e,r){const a=this.$$.callbacks[e]||(this.$$.callbacks[e]=[]);return a.push(r),()=>{const n=a.indexOf(r);n!==-1&&a.splice(n,1)}}$set(e){this.$$set&&!Be(e)&&(this.$$.skip_bound=!0,this.$$set(e),this.$$.skip_bound=!1)}}var V={exports:{}},le=function(e,r){return function(){for(var n=new Array(arguments.length),s=0;s<n.length;s++)n[s]=arguments[s];return e.apply(r,n)}},Ye=le,w=Object.prototype.toString;function W(t){return w.call(t)==="[object Array]"}function X(t){return typeof t=="undefined"}function Ge(t){return t!==null&&!X(t)&&t.constructor!==null&&!X(t.constructor)&&typeof t.constructor.isBuffer=="function"&&t.constructor.isBuffer(t)}function Qe(t){return w.call(t)==="[object ArrayBuffer]"}function Ze(t){return typeof FormData!="undefined"&&t instanceof FormData}function et(t){var e;return typeof ArrayBuffer!="undefined"&&ArrayBuffer.isView?e=ArrayBuffer.isView(t):e=t&&t.buffer&&t.buffer instanceof ArrayBuffer,e}function tt(t){return typeof t=="string"}function rt(t){return typeof t=="number"}function ce(t){return t!==null&&typeof t=="object"}function U(t){if(w.call(t)!=="[object Object]")return!1;var e=Object.getPrototypeOf(t);return e===null||e===Object.prototype}function nt(t){return w.call(t)==="[object Date]"}function at(t){return w.call(t)==="[object File]"}function st(t){return w.call(t)==="[object Blob]"}function de(t){return w.call(t)==="[object Function]"}function it(t){return ce(t)&&de(t.pipe)}function ot(t){return typeof URLSearchParams!="undefined"&&t instanceof URLSearchParams}function ut(t){return t.trim?t.trim():t.replace(/^\s+|\s+$/g,"")}function ft(){return typeof navigator!="undefined"&&(navigator.product==="ReactNative"||navigator.product==="NativeScript"||navigator.product==="NS")?!1:typeof window!="undefined"&&typeof document!="undefined"}function K(t,e){if(!(t===null||typeof t=="undefined"))if(typeof t!="object"&&(t=[t]),W(t))for(var r=0,a=t.length;r<a;r++)e.call(null,t[r],r,t);else for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&e.call(null,t[n],n,t)}function Y(){var t={};function e(n,s){U(t[s])&&U(n)?t[s]=Y(t[s],n):U(n)?t[s]=Y({},n):W(n)?t[s]=n.slice():t[s]=n}for(var r=0,a=arguments.length;r<a;r++)K(arguments[r],e);return t}function lt(t,e,r){return K(e,function(n,s){r&&typeof n=="function"?t[s]=Ye(n,r):t[s]=n}),t}function ct(t){return t.charCodeAt(0)===65279&&(t=t.slice(1)),t}var p={isArray:W,isArrayBuffer:Qe,isBuffer:Ge,isFormData:Ze,isArrayBufferView:et,isString:tt,isNumber:rt,isObject:ce,isPlainObject:U,isUndefined:X,isDate:nt,isFile:at,isBlob:st,isFunction:de,isStream:it,isURLSearchParams:ot,isStandardBrowserEnv:ft,forEach:K,merge:Y,extend:lt,trim:ut,stripBOM:ct},g=p;function he(t){return encodeURIComponent(t).replace(/%3A/gi,":").replace(/%24/g,"$").replace(/%2C/gi,",").replace(/%20/g,"+").replace(/%5B/gi,"[").replace(/%5D/gi,"]")}var pe=function(e,r,a){if(!r)return e;var n;if(a)n=a(r);else if(g.isURLSearchParams(r))n=r.toString();else{var s=[];g.forEach(r,function(f,c){f===null||typeof f=="undefined"||(g.isArray(f)?c=c+"[]":f=[f],g.forEach(f,function(l){g.isDate(l)?l=l.toISOString():g.isObject(l)&&(l=JSON.stringify(l)),s.push(he(c)+"="+he(l))}))}),n=s.join("&")}if(n){var o=e.indexOf("#");o!==-1&&(e=e.slice(0,o)),e+=(e.indexOf("?")===-1?"?":"&")+n}return e},dt=p;function L(){this.handlers=[]}L.prototype.use=function(e,r,a){return this.handlers.push({fulfilled:e,rejected:r,synchronous:a?a.synchronous:!1,runWhen:a?a.runWhen:null}),this.handlers.length-1},L.prototype.eject=function(e){this.handlers[e]&&(this.handlers[e]=null)},L.prototype.forEach=function(e){dt.forEach(this.handlers,function(a){a!==null&&e(a)})};var ht=L,pt=p,mt=function(e,r){pt.forEach(e,function(n,s){s!==r&&s.toUpperCase()===r.toUpperCase()&&(e[r]=n,delete e[s])})},me=function(e,r,a,n,s){return e.config=r,a&&(e.code=a),e.request=n,e.response=s,e.isAxiosError=!0,e.toJSON=function(){return{message:this.message,name:this.name,description:this.description,number:this.number,fileName:this.fileName,lineNumber:this.lineNumber,columnNumber:this.columnNumber,stack:this.stack,config:this.config,code:this.code,status:this.response&&this.response.status?this.response.status:null}},e},vt=me,ve=function(e,r,a,n,s){var o=new Error(e);return vt(o,r,a,n,s)},yt=ve,bt=function(e,r,a){var n=a.config.validateStatus;!a.status||!n||n(a.status)?e(a):r(yt("Request failed with status code "+a.status,a.config,null,a.request,a))},B=p,wt=B.isStandardBrowserEnv()?function(){return{write:function(r,a,n,s,o,u){var f=[];f.push(r+"="+encodeURIComponent(a)),B.isNumber(n)&&f.push("expires="+new Date(n).toGMTString()),B.isString(s)&&f.push("path="+s),B.isString(o)&&f.push("domain="+o),u===!0&&f.push("secure"),document.cookie=f.join("; ")},read:function(r){var a=document.cookie.match(new RegExp("(^|;\\s*)("+r+")=([^;]*)"));return a?decodeURIComponent(a[3]):null},remove:function(r){this.write(r,"",Date.now()-864e5)}}}():function(){return{write:function(){},read:function(){return null},remove:function(){}}}(),Et=function(e){return/^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(e)},gt=function(e,r){return r?e.replace(/\/+$/,"")+"/"+r.replace(/^\/+/,""):e},xt=Et,$t=gt,St=function(e,r){return e&&!xt(r)?$t(e,r):r},G=p,_t=["age","authorization","content-length","content-type","etag","expires","from","host","if-modified-since","if-unmodified-since","last-modified","location","max-forwards","proxy-authorization","referer","retry-after","user-agent"],Ct=function(e){var r={},a,n,s;return e&&G.forEach(e.split(`
`),function(u){if(s=u.indexOf(":"),a=G.trim(u.substr(0,s)).toLowerCase(),n=G.trim(u.substr(s+1)),a){if(r[a]&&_t.indexOf(a)>=0)return;a==="set-cookie"?r[a]=(r[a]?r[a]:[]).concat([n]):r[a]=r[a]?r[a]+", "+n:n}}),r},ye=p,Ot=ye.isStandardBrowserEnv()?function(){var e=/(msie|trident)/i.test(navigator.userAgent),r=document.createElement("a"),a;function n(s){var o=s;return e&&(r.setAttribute("href",o),o=r.href),r.setAttribute("href",o),{href:r.href,protocol:r.protocol?r.protocol.replace(/:$/,""):"",host:r.host,search:r.search?r.search.replace(/^\?/,""):"",hash:r.hash?r.hash.replace(/^#/,""):"",hostname:r.hostname,port:r.port,pathname:r.pathname.charAt(0)==="/"?r.pathname:"/"+r.pathname}}return a=n(window.location.href),function(o){var u=ye.isString(o)?n(o):o;return u.protocol===a.protocol&&u.host===a.host}}():function(){return function(){return!0}}();function Q(t){this.message=t}Q.prototype.toString=function(){return"Cancel"+(this.message?": "+this.message:"")},Q.prototype.__CANCEL__=!0;var k=Q,j=p,Rt=bt,At=wt,Nt=pe,Tt=St,Pt=Ct,Ut=Ot,Z=ve,Lt=q,Bt=k,be=function(e){return new Promise(function(a,n){var s=e.data,o=e.headers,u=e.responseType,f;function c(){e.cancelToken&&e.cancelToken.unsubscribe(f),e.signal&&e.signal.removeEventListener("abort",f)}j.isFormData(s)&&delete o["Content-Type"];var i=new XMLHttpRequest;if(e.auth){var l=e.auth.username||"",h=e.auth.password?unescape(encodeURIComponent(e.auth.password)):"";o.Authorization="Basic "+btoa(l+":"+h)}var b=Tt(e.baseURL,e.url);i.open(e.method.toUpperCase(),Nt(b,e.params,e.paramsSerializer),!0),i.timeout=e.timeout;function A(){if(!!i){var v="getAllResponseHeaders"in i?Pt(i.getAllResponseHeaders()):null,S=!u||u==="text"||u==="json"?i.responseText:i.response,E={data:S,status:i.status,statusText:i.statusText,headers:v,config:e,request:i};Rt(function(ne){a(ne),c()},function(ne){n(ne),c()},E),i=null}}if("onloadend"in i?i.onloadend=A:i.onreadystatechange=function(){!i||i.readyState!==4||i.status===0&&!(i.responseURL&&i.responseURL.indexOf("file:")===0)||setTimeout(A)},i.onabort=function(){!i||(n(Z("Request aborted",e,"ECONNABORTED",i)),i=null)},i.onerror=function(){n(Z("Network Error",e,null,i)),i=null},i.ontimeout=function(){var S=e.timeout?"timeout of "+e.timeout+"ms exceeded":"timeout exceeded",E=e.transitional||Lt.transitional;e.timeoutErrorMessage&&(S=e.timeoutErrorMessage),n(Z(S,e,E.clarifyTimeoutError?"ETIMEDOUT":"ECONNABORTED",i)),i=null},j.isStandardBrowserEnv()){var Pe=(e.withCredentials||Ut(b))&&e.xsrfCookieName?At.read(e.xsrfCookieName):void 0;Pe&&(o[e.xsrfHeaderName]=Pe)}"setRequestHeader"in i&&j.forEach(o,function(S,E){typeof s=="undefined"&&E.toLowerCase()==="content-type"?delete o[E]:i.setRequestHeader(E,S)}),j.isUndefined(e.withCredentials)||(i.withCredentials=!!e.withCredentials),u&&u!=="json"&&(i.responseType=e.responseType),typeof e.onDownloadProgress=="function"&&i.addEventListener("progress",e.onDownloadProgress),typeof e.onUploadProgress=="function"&&i.upload&&i.upload.addEventListener("progress",e.onUploadProgress),(e.cancelToken||e.signal)&&(f=function(v){!i||(n(!v||v&&v.type?new Bt("canceled"):v),i.abort(),i=null)},e.cancelToken&&e.cancelToken.subscribe(f),e.signal&&(e.signal.aborted?f():e.signal.addEventListener("abort",f))),s||(s=null),i.send(s)})},d=p,we=mt,kt=me,jt={"Content-Type":"application/x-www-form-urlencoded"};function Ee(t,e){!d.isUndefined(t)&&d.isUndefined(t["Content-Type"])&&(t["Content-Type"]=e)}function Dt(){var t;return(typeof XMLHttpRequest!="undefined"||typeof process!="undefined"&&Object.prototype.toString.call(process)==="[object process]")&&(t=be),t}function qt(t,e,r){if(d.isString(t))try{return(e||JSON.parse)(t),d.trim(t)}catch(a){if(a.name!=="SyntaxError")throw a}return(r||JSON.stringify)(t)}var D={transitional:{silentJSONParsing:!0,forcedJSONParsing:!0,clarifyTimeoutError:!1},adapter:Dt(),transformRequest:[function(e,r){return we(r,"Accept"),we(r,"Content-Type"),d.isFormData(e)||d.isArrayBuffer(e)||d.isBuffer(e)||d.isStream(e)||d.isFile(e)||d.isBlob(e)?e:d.isArrayBufferView(e)?e.buffer:d.isURLSearchParams(e)?(Ee(r,"application/x-www-form-urlencoded;charset=utf-8"),e.toString()):d.isObject(e)||r&&r["Content-Type"]==="application/json"?(Ee(r,"application/json"),qt(e)):e}],transformResponse:[function(e){var r=this.transitional||D.transitional,a=r&&r.silentJSONParsing,n=r&&r.forcedJSONParsing,s=!a&&this.responseType==="json";if(s||n&&d.isString(e)&&e.length)try{return JSON.parse(e)}catch(o){if(s)throw o.name==="SyntaxError"?kt(o,this,"E_JSON_PARSE"):o}return e}],timeout:0,xsrfCookieName:"XSRF-TOKEN",xsrfHeaderName:"X-XSRF-TOKEN",maxContentLength:-1,maxBodyLength:-1,validateStatus:function(e){return e>=200&&e<300},headers:{common:{Accept:"application/json, text/plain, */*"}}};d.forEach(["delete","get","head"],function(e){D.headers[e]={}}),d.forEach(["post","put","patch"],function(e){D.headers[e]=d.merge(jt)});var q=D,It=p,Ft=q,Ht=function(e,r,a){var n=this||Ft;return It.forEach(a,function(o){e=o.call(n,e,r)}),e},ge=function(e){return!!(e&&e.__CANCEL__)},xe=p,ee=Ht,Mt=ge,Jt=q,zt=k;function te(t){if(t.cancelToken&&t.cancelToken.throwIfRequested(),t.signal&&t.signal.aborted)throw new zt("canceled")}var Vt=function(e){te(e),e.headers=e.headers||{},e.data=ee.call(e,e.data,e.headers,e.transformRequest),e.headers=xe.merge(e.headers.common||{},e.headers[e.method]||{},e.headers),xe.forEach(["delete","get","head","post","put","patch","common"],function(n){delete e.headers[n]});var r=e.adapter||Jt.adapter;return r(e).then(function(n){return te(e),n.data=ee.call(e,n.data,n.headers,e.transformResponse),n},function(n){return Mt(n)||(te(e),n&&n.response&&(n.response.data=ee.call(e,n.response.data,n.response.headers,e.transformResponse))),Promise.reject(n)})},m=p,$e=function(e,r){r=r||{};var a={};function n(i,l){return m.isPlainObject(i)&&m.isPlainObject(l)?m.merge(i,l):m.isPlainObject(l)?m.merge({},l):m.isArray(l)?l.slice():l}function s(i){if(m.isUndefined(r[i])){if(!m.isUndefined(e[i]))return n(void 0,e[i])}else return n(e[i],r[i])}function o(i){if(!m.isUndefined(r[i]))return n(void 0,r[i])}function u(i){if(m.isUndefined(r[i])){if(!m.isUndefined(e[i]))return n(void 0,e[i])}else return n(void 0,r[i])}function f(i){if(i in r)return n(e[i],r[i]);if(i in e)return n(void 0,e[i])}var c={url:o,method:o,data:o,baseURL:u,transformRequest:u,transformResponse:u,paramsSerializer:u,timeout:u,timeoutMessage:u,withCredentials:u,adapter:u,responseType:u,xsrfCookieName:u,xsrfHeaderName:u,onUploadProgress:u,onDownloadProgress:u,decompress:u,maxContentLength:u,maxBodyLength:u,transport:u,httpAgent:u,httpsAgent:u,cancelToken:u,socketPath:u,responseEncoding:u,validateStatus:f};return m.forEach(Object.keys(e).concat(Object.keys(r)),function(l){var h=c[l]||s,b=h(l);m.isUndefined(b)&&h!==f||(a[l]=b)}),a},Se={version:"0.24.0"},Wt=Se.version,re={};["object","boolean","number","function","string","symbol"].forEach(function(t,e){re[t]=function(a){return typeof a===t||"a"+(e<1?"n ":" ")+t}});var _e={};re.transitional=function(e,r,a){function n(s,o){return"[Axios v"+Wt+"] Transitional option '"+s+"'"+o+(a?". "+a:"")}return function(s,o,u){if(e===!1)throw new Error(n(o," has been removed"+(r?" in "+r:"")));return r&&!_e[o]&&(_e[o]=!0,console.warn(n(o," has been deprecated since v"+r+" and will be removed in the near future"))),e?e(s,o,u):!0}};function Xt(t,e,r){if(typeof t!="object")throw new TypeError("options must be an object");for(var a=Object.keys(t),n=a.length;n-- >0;){var s=a[n],o=e[s];if(o){var u=t[s],f=u===void 0||o(u,s,t);if(f!==!0)throw new TypeError("option "+s+" must be "+f);continue}if(r!==!0)throw Error("Unknown option "+s)}}var Kt={assertOptions:Xt,validators:re},Ce=p,Yt=pe,Oe=ht,Re=Vt,I=$e,Ae=Kt,x=Ae.validators;function R(t){this.defaults=t,this.interceptors={request:new Oe,response:new Oe}}R.prototype.request=function(e){typeof e=="string"?(e=arguments[1]||{},e.url=arguments[0]):e=e||{},e=I(this.defaults,e),e.method?e.method=e.method.toLowerCase():this.defaults.method?e.method=this.defaults.method.toLowerCase():e.method="get";var r=e.transitional;r!==void 0&&Ae.assertOptions(r,{silentJSONParsing:x.transitional(x.boolean),forcedJSONParsing:x.transitional(x.boolean),clarifyTimeoutError:x.transitional(x.boolean)},!1);var a=[],n=!0;this.interceptors.request.forEach(function(h){typeof h.runWhen=="function"&&h.runWhen(e)===!1||(n=n&&h.synchronous,a.unshift(h.fulfilled,h.rejected))});var s=[];this.interceptors.response.forEach(function(h){s.push(h.fulfilled,h.rejected)});var o;if(!n){var u=[Re,void 0];for(Array.prototype.unshift.apply(u,a),u=u.concat(s),o=Promise.resolve(e);u.length;)o=o.then(u.shift(),u.shift());return o}for(var f=e;a.length;){var c=a.shift(),i=a.shift();try{f=c(f)}catch(l){i(l);break}}try{o=Re(f)}catch(l){return Promise.reject(l)}for(;s.length;)o=o.then(s.shift(),s.shift());return o},R.prototype.getUri=function(e){return e=I(this.defaults,e),Yt(e.url,e.params,e.paramsSerializer).replace(/^\?/,"")},Ce.forEach(["delete","get","head","options"],function(e){R.prototype[e]=function(r,a){return this.request(I(a||{},{method:e,url:r,data:(a||{}).data}))}}),Ce.forEach(["post","put","patch"],function(e){R.prototype[e]=function(r,a,n){return this.request(I(n||{},{method:e,url:r,data:a}))}});var Gt=R,Qt=k;function $(t){if(typeof t!="function")throw new TypeError("executor must be a function.");var e;this.promise=new Promise(function(n){e=n});var r=this;this.promise.then(function(a){if(!!r._listeners){var n,s=r._listeners.length;for(n=0;n<s;n++)r._listeners[n](a);r._listeners=null}}),this.promise.then=function(a){var n,s=new Promise(function(o){r.subscribe(o),n=o}).then(a);return s.cancel=function(){r.unsubscribe(n)},s},t(function(n){r.reason||(r.reason=new Qt(n),e(r.reason))})}$.prototype.throwIfRequested=function(){if(this.reason)throw this.reason},$.prototype.subscribe=function(e){if(this.reason){e(this.reason);return}this._listeners?this._listeners.push(e):this._listeners=[e]},$.prototype.unsubscribe=function(e){if(!!this._listeners){var r=this._listeners.indexOf(e);r!==-1&&this._listeners.splice(r,1)}},$.source=function(){var e,r=new $(function(n){e=n});return{token:r,cancel:e}};var Zt=$,er=function(e){return function(a){return e.apply(null,a)}},tr=function(e){return typeof e=="object"&&e.isAxiosError===!0},Ne=p,rr=le,F=Gt,nr=$e,ar=q;function Te(t){var e=new F(t),r=rr(F.prototype.request,e);return Ne.extend(r,F.prototype,e),Ne.extend(r,e),r.create=function(n){return Te(nr(t,n))},r}var y=Te(ar);y.Axios=F,y.Cancel=k,y.CancelToken=Zt,y.isCancel=ge,y.VERSION=Se.version,y.all=function(e){return Promise.all(e)},y.spread=er,y.isAxiosError=tr,V.exports=y,V.exports.default=y;var sr=V.exports;function ir(t){let e;return{c(){e=je("main"),e.innerHTML=`<div id="liff-page"><h1 class="svelte-1hoym3u">\u3088\u3046\u3053\u305DLIFF\u306E\u4E16\u754C\u3078</h1> 
		<p id="liff-message"></p></div>`,De(e,"class","svelte-1hoym3u")},m(r,a){ke(r,e,a)},p:_,i:_,o:_,d(r){r&&ie(e)}}}function or(t,e,r){let{name:a}=e;return window.onload=()=>{const n="1656807318-km8WVpYe",s=document.getElementById("liff-page"),o=window.liff,u=document.getElementById("liff-message");s.appendChild(u),o.isInClient()?(u.innerHTML="\u3053\u308C\u306FLIFF\u753B\u9762\u3067\u3059",o.init({liffId:n}).then(()=>{const f=o.getIDToken();sr.post("/id_api",{token:f}).then(c=>{let i=c.data;alert(i.name)}).catch(c=>alert("\u8A8D\u8A3C\u306B\u5931\u6557\u3057\u307E\u3057\u305F"))})):u.innerHTML="\u3053\u308C\u306FLIFF\u753B\u9762\u3058\u3083\u3042\u308A\u307E\u305B\u3093"},t.$$set=n=>{"name"in n&&r(0,a=n.name)},[a]}class ur extends Ke{constructor(e){super();Xe(this,e,or,ir,Le,{name:0})}}return new ur({target:document.body,props:{name:"world"}})}();
//# sourceMappingURL=bundle.js.map
