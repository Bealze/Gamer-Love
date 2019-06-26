-- Common Functions
	-- Rounds number down from given point
	local function mathRound(number, precision)
		local swap = 10 ^ precision;
		number = number * swap;
	  	return math.floor(number) / swap;
	end
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
	LOOT_MULTIPLIER=5,
	MAX_PERCENT_CLASSES=100,
	MAX_PERCENT_GROUPS=100
}


-- Config Functions
	-- The Config To Write If Missing
	local defaultConfig = [[
	local settings = {
		LOOT_MULTIPLIER = 5,
		MAX_PERCENT_CLASSES = 100, -- Raising this will cause errors
		MAX_PERCENT_GROUPS = 100 -- this one should be fine to raise... Higher value mean more chance for each item in the group to spawn
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
	SetConfig("ItemSpawnerMultiplier_Config.lua"); -- Calls Immediately
-- End Of Config Functions

-- Percent Set Functions
	local function SetPercentages(this, a, b, categoryTotal)
		if b["classes"] ~= nil then
			for c, d in pairs(b["classes"]) do
				for e, f in pairs(d) do
					if e == "percent" then
						local multiplierMax = mathRound(config["MAX_PERCENT_CLASSES"]/categoryTotal, 4); -- Floor to avoid exceeding 100% due to slightly inaccurate maths
						local newValue = f * clamp(config["LOOT_MULTIPLIER"], 0, multiplierMax);
						if newValue > f or newValue < f then -- Logs and sets if changes were made
							local item = "";
							if d["class"] ~= nil then
								item = d["class"];
							elseif d["category"] ~= nil then
								item = d["category"];
							else
								item = "Unknown";
							end
							this["itemCategories"][a]["classes"][c]["percent"] = newValue; -- This long reference is to modify the original table
							Log("Before - Item:"..item.."; Item Percent:"..f.."; Category Percent:"..categoryTotal);
							Log("After - Item:"..item.."; Item Percent:"..newValue);
						end
					end
				end
			end
		end
	end

	local function SetGroupPercentages(this, a, b)
		if b["group"] ~= nil then
			for c, d in pairs(b["group"]) do
				for e, f in pairs(d) do
					if e == "percent" then
						local newValue = clamp(f * config["LOOT_MULTIPLIER"], 1, config["MAX_PERCENT_GROUPS"]); -- Since it is a group, the total can be more than 100 so we don't need "multiplierMax"
						if newValue > f then
							local item = "";
							if d["class"] ~= nil then
								item = d["class"];
							elseif d["category"] ~= nil then
								item = d["category"];
							else
								item = "Unknown";
							end
							this["itemCategories"][a]["group"][c]["percent"] = newValue; -- This long reference is to modify the original table
							Log("Group Before - Item:"..item.."; Item Percent:"..f);
							Log("Group After - Item:"..item.."; Item Percent:"..newValue);
						end
					end

				end
			end
		end
	end
-- End Of Percent Set Functions

-- Gets Total Percentage For The Category
local function CategoryTotaller(b)
	local categoryTotal = 0;
	if b["classes"] ~= nil then
		for c, d in pairs(b["classes"]) do
			for e, f in pairs(d) do
				if e == "percent" then -- Only does this for items with percent
					categoryTotal = categoryTotal + f;
				end
			end
		end
	end
	return categoryTotal;
end


-- Start Function
function GizzISMMultiplier_Init(self)
	-- Loot Multiplier
	Log("Loot is being multiplied!");
	for a, b in pairs(self["itemCategories"]) do
		local categoryTotal = CategoryTotaller(b);
		SetPercentages(self, a, b, categoryTotal);
		SetGroupPercentages(self, a, b);
	end
	Log("Loot was multiplied!");
	-- Loot Multiplier End
end
GizzISMMultiplier_Init(ItemSpawnerManager); -- Calls Immediately