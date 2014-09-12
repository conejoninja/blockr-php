<?php
require_once 'BlockrAPI.php';

$bapi = new BlockrAPI();
print_r($bapi->coinInfo());
print_r($bapi->exchangeRate());
