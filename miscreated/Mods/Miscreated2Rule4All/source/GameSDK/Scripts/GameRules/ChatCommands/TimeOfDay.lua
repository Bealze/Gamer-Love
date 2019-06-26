-- !time
  -- Changes Time of Day/Night on the server (by number)
  ChatCommands["!time"] = function(playerId, command)
	Log(">> !time - %s", command)

	local player = System.GetEntity(playerId)
  
  -- Only allow the following SteamId to invoke the command 
  local steamid = player.player:GetSteam64Id()
  --local allowCommand = steamid == "76561198082291600" -- change this to some valid admin's Steam64 id
  
  -- or through faction restrictions
  --local allowCommand = allowCommand or nil ~= string.match(System.GetCVar("g_gameRules_faction6_steamids"), steamid)
  
  -- or through actual current faction
  local allowCommand = 6 == player.actor:GetFaction() -- faction 0 to 7 (same numbering as cvars)
  
	if allowCommand then
    System.ExecuteCommand("wm_forceTime " .. command)
    --g_gameRules.game:SendTextMessage(0, 0, playerid, command);
    --g_gameRules.game:SendTextMessage(4, playerId, string.format("Your position is: %.1f %.1f %.1f", pos.x, pos.y, pos.z));
	end
end