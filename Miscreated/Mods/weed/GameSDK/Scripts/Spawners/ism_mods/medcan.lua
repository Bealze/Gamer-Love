-----------------------------------------------------------------------------------------------------------------------------
local newItem = {
  class = "medcan",
  percent = 22,
}
-----------------------------------------------------------------------------------------------------------------------------
local categoryToAdjust = FindInTable(ItemSpawnerManager.itemCategories, "category", "RandomMedical")
local categoryItemToAdjust1 = FindInTable(categoryToAdjust.classes, "class", "AdrenalineSyringe")
local categoryItemToAdjust2 = FindInTable(categoryToAdjust.classes, "class", "AntiradiationPills")
local categoryItemToAdjust3 = FindInTable(categoryToAdjust.classes, "class", "PotassiumIodidePills")
local categoryItemToAdjust4 = FindInTable(categoryToAdjust.classes, "class", "Antibiotics")
local categoryItemToAdjust5 = FindInTable(categoryToAdjust.classes, "class", "Aspirin")
local categoryItemToAdjust6 = FindInTable(categoryToAdjust.classes, "class", "Bandage")
local categoryItemToAdjust7 = FindInTable(categoryToAdjust.classes, "class", "HeatPack")
local categoryItemToAdjust8 = FindInTable(categoryToAdjust.classes, "class", "WaterPurificationTablets")
local categoryItemToAdjust9 = FindInTable(categoryToAdjust.classes, "class", "Rags")
local categoryItemToAdjust10 = FindInTable(categoryToAdjust.classes, "class", "RubbingAlcohol")
local categoryItemToAdjust11 = FindInTable(categoryToAdjust.classes, "class", "Salt")
-----------------------------------------------------------------------------------------------------------------------------
table.insert(categoryToAdjust.classes, newItem)
-----------------------------------------------------------------------------------------------------------------------------
categoryItemToAdjust1.percent = 4
categoryItemToAdjust2.percent = 6
categoryItemToAdjust3.percent = 5
categoryItemToAdjust4.percent = 10
categoryItemToAdjust5.percent = 8
categoryItemToAdjust6.percent = 15
categoryItemToAdjust7.percent = 10
categoryItemToAdjust8.percent = 7
categoryItemToAdjust9.percent = 2
categoryItemToAdjust10.percent = 8
categoryItemToAdjust11.percent = 3
-----------------------------------------------------------------------------------------------------------------------------