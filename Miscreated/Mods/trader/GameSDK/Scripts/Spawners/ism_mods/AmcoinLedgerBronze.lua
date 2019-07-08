 -- remove the block comment to enable

-- sample item spawn mod
-- once you created a new cgf, xml, icons for a new item you also need to make it spawn, this is a sample how to add it to the spawners

-- .. place as many files in the mods folder as you like they all will be executed in same order on client/server resulting in the same indicies etc.

-- insert an additional item spawn
local newItem = {
  class = "AmcoinLedgerBronze", -- the item class name needs to be the same as the one in the xml file
  percent = 20, -- the percentage will need to be substracted from an existing field in the category
  percent2 = 5,
}

local categoryToAdjust = FindInTable(ItemSpawnerManager.itemCategories, "category", "CheckoutContent")
local categoryToAdjust2 = FindInTable(ItemSpawnerManager.itemCategories, "category", "RandomMutantLoot")

local categoryItemToAdjust = FindInTable(categoryToAdjust.classes, "class", "AmcoinLedger") -- could also search for fitting "class" instead "category"
local categoryItemToAdjust2 = FindInTable(categoryToAdjust.classes, "class", "Rocks")

-- Add new item at the end of list
table.insert(categoryToAdjust.classes, newItem)

-- Now adjust the item chance so they total 100% again
categoryItemToAdjust.percent = 63
categoryItemToAdjust.percent = 40

-- examine the log file if everything is correct, in doubt you can log information out using System.Log/server.log
--dump(ItemSpawnerManager) -- this is a lot
dump(categoryToAdjust) -- just the category (smarter choice to debug ;))

