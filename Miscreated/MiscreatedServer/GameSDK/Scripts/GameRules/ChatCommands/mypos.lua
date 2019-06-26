-- Send the player's position back to them via chat
ChatCommands["!mypos"] = function(playerId, command)
	Log(">> !mypos - %s", command);
	--Change Faction to what ever faction can use this command
	--local allowCommand = 4 == player.actor:GetFaction() -- faction 0 to 7 (same numbering as cvars)
		local player = System.GetEntity(playerId)
		local pos = player:GetWorldPos()
	--end
	g_gameRules.game:SendTextMessage(4, playerId, string.format("Your position is: %.1f %.1f %.1f", pos.x, pos.y, pos.z));
end