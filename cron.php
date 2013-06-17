<?php
		/*

			Dit bestand wordt ieder uur aangeroepen door de cronjob
	
		*/
		include 'config.php';
	 	$data = json_decode(file_get_contents("http://ec2-54-214-163-23.us-west-2.compute.amazonaws.com/api/v1/products/json"));


	 	$delete = mysqli_query($db, "DELETE from products");
	 	foreach($data->products as $product) {
	 		$data = $product->product->name;

	    $unixNow = time();
	    $timestamp = date('r', $unixNow) . "<br />";    
	 		
	 		$insert = mysqli_query($db, "INSERT into products(name, timestamp) VALUES('$data', '$timestamp')");
	 	}

	 	/*
	 	echo '<h1>Producten van externe webservice</h1>';
	 	echo '<ul>';
	 	foreach($data->products as $product) {
	 		echo '<li>'.$product->product->name.'</li>';
	 	}
	 	echo '</ul>';
		*/
?>