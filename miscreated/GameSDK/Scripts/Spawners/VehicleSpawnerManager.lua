VehicleSpawnerManager = {

--[[
	Property descriptions:
	initialMinVehicles - the minimum number of vehicles from this category that will exist on system startup
	abandonedTimer -- how long until an abandoned vehicle will be removed from gameplay (in seconds)
	abandonedRespawnTimer -- how long until an abandoned vehicle will respawn (in seconds)
	destroyedTimer -- how long until an destroyed vehicle will be removed from gameplay (in seconds)
	destroyedRespawnTimer -- how long until a destroyed vehicle will respawn (in seconds)
]]--

	vehicleCategories =
	{
		-- ====================================================================
		-- BASIC CATEGORIES
		-- ====================================================================

		------------------------------------------------
		------------------------------------------------
		{
			category = "armored_truck_army",
			classes =
			{
				{ class = "armored_truck_army", contents = "RandomPoliceSedanContents" },
			},
			initialMinVehicles = 6,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "armored_truck_police",
			classes =
			{
				{ class = "armored_truck_police", contents = "RandomPoliceSedanContents" },
			},
			initialMinVehicles = 6,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		{
			category = "bicycle",
			classes =
			{
				{
					-- All bicycle spawners now just spawn a quadbike
					class = "bicycle",
					skins =
					{
						-- If the total doesn't equal 100% then the remainder will spawn with the default skin
						{ name = "Bicycle_Black", percent = 11.0 },
						{ name = "Bicycle_Blue", percent = 11.0 },
						{ name = "Bicycle_Green", percent = 11.0 },
						{ name = "Bicycle_Orange", percent = 12.0 }, -- Default??
						{ name = "Bicycle_Pink", percent = 11.0 },
						{ name = "Bicycle_Purple", percent = 11.0 },
						{ name = "Bicycle_Red", percent = 11.0 },
						{ name = "Bicycle_White", percent = 11.0 },
						{ name = "Bicycle_Yellow", percent = 11.0 },
					},
				},
			},
			initialMinVehicles = 10,
			abandonedTimer = 21600,  -- six hours
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "dirtbike",
			classes =
			{
				{
					-- All dirtbike spawners now just spawn a quadbike
					class = "dirtbike",
					skins =
					{
						-- If the total doesn't equal 100% then the remainder will spawn with the default skin
						{ name = "Dirtbike_Black", percent = 11.0 },
						{ name = "Dirtbike_Blue", percent = 11.0 },
						{ name = "Dirtbike_Green", percent = 11.0 },
						{ name = "Dirtbike_Orange", percent = 12.0 }, -- Default??
						{ name = "Dirtbike_Pink", percent = 11.0 },
						{ name = "Dirtbike_Purple", percent = 11.0 },
						{ name = "Dirtbike_Red", percent = 11.0 },
						{ name = "Dirtbike_White", percent = 11.0 },
						{ name = "Dirtbike_Yellow", percent = 11.0 },
					},
				},
			},
			initialMinVehicles = 4,
			abandonedTimer = 21600,  -- six hours
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		{
			category = "dune_buggy",
			classes =
			{
				{ class = "dune_buggy", contents = "RandomF100TruckContents" },
			},
			initialMinVehicles = 5,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "f100truck",
			classes =
			{
				{ 
					class = "f100truck", contents = "RandomF100TruckContents",
					skins =
					{
						-- If the total doesn't equal 100% then the remainder will spawn with the default skin
						{ name = "F100Truck_Black", percent = 11.0 },
						{ name = "F100Truck_Blue", percent = 11.0 },
						{ name = "F100Truck_Green", percent = 11.0 },
						{ name = "F100Truck_Orange", percent = 12.0 }, -- Default
						{ name = "F100Truck_Pink", percent = 11.0 },
						{ name = "F100Truck_Purple", percent = 11.0 },
						{ name = "F100Truck_Red", percent = 11.0 },
						{ name = "F100Truck_White", percent = 11.0 },
						{ name = "F100Truck_Yellow", percent = 11.0 },
					},
				},
			},
			initialMinVehicles = 10,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "fishing_boat",
			classes =
			{
				{
					class = "fishing_boat", contents = "RandomFishingBoatContents",
					skins =
					{
						-- If the total doesn't equal 100% then the remainder will spawn with the default skin
						{ name = "FishingBoat_Black", percent = 11.0 },
						{ name = "FishingBoat_Blue", percent = 11.0 },
						{ name = "FishingBoat_Green", percent = 11.0 },
						{ name = "FishingBoat_Orange", percent = 12.0 },
						{ name = "FishingBoat_Pink", percent = 11.0 },
						{ name = "FishingBoat_Purple", percent = 11.0 },
						{ name = "FishingBoat_Red", percent = 11.0 },
						{ name = "FishingBoat_White", percent = 11.0 }, -- Default
						{ name = "FishingBoat_Yellow", percent = 11.0 },
					},
				},
			},
			initialMinVehicles = 5,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "jetski",
			classes =
			{
				{ class = "jetski" },
			},
			initialMinVehicles = 7,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "party_bus",
			classes =
			{
				{ class = "party_bus", contents = "RandomPartyBusContents" },
			},
			initialMinVehicles = 3,
			abandonedTimer = 25920000,  -- three days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "quadbike",
			classes =
			{
				{ class = "quadbike" },
			},
			initialMinVehicles = 10,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "sedan_base",
			classes =
			{
				{
					class = "sedan_base", contents = "RandomF100TruckContents",
					skins =
					{
						-- If the total doesn't equal 100% then the remainder will spawn with the default skin
						{ name = "SedanBase_Black", percent = 11.0 },
						{ name = "SedanBase_Blue", percent = 11.0 },
						{ name = "SedanBase_Green", percent = 11.0 },
						{ name = "SedanBase_Orange", percent = 11.0 },
						{ name = "SedanBase_Pink", percent = 11.0 },
						{ name = "SedanBase_Purple", percent = 11.0 },
						{ name = "SedanBase_Red", percent = 11.0 },
						{ name = "SedanBase_White", percent = 12.0 }, -- Default
						{ name = "SedanBase_Yellow", percent = 11.0 },
					},
				},
			},
			initialMinVehicles = 10,
			abandonedTimer = 17280000, -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "sedan_police",
			classes =
			{
				{ class = "sedan_police", contents = "RandomPoliceSedanContents" },
			},
			initialMinVehicles = 8,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "sedan_taxi",
			classes =
			{
				{ class = "sedan_taxi_blix", contents = "RandomF100TruckContents" },
				{ class = "sedan_taxi_engoa", contents = "RandomF100TruckContents" },
				{ class = "sedan_taxi_fullout", contents = "RandomF100TruckContents" },
			},
			initialMinVehicles = 6,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "suv_basic",
			classes =
			{
				{
					class = "suv_basic", contents = "RandomF100TruckContents",
					skins =
					{
						-- If the total doesn't equal 100% then the remainder will spawn with the default skin
						{ name = "SUVBasic_Black", percent = 11.0 },
						{ name = "SUVBasic_Blue", percent = 11.0 },
						{ name = "SUVBasic_Green", percent = 11.0 },
						{ name = "SUVBasic_Orange", percent = 12.0 },
						{ name = "SUVBasic_Pink", percent = 11.0 },
						{ name = "SUVBasic_Purple", percent = 11.0 },
						{ name = "SUVBasic_Red", percent = 11.0 },
						{ name = "SUVBasic_White", percent = 11.0 }, -- Default
						{ name = "SUVBasic_Yellow", percent = 11.0 },
					},
				},
			},
			initialMinVehicles = 6,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "towcar",
			classes =
			{
				{ class = "towcar", contents = "RandomTowcarContents" },
			},
			initialMinVehicles = 4,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "tractor",
			classes =
			{
				{ class = "tractor" },
			},
			initialMinVehicles = 2,
			abandonedTimer = 17280000,  -- two days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "truck_5ton",
			classes =
			{
				{ class = "truck_5ton", contents = "RandomTruck5TonContents" },
			},
			initialMinVehicles = 4,
			abandonedTimer = 25920000,  -- three days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

		------------------------------------------------
		------------------------------------------------
		{
			category = "truck_semi",
			classes =
			{
				{
					class = "truck_semi", contents = "RandomTruck5TonContents",
					skins =
					{
						-- If the total doesn't equal 100% then the remainder will spawn with the default skin
						{ name = "TruckSemi_Black", percent = 11.0 },
						{ name = "TruckSemi_Blue", percent = 11.0 },
						{ name = "TruckSemi_Green", percent = 11.0 },
						{ name = "TruckSemi_Orange", percent = 11.0 },
						{ name = "TruckSemi_Pink", percent = 11.0 },
						{ name = "TruckSemi_Purple", percent = 11.0 },
						{ name = "TruckSemi_Red", percent = 11.0 },
						{ name = "TruckSemi_White", percent = 12.0 }, -- Default
						{ name = "TruckSemi_Yellow", percent = 11.0 },
					},
				},
			},
			initialMinVehicles = 4,
			abandonedTimer = 17280000, -- three days
			abandonedRespawnTimer = 3600, -- one hour
			destroyedTimer = 120,
			destroyedRespawnTimer = 7200, -- two hours
		},

	},
}

--------------------------------------------------------------------------
-- Functions called from C++
--------------------------------------------------------------------------
function VehicleSpawnerManager:OnInit()
	--Log("VehicleSpawnerManager:OnInit");

	self:OnReset();
end

------------------------------------------------------------------------------------------------------
-- OnPropertyChange called only by the editor.
------------------------------------------------------------------------------------------------------
function VehicleSpawnerManager:OnPropertyChange()
	self:Reset();
end

------------------------------------------------------------------------------------------------------
-- OnReset called only by the editor.
------------------------------------------------------------------------------------------------------
function VehicleSpawnerManager:OnReset()
	--Log("VehicleSpawnerManager:OnReset");
	self:Reset();
end

------------------------------------------------------------------------------------------------------
-- OnSpawn called Editor/Game.
------------------------------------------------------------------------------------------------------
function VehicleSpawnerManager:OnSpawn()
	self:Reset();
end

function VehicleSpawnerManager:Reset()
	--Log("VehicleSpawnerManager:Reset");
end

-- Load mods
Script.LoadScriptFolder("scripts/spawners/vsm_mods", true, true)