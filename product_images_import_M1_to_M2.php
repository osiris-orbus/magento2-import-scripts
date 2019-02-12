<?php
require_once("helpers.php");
if(isset($_POST['skus']) || isset($_POST['all_skus']))
{
    $all_skus = $_POST['all_skus'];
    $skus = strpos($_POST['skus'], '^^ ') !== false ? explode(' ', $_POST['skus']) : array($_POST['skus']);
    $file_name = './csv_files/products_image_only.csv';
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
    }
    fclose($file);
    unset($data);

    $new_csv_file = './csv_files/image_associations.csv';
    $image_associations_csv = fopen($new_csv_file, 'w');
    addHeaders($image_associations_csv, 'images');

    if($all_skus)
    {
        foreach($product_data as $product)
        {
            $row_data = array(
                'sku'                   => $product['sku'],
                'base_image'            => $product['image'],
                'base_image_label'      => $product['image'],
                'small_image'           => $product['image'],
                'small_image_label'     => $product['image'],
                'thumbnail_image'       => $product['image'],
                'thumbnail_image_label' => $product['image'],
                'additional_images'     => implode(',', $product['other_images'])
            );
            fputcsv($image_associations_csv, $row_data);
        }
    }
    else
    {
        foreach($product_data as $product)
        {
            if(in_array($product['sku'], $skus))
            {
                $row_data = array(
                    'sku'                   => $product['sku'],
                    'base_image'            => $product['image'],
                    'base_image_label'      => $product['image'],
                    'small_image'           => $product['image'],
                    'small_image_label'     => $product['image'],
                    'thumbnail_image'       => $product['image'],
                    'thumbnail_image_label' => $product['image'],
                    'additional_images'     => implode(',', $product['other_images'])
                );
                fputcsv($image_associations_csv, $row_data);
            }
        }
    }
    echo "<h1>File $new_csv_file has been created!</h1>";
}

?>

<form method="post" action="#">
    <h3>Please enter a SKU or multiple SKU's to generate images csv file for Magento 2 import.</h3>
    <h3>For multiple skus, separate by a space.</h3>
    <input type="text" placeholder="SKU(s)" name="skus" style="width: 800px;"><br/><br/>
    <input type="checkbox" name="all_skus">All SKU's<br/><br/>
    <button type="submit">Process</button>
</form>
