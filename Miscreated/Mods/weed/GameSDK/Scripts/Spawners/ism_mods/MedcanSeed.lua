-----------------------------------------------------------------------------------------------------------------------------
local newItem = {
  class = "SeedsMedcan",
  percent = 10,
}
-----------------------------------------------------------------------------------------------------------------------------
local categoryToAdjust = FindInTable(ItemSpawnerManager.itemCategories, "category", "RandomSeeds")
local categoryItemToAdjust1 = FindInTable(categoryToAdjust.classes, "class", "SeedsBeets")
-----------------------------------------------------------------------------------------------------------------------------
table.insert(categoryToAdjust.classes, newItem)
-----------------------------------------------------------------------------------------------------------------------------
categoryItemToAdjust1.percent = 3
-----------------------------------------------------------------------------------------------------------------------------