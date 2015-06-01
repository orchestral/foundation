(function(){var e,t,n,r;if(n=this,r=n._,r||"undefined"==typeof require||null===require||(r=require("underscore")),!r)throw new Error("underscore.js is missing");
t=function(e){return Array.prototype.slice.call(e)},e=function(){function e(){}return e.prototype.config={},e.prototype.environment="production",e.prototype.instances={},
e.prototype.detectEnvironment=function(e){return r.isFunction(e)===!0&&(e=e.apply(n)),this.environment=e},e.prototype.env=function(){return this.environment;
},e.prototype.get=function(e,t){return"undefined"!=typeof this.config[e]?this.config[e]:null!=t?t:t=null},e.prototype.put=function(e,t){var n;return n=e,
r.isObject(n)||(n={},n[e]=t),this.config=r.defaults(n,this.config),this},e.prototype.on=function(e,t){var n;return n=this.make("event"),n.listen(e,t),this;
},e.prototype.trigger=function(e,t){var n;return n=this.make("event"),n.fire(e,t),this},e.prototype.bind=function(e,t){return this.instances[e]=[t,!1,null],
this},e.prototype.make=function(e){var o,i,u,s;return i=t(arguments),e=i.shift(),o=this.resolve(e),s=o[0],o[1]===!0&&o[2]===!0?s:(u=r.isFunction(s)?s.apply(n,i):s,
o[1]===!0&&(this.instances[e][0]=u,this.instances[e][2]=!0),u)},e.prototype.resolve=function(e){var t;return t=this.instances[e],r.isUndefined(t)?null:t;
},e.prototype.singleton=function(e,t){return this.instances[e]=[t,!0,!1],this},e.prototype.when=function(e,t){var n;return null!=this.ENV&&(n=this.ENV),
null!=this.environment&&(n=this.environment),n===e||"*"===e?this.run(t):void 0},e.prototype.run=function(e){return r.isFunction(e)?e.call(this):void 0},
e}(),n.Javie=new e}).call(this),function(){var e,t,n,r,o,i;if(n=null,r={},o="undefined"!=typeof exports&&null!==exports?exports:this,i=o._,i||"undefined"==typeof require||null===require||(i=require("underscore")),
!i)throw new Error("underscore.js is missing");e=function(){function e(){}return e.prototype.clone=function(e){var t;return t={to:function(t){return r[t]=i.clone(r[e]),
!0}}},e.prototype.listen=function(e,t){var n;if(i.isFunction(t)===!1)throw new Error("Callback is not a function");return n={id:e,callback:t},null==r[e]&&(r[e]=[]),
r[e].push(t),n},e.prototype.listener=function(e,t){return this.listen(e,t)},e.prototype.fire=function(e,t){var n,o,u;if(n=this,o=[],null==e)throw new Error("Event ID ["+e+"] is not available");
return null==r[e]?null:(u=function(e,r){var i;return i=e.apply(n,t||[]),o.push(i)},i.each(r[e],u),o)},e.prototype.first=function(e,t){var n,o,u,s;if(o=this,
u=[],null==e)throw new Error("Event ID ["+e+"] is not available");return null==r[e]?null:(n=r[e].slice(0,1),s=function(e,n){var r;return r=e.apply(o,t||[]),
u.push(r)},i.each(n,s),u[0])},e.prototype.until=function(e,t){var n,o,u;if(n=this,o=null,null==e)throw new Error("Event ID ["+e+"] is not available");return null==r[e]?null:(u=function(e,r){
var i;return i=e.apply(n,t||[]),null==o?o.push(i):void 0},i.each(r[e],u),o)},e.prototype.flush=function(e){return i.isUndefined(r[e])||(r[e]=null),!0},
e.prototype.forget=function(e){var t,n,o,u;if(o=this,t=e.id,u=e.callback,!i.isString(t))throw new Error("Event ID ["+t+"] is not provided");if(!i.isFunction(callback))throw new Error("Callback is not a function");
if(null==r[t])throw new Error("Event ID ["+t+"] is not available");return n=function(e,n){return u===e?r[t].splice(n,1):void 0},i.each(r[t],n),!0},e}(),
t=function(){function t(){return t.make()}return t.make=function(){return null!=n?n:n=new e},t}(),"undefined"!=typeof exports&&null!==exports?("undefined"!=typeof module&&null!==module&&module.exports&&(module.exports=t),
exports.EventDispatcher=t):(null==o.Javie&&(o.Javie={}),o.Javie.Events=t,o.Javie.EventDispatcher=t)}.call(this),function(){var e,t,n,r,o,i,u,s,l;l="undefined"!=typeof exports&&null!==exports?exports:this,
u=null,o=!1,i={ERROR:"error",WARNING:"warning",INFO:"info",DEBUG:"debug",LOG:"log"},n=function(e){return Array.prototype.slice.call(e)},r=function(e,t){
return o?s(e,t):!1},s=function(e,t){var n;switch(n=console,e){case"info":return n.info(t),!0;case"debug"&&null!=n.debug:return n.debug(t),!0;case"warning":
return n.warn(t),!0;case"error"&&null!=n.error:return n.error(t),!0;case"log":return n.log(t),!0;default:return n.log("["+e.toUpperCase()+"]",t),!0}},e=function(){
function e(){}return e.prototype.logs=[],e.prototype.dispatch=function(e,t){var n;return n=r(e,t),t.unshift(e),this.logs.push(t),n},e.prototype.info=function(){
return this.dispatch(i.INFO,n(arguments))},e.prototype.debug=function(){return this.dispatch(i.DEBUG,n(arguments))},e.prototype.warning=function(){return this.dispatch(i.WARNING,n(arguments));
},e.prototype.log=function(){return this.dispatch(i.LOG,n(arguments))},e.prototype.post=function(e,t){return this.dispatch(e,[t])},e}(),t=function(){function t(){
return t.make()}return t.make=function(){return null!=u?u:u=new e},t.enable=function(){return o=!0},t.disable=function(){return o=!1},t.status=function(){
return o},t}(),"undefined"!=typeof exports&&null!==exports?("undefined"!=typeof module&&null!==module&&module.exports&&(module.exports=t),l.Logger=t):(null==l.Javie&&(l.Javie={}),
l.Javie.Logger=t)}.call(this),function(){var e,t,n,r,o,i,u;o={},n=!1,i="undefined"!=typeof exports&&null!==exports?exports:this,u=function(e,t,n){return null==e&&(e=""),
null==t&&(t=""),null==n&&(n=r(!0)),{id:e,type:t,start:n,end:null,total:null,message:""}},r=function(e){var t,n,r;return r=(new Date).getTime(),t=parseInt(r/1e3,10),
n=""+(r-1e3*t)/1e3+" sec",e===!0?t:n},e=function(){function e(){this.logs=[],this.pair={},this.started=r(!0)}return e.prototype.logs=null,e.prototype.pair=null,
e.prototype.started=null,e.prototype.time=function(e,t){var r,o;return null==e&&(e=this.logs.length),n===!1?null:(o=u("time",e),o.message=t.toString(),
r=this.pair["time"+e],"undefined"!=typeof r?this.logs[r]=o:(this.logs.push(o),this.pair["time"+e]=this.logs.length-1),console.time(e),e)},e.prototype.timeEnd=function(e,t){
var o,i,s,l,p;return null==e&&(e=this.logs.length),n===!1?null:(i=this.pair["time"+e],"undefined"!=typeof i?(console.timeEnd(e),s=this.logs[i]):(s=u("time",e,this.started),
"undefined"!=typeof t&&(s.message=t),this.logs.push(s),i=this.logs.length-1),o=s.end=r(!0),l=s.start,p=o-l,s.total=p,this.logs[i]=s,p)},e.prototype.trace=function(){
return n&&console.trace(),!0},e.prototype.output=function(e){var t,r,o,i,u;if(e===!0&&(n=!0),n===!1)return!1;for(u=this.logs,o=0,i=u.length;i>o;o++)t=u[o],
"time"===t.type?(r=Math.floor(1e3*t.total),console.log("%s: %s - %dms",t.id,t.message,r)):console.log(t.id,t.message);return!0},e}(),t=function(){function t(e){
return t.make(e)}return t.make=function(t){return null==t&&""===t&&(t="default"),null!=o[t]?o[t]:o[t]=new e},t.enable=function(){return n=!0},t.disable=function(){
return n=!1},t.status=function(){return n},t}(),"undefined"!=typeof exports&&null!==exports?("undefined"!=typeof module&&null!==module&&module.exports&&(module.exports=t),
exports.Profiler=t):(null==i.Javie&&(i.Javie={}),i.Javie.Profiler=t)}.call(this),function(){var e,t,n,r,o,i,u,s,l;if(s="undefined"!=typeof exports&&null!==exports?exports:this,
u={},r=null,"undefined"==typeof s.Javie)throw new Error("Javie is missing");if("undefined"==typeof s.Javie.EventDispatcher)throw new Error("Javie.EventDispatcher is missing");
if(r=s.Javie.EventDispatcher.make(),l=s._,l||"undefined"==typeof require||null===require||(l=require("underscore")),!l)throw new Error("underscore.js is missing");
if(n=s.$,"undefined"==typeof n||null===n)throw new Error("Required jQuery or Zepto object is missing");o=function(n){var o,i,s,p;return p=null,l.isUndefined(u[n])?(p=new e,
p.config=l.defaults(p.config,t.config),p.put({name:n}),u[n]=p):(s=u[n],s.executed===!0&&(i=l.uniqueId(""+n+"_"),o=new e,r.clone("Request.onError: "+n).to("Request.onError: "+i),
r.clone("Request.onComplete: "+n).to("Request.onComplete: "+i),r.clone("Request.beforeSend: "+n).to("Request.beforeSend: "+i),o.put(s.config),p=o),p=s),
p},i=function(e){var t;if(l.isString(e)===!0)try{e=n.parseJSON(e)}catch(r){t=r}return e},e=function(){function e(){}return e.prototype.executed=!1,e.prototype.response=null,
e.prototype.config={name:"",type:"GET",uri:"",query:"",data:"",dataType:"json",id:"",object:null},e.prototype.get=function(e,t){return"undefined"!=typeof this.config[e]?this.config[e]:null!=t?t:t=null;
},e.prototype.put=function(e,t){var n;return n=e,l.isObject(e)||(n={},n[e]=t),this.config=l.defaults(n,this.config)},e.prototype.to=function(e,t,r){var o,i,u,p,f,c;
if(this.put({dataType:null!=r?r:r="json"}),u=["POST","GET","PUT","DELETE"],l.isUndefined(e))throw new Error("Missing required url parameter");return null==t&&(t=s.document),
this.put({object:t}),p=e.split(" "),1===p.length?c=p[0]:(-1!==l.indexOf(u,p[0])&&(f=p[0]),c=p[1],"GET"!==f&&(i=c.split("?"),i.length>1&&(e=i[0],this.put({
query:i[1]}))),c=c.replace(":baseUrl",this.get("baseUrl","")),this.put({type:f,uri:c})),o=n(this.get("object")).attr("id"),"undefined"!=typeof o&&this.put({
id:"#"+o}),this},e.prototype.execute=function(e){var t,o,u;return t=this,o=this.get("name"),l.isObject(e)||(e=""+n(this.get("object")).serialize()+"&"+this.get("query"),
"?&"===e&&(e="")),this.executed=!0,r.fire("Request.beforeSend",[this]),r.fire("Request.beforeSend: "+o,[this]),this.config.beforeSend(this),u={type:this.get("type"),
dataType:this.get("dataType"),url:this.get("uri"),data:e,complete:function(n){var u;return e=i(n.responseText),u=n.status,t.response=n,!l.isUndefined(e)&&e.hasOwnProperty("errors")&&(r.fire("Request.onError",[e.errors,u,t]),
r.fire("Request.onError: "+o,[e.errors,u,t]),t.config.onError(e.errors,u,t),e.errors=null),r.fire("Request.onComplete",[e,u,t]),r.fire("Request.onComplete: "+o,[e,u,t]),
t.config.onComplete(e,u,t),!0}},n.ajax(u),this},e}(),t=function(){function e(t){return e.make(t)}return e.make=function(e){return l.isString(e)||(e="default"),
o(e)},e.config={baseUrl:null,onError:function(e,t){},beforeSend:function(e,t){},onComplete:function(e,t){}},e.get=function(e,t){return null==t&&(t=null),
l.isUndefined(this.config[e])?t:this.config[e]},e.put=function(e,t){var n;return n=e,l.isObject(e)||(n={},n[e]=t),this.config=l.defaults(n,this.config);
},e}(),"undefined"!=typeof exports&&null!==exports?("undefined"!=typeof module&&null!==module&&module.exports&&(module.exports=t),s.Request=t):s.Javie.Request=t;
}.call(this),function(){var e,t;t=this,e=t.Javie,e.singleton("underscore",t._),e.singleton("event",function(){return new e.EventDispatcher}),e.bind("profiler",function(t){
return null!=t?new e.Profiler(t):e.Profiler}),e.bind("log",function(){return new e.Logger}),e.bind("request",function(t){return null!=t?new e.Request(t):e.Request;
})}.call(this);