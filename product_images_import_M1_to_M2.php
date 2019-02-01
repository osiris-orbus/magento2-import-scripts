<?php
require("helpers.php");
$file_name = 'copy_product_images.csv';
$file = fopen($file_name, 'r');
$data = csvFileToArray($file);
$product_data = array();
$i = 0;
foreach($data as $product)
{
    if($product['_media_image'] == '' || $product['status'] == 2)
    {
        continue;
    }
    elseif($product['sku'] != '')
    {
        $i++;
        $product_data[$i]['sku'] = $product['sku'];
        $product_data[$i]['status'] = $product['status'];
        $product_data[$i]['image'] = $product['image'];
        $product_data[$i]['thumbnail'] = $product['thumbnail'];
        $product_data[$i]['media_image'] = $product['_media_image'];
        $product_data[$i]['media_image'] = $product['_media_image'];
    }
    else
    {
        $product_data[$i]['other_images'][] = $product['_media_image'];
    }

}
print_f($product_data);