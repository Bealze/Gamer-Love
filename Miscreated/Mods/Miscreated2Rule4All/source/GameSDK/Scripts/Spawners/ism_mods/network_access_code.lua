

local newItem = {
  class = "network_access_code", 
  percent = 4, 
}

local categoryToAdjust = FindInTable(ItemSpawnerManager.itemCategories, "category", "RandomCraftingGuide")
local categoryItemToAdjust1 = FindInTable(categoryToAdjust.classes, "class", "guide_medical_bandages") 
local categoryItemToAdjust2 = FindInTable(categoryToAdjust.classes, "class", "guide_weapons_melee")
local categoryItemToAdjust3 = FindInTable(categoryToAdjust.classes, "class", "guide_structures_tire_stacks")

table.insert(categoryToAdjust.classes, newItem)

categoryItemToAdjust1.percent = 3
categoryItemToAdjust2.percent = 3
categoryItemToAdjust3.percent = 3





