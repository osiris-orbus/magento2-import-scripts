<?php
/**
 * Created by PhpStorm.
 * User: osiris
 * Date: 4/8/2019
 * Time: 11:06 AM
 */
class ExportFileConverter
{
    public $correct_header_values = array(
        'store'                  => 'store_view_code',
        'websites'               => 'product_websites',
        'attribute_set'          => 'attribute_set_code',
        'type'                   => 'product_type',
        'status'                 => 'product_online',
        'tax_class_id'           => 'tax_class_name',
        'image'                  => 'base_image',
        'thumbnail'              => 'thumbnail_image',
        'gallery'                => 'additional_images',
        'bundle_product_options' => 'bundle_values'
    );

    public function uploadFile($files, $dir, $file_type)
    {
        $target_file = $dir . basename($files[$file_type]["name"]);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (file_exists($target_file))
        {
            echo "Sorry, file already exists.";
        }
        elseif ($fileType != "csv")
        {
            echo "Sorry, only CSV files are allowed.";
        }
        else
        {
            if (move_uploaded_file($files[$file_type]["tmp_name"], $target_file))
            {
                echo "The file " . basename($files[$file_type]["name"]) . " has been uploaded.";
                return $target_file;
            }
            else
            {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        return $target_file;
    }

    public function updateHeaderValues($headers, $correct_header_values)
    {
        foreach($headers as $key => $header)
        {
            if(array_key_exists($header, $correct_header_values))
            {
                $headers[$key] = $correct_header_values[$header];
            }
        }
        return $headers;
    }

    public function splitByAttributeSet($file, $headers)
    {
        $data = csvFileToArray($file, $headers);
        $attribute_set = '';
        $new_attribute_set_file = null;
        $i = 0;
        $file_directory = './csv_files/automated_files/'.time();
        foreach($data as $product)
        {
            if($product['product_online'] == 'Disabled') // If product is disabled, skip.
                continue;
            if($attribute_set != $product['attribute_set_code']) // If we encounter a new attribute set.
            {
                $this->unsetHeaderAndProductValues($product, $headers);
                $attribute_set = $product['attribute_set_code'];
                mkdir($file_directory, 0777);
                $new_file_name = $file_directory.'/'.str_replace('/','-', $attribute_set).'.csv';
                $new_attribute_set_file = fopen($new_file_name, 'w+');
                fputcsv($new_attribute_set_file, $headers);
                $i++;
            }
            fputcsv($new_attribute_set_file, array_values($product));
        }
    }

    public function unsetHeaderAndProductValues(&$product, &$headers)
    {
        foreach(array_values($headers) as $key => $value)
        {
            if(strpos($value, 'orb_') !== false)
            {
                $key_name = $headers[$key];
                unset($product[$key_name]);
                unset($headers[$key]);
            }
        }
    }

    public function splitConfigurableFilesByProduct($file, $headers)
    {
        $products = csvFileToArray($file, $headers);
        $parent_sku = '';
        $num_of_products = count($products);
        for($i = 0; $i < $num_of_products; $i++)
        {
            $product_sku = $products[$i]['sku'];
            if($product_sku != $parent_sku && strpos($product_sku, '^^') !== false)
            {
                $parent_sku = $product_sku;
                echo $parent_sku."<br>";
                $sku_letters = str_replace('^^', '', $parent_sku);
                $new_file = fopen('./csv_files/automated_files/config-products/'.$sku_letters.'.csv', 'w');
                fputcsv($new_file, $headers);
                fputcsv($new_file, $products[$i]);
                for($n = $i+1; $n < $num_of_products; $n++)
                {
                    if(strpos($products[$n]['sku'], $sku_letters) !== false)
                    {
                        echo $products[$n]['sku']."<br>";
                        fputcsv($new_file, $products[$n]);
                    }
                }
            }
        }
    }
}

