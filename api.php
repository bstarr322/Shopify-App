<?php

require 'shopify.php';

session_start();

$sc = new ShopifyClient($_SESSION['shop'], $_SESSION['token'], $api_key, $secret);

function createProduct() {
  try {

    $data = $_POST;
    $product = array
    (
      "product" => array
      (
        "title"         => $data['title'],
        "body_html"     => $data['body_html'],
        "vendor"        => $data['vendor'],
        "product_type"  => $data['product_type'],
        "tags"          => "Barnes & Noble, John's Fav, \"Big Air\""
      )
    );

    $response = $sc->call('POST', '/admin/products.json', $product);
    
    return $response;

  } catch (ShopifyApiException $e) {
    
  }
}