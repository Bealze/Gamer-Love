-- The follow are some example chat commands
-- If you want to use them, please copy them into their own lua file and then upload them as a mod
-- For example: mirror.lua, spawn.lua, and give.lua

-- !mirror <message>
-- Sends the message <message> back to the invoking player's chat window
-- The 4 below sends to the chat message window, using 0 it will appear at the top of the player's screen
-- Replace playerId with a 0 to send to all clients
ChatCommands["!mirror"] = function(playerId, command)
	Log(">> !mirror - %s", command)

	g_gameRules.game:SendTextMessage(4, playerId, command);
end