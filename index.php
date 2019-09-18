<?php
use shohag\CreatorAPI\CreatorAPI;
require_once 'CreatorAPI.php';
$conf = require_once 'config.php';

$creator = new CreatorAPI($conf);

$data = array(
  'Name' => 'updated try'
);
// 3891075000000013000
//$ins = $creator->addRecord('Upload_File', $data);
//$insOBJ = json_decode($ins);
//var_dump($insOBJ);

//echo $creator->deleteRecord('File_ID=1005','Upload_File',$data,false);

echo $creator->allRecords('All_files');