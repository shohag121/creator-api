<?php
use shohag\CreatorAPI\CreatorAPI;
require_once 'CreatorAPI.php';
$conf = require_once 'config.php';

$creator = new CreatorAPI($conf);

$data = array(
  'Name' => '3rd try'
);
// 3891075000000013000
//$ins = $creator->addRecord('Upload_File', $data);
//$insOBJ = json_decode($ins);
//var_dump($insOBJ);