-- The follow are some example chat commands
-- If you want to use them, please copy them into their own lua file and then upload them as a mod
-- For example: mirror.lua, spawn.lua, and give.lua
-----------------------------------------------------------------------
-----------------------------------------------------------------------
-- !spawn <item_name>
-- Spawns the <item_name> 2m in front of the player at their feet level
-- <item_name> can be any valid item name in the game -ex. AT15
ChatCommands["!spawn"] = function(playerId, command)
	Log(">> !spawn - %s", command)

	local player = System.GetEntity(playerId)
  
  
   local steamid = player.player:GetSteam64Id()
    -- Only allow the following SteamId to invoke the command
  	--local allowCommand = steamid == "76561198870535366" -- change this to some valid admin's Steam64 id
  
  -- or through faction restrictions change "g_gameRules_faction6_steamids" to suit your faction of choice
   --local allowCommand = allowCommand or nil ~= string.match(System.GetCVar("g_gameRules_faction6_steamids"), steamid)
  
  -- or through actual current faction
  local allowCommand = 6 == player.actor:GetFaction() -- faction 0 to 7 (same numbering as cvars)
  
	if allowCommand then
		local vForwardOffset = {x=0,y=0,z=0}
		FastScaleVector(vForwardOffset, player:GetDirectionVector(), 2.0)

		local vSpawnPos = {x=0,y=0,z=0}
		FastSumVectors(vSpawnPos, vForwardOffset, player:GetWorldPos())

		ISM.SpawnItem(command, vSpawnPos)
	end
end