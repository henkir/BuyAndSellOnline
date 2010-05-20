/**
 * prototype.historyManager
 * 
 * Observes back/forward button usage and saves states
 * for registered modules into the hash. This allows to
 * bookmark specific states for an application.
 * Based on HistoryManager for Mootools by Harald Kirschner at http://digitarald.de/project/history-manager/
 * @version		1.0
 * 
 * @see			Events, Options
 * 
 * @license		MIT License
 * @author		Alfredo Artiles
 * @copyright	2008 Author
 */
 

/**
 * Extends Array with 2 helpers: isSimilar(array) and complement(array)
 * 
 */
Object.extend(Array.prototype, {

	/**
	 * isSimilar - Returns true for similar arrays, type-insensitive
	 * 
	 * @example
	 *  [1].isSimilar(['1']) == true
	 *  [1, 2].isSimilar([1, false]) == false
	 *  
	 * @return	{Boolean}
	 * @param	{Object} Array
	 */
	isSimilar: function(array) {
		return (this.toString() == array.toString());
	},

	/**
	 * complement - Fills up empty array values from another array, length is the same
	 * 
	 * @example
	 *  [1, null].complement([3, 4]) == [1, 4]
	 *	[undefined, '1'].complement([2, 3, 4]) == [2, '1']

	 * @return	{Array} this
	 * @param	{Object} Array
	 */
	complement: function(array) {
		for (var i = 0, j = this.length; i < j; i++) this[i] = (this[i] != undefined)?this[i]:(array[i] || null);  //$pick(this[i], array[i] || null);
		return this;
	}
});
 
var ProtoHistoryManager = Class.create({
	/**
	 * Default options - Can be overridden with setOptions
	 * 
	 * observeDelay: Duration for checking the state, default 100ms
	 * stateSeparator: Seperator for module-state join, default ';'
	 * iframeSrc: Scr for IE6/7 iframe, must exist on server!
	 * onStart: Fires on start
	 * onRegister: Fires on register
	 * onUnregister: Fires on unregister
	 * onUpdate: Fires when state changes from ...
	 * onStateChange: ... module changes
	 * onObserverChange: ... history change
	 */
	options: {
		observeDelay: 100,
		stateSeparator: ';',
		iframeSrc: 'blank.html',
		onStart: Prototype.emptyFunction,
		onRegister: Prototype.emptyFunction,
		onUnregister: Prototype.emptyFunction,
		onStart: Prototype.emptyFunction,
		onUpdate: Prototype.emptyFunction,
		onStateChange: Prototype.emptyFunction,
		onObserverChange: Prototype.emptyFunction
	},

	/**
	 * Default options for register
	 * 
	 * defaults: Default values array, initially empty.
	 * regexpParams: When regexp is a String, this is the second argument for new RegExp.
	 * skipDefaultMatch: default true; When true onGenerate is not called when current values are similar to the default values.
	 */
	dataOptions: {
		skipDefaultMatch: true,
		defaults: [],
		regexpParams: ''
	},

	/**
	 * Constructur - Class.initialize
	 * 
	 * Options:
	 *  - observeDelay: duration in ms, default 100 - BackBuddy observe the hash for changes periodical
	 *  - stateSeparator: char, default ';' - Separator for multiple module-states in the hash
	 *  - iframeSrc: string, default 'blank.html' - File for the iframe (IE6/7), must exist on the server!
	 *  - Events: onStart, onRegister, onStart, onUpdate, onStateChange, onObserverChange
	 * 
	 * @return	this
	 * 
	 * @param	{Object} options
	 */
	initialize: function(options) {
		if (this.modules) return this;
		this.setOptions(options);
		this.modules = $H({});
		this.count = history.length;
		this.states = [];
		this.states[this.count] = this.getHash();
		this.state = null;
		return this;
	},

	setOptions: function(options){
		Object.extend(this, this.options);
		Object.extend(this, options);
		return this;
	},
	
	/**
	 * Start - Check hash and start observer
	 * 
	 * Call start after registering ALL modules. This start the observer,
	 * reads the state from the hash and calls onMatch for effected modules.
	 * 
	 * @return	this
	 * 
	 */
	start: function() {
		new PeriodicalExecuter(this.observe.bind(this), this.options.observeDelay/1000);
		this.started = true;
		this.observe();
		this.update();
		this.onStart.apply(this, [this.state]);
		return this;
	},

	/**
	 * Registers a module
	 * 
	 * @return	{Object} Object with shortcuts for setValues, setValue, generate and unregister
	 * 
	 * @param	{String} Module key
	 * @param	{RegExp}/{String} Regular expression that matches the string updated from onGenerate
	 * @param	{Function} Will be called when the regexp matches, with the new values as argument.
	 * @param	{Function} Should return the string for the state string, values are first argument
	 * @param	{Array} default values, the input values given to onMatch and onGenerate will be complemented with these
	 * @param	{Object} (optional) options
	 */
	register: function(key, defaults, onMatch, onGenerate, regexp, options) {
		if (!this.modules) this.initialize();
		var data = Object.extend(this.dataOptions, options || {});
		Object.extend(data, {
			defaults: defaults,
			onMatch: onMatch,
			onGenerate: onGenerate,
			regexp: regexp
		});
		data.regexp = data.regexp || key + '-([\\w_-]*)';
		if (typeof data.regexp == 'string') data.regexp = new RegExp(data.regexp, data.regexpParams);
		data.onGenerate = data.onGenerate || function(values) { return key + '-' + values[0]; };

		data.values = data.defaults.clone();
		this.modules.set(key, data);
		//Event.fire(this, 'onUnregister', [key, data]);
		this.onUnregister.apply(this, [key, data]);
		return {
			setValues: function(values) {
				return this.setValues(key, values);
			}.bind(this),
			setValue: function(index, value) {
				return this.setValue(key, index, value);
			}.bind(this),
			generate: function(values) {
				return this.generate(key, values);
			}.bind(this),
			unregister: function() {
				return this.unregister(key);
			}.bind(this)
		};
	},

	/**
	 * unregister - Removes an module from the
	 * 
	 * @param	{String} Module key
	 */
	unregister: function(key) {
		//Event.fire(this, 'onRegister', [key]);
		this.onRegister.apply(this, [key]);
		this.modules.unset(key);
	},

	/**
	 * setValues - Set all values new, updates new state
	 * 
	 * @param	{String} Module key
	 * @param	{Object} Complete values
	 */
	setValues: function(key, values) {
		var data = this.modules.get(key);
		if (!data || data.values.isSimilar(values)) return this;
		data.values = values;
		this.update();
		return this;
	},

	/**
	 * setValue - Set one value, updates new state
	 * 
	 * @param	{String} Module key
	 * @param	{Number} Value index
	 * @param	{Object} Value
	 */
	setValue: function(key, index, value) {
		var data = this.modules.get(key);
		if (!data || data.values[index] == value) return this;
		data.values[index] = value;
		this.update();
		return this;
	},

	/**
	 * generate - Generates a hash from the given
	 * 
	 * @param	{String} Module key
	 * @param	{Number} Value index
	 * @param	{Object} Value
	 */
	generate: function(key, values) {
		var data = this.modules.get(key);
		var current = data.values.clone();
		data.values = values;
		var state = this.generateState();
		data.values = current;
		return '#' + state;
	},

	observe: function() {
		if (this.timeout) return;
		var state = this.getState();
		
		if (this.state == state) return;
		if ((Prototype.Browser.IE || Prototype.Browser.WebKit) && (this.state !== null)) this.setState(state, true);
		else this.state = state;
		this.modules.each(function(data) {
			var bits = state.match(data.value.regexp);
			if (bits) {
				bits.splice(0, 1);
				bits.complement(data.value.defaults);
				if (!bits.isSimilar(data.value.defaults)) data.value.values = bits;
			} else data.value.values = data.value.defaults.clone();
			data.value.onMatch(data.value.values, data.value.defaults);
		});
		//Event.fire(this, 'onStateChange', [state]).
		//Event.fire(this, 'onObserverChange', [state]);
		this.onStateChange.apply(this, [state]);
		this.onObserverChange.apply(this, [state]);
	},

	generateState: function() {
		var state = [];
		this.modules.each(function(data, key) {
			if (data.value.skipDefaultMatch && data.value.values.isSimilar(data.value.defaults)) return;
			state.push(data.value.onGenerate(data.value.values));
		});
		return state.join(this.options.stateSeparator);
	},

	update: function() {
		if (!this.started) return this;
		var state = this.generateState();
		if ((!this.state && !state) || (this.state == state)) return this;
		this.setState(state);
		this.onStateChange.apply(this, [state]);
		this.onUpdate.apply(this, [state]);
		//Event.fire(this, 'onStateChange', [state]).
		//Event.fire(this, 'onUpdate', [state]);
		return this;
	},

	observeTimeout: function() {
		if (this.timeout) this.timeout = clearInterval(this.timeout);
		else this.timeout = this.observeTimeout.bind(this).delay(200/1000);
	},

	getHash: function() {
		var href = top.location.href;
		var pos = href.indexOf('#') + 1;
		return (pos) ? href.substr(pos) : '';
	},

	getState: function() {
		var state = this.getHash();
		
		if (this.iframe) {
			var doc = this.iframe.contentWindow.document;
			if (doc && doc.body.id == 'state') {
				var istate = doc.body.innerText;
				if (this.state == state) return istate;
				this.istateOld = true;
			} else return this.istate;
		}
		if (Prototype.Browser.WebKit && history.length != this.count) {
			this.count = history.length;
			return (this.states[this.count - 1] != undefined)?this.states[this.count - 1] != undefined:state;
		}
		return state;
	},

	setState: function(state, fix) {
	
		state = state!=undefined?state:'';

		if (Prototype.Browser.WebKit) {
			if (!this.form) {
				this.form = new Element('form', {'method': 'get'});
				document.body.appendChild(this.form);;
			}	
			this.count = history.length;
			this.states[this.count] = state;
			this.observeTimeout();
			this.form.setProperty('action', '#' + state).submit();
		} else {
			top.location.hash = state || '#';
		}	
		
		if (Prototype.Browser.IE && (!fix || this.istateOld)) {
			if (!this.iframe) {
				this.iframe = new Element('iframe', {
					'src': this.options.iframeSrc,
					'styles': 'display: none;',
					'width': '1',
					'height': '1'
				});
				document.body.appendChild(this.iframe);

				this.istate = this.state;
			}
			try {
				var doc = this.iframe.contentWindow.document;
				doc.open();
				doc.write('<html><body id="state">' + state + '</body></html>');
				doc.close();
				this.istateOld = false;
			} catch(e) {};
		}
		this.state = state;
	},

	extend: Object.extend
});


