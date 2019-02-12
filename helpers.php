<?php

function print_f($arr)
{
    echo "<pre>" . print_r($arr, true) . "</pre>";
}

/*
 * First row of csv file (header values) needs to contain the following values in order to import correctly to M2.
 */
function addHeaders($file, $import_type)
{
    if ($import_type == 'pricing')
        $headers = array('sku', 'tier_price_website', 'tier_price_customer_group', 'tier_price_qty', 'tier_price', 'tier_price_value_type');
    elseif ($import_type == 'images')
        $headers = array('sku', 'base_image', 'base_image_label', 'small_image', 'small_image_label', 'thumbnail_image', 'thumbnail_image_label', 'additional_images');
    elseif($import_type == 'images_generate')
        $headers = array('sku', 'base_image', 'base_image_label', 'small_image', 'small_image_label', 'thumbnail_image', 'thumbnail_image_label', 'additional_images');
    else
        return '$import_type not correct';
    return fputcsv($file, $headers);
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

function processPricing($product, $file, $price_type)
{
    $sku = $product['sku'];
    $cws_price = $price_type == 'tier' ? $product['cws_tier_price'] : $product['cws_group_price'];
    $prices =  $price_type == 'tier' ? explode('|', $cws_price) : explode(',', $cws_price);
    foreach($prices as $price)
    {
        $insert_data = array($sku, 'All Websites [USD]');
        $price_data = explode('=', $price);
        $customer_group_id = $price_data[0];
        $customer_group_qty = $price_type == 'tier' ? $price_data[1] : 1;
        $customer_group_price = $price_type == 'tier' ? $price_data[2] : $price_data[1];
        $insert_data[] = group_id_names[$customer_group_id];
        $insert_data[] = $customer_group_qty;
        $insert_data[] = $customer_group_price;
        $insert_data[] = 'Fixed';
        fputcsv($file, $insert_data);
        unset($insert_data);
    }
}