<?php
require("helpers.php");
$file_name = 'products_image_only.csv';
//$file_name = 'copy_product_images.csv';
//$file_name = 'test_image_parsing.csv';
$file = fopen($file_name, 'r');
$data = csvFileToArray($file);
$product_data = array();
$i = 0;
$product = null;
for($n = 0; $n < count($data); $n++)
{
    $product = $data[$n];
    if($product['sku'] != '' && $product['status'] == 1 && $product['_media_image'] != '' && $product['visibility'] == 4)
    {
        $product_data[$i]['sku'] = $product['sku'];
        $product_data[$i]['image'] = $product['image'];
        $product_data[$i]['thumbnail'] = $product['thumbnail'];
        $product_data[$i]['media_image'] = $product['_media_image'];
        $other_images = 0;
        while($other_images <= 51)
        {
            if($data[$n]['_media_image'] != null)
            {
                $product_data[$i]['other_images'][] = $data[$n]['_media_image'];
                $other_images++;
                $n++;
            }
            else
            {
                $other_images++;
                $n++;
                continue;
            }
        }
        $i++;
    }
    unset($product);
}
print_f($product_data);
exit;



//print_f($product_data);exit;
//$new_csv_file = 'image_associations.csv';
//$image_associations_csv = fopen($new_csv_file, 'w');
//addHeaders($image_associations_csv, 'images');
//
//foreach($product_data as $product)
//{
//    $row_data = array(
//        'sku' => $product['sku'],
//        'base_image' => $product['image'],
//        'base_image_label' => $product['image'],
//        'small_image' => $product['image'],
//        'small_image_label' => $product['image'],
//        'thumbnail_image' => $product['image'],
//        'thumbnail_image_label' => $product['image'],
//        'additional_images' => implode(',', $product['other_images'])
//    );
//    fputcsv($image_associations_csv, $row_data);
//    print_f($row_data);exit;
//}

