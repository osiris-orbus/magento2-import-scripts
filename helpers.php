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

