/** 
 * A self hosted Open source PHP Leaderboard. Post, fetch and display high scores.
 * Based on the AJAX plugin.
 */
// ECMAScript 5 strict mode
"use strict";

assert2(cr, "cr namespace not created");
assert2(cr.plugins_, "cr.plugins_ not created");

/////////////////////////////////////
// Plugin class
cr.plugins_.Leaderboard = function(runtime)
{
	this.runtime = runtime;
};

(function ()
{
	var isNodeWebkit = false;
	var path = null;
	var fs = null;
	var nw_appfolder = "";
	
	var pluginProto = cr.plugins_.Leaderboard.prototype;
		
	/////////////////////////////////////
	// Object type class
	pluginProto.Type = function(plugin)
	{
		this.plugin = plugin;
		this.runtime = plugin.runtime;
	};

	var typeProto = pluginProto.Type.prototype;

	typeProto.onCreate = function()
	{
	};

	/////////////////////////////////////
	// Instance class
	pluginProto.Instance = function(type)
	{
		this.type = type;
		this.runtime = type.runtime;
		
		this.lastData = "";
		this.curTag = "";
		this.progress = 0;
		this.timeout = -1;
				
		isNodeWebkit = this.runtime.isNodeWebkit;
		
		if (isNodeWebkit)
		{
			path = require("path");
			fs = require("fs");
			nw_appfolder = path["dirname"](process["execPath"]) + "\\";
		}
	};
	
	var instanceProto = pluginProto.Instance.prototype;
	
	var theInstance = null;
	
	// For handling Leaderboard events in DC
	window["C2_Leaderboard_DCSide"] = function (event_, tag_, param_)
	{
		if (!theInstance)
			return;
		
		if (event_ === "success")
		{
			theInstance.curTag = tag_;
			theInstance.lastData = param_;
			theInstance.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnComplete, theInstance);
			theInstance.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnTopScoresComplete, theInstance);
			theInstance.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnGetLastRankComplete, theInstance);
			theInstance.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnGetBestRankComplete, theInstance);			
			theInstance.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnAddScore, theInstance);		
		}
		else if (event_ === "error")
		{
			theInstance.curTag = tag_;
			theInstance.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnError, theInstance);
		}
		else if (event_ === "progress")
		{
			theInstance.progress = param_;
			theInstance.curTag = tag_;
			theInstance.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnProgress, theInstance);
		}
	};

	instanceProto.onCreate = function()
	{
		theInstance = this;
		this.url = this.properties[0];
		this.gameId = this.properties[1];
		this.magicNumber = this.properties[2];
		this.magicKey = this.properties[3];
		this.debug = this.properties[4];
			
		this.randomString = function(length) {
			var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			var result = '';
			for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
			return result;
		};
				
		this.encode64 = function (text) {
			if (/([^\u0000-\u00ff])/.test(text)) {
				throw new Error("Can't base64 encode non-ASCII characters.");
			}
			var digits = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
				i = 0,
				cur, prev, byteNum, result = [];
			while (i < text.length) {
				cur = text.charCodeAt(i);
				byteNum = i % 3;
				switch (byteNum) {
				case 0:
					result.push(digits.charAt(cur >> 2));
					break;
				case 1:
					result.push(digits.charAt((prev & 3) << 4 | (cur >> 4)));
					break;
				case 2:
					result.push(digits.charAt((prev & 0x0f) << 2 | (cur >> 6)));
					result.push(digits.charAt(cur & 0x3f));
					break;
				}
				prev = cur;
				i++;
			}
			if (byteNum == 0) {
				result.push(digits.charAt((prev & 3) << 4));
				result.push("==");
			} else if (byteNum == 1) {
				result.push(digits.charAt((prev & 0x0f) << 2));
				result.push("=");
			}
			return result.join("");
		}
		
		this.decode64 = function(text) {
			text = text.replace(/\s/g, "");
			if (!(/^[a-z0-9\+\/\s]+\={0,2}$/i.test(text)) || text.length % 4 > 0) {
				throw new Error("Not a base64-encoded string.");
			}
			var digits = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
				cur, prev, digitNum, i = 0,
				result = [];
			text = text.replace(/=/g, "");
			while (i < text.length) {
				cur = digits.indexOf(text.charAt(i));
				digitNum = i % 4;
				switch (digitNum) {					
				case 1:
					result.push(String.fromCharCode(prev << 2 | cur >> 4));
					break;
				case 2:
					result.push(String.fromCharCode((prev & 0x0f) << 4 | cur >> 2));
					break;
				case 3:
					result.push(String.fromCharCode((prev & 3) << 6 | cur));
					break;
				}
				prev = cur;
				i++;
			}
			return result.join("");
		}
		
		this.ord = function(string) {
			var str = string + '',
				code = str.charCodeAt(0);
			if (0xD800 <= code && code <= 0xDBFF) {
				var hi = code;
				if (str.length === 1) {
					return code;
				}
				var low = str.charCodeAt(1);
				return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000;
			}
			if (0xDC00 <= code && code <= 0xDFFF) {
				return code;
			}
			return code;
		}

		this.encrypt = function(sData, sKey) {
			var sResult = "";
			var i = 0;
			for (i = 0; i < sData.length; i++) {
				var sChar = sData.substr(i, 1);
				var sKeyChar = sKey.substr(i % sKey.length - 1, 1);
				sChar = Math.floor(this.ord(sChar) + this.ord(sKeyChar));
				sChar = String.fromCharCode(sChar);
				sResult = sResult + sChar;
			}
			return this.encode64(sResult);
		}
		
		this.doubleEncrypt = function(data){			
			return this.encrypt(this.encrypt(data, this.magicKey), this.magicKey);		
		}
	};
	
	instanceProto.saveToJSON = function ()
	{
		return { "lastData": this.lastData };
	};
	
	instanceProto.loadFromJSON = function (o)
	{
		this.lastData = o["lastData"];
		this.curTag = "";
		this.progress = 0;
	};
	
	var next_request_headers = {};
	var next_override_mime = "";
	
	instanceProto.doRequest = function (tag_, url_, method_, data_)
	{
		if(this.debug){
			console.log("Leaderboard | Action:" + tag_ +  "| Request: " + url_);
		}
		// In directCanvas: forward request to webview layer
		if (this.runtime.isDirectCanvas)
		{
			AppMobi["webview"]["execute"]('C2_Leaderboard_WebSide("' + tag_ + '", "' + url_ + '", "' + method_ + '", ' + (data_ ? '"' + data_ + '"' : "null") + ');');
			return;
		}
		
		// Create a context object with the tag name and a reference back to this
		var self = this;
		var request = null;
		
		var doErrorFunc = function ()
		{
			self.curTag = tag_;
			self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnError, self);
		};
		
		var errorFunc = function ()
		{
			// In node-webkit, try looking up the file on disk instead since it wasn't found in the project.
			if (isNodeWebkit)
			{
				var filepath = nw_appfolder + url_;
				
				if (fs["existsSync"](filepath))
				{
					fs["readFile"](filepath, {"encoding": "utf8"}, function (err, data) {
						if (err)
						{
							doErrorFunc();
							return;
						}
						
						self.lastData = data.replace(/\r\n/g, "\n")
						self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnComplete, self);
						self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnTopScoresComplete, self);
						self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnGetLastRankComplete, self);
						self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnGetBestRankComplete, self);
						self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnAddScore, self);
						
					});
				}
				else
					doErrorFunc();
			}
			else
				doErrorFunc();
		};
			
		var progressFunc = function (e)
		{
			if (!e["lengthComputable"])
				return;
				
			self.progress = e.loaded / e.total;
			self.curTag = tag_;
			self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnProgress, self);
		};
			
		try
		{
			// Windows Phone 8 can't Leaderboard local files using the standards-based API, but
			// can if we use the old-school ActiveXObject. So use ActiveX on WP8 only.
			if (this.runtime.isWindowsPhone8)
				request = new ActiveXObject("Microsoft.XMLHTTP");
			else
				request = new XMLHttpRequest();
			
			request.onreadystatechange = function()
			{
				if (request.readyState === 4)
				{
					self.curTag = tag_;
					
					if (request.responseText)
						self.lastData = request.responseText.replace(/\r\n/g, "\n");		// fix windows style line endings
					else
						self.lastData = "";
					
					if (request.status >= 400)
						self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnError, self);
					else
					{
						// In node-webkit, don't trigger 'on success' with empty string if file not found
						if (!isNodeWebkit || self.lastData.length)
							self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnComplete, self);
							self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnTopScoresComplete, self);
							self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnGetLastRankComplete, self);
							self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnGetBestRankComplete, self);						
							self.runtime.trigger(cr.plugins_.Leaderboard.prototype.cnds.OnAddScore, self);
					}
				}
			};
			
			if (!this.runtime.isWindowsPhone8)
			{
				request.onerror = errorFunc;
				request.ontimeout = errorFunc;
				request.onabort = errorFunc;
				request["onprogress"] = progressFunc;
			}
			
			request.open(method_, url_);
			
			if (!this.runtime.isWindowsPhone8)
			{
				// IE requires timeout be set after open()
				if (this.timeout >= 0 && typeof request["timeout"] !== "undefined")
					request["timeout"] = this.timeout;
			}
			
			// Workaround for CocoonJS bug: property exists but is not settable
			try {
				request.responseType = "text";
			} catch (e) {}
			
			if (data_)
			{
				if (request["setRequestHeader"])
				{
					request["setRequestHeader"]("Content-Type", "application/x-www-form-urlencoded");
				}
			}
			
			// Apply custom headers
			if (request["setRequestHeader"])
			{
				var p;
				for (p in next_request_headers)
				{
					if (next_request_headers.hasOwnProperty(p))
					{
						try {
							request["setRequestHeader"](p, next_request_headers[p]);
						}
						catch (e) {}
					}
				}
				
				// Reset for next request
				next_request_headers = {};
			}
			
			// Apply MIME type override if one set
			if (next_override_mime && request["overrideMimeType"])
			{
				try {
					request["overrideMimeType"](next_override_mime);
				}
				catch (e) {}
				
				// Reset for next request
				next_override_mime = "";
			}

			if (data_)
				request.send(data_);
			else
				request.send();
			
		}
		catch (e)
		{
			errorFunc();
		}
	};
	
	/**BEGIN-PREVIEWONLY**/
	instanceProto.getDebuggerValues = function (propsections)
	{
		propsections.push({
			"title": "Leaderboard",
			"properties": [
				{"name": "Last data", "value": this.lastData, "readonly": true}
			]
		});
	};
	/**END-PREVIEWONLY**/

	//////////////////////////////////////
	// Conditions
	function Cnds() {};

	Cnds.prototype.OnComplete = function (tag)
	{		
		return cr.equals_nocase(tag, this.curTag);
	};
	
	Cnds.prototype.OnTopScoresComplete = function ()
	{		
		return cr.equals_nocase("TopScores", this.curTag);
	};

	Cnds.prototype.OnGetLastRankComplete = function ()
	{		
		return cr.equals_nocase("GetLastRank", this.curTag);
	};	
		
	Cnds.prototype.OnGetBestRankComplete = function ()
	{		
		return cr.equals_nocase("GetBestRank", this.curTag);
	};

	Cnds.prototype.OnAddScore = function ()
	{		
		return cr.equals_nocase("AddScore", this.curTag);
	};
	
	Cnds.prototype.OnError = function (tag)
	{
		return cr.equals_nocase(tag, this.curTag);
	};
	
	Cnds.prototype.OnProgress = function (tag)
	{
		return cr.equals_nocase(tag, this.curTag);
	};
	
	pluginProto.cnds = new Cnds();

	//////////////////////////////////////
	// Actions
	function Acts() {};

	Acts.prototype.TopScores = function ()
	{
		this.doRequest("TopScores",this.url + "/topscores/" + this.gameId, "GET");
	};	

	Acts.prototype.GetLastRank = function (player_)
	{
		this.doRequest("GetLastRank",this.url + "/getlastrank/" + this.gameId + "/" + player_, "GET");
	};	

	Acts.prototype.GetBestRank = function (player_)
	{
		this.doRequest("GetBestRank",this.url + "/getbestrank/" + this.gameId + "/" + player_ , "GET");
	};	
	
	Acts.prototype.AddScore = function (player_, score_)
	{
		var random = this.randomString(3) + this.magicNumber + this.randomString(2);		
		this.doRequest("AddScore",this.url + "/addscore/" + this.gameId + "/" + this.doubleEncrypt(random) + "/" + player_ + "/" + score_, "GET");
	};	
	
	Acts.prototype.Request = function (tag_, url_)
	{
		this.doRequest(tag_, url_, "GET");
	};
	
	Acts.prototype.RequestFile = function (tag_, file_)
	{
		this.doRequest(tag_, file_, "GET");
	};
	
	Acts.prototype.Post = function (tag_, url_, data_, method_)
	{
		this.doRequest(tag_, url_, method_, data_);
	};
	
	Acts.prototype.SetTimeout = function (t)
	{
		this.timeout = t * 1000;
	};
	
	Acts.prototype.SetHeader = function (n, v)
	{
		next_request_headers[n] = v;
	};
	
	Acts.prototype.OverrideMIMEType = function (m)
	{
		next_override_mime = m;
	};
	
	pluginProto.acts = new Acts();

	//////////////////////////////////////
	// Expressions
	function Exps() {};

	Exps.prototype.LastData = function (ret)
	{
		ret.set_string(this.lastData);
	};
	
	Exps.prototype.Progress = function (ret)
	{
		ret.set_float(this.progress);
	};
	
	pluginProto.exps = new Exps();

}());