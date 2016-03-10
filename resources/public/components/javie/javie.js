/**
 * ========================================================================
 * Javie
 * ========================================================================
 *
 * @package Javie
 * @require underscore, console, jQuery/Zepto
 * @version 2.1.1
 * @author  Mior Muhammad Zaki <https://github.com/crynobone>
 * @license MIT License
 * ========================================================================
 */

(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _helpers = require(3);

var Util = _interopRequireWildcard(_helpers);

var _Config = require(4);

var _Config2 = _interopRequireDefault(_Config);

var _underscore = require(10);

var _underscore2 = _interopRequireDefault(_underscore);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Container = (function () {
  function Container(name, instance) {
    var shared = arguments.length <= 2 || arguments[2] === undefined ? false : arguments[2];
    var resolved = arguments.length <= 3 || arguments[3] === undefined ? false : arguments[3];

    _classCallCheck(this, Container);

    this.name = name;
    this.instance = instance;
    this.shared = shared;
    this.resolved = resolved;
  }

  _createClass(Container, [{
    key: 'resolving',
    value: function resolving() {
      var options = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];

      if (this.isShared() && this.isResolved()) return this.instance;

      var resolved = this.instance;

      if (_underscore2.default.isFunction(resolved)) resolved = resolved.apply(this, options);

      if (this.isShared()) {
        this.instance = resolved;
        this.resolved = true;
      }

      return resolved;
    }
  }, {
    key: 'isResolved',
    value: function isResolved() {
      return this.resolved;
    }
  }, {
    key: 'isShared',
    value: function isShared() {
      return this.shared;
    }
  }]);

  return Container;
})();

var Application = (function () {
  function Application() {
    var environment = arguments.length <= 0 || arguments[0] === undefined ? 'production' : arguments[0];

    _classCallCheck(this, Application);

    this.config = new _Config2.default();
    this.environment = environment;
    this.instances = {};
  }

  _createClass(Application, [{
    key: 'detectEnvironment',
    value: function detectEnvironment(environment) {
      if (_underscore2.default.isFunction(environment)) environment = environment.apply(this);

      return this.environment = environment;
    }
  }, {
    key: 'env',
    value: function env() {
      return this.environment;
    }
  }, {
    key: 'get',
    value: function get(key) {
      var defaults = arguments.length <= 1 || arguments[1] === undefined ? null : arguments[1];

      return this.config.get(key, defaults);
    }
  }, {
    key: 'put',
    value: function put(key, value) {
      this.config.put(key, value);

      return this;
    }
  }, {
    key: 'on',
    value: function on(name, callback) {
      var events = this.make('event');
      events.listen(name, callback);

      return this;
    }
  }, {
    key: 'emit',
    value: function emit(name) {
      var options = arguments.length <= 1 || arguments[1] === undefined ? [] : arguments[1];

      var events = this.make('event');
      events.fire(name, options);

      return this;
    }
  }, {
    key: 'trigger',
    value: function trigger(name) {
      var options = arguments.length <= 1 || arguments[1] === undefined ? [] : arguments[1];

      return this.emit(name, options);
    }
  }, {
    key: 'bind',
    value: function bind(name, instance) {
      this.instances[name] = new Container(name, instance);

      return this;
    }
  }, {
    key: 'make',
    value: function make(name) {
      var options = Util.array_make(arguments);
      name = options.shift();

      if (this.instances[name] instanceof Container) return this.instances[name].resolving(options);
    }
  }, {
    key: 'singleton',
    value: function singleton(name, instance) {
      this.instances[name] = new Container(name, instance, true);

      return this;
    }
  }, {
    key: 'when',
    value: function when(environment, callback) {
      var env = this.environment;

      if (env === environment || environment == '*') return this.run(callback);
    }
  }, {
    key: 'run',
    value: function run(callback) {
      if (_underscore2.default.isFunction(callback)) return callback.call(this);
    }
  }]);

  return Application;
})();

exports.default = Application;

},{"10":10,"3":3,"4":4}],2:[function(require,module,exports){
'use strict';

var _underscore = require(10);

var _underscore2 = _interopRequireDefault(_underscore);

var _Application = require(1);

var _Application2 = _interopRequireDefault(_Application);

var _Config = require(4);

var _Config2 = _interopRequireDefault(_Config);

var _Events = require(5);

var _Events2 = _interopRequireDefault(_Events);

var _Log = require(6);

var _Log2 = _interopRequireDefault(_Log);

var _Profiler = require(7);

var _Profiler2 = _interopRequireDefault(_Profiler);

var _Request = require(8);

var _Request2 = _interopRequireDefault(_Request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var app = new _Application2.default();

app.singleton('underscore', _underscore2.default);

app.singleton('event', function () {
  return new _Events2.default();
});

app.singleton('log', function () {
  return _Log2.default;
});
app.singleton('log.writer', function () {
  return new _Log2.default();
});

app.bind('config', function () {
  var attributes = arguments.length <= 0 || arguments[0] === undefined ? {} : arguments[0];

  return new _Config2.default(attributes);
});

app.bind('profiler', function () {
  var name = arguments.length <= 0 || arguments[0] === undefined ? null : arguments[0];

  return name != null ? new _Profiler2.default(name) : _Profiler2.default;
});

app.bind('request', function () {
  var name = arguments.length <= 0 || arguments[0] === undefined ? null : arguments[0];

  return name != null ? new _Request2.default(name) : _Request2.default;
});

window.Javie = app;

},{"1":1,"10":10,"4":4,"5":5,"6":6,"7":7,"8":8}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.array_make = array_make;
exports.microtime = microtime;
function array_make(args) {
  return Array.prototype.slice.call(args);
}

function microtime() {
  var seconds = arguments.length <= 0 || arguments[0] === undefined ? true : arguments[0];

  var time = new Date().getTime();
  var ms = parseInt(time / 1000, 10);
  var sec = (time - ms * 1000) / 1000 + " sec";

  return seconds ? ms : sec;
}

},{}],4:[function(require,module,exports){
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _underscore = require(10);

var _underscore2 = _interopRequireDefault(_underscore);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Configuration = (function () {
  function Configuration() {
    var attributes = arguments.length <= 0 || arguments[0] === undefined ? {} : arguments[0];

    _classCallCheck(this, Configuration);

    this.attributes = attributes;
  }

  _createClass(Configuration, [{
    key: 'has',
    value: function has(key) {
      return !_underscore2.default.isUndefined(this.attributes[key]);
    }
  }, {
    key: 'get',
    value: function get(key) {
      var defaults = arguments.length <= 1 || arguments[1] === undefined ? null : arguments[1];

      return this.has(key) ? this.attributes[key] : defaults;
    }
  }, {
    key: 'put',
    value: function put(key, value) {
      var config = key;

      if (!_underscore2.default.isObject(key)) {
        config = {};
        config[key] = value;
      }

      this.attributes = _underscore2.default.defaults(config, this.attributes);
    }
  }, {
    key: 'all',
    value: function all() {
      return this.attributes;
    }
  }]);

  return Configuration;
})();

exports.default = Configuration;

},{"10":10}],5:[function(require,module,exports){
"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _underscore = require(10);

var _underscore2 = _interopRequireDefault(_underscore);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var dispatcher = null;
var events = {};

var Payload = (function () {
  function Payload(id, callback) {
    _classCallCheck(this, Payload);

    this.id = id;
    this.callback = callback;
  }

  _createClass(Payload, [{
    key: "getId",
    value: function getId() {
      return this.id;
    }
  }, {
    key: "getCallback",
    value: function getCallback() {
      return this.callback;
    }
  }]);

  return Payload;
})();

var Dispatcher = (function () {
  function Dispatcher() {
    _classCallCheck(this, Dispatcher);
  }

  _createClass(Dispatcher, [{
    key: "clone",
    value: function clone(id) {
      return {
        to: function to(_to) {
          return events[_to] = _underscore2.default.clone(events[id]);
        }
      };
    }
  }, {
    key: "listen",
    value: function listen(id, callback) {
      if (!_underscore2.default.isFunction(callback)) throw new Error("Callback is not a function.");

      var response = new Payload(id, callback);

      if (!_underscore2.default.isArray(events[id])) events[id] = [];

      events[id].push(callback);

      return response;
    }
  }, {
    key: "fire",
    value: function fire(id) {
      var options = arguments.length <= 1 || arguments[1] === undefined ? [] : arguments[1];

      if (id == null) throw new Error("Event ID [" + id + "] is not available.");

      return this.dispatch(events[id], options);
    }
  }, {
    key: "first",
    value: function first(id, options) {
      if (id == null) throw new Error("Event ID [" + id + "] is not available.");

      var event = events[id].slice(0, 1);
      var responses = this.dispatch(event, options);

      return responses.shift();
    }
  }, {
    key: "until",
    value: function until(id, options) {
      if (id == null) throw new Error("Event ID [" + id + "] is not available.");

      var responses = this.dispatch(events[id], options, true);

      return responses.length < 1 ? null : responses.shift();
    }
  }, {
    key: "flush",
    value: function flush(id) {
      if (!_underscore2.default.isNull(events[id])) events[id] = null;
    }
  }, {
    key: "forget",
    value: function forget(handler) {
      var me = this;

      if (!handler instanceof Payload) throw new Error("Invalid payload for Event ID [" + id + "]");

      var id = handler.getId();
      var ref = handler.getCallback();

      if (!_underscore2.default.isArray(events[id])) throw new Error("Event ID [" + id + "] is not available.");

      _underscore2.default.each(events[id], function (callback, key) {
        if (ref == callback) {
          events[id].splice(key, 1);
        }
      });
    }
  }, {
    key: "dispatch",
    value: function dispatch(queued) {
      var _this = this;

      var options = arguments.length <= 1 || arguments[1] === undefined ? [] : arguments[1];
      var halt = arguments.length <= 2 || arguments[2] === undefined ? false : arguments[2];

      var responses = [];

      if (!_underscore2.default.isArray(queued)) return null;

      _underscore2.default.each(queued, function (callback, key) {
        if (halt == false || responses.length == 0) {
          var applied = callback.apply(_this, options);
          responses.push(applied);
        }
      });

      return responses;
    }
  }]);

  return Dispatcher;
})();

var Events = (function () {
  function Events() {
    _classCallCheck(this, Events);

    return Events.make();
  }

  _createClass(Events, null, [{
    key: "make",
    value: function make() {
      if (dispatcher == null) dispatcher = new Dispatcher();

      return dispatcher;
    }
  }]);

  return Events;
})();

exports.default = Events;

},{"10":10}],6:[function(require,module,exports){
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _helpers = require(3);

var Util = _interopRequireWildcard(_helpers);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var writer = null;
var enabled = false;
var level = {
  ERROR: 'error',
  WARNING: 'warning',
  INFO: 'info',
  DEBUG: 'debug',
  LOG: 'log'
};

function _dispatch(type, message) {
  if (enabled) return post(type, message);
}

function post(type, message) {
  var c = console;

  switch (type) {
    case 'info':
      c.info(message);
      return true;
    case 'debug' && c.debug != null:
      c.debug(message);
      return true;
    case 'warning':
      c.warn(message);
      return true;
    case 'error' && c.error != null:
      c.error(message);
      return true;
    case 'log':
      c.log(message);
      return true;
    default:
      c.log('[' + type.toUpperCase() + ']', message);
      return true;
  }
}

var Writer = (function () {
  function Writer() {
    _classCallCheck(this, Writer);

    this.logs = [];
  }

  _createClass(Writer, [{
    key: 'dispatch',
    value: function dispatch(type, message) {
      var result = _dispatch(type, message);
      message.unshift(type);
      this.logs.push(message);

      return result;
    }
  }, {
    key: 'info',
    value: function info() {
      return this.dispatch(level.INFO, Util.array_make(arguments));
    }
  }, {
    key: 'debug',
    value: function debug() {
      return this.dispatch(level.DEBUG, Util.array_make(arguments));
    }
  }, {
    key: 'warning',
    value: function warning() {
      return this.dispatch(level.WARNING, Util.array_make(arguments));
    }
  }, {
    key: 'log',
    value: function log() {
      return this.dispatch(level.LOG, Util.array_make(arguments));
    }
  }, {
    key: 'post',
    value: function post(type, message) {
      return this.dispatch(type, [message]);
    }
  }]);

  return Writer;
})();

var Log = (function () {
  function Log() {
    _classCallCheck(this, Log);

    return Log.make();
  }

  _createClass(Log, null, [{
    key: 'make',
    value: function make() {
      if (writer == null) writer = new Writer();

      return writer;
    }
  }, {
    key: 'enable',
    value: function enable() {
      enabled = true;
    }
  }, {
    key: 'disable',
    value: function disable() {
      enabled = false;
    }
  }, {
    key: 'status',
    value: function status() {
      return enabled;
    }
  }]);

  return Log;
})();

exports.default = Log;

},{"3":3}],7:[function(require,module,exports){
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _helpers = require(3);

var Util = _interopRequireWildcard(_helpers);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var profilers = {};
var enabled = false;

var Schema = function Schema(id, type) {
  var start = arguments.length <= 2 || arguments[2] === undefined ? null : arguments[2];

  _classCallCheck(this, Schema);

  if (start == null) start = Util.microtime();

  this.id = id;
  this.type = type;
  this.start = start;
  this.end = null;
  this.total = null;
  this.message = '';
};

var Handler = (function () {
  function Handler(name) {
    _classCallCheck(this, Handler);

    this.name = name;
    this.logs = [];
    this.pair = {};
    this.started = Util.microtime();
  }

  _createClass(Handler, [{
    key: 'time',
    value: function time(id, message) {
      if (!enabled) return null;

      if (id == null) id = this.logs.length;

      var log = new Schema(id, 'time');
      log.message = message.toString();

      var key = this.pair['time' + id];

      if (typeof key != 'undefined') {
        this.logs[key] = log;
      } else {
        this.logs.push(log);
        this.pair['time' + id] = this.logs.length - 1;
      }

      console.time(id);

      return id;
    }
  }, {
    key: 'timeEnd',
    value: function timeEnd(id, message) {
      if (!enabled) return null;

      if (id == null) id = this.logs.length;

      var key = this.pair['time' + id];
      var log = null;

      if (typeof key != 'undefined') {
        console.timeEnd(id);
        log = this.logs[key];
      } else {
        log = new Schema(id, 'time', this.started);
        if (typeof message != 'undefined') log.message = message;

        this.logs.push(log);
        key = this.logs.length - 1;
      }

      var end = log.end = Util.microtime();
      var start = log.start;
      var total = end - start;
      log.total = total;
      this.logs[key] = log;

      return total;
    }
  }, {
    key: 'trace',
    value: function trace() {
      if (enable) console.trace();
    }
  }, {
    key: 'output',
    value: function output() {
      var auto = arguments.length <= 0 || arguments[0] === undefined ? false : arguments[0];

      if (auto) enabled = true;

      if (!enabled) return null;

      this.logs.forEach(function (log) {
        if (log.type == 'time') {
          var sec = Math.floor(log.total * 1000);
          console.log('%s: %s - %dms', log.id, log.message, sec);
        } else {
          console.log(log.id, log.message);
        }
      });
    }
  }]);

  return Handler;
})();

var Profiler = (function () {
  function Profiler(name) {
    _classCallCheck(this, Profiler);

    return Profiler.make(name);
  }

  _createClass(Profiler, null, [{
    key: 'make',
    value: function make() {
      var name = arguments.length <= 0 || arguments[0] === undefined ? 'default' : arguments[0];

      if (profilers[name] == null) profilers[name] = new Handler(name);

      return profilers[name];
    }
  }, {
    key: 'enable',
    value: function enable() {
      enabled = true;
    }
  }, {
    key: 'disable',
    value: function disable() {
      enabled = false;
    }
  }, {
    key: 'status',
    value: function status() {
      return enabled;
    }
  }]);

  return Profiler;
})();

exports.default = Profiler;

},{"3":3}],8:[function(require,module,exports){
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Events = require(5);

var _Events2 = _interopRequireDefault(_Events);

var _Config = require(4);

var _Config2 = _interopRequireDefault(_Config);

var _helpers = require(3);

var Util = _interopRequireWildcard(_helpers);

var _underscore = require(10);

var _underscore2 = _interopRequireDefault(_underscore);

var _jquery = require(9);

var _jquery2 = _interopRequireDefault(_jquery);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var dispatcher = _Events2.default.make();
var requests = {};

function json_parse(data) {
  if (_underscore2.default.isString(data)) {
    try {
      data = _jquery2.default.parseJSON(data);
    } catch (e) {
      data = null;
    }
  }

  return data;
}

var Handler = (function () {
  function Handler() {
    _classCallCheck(this, Handler);

    this.executed = false;
    this.response = null;
    this.config = new _Config2.default({
      name: '',
      type: 'GET',
      uri: '',
      query: '',
      data: '',
      dataType: 'json',
      id: '',
      object: null,
      headers: {},
      beforeSend: function beforeSend() {},
      onComplete: function onComplete() {},
      onError: function onError() {}
    });
  }

  _createClass(Handler, [{
    key: 'get',
    value: function get(key) {
      var defaults = arguments.length <= 1 || arguments[1] === undefined ? null : arguments[1];

      return this.config.get(key, defaults);
    }
  }, {
    key: 'put',
    value: function put(key, value) {
      this.config.put(key, value);

      return this;
    }
  }, {
    key: 'addHeader',
    value: function addHeader(key, value) {
      return this.header(key, value);
    }
  }, {
    key: 'header',
    value: function header(key, value) {
      var headers = this.config.get('headers', {});
      headers[key] = value;
      this.config.put({ headers: headers });

      return this;
    }
  }, {
    key: 'to',
    value: function to(url, object) {
      var dataType = arguments.length <= 2 || arguments[2] === undefined ? 'json' : arguments[2];
      var headers = arguments.length <= 3 || arguments[3] === undefined ? {} : arguments[3];

      var supported = ['POST', 'GET', 'PUT', 'DELETED'];

      if (_underscore2.default.isUndefined(url)) throw new Error("Missing required URL parameter.");

      if (object == null) object = window.document;

      var segment = url.split(' ');
      var uri = url;
      var type = this.config.get('type', 'POST');
      var query = this.config.get('query', '');

      if (segment.length == 1) {
        uri = segment[0];
      } else {
        if (_underscore2.default.indexOf(supported, segment[0]) > -1) type = segment[0];

        uri = segment[1];
      }

      if (type != 'GET') {
        var queries = uri.split('?');

        if (queries.length > 1) {
          uri = queries[0];
          query = queries[1];
        }
      }

      uri = uri.replace(':baseUrl', this.config.get('baseUrl', ''));

      this.config.put({
        dataType: dataType,
        object: object,
        query: query,
        type: type,
        uri: uri,
        headers: headers
      });

      var id = (0, _jquery2.default)(object).attr('id');

      if (typeof id != 'undefined') this.config.put({ id: '#' + id });

      return this;
    }
  }, {
    key: 'execute',
    value: function execute(data) {
      var me = this;
      var name = this.config.get('name');
      var object = this.config.get('object');
      var query = this.config.get('query');

      if (!_underscore2.default.isObject(data)) {
        data = (0, _jquery2.default)(object).serialize() + '&' + query;
        if (data == '?&') data = '';
      }

      this.executed = true;

      var payload = {
        type: this.config.get('type'),
        dataType: this.config.get('dataType'),
        url: this.config.get('uri'),
        data: data,
        headers: this.config.get('headers', {}),
        beforeSend: function beforeSend(xhr) {
          me.fireEvent('beforeSend', name, [me, xhr]);
        },
        complete: function complete(xhr) {
          data = json_parse(xhr.responseText);
          status = xhr.status;
          me.response = xhr;

          if (_underscore2.default.has(data, 'errors')) {
            me.fireEvent('onError', name, [data.errors, status, me, xhr]);
            data.errors = null;
          }

          me.fireEvent('onComplete', name, [data, status, me, xhr]);
        }
      };

      _jquery2.default.ajax(payload);

      return this;
    }
  }, {
    key: 'fireEvent',
    value: function fireEvent(type, name, args) {
      dispatcher.fire('Request.' + type, args);
      dispatcher.fire('Request.' + type + ': ' + name, args);

      var callback = this.config.get(type);

      if (_underscore2.default.isFunction(callback)) callback.apply(this, args);
    }
  }]);

  return Handler;
})();

var RequestAttributes = {
  baseUrl: null,
  onError: function onError(data, status) {},
  beforeSend: function beforeSend(data, status) {},
  onComplete: function onComplete(data, status) {}
};

var Request = (function () {
  function Request(name) {
    _classCallCheck(this, Request);

    return Request.make(name);
  }

  _createClass(Request, null, [{
    key: 'make',
    value: function make() {
      var name = arguments.length <= 0 || arguments[0] === undefined ? 'default' : arguments[0];

      return Request.find(name);
    }
  }, {
    key: 'get',
    value: function get(key) {
      var defaults = arguments.length <= 1 || arguments[1] === undefined ? null : arguments[1];

      if (!_underscore2.default.isUndefined(RequestAttributes[key])) return RequestAttributes[key];

      return defaults;
    }
  }, {
    key: 'put',
    value: function put(key, value) {
      var config = key;

      if (!_underscore2.default.isObject(key)) {
        config = {};
        config[key] = value;
      }

      RequestAttributes = _underscore2.default.defaults(config, RequestAttributes);
    }
  }, {
    key: 'find',
    value: function find(name) {
      var request = null;

      if (_underscore2.default.isUndefined(requests[name])) {
        request = new Handler();
        request.put(_underscore2.default.defaults(request.config.all(), RequestAttributes));
        request.put({ name: name });

        return requests[name] = request;
      }

      request = requests[name];

      if (!request.executed) return request;

      var key = _underscore2.default.uniqueId(name + '_');
      var child = new Handler();

      dispatcher.clone('Request.onError: ' + name).to('Request.onError: ' + key);
      dispatcher.clone('Request.onComplete: ' + name).to('Request.onComplete: ' + key);
      dispatcher.clone('Request.beforeSend: ' + name).to('Request.beforeSend: ' + key);

      child.put(request.config);

      return child;
    }
  }]);

  return Request;
})();

exports.default = Request;

},{"10":10,"3":3,"4":4,"5":5,"9":9}],9:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = jQuery;

},{}],10:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = _;

},{}]},{},[2]);

//# sourceMappingURL=bundle.js.map

//# sourceMappingURL=javie.js.map
