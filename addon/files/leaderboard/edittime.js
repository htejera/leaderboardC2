function GetPluginSettings()
{
	return {
		"name":			"Leaderboard",
		"id":			"Leaderboard",
		"version":		"1.0",
		"description":	"A self hosted Open source PHP Leaderboard. Post, fetch and display high scores",
		"author":		"Ukelele studio",
		"help url":		"http://ukelelestudio.com",
		"category":		"Web",
		"type":			"object",			
		"rotatable":	false,
		"flags":		pf_singleglobal
	};
};

//////////////////////////////////////////////////////////////
// Conditions
AddCondition(0,	cf_trigger, "On Top Scores completed", "Leaderboard", "On Top Scores completed", "Triggered when a Top Scores request completes successfully.", "OnTopScoresComplete");
AddCondition(1,	cf_trigger, "On Get Best Rank completed", "Leaderboard", "On Get Best Rank completed", "Triggered when a Get Best Rank request completes successfully.", "OnGetBestRankComplete");
AddCondition(2,	cf_trigger, "On Get Last Rank completed", "Leaderboard", "On Get Last Rank completed", "Triggered when a Get Last Rank request completes successfully.", "OnGetLastRankComplete");
AddCondition(3,	cf_trigger, "On Add Score completed", "Leaderboard", "On Add Score completed", "Triggered when a Add Score request completes successfully.", "OnAddScore");

//////////////////////////////////////////////////////////////
// Actions
AddStringParam("Tag", "A tag, which can be anything you like, to distinguish between different Leaderboard requests.", "\"\"");
AddFileParam("File", "Select a project file to request.");
AddAction(1, 0, "Request project file", "Leaderboard", "Request <b>{1}</b> (tag <i>{0}</i>)", "Request a file in the project and retrieve its contents.", "RequestFile");

AddNumberParam("Timeout", "The timeout for Leaderboard requests in seconds. Use -1 for no timeout.");
AddAction(2, 0, "Set timeout", "Leaderboard", "Set timeout to <i>{0}</i> seconds", "Set the maximum time before a request is considered to have failed.", "SetTimeout");

AddStringParam("Header", "The HTTP header name to set on the request.");
AddStringParam("Value", "A string of the value to set the header to.");
AddAction(3, 0, "Set request header", "Leaderboard", "Set request header <i>{0}</i> to <i>{1}</i>", "Set a HTTP header on the next request that is made.", "SetHeader");

AddAction(4, 0, "Request Top Scores", "Leaderboard", "Request Top Scores", "Top Scores request returns an array of scores to your function where you can display the data in your Leaderboard.", "TopScores");

AddStringParam("Player", "The player name.");
AddAction(5, 0, "Get the player's last rank", "Leaderboard", "Get the last rank. Player: <b>{0}</b>", "Get the player's last rank.", "GetLastRank");

AddStringParam("Player", "The player name.");
AddAction(6, 0, "Get the player's best rank", "Leaderboard", "Get the best rank. Player: <b>{0}</b>", "Get the player's best rank.", "GetBestRank");

AddStringParam("Player", "The player name.");
AddNumberParam("Score", "The player's score.");
AddAction(7, 0, "Submit score", "Leaderboard","Submit score. Player: <b>{0}</b> | Score: <b>{1}</b>", "Submit player's score.", "AddScore");

//////////////////////////////////////////////////////////////
// Expressions
AddExpression(0, ef_return_string, "Get last data", "Leaderboard", "LastData", "Get the data returned by the last successful request.");
AddExpression(1, ef_return_number, "Get progress", "Leaderboard", "Progress", "Get the progress, from 0 to 1, of the request in 'On progress'.");

ACESDone();

// Property grid properties for this plugin
var property_list = [
	new cr.Property(ept_text, "Leaderboard service URL","The Leaderboard URL.", "The Leaderboard URL."),
	new cr.Property(ept_text, "Game ID", "", "The game id."),
	new cr.Property(ept_text, "Magic number", "", "The magic number. Should be equal to the 'magic_number' property in the config.ini file"),
	new cr.Property(ept_text, "Magic key", "", "The magic key. Should be equal to the 'magic_key' property in the config.ini file"),
	new cr.Property(ept_combo, "Log requests", "False","Sends request URLs into console. For debugging purposes.", "False|True"),
];
	
// Called by IDE when a new object type is to be created
function CreateIDEObjectType()
{
	return new IDEObjectType();
}

// Class representing an object type in the IDE
function IDEObjectType()
{
	assert2(this instanceof arguments.callee, "Constructor called as a function");
}

// Called by IDE when a new object instance of this type is to be created
IDEObjectType.prototype.CreateInstance = function(instance)
{
	return new IDEInstance(instance, this);
}

// Class representing an individual instance of an object in the IDE
function IDEInstance(instance, type)
{
	assert2(this instanceof arguments.callee, "Constructor called as a function");
	
	// Save the constructor parameters
	this.instance = instance;
	this.type = type;
	
	// Set the default property values from the property table
	this.properties = {};
	
	for (var i = 0; i < property_list.length; i++)
		this.properties[property_list[i].name] = property_list[i].initial_value;
}

// Called by the IDE after all initialization on this instance has been completed
IDEInstance.prototype.OnCreate = function()
{
}

// Called by the IDE after a property has been changed
IDEInstance.prototype.OnPropertyChanged = function(property_name)
{
}
	
// Called by the IDE to draw this instance in the editor
IDEInstance.prototype.Draw = function(renderer)
{
}

// Called by the IDE when the renderer has been released (ie. editor closed)
// All handles to renderer-created resources (fonts, textures etc) must be dropped.
// Don't worry about releasing them - the renderer will free them - just null out references.
IDEInstance.prototype.OnRendererReleased = function()
{
}
