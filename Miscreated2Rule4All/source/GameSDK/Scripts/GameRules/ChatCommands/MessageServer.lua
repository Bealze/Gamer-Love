-- Sends the message <message> to the entire server in the chat window
ChatCommands["!wmsg"] = function(playerId, command)
	Log(">> !wmsg - %s", command)

	local player = System.GetEntity(playerId)
	--local allowCommand = allowCommand or nil ~= string.match(System.GetCVar("g_gameRules_faction6_steamids"), steamid)
	local allowCommand = 6 == player.actor:GetFaction() -- faction 0 to 7 (same numbering as cvars)
  
	if allowCommand then
		g_gameRules.game:SendTextMessage(4, 0, command);
	end
end

-- Sends the message <message> to the entire server at the top of the screen
ChatCommands["!wann"] = function(playerId, command)
	Log(">> !wann - %s", command)

	local player = System.GetEntity(playerId)
	--local allowCommand = allowCommand or nil ~= string.match(System.GetCVar("g_gameRules_faction6_steamids"), steamid)
	local allowCommand = 6 == player.actor:GetFaction() -- faction 0 to 7 (same numbering as cvars)
  
	if allowCommand then
		g_gameRules.game:SendTextMessage(0, 0, command);
	end
end