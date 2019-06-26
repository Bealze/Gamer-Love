-- Common Functions
	-- Returns a number with a min and max
	--[[local function mathClamp(number, min, max)
		if number < min then
			return min;
		elseif number > max then
			return max;
		else
			return number;
		end
	end]]
-- End Of Common Functions


-- The Default Config
local config = {
	ACTIONABLE_MULTIPLIER=2,
	MAX_PERCENT=100
}


-- Config Functions
	-- The Config To Write If Missing
	local defaultConfig = [[
	local settings = {
		ACTIONABLE_MULTIPLIER=2,
		MAX_PERCENT=100 -- Raising this will cause errors
	}
	return settings;
	]];

	-- Writes A Config File
	local function WriteConfig(filename)
		file = io.open (filename, "w");
		file:write(defaultConfig);
		file:close();
	end

	-- Sets The Config To Match The Config File
	local function SetConfig(filename)
		local file = io.open (filename);
		-- Writes A Config File If Missing
		-- And Returns To Go With Defaults
		if file == nil then
			WriteConfig(filename);
			return;
		end
		file:close();
		package.path = "./"..filename..";"..package.path;
		local settings = require(filename);
		for a, b in pairs(settings) do
			config[a] = b;
		end
	end
	SetConfig("ItemActionableMultiplier_Config.lua"); -- Calls Immediately
-- End Of Config Functions

-- Percent Set Functions
	local function SetPercentages(self, a, b)
		Log("Start Item:"..self["actionables"][a]["text"])
		if b["minUses"] ~= nil then
			local newValue = self["actionables"][a]["minUses"] * config["ACTIONABLE_MULTIPLIER"];
			Log("Before Min Uses:"..b["minUses"]);
			self["actionables"][a]["minUses"] = newValue;
			Log("After Min Uses:"..newValue);
		end
		if b["uses"] ~= nil then
			local newValue = self["actionables"][a]["uses"] * config["ACTIONABLE_MULTIPLIER"]
			Log("Before Uses:"..b["uses"]);
			self["actionables"][a]["uses"] = newValue;
			Log("After Uses:"..newValue);
		end
		if b["regenerate"] ~= nil then
			local newValue = self["actionables"][a]["regenerate"] / config["ACTIONABLE_MULTIPLIER"]
			Log("Before Regeneration Time:"..b["regenerate"]);
			self["actionables"][a]["regenerate"] = newValue;
			Log("After Regeneration Time:"..newValue);
		end
		if b["percentage"] ~= nil then
			local newValue = clamp(self["actionables"][a]["percentage"] * config["ACTIONABLE_MULTIPLIER"], 0, config["MAX_PERCENT"])
			Log("Before Search Percentage:"..b["percentage"]);
			self["actionables"][a]["percentage"] = newValue;
			Log("After Search Percentage:"..newValue);
		end
		Log("End Item:"..self["actionables"][a]["text"])
	end
-- End Of Percent Set Functions

-- Start Function
function GizzASMMultiplier_Init(self)
	-- Loot Multiplier
	Log("Actionable Loot is being multiplied!");
	for a, b in pairs(self["actionables"]) do
		SetPercentages(self, a, b);
	end
	Log("Actionable Loot was multiplied!");
	-- Loot Multiplier End
end
GizzASMMultiplier_Init(ActionableWorldManager); -- Calls Immediately