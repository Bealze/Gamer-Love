-- Summon a player by SteamId to your position
ChatCommands["!summon"] = function(playerId, command)
	Log(">> !summon - %s", command);

		local player = System.GetEntity(playerId)
		--Change Faction to what ever faction can use this command
		--local allowCommand = allowCommand or nil ~= string.match(System.GetCVar("g_gameRules_faction6_steamids"), steamid)
		local allowCommand = 6 == player.actor:GetFaction() -- faction 0 to 7 (same numbering as cvars)
		
		if allowCommand then
			-- Performing a generic entity search is very expensive - use sparingly
			local players = System.GetEntitiesByClass("Player")

			for i,p in pairs(players) do 
				if p.player:GetSteam64Id() == command then
					p.player:TeleportTo(playerId)
					return;
				end
			end
		end
	g_gameRules.game:SendTextMessage(4, playerId, "A player with the SteamId does not exist on the server.");
end