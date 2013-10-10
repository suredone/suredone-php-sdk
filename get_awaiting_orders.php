<!doctype html>
<?php
session_start();?>
<html lang="en">
	<head>
	<meta charset="utf-8" />
	<title>jQuery UI Dialog - Modal form</title>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css" />
	<style>
body {
	font-size: 62.5%;
}
label, input {
	display:block;
}
input.text {
	margin-bottom:12px;
	width:95%;
	padding: .4em;
}
fieldset {
	padding:0;
	border:0;
	margin-top:25px;
}
h1 {
	font-size: 1.2em;
	margin: .6em 0;
}
div#users-contain {
	width: 350px;
	margin: 20px 0;
}
div#users-contain table {
	margin: 1em 0;
	border-collapse: collapse;
	width: 100%;
}
div#users-contain table td, div#users-contain table th {
	border: 1px solid #eee;
	padding: .6em 10px;
	text-align: left;
}
.ui-dialog .ui-state-error {
	padding: .3em;
}
.validateTips {
	border: 1px solid transparent;
	padding: 0.3em;
}
</style>
	<script>
	$(function() {
		var page_no = $( "#page_no" ),
			email = $( "#email" ),
			password = $( "#password" ),
			allFields = $( [] ).add( name ).add( email ).add( password ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}

		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				"Call SDK": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );



	$.post("call_get_awaiting_orders.php", { page_no: page_no.val() })
.done(function(data) {


$("#response").html(data);
});


$( this ).dialog( "close" );

				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#btn-parameters" )
			.button()
			.click(function() {
				$( "#dialog-form" ).dialog( "open" );
			});
	});
	</script>
	</head>
	<body>
<div id="dialog-form" title="Parameters Dialog Box">
      <p class="validateTips">Please provide page number:e.g. <b>1</b></p>
      <form>
    <fieldset>
          <label for="page_no">Page No.</label>
          <input type="text" name="page_no" id="page_no" class="text ui-widget-content ui-corner-all" />
        </fieldset>
  </form>
    </div>
<h1><?=isset($_SESSION['username'])? "Logged in user:" . $_SESSION['username']:"Please authenticate"?></h1>
<br />
<button id="btn-parameters">Click here to provide parameters</button>
<div id="users-contain" class="ui-widget">
      <h1>API Response:</h1>
      <div id=response> </div>
      <br> <br>
            <h1>Source Code:</h1>
      <pre style="font-size:12px;">

echo "---- Testing Get Awaiting Orders ----<br>";


$params = array();
$response = SureDone_Store::get_awaiting_orders($_REQUEST['page_no'], 'shipcarrier', $token, isset($_SESSION['username'])?$_SESSION['username']:'');

print_r($response);		
        
      </pre>

    </div>
    
</body>
</html>
