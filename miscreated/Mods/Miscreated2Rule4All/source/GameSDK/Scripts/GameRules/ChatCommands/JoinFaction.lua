-- !joinf <factionno>
-- Joins the faction if its enabled and possible (forced: without requiring server restart)
-- <factionno> 0-7 (1,2 invalid)
ChatCommands["!joinf"] = function(playerId, command)
	Log(">> !joinf - %s", command)
  
	local player = System.GetEntity(playerId)
	
	player.actor:SetFaction( tonumber(command), true ) -- param is factionno, forced (forced meaning, player can switch faction at any point without server restart)
end