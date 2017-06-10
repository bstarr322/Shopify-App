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

    // // Post New Product.
    // $new_product = array
    // (
    //   "product" => array
    //   (
    //     "title"     => "Burton Custom Freestyle 151",
    //     "body_html" => "<strong>Good snowboard!<\/strong>",
    //     "vendor"    => "Burton",
    //     "product_type"  => "Snowboard",
    //     "tags"      => "Barnes & Noble, John's Fav, \"Big Air\""
    //   )
    // );

    // try {
    //   $pr_response = $sc->call('POST', '/admin/products.json', $new_product);

    // } catch (ShopifyApiException $e) {
      
    // }

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
      // echo $sc->callsMade(); // 2
      // echo $sc->callsLeft(); // 498
      // echo $sc->callLimit(); // 500

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

?>

<!DOCTYPE html>
<html>
<head>
  <title>AarSoft</title>
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
  <meta content="Let's build with me !" name="description">

  <link rel="icon" type="image/png" href="https://tnckb94959.i.lithium.com/html/assets/favicon.png" sizes="32x32">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.2/css/bulma.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <script
  src="https://code.jquery.com/jquery-1.11.1.js"
  integrity="sha256-MCmDSoIMecFUw3f1LicZ/D/yonYAoHrgiep/3pCH9rw="
  crossorigin="anonymous"></script>

  <style type="text/css">
    section.main {
      min-height: calc(100vh - 325px);
      display: flex;
      align-items: center;
    }
  </style>
</head>
<body>

  <section class="hero is-primary">
    <div class="hero-body">
      <div class="container">
        <h1 class="title">
          Aaron Soft
        </h1>
        <h2 class="subtitle">
          Install this app in a shop to get access to its private admin data.
        </h2>
      </div>
    </div>
  </section>
  <section class="section main">
    <div class="container">
      <div class="columns">
        <div class="column is-8 is-offset-2">
          <form method="POST" id="form_pr">
            <label class="label">Product Details</label>
            <div class="field">
              <label class="label">Product Name</label>
              <p class="control">
                <input name="title" class="input" type="text" placeholder="Text input">
              </p>
            </div>
            <div class="field">
              <label class="label">Product Description</label>
              <p class="control">
                <input name="body_html" class="input" type="text" placeholder="Text input">
              </p>
            </div>
            <div class="field">
              <label class="label">Vendor</label>
              <p class="control">
                <input name="vendor" class="input" type="text" placeholder="Text input">
              </p>
            </div>
            <div class="field">
              <label class="label">Product Type</label>
              <p class="control">
                <input name="product_type" class="input" type="text" placeholder="Text input">
              </p>
            </div>
            <a class="button create-product">Primary</a>
          </form>
          
          <div class="field">
            <label for='shop' class="label">Product Response</label>
          </div>
          <figure class="response">
            <pre>
              <code>
              </code>
            </pre>
          </figure>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="content has-text-centered">
        <p>Don&rsquo;t have a shop to install your app in handy? <a href="https://app.shopify.com/services/partners/api_clients/test_shops"> Create a test shop.</a>
        </p>
      </div>
    </div>
  </footer>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.create-product').on('click', function() {
        $.ajax({
          url: '/api.php/createProduct'
          method: 'POST',
          dataType: "json",
          data: JSON.stringify($("#form_pr").serialize()),
          success: function(data) {
            console.log('Succssfully Created!');
            $('.response code').html(data);
          },
          error: function() {

          }
        })
      });
    });
  </script>
</body>
</html>