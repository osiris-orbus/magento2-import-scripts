<?php
require("helpers.php");

define('group_id_names', array(
    0 => 'NOT LOGGED IN',
    4 => 'MSRP Standard',
    5 => 'MSRP Advanced',
    6 => 'Tier 1',
    7 => 'Tier 2',
    8 => 'Tier 3',
    9 => 'Tier 4',
    10 => 'Tier 5'
));

define('group_discount', array(
    0 => 0,
    4 => 0,
    5 => 0,
    6 => 60.00,
    7 => 58.00,
    8 => 55.80,
    9 => 55.80,
    10 => 55.80,
));

$filename = 'latest_product_export_modified.csv';
$file = fopen($filename, 'r');
$data = csvFileToArray($file);
$advanced_pricing_csv_file = fopen('advanced_pricing_import.csv', 'w');
addHeaders($advanced_pricing_csv_file); // We add the Magento 2 header values to the first row of the csv file needed to correctly import advanced product pricing.
foreach ($data as $product)
{
    if(!empty($product['cws_tier_price'])) // Check if product contains Tier Pricing.
    {
        processTierPricing($product, $advanced_pricing_csv_file);
    }
    if(!empty($product['cws_group_price'])) // Check if product contains Group Pricing.
    {
        processDiscountPricing($product, $advanced_pricing_csv_file);
    }
}

echo "<h1>Complete!</h1>";




















