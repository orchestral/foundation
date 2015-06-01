/**
 * ========================================================================
 * Javie
 * ========================================================================
 *
 * @package Javie
 * @require underscore, console, jQuery/Zepto
 * @version 1.2.0
 * @author  Mior Muhammad Zaki <https://github.com/crynobone>
 * @license MIT License
 * ========================================================================
 */

(function() {
  var Application, array_make, root, _;

  root = this;

  _ = root._;

  if (!_ && (typeof require !== "undefined" && require !== null)) {
    _ = require('underscore');
  }

  if (!_) {
    throw new Error("underscore.js is missing");
  }

  array_make = function(args) {
    return Array.prototype.slice.call(args);
  };

  Application = (function() {
    function Application() {}

    Application.prototype.config = {};

    Application.prototype.environment = 'production';

    Application.prototype.instances = {};

    Application.prototype.detectEnvironment = function(environment) {
      if (_.isFunction(environment) === true) {
        environment = environment.apply(root);
      }
      return this.environment = environment;
    };

    Application.prototype.env = function() {
      return this.environment;
    };

    Application.prototype.get = function(key, alt) {
      if (typeof this.config[key] !== 'undefined') {
        return this.config[key];
      }
      return alt != null ? alt : alt = null;
    };

    Application.prototype.put = function(key, value) {
      var config;
      config = key;
      if (!_.isObject(config)) {
        config = {};
        config[key] = value;
      }
      this.config = _.defaults(config, this.config);
      return this;
    };

    Application.prototype.on = function(name, callback) {
      var dispatcher;
      dispatcher = this.instances['event'];
      dispatcher.listen(name, callback);
      return this;
    };

    Application.prototype.trigger = function(name, options) {
      var dispatcher;
      dispatcher = this.instances['event'];
      dispatcher.fire(name, options);
      return this;
    };

    Application.prototype.bind = function(name, instance) {
      this.instances[name] = instance;
      return this;
    };

    Application.prototype.make = function(name) {
      var base, options;
      options = array_make(arguments);
      name = options.shift();
      base = this.resolve(name);
      if (_.isFunction(base)) {
        return base.apply(root, options);
      } else {
        return base;
      }
    };

    Application.prototype.resolve = function(name) {
      var base;
      base = this.instances[name];
      if (_.isUndefined(base)) {
        return null;
      } else {
        return base;
      }
    };

    Application.prototype.when = function(environment, callback) {
      var env;
      if (this.ENV != null) {
        env = this.ENV;
      }
      if (this.environment != null) {
        env = this.environment;
      }
      if (env === environment || environment === '*') {
        return this.run(callback);
      }
    };

    Application.prototype.run = function(callback) {
      if (_.isFunction(callback)) {
        return callback.call(this);
      }
    };

    return Application;

  })();

  root.Javie = new Application;

}).call(this);


/*
 * ==========================================================
 * Javie.EventDispatcher
 * ==========================================================
 *
 * Event Dispatcher Helper for Client-side JavaScript
 * and Node.js
 *
 * @package Javie
 * @class   Event
 * @require underscore
 * @version 1.2.0
 * @since   0.1.0
 * @author  Mior Muhammad Zaki <https://github.com/crynobone>
 * @license MIT License
 * ==========================================================
 */

(function() {
  var EventDispatcher, EventRepository, dispatcher, events, root, _;

  dispatcher = null;

  events = {};

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  _ = root._;

  if (!_ && (typeof require !== "undefined" && require !== null)) {
    _ = require('underscore');
  }

  if (!_) {
    throw new Error("underscore.js is missing");
  }

  EventDispatcher = (function() {
    function EventDispatcher() {}

    EventDispatcher.prototype.clone = function(id) {
      var clonable;
      return clonable = {
        to: function(cloneTo) {
          events[cloneTo] = _.clone(events[id]);
          return true;
        }
      };
    };

    EventDispatcher.prototype.listen = function(id, callback) {
      var response;
      if (_.isFunction(callback) === false) {
        throw new Error("Callback is not a function");
      }
      response = {
        id: id,
        callback: callback
      };
      if (events[id] == null) {
        events[id] = [];
      }
      events[id].push(callback);
      return response;
    };

    EventDispatcher.prototype.listener = function(id, callback) {
      return this.listen(id, callback);
    };

    EventDispatcher.prototype.fire = function(id, options) {
      var me, responses, run_each_events;
      me = this;
      responses = [];
      if (id == null) {
        throw new Error("Event ID [" + id + "] is not available");
      }
      if (events[id] == null) {
        return null;
      }
      run_each_events = function(callback, key) {
        var applied;
        applied = callback.apply(me, options || []);
        return responses.push(applied);
      };
      _.each(events[id], run_each_events);
      return responses;
    };

    EventDispatcher.prototype.first = function(id, options) {
      var first, me, responses, run_each_events;
      me = this;
      responses = [];
      if (id == null) {
        throw new Error("Event ID [" + id + "] is not available");
      }
      if (events[id] == null) {
        return null;
      }
      first = events[id].slice(0, 1);
      run_each_events = function(callback, key) {
        var applied;
        applied = callback.apply(me, options || []);
        return responses.push(applied);
      };
      _.each(first, run_each_events);
      return responses[0];
    };

    EventDispatcher.prototype.until = function(id, options) {
      var me, responses, run_each_events;
      me = this;
      responses = null;
      if (id == null) {
        throw new Error("Event ID [" + id + "] is not available");
      }
      if (events[id] == null) {
        return null;
      }
      run_each_events = function(callback, key) {
        var applied;
        applied = callback.apply(me, options || []);
        if (responses == null) {
          return responses.push(applied);
        }
      };
      _.each(events[id], run_each_events);
      return responses;
    };

    EventDispatcher.prototype.flush = function(id) {
      if (!_.isUndefined(events[id])) {
        events[id] = null;
      }
      return true;
    };

    EventDispatcher.prototype.forget = function(handler) {
      var id, loop_each_events, me, ref;
      me = this;
      id = handler.id;
      ref = handler.callback;
      if (!_.isString(id)) {
        throw new Error("Event ID [" + id + "] is not provided");
      }
      if (!_.isFunction(callback)) {
        throw new Error('Callback is not a function');
      }
      if (events[id] == null) {
        throw new Error("Event ID [" + id + "] is not available");
      }
      loop_each_events = function(callback, key) {
        if (ref === callback) {
          return events[id].splice(key, 1);
        }
      };
      _.each(events[id], loop_each_events);
      return true;
    };

    return EventDispatcher;

  })();

  EventRepository = (function() {
    function EventRepository() {
      return EventRepository.make();
    }

    EventRepository.make = function() {
      return dispatcher != null ? dispatcher : dispatcher = new EventDispatcher;
    };

    return EventRepository;

  })();

  if (typeof exports !== "undefined" && exports !== null) {
    if ((typeof module !== "undefined" && module !== null) && module.exports) {
      module.exports = EventRepository;
    }
    exports.EventDispatcher = EventRepository;
  } else {
    if (root.Javie == null) {
      root.Javie = {};
    }
    root.Javie.Events = EventRepository;
    root.Javie.EventDispatcher = EventRepository;
  }

}).call(this);


/*
 * ==========================================================
 * Javie.Logger
 * ==========================================================
 *
 * Logger Helper for Client-side JavaScript and Node.js
 *
 * @package Javie
 * @class   Logger
 * @require console
 * @version 1.2.0
 * @since   0.1.0
 * @author  Mior Muhammad Zaki <https://github.com/crynobone>
 * @license MIT License
 * ==========================================================
 */

(function() {
  var Logger, LoggerRepository, array_make, dispatch, enabled, level, logger, post, root;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  logger = null;

  enabled = false;

  level = {
    ERROR: 'error',
    WARNING: 'warning',
    INFO: 'info',
    DEBUG: 'debug',
    LOG: 'log'
  };

  array_make = function(args) {
    return Array.prototype.slice.call(args);
  };

  dispatch = function(type, message) {
    if (!enabled) {
      return false;
    }
    return post(type, message);
  };

  post = function(type, message) {
    var c;
    c = console;
    switch (type) {
      case 'info':
        c.info(message);
        return true;
      case 'debug' && (c.debug != null):
        c.debug(message);
        return true;
      case 'warning':
        c.warn(message);
        return true;
      case 'error' && (c.error != null):
        c.error(message);
        return true;
      case 'log':
        c.log(message);
        return true;
      default:
        c.log("[" + (type.toUpperCase()) + "]", message);
        return true;
    }
  };

  Logger = (function() {
    function Logger() {}

    Logger.prototype.logs = [];

    Logger.prototype.dispatch = function(type, message) {
      var result;
      result = dispatch(type, message);
      message.unshift(type);
      this.logs.push(message);
      return result;
    };

    Logger.prototype.info = function() {
      return this.dispatch(level.INFO, array_make(arguments));
    };

    Logger.prototype.debug = function() {
      return this.dispatch(level.DEBUG, array_make(arguments));
    };

    Logger.prototype.warning = function() {
      return this.dispatch(level.WARNING, array_make(arguments));
    };

    Logger.prototype.log = function() {
      return this.dispatch(level.LOG, array_make(arguments));
    };

    Logger.prototype.post = function(type, message) {
      return this.dispatch(type, [message]);
    };

    return Logger;

  })();

  LoggerRepository = (function() {
    function LoggerRepository() {
      return LoggerRepository.make();
    }

    LoggerRepository.make = function() {
      return logger != null ? logger : logger = new Logger;
    };

    LoggerRepository.enable = function() {
      return enabled = true;
    };

    LoggerRepository.disable = function() {
      return enabled = false;
    };

    LoggerRepository.status = function() {
      return enabled;
    };

    return LoggerRepository;

  })();

  if (typeof exports !== "undefined" && exports !== null) {
    if ((typeof module !== "undefined" && module !== null) && module.exports) {
      module.exports = LoggerRepository;
    }
    root.Logger = LoggerRepository;
  } else {
    if (root.Javie == null) {
      root.Javie = {};
    }
    root.Javie.Logger = LoggerRepository;
  }

}).call(this);


/*
 * ==========================================================
 * Javie.Profiler
 * ==========================================================
 *
 * Profiler Helper for Client-side JavaScript and Node.js
 *
 * @package Javie
 * @class   Profiler
 * @require console
 * @version 1.2.0
 * @since   0.1.0
 * @author  Mior Muhammad Zaki <https://github.com/crynobone>
 * @license MIT License
 * ==========================================================
 */

(function() {
  var Profiler, ProfilerRepository, enabled, microtime, profilers, root, schema;

  profilers = {};

  enabled = false;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  schema = function(id, type, start) {
    if (id == null) {
      id = '';
    }
    if (type == null) {
      type = '';
    }
    if (start == null) {
      start = microtime(true);
    }
    return {
      id: id,
      type: type,
      start: start,
      end: null,
      total: null,
      message: ''
    };
  };

  microtime = function(seconds) {
    var ms, sec, time;
    time = new Date().getTime();
    ms = parseInt(time / 1000, 10);
    sec = "" + ((time - (ms * 1000)) / 1000) + " sec";
    if (seconds === true) {
      return ms;
    } else {
      return sec;
    }
  };

  Profiler = (function() {
    Profiler.prototype.logs = null;

    Profiler.prototype.pair = null;

    Profiler.prototype.started = null;

    function Profiler() {
      this.logs = [];
      this.pair = {};
      this.started = microtime(true);
    }

    Profiler.prototype.time = function(id, message) {
      var key, log;
      if (id == null) {
        id = this.logs.length;
      }
      if (enabled === false) {
        return null;
      }
      log = schema('time', id);
      log.message = message.toString();
      key = this.pair["time" + id];
      if (typeof key !== 'undefined') {
        this.logs[key] = log;
      } else {
        this.logs.push(log);
        this.pair["time" + id] = this.logs.length - 1;
      }
      console.time(id);
      return id;
    };

    Profiler.prototype.timeEnd = function(id, message) {
      var end, key, log, start, total;
      if (id == null) {
        id = this.logs.length;
      }
      if (enabled === false) {
        return null;
      }
      key = this.pair["time" + id];
      if (typeof key !== 'undefined') {
        console.timeEnd(id);
        log = this.logs[key];
      } else {
        log = schema('time', id, this.started);
        if (typeof message !== 'undefined') {
          log.message = message;
        }
        this.logs.push(log);
        key = this.logs.length - 1;
      }
      end = log.end = microtime(true);
      start = log.start;
      total = end - start;
      log.total = total;
      this.logs[key] = log;
      return total;
    };

    Profiler.prototype.trace = function() {
      if (enabled) {
        console.trace();
      }
      return true;
    };

    Profiler.prototype.output = function(auto) {
      var log, sec, _i, _len, _ref;
      if (auto === true) {
        enabled = true;
      }
      if (enabled === false) {
        return false;
      }
      _ref = this.logs;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        log = _ref[_i];
        if (log.type === 'time') {
          sec = Math.floor(log.total * 1000);
          console.log('%s: %s - %dms', log.id, log.message, sec);
        } else {
          console.log(log.id, log.message);
        }
      }
      return true;
    };

    return Profiler;

  })();

  ProfilerRepository = (function() {
    function ProfilerRepository(name) {
      return ProfilerRepository.make(name);
    }

    ProfilerRepository.make = function(name) {
      if (!((name != null) || name !== '')) {
        name = 'default';
      }
      return profilers[name] != null ? profilers[name] : profilers[name] = new Profiler;
    };

    ProfilerRepository.enable = function() {
      return enabled = true;
    };

    ProfilerRepository.disable = function() {
      return enabled = false;
    };

    ProfilerRepository.status = function() {
      return enabled;
    };

    return ProfilerRepository;

  })();

  if (typeof exports !== "undefined" && exports !== null) {
    if ((typeof module !== "undefined" && module !== null) && module.exports) {
      module.exports = ProfilerRepository;
    }
    exports.Profiler = ProfilerRepository;
  } else {
    if (root.Javie == null) {
      root.Javie = {};
    }
    root.Javie.Profiler = ProfilerRepository;
  }

}).call(this);


/*
 * ==========================================================
 * Javie.Request
 * ==========================================================
 *
 * Request Helper for Client-side JavaScript
 *
 * @package Javie
 * @class   Request
 * @require underscore, jQuery/Zepto
 * @version 1.1.3
 * @since   0.1.1
 * @author  Mior Muhammad Zaki <https://github.com/crynobone>
 * @license MIT
 * ==========================================================
 */

(function() {
  var Request, RequestRepository, api, dispatcher, find_request, json_parse, requests, root, _;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  requests = {};

  dispatcher = null;

  if (typeof root.Javie === 'undefined') {
    throw new Error("Javie is missing");
  }

  if (typeof root.Javie.EventDispatcher === 'undefined') {
    throw new Error("Javie.EventDispatcher is missing");
  }

  dispatcher = root.Javie.EventDispatcher.make();

  _ = root._;

  if (!_ && (typeof require !== "undefined" && require !== null)) {
    _ = require('underscore');
  }

  if (!_) {
    throw new Error("underscore.js is missing");
  }

  api = root.$;

  if (typeof api === 'undefined' || api === null) {
    throw new Error("Required jQuery or Zepto object is missing");
  }

  find_request = function(name) {
    var child, child_name, parent, request;
    request = null;
    if (!_.isUndefined(requests[name])) {
      parent = requests[name];
      if (parent.executed === true) {
        child_name = _.uniqueId("" + name + "_");
        child = new Request;
        dispatcher.clone("Request.onError: " + name).to("Request.onError: " + child_name);
        dispatcher.clone("Request.onComplete: " + name).to("Request.onComplete: " + child_name);
        dispatcher.clone("Request.beforeSend: " + name).to("Request.beforeSend: " + child_name);
        child.put(parent.config);
        request = child;
      }
      request = parent;
    } else {
      request = new Request;
      request.config = _.defaults(request.config, RequestRepository.config);
      request.put({
        'name': name
      });
      requests[name] = request;
    }
    return request;
  };

  json_parse = function(data) {
    var e;
    if (_.isString(data) === true) {
      try {
        data = api.parseJSON(data);
      } catch (_error) {
        e = _error;
      }
    }
    return data;
  };

  Request = (function() {
    function Request() {}

    Request.prototype.executed = false;

    Request.prototype.response = null;

    Request.prototype.config = {
      'name': '',
      'type': 'GET',
      'uri': '',
      'query': '',
      'data': '',
      'dataType': 'json',
      'id': '',
      'object': null
    };

    Request.prototype.get = function(key, alt) {
      if (typeof this.config[key] !== 'undefined') {
        return this.config[key];
      }
      return alt != null ? alt : alt = null;
    };

    Request.prototype.put = function(key, value) {
      var config;
      config = key;
      if (!_.isObject(key)) {
        config = {};
        config[key] = value;
      }
      return this.config = _.defaults(config, this.config);
    };

    Request.prototype.to = function(url, object, data_type) {
      var id, queries, request_method, segment, type, uri;
      this.put({
        'dataType': data_type != null ? data_type : data_type = 'json'
      });
      request_method = ['POST', 'GET', 'PUT', 'DELETE'];
      if (_.isUndefined(url)) {
        throw new Error("Missing required url parameter");
      }
      if (object == null) {
        object = root.document;
      }
      this.put({
        'object': object
      });
      segment = url.split(' ');
      if (segment.length === 1) {
        uri = segment[0];
      } else {
        if (_.indexOf(request_method, segment[0]) !== -1) {
          type = segment[0];
        }
        uri = segment[1];
        if (type !== 'GET') {
          queries = uri.split('?');
          if (queries.length > 1) {
            url = queries[0];
            this.put({
              'query': queries[1]
            });
          }
        }
        uri = uri.replace(':baseUrl', this.get('baseUrl', ''));
        this.put({
          'type': type,
          'uri': uri
        });
      }
      id = api(this.get('object')).attr('id');
      if (typeof id !== 'undefined') {
        this.put({
          'id': "#" + id
        });
      }
      return this;
    };

    Request.prototype.execute = function(data) {
      var me, name, request;
      me = this;
      name = this.get('name');
      if (!_.isObject(data)) {
        data = "" + (api(this.get('object')).serialize()) + "&" + (this.get('query'));
        if (data === '?&') {
          data = '';
        }
      }
      this.executed = true;
      dispatcher.fire('Request.beforeSend', [this]);
      dispatcher.fire("Request.beforeSend: " + name, [this]);
      this.config['beforeSend'](this);
      request = {
        'type': this.get('type'),
        'dataType': this.get('dataType'),
        'url': this.get('uri'),
        'data': data,
        'complete': function(xhr) {
          var status;
          data = json_parse(xhr.responseText);
          status = xhr.status;
          me.response = xhr;
          if (!_.isUndefined(data) && data.hasOwnProperty('errors')) {
            dispatcher.fire('Request.onError', [data.errors, status, me]);
            dispatcher.fire("Request.onError: " + name, [data.errors, status, me]);
            me.config['onError'](data.errors, status, me);
            data.errors = null;
          }
          dispatcher.fire('Request.onComplete', [data, status, me]);
          dispatcher.fire("Request.onComplete: " + name, [data, status, me]);
          me.config['onComplete'](data, status, me);
          return true;
        }
      };
      api.ajax(request);
      return this;
    };

    return Request;

  })();

  RequestRepository = (function() {
    function RequestRepository(name) {
      return RequestRepository.make(name);
    }

    RequestRepository.make = function(name) {
      if (!_.isString(name)) {
        name = 'default';
      }
      return find_request(name);
    };

    RequestRepository.config = {
      'baseUrl': null,
      'onError': function(data, status) {},
      'beforeSend': function(data, status) {},
      'onComplete': function(data, status) {}
    };

    RequestRepository.get = function(key, alt) {
      if (alt == null) {
        alt = null;
      }
      if (_.isUndefined(this.config[key])) {
        return alt;
      }
      return this.config[key];
    };

    RequestRepository.put = function(key, value) {
      var config;
      config = key;
      if (!_.isObject(key)) {
        config = {};
        config[key] = value;
      }
      return this.config = _.defaults(config, this.config);
    };

    return RequestRepository;

  })();

  if (typeof exports !== "undefined" && exports !== null) {
    if ((typeof module !== "undefined" && module !== null) && module.exports) {
      module.exports = RequestRepository;
    }
    root.Request = RequestRepository;
  } else {
    root.Javie.Request = RequestRepository;
  }

}).call(this);

(function() {
  var javie, root;

  root = this;

  javie = root.Javie;

  javie.bind('underscore', function() {
    return root._;
  });

  javie.bind('event', function() {
    return new javie.EventDispatcher;
  });

  javie.bind('profiler', function(name) {
    if (name != null) {
      return new javie.Profiler(name);
    } else {
      return javie.Profiler;
    }
  });

  javie.bind('log', function() {
    return new javie.Logger;
  });

  javie.bind('request', function(name) {
    if (name != null) {
      return new javie.Request(name);
    } else {
      return javie.Request;
    }
  });

}).call(this);
