/* CROSS-BROWSER EVENT HANDLER */
function addEvent(obj, evType, fn){
    if (obj.addEventListener){
	obj.addEventListener(evType, fn, true);
	return true;
    } else if (obj.attachEvent){
	var r = obj.attachEvent("on"+evType, fn);
	return r;
    } else {
	return false;
    }
}
/* END EVENT HANDLER */


function doGetPage(i) {
    if (i == 1 || i == '') {
	new Ajax.Updater('content','/BuyAndSellOnline/pages/display', {asynchronous:true, evalScripts:true, requestHeaders:['X-Update', 'content']});
    } else if (i) {
	new Ajax.Updater('content','/BuyAndSellOnline/' + i, {asynchronous:true, evalScripts:true, requestHeaders:['X-Update', 'content']});
    }
}


/* PAGELOCATOR */

    function PageLocator(propertyToUse, dividingCharacter) {
	this.propertyToUse = propertyToUse;
	this.defaultQS = 1;
	this.dividingCharacter = dividingCharacter;
    }

PageLocator.prototype.getLocation = function() {
    return eval(this.propertyToUse);
}

    PageLocator.prototype.getHash = function() {
	var url = this.getLocation();
	if(url.indexOf(this.dividingCharacter)>-1) {
	    var url_elements = url.split(this.dividingCharacter);
	    return url_elements[url_elements.length-1];
	} else {
	    return this.defaultQS;
	}
    }

	PageLocator.prototype.getHref = function() {
	    var url = this.getLocation();
	    var url_elements = url.split(this.dividingCharacter)
	    return url_elements[0];
	}
	    PageLocator.prototype.makeNewLocation = function(new_qs) {
		return this.getHref() + this.dividingCharacter + new_qs;
	    }
	    /* END PAGELOCATOR */


	    /* AjaxIframesFixer */
		function AjaxIframesFixer(iframeid) {
		    this.iframeid = iframeid;
		    if (document.getElementById('ajaxnav')) {
			this.fixLinks();

			this.locator = new PageLocator("document.frames['"+this.iframeid+"'].getLocation()", "/BuyAndSellOnline/");
			this.windowlocator = new PageLocator("window.location.href", "#");
			this.timer = new Timer(this);

			this.delayInit(); // required or IE doesn't fire
		    }
		}
AjaxIframesFixer.prototype.fixLinks = function (iframeid) {
    var links = document.getElementsByTagName("A");
    for(var i=0; i<links.length; i++) {
	var href = links[i].getAttribute("href");
	var hash = href.substr(href.indexOf("/BuyAndSellOnline/")+18);
	links[i].setAttribute("href","javascript:document.getElementById('"+this.iframeid+"').setAttribute('src', 'mock-page.php?hash="+hash+"');");
    }
}
    AjaxIframesFixer.prototype.delayInit = function(){
	this.timer.setTimeout("checkBookmark", 100, "");
    }
	AjaxIframesFixer.prototype.checkBookmark = function(){
	    window.location = this.windowlocator.makeNewLocation(this.locator.getHash());
	    this.checkWhetherChanged(0);
	}
	    AjaxIframesFixer.prototype.checkWhetherChanged = function(location){
		if(this.locator.getHash() != location) {
		    doGetPage(this.locator.getHash());
		    window.location = this.windowlocator.makeNewLocation(this.locator.getHash());
		}
		this.timer.setTimeout("checkWhetherChanged", 200, this.locator.getHash());
	    }
	    /* END AjaxIframesFixer */


	    /* AjaxUrlFixer */
		function AjaxUrlFixer() {
		    this.fixLinks();

		    this.locator = new PageLocator("window.location.href", "#");
		    this.timer = new Timer(this);
		    this.checkWhetherChanged(0);
		}
AjaxUrlFixer.prototype.fixLinks = function () {
    var links = document.getElementsByTagName("A");
    for(var i=0; i<links.length; i++) {
	var href = links[i].getAttribute("href");
	var hash = href.substr(href.indexOf("/BuyAndSellOnline/")+18);
	links[i].setAttribute("href","#"+hash);
    }
}
    AjaxUrlFixer.prototype.checkWhetherChanged = function(location){
	if(this.locator.getHash() != location) {
	    doGetPage(this.locator.getHash());
	}
	this.timer.setTimeout("checkWhetherChanged", 200, this.locator.getHash());
    }
    /* END AjaxUrlFixer */

	function setContent(new_content) {
	    if(!document.getElementById || !document.getElementsByTagName) return;
	    var container = document.getElementById("content");
	    container.innerHTML = new_content;
	}
function FixBackAndBookmarking() {
    if(!document.getElementById || !document.getElementsByTagName) return;
    if(document.iframesfix) {
	fix = new AjaxIframesFixer('ajaxnav');
    } else {
	fix = new AjaxUrlFixer();
    }
}

var detect = navigator.userAgent.toLowerCase();
if(detect.indexOf("msie")>-1) document.iframesfix = true;
addEvent(window, "load", FixBackAndBookmarking);