<?php

require 'shopify.php';

session_start();

$sc = new ShopifyClient($_SESSION['shop'], $_SESSION['token'], $api_key, $secret);

function createProduct($product) {
  try {
    $response = $sc->call('POST', '/admin/products.json', $product);
  } catch (ShopifyApiException $e) {
    
  }
  return $response;
}