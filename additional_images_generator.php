<?php
$new_csv_file = './csv_files/image_associations.csv';
$image_associations_csv = fopen($new_csv_file, 'w');
addHeaders($image_associations_csv, 'images');