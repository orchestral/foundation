/**
 * ========================================================================
 * Javie
 * ========================================================================
 *
 * @package Javie
 * @require underscore, console, jQuery/Zepto
 * @version 2.0.2
 * @author  Mior Muhammad Zaki <https://github.com/crynobone>
 * @license MIT License
 * ========================================================================
 */

(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _vendorUnderscore = require('./vendor/underscore');

var _vendorUnderscore2 = _interopRequireDefault(_vendorUnderscore);

var _ApplicationEs6 = require('./Application.es6');

var _ApplicationEs62 = _interopRequireDefault(_ApplicationEs6);

var _modulesEventsEventsEs6 = require('./modules/events/Events.es6');

var _modulesEventsEventsEs62 = _interopRequireDefault(_modulesEventsEventsEs6);

var _modulesLogLogEs6 = require('./modules/log/Log.es6');

var _modulesLogLogEs62 = _interopRequireDefault(_modulesLogLogEs6);

var _modulesProfilerProfilerEs6 = require('./modules/profiler/Profiler.es6');

var _modulesProfilerProfilerEs62 = _interopRequireDefault(_modulesProfilerProfilerEs6);

var _modulesRequestRequestEs6 = require('./modules/request/Request.es6');

var _modulesRequestRequestEs62 = _interopRequireDefault(_modulesRequestRequestEs6);

var app = new _ApplicationEs62['default']();

app.singleton('underscore', _vendorUnderscore2['default']);

app.singleton('event', function () {
  return new _modulesEventsEventsEs62['default']();
});

app.singleton('log', function () {
  return _modulesLogLogEs62['default'];
});
app.singleton('log.writer', function () {
  return new _modulesLogLogEs62['default']();
});

app.bind('profiler', function () {
  var name = arguments[0] === undefined ? null : arguments[0];

  return name != null ? new _modulesProfilerProfilerEs62['default'](name) : _modulesProfilerProfilerEs62['default'];
});

app.bind('request', function () {
  var name = arguments[0] === undefined ? null : arguments[0];

  return name != null ? new _modulesRequestRequestEs62['default'](name) : _modulesRequestRequestEs62['default'];
});

window.Javie = app;

},{"./Application.es6":2,"./modules/events/Events.es6":4,"./modules/log/Log.es6":5,"./modules/profiler/Profiler.es6":6,"./modules/request/Request.es6":7,"./vendor/underscore":9}],2:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var _helpers = require('./helpers');

var Util = _interopRequireWildcard(_helpers);

var _ = require('./vendor/underscore');

var Container = (function () {
  function Container(name, instance) {
    var shared = arguments[2] === undefined ? false : arguments[2];
    var resolved = arguments[3] === undefined ? false : arguments[3];

    _classCallCheck(this, Container);

    this.name = name;
    this.instance = instance;
    this.shared = shared;
    this.resolved = resolved;
  }

  _createClass(Container, [{
    key: 'resolving',
    value: function resolving() {
      var options = arguments[0] === undefined ? [] : arguments[0];

      if (this.isShared() && this.isResolved()) return this.instance;

      var resolved = this.instance;

      if (_.isFunction(resolved)) resolved = resolved.apply(this, options);

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
    var environment = arguments[0] === undefined ? 'production' : arguments[0];

    _classCallCheck(this, Application);

    this.config = {};
    this.environment = environment;
    this.instances = {};
  }

  _createClass(Application, [{
    key: 'detectEnvironment',
    value: function detectEnvironment(environment) {
      if (_.isFunction(environment)) environment = environment.apply(this);

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
      var defaults = arguments[1] === undefined ? null : arguments[1];

      if (typeof this.config[key] !== 'undefined') return this.config[key];

      return defaults;
    }
  }, {
    key: 'put',
    value: function put(key, value) {
      var config = key;

      if (!_.isObject(config)) {
        config = {};
        config[key] = value;
      }

      this.config = _.defaults(config, this.config);

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
    key: 'trigger',
    value: function trigger(name) {
      var options = arguments[1] === undefined ? [] : arguments[1];

      var events = this.make('event');
      events.fire(name, options);

      return this;
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
      if (_.isFunction(callback)) return callback.call(this);
    }
  }]);

  return Application;
})();

exports['default'] = Application;
module.exports = exports['default'];

},{"./helpers":3,"./vendor/underscore":9}],3:[function(require,module,exports){
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
  var seconds = arguments[0] === undefined ? true : arguments[0];

  var time = new Date().getTime();
  var ms = parseInt(time / 1000, 10);
  var sec = (time - ms * 1000) / 1000 + " sec";

  return seconds ? ms : sec;
}

},{}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var _vendorUnderscore = require("../../vendor/underscore");

var _vendorUnderscore2 = _interopRequireDefault(_vendorUnderscore);

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
          return events[_to] = _vendorUnderscore2["default"].clone(events[id]);
        }
      };
    }
  }, {
    key: "listen",
    value: function listen(id, callback) {
      if (!_vendorUnderscore2["default"].isFunction(callback)) throw new Error("Callback is not a function.");

      var response = new Payload(id, callback);

      if (!_vendorUnderscore2["default"].isArray(events[id])) events[id] = [];

      events[id].push(callback);

      return response;
    }
  }, {
    key: "fire",
    value: function fire(id) {
      var options = arguments[1] === undefined ? [] : arguments[1];

      if (id == null) throw new Error("Event ID [" + id + "] is not available.");

      return this.dispatch(events[id], options);
    }
  }, {
    key: "first",
    value: function first(id, options) {
      if (id == null) throw new Error("Event ID [" + id + "] is not available.");

      var first = events[id].slice(0, 1);
      var responses = this.dispatch(first, options);

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
      if (!_vendorUnderscore2["default"].isNull(events[id])) events[id] = null;
    }
  }, {
    key: "forget",
    value: function forget(handler) {
      var me = this;

      if (!handler instanceof Payload) throw new Error("Invalid payload for Event ID [" + id + "]");

      var id = handler.getId();
      var ref = handler.getCallback();

      if (!_vendorUnderscore2["default"].isArray(events[id])) throw new Error("Event ID [" + id + "] is not available.");

      _vendorUnderscore2["default"].each(events[id], function (callback, key) {
        if (ref == callback) {
          events[id].splice(key, 1);
        }
      });
    }
  }, {
    key: "dispatch",
    value: function dispatch(queued) {
      var _this = this;

      var options = arguments[1] === undefined ? [] : arguments[1];
      var halt = arguments[2] === undefined ? false : arguments[2];

      var responses = [];

      if (!_vendorUnderscore2["default"].isArray(queued)) return null;

      _vendorUnderscore2["default"].each(queued, function (callback, key) {
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

exports["default"] = Events;
module.exports = exports["default"];

},{"../../vendor/underscore":9}],5:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var _helpers = require('../../helpers');

var Util = _interopRequireWildcard(_helpers);

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

exports['default'] = Log;
module.exports = exports['default'];

},{"../../helpers":3}],6:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var _helpers = require('../../helpers');

var Util = _interopRequireWildcard(_helpers);

var profilers = {};
var enabled = false;

var Schema = function Schema(id, type) {
  var start = arguments[2] === undefined ? null : arguments[2];

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
      var auto = arguments[0] === undefined ? false : arguments[0];

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
      var name = arguments[0] === undefined ? 'default' : arguments[0];

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

exports['default'] = Profiler;
module.exports = exports['default'];

},{"../../helpers":3}],7:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var _eventsEventsEs6 = require('../events/Events.es6');

var _eventsEventsEs62 = _interopRequireDefault(_eventsEventsEs6);

var _helpers = require('../../helpers');

var Util = _interopRequireWildcard(_helpers);

var dispatcher = _eventsEventsEs62['default'].make();
var requests = {};
var _ = require('../../vendor/underscore');
var api = require('../../vendor/jquery');

function json_parse(data) {
  if (_.isString(data)) {
    try {
      data = api.parseJSON(data);
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
    this.config = {
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
    };
  }

  _createClass(Handler, [{
    key: 'get',
    value: function get(key) {
      var defaults = arguments[1] === undefined ? null : arguments[1];

      if (!_.isUndefined(this.config[key])) return this.config[key];

      return defaults;
    }
  }, {
    key: 'put',
    value: function put(key, value) {
      var config = key;

      if (!_.isObject(key)) {
        config = {};
        config[key] = value;
      }

      this.config = _.defaults(config, this.config);

      return this;
    }
  }, {
    key: 'addHeader',
    value: function addHeader(key, value) {
      var headers = this.get('headers', {});
      headers[key] = value;
      this.put({ headers: headers });

      return this;
    }
  }, {
    key: 'to',
    value: function to(url, object) {
      var dataType = arguments[2] === undefined ? 'json' : arguments[2];
      var headers = arguments[3] === undefined ? {} : arguments[3];

      var supported = ['POST', 'GET', 'PUT', 'DELETED'];

      if (_.isUndefined(url)) throw new Error('Missing required URL parameter.');

      if (object == null) object = window.document;

      var segment = url.split(' ');
      var uri = url;
      var type = this.get('type', 'POST');
      var query = this.get('query', '');

      if (segment.length == 1) {
        uri = segment[0];
      } else {
        if (_.indexOf(supported, segment[0]) > -1) type = segment[0];

        uri = segment[1];
      }

      if (type != 'GET') {
        var queries = uri.split('?');

        if (queries.length > 1) {
          uri = queries[0];
          query = queries[1];
        }
      }

      uri = uri.replace(':baseUrl', this.get('baseUrl', ''));

      this.put({
        dataType: dataType,
        object: object,
        query: query,
        type: type,
        uri: uri,
        headers: headers
      });

      var id = api(object).attr('id');

      if (typeof id != 'undefined') this.put({ id: '#' + id });

      return this;
    }
  }, {
    key: 'execute',
    value: function execute(data) {
      var me = this;
      var name = this.get('name');
      var object = this.get('object');
      var query = this.get('query');

      if (!_.isObject(data)) {
        data = api(object).serialize() + '&' + query;
        if (data == '?&') data = '';
      }

      this.executed = true;

      var payload = {
        type: this.get('type'),
        dataType: this.get('dataType'),
        url: this.get('uri'),
        data: data,
        headers: this.get('headers', {}),
        beforeSend: function beforeSend(xhr) {
          me.fireEvent('beforeSend', name, [me, xhr]);
        },
        complete: function complete(xhr) {
          data = json_parse(xhr.responseText);
          status = xhr.status;
          me.response = xhr;

          if (!_.isUndefined(data) && data.hasOwnProperty('error')) {
            me.fireEvent('onError', name, [data.errors, status, me, xhr]);
            data.errors = null;
          }

          me.fireEvent('onComplete', name, [data, status, me, xhr]);
        }
      };

      api.ajax(payload);

      return this;
    }
  }, {
    key: 'fireEvent',
    value: function fireEvent(type, name, args) {
      dispatcher.fire('Request.' + type, args);
      dispatcher.fire('Request.' + type + ': ' + name, args);

      var callback = this.config[type];

      if (_.isFunction(callback)) callback.apply(this, args);
    }
  }]);

  return Handler;
})();

var Request = (function () {
  function Request(name) {
    _classCallCheck(this, Request);

    return Request.make(name);
  }

  _createClass(Request, null, [{
    key: 'make',
    value: function make() {
      var name = arguments[0] === undefined ? 'default' : arguments[0];

      return Request.find(name);
    }
  }, {
    key: 'get',
    value: function get(key) {
      var defaults = arguments[1] === undefined ? null : arguments[1];

      if (!_.isUndefined(Request.config[key])) return Request.config[key];

      return defaults;
    }
  }, {
    key: 'put',
    value: function put(key, value) {
      var config = key;

      if (!_.isObject(key)) {
        config = {};
        config[key] = value;
      }

      Request.config = _.defaults(config, Request.config);
    }
  }, {
    key: 'find',
    value: function find(name) {
      var request = null;

      if (_.isUndefined(requests[name])) {
        request = new Handler();
        request.config = _.defaults(request.config, Request.config);
        request.put({ name: name });

        return requests[name] = request;
      }

      request = requests[name];

      if (!request.executed) return request;

      var key = _.uniqueId(name + '_');
      var child = new Handler();

      dispatcher.clone('Request.onError: ' + name).to('Request.onError: ' + name);
      dispatcher.clone('Request.onComplete: ' + name).to('Request.onComplete: ' + name);
      dispatcher.clone('Request.beforeSend: ' + name).to('Request.beforeSend: ' + name);

      child.put(parent.config);

      return child;
    }
  }, {
    key: 'config',
    value: {
      baseUrl: null,
      onError: function onError(data, status) {},
      beforeSend: function beforeSend(data, status) {},
      onComplete: function onComplete(data, status) {}
    },
    enumerable: true
  }]);

  return Request;
})();

exports['default'] = Request;
module.exports = exports['default'];

},{"../../helpers":3,"../../vendor/jquery":8,"../../vendor/underscore":9,"../events/Events.es6":4}],8:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = jQuery;
module.exports = exports["default"];

},{}],9:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = _;
module.exports = exports["default"];

},{}]},{},[1]);

//# sourceMappingURL=javie.js.map