<?php
require("helpers.php");
$filename = './csv_files/generate_positions.csv'; // csv file should only contain the columns 'sku' and 'related_product_sku'.
$file = fopen($filename, 'r');
$data = csvFileToArray($file);

$new_filename = './csv_files/Generate Related Positions/related_skus.csv';
$new_file = fopen($new_filename, 'w');
fputcsv($new_file, array('sku', 'related_product_sku', 'related_position'));

foreach($data as $product)
{
    $related_skus = $product['related_product_sku'];
    $related_skus_position = '';
    if($related_skus != '')
    {
        $related_skus = explode(',', $product['related_product_sku']);
        $related_skus_count = count($related_skus);
        for($i = 1; $i <= $related_skus_count; $i++)
        {
            $related_skus_position .= $i != $related_skus_count ? $i . ',' : $i;
        }
        $product['related_position'] = $related_skus_position;
        fputcsv($new_file, $product);
        continue;
    }
    fputcsv($new_file, $product);
}

echo "<h3>New file created ($new_filename).</h3>";
echo "<h3>Open the file and use the values from the 'related_position' column and copy them over to the product export file 'related_position'. If the column does not exist, insert it next to the 'related_product_sku'</h3>";