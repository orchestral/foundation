!function e(n,t,r){function u(a,o){if(!t[a]){if(!n[a]){var s="function"==typeof require&&require;if(!o&&s)return s(a,!0);if(i)return i(a,!0);var l=new Error("Cannot find module '"+a+"'");
throw l.code="MODULE_NOT_FOUND",l}var f=t[a]={exports:{}};n[a][0].call(f.exports,function(e){var t=n[a][1][e];return u(t?t:e)},f,f.exports,e,n,t,r)}return t[a].exports;
}for(var i="function"==typeof require&&require,a=0;a<r.length;a++)u(r[a]);return u}({1:[function(e,n,t){"use strict";function r(e){return e&&e.__esModule?e:{
"default":e}}function u(e){if(e&&e.__esModule)return e;var n={};if(null!=e)for(var t in e)Object.prototype.hasOwnProperty.call(e,t)&&(n[t]=e[t]);return n["default"]=e,
n}function i(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}var a=function(){function e(e,n){for(var t=0;t<n.length;t++){
var r=n[t];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(n,t,r){return t&&e(n.prototype,t),
r&&e(n,r),n}}();Object.defineProperty(t,"__esModule",{value:!0});var o=e("./helpers"),s=u(o),l=e("./modules/config/Config.es6"),f=r(l),c=e("./vendor/underscore"),d=r(c),v=function(){
function e(n,t){var r=arguments.length<=2||void 0===arguments[2]?!1:arguments[2],u=arguments.length<=3||void 0===arguments[3]?!1:arguments[3];i(this,e),
this.name=n,this.instance=t,this.shared=r,this.resolved=u}return a(e,[{key:"resolving",value:function(){var e=arguments.length<=0||void 0===arguments[0]?[]:arguments[0];
if(this.isShared()&&this.isResolved())return this.instance;var n=this.instance;return d["default"].isFunction(n)&&(n=n.apply(this,e)),this.isShared()&&(this.instance=n,
this.resolved=!0),n}},{key:"isResolved",value:function(){return this.resolved}},{key:"isShared",value:function(){return this.shared}}]),e}(),g=function(){
function e(){var n=arguments.length<=0||void 0===arguments[0]?"production":arguments[0];i(this,e),this.config=new f["default"],this.environment=n,this.instances={};
}return a(e,[{key:"detectEnvironment",value:function(e){return d["default"].isFunction(e)&&(e=e.apply(this)),this.environment=e}},{key:"env",value:function(){
return this.environment}},{key:"get",value:function(e){var n=arguments.length<=1||void 0===arguments[1]?null:arguments[1];return this.config.get(e,n)}},{
key:"put",value:function(e,n){return this.config.put(e,n),this}},{key:"on",value:function(e,n){var t=this.make("event");return t.listen(e,n),this}},{key:"emit",
value:function(e){var n=arguments.length<=1||void 0===arguments[1]?[]:arguments[1],t=this.make("event");return t.fire(e,n),this}},{key:"trigger",value:function(e){
var n=arguments.length<=1||void 0===arguments[1]?[]:arguments[1];return this.emit(e,n)}},{key:"bind",value:function(e,n){return this.instances[e]=new v(e,n),
this}},{key:"make",value:function(e){var n=s.array_make(arguments);return e=n.shift(),this.instances[e]instanceof v?this.instances[e].resolving(n):void 0;
}},{key:"singleton",value:function(e,n){return this.instances[e]=new v(e,n,!0),this}},{key:"when",value:function(e,n){var t=this.environment;return t===e||"*"==e?this.run(n):void 0;
}},{key:"run",value:function(e){return d["default"].isFunction(e)?e.call(this):void 0}}]),e}();t["default"]=g},{"./helpers":3,"./modules/config/Config.es6":4,
"./vendor/underscore":10}],2:[function(e,n,t){"use strict";function r(e){return e&&e.__esModule?e:{"default":e}}var u=e("./vendor/underscore"),i=r(u),a=e("./Application.es6"),o=r(a),s=e("./modules/config/Config.es6"),l=r(s),f=e("./modules/events/Events.es6"),c=r(f),d=e("./modules/log/Log.es6"),v=r(d),g=e("./modules/profiler/Profiler.es6"),h=r(g),m=e("./modules/request/Request.es6"),p=r(m),y=new o["default"];
y.singleton("underscore",i["default"]),y.singleton("event",function(){return new c["default"]}),y.singleton("log",function(){return v["default"]}),y.singleton("log.writer",function(){
return new v["default"]}),y.bind("config",function(){var e=arguments.length<=0||void 0===arguments[0]?{}:arguments[0];return new l["default"](e)}),y.bind("profiler",function(){
var e=arguments.length<=0||void 0===arguments[0]?null:arguments[0];return null!=e?new h["default"](e):h["default"]}),y.bind("request",function(){var e=arguments.length<=0||void 0===arguments[0]?null:arguments[0];
return null!=e?new p["default"](e):p["default"]}),window.Javie=y},{"./Application.es6":1,"./modules/config/Config.es6":4,"./modules/events/Events.es6":5,
"./modules/log/Log.es6":6,"./modules/profiler/Profiler.es6":7,"./modules/request/Request.es6":8,"./vendor/underscore":10}],3:[function(e,n,t){"use strict";
function r(e){return Array.prototype.slice.call(e)}function u(){var e=arguments.length<=0||void 0===arguments[0]?!0:arguments[0],n=(new Date).getTime(),t=parseInt(n/1e3,10),r=(n-1e3*t)/1e3+" sec";
return e?t:r}Object.defineProperty(t,"__esModule",{value:!0}),t.array_make=r,t.microtime=u},{}],4:[function(e,n,t){"use strict";function r(e){return e&&e.__esModule?e:{
"default":e}}function u(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}var i=function(){function e(e,n){for(var t=0;t<n.length;t++){
var r=n[t];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(n,t,r){return t&&e(n.prototype,t),
r&&e(n,r),n}}();Object.defineProperty(t,"__esModule",{value:!0});var a=e("../../vendor/underscore"),o=r(a),s=function(){function e(){var n=arguments.length<=0||void 0===arguments[0]?{}:arguments[0];
u(this,e),this.attributes=n}return i(e,[{key:"has",value:function(e){return!o["default"].isUndefined(this.attributes[e])}},{key:"get",value:function(e){
var n=arguments.length<=1||void 0===arguments[1]?null:arguments[1];return this.has(e)?this.attributes[e]:n}},{key:"put",value:function(e,n){var t=e;o["default"].isObject(e)||(t={},
t[e]=n),this.attributes=o["default"].defaults(t,this.attributes)}},{key:"all",value:function(){return this.attributes}}]),e}();t["default"]=s},{"../../vendor/underscore":10
}],5:[function(e,n,t){"use strict";function r(e){return e&&e.__esModule?e:{"default":e}}function u(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function");
}var i=function(){function e(e,n){for(var t=0;t<n.length;t++){var r=n[t];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r);
}}return function(n,t,r){return t&&e(n.prototype,t),r&&e(n,r),n}}();Object.defineProperty(t,"__esModule",{value:!0});var a=e("../../vendor/underscore"),o=r(a),s=null,l={},f=function(){
function e(n,t){u(this,e),this.id=n,this.callback=t}return i(e,[{key:"getId",value:function(){return this.id}},{key:"getCallback",value:function(){return this.callback;
}}]),e}(),c=function(){function e(){u(this,e)}return i(e,[{key:"clone",value:function(e){return{to:function(n){return l[n]=o["default"].clone(l[e])}}}},{
key:"listen",value:function(e,n){if(!o["default"].isFunction(n))throw new Error("Callback is not a function.");var t=new f(e,n);return o["default"].isArray(l[e])||(l[e]=[]),
l[e].push(n),t}},{key:"fire",value:function(e){var n=arguments.length<=1||void 0===arguments[1]?[]:arguments[1];if(null==e)throw new Error("Event ID ["+e+"] is not available.");
return this.dispatch(l[e],n)}},{key:"first",value:function(e,n){if(null==e)throw new Error("Event ID ["+e+"] is not available.");var t=l[e].slice(0,1),r=this.dispatch(t,n);
return r.shift()}},{key:"until",value:function(e,n){if(null==e)throw new Error("Event ID ["+e+"] is not available.");var t=this.dispatch(l[e],n,!0);return t.length<1?null:t.shift();
}},{key:"flush",value:function(e){o["default"].isNull(l[e])||(l[e]=null)}},{key:"forget",value:function(e){if(!e instanceof f)throw new Error("Invalid payload for Event ID ["+n+"]");
var n=e.getId(),t=e.getCallback();if(!o["default"].isArray(l[n]))throw new Error("Event ID ["+n+"] is not available.");o["default"].each(l[n],function(e,r){
t==e&&l[n].splice(r,1)})}},{key:"dispatch",value:function(e){var n=this,t=arguments.length<=1||void 0===arguments[1]?[]:arguments[1],r=arguments.length<=2||void 0===arguments[2]?!1:arguments[2],u=[];
return o["default"].isArray(e)?(o["default"].each(e,function(e,i){if(0==r||0==u.length){var a=e.apply(n,t);u.push(a)}}),u):null}}]),e}(),d=function(){function e(){
return u(this,e),e.make()}return i(e,null,[{key:"make",value:function(){return null==s&&(s=new c),s}}]),e}();t["default"]=d},{"../../vendor/underscore":10
}],6:[function(e,n,t){"use strict";function r(e){if(e&&e.__esModule)return e;var n={};if(null!=e)for(var t in e)Object.prototype.hasOwnProperty.call(e,t)&&(n[t]=e[t]);
return n["default"]=e,n}function u(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}function i(e,n){return c?a(e,n):void 0;
}function a(e,n){var t=console;switch(e){case"info":return t.info(n),!0;case null!=t.debug:return t.debug(n),!0;case"warning":return t.warn(n),!0;case null!=t.error:
return t.error(n),!0;case"log":return t.log(n),!0;default:return t.log("["+e.toUpperCase()+"]",n),!0}}var o=function(){function e(e,n){for(var t=0;t<n.length;t++){
var r=n[t];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(n,t,r){return t&&e(n.prototype,t),
r&&e(n,r),n}}();Object.defineProperty(t,"__esModule",{value:!0});var s=e("../../helpers"),l=r(s),f=null,c=!1,d={ERROR:"error",WARNING:"warning",INFO:"info",
DEBUG:"debug",LOG:"log"},v=function(){function e(){u(this,e),this.logs=[]}return o(e,[{key:"dispatch",value:function(e,n){var t=i(e,n);return n.unshift(e),
this.logs.push(n),t}},{key:"info",value:function(){return this.dispatch(d.INFO,l.array_make(arguments))}},{key:"debug",value:function(){return this.dispatch(d.DEBUG,l.array_make(arguments));
}},{key:"warning",value:function(){return this.dispatch(d.WARNING,l.array_make(arguments))}},{key:"log",value:function(){return this.dispatch(d.LOG,l.array_make(arguments));
}},{key:"post",value:function(e,n){return this.dispatch(e,[n])}}]),e}(),g=function(){function e(){return u(this,e),e.make()}return o(e,null,[{key:"make",
value:function(){return null==f&&(f=new v),f}},{key:"enable",value:function(){c=!0}},{key:"disable",value:function(){c=!1}},{key:"status",value:function(){
return c}}]),e}();t["default"]=g},{"../../helpers":3}],7:[function(e,n,t){"use strict";function r(e){if(e&&e.__esModule)return e;var n={};if(null!=e)for(var t in e)Object.prototype.hasOwnProperty.call(e,t)&&(n[t]=e[t]);
return n["default"]=e,n}function u(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}var i=function(){function e(e,n){
for(var t=0;t<n.length;t++){var r=n[t];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(n,t,r){
return t&&e(n.prototype,t),r&&e(n,r),n}}();Object.defineProperty(t,"__esModule",{value:!0});var a=e("../../helpers"),o=r(a),s={},l=!1,f=function v(e,n){
var t=arguments.length<=2||void 0===arguments[2]?null:arguments[2];u(this,v),null==t&&(t=o.microtime()),this.id=e,this.type=n,this.start=t,this.end=null,
this.total=null,this.message=""},c=function(){function e(n){u(this,e),this.name=n,this.logs=[],this.pair={},this.started=o.microtime()}return i(e,[{key:"time",
value:function(e,n){if(!l)return null;null==e&&(e=this.logs.length);var t=new f(e,"time");t.message=n.toString();var r=this.pair["time"+e];return"undefined"!=typeof r?this.logs[r]=t:(this.logs.push(t),
this.pair["time"+e]=this.logs.length-1),console.time(e),e}},{key:"timeEnd",value:function(e,n){if(!l)return null;null==e&&(e=this.logs.length);var t=this.pair["time"+e],r=null;
"undefined"!=typeof t?(console.timeEnd(e),r=this.logs[t]):(r=new f(e,"time",this.started),"undefined"!=typeof n&&(r.message=n),this.logs.push(r),t=this.logs.length-1);
var u=r.end=o.microtime(),i=r.start,a=u-i;return r.total=a,this.logs[t]=r,a}},{key:"trace",value:function(){enable&&console.trace()}},{key:"output",value:function(){
var e=arguments.length<=0||void 0===arguments[0]?!1:arguments[0];return e&&(l=!0),l?void this.logs.forEach(function(e){if("time"==e.type){var n=Math.floor(1e3*e.total);
console.log("%s: %s - %dms",e.id,e.message,n)}else console.log(e.id,e.message)}):null}}]),e}(),d=function(){function e(n){return u(this,e),e.make(n)}return i(e,null,[{
key:"make",value:function(){var e=arguments.length<=0||void 0===arguments[0]?"default":arguments[0];return null==s[e]&&(s[e]=new c(e)),s[e]}},{key:"enable",
value:function(){l=!0}},{key:"disable",value:function(){l=!1}},{key:"status",value:function(){return l}}]),e}();t["default"]=d},{"../../helpers":3}],8:[function(e,n,t){
"use strict";function r(e){if(e&&e.__esModule)return e;var n={};if(null!=e)for(var t in e)Object.prototype.hasOwnProperty.call(e,t)&&(n[t]=e[t]);return n["default"]=e,
n}function u(e){return e&&e.__esModule?e:{"default":e}}function i(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}function a(e){
if(g["default"].isString(e))try{e=m["default"].parseJSON(e)}catch(n){e=null}return e}var o=function(){function e(e,n){for(var t=0;t<n.length;t++){var r=n[t];
r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(n,t,r){return t&&e(n.prototype,t),
r&&e(n,r),n}}();Object.defineProperty(t,"__esModule",{value:!0});var s=e("../events/Events.es6"),l=u(s),f=e("../config/Config.es6"),c=u(f),d=e("../../helpers"),v=(r(d),
e("../../vendor/underscore")),g=u(v),h=e("../../vendor/jquery"),m=u(h),p=l["default"].make(),y={},k=function(){function e(){i(this,e),this.executed=!1,
this.response=null,this.config=new c["default"]({name:"",type:"GET",uri:"",query:"",data:"",dataType:"json",id:"",object:null,headers:{},beforeSend:function(){},
onComplete:function(){},onError:function(){}})}return o(e,[{key:"get",value:function(e){var n=arguments.length<=1||void 0===arguments[1]?null:arguments[1];
return this.config.get(e,n)}},{key:"put",value:function(e,n){return this.config.put(e,n)}},{key:"addHeader",value:function(e,n){var t=this.config.get("headers",{});
return t[e]=n,this.config.put({headers:t}),this}},{key:"to",value:function(e,n){var t=arguments.length<=2||void 0===arguments[2]?"json":arguments[2],r=arguments.length<=3||void 0===arguments[3]?{}:arguments[3],u=["POST","GET","PUT","DELETED"];
if(g["default"].isUndefined(e))throw new Error("Missing required URL parameter.");null==n&&(n=window.document);var i=e.split(" "),a=e,o=this.config.get("type","POST"),s=this.config.get("query","");
if(1==i.length?a=i[0]:(g["default"].indexOf(u,i[0])>-1&&(o=i[0]),a=i[1]),"GET"!=o){var l=a.split("?");l.length>1&&(a=l[0],s=l[1])}a=a.replace(":baseUrl",this.config.get("baseUrl","")),
this.config.put({dataType:t,object:n,query:s,type:o,uri:a,headers:r});var f=m["default"](n).attr("id");return"undefined"!=typeof f&&this.config.put({id:"#"+f
}),this}},{key:"execute",value:function(e){var n=this,t=this.config.get("name"),r=this.config.get("object"),u=this.config.get("query");g["default"].isObject(e)||(e=m["default"](r).serialize()+"&"+u,
"?&"==e&&(e="")),this.executed=!0;var i={type:this.config.get("type"),dataType:this.config.get("dataType"),url:this.config.get("uri"),data:e,headers:this.config.get("headers",{}),
beforeSend:function(e){n.fireEvent("beforeSend",t,[n,e])},complete:function(r){e=a(r.responseText),status=r.status,n.response=r,!g["default"].isUndefined(e)&&e.hasOwnProperty("error")&&(n.fireEvent("onError",t,[e.errors,status,n,r]),
e.errors=null),n.fireEvent("onComplete",t,[e,status,n,r])}};return m["default"].ajax(i),this}},{key:"fireEvent",value:function(e,n,t){p.fire("Request."+e,t),
p.fire("Request."+e+": "+n,t);var r=this.config[e];g["default"].isFunction(r)&&r.apply(this,t)}}]),e}(),b={baseUrl:null,onError:function(e,n){},beforeSend:function(e,n){},
onComplete:function(e,n){}},w=function(){function e(n){return i(this,e),e.make(n)}return o(e,null,[{key:"make",value:function(){var n=arguments.length<=0||void 0===arguments[0]?"default":arguments[0];
return e.find(n)}},{key:"get",value:function(e){var n=arguments.length<=1||void 0===arguments[1]?null:arguments[1];return g["default"].isUndefined(b[e])?n:b[e];
}},{key:"put",value:function(e,n){var t=e;g["default"].isObject(e)||(t={},t[e]=n),b=g["default"].defaults(t,b)}},{key:"find",value:function(e){var n=null;
if(g["default"].isUndefined(y[e]))return n=new k,n.put(g["default"].defaults(n.config.all(),b)),n.put({name:e}),y[e]=n;if(n=y[e],!n.executed)return n;var t=(g["default"].uniqueId(e+"_"),
new k);return p.clone("Request.onError: "+e).to("Request.onError: "+e),p.clone("Request.onComplete: "+e).to("Request.onComplete: "+e),p.clone("Request.beforeSend: "+e).to("Request.beforeSend: "+e),
t.put(n.config),t}}]),e}();t["default"]=w},{"../../helpers":3,"../../vendor/jquery":9,"../../vendor/underscore":10,"../config/Config.es6":4,"../events/Events.es6":5
}],9:[function(e,n,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t["default"]=jQuery},{}],10:[function(e,n,t){"use strict";Object.defineProperty(t,"__esModule",{
value:!0}),t["default"]=_},{}]},{},[2]);