<?php

  require 'shopify.php';

  session_start();

  $sc = new ShopifyClient($_SESSION['shop'], $_SESSION['token'], $api_key, $secret);

  if (!$_SESSION) {
    header("Location: install.php");
  }
  // if(!$sc->validateSignature($_GET))
  //   die('Error: invalid signature.');
  
  try
  {
    // Get all products
    $products = $sc->call('GET', '/admin/products.json', array('published_status'=>'published'));

    // Post New Product.
    $new_product = array
    (
      "product" => array
      (
        "title"     => "Burton Custom Freestyle 151",
        "body_html" => "<strong>Good snowboard!<\/strong>",
        "vendor"    => "Burton",
        "product_type"  => "Snowboard",
        "tags"      => "Barnes & Noble, John's Fav, \"Big Air\""
      )
    );

    try {
      $response = $sc->call('POST', '/admin/products.json', $new_product);
    } catch (ShopifyApiException $e) {
      
    }

    // Create a new recurring charge
    $charge = array
    (
      "recurring_application_charge"=>array
      (
        "price"=>10.0,
        "name"=>"Super Duper Plan",
        "return_url"=>"http://super-duper.shopifyapps.com",
        "test"=>true
      )
    );

    try
    {
      $recurring_application_charge = $sc->call('POST', '/admin/recurring_application_charges.json', $charge);

      // API call limit helpers
      echo $sc->callsMade(); // 2
      echo $sc->callsLeft(); // 498
      echo $sc->callLimit(); // 500

    }
    catch (ShopifyApiException $e)
    {
      // If you're here, either HTTP status code was >= 400 or response contained the key 'errors'
    }

  }
  catch (ShopifyApiException $e)
  {
    /* 
     $e->getMethod() -> http method (GET, POST, PUT, DELETE)
     $e->getPath() -> path of failing request
     $e->getResponseHeaders() -> actually response headers from failing request
     $e->getResponse() -> curl response object
     $e->getParams() -> optional data that may have been passed that caused the failure

    */
  }
  catch (ShopifyCurlException $e)
  {
    // $e->getMessage() returns value of curl_errno() and $e->getCode() returns value of curl_ error()
  }

