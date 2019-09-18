<?php
use shohag\CreatorAPI\CreatorAPI;
require_once 'CreatorAPI.php';
$conf = require_once 'config.php';

$creator = new CreatorAPI($conf);

$data = array(
  'Name' => 'Shohag',
);

$ins = $creator->addRecord('Upload_File', $data);

die(var_dump($ins));