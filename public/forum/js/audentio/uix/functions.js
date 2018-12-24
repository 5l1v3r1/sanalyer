// #######################################################################################
// #																					 #
// #							 Request Animation Frame Polyfill						 #
// #																					 #
// #######################################################################################

// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
// http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating
 
// requestAnimationFrame polyfill by Erik Möller. fixes from Paul Irish and Tino Zijdel
// refactored by Yannick Albert
 
// MIT license
(function(window) {
		var equestAnimationFrame = 'equestAnimationFrame',
				requestAnimationFrame = 'r' + equestAnimationFrame,
				
				ancelAnimationFrame = 'ancelAnimationFrame',
				cancelAnimationFrame = 'c' + ancelAnimationFrame,
				
				expectedTime = 0,
				vendors = ['moz', 'ms', 'o', 'webkit'],
				vendor;
		
		while(!window[requestAnimationFrame] && (vendor = vendors.pop())) {
				window[requestAnimationFrame] = window[vendor + 'R' + equestAnimationFrame];
				window[cancelAnimationFrame] = window[vendor + 'C' + ancelAnimationFrame] || window[vendor + 'CancelR' + equestAnimationFrame];
		}
		
		if(!window[requestAnimationFrame]) {
				window[requestAnimationFrame] = function(callback) {
						var currentTime = new Date().getTime(),
								adjustedDelay = 16 - (currentTime - expectedTime),
								delay = adjustedDelay > 0 ? adjustedDelay : 0;
						
						expectedTime = currentTime + delay;
						
						return setTimeout(function() {
								callback(expectedTime);
						}, delay);
				};
				
				window[cancelAnimationFrame] = clearTimeout;
		}
}(this));


// #######################################################################################
// #																					 #
// #							 			Modernizr									 #
// #																					 #
// #######################################################################################



/*!
 * modernizr v3.0.0-alpha.3
 * Build http://v3.modernizr.com/download/#-borderradius-cssanimations-csspositionsticky-csstransforms-csstransforms3d-csstransitions-draganddrop-emoji-flexbox-flexboxlegacy-fontface-rgba-touchevents-dontmin
 *
 * Copyright (c)
 *	Faruk Ates
 *	Paul Irish
 *	Alex Sexton
 *	Ryan Seddon
 *	Alexander Farkas
 *	Patrick Kettner
 *	Stu Cox
 *	Richard Herrera

 * MIT License
 */

/*
 * Modernizr tests which native CSS3 and HTML5 features are available in the
 * current UA and makes the results available to you in two ways: as properties on
 * a global `Modernizr` object, and as classes on the `<html>` element. This
 * information allows you to progressively enhance your pages with a granular level
 * of control over the experience.
*/

;(function(window, document, undefined){
	var classes = [];
	

	var tests = [];
	

	var ModernizrProto = {
		// The current version, dummy
		_version: '3.0.0-alpha.3',

		// Any settings that don't work as separate modules
		// can go in here as configuration.
		_config: {
			'classPrefix' : '',
			'enableClasses' : true,
			'enableJSClass' : true,
			'usePrefixes' : true
		},

		// Queue of tests
		_q: [],

		// Stub these for people who are listening
		on: function( test, cb ) {
			// I don't really think people should do this, but we can
			// safe guard it a bit.
			// -- NOTE:: this gets WAY overridden in src/addTest for
			// actual async tests. This is in case people listen to
			// synchronous tests. I would leave it out, but the code
			// to *disallow* sync tests in the real version of this
			// function is actually larger than this.
			var self = this;
			setTimeout(function() {
				cb(self[test]);
			}, 0);
		},

		addTest: function( name, fn, options ) {
			tests.push({name : name, fn : fn, options : options });
		},

		addAsyncTest: function (fn) {
			tests.push({name : null, fn : fn});
		}
	};

	

	// Fake some of Object.create
	// so we can force non test results
	// to be non "own" properties.
	var Modernizr = function(){};
	Modernizr.prototype = ModernizrProto;

	// Leak modernizr globally when you `require` it
	// rather than force it here.
	// Overwrite name so constructor name is nicer :D
	Modernizr = new Modernizr();

	

	/**
	 * is returns a boolean for if typeof obj is exactly type.
	 */
	function is( obj, type ) {
		return typeof obj === type;
	}
	;

	// Run through all tests and detect their support in the current UA.
	function testRunner() {
		var featureNames;
		var feature;
		var aliasIdx;
		var result;
		var nameIdx;
		var featureName;
		var featureNameSplit;

		for ( var featureIdx in tests ) {
			featureNames = [];
			feature = tests[featureIdx];
			// run the test, throw the return value into the Modernizr,
			//	 then based on that boolean, define an appropriate className
			//	 and push it into an array of classes we'll join later.
			//
			//	 If there is no name, it's an 'async' test that is run,
			//	 but not directly added to the object. That should
			//	 be done with a post-run addTest call.
			if ( feature.name ) {
				featureNames.push(feature.name.toLowerCase());

				if (feature.options && feature.options.aliases && feature.options.aliases.length) {
					// Add all the aliases into the names list
					for (aliasIdx = 0; aliasIdx < feature.options.aliases.length; aliasIdx++) {
						featureNames.push(feature.options.aliases[aliasIdx].toLowerCase());
					}
				}
			}

			// Run the test, or use the raw value if it's not a function
			result = is(feature.fn, 'function') ? feature.fn() : feature.fn;


			// Set each of the names on the Modernizr object
			for (nameIdx = 0; nameIdx < featureNames.length; nameIdx++) {
				featureName = featureNames[nameIdx];
				// Support dot properties as sub tests. We don't do checking to make sure
				// that the implied parent tests have been added. You must call them in
				// order (either in the test, or make the parent test a dependency).
				//
				// Cap it to TWO to make the logic simple and because who needs that kind of subtesting
				// hashtag famous last words
				featureNameSplit = featureName.split('.');

				if (featureNameSplit.length === 1) {
					Modernizr[featureNameSplit[0]] = result;
				} else {
					// cast to a Boolean, if not one already
					/* jshint -W053 */
					if (Modernizr[featureNameSplit[0]] && !(Modernizr[featureNameSplit[0]] instanceof Boolean)) {
						Modernizr[featureNameSplit[0]] = new Boolean(Modernizr[featureNameSplit[0]]);
					}

					Modernizr[featureNameSplit[0]][featureNameSplit[1]] = result;
				}

				classes.push((result ? '' : 'no-') + featureNameSplit.join('-'));
			}
		}
	}

	;

	var docElement = document.documentElement;
	

	// Pass in an and array of class names, e.g.:
	//	['no-webp', 'borderradius', ...]
	function setClasses( classes ) {
		var className = docElement.className;
		var classPrefix = Modernizr._config.classPrefix || '';

		// Change `no-js` to `js` (we do this independently of the `enableClasses`
		// option)
		// Handle classPrefix on this too
		if(Modernizr._config.enableJSClass) {
			var reJS = new RegExp('(^|\\s)'+classPrefix+'no-js(\\s|$)');
			className = className.replace(reJS, '$1'+classPrefix+'js$2');
		}

		if(Modernizr._config.enableClasses) {
			// Add the new classes
			className += ' ' + classPrefix + classes.join(' ' + classPrefix);
			docElement.className = className;
		}

	}

	;

	var createElement = function() {
		if (typeof document.createElement !== 'function') {
			// This is the case in IE7, where the type of createElement is "object".
			// For this reason, we cannot call apply() as Object is not a Function.
			return document.createElement(arguments[0]);
		} else {
			return document.createElement.apply(document, arguments);
		}
	};
	
/*!
{
	"name": "CSS rgba",
	"caniuse": "css3-colors",
	"property": "rgba",
	"tags": ["css"],
	"notes": [{
		"name": "CSSTricks Tutorial",
		"href": "http://css-tricks.com/rgba-browser-support/"
	}]
}
!*/

	Modernizr.addTest('rgba', function() {
		var elem = createElement('div');
		var style = elem.style;
		style.cssText = 'background-color:rgba(150,255,150,.5)';

		return ('' + style.backgroundColor).indexOf('rgba') > -1;
	});

/*!
{
	"name": "Drag & Drop",
	"property": "draganddrop",
	"caniuse": "dragndrop",
	"knownBugs": ["Mobile browsers like Android, iOS < 6, and Firefox OS technically support the APIs, but don't expose it to the end user, resulting in a false positive."],
	"notes": [{
		"name": "W3C spec",
		"href": "http://www.w3.org/TR/2010/WD-html5-20101019/dnd.html"
	}],
	"polyfills": ["dropfile", "moxie", "fileapi"]
}
!*/
/* DOC
Detects support for native drag & drop of elements.
*/

	Modernizr.addTest('draganddrop', function() {
		var div = createElement('div');
		return ('draggable' in div) || ('ondragstart' in div && 'ondrop' in div);
	});


	// List of property values to set for css tests. See ticket #21
	var prefixes = (ModernizrProto._config.usePrefixes ? ' -webkit- -moz- -o- -ms- '.split(' ') : []);

	// expose these for the plugin API. Look in the source for how to join() them against your input
	ModernizrProto._prefixes = prefixes;

	
/*!
{
	"name": "CSS position: sticky",
	"property": "csspositionsticky",
	"tags": ["css"],
	"builderAliases": ["css_positionsticky"],
	"notes": [{
		"name": "Chrome bug report",
		"href":"https://code.google.com/p/chromium/issues/detail?id=322972"
	}],
	"warnings": [ "using position:sticky on anything but top aligned elements is buggy in Chrome < 37 and iOS <=7+" ]
}
!*/

	// Sticky positioning - constrains an element to be positioned inside the
	// intersection of its container box, and the viewport.
	Modernizr.addTest('csspositionsticky', function() {
		var prop = 'position:';
		var value = 'sticky';
		var el = createElement('modernizr');
		var mStyle = el.style;

		mStyle.cssText = prop + prefixes.join(value + ';' + prop).slice(0, -prop.length);

		return mStyle.position.indexOf(value) !== -1;
	});

/*!
{
	"name": "CSS Supports",
	"property": "supports",
	"caniuse": "css-featurequeries",
	"tags": ["css"],
	"builderAliases": ["css_supports"],
	"notes": [{
		"name": "W3 Spec",
		"href": "http://dev.w3.org/csswg/css3-conditional/#at-supports"
	},{
		"name": "Related Github Issue",
		"href": "github.com/Modernizr/Modernizr/issues/648"
	},{
		"name": "W3 Info",
		"href": "http://dev.w3.org/csswg/css3-conditional/#the-csssupportsrule-interface"
	}]
}
!*/

	var newSyntax = 'CSS' in window && 'supports' in window.CSS;
	var oldSyntax = 'supportsCSS' in window;
	Modernizr.addTest('supports', newSyntax || oldSyntax);

/*!
{
	"name": "Canvas",
	"property": "canvas",
	"caniuse": "canvas",
	"tags": ["canvas", "graphics"],
	"polyfills": ["flashcanvas", "excanvas", "slcanvas", "fxcanvas"]
}
!*/
/* DOC
Detects support for the `<canvas>` element for 2D drawing.
*/

	// On the S60 and BB Storm, getContext exists, but always returns undefined
	// so we actually have to call getContext() to verify
	// github.com/Modernizr/Modernizr/issues/issue/97/
	Modernizr.addTest('canvas', function() {
		var elem = createElement('canvas');
		return !!(elem.getContext && elem.getContext('2d'));
	});

/*!
{
	"name": "Canvas text",
	"property": "canvastext",
	"caniuse": "canvas-text",
	"tags": ["canvas", "graphics"],
	"polyfills": ["canvastext"]
}
!*/
/* DOC
Detects support for the text APIs for `<canvas>` elements.
*/

	Modernizr.addTest('canvastext',	function() {
		if (Modernizr.canvas	=== false) return false;
		return typeof createElement('canvas').getContext('2d').fillText == 'function';
	});

/*!
{
	"name": "Emoji",
	"property": "emoji"
}
!*/
/* DOC
Detects support for emoji character sets.
*/

	Modernizr.addTest('emoji', function() {
		if (!Modernizr.canvastext) return false;
		var pixelRatio = window.devicePixelRatio || 1;
		var offset = 12 * pixelRatio;
		var node = createElement('canvas');
		var ctx = node.getContext('2d');
		ctx.fillStyle = '#f00';
		ctx.textBaseline = 'top';
		ctx.font = '32px Arial';
		ctx.fillText('\ud83d\udc28', 0, 0); // U+1F428 KOALA
		return ctx.getImageData(offset, offset, 1, 1).data[0] !== 0;
	});


	function getBody() {
		// After page load injecting a fake body doesn't work so check if body exists
		var body = document.body;

		if(!body) {
			// Can't use the real body create a fake one.
			body = createElement('body');
			body.fake = true;
		}

		return body;
	}

	;

	// Inject element with style element and some CSS rules
	function injectElementWithStyles( rule, callback, nodes, testnames ) {
		var mod = 'modernizr';
		var style;
		var ret;
		var node;
		var docOverflow;
		var div = createElement('div');
		var body = getBody();

		if ( parseInt(nodes, 10) ) {
			// In order not to give false positives we create a node for each test
			// This also allows the method to scale for unspecified uses
			while ( nodes-- ) {
				node = createElement('div');
				node.id = testnames ? testnames[nodes] : mod + (nodes + 1);
				div.appendChild(node);
			}
		}

		// <style> elements in IE6-9 are considered 'NoScope' elements and therefore will be removed
		// when injected with innerHTML. To get around this you need to prepend the 'NoScope' element
		// with a 'scoped' element, in our case the soft-hyphen entity as it won't mess with our measurements.
		// msdn.microsoft.com/en-us/library/ms533897%28VS.85%29.aspx
		// Documents served as xml will throw if using &shy; so use xml friendly encoded version. See issue #277
		style = ['&#173;','<style id="s', mod, '">', rule, '</style>'].join('');
		div.id = mod;
		// IE6 will false positive on some tests due to the style element inside the test div somehow interfering offsetHeight, so insert it into body or fakebody.
		// Opera will act all quirky when injecting elements in documentElement when page is served as xml, needs fakebody too. #270
		(!body.fake ? div : body).innerHTML += style;
		body.appendChild(div);
		if ( body.fake ) {
			//avoid crashing IE8, if background image is used
			body.style.background = '';
			//Safari 5.13/5.1.4 OSX stops loading if ::-webkit-scrollbar is used and scrollbars are visible
			body.style.overflow = 'hidden';
			docOverflow = docElement.style.overflow;
			docElement.style.overflow = 'hidden';
			docElement.appendChild(body);
		}

		ret = callback(div, rule);
		// If this is done after page load we don't want to remove the body so check if body exists
		if ( body.fake ) {
			body.parentNode.removeChild(body);
			docElement.style.overflow = docOverflow;
			// Trigger layout so kinetic scrolling isn't disabled in iOS6+
			docElement.offsetHeight;
		} else {
			div.parentNode.removeChild(div);
		}

		return !!ret;

	}

	;

	var testStyles = ModernizrProto.testStyles = injectElementWithStyles;
	
/*!
{
	"name": "@font-face",
	"property": "fontface",
	"authors": ["Diego Perini", "Mat Marquis"],
	"tags": ["css"],
	"knownBugs": [
		"False Positive: WebOS http://github.com/Modernizr/Modernizr/issues/342",
		"False Postive: WP7 http://github.com/Modernizr/Modernizr/issues/538"
	],
	"notes": [{
		"name": "@font-face detection routine by Diego Perini",
		"href": "http://javascript.nwbox.com/CSSSupport/"
	},{
		"name": "Filament Group @font-face compatibility research",
		"href": "https://docs.google.com/presentation/d/1n4NyG4uPRjAA8zn_pSQ_Ket0RhcWC6QlZ6LMjKeECo0/edit#slide=id.p"
	},{
		"name": "Filament Grunticon/@font-face device testing results",
		"href": "https://docs.google.com/spreadsheet/ccc?key=0Ag5_yGvxpINRdHFYeUJPNnZMWUZKR2ItMEpRTXZPdUE#gid=0"
	},{
		"name": "CSS fonts on Android",
		"href": "http://stackoverflow.com/questions/3200069/css-fonts-on-android"
	},{
		"name": "@font-face and Android",
		"href": "http://archivist.incutio.com/viewlist/css-discuss/115960"
	}]
}
!*/

	var blacklist = (function() {
		var ua = navigator.userAgent;
		var wkvers = ua.match( /applewebkit\/([0-9]+)/gi ) && parseFloat( RegExp.$1 );
		var webos = ua.match( /w(eb)?osbrowser/gi );
		var wppre8 = ua.match( /windows phone/gi ) && ua.match( /iemobile\/([0-9])+/gi ) && parseFloat( RegExp.$1 ) >= 9;
		var oldandroid = wkvers < 533 && ua.match( /android/gi );
		return webos || oldandroid || wppre8;
	}());
	if( blacklist ) {
		Modernizr.addTest('fontface', false);
	} else {
		testStyles('@font-face {font-family:"font";src:url("https://")}', function( node, rule ) {
			var style = document.getElementById('smodernizr');
			var sheet = style.sheet || style.styleSheet;
			var cssText = sheet ? (sheet.cssRules && sheet.cssRules[0] ? sheet.cssRules[0].cssText : sheet.cssText || '') : '';
			var bool = /src/i.test(cssText) && cssText.indexOf(rule.split(' ')[0]) === 0;
			Modernizr.addTest('fontface', bool);
		});
	}
;
/*!
{
	"name": "Touch Events",
	"property": "touchevents",
	"caniuse" : "touch",
	"tags": ["media", "attribute"],
	"notes": [{
		"name": "Touch Events spec",
		"href": "http://www.w3.org/TR/2013/WD-touch-events-20130124/"
	}],
	"warnings": [
		"Indicates if the browser supports the Touch Events spec, and does not necessarily reflect a touchscreen device"
	],
	"knownBugs": [
		"False-positive on some configurations of Nokia N900",
		"False-positive on some BlackBerry 6.0 builds – https://github.com/Modernizr/Modernizr/issues/372#issuecomment-3112695"
	]
}
!*/
/* DOC
Indicates if the browser supports the W3C Touch Events API.

This *does not* necessarily reflect a touchscreen device:

* Older touchscreen devices only emulate mouse events
* Modern IE touch devices implement the Pointer Events API instead: use `Modernizr.pointerevents` to detect support for that
* Some browsers & OS setups may enable touch APIs when no touchscreen is connected
* Future browsers may implement other event models for touch interactions

See this article: [You Can't Detect A Touchscreen](http://www.stucox.com/blog/you-cant-detect-a-touchscreen/).

It's recommended to bind both mouse and touch/pointer events simultaneously – see [this HTML5 Rocks tutorial](http://www.html5rocks.com/en/mobile/touchandmouse/).

This test will also return `true` for Firefox 4 Multitouch support.
*/

	// Chrome (desktop) used to lie about its support on this, but that has since been rectified: http://crbug.com/36415
	Modernizr.addTest('touchevents', function() {
		var bool;
		if(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
			bool = true;
		} else {
			var query = ['@media (',prefixes.join('touch-enabled),('),'heartz',')','{#modernizr{top:9px;position:absolute}}'].join('');
			testStyles(query, function( node ) {
				bool = node.offsetTop === 9;
			});
		}
		return bool;
	});


	// Following spec is to expose vendor-specific style properties as:
	//	 elem.style.WebkitBorderRadius
	// and the following would be incorrect:
	//	 elem.style.webkitBorderRadius

	// Webkit ghosts their properties in lowercase but Opera & Moz do not.
	// Microsoft uses a lowercase `ms` instead of the correct `Ms` in IE8+
	//	 erik.eae.net/archives/2008/03/10/21.48.10/

	// More here: github.com/Modernizr/Modernizr/issues/issue/21
	var omPrefixes = 'Moz O ms Webkit';
	

	var cssomPrefixes = (ModernizrProto._config.usePrefixes ? omPrefixes.split(' ') : []);
	ModernizrProto._cssomPrefixes = cssomPrefixes;
	

	var domPrefixes = (ModernizrProto._config.usePrefixes ? omPrefixes.toLowerCase().split(' ') : []);
	ModernizrProto._domPrefixes = domPrefixes;
	

	/**
	 * contains returns a boolean for if substr is found within str.
	 */
	function contains( str, substr ) {
		return !!~('' + str).indexOf(substr);
	}

	;

	// Helper function for converting kebab-case to camelCase,
	// e.g. box-sizing -> boxSizing
	function cssToDOM( name ) {
		return name.replace(/([a-z])-([a-z])/g, function(str, m1, m2) {
			return m1 + m2.toUpperCase();
		}).replace(/^-/, '');
	}
	;

	// Change the function's scope.
	function fnBind(fn, that) {
		return function() {
			return fn.apply(that, arguments);
		};
	}

	;

	/**
	 * testDOMProps is a generic DOM property test; if a browser supports
	 *	 a certain property, it won't return undefined for it.
	 */
	function testDOMProps( props, obj, elem ) {
		var item;

		for ( var i in props ) {
			if ( props[i] in obj ) {

				// return the property name as a string
				if (elem === false) return props[i];

				item = obj[props[i]];

				// let's bind a function
				if (is(item, 'function')) {
					// bind to obj unless overriden
					return fnBind(item, elem || obj);
				}

				// return the unbound function or obj or value
				return item;
			}
		}
		return false;
	}

	;

	/**
	 * Create our "modernizr" element that we do most feature tests on.
	 */
	var modElem = {
		elem : createElement('modernizr')
	};

	// Clean up this element
	Modernizr._q.push(function() {
		delete modElem.elem;
	});

	

	var mStyle = {
		style : modElem.elem.style
	};

	// kill ref for gc, must happen before
	// mod.elem is removed, so we unshift on to
	// the front of the queue.
	Modernizr._q.unshift(function() {
		delete mStyle.style;
	});

	

	// Helper function for converting camelCase to kebab-case,
	// e.g. boxSizing -> box-sizing
	function domToCSS( name ) {
		return name.replace(/([A-Z])/g, function(str, m1) {
			return '-' + m1.toLowerCase();
		}).replace(/^ms-/, '-ms-');
	}
	;

	// Function to allow us to use native feature detection functionality if available.
	// Accepts a list of property names and a single value
	// Returns `undefined` if native detection not available
	function nativeTestProps ( props, value ) {
		var i = props.length;
		// Start with the JS API: http://www.w3.org/TR/css3-conditional/#the-css-interface
		if ('CSS' in window && 'supports' in window.CSS) {
			// Try every prefixed variant of the property
			while (i--) {
				if (window.CSS.supports(domToCSS(props[i]), value)) {
					return true;
				}
			}
			return false;
		}
		// Otherwise fall back to at-rule (for Opera 12.x)
		else if ('CSSSupportsRule' in window) {
			// Build a condition string for every prefixed variant
			var conditionText = [];
			while (i--) {
				conditionText.push('(' + domToCSS(props[i]) + ':' + value + ')');
			}
			conditionText = conditionText.join(' or ');
			return injectElementWithStyles('@supports (' + conditionText + ') { #modernizr { position: absolute; } }', function( node ) {
				return getComputedStyle(node, null).position == 'absolute';
			});
		}
		return undefined;
	}
	;

	// testProps is a generic CSS / DOM property test.

	// In testing support for a given CSS property, it's legit to test:
	//		`elem.style[styleName] !== undefined`
	// If the property is supported it will return an empty string,
	// if unsupported it will return undefined.

	// We'll take advantage of this quick test and skip setting a style
	// on our modernizr element, but instead just testing undefined vs
	// empty string.

	// Property names can be provided in either camelCase or kebab-case.

	function testProps( props, prefixed, value, skipValueTest ) {
		skipValueTest = is(skipValueTest, 'undefined') ? false : skipValueTest;

		// Try native detect first
		if (!is(value, 'undefined')) {
			var result = nativeTestProps(props, value);
			if(!is(result, 'undefined')) {
				return result;
			}
		}

		// Otherwise do it properly
		var afterInit, i, propsLength, prop, before;

		// If we don't have a style element, that means
		// we're running async or after the core tests,
		// so we'll need to create our own elements to use
		if ( !mStyle.style ) {
			afterInit = true;
			mStyle.modElem = createElement('modernizr');
			mStyle.style = mStyle.modElem.style;
		}

		// Delete the objects if we
		// we created them.
		function cleanElems() {
			if (afterInit) {
				delete mStyle.style;
				delete mStyle.modElem;
			}
		}

		propsLength = props.length;
		for ( i = 0; i < propsLength; i++ ) {
			prop = props[i];
			before = mStyle.style[prop];

			if (contains(prop, '-')) {
				prop = cssToDOM(prop);
			}

			if ( mStyle.style[prop] !== undefined ) {

				// If value to test has been passed in, do a set-and-check test.
				// 0 (integer) is a valid property value, so check that `value` isn't
				// undefined, rather than just checking it's truthy.
				if (!skipValueTest && !is(value, 'undefined')) {

					// Needs a try catch block because of old IE. This is slow, but will
					// be avoided in most cases because `skipValueTest` will be used.
					try {
						mStyle.style[prop] = value;
					} catch (e) {}

					// If the property value has changed, we assume the value used is
					// supported. If `value` is empty string, it'll fail here (because
					// it hasn't changed), which matches how browsers have implemented
					// CSS.supports()
					if (mStyle.style[prop] != before) {
						cleanElems();
						return prefixed == 'pfx' ? prop : true;
					}
				}
				// Otherwise just return true, or the property name if this is a
				// `prefixed()` call
				else {
					cleanElems();
					return prefixed == 'pfx' ? prop : true;
				}
			}
		}
		cleanElems();
		return false;
	}

	;

	/**
	 * testPropsAll tests a list of DOM properties we want to check against.
	 *		 We specify literally ALL possible (known and/or likely) properties on
	 *		 the element including the non-vendor prefixed one, for forward-
	 *		 compatibility.
	 */
	function testPropsAll( prop, prefixed, elem, value, skipValueTest ) {

		var ucProp = prop.charAt(0).toUpperCase() + prop.slice(1),
		props = (prop + ' ' + cssomPrefixes.join(ucProp + ' ') + ucProp).split(' ');

		// did they call .prefixed('boxSizing') or are we just testing a prop?
		if(is(prefixed, 'string') || is(prefixed, 'undefined')) {
			return testProps(props, prefixed, value, skipValueTest);

			// otherwise, they called .prefixed('requestAnimationFrame', window[, elem])
		} else {
			props = (prop + ' ' + (domPrefixes).join(ucProp + ' ') + ucProp).split(' ');
			return testDOMProps(props, prefixed, elem);
		}
	}

	// Modernizr.testAllProps() investigates whether a given style property,
	//		 or any of its vendor-prefixed variants, is recognized
	// Note that the property names must be provided in the camelCase variant.
	// Modernizr.testAllProps('boxSizing')
	ModernizrProto.testAllProps = testPropsAll;

	

	/**
	 * testAllProps determines whether a given CSS property, in some prefixed
	 * form, is supported by the browser. It can optionally be given a value; in
	 * which case testAllProps will only return true if the browser supports that
	 * value for the named property; this latter case will use native detection
	 * (via window.CSS.supports) if available. A boolean can be passed as a 3rd
	 * parameter to skip the value check when native detection isn't available,
	 * to improve performance when simply testing for support of a property.
	 *
	 * @param prop - String naming the property to test (either camelCase or
	 *							 kebab-case)
	 * @param value - [optional] String of the value to test
	 * @param skipValueTest - [optional] Whether to skip testing that the value
	 *												is supported when using non-native detection
	 *												(default: false)
	 */
	function testAllProps (prop, value, skipValueTest) {
		return testPropsAll(prop, undefined, undefined, value, skipValueTest);
	}
	ModernizrProto.testAllProps = testAllProps;
	
/*!
{
	"name": "Border Radius",
	"property": "borderradius",
	"caniuse": "border-radius",
	"polyfills": ["css3pie"],
	"tags": ["css"],
	"notes": [{
		"name": "Comprehensive Compat Chart",
		"href": "http://muddledramblings.com/table-of-css3-border-radius-compliance"
	}]
}
!*/

	Modernizr.addTest('borderradius', testAllProps('borderRadius', '0px', true));

/*!
{
	"name": "CSS Animations",
	"property": "cssanimations",
	"caniuse": "css-animation",
	"polyfills": ["transformie", "csssandpaper"],
	"tags": ["css"],
	"warnings": ["Android < 4 will pass this test, but can only animate a single property at a time"],
	"notes": [{
		"name" : "Article: 'Dispelling the Android CSS animation myths'",
		"href": "http://goo.gl/OGw5Gm"
	}]
}
!*/
/* DOC
Detects whether or not elements can be animated using CSS
*/

	Modernizr.addTest('cssanimations', testAllProps('animationName', 'a', true));

/*!
{
	"name": "CSS Transforms",
	"property": "csstransforms",
	"caniuse": "transforms2d",
	"tags": ["css"]
}
!*/

	Modernizr.addTest('csstransforms', function() {
		// Android < 3.0 is buggy, so we sniff and blacklist
		// http://git.io/hHzL7w
		return navigator.userAgent.indexOf('Android 2.') === -1 &&
					 testAllProps('transform', 'scale(1)', true);
	});

/*!
{
	"name": "CSS Transforms 3D",
	"property": "csstransforms3d",
	"caniuse": "transforms3d",
	"tags": ["css"],
	"warnings": [
		"Chrome may occassionally fail this test on some systems; more info: https://code.google.com/p/chromium/issues/detail?id=129004"
	]
}
!*/

	Modernizr.addTest('csstransforms3d', function() {
		var ret = !!testAllProps('perspective', '1px', true);
		var usePrefix = Modernizr._config.usePrefixes;

		// Webkit's 3D transforms are passed off to the browser's own graphics renderer.
		//	 It works fine in Safari on Leopard and Snow Leopard, but not in Chrome in
		//	 some conditions. As a result, Webkit typically recognizes the syntax but
		//	 will sometimes throw a false positive, thus we must do a more thorough check:
		if ( ret && (!usePrefix || 'webkitPerspective' in docElement.style )) {
			var mq;
			// Use CSS Conditional Rules if available
			if (Modernizr.supports) {
				mq = '@supports (perspective: 1px)';
			} else {
				// Otherwise, Webkit allows this media query to succeed only if the feature is enabled.
				// `@media (transform-3d),(-webkit-transform-3d){ ... }`
				mq = '@media (transform-3d)';
				if (usePrefix ) mq += ',(-webkit-transform-3d)';
			}
			// If loaded inside the body tag and the test element inherits any padding, margin or borders it will fail #740
			mq += '{#modernizr{left:9px;position:absolute;height:5px;margin:0;padding:0;border:0}}';

			testStyles(mq, function( elem ) {
				ret = elem.offsetLeft === 9 && elem.offsetHeight === 5;
			});
		}

		return ret;
	});

/*!
{
	"name": "CSS Transitions",
	"property": "csstransitions",
	"caniuse": "css-transitions",
	"tags": ["css"]
}
!*/

	Modernizr.addTest('csstransitions', testAllProps('transition', 'all', true));

/*!
{
	"name": "Flexbox",
	"property": "flexbox",
	"caniuse": "flexbox",
	"tags": ["css"],
	"notes": [{
		"name": "The _new_ flexbox",
		"href": "http://dev.w3.org/csswg/css3-flexbox"
	}],
	"warnings": [
		"A `true` result for this detect does not imply that the `flex-wrap` property is supported; see the `flexwrap` detect."
	]
}
!*/
/* DOC
Detects support for the Flexible Box Layout model, a.k.a. Flexbox, which allows easy manipulation of layout order and sizing within a container.
*/

	Modernizr.addTest('flexbox', testAllProps('flexBasis', '1px', true));

/*!
{
	"name": "Flexbox (legacy)",
	"property": "flexboxlegacy",
	"tags": ["css"],
	"polyfills": ["flexie"],
	"notes": [{
		"name": "The _old_ flexbox",
		"href": "http://www.w3.org/TR/2009/WD-css3-flexbox-20090723/"
	}]
}
!*/

	Modernizr.addTest('flexboxlegacy', testAllProps('boxDirection', 'reverse', true));


	// Run each test
	testRunner();

	// Remove the "no-js" class if it exists
	setClasses(classes);

	delete ModernizrProto.addTest;
	delete ModernizrProto.addAsyncTest;

	// Run the things that are supposed to run after the tests
	for (var i = 0; i < Modernizr._q.length; i++) {
		Modernizr._q[i]();
	}

	// Leak Modernizr namespace
	window.Modernizr = Modernizr;


;

})(window, document);










// #######################################################################################
// #																					 #
// #									Post Pagination									 #
// #																					 #
// #######################################################################################

var audentio;
if (audentio === undefined) audentio = {};

audentio.pagination = {
	enabled: false,
	id: "audentio_postPagination",
	displaySize: 800, // minimum display width in px to show on
	scrollDuration: 300, // duration of scroll to in ms
	sizeValid: false,
	needsInit: true,
	running: false,
	numPosts: 0,
	currentPost: 0,
	parent: null,
	threads: null,
	posts: [],
	uixSticky: true,
	input: null,
	dropdown: null,
	ele: null,
	scrollInterval: null,
	target: -1,
	lastPost: -1,
	listenersAdded: false,
	nextPage: "",
	prevPage: "",
	keyFn: function(event) {
		audentio.pagination.keyEvent(event)
	},
	offset: 0,
	parentEle: 'navigation',
	container: null,
	containerParent: null,
	containerRemoved: 0,
	popupMenu: null,
	useDropdown: true,
	outOfPhrase: "Out of",
	enterIndexPhrase: "Enter Index",
	additionalPages: false,


	init: function() {
		var ap = audentio.pagination, d = document;

		if (uix !== undefined && uix.maxResponsiveWideWidth !== undefined) audentio.pagination.displaySize = uix.maxResponsiveWideWidth;
		if (ap.id != "") {
			var pfTime = uix.time();
			var parent = d.getElementById(ap.id);
			audentio.pagination.parent = parent;
			threads = d.getElementById('messageList');
			audentio.pagination.threads = threads;
			if (parent !== null && threads !== null && ap.needsInit) {
				audentio.pagination.enabled = true;
				var windowWidth = window.innerWidth;

				var links = d.getElementsByTagName("LINK");
				for (var i = 0, len = links.length; i < len; i++) {
					if (links[i].rel == "prev") {
						audentio.pagination.prevPage = links[i].href;
						audentio.pagination.additionalPages = true;
					}
					if (links[i].rel == "next") {
						audentio.pagination.nextPage = links[i].href;
						audentio.pagination.additionalPages = true;
					}
				}

				ap.updatePosts();

				if ((windowWidth > ap.displaySize) && (ap.numPosts > 1 || ap.additionalPages)) {

					if (windowWidth < 1025) audentio.pagination.useDropdown = false; // disable dropdown for tablet and mobile

					content = '<a href="javascript: void(0)" onclick="audentio.pagination.scrollToPost(0)"><i class="fa fa-angle-double-up pointer fa-fw pagetop"></i></a>';
					content += '<a href="javascript: void(0)" onclick="audentio.pagination.prevPost()"><i class="fa fa-angle-up pointer fa-fw pageup"></i></a>';
					
					content += '<a href="javascript:void(0)" class="uix_paginationMenu">';
					content += '	<span>' + ap.outOfPhrase + '</span>';
					content += '</a>';

					content += '<a href="javascript: void(0)" onclick="audentio.pagination.nextPost()"><i class="fa fa-angle-down pointer fa-fw pagedown"></i></a>';
					content += '<a href="javascript: void(0)" onclick="audentio.pagination.scrollToPost(' + (ap.numPosts - 1) + ')"><i class="fa fa-angle-double-down pointer fa-fw pagebottom"></i></a>';
					content += '<div class="progress-container">';
					content += '	<div class="progress-bar" id="audentio_postPaginationBar"></div>';
					content += '</div>';
					if (ap.useDropdown) {
						content += '	<div class="Menu" id="audentio_postPaginationDropdown">';
						content += '		<div class="primaryContent">';
						content += '			<input type="text" id="audentio_postPaginationInput" class="textCtrl" placeholder="' + ap.enterIndexPhrase + '">';
						content += '		</div>';
						content += '	</div>';
					}


					var paginator = d.createElement("div");
					paginator.className = "navLink";
					paginator.setAttribute('rel', 'Menu')
					paginator.innerHTML = content;
					parent.appendChild(paginator);

					if (ap.useDropdown) {
						audentio.pagination.popupMenu = new XenForo.PopupMenu($(parent))
					}

					if (!uix.initDone) {
						uix.updateNavTabs = true;
					} else {
						uix.navTabs();
					}

					audentio.pagination.ele = paginator;
					ap.updateTotalPost(ap.numPosts)
					ap.updateCurrentPost();

					if (ap.useDropdown) {
						audentio.pagination.input = d.getElementById("audentio_postPaginationInput");
						audentio.pagination.dropdown = d.getElementById("audentio_postPaginationDropdown");
					}

					d.addEventListener("scroll", function(event) {
						ap.updateCurrentPost();
					});
					ap.updateBar()
					audentio.pagination.running = true;
					audentio.pagination.needsInit = false;

					window.setTimeout(function() {
						window.requestAnimationFrame(function(){
							ap.update()
							audentio.pagination.lastPost = ap.currentPost;
						});
					}, 100); // allow page to render

					ap.checkSize();


					var us = uix.sidebarSticky,
						d = document,
						B = d.body,
						H = d.documentElement,
						docHeight = d.height;

					window.setInterval(function(){
						if (ap.running && uix.initDone) {
							if (typeof docHeight !== 'undefined') {
								var documentHeight = document.height // For webkit browsers
							} else {
								var documentHeight = Math.max( B.scrollHeight, B.offsetHeight,H.clientHeight, H.scrollHeight, H.offsetHeight );
							}

							if (documentHeight != uix.documentHeight) {
								uix.documentHeight = documentHeight;
								ap.update();
							}
						}
					}, 300);
				}
			} else {
				audentio.pagination.listenersAdded = true; //not in a threadList, don't add resize listeners
			}

			if (ap.listenersAdded == false) {
				if (ap.useDropdown) {
					if (ap.running) ap.input.addEventListener('keypress', ap.keyFn);
				}

				audentio.pagination.listenersAdded = true;
			}
		}
		uix.tStamp("Pagination Init", 2, pfTime);
	},

	update: function() {
		var ap = audentio.pagination,
			pfTime = uix.time();
		ap.checkSize();
		if (ap.sizeValid) {
			if (ap.needsInit) {
				ap.init(ap.id);
			} else {
				ap.updatePosts();
				ap.updateCurrentPost();
			}
		}
		uix.tStamp("Pagination Update", 2, pfTime);
	},

	checkSize: function() {
		var ap = audentio.pagination, d = document;

		if (window.innerWidth > ap.displaySize) {
			audentio.pagination.sizeValid = true;
			if (ap.containerRemoved == 1 && ap.containerParent !== null && ap.container !== null) {
				ap.containerParent.appendChild(ap.container);
				if (!uix.initDone) {
					uix.updateNavTabs = true;
				} else {
					uix.navTabs();
				}
				audentio.pagination.containerRemoved = 0;
			}

			if (ap.running) {
				ap.ele.style.display = "block";
				if (d.getElementById(ap.parentEle) !== null) {
					var className = d.getElementById(ap.parentEle).className;
					if (className.indexOf("activePagination") == -1) {
						className += ' activePagination';
						d.getElementById(ap.parentEle).className = className;
					}
				}
			} else {
				if (ap.needsInit) ap.init();
			}
		} else {
			audentio.pagination.sizeValid = false;
			if (ap.running) {
				ap.ele.style.display = "none";
				if (d.getElementById(ap.parentEle) !== null) {
					var className = d.getElementById(ap.parentEle).className;
					if (className.indexOf("activePagination") > -1) {
						className = className.replace('activePagination', '');
						d.getElementById(ap.parentEle).className = className;
					}
				}
			}

			if (ap.containerRemoved == 0) {
				if (d.getElementById('audentio_postPagination') !== null) {
					//audentio.pagination.containerParent = d.getElementById('audentio_postPagination').parentNode;
					//audentio.pagination.container = ap.containerParent.removeChild(d.getElementById('audentio_postPagination'));
					if (!uix.initDone) {
						uix.updateNavTabs = true;
					} else {
						uix.navTabs();
					}
					audentio.pagination.containerRemoved = 1;
				}
			}
		}
	},

	keyEvent: function(event) {
		var key = event.which || event.keyCode, ap = audentio.pagination, d = document;
		if (key == 13) { // 13 is enter
			if (ap.useDropdown) {
				var index = parseInt(ap.input.value);
				if (index < 1) index = 1;
				if (index > ap.numPosts) index = ap.numPosts;
				audentio.pagination.input.value = "";
				ap.scrollToPost(index - 1);
				d.getElementById('audentio_postPaginationDropdown').style.display = "none";
			}
		} else if (key == 37) { // left arrow
			//ap.prevPost();
		} else if (key == 39) { // right arrow
			//ap.nextPost();
		}
	},

	getOffset: function(num) {
		var ap = audentio.pagination;

		if (num - ap.currentPost > 0) { // scrolling down
			if (ap.uixSticky) {
				return uix.sticky.downStickyHeight + ap.offset;
			}
		} else {
			if (ap.uixSticky) return uix.sticky.fullStickyHeight + ap.offset;
		}
		return 0;
	},

	prevPost: function() {
		var ap = audentio.pagination;

		if (ap.target != -1) {
			window.scrollTo(0, ap.target);
			ap.updateCurrentPost();
		}
		var target = ap.currentPost - 1;
		if (target < 0) {
			if (ap.prevPage != "") window.location.href = ap.prevPage;
			//target = 0;
		} else {
			if (ap.currentPost > 0) ap.scrollToPost(target);
		}

		return false;
	},

	nextPost: function() {
		var ap = audentio.pagination;

		if (ap.target != -1) {
			window.scrollTo(0, ap.target);
			ap.updateCurrentPost();
		}
		var target = ap.currentPost + 1;
		if (target >= ap.numPosts) {
			if (ap.nextPage != "") window.location.href = ap.nextPage;
		} else {
			ap.scrollToPost(target);
		}
		return false;
	},

	scrollToPost: function(num) {
		uix.hideDropdowns.hide();

		var target = 0, ap = audentio.pagination, d = document, w = window;
		if (num >= ap.numPosts) {
			target = d.body.getBoundingClientRect().height;
		} else if (num >= 0) {
			target = ap.posts[num] - ap.getOffset(num);
			if (target !== target) target = ap.posts[ap.posts.length - 1] - ap.getOffset(num);
		}
		audentio.pagination.target = target;
		var startY = w.scrollY,
			numSteps = Math.ceil(ap.scrollDuration / 15),
			scrollStep = (startY - target) / numSteps,
			scrollCount = 0;

		clearInterval(ap.scrollInterval);

		if (ap.lastPost == num) {
			if (num <= 0 && ap.prevPage != "") w.location.href = ap.prevPage;
			if (num >= (ap.numPosts - 1) && ap.nextPage != "") w.location.href = ap.nextPage;
		} else {
			audentio.pagination.scrollInterval = setInterval(function() {
				if (scrollCount < numSteps && ap.target != -1) {
					scrollCount += 1;
					w.scrollTo(0, (startY - (scrollStep * scrollCount)));
				} else {
					clearInterval(ap.scrollInterval);
					w.scrollTo(0, target);
					audentio.pagination.target = -1;
				}
			}, 16);
		}
		audentio.pagination.lastPost = num;
		return false;
	},

	updateNumPosts: function() {
		var posts = audentio.pagination.threads.getElementsByClassName('message')
		var count = 0;
		for (var i = 0, len = posts.length; i < len; i++){
			if (posts[i].className.indexOf("deleted") < 0) count = count + 1;
		}

		audentio.pagination.numPosts = count;
	},

	updateCurrentPost: function() {
		var d = document, w = window, ap = audentio.pagination,
			scrollTop = w.scrollY || d.documentElement.scrollTop,
			currentPost = 0
		
		scrollTop += ap.getOffset() - 20;

		for (var i = 0; i < ap.numPosts - 1; i++) {
			if (scrollTop >= ap.posts[i]) {
				currentPost = i + 1;
			} else {
				break;
			}
		}
		if (d.getElementById('audentio_postPaginationCurrent') !== null) {
			d.getElementById('audentio_postPaginationCurrent').innerHTML = currentPost + 1;
			if (currentPost != ap.currentPost) {
				audentio.pagination.currentPost = currentPost;
				ap.updateBar();
				audentio.pagination.lastPost = currentPost;
			}
		}
	},

	updateTotalPost: function(value) {
		var d = document;
		if (d.getElementById('audentio_postPaginationTotal') !== null) {
			d.getElementById('audentio_postPaginationTotal').innerHTML = value;
		}
	},

	updatePosts: function() {
		var ap = audentio.pagination;

		ap.updateNumPosts();
		var postCount = 0,
			posts = ap.threads.getElementsByClassName('message');
		for (var i = 0, len = posts.length; i < len; i++) {
			var post = posts[i];
			if (post.className.indexOf("deleted") < 0) {
				audentio.pagination.posts[postCount] = post.offsetTop
				postCount = postCount + 1;
			}
		}
	},

	updateBar: function() {
		var ap = audentio.pagination;

		document.getElementById('audentio_postPaginationBar').style.width = ((ap.currentPost + 1) / ap.numPosts) * 100 + "%"
	}
}


// #######################################################################################
// #																					 #
// #									Grid Layout										 #
// #																					 #
// #######################################################################################

audentio.grid = {
	running: false,
	listenersAdded: false,
	minEnableWidth: 1,
	layout: {},
	eles: [],
	equalCategories: true,
	classAdded: false,
	alwaysCheck: true,
	nextSubnodeIds: [],
	scriptTags: [],
	layoutNodeIds: [],
	debug: "",

	parse: function(layoutOrig) {
		audentio.grid.debug = layoutOrig;

		var ag = audentio.grid,
			result = {
				global: {
					root: {
						numCol: 6,
						fillLast: 0,
						minWidth: 300,
					}
				}
			},
			layout = JSON.parse(layoutOrig.split('&quot;').join('"')),
			layoutKeys = Object.keys(layout);

		for (var i = 0, len = ag.scriptTags.length; i < len; i++) {
			audentio.grid.nextSubnodeIds.push(ag.getNodeId(ag.scriptTags[i].nextElementSibling));
		}

		for (var keyNum = 0, keyLen = layoutKeys.length; keyNum < keyLen; keyNum++) {
			var key = layoutKeys[keyNum],
				tempKey = (layoutKeys[keyNum] == 'category') ? 0 : layoutKeys[keyNum],
				nextSubnodeId = 0,
				subNodeIndex = 0;

			for (var i = 0, len = ag.layoutNodeIds.length; i < len; i++) {
				if (ag.layoutNodeIds[i] == tempKey) {
					nextSubnodeId = ag.nextSubnodeIds[i];
					subNodeIndex = i;
					break;
				}
			}

			if (nextSubnodeId == 0) { // root node
				var keyName = 'node_' + tempKey,
					keyEle = layout[key];
				if (key == 'default') keyName = 'global';

				if (typeof(result[keyName]) === 'undefined') result[keyName] = {}
				if (typeof(result[keyName].root) === 'undefined') result[keyName].root = {};
				if (keyEle.fill_last_row !== undefined && parseInt(keyEle.fill_last_row.value) >= 0) result[keyName].root.fillLast = parseInt(keyEle.fill_last_row.value);
				if (keyEle.minimum_column_width !== undefined && parseInt(keyEle.minimum_column_width.value) >= 0) result[keyName].root.minWidth = parseInt(keyEle.minimum_column_width.value);
				if (keyEle.maximum_columns !== undefined && parseInt(keyEle.maximum_columns.value) >= 0) result[keyName].root.numCol = parseInt(keyEle.maximum_columns.value);
				if (keyEle.column_widths !== undefined && keyEle.column_widths.value == 1) {
					if (keyEle.custom_column_widths !== undefined && keyEle.custom_column_widths.layouts !== undefined) {
						var colEles = keyEle.custom_column_widths.layouts,
							colKeys = Object.keys(colEles);

						for (var colNum = 0, colLen = colKeys.length; colNum < colLen; colNum++){
							var col = colKeys[colNum],
								valEles = colEles[col],
								valKeys = Object.keys(velEles);

							result[keyName].root['col_' + col] = {};

							for (var valNum = 0, valLen = valKeys.length; valNum < valLen; valNum++) {
								var val = valKeys[valNum];

								var colWidth = parseInt(valEles[val]);
								if (colWidth < 1 || colWidth !== colWidth) colWidth = 1;
								result[keyName].root['col_' + col]['col' + val] = colWidth;
							}
						}
					}
				}
				if (typeof(result[keyName].listeners) === "undefined") {
					result[keyName].listeners = [];
				}
			} else { // subNode
				var parentNodeId = ag.getNodeId(ag.scriptTags[subNodeIndex].parentElement.parentElement);
				if (parentNodeId == -1) parentNodeId = 0;

				var keyName = 'node_' + parentNodeId,
					subKeyName = 'node_' + nextSubnodeId,
					keyEle = layout[key];

				if (typeof(result[keyName]) === 'undefined') result[keyName] = {};
				if (typeof(result[keyName].root) === 'undefined') result[keyName].root = {
					fillLast: parseInt(layout.default.fill_last_row.value),
					minWidth: parseInt(layout.default.minimum_column_width.value),
					numCol: parseInt(layout.default.maximum_columns.value),
				};
				if (typeof(result[keyName][subKeyName]) === 'undefined') {
					result[keyName][subKeyName] = {};
					if (typeof(result[keyName].subLayout) === 'undefined') result[keyName].subLayout = {};
					result[keyName].subLayout[subKeyName] = subKeyName;
					if (keyEle.fill_last_row !== undefined && parseInt(keyEle.fill_last_row.value) >= 0) result[keyName][subKeyName].fillLast = parseInt(keyEle.fill_last_row.value);
					if (keyEle.minimum_column_width !== undefined && parseInt(keyEle.minimum_column_width.value) >= 0) result[keyName][subKeyName].minWidth = parseInt(keyEle.minimum_column_width.value);
					if (keyEle.maximum_columns !== undefined && parseInt(keyEle.maximum_columns.value) >= 0) result[keyName][subKeyName].numCol = parseInt(keyEle.maximum_columns.value);
					if (keyEle.column_widths !== undefined && keyEle.column_widths.value == 1) {
						if (keyEle.custom_column_widths !== undefined && keyEle.custom_column_widths.layouts !== undefined) {
							var colEles = keyEle.custom_column_widths.layouts,
								colKeys = Object.keys(colEles);

							for (var colNum = 0, colLen = colKeys.length; colNum < colLen; colNum++){
								var col = colKeys[colNum],
									valEles = colEles[col],
									valKeys = Object.keys(velEles);

								result[keyName][subKeyName]['col_' + col] = {};

								for (var valNum = 0, valLen = valKeys.length; valNum < valLen; valNum++) {
									var val = valKeys[valNum];

									var colWidth = parseInt(valKeys[val]);
									if (colWidth < 1 || colWidth !== colWidth) colWidth = 1;
									result[keyName][subKeyName]['col_' + col]['col' + val] = colWidth;
								}
							}
						}
					}
				}
			}
		}

		audentio.grid.layout = result;

		if (ag.running) ag.update();

		return result;
	},

	addSizeListener: function(node, className, widthMax, widthMin) {
		var ag = audentio.grid;

		if (typeof(ag.layout[node]) === "undefined") {
			audentio.grid.layout[node] = {};
		}
		if (typeof(ag.layout[node].listeners) === "undefined") {
			audentio.grid.layout[node].listeners = [];
		}
		audentio.grid.layout[node].listeners.push([className, widthMax, widthMin]);

		if (ag.running) ag.update();
	},

	addSubNode: function(layoutNodeId) {
		var scriptTag = document.scripts[document.scripts.length - 1];
		audentio.grid.scriptTags.push(scriptTag);
		audentio.grid.layoutNodeIds.push(layoutNodeId);
	},

	init: function(layout, minWidth) {
		var ag = audentio.grid,
			pfTime = uix.time();

		if (layout !== undefined) audentio.grid.layout = layout;
		if (minWidth !== undefined) audentio.grid.minEnableWidth = minWidth;
		if (ag.running == false && window.innerWidth >= ag.minEnableWidth) {
			audentio.grid.running = true;
			var forums = ag.getForums();
			if (forums !== null) {
				if (ag.classAdded == false) {
					forums.className += ' audentio_grid_running';
					audentio.grid.classAdded = true;
				}
				var nodeId = 0,
					bodyClass = document.getElementsByTagName("body")[0].className.split(" ");
					//bodyClass = document.getElementsByTagName("body")[0].className.replace("node1", "").split(" ");

				for (var i = 0, len = bodyClass.length; i < len; i++) {
					if (bodyClass[i].indexOf("node") > -1) {
						var possibleId = bodyClass[i].replace("node", "")
						if (possibleId == parseInt(possibleId)) {
							nodeId = possibleId;
							break;
						}
					}
				}
				ag.checkWidths(); // parse and set column widths
				ag.recurse(forums, "node_" + nodeId); // build ele tree
				ag.update(); // update element widths
			}
		} else {
			var forums = ag.getForums();
		}

		if (forums != null) {
			forums.style.visibility = "visible";
		}

		if (ag.listenersAdded == false) {
			audentio.grid.listenersAdded = true;
		}

		uix.tStamp("Grid Init", 2, pfTime);
	},

	checkWidths: function() {
		var ag = audentio.grid,
			node0Set = false,
			nodeKeys = Object.keys(ag.layout);

		for (var i = 0, lenNode = nodeKeys.length; i < lenNode; i++){
			var node = nodeKeys[i],
				nodeEle = ag.layout[node],
				subKeys = Object.keys(nodeEle);

			for (var j = 0, lenSub = subKeys.length; j < lenSub; j++){
				var subLayout = subKeys[j],
					subEle = nodeEle[subLayout],
					colKeys = Object.keys(subEle);

				if (node == "node_0") {
					node0Set = true;
					if (ag.layout.node_0[subLayout].numCol != 1) audentio.grid.equalCategories = false; // performance improvement, don't need to check all widths
				}
				for (var k = 0, lenCol = colKeys.length; k < lenCol; k++){

					var col = colKeys[k],
						colEle = subEle[col],
						widthKeys = uix.fn.getKeys(colEle),
						totalWidth = 0;

					if (col.indexOf("col_") > -1 && widthKeys !== null) {
						for (var l = 0, len = widthKeys.length; l < len; l++){
							totalWidth += parseInt(colEle[widthKeys[l]])
						}
						if (totalWidth != 100) {
							for (var l = 0, len = widthKeys.length; l < len; l++){
								var width = widthKeys[l];
								ag.layout[node][subLayout][col][width] = (parseInt(colEle[width] / totalWidth) * 100)
							}
						}
					}
				}
			}
		}
		if (node0Set == false) {
			if (ag.layout.global.root.numCol != 1) audentio.grid.equalCategories = false;
		}
	},

	getNodeId: function(ele) {
		var ret = -1;
		if (ele !== null && typeof(ele) !== "undefined") {
			var classList = ele.className.split(" ");
			for (var i = 0, len = classList.length; i < len; i++) {
				if (classList[i].indexOf("node_") > -1 && classList[i].indexOf("audentio") == -1) {
					ret = parseInt(classList[i].replace("node_", ""));
				}
			}
		}
		return ret;
	},

	recurse: function(ele, layoutName) {
		var ag = audentio.grid;
		if (ag.layout[layoutName] === undefined) layoutName = "global";
		var 
			newEle = {
				ele: ele,
				layoutName: layoutName,
				children: [],
				ratio: 1,
				eleID: ag.getNodeId(ele.parentNode),
				subLayouts: [{
					name: 'root',
					count: 0,
					currentCols: 0
				}]
			},
			children = ele.children,
			subLayoutName = "root",
			hasSubLayouts = false,
			numChildren = 0;

		for (var i = 0, len = children.length; i < len; i++) {
			var currentChild = children[i];
			if (currentChild.tagName == "LI") {
				var child = {
						ele: currentChild,
						nodeId: ag.getNodeId(currentChild),
						eleIndex: -1,
						setup: false,
						classes: {},
					},
					subEles = ag.layout[layoutName],
					subKeys = Object.keys(subEles);

				for (var subNum = 0, subLen = subKeys.length; subNum < subLen; subNum++){
					var subLayout = subKeys[subNum];

					if ("node_" + child.nodeId == subLayout) {
						subLayoutName = subLayout;
						newEle.subLayouts.push({
							name: subLayout,
							count: 0,
							currentCols: 0
						});
						hasSubLayouts = true
					}
				}
				newEle.subLayouts[newEle.subLayouts.length - 1].count++;

				child.subLayout = subLayoutName;
				newEle.children.push(child);
				numChildren++;
			}
		}

		if (ag.layout.global.root.numCol > 1 || ag.layout[layoutName].root.numCol > 1 || hasSubLayouts) ag.eles.push(newEle);

		for (var i = 0; i < numChildren; i++) {
			var nodeId = newEle.children[i].nodeId;
			var nextLayoutName = "global";
			if (ag.layout['node_' + nodeId] !== undefined) nextLayoutName = "node_" + nodeId;

			var child = newEle.children[i].ele.getElementsByClassName("nodeList");
			if (child.length > 0) ag.recurse(child[0], nextLayoutName);
		}
	},

	set: function(index) {
		var ag = audentio.grid,
			eleRoot = ag.eles[index],
			ele = eleRoot.ele,
			layoutName = eleRoot.layoutName,
			changeMade = false,
			nodeChildren = eleRoot.children,
			childStart = 0,
			absoluteRowNum = 0,
			absoluteNodeNum = 0,
			subLayoutNum = 0,
			numSubLayouts = eleRoot.subLayouts.length,
			subKeys = Object.keys(eleRoot.subLayouts);

		for (var subNum = 0, lenSub = subKeys.length; subNum < lenSub; subNum++){
			var subLayout = subKeys[subNum];

			subLayoutNum++;
			var fillLast = ag.layout.global.root.fillLast,
				subName = eleRoot.subLayouts[subLayout].name,
				subLayoutVar = {},
				children = [],
				widthSum = 100,
				rowNum = 0;

			if (typeof(ag.layout[layoutName]) !== "undefined" && typeof(ag.layout[layoutName][subName]) !== "undefined") subLayoutVar = ag.layout[layoutName][subName];
			if (typeof(subLayoutVar.fillLast) === "number") fillLast = subLayoutVar.fillLast; // override global

			for (var i = 0, len = nodeChildren.length; i < len; i++) {
				if (subName == nodeChildren[i].subLayout) children.push(nodeChildren[i]);
			}
			var childLen = children.length,
				numCols = ag.findCols(index, childLen, subName);

			if (subLayoutVar.currentCols != numCols || ag.alwaysCheck) {
				changeMade = true;
				var lastFullRow = childLen - (childLen % numCols)

				for (var i = 0; i < childLen; i++) {
					var itemWidth = 100 / numCols,
						lastRowCols = 0;
					if (typeof(ag.layout["global"]["root"]["col_" + numCols]) !== "undefined") itemWidth = ag.layout["global"]["root"]["col_" + numCols]["col" + (i % numCols + 1)];
					if (typeof(subLayoutVar["col_" + numCols]) !== "undefined") itemWidth = subLayoutVar["col_" + numCols]["col" + (i % numCols + 1)];

					if (fillLast == 0) {
						lastRowCols = (childLen % numCols)
						if (i >= lastFullRow) itemWidth = 100 / lastRowCols;
					} else if (fillLast == 2) {
						if (i >= lastFullRow) {
							lastRowCols = ag.findCols(index, (childLen % numCols), subName);
							var itemWidth = 100 / lastRowCols;
							if (typeof(ag.layout["global"]["root"]["col_" + lastRowCols]) !== "undefined") itemWidth = ag.layout["global"]["root"]["col_" + lastRowCols]["col" + ((i - lastFullRow) % lastRowCols + 1)];
							if (typeof(subLayoutVar["col_" + lastRowCols]) !== "undefined") itemWidth = subLayoutVar["col_" + lastRowCols]["col" + ((i - lastFullRow) % lastRowCols + 1)];
						}
					} else if (fillLast == 3) {
						lastRowCols = 1;
						if (i >= lastFullRow) itemWidth = 100
					}

					var leftCol = (widthSum >= 99 || itemWidth == 100) ? true: false,
						rightCol = (i % numCols == numCols - 1 || itemWidth == 100 || (i >= lastFullRow && (i - lastFullRow) % lastRowCols == lastRowCols - 1)) ? true: false,
						maxCols = (lastRowCols > 0) ? lastRowCols : numCols;

					if (widthSum >= 99) {
						widthSum = 0;
						rowNum++;
						absoluteRowNum++;
					}

					ag.setClass(children[i], i % numCols + 1, maxCols, (eleRoot.parentWidth * itemWidth) / 100, rowNum, absoluteRowNum, i + 1, absoluteNodeNum + 1, subLayoutNum, leftCol, rightCol, numSubLayouts, ag.layout[layoutName].listeners);

					var eleChildren = eleRoot.children;
					if (eleRoot.layoutName == "node_0") {
						for (var j = 1, len2 = eleChildren.length; j <= len2; j++) {
							if (children[i].nodeId == eleChildren[j - 1].nodeId) {
								if (typeof(ag.eles[j]) !== "undefined") ag.eles[j].ratio = itemWidth / 100;
							}
						}
					}

					children[i].ele.style.width = itemWidth + "%";
					if (children[i].setup == false) {
						children[i].setup = true;
					}
					widthSum += itemWidth;
					absoluteNodeNum++;
				}
				audentio.grid.eles[index].subLayouts[subLayout].currentCols = numCols;
			}

			childStart++;
		}
		return changeMade;
	},

	setClass: function(child, colNum, maxCol, width, rowNum, rowNumAbs, nodeNum, nodeAbsNum, subLayoutNum, leftCol, rightCol, numSubLayouts, listeners) {
		var ag = audentio.grid,
			prefix = "audentio_grid";

		if (typeof(listeners) === "undefined" || listeners.length == 0) listeners = ag.layout.global.listeners;

		var newClasses = {
			a : prefix,
			b : (width != 100) ? prefix + '_active' : "",
			c : (leftCol) ? prefix + '_left' : "",
			d : (rightCol) ? prefix + '_right' : "",
			e : prefix + '_column_' + colNum,
			f : prefix + '_columnMax_' + maxCol,
			g : prefix + '_rowSublayout_' + rowNum,
			h : prefix + '_row_' + rowNumAbs,
			i : prefix + '_subLayout_' + subLayoutNum,
			j : prefix + '_numSubLayouts_' + numSubLayouts,
			k : prefix + '_nodeSublayout_' + nodeNum,
			l : prefix + '_node_' + nodeAbsNum,
		},
		listenerLen = listeners.length;

		if (listenerLen > 0) {
			for (var i = 0; i < listenerLen; i++) {
				if (width < listeners[i][1] && width >= listeners[i][2]) {
					newClasses[listeners[i][0]] = listeners[i][0];
				} else {
					newClasses[listeners[i][0]] = "";
				}
			}
		}
	
		var eleClasses = child.ele.className,
			changeMade = false;
		for (var i = 0, len = Object.keys(newClasses).length; i < len; i++) {
			var key = Object.keys(newClasses)[i]
			if (newClasses[key] != child.classes[key]) {
				changeMade = true;
				if (typeof(child.classes[key]) === "undefined" || child.classes[key] == "") {
					eleClasses += " " + newClasses[key];
					eleClasses = eleClasses.replace("	", " ");
				} else {
					eleClasses = eleClasses.replace(child.classes[key], newClasses[key]);
					eleClasses = eleClasses.replace("	", " ");
				}
			}
		}

		if (changeMade) {
			child.ele.className = eleClasses;
			child.classes = newClasses;
		}
	},

	findCols: function(index, numChildren, subName) {
		var ag = audentio.grid,
			subLayout = {},
			ele = ag.eles[index].ele,
			layoutName = ag.eles[index].layoutName,
			minWidth = ag.layout.global.root.minWidth,
			numColumns = 1,
			parentWidth = ag.eles[index].parentWidth;

		if (typeof(ag.layout[layoutName]) !== "undefined" && typeof(ag.layout[layoutName][subName]) !== "undefined") subLayout = ag.layout[layoutName][subName];
		if (typeof(subLayout.minWidth) === "number") minWidth = subLayout.minWidth; // override global
		var minPercent = (minWidth / parentWidth) * 100

		if (typeof(subLayout.numCol) === "number") numColumns = subLayout.numCol;
		if (numColumns > numChildren && subLayout.fillLast != 1) numColumns = numChildren;
		var numCols = numColumns;
		for (var i = numColumns; i > 1; i--) {
			var minCol = 100
			for (j = 1; j <= subLayout.numCol; j++) {
				var width = 100 / i
				if (typeof(ag.layout["global"]["root"]["col_" + i]) !== "undefined") width = ag.layout["global"]["root"]["col_" + i]["col" + j];
				if (typeof(subLayout["col_" + i]) !== "undefined") width = subLayout["col_" + i]["col" + j];
				if (width !== undefined && width < minCol) minCol = width
			}
			if (minCol >= minPercent) {
				break;
			} else {
				numCols = i - 1;
			}
		}
		return numCols;
	},

	update: function() {
		var ag = audentio.grid,
			pfTime = uix.time();
		if (window.innerWidth >= ag.minEnableWidth) {
			if (ag.running) {
				var changeMade = false,
					gridEles = ag.eles,
					gridElesLen = gridEles.length;
				if (gridElesLen > 0) {
					var globalWidth = gridEles[0].ele.offsetWidth / gridEles[0].ratio;
					for (i = 0; i < gridElesLen; i++) {
						gridEles[i].parentWidth = globalWidth * gridEles[i].ratio;
						if (ag.set(i)) changeMade = true;
					}
				}
			} else {
				ag.init();
			}
		} else {
			audentio.grid.running = false;
			var forums = ag.getForums();
			if (ag.classAdded && forums !== null) {
				forums.className = forums.className.remove('audentio_grid_running');
				audentio.grid.classAdded = false;
			}
		}

		uix.tStamp("Grid Update", 2, pfTime);
	},

	getForums: function() {
		var ag = audentio.grid;

		var d = document,
			forums = d.getElementById("forums");
		if (forums == null) {
			forumsTemp = d.getElementsByClassName("nodeList section sectionMain");
			if (forumsTemp.length == 1) forums = forumsTemp[0];
		}
		if (forums == null) {
			forumsTemp = d.getElementsByClassName("watch_forums");
			if (forumsTemp.length == 1) forumsTemp = forumsTemp[0].getElementsByClassName("nodeList");
			if (forumsTemp.length == 1) forums = forumsTemp[0];
		}
		return forums;
	},
}


// #######################################################################################
// #																					 #
// #								 UI.X Debug Functions								 #
// #																					 #
// #######################################################################################
// Used to get times for UI.X initialization and basic information, even outside of beta mode.

//custom logger: logs only under Beta mode
uix.log = function(x, style) {
    if (typeof(style) === "undefined") {
        if (uix.betaMode) console.log(x);
    } else {
        if (uix.betaMode) console.log("%c" + x, style);
    }
};
uix.info = function(x) {
	if (uix.betaMode) console.info(x);
};

uix.debug = function() {
	console.log(uix.initTime);
	console.log(uix.xfTime);
	var result = "===========================================================================\n"
	var versionLength = 30,
		numLength = 10,
		nameLength = 46,
		totalLength = 14,
		durationLength = 14,
		timePoints = 3;

	result += "User Settings: \n"
	if (typeof(uix.user) !== "undefined"){
		var userKeys = Object.keys(uix.user);
		for (var i = 0, len = userKeys.length; i < len; i++){
			result += "	" + uix.spaceToLength(userKeys[i], 25) + ": " + uix.user[userKeys[i]] + "\n";
		}
	} else {
		result += "User settings not found.	There's likely an issue with the page_container_js_head template. \n"
	}
	result += "\n";

	result += "Browser: " + navigator.userAgent + "\n"
	result += uix.spaceToLength("UI.X Version", versionLength) + ": " + uix.version + "\n"
	result += uix.spaceToLength("UI.X Javascript Version", versionLength) + ": " + uix.jsVersion + "\n"
	result += uix.spaceToLength("UI.X js_head Version", versionLength) + ": " + uix.jsHeadVersion + "\n"
	result += uix.spaceToLength("UI.X Addon Version", versionLength) + ": " + uix.addonVersion + "\n"
	result += uix.spaceToLength("Beta Mode", versionLength) + ": " + uix.betaMode + "\n"
	result += uix.spaceToLength("Screen size (width, height)", versionLength) + ": " + "(" + window.screen.availWidth + ", " + window.screen.availHeight + ")" + "\n"
	result += uix.spaceToLength("Window size (width, height)", versionLength) + ": " + "(" + window.innerWidth + ", " + window.innerHeight + ")" + "\n"
	result += uix.spaceToLength("Current Page", versionLength) + ": " + window.location.href + "\n"
	result += uix.spaceToLength("XenForo Address", versionLength) + ": " + XenForo.baseUrl() + "\n"
	result += uix.spaceToLength("User ID", versionLength) + ": " + XenForo.visitor.user_id + "\n"
	result += uix.spaceToLength("RTL", versionLength) + ": " + XenForo.RTL + "\n"
	
	result += '\n' + uix.spaceToLength("", numLength) + uix.spaceToLength("Description", nameLength) + uix.spaceToLength("Total (ms)", totalLength) + uix.spaceToLength("Duration (ms)", durationLength)
	if (typeof(uix.initLog) !== "undefined"){
		for (var i = 0, len = uix.initLog.length; i < len; i++) {
			var index = i + 1;
			if (index >= len) index = 0;
			result = result + '\n' + uix.spaceToLength((i + 1).toString() + ":", numLength) + (uix.spaceToLength(uix.initLog[i][0], nameLength) + uix.spaceToLength(uix.round(uix.initLog[i][1], timePoints).toString(), totalLength) + uix.spaceToLength(uix.round(Math.abs(uix.initLog[index][1] - uix.initLog[i][1]), timePoints).toString(), durationLength))
		}
	} else {
		result += "No uix.initLog created. \n";
	}
	console.log(result);
	uix.pfLog = true;
	uix.betaMode = true;
}

uix.logI = function(label){
	uix.tStamp(label, 0, uix.pfTime);
	uix.initLog.push([label, (uix.time(true) - uix.pfTime)])
}

uix.spaceToLength = function(input, length) {
	var result = input;
	for (var i = input.length; i <= length; i++) {
		result += " "
	}
	return result;
}

uix.round = function(num, points){
	return Math.round(num * Math.pow(10, points)) / Math.pow(10, points);
}

uix.tStamp = function (label, level, startTime) {
	if (uix.pfLog && startTime !== null) uix.log(label + ": " + uix.round(uix.time() - startTime, 5) + " ms");
}

uix.time = function(force){
	if (force || uix.pfLog) {
		if (typeof(window.performance) === "undefined") return Date.now();
		return window.performance.now ? (performance.now() + performance.timing.navigationStart) : Date.now();
	}
	return null;
}


// #######################################################################################
// #																					 #
// #						 Xenforo Jump to Quoted Post								 #
// #																					 #
// #######################################################################################
// Override default xenforo function to add in sticky height offset.
XenForo.AttributionLink = function($link) {
	$link.click(function(e) {
		if ($(this.hash).length) {
			try {
				var hash = this.hash,
					top = $(this.hash).offset().top - uix.sticky.getOffset($(this.hash).offset().top), // modified line
					scroller = XenForo.getPageScrollTagName();

				if ("pushState" in window.history) {
					window.history.pushState({}, '', window.location.toString().replace(/#.*$/, '') + hash);
				}

				$(scroller).animate({
					scrollTop: top
				}, XenForo.speed.normal, 'swing', function() {
					if (!window.history.pushState) {
						window.location.hash = hash;
					}
				});
			} catch (e) {
				window.location.hash = this.hash;
			}

			e.preventDefault();
		}
	});
};



// #######################################################################################
// #																					 #
// #									Xenforo Reply									 #
// #																					 #
// #######################################################################################
// Override default xenforo function to add sticky height offset.

XenForo.QuickReply = function($form) {
	if ($('#messageList').length == 0) {
		return console.error('Quick Reply not possible for %o, no #messageList found.', $form);
	}

	var $lastDateInput = $('input[name="last_date"]', $form);
	if ($lastDateInput.data('load-value')) {
		// FF caches this value on refresh, but since we have the new posts, we need this to reflect the source value
		$lastDateInput.val(Math.max($lastDateInput.val(), $lastDateInput.data('load-value')));
	}

	var submitEnableCallback = XenForo.MultiSubmitFix($form);

	/**
	 * Scrolls QuickReply into view and focuses the editor
	 */
	this.scrollAndFocus = function() {
		$(document).scrollTop($form.offset().top - uix.sticky.getOffset($form.offset().top)); // modified line

		var ed = XenForo.getEditorInForm($form);
		if (!ed) {
			return false;
		}

		if (ed.$editor) {
			ed.focus(true);
		} else {
			ed.focus();
		}

		return this;
	};

	$form.data('QuickReply', this).bind({
		/**
		 * Fires just before the form would be AJAX submitted,
		 * to detect whether or not the 'more options' button was clicked,
		 * and to abort AJAX submission if it was.
		 *
		 * @param event e
		 * @return
		 */
		AutoValidationBeforeSubmit: function(e) {
			if ($(e.clickedSubmitButton).is('input[name="more_options"]')) {
				e.preventDefault();
				e.returnValue = true;
			}
		},

		/**
		 * Fires after the AutoValidator form has successfully validated the AJAX submission
		 *
		 * @param event e
		 */
		AutoValidationComplete: function(e) {
			if (e.ajaxData._redirectTarget) {
				window.location = e.ajaxData._redirectTarget;
			}

			$('input[name="last_date"]', $form).val(e.ajaxData.lastDate);

			if (submitEnableCallback) {
				submitEnableCallback();
			}

			$form.find('input:submit').blur();

			new XenForo.ExtLoader(e.ajaxData, function() {
				$('#messageList').find('.messagesSinceReplyingNotice').remove();

				$(e.ajaxData.templateHtml).each(function() {
					if (this.tagName) {
						$(this).xfInsert('appendTo', $('#messageList'));
					}
				});
			});

			var $textarea = $('#QuickReply').find('textarea');
			$textarea.val('');
			var ed = $textarea.data('XenForo.BbCodeWysiwygEditor');
			if (ed) {
				ed.resetEditor(null, true);
			}

			$form.trigger('QuickReplyComplete');

			return false;
		},

		BbCodeWysiwygEditorAutoSaveComplete: function(e) {
			var $messageList = $('#messageList'),
				$notice = $messageList.find('.messagesSinceReplyingNotice');

			if (e.ajaxData.newPostCount && e.ajaxData.templateHtml) {
				if ($notice.length) {
					$notice.remove();
					$(e.ajaxData.templateHtml).appendTo($messageList).show().xfActivate();
				} else {
					$(e.ajaxData.templateHtml).xfInsert('appendTo', $messageList);
				}
			} else {
				$notice.remove();
			}
		}
	});
};


// #######################################################################################
// #																					 #
// #								UIX Sticky Functions								 #
// #																					 #
// #######################################################################################

uix.init.sticky = function() {
	uix.sticky.set({
		betaMode: uix.betaMode,
		pfLog: uix.pfLog,
		minWidthDefault: uix.stickyNavigation_minWidth,
		maxWidthDefault: uix.stickyNavigation_maxWidth,
		minHeightDefault: uix.stickyNavigation_minHeight,
		maxHeightDefault: uix.stickyNavigation_maxHeight,
		stickyMinPos: uix.stickyGlobalMinimumPosition,
		postAddFunc: function() {
			window.requestAnimationFrame(uix.init.fixScrollLocation);
		},
		postStickFunc: function(eles) {
			window.requestAnimationFrame(function(){
				if (typeof(eles) !== "undefined" && eles.indexOf("navigation") > -1) {
					uix.navTabs();
				}
				uix.hideDropdowns.hide();
			})
		},
		postUnstickFunc: function(eles) {
			window.requestAnimationFrame(function(){
				if (typeof(eles) !== "undefined" && eles.indexOf("navigation") > -1) uix.navTabs();
				if (uix.enableBorderCheck) {
					uix.checkRadius.check();
				}
				uix.hideDropdowns.hide();
			})
		},
		postDelayUnstickFunc: function() {
			if (uix.sidebarSticky.running) uix.sidebarSticky.check();
			if (uix.enableBorderCheck) {
				window.setTimeout(function() {
					window.requestAnimationFrame(function(){
						uix.checkRadius.check();
					})
				}, 100);
			}
			uix.slidingSidebar = true;
			uix.hideDropdowns.hide();
		},
		preDelayUnstickFunc: function() {
			if (uix.elm.sidebar.length && uix.stickySidebar && uix.sidebarSticky.innerWrapper) {
				if (uix.sidebarSticky.bottomFixed == 0) { // bit of a hack, but stops sidebar flicker
					uix.sidebarSticky.innerWrapper.css({
						transition: 'top 0.2s'
					});
				} else {
					uix.sidebarSticky.innerWrapper.css({
						transition: 'top 0s'
					}); // remove transition to stop flicker
					uix.slidingSidebar = false;
				}
			}
			uix.sidebarSticky.check();
		}
	});

	var stickySel = $('.stickyTop')
	for (var i = 0, len = stickySel.length; i < len; i++) {
		var item = "#" + stickySel[i].id
		if (uix.stickyItems[item] !== undefined && uix.sticky.hasItem(item) == false) uix.sticky.add(item, uix.stickyItems[item].normalHeight, uix.stickyItems[item].stickyHeight, uix.stickyItems[item].options);
	}
	uix.sticky.initItemsAdded();
}


uix.sticky = {
	items: [],
	allInitItemsAdded: false,
	stickyHeight: 0,
	fullStickyHeight: 0,
	downStickyHeight: 0,
	stickyElmsHeight: 0,
	pfLog: false,
	betaMode: false,
	running: false,
	stickyLastBottom: 0,
	lastScrollTop: 0, // 
	scrollStart: 0, // the position that a scroll starts
	scrollDistance: 0, // counter of the distance of the current scroll
	scrollDirection: "start", // neither up nor down intentionally
	scrollClass: "",
	scrollLastClass: "",
	preventStickyTop: false, // stops sliding sticky from sticking during jump to top
	stickyMinPos: 350, // if scrollDetector enabled, won't sticky before this value
	stickyOffsetDist: 0, // the amount to position offscreen sticky elements
	stickyMinDist: 10, // min distance for a scroll to trigger sticky hide or show
	scrollDetectorRunning: false, // boolean if the scrollDetector is running
	scrollTop: window.scrollY || document.documentElement.scrollTop,
	html: $('html'),
	needsInit: true,
	windowWidth: 0,
	windowHeight: 0,
	minWidthDefault: 0,
	maxWidthDefault: 999999,
	minHeightDefault: 0,
	maxHeightDefault: 999999,
	noSubEle: 0,
	postAddFunc: function() {},
	postStickFunc: function() {},
	postUnstickFunc: function() {},
	postDelayUnstickFunc: function() {},
	preDelayUnstickFunc: function() {},

	set: function(o) {
		if (o !== undefined) {
			if (o.betaMode !== undefined) uix.sticky.betaMode = o.betaMode;
			if (o.pfLog !== undefined) uix.sticky.pfLog = o.pfLog;
			if (o.stickyMinPos !== undefined) uix.sticky.stickyMinPos = o.stickyMinPos;
			if (o.stickyMinDist !== undefined) uix.sticky.stickyMinDist = o.stickyMinDist;
			if (o.minWidthDefault !== undefined) uix.sticky.minWidthDefault = o.minWidthDefault;
			if (o.maxWidthDefault !== undefined) uix.sticky.maxWidthDefault = o.maxWidthDefault;
			if (o.minHeightDefault !== undefined) uix.sticky.minHeightDefault = o.minHeightDefault;
			if (o.maxHeightDefault !== undefined) uix.sticky.maxHeightDefault = o.maxHeightDefault;
			if (o.postAddFunc !== undefined) uix.sticky.postAddFunc = o.postAddFunc;
			if (o.postStickFunc !== undefined) uix.sticky.postStickFunc = o.postStickFunc;
			if (o.postUnstickFunc !== undefined) uix.sticky.postUnstickFunc = o.postUnstickFunc;
			if (o.postDelayUnstickFunc !== undefined) uix.sticky.postDelayUnstickFunc = o.postDelayUnstickFunc;
			if (o.preDelayUnstickFunc !== undefined) uix.sticky.preDelayUnstickFunc = o.preDelayUnstickFunc;
		}
	},

	init: function() {
		uix.sticky.viewport();
		uix.sticky.needsInit = false;
	},

	initItemsAdded: function(){
		var us = uix.sticky,
			pfTime = uix.time();
		uix.sticky.allInitItemsAdded = true;

		for (var i = 0, len = us.items.length; i < len; i++){
			us.checkSize(i); // see if item sticky is enabled at current size
		}
		
		uix.sticky.running = true;

		us.check();
		us.postAddFunc();
		uix.tStamp("UIX.sticky.initItemsAdded", 2, pfTime)
	},

	add: function(itemName, normalHeight, stickyHeight, o) {
		var us = uix.sticky,
			pfTime = uix.time();

		if (us.needsInit) us.init();

		var item = {
			name: itemName,
			elm: $(itemName),
			docElm: document.getElementById(itemName.replace('#', '')),
			state: 0,
			transitionState: 0,
			subElement: null,
			subCheckSize: 0,
			subNormalHeight: 0,
			subStickyHeight: 0,
			subStickyHide: false,
			subStickyHideClassAdded: false,
			itemFromWindowTop: null,
			wrapperFromWindowTop: null,

		};
		item.offset = item.elm.offset();

		if (o === undefined) {
			item.maxWidth = us.maxWidthDefault;
			item.minWidth = us.minWidthDefault;
			item.maxHeight = us.maxHeightDefault;
			item.minHeight = us.minHeightDefault;
			item.scrollSticky = 1;
		} else {
			item.maxWidth = (o.maxWidth === undefined ? us.maxWidthDefault : o.maxWidth);
			item.minWidth = (o.minWidth === undefined ? us.minWidthDefault : o.minWidth);
			item.maxHeight = (o.maxHeight === undefined ? us.maxHeightDefault : o.maxHeight);
			item.minHeight = (o.minHeight === undefined ? us.minHeightDefault : o.minHeight);
			item.scrollSticky = (o.scrollSticky === undefined ? 1 : o.scrollSticky);
			item.subElement = (o.subElement === undefined ? null : $(o.subElement));
			item.subNormalHeight = (o.subNormalHeight === undefined ? 0 : o.subNormalHeight);
			item.subStickyHeight = (o.subStickyHeight === undefined ? 0 : o.subStickyHeight);
			item.subStickyHide = (o.subStickyHide === undefined ? false : o.subStickyHide);
		}
		if (item.scrollSticky) us.scrollDetectorRunning = true;

		if (o.subStickyHide) item.subStickyHeight = 0;
		item.normalHeight = normalHeight + item.subNormalHeight;
		item.stickyHeight = stickyHeight + item.subStickyHeight;
		uix.sticky.fullStickyHeight += item.stickyHeight;
		if (item.scrollSticky == 0) uix.sticky.downStickyHeight += item.stickyHeight;

		if (item.normalHeight > item.stickyHeight) {
			uix.sticky.stickyOffsetDist += item.normalHeight;
		} else {
			uix.sticky.stickyOffsetDist += item.stickyHeight;
		}

		uix.sticky.stickyLastBottom = item.offset.top + item.elm.outerHeight();

		item.wrapper = item.elm.find('.sticky_wrapper');

		uix.sticky.items.push(item);

		if (us.allInitItemsAdded) us.initItemsAdded(); // initializes things, don't want to run unless adding an item after setup is complete

		uix.tStamp("UIX.sticky.add " + itemName, 2, pfTime)
	},

	hasItem: function(itemName) {
		var usi = uix.sticky.items;
		for (var x = 0, len = usi.length; x < len; x++) {
			if (usi[x].name == itemName) return true;
		}
		return false;
	},

	remove: function(itemName) {
		var us = uix.sticky;
		for (var x = 0, len = us.items.length; x < len; x++) {
			if (us.items[x].name == itemName) {
				uix.fullStickyHeight -= us.items[x].stickyHeight;
				uix.sticky.items.splice(x, 1);
			}
		}
		us.check();
	},

	stick: function(index, currentHeight) {
		var us = uix.sticky,
			currentItem = us.items[index],
			target = currentItem.elm,
			innerWrapper = currentItem.wrapper,
			normalHeight = currentItem.normalHeight,
			pfTime = uix.time();

		$('.lastSticky').removeClass('lastSticky');
		target.addClass('lastSticky').removeClass('inactiveSticky').addClass('activeSticky').css('height', normalHeight);

		if (currentItem.scrollSticky) {
			innerWrapper.css('top', (currentHeight - us.stickyOffsetDist)); // offset so items can scroll
		} else {
			innerWrapper.css({
				'top': currentHeight,
			});
		}

		uix.sticky.items[index].state = 1;
		uix.tStamp("UIX.sticky.stick " + currentItem.name , 2, pfTime)
	},

	unstick: function(index) {
		var us = uix.sticky,
			currentItem = us.items[index],
			target = currentItem.elm,
			innerWrapper = currentItem.wrapper,
			stickyHeight = currentItem.stickyHeight,
			pfTime = uix.time();

		if (false){
			innerWrapper.css('position', 'static')
		} else {
			target.addClass('inactiveSticky').removeClass('lastSticky').removeClass('activeSticky').css({
				'height': '',
			});

			innerWrapper.css({
				'top': '',
			});

			uix.sticky.items[index].state = 0;
			if (currentItem.state != 2) {
				$('.activeSticky').last().addClass('lastSticky');
			}
		}

		uix.tStamp("UIX.sticky.unstick " + currentItem.name, 2, pfTime)
	},

	delayUnstick: function() { // unsticks everything with state == 2, used for scroll sticky
		var us = uix.sticky,
			unstickDone = false;

		for (var x = 0, len = us.items.length; x < len; x++) {
			if (us.items[x].state == 2) {
				us.items[x].wrapper.css({
					'transform': '',
					'-webkit-transform': '',
					'-ms-transform': '',
					'-ms-transform': '',
					'-moz-transform': '',
				})
				uix.sticky.items[x].transitionState = 0;
				us.unstick(x);
				unstickDone = true;
			}
		}
		if (unstickDone) {
			$('.activeSticky').last().addClass('lastSticky');
			us.postDelayUnstickFunc();
		}
	},

	delayStick: function() {
		var us = uix.sticky;

		for (var x = 0, len = us.items.length; x < len; x++) {
			if (us.items[x].state == 3) {
				us.items[x].wrapper.css({
					'transform': 'translate3d(0, ' + us.stickyOffsetDist + 'px, 0)',
					'-webkit-transform': 'translate3d(0, ' + uix.sticky.stickyOffsetDist + 'px, 0)',
					'-ms-transform': 'translate3d(0, ' + us.stickyOffsetDist + 'px, 0)',
					'-ms-transform': 'translate3d(0, ' + us.stickyOffsetDist + 'px, 0)',
					'-moz-transform': 'translate3d(0, ' + us.stickyOffsetDist + 'px, 0)',
				}); // move to scroll in
				uix.sticky.items[x].transitionState = 2;
				uix.sticky.items[x].state = 1;
			}
		}
	},

	
	checkGet: function(){
		var us = uix.sticky;

		if (us.scrollDetectorRunning) us.scrollDetectorGet();

		for (var x = 0, len = us.items.length; x < len; x++) {
			var currentItem = us.items[x];
			if (currentItem.enabled) {
				if (typeof(currentItem.wrapper[0]) !== "undefined") {
					uix.sticky.items[x].itemFromWindowTop = currentItem.docElm.getBoundingClientRect().top;
					uix.sticky.items[x].wrapperFromWindowTop = currentItem.wrapper[0].getBoundingClientRect().top;
				}
			}
		}
	},
	

	checkSet: function(){
		var us = uix.sticky,
			currentStickyHeight = 0,
			needsDelayStick = false,
			needsDelayUnstick = false,
			needsPostStick = false,
			needsPostUnstick = false,
			newStickEles = "",
			newUnstickEles = "";

		if (us.scrollDetectorRunning) us.scrollDetectorSet();

		for (var x = 0, len = us.items.length; x < len; x++) {
			var currentItem = us.items[x];
			if (currentItem.enabled) {
				var itemFromWindowTop = currentItem.itemFromWindowTop,
					innerWrapper = currentItem.wrapper,
					wrapperFromWindowTop = currentItem.wrapperFromWindowTop,
					wrapperFromWindowTopInit = wrapperFromWindowTop;
				if (wrapperFromWindowTop < currentStickyHeight) wrapperFromWindowTop = currentStickyHeight; // fix for iOS
				if (us.scrollDetectorRunning && currentItem.scrollSticky == 1) {
					if (us.scrollTop <= us.stickyMinPos) {
						us.delayUnstick();
						if (currentItem.transitionState != 0) {
							innerWrapper.css({
								'transform': '',
								'-webkit-transform': '',
								'-ms-transform': '',
								'-ms-transform': '',
								'-moz-transform': '',
							});
							uix.sticky.items[x].transitionState = 0;
						}
					} else if (us.scrollTop > us.stickyMinPos && us.scrollDirection == "down" && (itemFromWindowTop < wrapperFromWindowTop && us.scrollDistance > us.stickyMinDist) && currentItem.transitionState != 1) {
						innerWrapper.css({
							'transform': 'translate3d(0, 0, 0)',
							'-webkit-transform': 'translate3d(0, 0, 0)',
							'-ms-transform': 'translate3d(0, 0, 0)',
							'-ms-transform': 'translate3d(0, 0, 0)',
							'-moz-transform': 'translate3d(0, 0, 0)',
						});
						uix.sticky.items[x].transitionState = 1;
					}

					if (currentItem.state == 1) { //Is stuck
						if ((itemFromWindowTop > wrapperFromWindowTop) || (us.scrollDirection == "down" && us.scrollDistance > us.stickyMinDist) || us.scrollTop <= us.stickyMinPos) {
							innerWrapper.css({
								'transform': 'translate3d(0, 0, 0)',
								'-webkit-transform': 'translate3d(0, 0, 0)',
								'-ms-transform': 'translate3d(0, 0, 0)',
								'-ms-transform': 'translate3d(0, 0, 0)',
								'-moz-transform': 'translate3d(0, 0, 0)',
							});
							uix.sticky.items[x].transitionState = 1;
							uix.sticky.items[x].state = 2; // prevent any additional sticking or unsticking until it is unstuck
							needsDelayUnstick = true;
							us.subElementCheck(x, currentStickyHeight, itemFromWindowTop);
						} else {
							us.subElementCheck(x, currentStickyHeight, itemFromWindowTop);
							currentStickyHeight += currentItem.stickyHeight;
						}
					} else if (currentItem.state == 0 && us.preventStickyTop == false) { //Not stuck
						if (wrapperFromWindowTopInit - currentStickyHeight <= 0 && us.scrollDirection == "up" && us.scrollDistance > us.stickyMinDist && us.scrollTop > us.stickyMinPos) {
							us.stick(x, currentStickyHeight);
							uix.sticky.items[x].state = 3;
							needsDelayStick = true;
							currentStickyHeight += currentItem.stickyHeight;
						}
					}
				} else { // not scrollSticky
					if (currentItem.state == 1) { //Is stuck
						if (itemFromWindowTop > wrapperFromWindowTop) {
							us.unstick(x);
							needsPostUnstick = true;
							newUnstickEles += "," + currentItem.name;
							us.subElementCheck(x, currentStickyHeight, itemFromWindowTop);
						} else {
							us.subElementCheck(x, currentStickyHeight, itemFromWindowTop);
							currentStickyHeight += currentItem.stickyHeight;
						}
					} else { //Not stuck
						if (wrapperFromWindowTopInit - currentStickyHeight <= 0) {
							us.stick(x, currentStickyHeight);
							needsPostStick = true;
							newStickEles += "," + currentItem.name;
							us.subElementCheck(x, currentStickyHeight, itemFromWindowTop);
							currentStickyHeight += currentItem.stickyHeight;
						}
					}
				}
			} else {
				if (currentItem.state == 1) {
					us.unstick(x);
				}
			}
		};
		uix.sticky.stickyHeight = currentStickyHeight;

		if (needsPostStick) {
			us.postStickFunc(newStickEles);
		}

		if (needsPostUnstick) {
			us.postUnstickFunc(newUnstickEles);
		}

		if (needsDelayUnstick) {
			us.preDelayUnstickFunc();
			window.setTimeout(function() {
				window.requestAnimationFrame(function(){
					us.delayUnstick();
				})
			}, 210); // delay so the translate3d can happen
		} else if (needsDelayStick) {
			us.delayStick();
		}

		if (uix.smallLogo.running) uix.smallLogo.set();
	},

	check: function(){
		var us = uix.sticky,
			pfTime = uix.time();

		us.checkGet();
		us.checkSet();

		uix.tStamp("UIX.sticky.check", 2, pfTime)
	},

	subElementCheck: function(x, currentStickyHeight, itemFromWindowTop) {
		var us = uix.sticky,
			currentItem = us.items[x];
		if (currentItem.subElement !== null && currentItem.subNormalHeight > 0 && currentItem.subStickyHide) {
			if (us.scrollDetectorRunning && currentItem.scrollSticky == 1) {
				if (currentItem.state == 1 && currentItem.subStickyHideClassAdded == false) {
					uix.sticky.items[x].subStickyHideClassAdded = true;
					currentItem.elm.addClass('uix_hideSubElement');
				} else if (currentItem.state != 1 && currentItem.subStickyHideClassAdded) {
					uix.sticky.items[x].subStickyHideClassAdded = false;
					currentItem.elm.removeClass('uix_hideSubElement');
					us.updateNavLinks(x);
				}
			} else {
				if (currentItem.state == 1) {
					if ((-1 * itemFromWindowTop) + currentStickyHeight > currentItem.subNormalHeight) {
						if (currentItem.subStickyHideClassAdded == false) {
							uix.sticky.items[x].subStickyHideClassAdded = true;
							currentItem.elm.addClass('uix_hideSubElement');
						}
					} else {
						if (currentItem.subStickyHideClassAdded) {
							uix.sticky.items[x].subStickyHideClassAdded = false;
							currentItem.elm.removeClass('uix_hideSubElement');
							us.updateNavLinks(x);
						}
					}
				} else {
					uix.sticky.items[x].subStickyHideClassAdded = false;
					currentItem.elm.removeClass('uix_hideSubElement');
					us.updateNavLinks(x);
				}
			}
		}
	},

	updateNavLinks: function(x){
		var us = uix.sticky;

		if (us.windowWidth != us.items[x].subCheckSize){
			uix.sticky.items[x].subCheckSize = us.windowWidth;

			window.setTimeout(function(){
				window.requestAnimationFrame(function(){
					XenForo.updateVisibleNavigationLinks();
				})
			}, 100)
		}
	},

	resize: function() {
		var us = uix.sticky;
		us.viewport();
		for (var x = 0, len = us.items.length; x < len; x++) {
			us.checkSize(x)
		}
		us.update();
	},

	viewport: function() {
		uix.sticky.windowWidth = uix.windowWidth;
		uix.sticky.windowHeight = uix.windowHeight;
	},

	checkSize: function(x) {
		var us = uix.sticky,
			item = us.items[x],
			windowHeight = us.windowHeight,
			windowWidth = us.windowWidth;
		if (windowHeight > item.minHeight && windowHeight <= item.maxHeight && windowWidth > item.minWidth && windowWidth <= item.maxWidth) {
			uix.sticky.items[x].enabled = true;
		} else {
			uix.sticky.items[x].enabled = false;
		}
	},

	update: function() {
		var us = uix.sticky,
			items = us.items;
		currentTop = 0;
		us.check();
		for (var x = 0, len = items.length; x < len; x++) {
			var item = items[x];
			if (item.state == 1 && item.enabled) {
				innerWrapper = item.wrapper;
				if (item.scrollSticky) {
					innerWrapper.css('top', (currentTop - us.stickyOffsetDist)); // offset so items can scroll
				} else {
					innerWrapper.css({
						'top': currentTop,
					});
				}
				currentTop += item.stickyHeight;
			}
		}
		us.check();
	},

	getState: function(itemName) {
		var usi = uix.sticky.items;
		for (var x = 0, len = usi.length; x < len; x++) {
			if (usi[x].name == itemName) return usi[x].state;
		}
		return -1;
	},

	getItemIndex: function(itemName) {
		var usi = uix.sticky.items;
		for (var x = 0, len = usi.length; x < len; x++) {
			if (usi[x].name == itemName) return x;
		}
		return -1;
	},

	updateScrollTop: function() {
		uix.sticky.scrollTop = window.scrollY || document.documentElement.scrollTop;
		return uix.sticky.scrollTop;
	},

	getOffset: function(desiredPos) {
		var us = uix.sticky;
		if (us.scrollDetectorRunning == false) return us.fullStickyHeight + uix.globalPadding;
		us.updateScrollTop();
		if (desiredPos < us.scrollTop) return us.fullStickyHeight + uix.globalPadding;
		return uix.globalPadding;
	},

	scrollDetectorGet: function(){
		var us = uix.sticky;

		us.updateScrollTop();

		direction = "";
		if (us.scrollDirection == 'start') uix.sticky.scrollStart = us.scrollTop; // initialize
		if (us.scrollTop > us.lastScrollTop) {
			if (us.scrollDirection == 'up') uix.sticky.scrollStart = us.lastScrollTop; // just changing to a new direction, record the new starting point
			direction = 'down';
			uix.sticky.scrollClass = "scrollDirection-down";
		} else if (us.scrollTop < us.lastScrollTop) {
			if (us.scrollDirection == 'down') uix.sticky.scrollStart = us.lastScrollTop; // just changing to a new direction, record the new starting point
			direction = 'up';
			uix.sticky.scrollClass = "scrollDirection-up";
		}
		uix.sticky.scrollDistance = Math.abs(us.scrollTop - us.scrollStart);
		uix.sticky.scrollDirection = direction;
		uix.sticky.lastScrollTop = us.scrollTop;
	},

	scrollDetectorSet: function(){
		var us = uix.sticky;

		if (us.scrollClass !== us.scrollLastClass) {
			us.html.removeClass(us.scrollLastClass).addClass(us.scrollClass);
			uix.sticky.scrollLastClass = us.scrollClass;
		}
	},

	scrollDetector: function() {
		var us = uix.sticky,
			pfTime = uix.time();
		
		us.scrollDetectorGet();
		us.scrollDetectorSet();

		uix.tStamp("UIX.sticky.scrollDetector", 2, pfTime)
	},
};

uix.sidebarSticky = {
	running: false,
	hasTransition: true,
	state: 0,
	bottomFixed: 0,
	hasSidebar: false,

	init: function() {
		var us = uix.sidebarSticky,
			d = document,
			B = d.body,
			H = d.documentElement,
			docHeight = d.height,
			elm = uix.elm.sidebar;

		uix.sidebarSticky.innerWrapper = elm.find('.inner_wrapper');
		uix.sidebarSticky.running = true;
		us.resize();
		window.setInterval(function(){
			window.requestAnimationFrame(function(){
				if (us.running && uix.initDone && uix.sidebarVisible == 1) {
					var documentHeight,
						sidebarHeight,
						sidebarEleHeight = elm.outerHeight(),
						wrapperHeight = us.innerWrapper.outerHeight();

					if (typeof docHeight !== 'undefined') {
						documentHeight = document.height // For webkit browsers
					} else {
						documentHeight = Math.max( B.scrollHeight, B.offsetHeight,H.clientHeight, H.scrollHeight, H.offsetHeight );
					}

					if (sidebarEleHeight > wrapperHeight) {
						sidebarHeight = sidebarEleHeight;
					} else {
						sidebarHeight = wrapperHeight;
					}

					if (documentHeight != uix.documentHeight || sidebarHeight != us.sidebarHeight) {
						uix.documentHeight = documentHeight;
						us.update();
					}
				}
			});
		}, 1000);
	},


	resize: function() {
		var us = uix.sidebarSticky;

		if (uix.elm.sidebar.length && uix.stickySidebar && uix.sidebarVisible == 1) {
			if (uix.windowWidth > uix.sidebarMaxResponsiveWidth && us.running) {
				uix.sidebarSticky.state = -1;
				uix.sidebarSticky.bottomFixed = 0;
				us.update();
				if (us.state == 1) {
					us.innerWrapper.css({
						left: us.sidebarFromLeft
					});
				} else {
					us.innerWrapper.css('left', '')
				}
			} else if (uix.windowWidth > uix.sidebarMaxResponsiveWidth && !us.running && !uix.stickyForceDisable) {
				us.init();
			} else {
				us.reset();
			}
		}
	},

	update: function() {
		var us = uix.sidebarSticky,
			elm = uix.elm.sidebar;

		uix.sidebarSticky.hasSidebar = elm.length;

		if (us.hasSidebar && uix.stickySidebar && us.running) {
			var pfTime = uix.time(),
				offset = elm.offset(),
				sidebarHeight = elm.outerHeight(),
				wrapperHeight = us.innerWrapper.outerHeight();

			uix.sidebarSticky.sidebarOffset = offset;
			uix.sidebarSticky.sidebarFromLeft = offset.left;
			uix.sidebarSticky.mainContainerHeight = uix.elm.mainContainer.outerHeight();

			if (sidebarHeight > wrapperHeight) {
				uix.sidebarSticky.sidebarHeight = sidebarHeight;
			} else {
				uix.sidebarSticky.sidebarHeight = wrapperHeight;
			}

			uix.sidebarSticky.bottomLimit = uix.elm.mainContainer.offset().top + us.mainContainerHeight - uix.sidebarStickyBottomOffset;
			uix.sidebarSticky.maxTop = us.bottomLimit - (offset.top + us.sidebarHeight);

			us.check();

			uix.sidebarSticky.innerWrapper.css('width', elm.outerWidth());

			uix.tStamp("UIX.sidebarSticky.update", 2, pfTime);
		}
	},


	check: function() {
		var us = uix.sidebarSticky;

		if (us.hasSidebar && uix.stickySidebar && uix.windowWidth > uix.sidebarMaxResponsiveWidth) {
			var pfTime = uix.time();
			if (us.mainContainerHeight > us.sidebarHeight) {
				var sidebarFromWindowTop = us.sidebarOffset.top - (uix.sticky.stickyHeight + uix.scrollTop),
					bottomLimitFromWindowTop = us.bottomLimit - uix.scrollTop;

				if (us.bottomFixed != 1 && bottomLimitFromWindowTop - uix.sticky.stickyHeight <= us.sidebarHeight + uix.globalPadding) {
					us.fixBottom()
				} else if (us.state != 0 && sidebarFromWindowTop - uix.globalPadding > 0) {
					us.unstick();
				} else if (us.state != 1 && bottomLimitFromWindowTop - uix.sticky.stickyHeight > us.sidebarHeight + uix.globalPadding && sidebarFromWindowTop - uix.globalPadding <= 0) {
					us.stick();
				}
			} else if (us.running) {
				if (us.state != 0) us.unstick();
			}
			uix.tStamp("UIX.sidebarSticky.check", 2, pfTime);
		}
	},

	stick: function() {
		var us = uix.sidebarSticky;

		if (us.hasSidebar && uix.stickySidebar && uix.windowWidth > uix.sidebarMaxResponsiveWidth) {
			var pfTime = uix.time();
			uix.elm.sidebar.addClass('sticky');
			uix.sidebarSticky.state = 1;
			uix.sidebarSticky.bottomFixed = 0;
			if (!uix.slidingSidebar && us.hasTransition) {
				uix.sidebarSticky.hasTransition = false;
				uix.sidebarSticky.innerWrapper.css({
					transition: 'top 0.0s'
				});
			}
			uix.sidebarSticky.innerWrapper.css({
				top: uix.sticky.stickyHeight + uix.globalPadding,
				left: us.sidebarOffset.left,
			});
			uix.tStamp("UIX.sidebarSticky.stick", 2, pfTime);
		}
	},

	unstick: function() {
		var us = uix.sidebarSticky;

		if (us.hasSidebar && uix.stickySidebar && us.running) {
			var pfTime = uix.time();
			uix.elm.sidebar.removeClass('sticky');
			uix.sidebarSticky.state = 0;
			uix.sidebarSticky.bottomFixed = 0;
			if (uix.slidingSidebar && !us.hasTransition) {
				uix.sidebarSticky.hasTransition = true;
				uix.sidebarSticky.innerWrapper.css({
					transition: 'top 0.2s'
				});
			} else if (!uix.slidingSidebar && us.hasTransition) {
				uix.sidebarSticky.hasTransition = false;
				uix.sidebarSticky.innerWrapper.css({
					transition: 'top 0.0s'
				});
			}
			uix.sidebarSticky.innerWrapper.css({
				top: '',
				left: ''
			});
			uix.tStamp("UIX.sidebarSticky.unStick", 2, pfTime);
		}
	},

	fixBottom: function() {
		var us = uix.sidebarSticky;

		if (us.hasSidebar && uix.stickySidebar && uix.windowWidth > uix.sidebarMaxResponsiveWidth) {
			var pfTime = uix.time();
			uix.stickySidebar.hasTransition = false;
			if (uix.sidebarSticky.state == 1){
				uix.elm.sidebar.removeClass('sticky');
			}

			uix.sidebarSticky.innerWrapper.css({
				transition: 'top 0.0s',
				top: us.maxTop,
				left: ''
			});
			uix.sidebarSticky.state = 2;
			uix.sidebarSticky.bottomFixed = 1;
			uix.tStamp("UIX.sidebarSticky.fixBottom", 2, pfTime);
		}
	},

	reset: function() {
		var us = uix.sidebarSticky;

		if (us.hasSidebar && uix.stickySidebar && us.running) {
			var pfTime = uix.time();
			us.unstick();
			uix.sidebarSticky.innerWrapper.css('width', '');
			uix.sidebarSticky.running = false;
			uix.tStamp("UIX.sidebarSticky.reset", 2, pfTime);
		}
	}
};


//TODO: make work on resize with changeMade
uix.stickyFooter = {
	running: false,
	eleTop: null,
	contentWrapper: null,
	eleTopSel: "#uix_stickyFooterSpacer",
	contentWrapperSel: "#uix_wrapper",
	state: 0,
	paddingBottom: 0,
	minDiff: uix.globalPadding,
	targetHeight: 0,
	changeMade: false,

	init: function(){
		var us = uix.stickyFooter;

		uix.stickyFooter.running = true;
		uix.stickyFooter.eleTop = $(us.eleTopSel);
		uix.stickyFooter.contentWrapper = $(us.contentWrapperSel)

		uix.stickyFooter.get();
	},

	get: function(){
		var us = uix.stickyFooter,
			changeMade = false;

		if (us.running){
			if (us.eleTop.length > 0 && us.contentWrapper.length > 0) {
				uix.stickyFooter.paddingBottom = us.contentWrapper.offset().top;

				var windowHeight = uix.windowHeight,
					contentHeight = us.contentWrapper.height(),
					heightDiff = windowHeight - contentHeight - us.paddingBottom - us.minDiff,
					topHeight = us.eleTop.height(),
					targetHeight = (topHeight + heightDiff);

				if (targetHeight - topHeight > 0){
					uix.stickyFooter.state = 1;
				} else if (us.state == 1) {
					uix.stickyFooter.state = 0;
				}

				if (uix.stickyFooter.targetHeight != targetHeight) {
					changeMade = true;
					uix.stickyFooter.targetHeight = targetHeight;
				}

			}
			uix.stickyFooter.changeMade = changeMade;
		}
	},

	set: function(){
		var us = uix.stickyFooter;
		if (us.changeMade){ // use changeMade later
			uix.stickyFooter.eleTop.css('min-height', (us.targetHeight + us.minDiff) + "px");
			if (uix.enableBorderCheck) uix.checkRadius.check();
		}
	},

	check: function(){
		var us = uix.stickyFooter;
		us.get();
		us.set();
	}
}




// #######################################################################################
// #																					 #
// #									UIX Check Radius								 #
// #																					 #
// #######################################################################################

uix.checkRadius = {
	elms: ["#logoBlock .pageContent", "#content .pageContent", "#userBar .pageContent", "#navigation .pageContent", ".footer .pageContent", "#uix_footer_columns .pageContent", ".footerLegal .pageContent"],
	elmInfo: [],
	needsInit: true,
	wrapperTop: null,
	needsCheck: false,
	uixWrapper: null,
	running: false,

	init: function(){
		var uc = uix.checkRadius;

		/* Target elements to run tests against */
		for (var i = 0, len = uc.elms.length; i < len; i++) {
			var element_selector = uc.elms[i],
				element = $(element_selector),
				eleLength = element.length;
			if (eleLength) {
				var newEle = {
					element: element,
					length: eleLength,
					lastClass: "",
					fullWidth: false,
				}

				uix.checkRadius.elmInfo.push(newEle);
			}
		}

		uix.checkRadius.uixWrapper = $("#uix_wrapper");

		uc.resize();

		uix.checkRadius.needsInit = false;
		uix.checkRadius.running = true;

		uc.check(true);
	},

	resize: function(){
		uix.checkRadius.wrapperTop = uix.checkRadius.uixWrapper.offset().top;
	},

	get: function(checkWidth){
		if (checkWidth !== true) checkWidth = false;

		var uc = uix.checkRadius,
			wrapperTop = uc.wrapperTop,
			windowWidth = (checkWidth) ? uix.windowWidth : 0;

		var elmInfoLen = uc.elmInfo.length;

		for (var i = 0; i < elmInfoLen; i++) {
			var elm = uc.elmInfo[i].element;
			if (checkWidth) uix.checkRadius.elmInfo[i].width = elm.outerWidth();
			uix.checkRadius.elmInfo[i].height = elm.outerHeight();
			uix.checkRadius.elmInfo[i].topOffset = elm.offset().top;
			uix.checkRadius.elmInfo[i].topRadius = true;
			uix.checkRadius.elmInfo[i].bottomRadius = true;
		}
	},

	set: function(checkWidth){
		if (checkWidth !== true) checkWidth = false;

		var uc = uix.checkRadius,
			wrapperTop = uc.wrapperTop,
			windowWidth = (checkWidth) ? uix.windowWidth : 0;

		var elmInfoLen = uc.elmInfo.length;

		for (var i = 0; i < elmInfoLen; i++) { // Loop through all
			var elmI = uc.elmInfo[i];
			if (elmI.length) {
				if (checkWidth && elmI.width == windowWidth) { //Reset border-radius if element is full width
					uix.checkRadius.elmInfo[i].topRadius = false;
					uix.checkRadius.elmInfo[i].bottomRadius = false;
					uix.checkRadius.elmInfo[i].fullWidth = true;
				} else if (elmI.fullWidth) {
					uix.checkRadius.elmInfo[i].topRadius = false;
					uix.checkRadius.elmInfo[i].bottomRadius = false;
				} else {
					for (var x = 0; x < elmInfoLen; x++) { // Check if our element is touching other elms[]
						var elmX = uc.elmInfo[x];
						
						if (x != i) { // Dont check against itself
							if (elmX.length) {
								if (Math.abs(elmI.topOffset - (elmX.topOffset + elmX.height)) < 1) uix.checkRadius.elmInfo[i].topRadius = false; // attached top
								if (Math.abs((elmI.topOffset + elmI.height) - elmX.topOffset) < 1) uix.checkRadius.elmInfo[i].bottomRadius = false; // attached bottom
							}
						}
					}
				}

				var newClass = "defaultBorderRadius";
				if (elmI.topRadius == false && elmI.bottomRadius == false) {
					newClass = "noBorderRadius";
				} else if (elmI.topRadius == false) {
					newClass = "noBorderRadiusTop";
				} else if (elmI.bottomRadius == false) {
					newClass = "noBorderRadiusBottom";
				}

				if (newClass != elmI.lastClass){
					elmI.element.addClass(newClass).removeClass(elmI.lastClass);
					uix.checkRadius.elmInfo[i].lastClass = newClass;
				}
			}
		}
		if (uc.needsCheck) uix.checkRadius.needsCheck = false;
	},

	scheduleCheck: function(){
		uix.checkRadius.needsCheck = true;
	},

	check: function(checkWidth) {
		var uc = uix.checkRadius,
			pfTime = uix.time();

		if (uc.running){
			if (checkWidth !== true) checkWidth = false;
			uc.get(checkWidth);
			uc.set(checkWidth);
		}

		uix.tStamp("UIX.checkRadius.check", 2, pfTime);
	},
}

// #######################################################################################
// #																					 #
// #									UIX Small Logo									 #
// #																					 #
// #######################################################################################

uix.smallLogo = {
	needsInit: true,
	logoTopOffset: 0,
	logoOuterHeight: 0,
	stickyWrapper: null,
	stickyWrapperOffsetTop: 0,
	stickyWrapperOuter: 0,
	visible: 0,
	running: false,


	init: function(){
		uix.smallLogo.needsInit = false;
		if (uix.elm.logoSmall.length && uix.elm.logo.length){
			uix.smallLogo.stickyWrapper = uix.elm.navigation.find('.sticky_wrapper'),
			uix.smallLogo.resize();
			uix.smallLogo.check();
			uix.smallLogo.running = true;
		}
	},

	get: function(){

	},

	set: function(){
		var us = uix.smallLogo;

		if (uix.sticky.getState('#navigation') == 1) {
			var logoTopOffset = us.logoTopOffset + us.logoOuterHeight,
				navigationBottomOffset = uix.scrollTop;

			if (logoTopOffset < navigationBottomOffset) {
				if (us.visible == 0) uix.smallLogo.addLogo();
			} else {
				if (us.visible == 1) uix.smallLogo.removeLogo();
			}
		} else {
			if (us.visible == 1) uix.smallLogo.removeLogo();
		}
	},

	addLogo: function(){
		uix.smallLogo.visible = 1;

		var pfTime2 = uix.time();
		uix.elm.html.addClass('activeSmallLogo');
		if (!uix.initDone) {
			uix.updateNavTabs = true;
		} else {
			uix.navTabs();
			if (uix.enableBorderCheck) uix.ulManager.check();
		}
		uix.tStamp("uix.navTabs", 3, pfTime2);
	},

	removeLogo: function(){
		uix.smallLogo.visible = 0;

		var pfTime2 = uix.time();
		uix.elm.html.removeClass('activeSmallLogo');
					
		if (!uix.initDone) {
			uix.updateNavTabs = true;
		} else {
			uix.navTabs();
			if (uix.enableBorderCheck) uix.ulManager.check();
		}
		uix.tStamp("uix.navTabs", 3, pfTime2);
	},

	check: function(){
		var us = uix.smallLogo,
			pfTime = uix.time();

		if (us.needsInit) us.init();
		us.get();
		us.set();

		uix.tStamp("UIX.smallLogo.check", 2, pfTime);
	},

	resize: function(){
		uix.smallLogo.logoOuterHeight = uix.elm.logo.outerHeight(true) / 2;
		uix.smallLogo.logoTopOffset = uix.elm.logo.offset().top;
		uix.smallLogo.stickyWrapperOuter = uix.smallLogo.stickyWrapper.outerHeight(true);
	}
}

// #######################################################################################
// #																					 #
// #									UIX Viewport									 #
// #																					 #
// #######################################################################################

uix.viewport = {
	running: false,
	scrollClass: "",

	get: function(){
		uix.scrollTop = window.scrollY || document.documentElement.scrollTop;
	},

	set: function(){
		if (uix.scrollTop == 0) {
			uix.elm.html.removeClass('scrollNotTouchingTop');
			uix.viewport.scrollClass = "";
		} else if (uix.viewport.scrollClass != 'scrollNotTouchingTop') {
			uix.elm.html.addClass('scrollNotTouchingTop');
			uix.viewport.scrollClass = 'scrollNotTouchingTop';
		}
	},

	check: function(){
		var pfTime = uix.time();
		uix.viewport.get();
		uix.viewport.set();
		uix.tStamp("UIX.viewport.check", 2, pfTime);
	},

	init: function(){
		var pfTime = uix.time();
		var e = window,
			a = 'inner';
		if (!window.innerWidth) {
			a = 'client';
			e = document.documentElement || document.body;
		}
		var viewport = {
			width: e[a + 'Width'],
			height: e[a + 'Height']
		};
		uix.windowWidth = viewport.width;
		uix.windowHeight = viewport.height;

		if (uix.viewport.running == false) uix.viewport.running = true; // don't add the on scroll multiple times

		uix.viewport.get();

		uix.tStamp("UIX.viewport.init", 1, pfTime);
	}

}



// #######################################################################################
// #																					 #
// #							 UIX Width Toggle Functions								 #
// #																					 #
// #######################################################################################

uix.toggleWidth = {
	eles: null,
	needsInit: true,
	state: 0,
	widthSet: false,
	running: false,
	sheet: function() {
		var style = document.createElement("style");

		// WebKit hack :(
		style.appendChild(document.createTextNode(""));

		// Add the <style> element to the page
		document.head.appendChild(style);

		return style.sheet;
	},

	setup: function(){
		uix.toggleWidth.state = uix.user.widthToggleState;
		uix.toggleWidth.running = true;

		if (uix.toggleWidth.state == 1){
			$('.chooser_widthToggle').addClass('uix_widthToggle_upper').removeClass('uix_widthToggle_lower');
		}
	},

	init: function(){
		if (uix.pageStyle == 2) {
			uix.toggleWidth.eles = $('#uix_wrapper, .pageWidth, .navTabs .navTab.selected .blockLinksList');
		} else if (uix.pageStyle == 1){
			uix.toggleWidth.eles = $('.pageWidth, .navTabs .navTab.selected .blockLinksList');
		} else {
			uix.toggleWidth.eles = $('.pageWidth');
		}
		uix.toggleWidth.needsInit = false;
	},

	resize: function(){
		if (uix.toggleWidth.running){
			if (uix.windowWidth <= parseInt(uix.widthToggleLower) + (2 * uix.globalPadding)) {
				if (uix.toggleWidth.widthSet){
					uix.toggleWidth.setMaxWidthQuick("");
					uix.toggleWidth.widthSet = false;
				}
			} else if (uix.toggleWidth.widthSet == false) {
				if (uix.toggleWidth.state == 1) {
					uix.toggleWidth.setMaxWidthQuick(uix.widthToggleUpper);
				} else {
					uix.toggleWidth.setMaxWidthQuick(uix.widthToggleLower);
				}
			}
		}
	},

	toggle: function(){
		if (uix.toggleWidth.needsInit) uix.toggleWidth.init();

		if (uix.toggleWidth.state != 1){
			uix.toggleWidth.setMaxWidth(uix.widthToggleUpper)
			uix.toggleWidth.state = 1;
			$('.chooser_widthToggle').addClass('uix_widthToggle_upper').removeClass('uix_widthToggle_lower');
		} else {
			uix.toggleWidth.setMaxWidth(uix.widthToggleLower)
			uix.toggleWidth.state = 0;
			$('.chooser_widthToggle').addClass('uix_widthToggle_lower').removeClass('uix_widthToggle_upper');
		}

		var xmlhttp = new XMLHttpRequest();

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 ) {
				if(xmlhttp.status == 200){
					content = xmlhttp.responseText;
				}
			}
		}

		xmlhttp.open('GET', "index.php/account/uix-toggle-width", true);
		xmlhttp.send();
	},

	setMaxWidth: function(val){
		uix.toggleWidth.widthSet = true;

		window.requestAnimationFrame(function(){
			uix.toggleWidth.eles.css({
				'transition': 'max-width 0.3s ease',
				'-webkit-transition': 'max-width 0.3s ease'
			});

			window.requestAnimationFrame(function(){
				uix.toggleWidth.setWidth(val);
				window.setTimeout(function(){
					window.requestAnimationFrame(function(){
						uix.toggleWidth.eles.css({
							'transition': '',
							'-webkit-transition': ''
						});
						window.dispatchEvent(new Event('resize'));
					});
				}, 300)
			})
		});
	},

	setMaxWidthQuick: function(val){
		if (uix.toggleWidth.needsInit) uix.toggleWidth.init();

		uix.toggleWidth.setWidth(val);

		window.dispatchEvent(new Event('resize'));
	},

	setWidth: function(val){
		uix.toggleWidth.widthSet = true;
		if (uix.pageStyle == 2) {
			for (var i = 0, len = uix.toggleWidth.eles.length; i < len; i++){
				var ele = uix.toggleWidth.eles[i]
				if (ele.className != "" && ele.className.indexOf("pageWidth") > -1) {
					uix.toggleWidth.sheet().insertRule("#uix_wrapper .activeSticky .pageWidth {max-width: " + val + ";}", 0);
				} else {
					ele.style.maxWidth = val;
				}
			}
		} else {
			uix.toggleWidth.eles.css('max-width', val)
		}
	}
}


// #######################################################################################
// #																					 #
// #							 UIX Collapsible Functions								 #
// #																					 #
// #######################################################################################

uix.collapsible = {
	items: [],
	needsInit: true,


	init: function(){
		var changeMade = false;
		if (uix.collapsibleNodes) {
			var eles = document.getElementsByClassName("node category level_1"),
				colDefaultArray = uix.collapsedNodesDefault.split(","),
				colNodesCookie = $.getCookie('uix_collapsedNodes'),
				colNodesArray = (colNodesCookie == null) ? [] : colNodesCookie.split(','),
				expNodesCookie = $.getCookie('uix_expandedNodes'),
				expNodesArray = (expNodesCookie == null) ? [] : expNodesCookie.split(",");

			for (var i = 0, len = eles.length; i < len; i++){
				(function (i) { 
					var trigger = eles[i].getElementsByClassName("uix_collapseNodes"),
						ele = eles[i],
						eleClass = ele.className,
						eleClassSplit = eleClass.split(" "),
						label = "",
						defaultVal = true,
						itemState = 1,
						defaultCollapsed = false;
					
					for (var j = 0, len2 = eleClassSplit.length; j < len2; j++){
						if (eleClassSplit[j].indexOf("node_") > -1) {
							label = eleClassSplit[j];
							break;
						}
					}

					if (label != ""){
						for (var j = 0, len2 = colDefaultArray.length; j < len2; j++){
							if (label == colDefaultArray[j]) {
								itemState = 0;
								defaultCollapsed = true;
								break;
							}
						}
						for (var j = 0, len2 = colNodesArray.length; j < len2; j++){
							if (label == colNodesArray[j]) {
								itemState = 0;
								defaultVal = false;
								break;
							}
						}
						if (itemState == 0){
							for (var j = 0, len2 = expNodesArray.length; j < len2; j++){
								if (label == expNodesArray[j]) {
									itemState = 1;
									defaultVal = false;
									break;
								}
							}
						}

						var item = {
								ele: ele,
								child: ele.getElementsByClassName("nodeList")[0],
								state: itemState,
								enabled: 1,
								index: i,
								defaultVal: defaultVal,
								defaultCollapsed: defaultCollapsed,
								label: label,
								trigger: (trigger.length === 1) ? trigger[0] : null,
							};

						if (item.trigger !== null) {
							item.trigger.addEventListener('click',
								function(e){
									e.preventDefault();
									uix.collapsible.toggle(i)
									return false;
								},
								false
							)
						}

						uix.collapsible.items.push(item);

						if (itemState == 0){
							uix.collapsible.collapse(i, false);
							changeMade = true;
						} else if (itemState == 1){
							uix.collapsible.expand(i, false);
						}
					}
				}) (i); 
			}
		}
		if (changeMade) uix.sidebarSticky.resize();
		uix.collapsible.needsInit = false;
	},

	toggle: function(i){
		var item = uix.collapsible.items[i];
		if (item.state == 1) {
			uix.collapsible.collapse(i, true);
		} else {
			uix.collapsible.expand(i, true);
		}
	},

	collapse: function(i, setCookie){
		uix.collapsible.items[i].state = 0;
		if (setCookie) {
			if (uix.sidebarSticky.running) var stickyUpdate = window.setInterval(function() {
				uix.sidebarSticky.resize();
			}, 16);
			if (typeof(uix.collapsible.items[i].child) !== "undefined"){
				$(uix.collapsible.items[i].child).slideUp(400, function() {
					if (uix.sidebarSticky.running) {
						window.clearInterval(stickyUpdate);
						if (!uix.collapsible.needsInit) uix.sidebarSticky.resize();
					}
				});
			}
			uix.collapsible.items[i].defaultVal = false;
			uix.collapsible.updateCookie();
		} else {
			if (typeof(uix.collapsible.items[i].child) !== "undefined") uix.collapsible.items[i].child.style.display = "none";
		}
		uix.collapsible.items[i].ele.className += (" collapsed");
	},

	expand: function(i, setCookie){
		uix.collapsible.items[i].state = 1;
		if (setCookie) {
			if (uix.sidebarSticky.running) var stickyUpdate = window.setInterval(function() {
				uix.sidebarSticky.resize();
			}, 16);
			if (typeof(uix.collapsible.items[i].child) !== "undefined"){
				$(uix.collapsible.items[i].child).slideDown(400, function() {
					if (uix.sidebarSticky.running) {
						window.clearInterval(stickyUpdate);
						if (!uix.collapsible.needsInit) uix.sidebarSticky.resize();
					}
				});
			}
			uix.collapsible.items[i].defaultVal = false;
			uix.collapsible.updateCookie();
		} else {
			if (typeof(uix.collapsible.items[i].child) !== "undefined") uix.collapsible.items[i].child.style.display = "";

		}
		uix.collapsible.items[i].ele.className = uix.collapsible.items[i].ele.className.replace(" collapsed", "");
	},

	updateCookie: function(){
		var retCol = "";
		var retExp = "";
		for (var i = 0, len = uix.collapsible.items.length; i < len; i++){
			var item = uix.collapsible.items[i];
			if (item.defaultVal == false){
				if (item.state == 0 && !item.defaultCollapsed){
					retCol += item.label + ","
				} else if (item.state == 1){
					retExp += item.label + ","
				}
			}
		}
		$.setCookie("uix_collapsedNodes", retCol);
		$.setCookie("uix_expandedNodes", retExp);
	},

	resetCookies: function(){
		$.setCookie("uix_collapsedNodes", "");
		$.setCookie("uix_expandedNodes", "");
	},

}




// #######################################################################################
// #																					 #
// #								 UIX Userbar Functions								 #
// #																					 #
// #######################################################################################

uix.userBar = {
	state: 0,
	enableHiding: true,
	needsInit: true,
	ele: null,

	init: function(){
		uix.userBar.ele = $('#userBar');
		if (uix.userBar.ele.length){
			if ($('.moderatorTabs', uix.userBar.ele).length) {
				uix.userBar.enableHiding = false
			} else if ($('#QuickSearch', uix.userBar.ele).length) {
				uix.userBar.enableHiding = false
			} else {
				var visitorTabs = $('.navRight li', uix.userBar.ele)
				for (var i = 0, len = visitorTabs.length; i < len; i++){
					if ((visitorTabs[i].className.indexOf("account") == -1 && visitorTabs[i].className.indexOf("inbox") == -1 && visitorTabs[i].className.indexOf("alert") == -1) || visitorTabs[i].className.indexOf("uix_offCanvas_trigger") > -1) uix.userBar.enableHiding = false
				}
			}
		} else {
			uix.userBar.enableHiding = false;
		}

		if (uix.userBar.enableHiding){
			if (typeof(uix.stickyItems['#userBar']) !== "undefined" && uix.stickyItems['#userBar'].options.minWidth < uix.offCanvasTriggerWidth) uix.stickyItems['#userBar'].options.minWidth = uix.offCanvasTriggerWidth; // disable sticky userbar
		}

		uix.userBar.needsInit = false;
	},

	check:function(){
		if (uix.userBar.needsInit) uix.userBar.init()

		if (uix.userBar.enableHiding){
			if (uix.windowWidth <= uix.offCanvasTriggerWidth){
				if (uix.userBar.state == 1) {
					uix.userBar.ele.css({'display': ''});
					uix.userBar.state = 0;
				}
			} else {
				if (uix.userBar.state == 0) {
					uix.userBar.ele.css({'display': 'block'});
					uix.userBar.state = 1;
				}
			}
		} else {
			if (uix.userBar.state == 0) {
				uix.userBar.ele.css({'display': 'block'});
				uix.userBar.state = 1;
			}
		}
	}


}


// #######################################################################################
// #																					 #
// #							 UIX Unordered List Functions							 #
// #																					 #
// #######################################################################################


uix.ulManager = {
	needsInit: true,
	items: [],
	leftClass: "uix_leftMost",
	rightClass: "uix_rightMost",

	init: function(){
		var parents = [document.getElementById("navigation"), document.getElementById("userBar")];

		for (var i = 0, len1 = parents.length; i < len1; i++){
			if (typeof(parents[i]) !== "undefined" && parents[i] !== null) {
				var uls = parents[i].getElementsByTagName("ul");
				for (var j = 0, len2 = uls.length; j < len2; j++){
					if (typeof(uls[j]) !== "undefined"){
						var className = uls[j].className
						if (className.indexOf("navLeft") > -1 || className.indexOf("navRight") > -1) {
							var children = [];
							var childSel = uls[j].children;

							for (var k = 0, len3 = childSel.length; k < len3; k++){
								if (childSel[k].tagName == "LI") children.push(childSel[k]);
							}

							uix.ulManager.items.push({
								ele: uls[j],
								children: children,
								first: -1,
								firstOld: -1,
								last: -1,
								lastOld: -1,
							})
						}
					}
				}
			}
		}

		uix.ulManager.needsInit = false;
	},

	checkGet: function(){
		if (uix.ulManager.needsInit) uix.ulManager.init();

		for (var i = 0, len1 = uix.ulManager.items.length; i < len1; i++){
			var item = uix.ulManager.items[i];
			for (var j = 0, len2 = item.children.length; j < len2; j++){
				if (window.getComputedStyle(item.children[j]).display.indexOf("none") === -1){
					uix.ulManager.items[i].firstOld = uix.ulManager.items[i].first;
					uix.ulManager.items[i].first = j;
					break;
				}
			}

			for (var j = item.children.length - 1; j >= 0; j--){
				if (window.getComputedStyle(item.children[j]).display.indexOf("none") === -1){
					uix.ulManager.items[i].lastOld = uix.ulManager.items[i].last;
					uix.ulManager.items[i].last = j;
					break;
				}
			}
		}
	},

	checkSet: function(){
		for (var i = 0, len1 = uix.ulManager.items.length; i < len1; i++){
			if (uix.ulManager.items[i].first != uix.ulManager.items[i].firstOld){
				if (typeof(uix.ulManager.items[i].children[uix.ulManager.items[i].firstOld]) !== "undefined") uix.ulManager.items[i].children[uix.ulManager.items[i].firstOld].className = uix.ulManager.items[i].children[uix.ulManager.items[i].firstOld].className.replace(" " + uix.ulManager.leftClass, "");
				if (typeof(uix.ulManager.items[i].children[uix.ulManager.items[i].first]) !== "undefined") uix.ulManager.items[i].children[uix.ulManager.items[i].first].className += " " + uix.ulManager.leftClass;
			}

			if (uix.ulManager.items[i].last != uix.ulManager.items[i].lastOld){
				if (typeof(uix.ulManager.items[i].children[uix.ulManager.items[i].lastOld]) !== "undefined") uix.ulManager.items[i].children[uix.ulManager.items[i].lastOld].className = uix.ulManager.items[i].children[uix.ulManager.items[i].lastOld].className.replace(" " + uix.ulManager.rightClass, "");
				if (typeof(uix.ulManager.items[i].children[uix.ulManager.items[i].last]) !== "undefined") uix.ulManager.items[i].children[uix.ulManager.items[i].last].className += " " + uix.ulManager.rightClass;
			}
		}
	},

	check: function(){
		uix.ulManager.checkGet();
		uix.ulManager.checkSet();
	}
}


// #######################################################################################
// #																					 #
// #									UIX Functions									 #
// #																					 #
// #######################################################################################

uix.fn.getKeys = function(obj){
	if (typeof(obj) === "object") return Object.keys(obj);
	return null;
}

uix.fn.jumpToFixed = function() {
	var pfTime = uix.time();
	if (uix.jumpToFixed_delayHide && uix.jumpToScrollTimer) {
		clearTimeout(uix.jumpToScrollTimer); // clear any previous pending timer
		clearTimeout(uix.jumpToScrollHideTimer);
	}

	if (uix.scrollTop > 140) {
		if (uix.jumpToFixedOpacity == 0) {
			window.requestAnimationFrame(function(){
				uix.elm.jumpToFixed.css({
					'display': 'block'
				});
				uix.jumpToFixedOpacity = 1;
				window.requestAnimationFrame(function(){
					uix.elm.jumpToFixed.css({
						'opacity': '1'
					});
				});
			});
		}
		if (uix.jumpToFixed_delayHide == 1) {
			uix.jumpToScrollTimer = setTimeout(function() {
				window.requestAnimationFrame(function(){
					uix.jumpToScrollTimer = null;
					uix.jumpToFixedOpacity = 0;
					uix.elm.jumpToFixed.css({
						'opacity': '0'
					});
					uix.jumpToScrollHideTimer = window.setTimeout(function() {
						window.requestAnimationFrame(function(){
							if (uix.jumpToFixedOpacity == 0) uix.elm.jumpToFixed.css({
								'display': 'none'
							});
						})
					}, 450);
				})
			}, 1500); // set new timer
		}
	} else {
		if (uix.jumpToFixedOpacity != 0) {
			uix.jumpToFixedOpacity = 0;
			uix.elm.jumpToFixed.css({
				'opacity': '0'
			});
		}
		uix.jumpToScrollHideTimer = window.setTimeout(function() {
			window.requestAnimationFrame(function(){
				if (uix.jumpToFixedOpacity == 0) uix.elm.jumpToFixed.css({
					'display': 'none'
				});
			})
		}, 450);
	}
	uix.tStamp("UIX.fn.jumpToFixed", 2, pfTime);
}

uix.fn.updateSidebar = function() {
	if (uix.windowWidth > uix.sidebarMaxResponsiveWidth) {
		uix.elm.sidebar.css({
			'width': uix.sidebarWidth,
			'float': uix.sidebar_innerFloat
		});
	} else {
		uix.elm.sidebar.css({
			'width': '',
			'float': ''
		});
		if (uix.sidebarSticky.length && uix.sidebarSticky.innerWrapper.length) {
			uix.sidebarSticky.innerWrapper.css({
				'width': '',
				'top': ''
			});
		}
	}
}

uix.fn.scrollToAnchor = function(href) {

	href = typeof(href) == "string" ? href : $(this).attr("href");
	if (!href) return;

	var $target = $("a[href='" + href + "']");
	if ($target.length) {
		uix.sticky.preventStickyTop = true;
		var topOffset = $target.offset().top
		$('html, body').animate({
			scrollTop: topOffset - uix.sticky.getOffset(topOffset)
		}, XenForo.speed.normal, 'swing', function() {
			uix.sticky.preventStickyTop = false;
			uix.sticky.check();
		});

		if (history && "pushState" in history) {
			history.pushState({}, document.title, window.location.pathname + window.location.search + href.replace("index.php", ""));
			return false;
		}
	}
}


uix.fn.postSearhMinimalShow = function(){
    document.getElementById('uix_searchMinimalInput').children[0].focus();
    uix.searchMinimalState = 1;
    $('#uix_searchMinimal').addClass('show')
}

uix.fn.postSearchMinimalHide = function(){
    uix.searchMinimalState = 0;
    $('#uix_searchMinimal').removeClass('show');
    var withSearch = $(".withSearch");
    if (withSearch.length) withSearch.removeClass('uix_searchMinimalActive');
}

uix.fn.searchClick = function() {
    var withSearch = $(".withSearch");
    if (withSearch.length){
        if (uix.searchMinimalState <= 0){
            withSearch.addClass('uix_searchMinimalActive');
        }
    }

	if (uix.searchPosition == 1) {
		if (uix.searchMinimalState <= 0) {
			window.requestAnimationFrame(function(){
				$('#QuickSearchPlaceholder').css({
					'transition': 'opacity 0.3s'
				});
				window.requestAnimationFrame(function(){
					$('#QuickSearchPlaceholder').css({
						'opacity': 0
					});
					window.setTimeout(function() {
						window.requestAnimationFrame(function(){
							$('#QuickSearchPlaceholder').css({
								'display': 'none'
							});
							$('#uix_searchMinimal').css({
								'display': 'block',
								'width' : 'auto',
							});
							window.setTimeout(function() {
								window.requestAnimationFrame(function(){
									$('#uix_searchMinimal').css({
										'opacity': 1
									});
									uix.fn.postSearhMinimalShow();
								})
							}, 300)
						});
					}, 300)
				});
			});
		} else {
			window.requestAnimationFrame(function(){
				$('#uix_searchMinimal').css({
					'opacity': 0
				});
				window.setTimeout(function() {
					window.requestAnimationFrame(function(){
						$('#uix_searchMinimal').css({
							'display': '',
							'opacity': '',
							'width' : '',
						});
						$('#QuickSearchPlaceholder').css({
							'display': '',
						});
						window.setTimeout(function() {
							window.requestAnimationFrame(function(){
								$('#QuickSearchPlaceholder').css({
									'opacity': '',
									'transition': ''
								});
								uix.fn.postSearchMinimalHide();
							})
						}, 300)
					})
				}, 300)
			})
		}
	} else if (uix.searchPosition == 4) {
		if (uix.searchMinimalState <= 0) {
			window.requestAnimationFrame(function(){
				$('.uix_breadCrumb_toggleList, .breadBoxTop .breadcrumb, .breadBoxTop .topCtrl').css({
					'transition': 'opacity 0.3s'
				});

				window.requestAnimationFrame(function(){
					$('.breadBoxTop .breadcrumb, .uix_breadCrumb_toggleList, .breadBoxTop .topCtrl').css({
						'opacity': 0
					});
					window.setTimeout(function() {
						window.requestAnimationFrame(function(){
							$('#QuickSearchPlaceholder, .breadBoxTop .breadcrumb, .breadBoxTop .topCtrl, .uix_sidebar_collapse.toggleList_item').css({
								'display': 'none'
							});
							$('.uix_breadCrumb_toggleList').css({
								'float': 'none'
							})
							$('.uix_breadCrumb_toggleList li.toggleList_item').css({
								'float': 'none',
								'margin': 0
							})
							$('#uix_searchMinimal').css({
								'width': '100%',
								'display': 'block',
								'opacity': 1
							});
							$('.uix_breadCrumb_toggleList').css({
								'opacity': 1
							});
							window.setTimeout(function() {
								window.requestAnimationFrame(function(){
									uix.fn.postSearhMinimalShow();
								})
							}, 300)
						})
					}, 300)
				});
			});
		} else {
			window.requestAnimationFrame(function(){
				$('.breadBoxTop .breadcrumb, .uix_breadCrumb_toggleList, .breadBoxTop .topCtrl').css({
					'opacity': 0
				});

				window.setTimeout(function() {
					window.requestAnimationFrame(function(){
						$('#QuickSearchPlaceholder, .breadBoxTop .breadcrumb, .breadBoxTop .topCtrl, .uix_sidebar_collapse.toggleList_item').css({
							'display': ''
						});
						$('.uix_breadCrumb_toggleList').css({
							'float': ''
						})
						$('.uix_breadCrumb_toggleList li.toggleList_item').css({
							'float': '',
							'margin': ''
						})
						$('#uix_searchMinimal').css({
							'width': '',
							'display': '',
							'opacity': ''
						});
						$('.breadBoxTop .breadcrumb, .uix_breadCrumb_toggleList, .breadBoxTop .topCtrl').css({
							'opacity': 1
						});
						window.setTimeout(function() {
							window.requestAnimationFrame(function(){
								$('.uix_breadCrumb_toggleList, .breadBoxTop .breadcrumb, .breadBoxTop .topCtrl').css({
									'opacity': '',
									'transition': ''
								});
								uix.fn.postSearchMinimalHide();
							})
						}, 300)
					})
				}, 350)
			});
		}
	} else if (!uix.elm.html.hasClass('csstransforms')) {
		if (uix.searchMinimalState == -1) {
			uix.searchMinimalState = 0;
		}
		if (uix.searchMinimalState == 0) {
			$('#uix_searchMinimal').css({
				'display': 'inline-block',
				'width': '100%',
				'opacity': 1
			});
			for (var i = 0, len = uix.searchEles.length ; i < len ; i++) {
				uix.searchEles[i][0].css({
					'display': 'none'
				});
			}
			uix.fn.postSearhMinimalShow();
		} else {
			for (var i = 0, len = uix.searchEles.length; i < len; i++) {
				uix.searchEles[i][0].css({
					'display': ''
				});
			}
			$('#uix_searchMinimal').css({
				'display': '',
				'width': '',
				'opacity': ''
			});
			uix.fn.postSearchMinimalHide();
		}
	} else {
		var searchParent = $('#searchBar').parent();
		window.requestAnimationFrame(function(){
			if (uix.searchMinimalState == -1) {
				for (var i = 0, len = uix.searchEles.length; i < len; i++) {
					uix.searchEles[i][0].css({
						'transition': 'width 0.3s, opacity 0.3s'
					});
				}
				uix.searchMinimalState = 0;
			}
			if (uix.searchMinimalState == 0) {
				for (var i = 0, len = uix.searchEles.length; i < len; i++) {
					uix.searchEles[i][1] = uix.searchEles[i][0].outerWidth();
				}
				for (var i = 0, len = uix.searchEles.length; i < len; i++) {
					uix.searchEles[i][0].css({
						'opacity': '1',
						'width': uix.searchEles[i][1] + 'px'
					});
				}
				searchParent.css('overflow', 'hidden')
			}

			window.requestAnimationFrame(function(){
				if (uix.searchMinimalState == 0) {
					for (var i = 0, len = uix.searchEles.length; i < len; i++) {
						uix.searchEles[i][0].css({
							'overflow': 'hidden',
							'opacity': '0',
							'width': '0'
						});
					}
					
					window.setTimeout(function() {
						window.requestAnimationFrame(function(){
							$('#uix_searchMinimal').css('display', 'inline-block');
							window.requestAnimationFrame(function(){
								$('#uix_searchMinimal').css({
									'width': '100%',
									'opacity': '1',
									'position': 'relative'
								});
							})
						})
					}, 250);

					window.setTimeout(function() {
						window.requestAnimationFrame(function(){
							for (var i = 0, len = uix.searchEles.length; i < len; i++) {
								uix.searchEles[i][0].css({
									'display': 'none'
								});
							}
							uix.fn.postSearhMinimalShow();
						});

						$('#navigation .publicTabs, #navigation .visitorTabs').css({
							'width': 0,
							'border': 'none'
						})
					}, 600);
				} else {
					window.requestAnimationFrame(function(){
						$('#uix_searchMinimal').css({
							'width': '0',
							'opacity': '0'
						});
						window.setTimeout(function() {
							window.requestAnimationFrame(function(){
								$('#navigation .publicTabs, #navigation .visitorTabs').css({
									'width': '',
									'border': ''
								})

								for (var i = 0, len = uix.searchEles.length; i < len; i++) {
									uix.searchEles[i][0].css({
										'display': ''
									});
								}
								window.requestAnimationFrame(function(){
									for (var i = 0, len = uix.searchEles.length; i < len; i++) {
										uix.searchEles[i][0].css({
											'opacity': '1',
											'width': uix.searchEles[i][1] + 'px'
										});
									}
									window.setTimeout(function() {
										window.requestAnimationFrame(function(){
											$('#uix_searchMinimal').css('display', '');
											
											window.setTimeout(function(){
												window.requestAnimationFrame(function(){
													for (var i = 0, len = uix.searchEles.length; i < len; i++) {
														uix.searchEles[i][0].css({
															'transition': 'width 0s, opacity 0s'
														});
													}
												})
											}, 0)

											window.setTimeout(function(){
												window.requestAnimationFrame(function(){
													for (var i = 0, len = uix.searchEles.length; i < len; i++) {
														uix.searchEles[i][0].css({
															'overflow': '',
															'opacity': '',
															'width': ''
														});
													}
												})
											}, 50)

											window.setTimeout(function(){
												window.requestAnimationFrame(function(){
													for (var i = 0, len = uix.searchEles.length; i < len; i++) {
														uix.searchEles[i][0].css({
															'transition': 'width 0.3s, opacity 0.3s'
														});
													}
												})
											}, 100)

											searchParent.css('overflow', '')
											uix.fn.postSearchMinimalHide();
											if (uix.searchPosition == 2) {
												if (!uix.initDone) {
													uix.updateNavTabs = true;
												} else {
													uix.navTabs();
												}
											}
										})
									}, 350);
								})
							});
						}, 250)
					});
				}
			});
		});
	}
}

uix.fn.syncBaloon = function($source, $dest) {
	if ($source.length && $dest.length) {
		var $sourceCounter = $source.find('span.Total'),
			total = $sourceCounter.text(),
			$destCounter = $dest.find('span.Total'),
			oldTotal = $destCounter.text();

		if (total != oldTotal) {
			$destCounter.text(total);

			if (total == '0') {
				$dest.fadeOut('fast', function() {
					$dest.addClass('Zero').css('display', '');
				});
			} else {
				$dest.fadeIn('fast', function() {
					$dest.removeClass('Zero').css('display', '');
				});
			}
		}
	}
}

uix.hideDropdowns = {
	eles: null,

	init: function(){
		uix.hideDropdowns.eles = $('body > .Menu');
	},

	hide: function(){
		uix.hideDropdowns.init();
		if (uix.hideDropdowns.eles !== null) uix.hideDropdowns.eles.css('display', 'none');
	},
}

uix.fn.mainSidebarResize = function() {
	var pfTime = uix.time();

	var origSidebarMargin = 0;

	if (XenForo.RTL) {
		var marginDirection = (uix.elm.sidebar.hasClass('uix_mainSidebar_left')) ? 'marginRight' : 'marginLeft'
	} else {
		var marginDirection = (uix.elm.sidebar.hasClass('uix_mainSidebar_left')) ? 'marginLeft' : 'marginRight'
	}
		
	uix.elm.mainContent.css('transition', 'margin 0s');

	if (uix.mainContainerMargin.length) origSidebarMargin = uix.mainContainerMargin;

	if (uix.windowWidth <= uix.sidebarMaxResponsiveWidth) {
		uix.elm.mainContent.css({
			'marginRight': 0,
			'marginLeft': 0
		});
	} else if (uix.sidebarVisible == 1) {
		uix.elm.mainContent.css(marginDirection, origSidebarMargin);
	}
	window.setTimeout(function() {
		window.requestAnimationFrame(function(){
			uix.elm.mainContent.css('transition', 'margin 0.4s ease-out');
		});
	}, 400)

	uix.tStamp("UIX.fn.mainSidebarResize", 2, pfTime);
}



// #######################################################################################
// #																					 #
// #							 UIX Initialization Functions							 #
// #																					 #
// #######################################################################################


uix.init.welcomeBlock = function() {
	var pfTime = uix.time();
	if (uix.elm.welcomeBlock.length && uix.elm.welcomeBlock.hasClass('uix_welcomeBlock_fixed')) {

		if ($.getCookie('uix_hiddenWelcomeBlock') == 1) {
			if (uix.reinsertWelcomeBlock) {
				uix.elm.welcomeBlock.removeClass('uix_welcomeBlock_fixed');
			}
		} else {
			uix.elm.welcomeBlock.css({
				'display': 'block'
			});
		}

		uix.elm.welcomeBlock.find('.close').on('click', function(e) {
			e.preventDefault();
			$.setCookie('uix_hiddenWelcomeBlock', 1);
			window.requestAnimationFrame(function(){
				uix.elm.welcomeBlock.css({
					'opacity': '0'
				});
				window.setTimeout(function() {
					window.requestAnimationFrame(function(){
						if (uix.reinsertWelcomeBlock) {
							uix.elm.welcomeBlock.removeClass('uix_welcomeBlock_fixed');
							uix.elm.welcomeBlock.css('opacity', 1);
							if (uix.sidebarSticky.running) uix.sidebarSticky.update();
						} else {
							uix.elm.welcomeBlock.css({
								'display': 'none'
							});
						}
					});
				}, 500);
			});
		});
	}

	uix.tStamp("UIX.init.welcomeBlock", 1, pfTime);
};

uix.init.setupAdminLinks = function() {
	var modTabs = $('.moderatorTabs');
	if (modTabs.length) {
		var pfTime = uix.time();
		modTabs.children().each(function() {
			var $this = $(this);
			if ($this.is('a')) $this.addClass('navLink');
			if (!$this.is('li')) $this.wrap('<li class="navTab" />');
		});

		$('.uix_adminMenu .blockLinksList').children().each(function() {
			var $this = $(this);
			if ($this.is('a')) $this.addClass('navLink');
			if (!$this.is('li')) $this.wrap('<li class="navTab" />');
		});

		if ($('.admin', modTabs).length) {
			var
				$itemCounts = $('.uix_adminMenu').find('.itemCount'),
				adminListTotal = 0,
				adminListTotalUnread = 0;

			$itemCounts.each(function() {
				var $this = $(this)
				adminListTotal += parseInt($this.text(), 10);
				if ($this.hasClass('alert')) adminListTotalUnread = 1;
			});

			if (adminListTotal > 0) {
				$('.admin .itemCount', modTabs).removeClass('Zero').find('.Total').text(adminListTotal);
				if (adminListTotalUnread) $('.admin .itemCount', modTabs).addClass('alert');
			}
		}
		uix.tStamp("UIX.init.setupAdminLinks", 1, pfTime);
	}
}

uix.init.jumpToFixed = function() {
	var pfTime = uix.time();
	var scrollToThe = function(pos) {
		var pfTime2 = uix.time();
		if (pos == 'bottom') {
			$('html, body').stop().animate({
				scrollTop: $(document).height()
			}, 400);
		} else {
			uix.sticky.preventStickyTop = true; // stop sliding sticky from sticking
			$('html, body').stop().animate({
				scrollTop: 0
			}, 400);
			window.setTimeout(function() {
				uix.sticky.preventStickyTop = false;
			}, 400); // reallow sliding sticky to stick
		}
		uix.tStamp("UIX.init.jumpToFixed", 2, pfTime2);
		return false;
	};

	var jumpToFixed = uix.elm.jumpToFixed;

	$('.topLink').on('click', function() {
		scrollToThe('top')
	});

	if (jumpToFixed.length) {
		jumpToFixed.find('a').on('click', function(e) {
			e.preventDefault();
			scrollToThe($(this).data('position'))
		});

		if (uix.jumpToFixed_delayHide) {
			jumpToFixed.hover(
				function() {
					clearTimeout(uix.jumpToScrollTimer);
					clearTimeout(uix.jumpToScrollHideTimer);
					window.requestAnimationFrame(function(){
						jumpToFixed.css({
							'display': 'block'
						});
						uix.jumpToFixedOpacity = 1;
						window.requestAnimationFrame(function(){
							jumpToFixed.css({
								'opacity': '1'
							});
						});
					});
				},
				function() {
					window.requestAnimationFrame(function(){
						uix.jumpToFixedOpacity = 0;
						jumpToFixed.css({
							'opacity': '0'
						});
						uix.jumpToScrollHideTimer = window.setTimeout(function() {
							window.requestAnimationFrame(function(){
								if (uix.jumpToFixedOpacity == 0) jumpToFixed.css({
									'display': 'none'
								});
							});
						}, 450);
					});
				}
			);
		}
		uix.jumpToFixedRunning = true;
	}
	uix.tStamp("UIX.init.jumpToFixed", 1, pfTime);
}

uix.init.fixScrollLocation = function() {
	var hash = document.location.hash
	if (hash.length > 2 && uix.sticky.scrollDetectorRunning == false) {
		var pfTime = uix.time();
		var $target = $(hash);
		if ($target.length) {
			var topOffset = $target.offset().top
			var newScroll = topOffset - uix.sticky.getOffset(topOffset);
			window.scrollTo(0, newScroll);
		}
		uix.tStamp("UIX.init.fixScrollLocation", 1, pfTime);
	}
}


uix.init.mainSidebar = function() {
	var pfTime = uix.time();
	var
		mainSidebar = uix.elm.sidebar,
		sidebarCollapse = $('.uix_sidebar_collapse'),
		sidebarCollapsePhrase = $('.uix_sidebar_collapse_phrase');

	if (mainSidebar.length) {
		if (uix.collapsibleSidebar && uix.canCollapseSidebar == "1") {
			if (XenForo.RTL) {
				var marginDirection = (mainSidebar.hasClass('uix_mainSidebar_left')) ? 'marginRight' : 'marginLeft'
			} else {
				var marginDirection = (mainSidebar.hasClass('uix_mainSidebar_left')) ? 'marginLeft' : 'marginRight'
			}

			var origSidebarMargin = 0;

			if (uix.mainContainerMargin.length) origSidebarMargin = uix.mainContainerMargin;

			sidebarCollapse.find('a').on('click', function(e) {
				e.preventDefault();
				if (uix.sidebarVisible == 1) {
					if (typeof(uix.sidebarCookieExpire) === "undefined" || parseInt(uix.sidebarCookieExpire) <= 0 || uix.sidebarCookieExpire == "") {
						$.setCookie("uix_collapsedSidebar", 1);
					} else {
						$.setCookie("uix_collapsedSidebar", 1, (Date.now() + (86400 * parseInt(uix.sidebarCookieExpire))));
					}
					window.requestAnimationFrame(function(){
						sidebarCollapse.addClass('uix_sidebar_collapsed');
						sidebarCollapsePhrase.text(uix.collapsibleSidebar_phrase_open);

						mainSidebar.css({
							'opacity': '0'
						});

						window.requestAnimationFrame(function(){
							if (uix.windowWidth > uix.sidebarMaxResponsiveWidth) {
								window.setTimeout(function() {
									window.requestAnimationFrame(function(){
										uix.sidebarVisible = 0;
										mainSidebar.css({
											'display': 'none',
										});
										uix.elm.mainContent.css(marginDirection, 0);
										window.setTimeout(function() {
											window.requestAnimationFrame(function(){
												if (uix.sidebarSticky.running) uix.sidebarSticky.unstick();
												window.dispatchEvent(new Event('resize'));
											});
										}, 400); // fix notices
									});
								}, 400);
							} else {
								//mainSidebar.hide();
								//uix.elm.mainContent.css(marginDirection, 0);
							}
						})
					});
				} else {
					$.setCookie("uix_collapsedSidebar", 0);
					sidebarCollapse.removeClass('uix_sidebar_collapsed');
					sidebarCollapsePhrase.text(uix.collapsibleSidebar_phrase_close);
					var stickyCondition = (uix.stickySidebar && uix.windowWidth > uix.sidebarMaxResponsiveWidth && !uix.sidebarSticky.running && !uix.stickyForceDisable && uix.user.stickyEnableSidebar);

					if (uix.sidebarSticky.running) uix.sidebarSticky.unstick();
					window.requestAnimationFrame(function(){
						mainSidebar.css({
							'opacity': '0'
						}); // on refresh, still want to be 0 so it can fade in
						window.requestAnimationFrame(function(){
							if (uix.windowWidth > uix.sidebarMaxResponsiveWidth) {
								uix.elm.mainContent.css(marginDirection, origSidebarMargin)
								window.setTimeout(function() {
									window.requestAnimationFrame(function(){
										mainSidebar.css({
											'display': 'block',
										});
										window.requestAnimationFrame(function(){
											mainSidebar.css({
												'opacity': '1'
											});
											window.setTimeout(function() {
												window.dispatchEvent(new Event('resize'));
												uix.sidebarVisible = 1;
												if (stickyCondition) uix.sidebarSticky.init();
											}, 400); // fix notices
										})
									})
								}, 400);
							} else {

								mainSidebar.css({
									'display': 'block',
									'opacity': '1'
								});
								uix.elm.mainContent.css(marginDirection, 0);
								window.dispatchEvent(new Event('resize'));
							}
						});
					});
				}
			});

			if ($.getCookie('uix_collapsedSidebar') == 1) {
				uix.sidebarVisible = 0
				window.requestAnimationFrame(function(){
					sidebarCollapse.addClass('uix_sidebar_collapsed');
					sidebarCollapsePhrase.text(uix.collapsibleSidebar_phrase_open);
					mainSidebar.hide();
					uix.elm.mainContent.css(marginDirection, 0);
					window.dispatchEvent(new Event('resize'));
					window.setTimeout(function() {
						window.requestAnimationFrame(function(){
							uix.elm.mainContent.css('transition', 'margin 0.4s ease-out');
						});
					}, 400)
				});
			} else {
				window.requestAnimationFrame(function(){
					uix.elm.mainContent.css('transition', 'margin 0.4s ease-out');
				});
			}
			
		} else {
			//$.setCookie("uix_collapsedSidebar", 0);
		}
		uix.fn.updateSidebar() //updates sidebar css 
	}
	uix.tStamp("UIX.init.mainSidebar", 1, pfTime);
};




uix.init.offcanvas = function() {
	var pfTime = uix.time();

	$('.uix_sidePane .navTab.selected').addClass('active');
	var liHeight = $('.uix_sidePane .navTab .blockLinksList li').height();
	$('.uix_sidePane .navTab.selected .subMenu').css('maxHeight', ($('.blockLinksList li', $('.uix_sidePane .navTab.selected')).length * liHeight) + 'px')
	$('.uix_sidePane .SplitCtrl').on('click', function(e) {
		if ($(e.target).closest('.navTab').hasClass('active')) {
			$('.uix_sidePane .navTab').removeClass('active');
			$('.uix_sidePane .navTab .subMenu').css('maxHeight', '')
		} else {
			$('.uix_sidePane .navTab').removeClass('active');
			$('.uix_sidePane .navTab .subMenu').css('maxHeight', '')
			$(e.target).closest('.navTab').addClass('active');
			$('.subMenu', $(e.target).closest('.navTab')).css('maxHeight', ($('.blockLinksList li', $(e.target).closest('.navTab')).length * liHeight) + 'px')
		}
		return false;
	});

	uix.offcanvas = {};
	uix.offcanvas.wrapper = $('.off-canvas-wrapper');
	uix.offcanvas.exit = $('.exit-off-canvas');
	uix.offcanvas.move = function(direction) {
		window.requestAnimationFrame(function(){
			uix.offcanvas.exit.css({
				display: 'block',
			});
			window.requestAnimationFrame(function(){
				uix.offcanvas.wrapper.addClass('move-' + direction);
				uix.hideDropdowns.hide();
			})
		})
	},

	uix.offcanvas.reset = function() {
		window.requestAnimationFrame(function(){
			uix.offcanvas.wrapper.removeClass('move-right').removeClass('move-left');
			window.setTimeout(function(){
				window.requestAnimationFrame(function(){
					uix.offcanvas.exit.css({
						display: '',
					});
				})
			}, 500)
		})
	};

	$('#uix_paneTriggerLeft').on('click', function() {
		if (XenForo.isRTL()) {
			uix.offcanvas.move('left');
		} else {
			uix.offcanvas.move('right');
		}
		return false;
	});
	$('#uix_paneTriggerRight').on('click', function() {
		if (XenForo.isRTL()) {
			uix.offcanvas.move('right');
		} else {
			uix.offcanvas.move('left');
		}
		return false;
	});

	uix.offcanvas.exit.on('click', function() {
		uix.offcanvas.reset();
		return false;
	});

	uix.tStamp("UIX.init.offCanvas", 1, pfTime);
}



uix.init.offCanvasVisitor = function() {
	window.setInterval(function(){
		uix.fn.syncBaloon($('#VisitorExtraMenu_Counter'), $('#uix_VisitorExtraMenu_Counter'));
		uix.fn.syncBaloon($('#ConversationsMenu_Counter'), $('#uix_ConversationsMenu_Counter'));
		uix.fn.syncBaloon($('#AlertsMenu_Counter'), $('#uix_AlertsMenu_Counter'));
	}, 3000);
}


uix.init.scrollFunctions = function() {
	var scrollTimer = null,
		endScroll = null,
		lastScroll = -1;
		newScroll = false,

	$(window).on('scroll', function(e) {
		if (uix.initDone) scrollEvent();
	});
	
	function scrollEvent() {
		newScroll = true;
		if (scrollTimer == null) {
			scrollTimer = window.requestAnimationFrame(function(){
				scrollUpdate();
				scrollTimer = null;
				window.clearTimeout(endScroll);

				endScroll = setTimeout(function() {
					newScroll = false;
					momentumScroll(0);
				}, 50); // check for any momentum scrolling
			}); // set new timer
		}
	}

	function momentumScroll(depth) {
		if (depth < 6 && newScroll == false) {
			window.setTimeout(function(){
				window.requestAnimationFrame(function(){
					scrollUpdate();
					momentumScroll(depth + 1) 
				})
			}, 100)
		}
	}

	function scrollUpdate() {
		var pfTime = uix.time();
		if (uix.viewport.running) uix.viewport.get();

		//if (lastScroll != uix.scrollTop){
			if (uix.sticky.running) uix.sticky.checkGet();
			if (uix.smallLogo.running) uix.smallLogo.get();

			if (uix.viewport.running) uix.viewport.set();
			if (uix.sticky.running) uix.sticky.checkSet();
			if (uix.sidebarSticky.running) uix.sidebarSticky.check();
			if (uix.jumpToFixedRunning) uix.fn.jumpToFixed();

			lastScroll = uix.scrollTop;
		//}
		uix.tStamp("UIX.scrollUpdate", 1, pfTime);
	}
}

uix.init.resizeFunctions = function() {
	var resizeTimer = null,
		endSResize = null;

	$(window).on('resize orientationchange', function(e) {
		if (uix.initDone) resizeEvent();
	});

	function resizeEvent() {
		if (resizeTimer == null) {
			resizeTimer = window.requestAnimationFrame(function(){
				resizeUpdate();
				resizeTimer = null;
				window.clearTimeout(endSResize);
			}); // set new timer
		} else {
			endSResize = setTimeout(function() {
				window.requestAnimationFrame(function(){
					resizeUpdate();
				});
			}, 250); // fire one last resize event
		}
	}

	function resizeUpdate() {
		var pfTime = uix.time();
		uix.viewport.init(); //update viewport variables

		uix.toggleWidth.resize();

		if (uix.enableBorderCheck) uix.checkRadius.resize();

		if (uix.offCanvasSidebar) uix.userBar.check();

		if (uix.sticky.running) uix.sticky.resize();
		if (uix.enableBorderCheck) uix.checkRadius.check(true);
		if (audentio.grid.running) audentio.grid.update();
		if (uix.elm.sidebar.length) {
			uix.fn.mainSidebarResize();
			uix.fn.updateSidebar(); //updates sidebar css
		}
		uix.sidebarSticky.resize(); //update stickysidebar position

		if (uix.smallLogo.running) uix.smallLogo.resize();

		if (uix.enableBorderCheck) uix.ulManager.check();

		if (audentio.pagination.enabled) audentio.pagination.update();



		if (uix.stickyFooter.running) uix.stickyFooter.check();
		uix.tStamp("UIX.resizeUpdate", 1, pfTime);
	}
}

uix.init.search = function() {
	if ($('#searchBar.hasSearchButton').length) {
		$("#QuickSearch .primaryControls span").click(function(e) {
			e.preventDefault();
			$("#QuickSearch > .formPopup").submit();
		});
	}

	if (uix.searchMinimalSize > 0 && $('#QuickSearchPlaceholder').length && uix.searchPosition != 0) {
		uix.init.searchClick();
		$(window).on('resize', function() {
			uix.init.searchClick();
		});

		$('#uix_searchMinimalClose').on('click', function(e) {
			e.preventDefault();
			uix.fn.searchClick();
		});

		$('#uix_searchMinimalOptions').on('click', function(e) {
			e.preventDefault();
			var delay = 0;
			if (uix.searchMinimalState == 1) {
				delay = 400;
				uix.fn.searchClick();
			}
			setTimeout(function() {
				window.requestAnimationFrame(function(){
					$('#QuickSearch').css({
						'opacity': 0,
						'transtion': 'opacity 0.3s'
					})
					window.requestAnimationFrame(function(){
						$('#QuickSearch').addClass('show');
						$('#QuickSearchPlaceholder').addClass('hide');
						window.requestAnimationFrame(function(){
							$('#QuickSearch').css({
								'opacity': 1
							})
							$('#QuickSearchQuery').focus();
							window.setTimeout(function() {
								window.requestAnimationFrame(function(){
									$('#QuickSearch').css({
										'opacity': '',
										'transtion': ''
									})
								});
							}, 300);
						});
					});
				});
			}, delay);
		});

		if (uix.searchPosition != 1) {
			var search = $('#searchBar');
			var searchContainer = search.parent();
			var searchContainerSiblings = searchContainer.siblings();
			var searchParentSiblings = searchContainer.parent().siblings();

			for (var i = 0, len = search.length; i < len; i++) {
				uix.searchEles.push([$(search[i]), 0]);
			}

			for (var i = 0, len = searchContainerSiblings.length; i < len; i++) {
				if (searchContainerSiblings[i].id !== "uix_searchMinimal") uix.searchEles.push([$(searchContainerSiblings[i]), 0]);
			}

			for (var i = 0, len = searchParentSiblings.length; i < len; i++) {
				if (searchParentSiblings[i].id !== "uix_searchMinimal") {
					if (uix.searchPosition == 2) {
						if ($(searchParentSiblings[i]).hasClass('publicTabs')) {
							var publicTabsChildren = $('.publicTabs>li');
							for (var j = 0, len2 = publicTabsChildren.length; j < len2; j++) {
								if ($(publicTabsChildren[j]).hasClass('selected')) {
									var selectedChildren = $(publicTabsChildren[j]).children();
									for (var k = 0, len3 = selectedChildren.length; k < len3; k++) {
										if ($(selectedChildren[k]).hasClass('tabLinks') == false) {
											uix.searchEles.push([$(selectedChildren[k]), 0]);
										}
									}

								} else {
									uix.searchEles.push([$(publicTabsChildren[j]), 0]);
								}
							}
						} else {
							uix.searchEles.push([$(searchParentSiblings[i]), 0]);
						}
					} else {
						uix.searchEles.push([$(searchParentSiblings[i]), 0]);
					}
				}
			}
		}
	}
}

uix.init.searchClick = function() {
	if (uix.windowWidth > uix.searchMinimalSize && uix.slidingSearchEnabled != 0) {
		$("#QuickSearchPlaceholder").unbind("click")
		$('#QuickSearchPlaceholder').click(function(e) {
			e.preventDefault();
			window.requestAnimationFrame(function(){
				$('#QuickSearch').addClass('show');
				$('#QuickSearchPlaceholder').addClass('hide');
				$('#QuickSearchQuery').focus();
			});
		});
		uix.slidingSearchEnabled = 0;
	} else if (uix.windowWidth <= uix.searchMinimalSize && uix.slidingSearchEnabled != 1) {
		$("#QuickSearchPlaceholder").unbind("click")
		$('#QuickSearchPlaceholder').on('click', function(e) {
			e.preventDefault();
			uix.fn.searchClick();
		});
		
		uix.slidingSearchEnabled = 1;
	}
}

uix.init.offsetAnchors = function(){
	var anchors = document.getElementsByTagName("a")
	for (var i = 0, len = anchors.length; i < len; i++){
		var a = anchors[i],
			href = a.href;
		if (href.indexOf("#") !== -1){
			var split = href.split("#");
			if (split.length == 2 && split[1].length > 0) {
				var addListener = true;
				var parEle = a.parentElement;
				if (typeof(a.className) !== "undefined" && a.className.indexOf("AttributionLink") !== -1) addListener = false;
				if (parEle.className.indexOf("navControls") !== -1) addListener = false;
				if (parEle.className.indexOf("topLink") !== -1) addListener = false;
				if (parEle.id == "uix_jumpToFixed") addListener = false;
				if (typeof(parEle.parentElement.className) !== "undefined"){
                    if (parEle.parentElement.className.indexOf("Tabs") !== -1) addListener = false;
                } 
				if (addListener){
					a.addEventListener("click", uix.fn.scrollToAnchor);
				}
			}
		}
	}
}

uix.navTabs = function(){
	if (uix.windowWidth > uix.offCanvasTriggerWidth) {
		XenForo.updateVisibleNavigationTabs();
	}
}

uix.initGlobalVars = function() {
	uix.jsVersion = "1.4.6.0";

	//UIX variables
	uix.windowWidth = 0;
	uix.windowHeight = 0;
	uix.documentHeight = 0;
	uix.scrollTop = 0;

	uix.elm = {
		html: $('html'),
		navigation: $('#navigation'),
		logo: $('#logo'),
		logoSmall: $('#logo_small'),
		jumpToFixed: $('#uix_jumpToFixed'),
		mainContainer: $('.mainContainer'),
		mainContent: $('.mainContent', uix.elm.mainContainer),
		welcomeBlock: $('#uix_welcomeBlock'),
		sidebar: $('.uix_mainSidebar'),
		sidebar_inner: $('.sidebar', uix.elm.sidebar),
	};

	uix.sidebarVisible = 1;
	uix.hasSticky = Object.keys(uix.stickyItems).length > 0 ? true : false;
	uix.contentHeight = uix.elm.mainContent.outerHeight();
	uix.emBreak = uix.elm.mainContainer.scrollTop();
	uix.slidingSidebar = true; // if false, disable transition for sliding sticky's sidebar
	uix.jumpToScrollTimer = null;
	uix.jumpToScrollHideTimer = null;
	uix.jumpToFixedRunning = false;
	uix.jumpToFixedOpacity = 0;
	uix.stickyForceDisable = false,
	uix.slidingSearchEnabled = 0;
	uix.searchMinimalState = -1;
	uix.searchEles = [];
	uix.logoVisible = 0;
	uix.pfLog = false; // if true, will output timers for most functions for performance testing
	uix.pfTime = uix.time(true); // used for timing performance testing
	uix.initTime = null;
	uix.xfTime = null;
	uix.initDone = false;
	uix.updateNavTabs = false;
	uix.initLog = [];

	if (typeof(uix.sidebarMaxResponsiveWidthStr) === 'string' && uix.sidebarMaxResponsiveWidthStr.replace(" ", "") == "100%") uix.sidebarMaxResponsiveWidth = 999999;
}

uix.initFunc = function() {
	uix.initGlobalVars();

	$(window).on('load', uix.init.fixScrollLocation);

	uix.initGet = function(){

	},

	uix.initSet = function(){

	},

	uix.initFuncInner = function() {

		if (uix.betaMode) console.warn("%cUI.X IS IN BETA MODE", "color:#E74C3C;font-weight:bold");

		if (!uix.elm.html.hasClass("flexbox")) uix.elm.html.removeClass("hasFlexbox")

		uix.logI("Pre UIX Viewport Init");
		uix.slidingSidebar = false;
		uix.viewport.init();

		uix.logI("Pre UIX Welcome Block Init");
		uix.init.welcomeBlock();


		if (uix.elm.sidebar.length && uix.stickySidebar && uix.windowWidth > uix.sidebarMaxResponsiveWidth) {
			uix.logI("Pre UIX Sidebar Init");
			uix.sidebarSticky.innerWrapper = uix.elm.sidebar_inner;
		}

		uix.logI("Pre UIX Main Sidebar Init");
		uix.init.mainSidebar();

		uix.logI("Pre UIX Collapsible Nodes Init");
		uix.collapsible.init();

		if (uix.offCanvasSidebar) {
			uix.logI("Pre UIX Off Canvas Init");
			uix.init.offcanvas();
			uix.userBar.check();
		}

		uix.logI("Pre UIX AdminLinks Init");
		uix.init.setupAdminLinks();

		if (uix.hasSticky) {
			if (uix.stickyDisableIOSThirdParty) {
				var userAgent = navigator.userAgent;

				if (userAgent.match(/(iPod|iPhone|iPad)/)) {
					for (var i = 1; i < 8; i++){
						if (userAgent.indexOf("OS " + i + "_") > -1) uix.stickyForceDisable = true;
					}
					if (userAgent.indexOf("OS 8_") > -1) {
						if (userAgent.indexOf("Version/") == -1) uix.stickyForceDisable = true; // non-safari
					}
				} else if (userAgent.match(/Android/)) {
					if (userAgent.indexOf("Android 1") > -1) uix.stickyForceDisable = true;
					if (userAgent.indexOf("Android 2") > -1) uix.stickyForceDisable = true;
					if (userAgent.indexOf("Android 3") > -1) uix.stickyForceDisable = true;
				}
			}

			if (!uix.stickyForceDisable){
				uix.logI("Pre UIX Sticky Init");
				uix.init.sticky();
			}
		}

		if (uix.nodeStyle == 3) {
			uix.logI("Pre UIX Grid Layout Init");
			audentio.grid.init(); // loads for any width
		}

		if (uix.elm.sidebar.length && uix.stickySidebar && uix.windowWidth > uix.sidebarMaxResponsiveWidth && uix.sidebarVisible == 1 && !uix.stickyForceDisable && uix.user.stickyEnableSidebar) {
			uix.logI("Pre UIX Sticky Sidebar Init");
			uix.sidebarSticky.init();
		}

		if (audentio.pagination.enabled) {
			uix.logI("Pre UIX Pagination Init");
			audentio.pagination.init();
		}

		uix.logI("Pre UIX Fix Scroll Location Init");
		uix.init.fixScrollLocation();

		uix.logI("Pre UIX Scroll Functions Init");
		uix.init.scrollFunctions();

		if (uix.enableStickyFooter){
			uix.logI("Pre UIX Sticky Footer Init");
			uix.stickyFooter.init();
		}

		uix.logI("Pre UIX Search Init");
		uix.init.search();

		uix.logI("Pre UIX Resize Init");
		uix.init.resizeFunctions();

		uix.logI("Pre UIX SmallLogo Init");
		uix.smallLogo.init()

		if (uix.enableBorderCheck){
			uix.logI("Pre UIX UL Manager Check Get");
			uix.ulManager.checkGet();
		}

		uix.slidingSidebar = true;
		uix.logI("Pre UIX content Init");
		if ($("#content.register_form").length) $("#loginBarHandle").hide();

		if (uix.updateNavTabs) {
			uix.logI("Pre XenForo tab Init");
			uix.navTabs(); // prevented during load to only need to do once.
		}

		if (uix.offCanvasSidebarVisitorTabs) {
			uix.logI("Pre UIX Offcanvas Visitor Tabs Init");
			uix.init.offCanvasVisitor();
		}

		uix.logI("Pre UIX Jump to Fixed Init");
		uix.init.jumpToFixed();

		if (uix.enableStickyFooter){
			uix.logI("Pre UIX Sticky Footer Set");
			uix.stickyFooter.set();
			if (uix.stickyFooter.state == 1) uix.stickyFooter.check();
		}

		if (uix.toggleWidthEnabled) {
			uix.logI("Pre UIX Toggle Width Setup");
			uix.toggleWidth.setup();
		}

		if (uix.enableBorderCheck) {
			uix.logI("Pre UIX Check Radius Init");
			uix.checkRadius.init();
			uix.logI("Pre UIX UL Manager Check Set");
			uix.ulManager.checkSet();
		}

		uix.logI("Pre UIX Offset Anchors"); // Intercept all anchor clicks
		uix.init.offsetAnchors();
		
	}

	uix.logI("Pre UIX Init");
	uix.initFuncInner(); //Initialize UIX
	uix.initDone = true;
	uix.initTime = "UI.X initialized in " + uix.round(uix.time(true) - uix.pfTime, 4) + " ms.";
	uix.log(uix.initTime, "color:#008F00");
	uix.xfTime = "Xenforo loaded in " + (Date.now() - XenForo._pageLoadTime * 1000) + " ms.  UI.X was " + uix.round(((uix.time(true) - uix.pfTime) / (Date.now() - XenForo._pageLoadTime * 1000)) * 100, 2) + "% of that time.";
	uix.log(uix.xfTime, "color:#00A3CC");
	uix.initLog.push(["Post UIX Init", (uix.time(true) - uix.pfTime)])
}