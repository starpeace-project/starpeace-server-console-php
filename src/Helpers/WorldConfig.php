<?php

namespace Starpeace\Console\Helpers;

class WorldConfig
{
    public static function processConfig($configArray)
    {
        /**
         * The config array already contains our servers configuration data
         * parsed into an array for us to work on, now we need to process
         * and build a new array of categorised data that we can use to
         * produce a consistent view file for the world login pages to use.
         *
         * Categories we want:
         *
         * 1. General Settings
         *  CountUpgrades = If Upgrades are counted as building slots.
         *  NoSubsidies=1
         *  ElectionsOn=1
         *  SendingMoney=0
         *  AlliesPageOn=1
         *  TranscendingOn=1
         *  RandomSeasons=1
         *  RelayEconomy=0
         *  MagnaAtApprentice=1
         *  MaxInvestors=100
         *  NoTradeCenter=1
         *
         *
         * 2. Tycoon Upgrade Levels
         *  Apprentice.facLimit=50
         *  Entrepreneur.facLimit=100
         *  Tycoon.facLimit=200
         *  Master.facLimit=350
         *  Paradigm.facLimit=500
         *  Legend.facLimit=501
         *
         * 3. Boosts
         *  ResInhabBoost=5
         *  ConstBoost=3
         *  CommerceBoost=1
         *
         * 4. Upgrade Levels
         *  CountUpgrades = If Upgrades are counted as building slots.
         *  MaxServiceUpgrades
         *  MaxOfficeUpgrades
         *  ResMaxUpgrade
         *  MaxRCenterUpgrade=50
         *
         * 5. Misc Settings
         *  Tutorial=0
         *  RemoveVisitors=1
         *  RepairRoads=0
         *  VoteLevel=1
         *  FightFacColonies=yes
         *  LifeAfterLegend = ?
         *  ServiceBuysBatched=1
         *  RemoveVisitors=1
         *  RoadZone=11
         *  MinTaxesPer=20
         *  MinCivicsWage=150
         *  MaxSubPop=500
         *  MixedPlanet=1
         *  MixedDesire=1000
         *  ServiceBuysBatched=1
         *
         * 6. Building Separation
         *  MinCommerceSep=2
         *  MinOfficeSep=1
         *
         * 7. Thread Speeds / Priorities
         *  SimSpeed=3
         *  DASpeed=4
         *  CacheSpeed=4
         *  IntSpeed=0
         *  Trans=0
         *
         */

        $baseCategories = [
            'general_settings' => [
                'display_name' => 'General Settings',
                'display' => 'block',
                'settings' => [
                    'CountUpgrades' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Count Upgrades',
                        'tool_tip' => 'Count upgrades as building slots',
                        'description' => 'Count upgrades as building slots',
                    ],
                    'NoSubsidies' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Town Subsidies',
                        'tool_tip' => 'Town subsidies at low population',
                        'description' => 'Town subsidies at low population',
                    ],
                    'ElectionsOn' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Elections',
                        'tool_tip' => 'Elections enabled or not',
                        'description' => 'Election of Mayor and Presidents enabled',
                    ],
                    'SendingMoney' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Money Transfers',
                        'tool_tip' => 'Allow money transfers between players',
                        'description' => 'Allow money transfers between players',
                    ],
                    'AlliesPageOn' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Allies',
                        'tool_tip' => 'Allow player alliances',
                        'description' => 'Allow player alliances',
                    ],
                    'TranscendingOn' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Transcendence',
                        'tool_tip' => 'Allow nobility gain through transcendence',
                        'description' => 'Allow nobility gain through transcendence',
                    ],
                    'RandomSeasons' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Random Seasons',
                        'tool_tip' => 'Random game seasons in suppose to spring, summer, autumn, winter cycle',
                        'description' => 'Random game seasons in suppose to spring, summer, autumn, winter cycle',
                    ],
                    'RelayEconomy' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Recession Code',
                        'tool_tip' => 'Whether recession code is active in simulation',
                        'description' => 'Whether recession code is active in simulation',
                    ],
                    'MagnaAtApprentice' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Apprentice Magna',
                        'tool_tip' => 'If apprentices with enough nobility can create magna companies',
                        'description' => 'If apprentices with enough nobility can create magna companies',
                    ],
                    'MaxInvestors' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Max World Players',
                        'tool_tip' => 'Maximum player allowed to join this world',
                        'description' => 'Maximum player allowed to join this world',
                    ],
                    'NoTradeCenter' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'No Trade Centre',
                        'tool_tip' => 'Prevent trade centre sales',
                        'description' => 'Prevent trade centre sales',
                    ],
                ],
            ],
            'tycoon_upgrade_levels' => [
                'display_name' => 'Tycoon Building Slots',
                'display' => 'block',
                'settings' => [
                    'Apprentice.facLimit' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Apprentice',
                        'tool_tip' => 'Maximum building slots for this level',
                        'description' => 'Maximum building slots for this level',
                    ],
                    'Entrepreneur.facLimit' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Entrepreneur',
                        'tool_tip' => 'Maximum building slots for this level',
                        'description' => 'Maximum building slots for this level',
                    ],
                    'Tycoon.facLimit' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Tycoon',
                        'tool_tip' => 'Maximum building slots for this level',
                        'description' => 'Maximum building slots for this level',
                    ],
                    'Master.facLimit' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Master',
                        'tool_tip' => 'Maximum building slots for this level',
                        'description' => 'Maximum building slots for this level',
                    ],
                    'Paradigm.facLimit' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Paradigm',
                        'tool_tip' => 'Maximum building slots for this level',
                        'description' => 'Maximum building slots for this level',
                    ],
                    'Legend.facLimit' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Legend',
                        'tool_tip' => 'Maximum building slots for this level',
                        'description' => 'Maximum building slots for this level',
                    ],
                ],
            ],
            'building_upgrade_levels' => [
                'display_name' => 'Building Max Upgrades',
                'display' => 'block',
                'settings' => [
                    'CountUpgrades' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Count Upgrades',
                        'tool_tip' => 'Count building upgrades as a building slot',
                        'description' => 'Count building upgrades as a building slot',
                    ],
                    'MaxServiceUpgrades' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Service Maximum',
                        'tool_tip' => 'Maximum upgrade level for services',
                        'description' => 'Maximum upgrade level for services',
                    ],
                    'MaxOfficeUpgrades' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Office Maximum',
                        'tool_tip' => 'Maximum upgrade level for offices',
                        'description' => 'Maximum upgrade level for offices',
                    ],
                    'ResMaxUpgrade' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Residential Maximum',
                        'tool_tip' => 'Maximum upgrade level for residencies',
                        'description' => 'Maximum upgrade level for residencies',
                    ],
                    'MaxRCenterUpgrade' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Research Centre Maximum',
                        'tool_tip' => 'Maximum upgrade level for research centres',
                        'description' => 'Maximum upgrade level for research centres',
                    ],
                ],
            ],
            'misc_settings' => [
                'display_name' => 'Misc Settings',
                'display' => 'block',
                'settings' => [
                    'Tutorial' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Tutorial Mode',
                        'tool_tip' => 'If enabled players are guided by a tutorial',
                        'description' => 'If enabled players are guided by a tutorial',
                    ],
                    'RemoveVisitors' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Remove Visitors',
                        'tool_tip' => 'Prevent visitor access to the world',
                        'description' => 'Prevent visitor access to the world',
                    ],
                    'RepairRoads' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Repair Roads',
                        'tool_tip' => 'Automatically repair roads',
                        'description' => 'Automatically repair roads',
                    ],
                    'VoteLevel' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Vote Level',
                        'tool_tip' => 'Votes required for office',
                        'description' => 'Votes required for office',
                    ],
                    'FightFacColonies' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Fighting faction colonies',
                        'tool_tip' => 'Fighting faction colonies',
                        'description' => 'Fighting faction colonies',
                    ],
                    'LifeAfterLegend' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Life after Legend',
                        'tool_tip' => 'Whether Tycoon can continue level after legend',
                        'description' => 'Whether Tycoon can continue level after legend',
                    ],
                    'ServiceBuysBatched' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Services Buy Batched',
                        'tool_tip' => 'Services buy supplies in batches',
                        'description' => 'Services buy supplies in batches',
                    ],
                    'RoadZone' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Road Zone',
                        'tool_tip' => 'The zone that roads belong to',
                        'description' => 'The zone that roads belong to',
                    ],
                    'MinTaxesPer' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Minimum Taxes',
                        'tool_tip' => 'Minimum tax level',
                        'description' => 'Minimum tax level',
                    ],
                    'MinCivicsWage' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Minimum Civic Wages',
                        'tool_tip' => 'This is the minimum wage level a Tycoon can set for civic wages',
                        'description' => 'This is the minimum wage level a Tycoon can set for civic wages',
                    ],
                    'MaxSubPop' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Maximum Subsidy Population',
                        'tool_tip' => 'The level of population after which subsidies will no longer be paid',
                        'description' => 'The level of population after which subsidies will no longer be paid',
                    ],
                    'MixedPlanet' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Mixed Planet',
                        'tool_tip' => 'An as yet unknown setting',
                        'description' => 'An as yet unknown setting',
                    ],
                    'MixedDesire' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Mixed Desire',
                        'tool_tip' => 'An as yet unknown setting',
                        'description' => 'An as yet unknown setting',
                    ],
                ],
            ],
            'boosts' => [
                'display_name' => 'Simulation Boosts',
                'display' => 'block',
                'settings' => [
                    'ResInhabBoost' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Residential Boost',
                        'tool_tip' => 'Residential Boost',
                        'description' => 'Residential Boost',
                    ],
                    'ConstBoost' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Construction Boost',
                        'tool_tip' => 'Construction Boost',
                        'description' => 'Construction Boost',
                    ],
                    'CommerceBoost' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Commerce Boost',
                        'tool_tip' => 'Commerce Boost',
                        'description' => 'Commerce Boost',
                    ],
                ],
            ],
            'building_separation' => [
                'display_name' => 'Building Separation',
                'display' => 'block',
                'settings' => [
                    'MinCommerceSep' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Commerce',
                        'tool_tip' => 'How far apart commerce buildings of the same class must be',
                        'description' => 'How far apart commerce buildings of the same class must be',
                    ],
                    'MinOfficeSep' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Office',
                        'tool_tip' => 'How far apart office buildings of the same class must be',
                        'description' => 'How far apart office buildings of the same class must be',
                    ],
                ],
            ],
            'thread_speeds' => [
                'display_name' => 'Thread Speeds',
                'display' => 'none',
                'settings' => [
                    'SimSpeed' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Model Server',
                        'tool_tip' => 'Simulation Speed',
                        'description' => 'Simulation Speed',
                    ],
                    'DASpeed' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Direct Access',
                        'tool_tip' => 'Speed of something',
                        'description' => 'Speed of something',
                    ],
                    'CacheSpeed' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Cache Server',
                        'tool_tip' => 'Cache server speed',
                        'description' => 'Cache server speed',
                    ],
                    'IntSpeed' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Interface Server',
                        'tool_tip' => 'Interface Server speed',
                        'description' => 'Interface Server speed',
                    ],
                    'Trans' => [
                        'setting' => '',
                        'default' => '',
                        'display' => 'block',
                        'input_type' => 'text',
                        'display_name' => 'Transcendence',
                        'tool_tip' => 'Speed of transcendence',
                        'description' => 'Speed of transcendence',
                    ],
                ],
            ],
        ];

        foreach ($baseCategories as $categoryKey => &$category) {
            foreach ($category['settings'] as $settingName => &$setting) {
                $setting['setting'] = $configArray[$settingName] ?? $setting['default'];
            }
        }

        return self::makeTables($baseCategories);
    }

    public static function makeTables($tableData, $columnCount = 3)
    {
        $rows = array_chunk($tableData, $columnCount, true);

        $tables = [];

        foreach ($rows as $row) {
            foreach ($row as $columnName => $columnContents) {
                $table = '<table style="display: ' . $columnContents['display'] . '">';
                $table .= '<tr style="color: orange; font-weight: bold;">';
                $table .= '<td colspan="2" style="text-align: center;">';
                $table .= $columnContents['display_name'];
                $table .= '</td>';
                $table .= '<td colspan="1" style="text-align: center;">';
                $table .= 'Value';
                $table .= '</td>';
                $table .= '</tr>';
                foreach ($columnContents['settings'] as $setting) {
                    $noValue = empty($setting['setting']) && empty($setting['default']);
                    $display = $noValue ? 'none' : $setting['display'];
                    $table .= '<tr style="color: darkgreen; display: '.$display.'">';
                    $table .= '<td colspan="2">';
                    $table .= '<span class="tooltip">';
                    $table .= '<span class="tooltiptext">';
                    $table .= $setting['tool_tip'];
                    $table .= '</span>';
                    $table .= $setting['display_name'];
                    $table .= '</span>';
                    $table .= '</td>';
                    $table .= '<td colspan="1" style="text-align: center; font-weight: bold;">';
                    $table .= '<span class="tooltip">';
                    $table .= '<span class="tooltiptext">';
                    $table .= $setting['tool_tip'];
                    $table .= '</span>';
                    $table .= !empty($setting['setting']) ? $setting['setting'] : $setting['default'];
                    $table .= '</span>';
                    $table .= '</td>';
                    $table .= '</tr>';
                }
                $table .= '</table>';

                $tables[] = $table;
            }

        }

        // Now build the actual parent table of our tables

        $parentTable = self::getToolTipStyle() . '<table style="background-color: black; color: white; width: 90%">';
        $parentTable .= '<tr>';
        $parentTable .= '<td colspan="' . $columnCount . '" style="font-size: 3em; color: orange; text-align: center">';
        $parentTable .= 'WORLD SETTINGS';
        $parentTable .= '</td>';
        $parentTable .= '</tr>';

        $rows = array_chunk($tables, $columnCount, true);


        foreach ($rows as $columns) {
            $parentTable .= '<tr>';
            foreach ($columns as $column) {
                $parentTable .= '<td valign="top" align="center">';
                $parentTable .= $column;
                $parentTable .= '</td>';
            }
            $parentTable .= '</tr>';
        }
        $parentTable .= '</table>';


        if (!empty(TESTING) && TESTING) {
            if (!empty(TESTING_PATH)) {
                file_put_contents(TESTING_PATH . 'testView.html', $parentTable);
            }
        }

        return $parentTable;
    }

    public static function getToolTipStyle()
    {
        return "<style>
.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 200px;
  background-color: black;
  color: orange;
  text-align: center;
  border: white;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
</style>";
    }
}