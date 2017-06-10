<?php

  require 'shopify.php';

  /* Define your APP`s key and secret*/
  define('SHOPIFY_API_KEY','65c926c9545d5e1b22a1bb0cf39037ef');
  define('SHOPIFY_SECRET','2e7bf7b607014d8bd1569121b4544dd7');
  
  /* Define requested scope (access rights) - checkout https://docs.shopify.com/api/authentication/oauth#scopes   */
  define('SHOPIFY_SCOPE','read_orders, read_products, write_products'); //eg: define('SHOPIFY_SCOPE','read_orders,write_orders');
  
  if (isset($_GET['code'])) { // if the code param has been sent to this page... we are in Step 2
    // Step 2: do a form POST to get the access token
    $shopifyClient = new ShopifyClient($_GET['shop'], "", SHOPIFY_API_KEY, SHOPIFY_SECRET);
    session_unset();
    
    if ( ! session_id() ) @ session_start();
    
    // Now, request the token and store it in your session.
    $_SESSION['token'] = $shopifyClient->getAccessToken($_GET['code']);

    if ($_SESSION['token'] != '')
      $_SESSION['shop'] = $_GET['shop'];
  
    header("Location: index.php");
    exit;   

  } else if (isset($_POST['shop'])) { // if they posted the form with the shop name
  
    // Step 1: get the shopname from the user and redirect the user to the
    // shopify authorization page where they can choose to authorize this app
    $shop = isset($_POST['shop']) ? $_POST['shop'] : $_GET['shop'];
    $shopifyClient = new ShopifyClient($shop, "", SHOPIFY_API_KEY, SHOPIFY_SECRET);
  
    // get the URL to the current page
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") { $pageURL .= "s"; }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
    } else {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
    }

    // redirect to authorize url
    header("Location: " . $shopifyClient->getAuthorizeUrl(SHOPIFY_SCOPE, $pageURL));
    exit;
  }
  
  // first time to the page, show the form below
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

  <style type="text/css">
    section.main {
      height: calc(100vh - 325px);
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
          <div class="field">
            <label for='shop' class="label"><strong>The URL of the Shop</strong> 
              <span class="hint">(enter it exactly like this: myshop.myshopify.com)</span> 
            </label>
          </div>
          <form action="" method="post">
            <input id="code" name="code" size="45" type="hidden" value="" />

            <div class="field is-grouped">
              <p class="control is-expanded">
                <input id="shop" class="input" name="shop" size="45" type="text" value="" placeholder="myshop.myshopify.com" />
              </p>
              <p class="control">
                <input name="commit" class="button is-info" type="submit" value="Install" />
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="content has-text-centered">
        <p>Don&rsquo;t have a shop to install your app in handy?<a href="https://app.shopify.com/services/partners/api_clients/test_shops">Create a test shop.</a>
        </p>
      </div>
    </div>
  </footer>
</body>
</html>