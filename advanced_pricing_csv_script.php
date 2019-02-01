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

//$file = 'all_product_data.csv';
$file = fopen('Book1.csv', 'r');
$data = array();
$header = fgetcsv($file);
while ($row = fgetcsv($file))
{
    $data[] = array_combine($header, $row);
}

$advanced_pricing_csv_file = fopen('advanced_pricing_import.csv', 'w');
addHeaders($advanced_pricing_csv_file);

foreach ($data as $product)
{
    if(!empty($product['cws_tier_price']))
    {
        processTierPricing($product, $advanced_pricing_csv_file);
    }
    if(!empty($product['cws_group_price']))
    {
        processDiscountPricing($product, $advanced_pricing_csv_file);
    }

}

function processDiscountPricing($product, $file)
{
    $sku = $product['sku'];
    $cws_group_price = $product['cws_group_price'];
    $groups = explode(',', $cws_group_price);
    foreach($groups as $group)
    {
        $insert_data = array($sku, 'All Websites [USD]');
        $group_data = explode('=', $group);
        $group_id = $group_data[0];
        $group_qty = 1;
        $insert_data[] = group_id_names[$group_id];
        $insert_data[] = $group_qty;
        $insert_data[] = group_discount[$group_id];
        $insert_data[] = 'Discount';
        fputcsv($file, $insert_data);
        unset($insert_data);
    }
}

function processTierPricing($product, $file)
{
    $sku = $product['sku'];
    $cws_group_price = $product['cws_tier_price'];
    $groups = explode('|', $cws_group_price);
    foreach($groups as $group)
    {
        $insert_data = array($sku, 'All Websites [USD]');
        $group_data = explode('=', $group);
        $group_id = $group_data[0];
        $group_qty = $group_data[1];
        $group_price = $group_data[2];
        $insert_data[] = group_id_names[$group_id];
        $insert_data[] = $group_qty;
        $insert_data[] = $group_price;
        $insert_data[] = 'Fixed';
        fputcsv($file, $insert_data);
        unset($insert_data);
    }
}




















