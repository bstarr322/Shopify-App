<?php

require 'shopify.php';

session_start();

$sc = new ShopifyClient($_SESSION['shop'], $_SESSION['token'], $api_key, $secret);

function createProduct($data) {
  try {
    var_dump($data);
    $product = array('product' => json_decode($data) );
    var_dump($product);
    $response = $sc->call('POST', '/admin/products.json', $product);
  } catch (ShopifyApiException $e) {
    
  }
  return $response;
}