-----------------------------------------------------------------------------------------------------------------------------
local newItem = {
  class = "zipzap",
  percent = 49,
}
-----------------------------------------------------------------------------------------------------------------------------
local categoryToAdjust = FindInTable(ItemSpawnerManager.itemCategories, "category", "RandomMutantLoot")
local categoryItemToAdjust1 = FindInTable(categoryToAdjust.classes, "class", "Rocks")
-----------------------------------------------------------------------------------------------------------------------------
table.insert(categoryToAdjust.classes, newItem)
-----------------------------------------------------------------------------------------------------------------------------
categoryItemToAdjust1.percent = 1
-----------------------------------------------------------------------------------------------------------------------------