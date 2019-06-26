-- !heal 
-- Heals the player to full health
ChatCommands["!heal"] = function(playerId, command)
	Log(">> !heal - %s", command)

	local player = System.GetEntity(playerId)
  
  
   	local steamid = player.player:GetSteam64Id()
    -- Only allow the following SteamId to invoke the command
  	--local allowCommand = steamid == "76561198870535366" -- change this to some valid admin's Steam64 id
  
  -- or through faction restrictions change "g_gameRules_faction6_steamids" to suit your faction of choice
   	--local allowCommand = allowCommand or nil ~= string.match(System.GetCVar("g_gameRules_faction6_steamids"), steamid)
  
  -- or through actual current faction
  local allowCommand = 6 == player.actor:GetFaction() -- faction 0 to 7 (same numbering as cvars)
  
	if allowCommand then
		player.actor:SetHealth(100.0);
	end
	g_gameRules.game:SendTextMessage(4, playerId, command);
end