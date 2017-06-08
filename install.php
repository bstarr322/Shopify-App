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
    
    // Now, request the token and store it in your session.
    $_SESSION['token'] = $shopifyClient->getAccessToken($_GET['code']);
    var_dump($_SESSION['token']); exit;
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
  <p>Install this app in a shop to get access to its private admin data.</p> 
 
  <p style="padding-bottom: 1em;">
    <span class="hint">Don&rsquo;t have a shop to install your app in handy? <a href="https://app.shopify.com/services/partners/api_clients/test_shops">Create a test shop.</a></span>
  </p> 
   
  <form action="" method="post">
    <label for='shop'><strong>The URL of the Shop</strong> 
      <span class="hint">(enter it exactly like this: myshop.myshopify.com)</span> 
    </label> 
    <p> 
      <input id="code" name="code" size="45" type="hidden" value="" /> 
      <input id="shop" name="shop" size="45" type="text" value="" /> 
      <input name="commit" type="submit" value="Install" /> 
    </p> 
  </form>