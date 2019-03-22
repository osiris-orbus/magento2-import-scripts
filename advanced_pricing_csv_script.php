<?php
require_once("helpers.php");
if(isset($_POST['skus']))
{
    $skus = strpos($_POST['skus'], " ") !== false ? explode(' ', $_POST['skus']) : array($_POST['skus']);
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

    /*
     * This should be an M1 product export file. We parse the file and create an array of the data in order to extract it more easily.
     */
    //$filename = 'latest_product_export_modified.csv';
    $filename = './csv_files/productdata_advanced_pricing_only_MASTER.csv';
    $file = fopen($filename, 'r');
    $data = csvFileToArray($file);

    /*
     * We create or update a csv file and add the header columns needed to correctly import advanced pricing in Magento 2.
     */
    $new_csv_file = './csv_files/Advanced Pricing Imports/';
    $new_csv_file .= !empty($_POST['file_name']) ? $_POST['file_name'].'.csv' : 'advanced_pricing_import.csv';
    $advanced_pricing_csv_file = fopen($new_csv_file, 'w'); // Create/update new file for import.
    addHeaders($advanced_pricing_csv_file, 'pricing'); // We add the Magento 2 header values to the first row of the csv file needed to correctly import advanced product pricing.

    /*
     * We have to iterate through the M1 product csv file and extract the necessary data to populate the new csv file used to import advanced pricing.
     */
    foreach ($data as $product)
    {
        if(in_array($product['sku'], $skus))
        {
            if(!empty($product['cws_tier_price'])) // Check if product contains Tier Pricing.
            {
                processPricing($product, $advanced_pricing_csv_file, 'tier');
            }
            if(!empty($product['cws_group_price'])) // Check if product contains Group Pricing.
            {
                processPricing($product, $advanced_pricing_csv_file, 'group');
            }
        }
    }

    echo "<h1>Complete! File is located in: $new_csv_file</h1>";
}
?>

<form method="post" action="#">
    <h3>Please enter a SKU or multiple SKU's to generate advanced pricing csv file for Magento 2 import.</h3>
    <h3>For multiple skus, separate by a space.</h3>
    <input type="text" placeholder="File Name" name="file_name" ><br/><br/>
    <input type="text" placeholder="SKU(s)" name="skus" style="width: 800px;"><br/><br/>
    <button type="submit">Process</button>
</form>



















