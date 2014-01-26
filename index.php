<?php
session_start();
?>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link rel="stylesheet" href="http://jqueryui.com/jquery-wp-content/themes/jquery/css/base.css?v=1">
<link rel="stylesheet" href="http://jqueryui.com/jquery-wp-content/themes/jqueryui.com/style.css">
<link rel="pingback" href="http://jqueryui.com/xmlrpc.php" />
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<style>
.content-right #content {
	float: left;
	width:100%;
	margin-bottom: 100px;
}
</style>
</head>
<?php
// report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// load the SureDone library
require_once ('includes/SureDone_Startup.php');

$is_post = !empty($_REQUEST['api_call']);
$api_call = !empty($_REQUEST['api_call'])? $_REQUEST['api_call']: "";
?>
<script>
$(function() {
var demoFrame = $( ".demo-frame" ),
demoDescription = $( ".demo-description" ),
sourceView = $( ".view-source > div" ),
demoList = $( ".demo-list" ),
currentDemo = location.hash.substring( 1 );
demoList.on( "click", "a", function( event ) {
event.preventDefault();
var filename = event.target.pathname;
demoFrame.attr( "src", filename );

$.get( filename.replace( "demos", "demos-highlight" ) ).then(function( content ) {
sourceView.html( content );
});
demoList.find( ".active" ).removeClass( "active" );
$( this ).parent().addClass( "active" );
location.hash = "#" + demo;
});
$( ".view-source a" ).on( "click", function() {
sourceView.animate({
opacity: "toggle",
height: "toggle"
});
});
if ( currentDemo ) {
demoList.find( "a" ).filter(function() {
return this.pathname.split( "/" )[ 4 ] === (currentDemo + ".html");
}).click();
}
});
</script>

<div class="content-right twelve columns">
  <div id="content">
    <h1 class="entry-title">SureDone SDK Test</h1>
    <hr>
    <p class="desc">Please use the options available (right sidebar) to see demonstration of SureDone PHP SDK</p>
    <div class="demo-list">
      <h2>Options (API Calls)</h2>
      <ul>
        <li class=""><a href="authenticate.php">Authenticate</a></li>
        <li class=""><a href="search.php">Search</a></li>
        <li class=""><a href="forgot_pass.php">Forgot Pass</a></li>
        <li class=""><a href="assist.php">Assist</a></li>
        <li class=""><a href="profile.php">Profile</a></li>
        <li class=""><a href="single_object_by_id.php">GET Editor single element data (by ID)</a></li>
        <li class=""><a href="single_object_by_sku.php">GET Editor single element data (by SKU)</a></li>
        <li class=""><a href="get_all_options.php">GET All Options</a></li>
        <li class=""><a href="get_editor_results.php">GET Editor results data</a></li>
        <li class=""><a href="get_editor_objects.php">GET Editor objects</a></li>
        <li class=""><a href="get_all_orders.php">GET All Orders</a></li>
        <li class=""><a href="get_shipped_orders.php">GET Shipped Orders</a></li>
        <li class=""><a href="get_awaiting_orders.php">GET Awaiting Orders</a></li>
        <li class=""><a href="get_packing_orders.php">GET Packing Orders</a></li>
        <li class=""><a href="get_single_order.php">GET Order single data</a></li>
        <li class=""><a href="post_update_order.php">POST Update Order</a></li>
        <li class=""><a href="get_invoice.php">GET Invoice</a></li>
        <li class=""><a href="get_option.php">GET Option</a></li>



      </ul>
    </div>
    <iframe class="demo-frame" src=""></iframe>
  </div>
</div>
<br />
<br />
<br />
