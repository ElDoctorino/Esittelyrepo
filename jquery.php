<?php
// Tiedosto on kalenterin viikko csv makeria varten, tiedostoa ei ole viimeistelty
if( isset($_POST['name']) ){
	echo $_POST['name'];
	exit;
   }

session_start();

include "functions.php";

include "dbConfig.php";

include "alku.php";



tarkistus_lvl1();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
   $(document).ready(function(){
      // everything here will be executed once index.html has finished loading, so at the start when the user is yet to do anything.
      $('#yearselect').change(load_new_content); //this translates to: "when the element with id='select1' changes its value execute load_new_content() function"
	  $('#weekselect').change(load_new_content); //this translates to: "when the element with id='select1' changes its value execute load_new_content() function"
});
function load_new_content(){
		var selected_option_value=$("#yearselect option:selected").val(); //get the value of the current selected option.
		var weekvalue=$("#weekselect option:selected").val(); //get the value of the current selected option.
	 	console.log(selected_option_value, weekvalue);
		$.post("exceljquery.php", {option_value: selected_option_value, weekvalue: weekvalue},
			function(data){ //this will be executed once the `exceljquery.php` ends its execution, `data` contains everything said script echoed.
				 //$("#place_where_you_want_the_new_html").html(data);
				 //alert(data); //just to see what it returns
				// alert("Selected value is : " + document.getElementById("yearselect").value);
				 location.reload(true);
			}

		);
		$.ajax({
   type: "POST",
   url: "exceljquery.php",
   data: {"option_value":option_value, "weekvalue":weekvalue},
   success: function(success){
	   alert("ok");
   }
});
   }

</script>