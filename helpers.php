<?php

function print_f($arr)
{
    echo "<pre>" . print_r($arr, true) . "</pre>";
}

function addHeaders($file)
{
    $headers = array('sku', 'tier_price_website', 'tier_price_customer_group', 'tier_price_qty', 'tier_price', 'tier_price_value_type');
    fputcsv($file, $headers);
}

function csvFileToArray($file)
{
    $data = array();
    $header = fgetcsv($file);
    while ($row = fgetcsv($file))
    {
        $data[] = array_combine($header, $row);
    }
    return $data;
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
