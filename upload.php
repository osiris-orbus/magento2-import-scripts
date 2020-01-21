<?php
require 'ExportFileConverter.php';
require_once 'helpers.php';
if(count($_FILES) > 0)
{
    if(isset($_FILES['export']) && $_FILES['export'] != '')
    {
        $file_type = 'export';
        $dir = 'uploads/';
    }
    elseif (isset($_FILES['config-file']) && $_FILES['config-file'] != '')
    {
        $file_type = 'config-file';
        $dir = 'csv_files/automated_files/config-products/';
    }
    $converter = new ExportFileConverter();
    $file_name = $_FILES[$file_type]['name'];
    $uploaded_file = $converter->uploadFile($_FILES, $dir, $file_type);
    $product_file = fopen($uploaded_file, 'r');

    $headers = fgetcsv($product_file);
    $new_headers = $converter->updateHeaderValues($headers, $converter->correct_header_values);
    $converter->splitByAttributeSet($product_file, $new_headers);
    fclose($product_file);
    unlink(realpath($uploaded_file));

//        fputcsv($new_file, $new_headers);
//        $converter->splitConfigurableFilesByProduct($product_file, $headers);
}