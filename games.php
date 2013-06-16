<?php
	require_once 'config.php';

	$request_method = $_SERVER['REQUEST_METHOD'];
	$type = $_GET['type'];
	$id = mysqli_real_escape_string($db, $_GET['id']);

	switch($request_method)
	{
		case 'HEAD':
			header("Cache-Control: no-cache, must-revalidate");
		break;
		case 'OPTIONS':
			header('Allow: HEAD, OPTIONS, GET, POST, PUT, DELETE');
		break;
		case 'GET':
			
			if(!isset($type) || empty($type) || $type != 'xml' && $type != 'json' && $type != 'html' ) $type = 'html';
			
			$query = mysqli_query($db, "SELECT * FROM games");
			$rows = mysqli_num_rows($query);

			while($game = mysqli_fetch_assoc($query))
			{
				if(isset($id) && !empty($id))
				{

					if($id == $game['id'])
					{
						$results[$game['id']] = $game;
					}
				}
				else
				{
					$results[$game['id']] = $game;
				}
			}
		
			if($rows > 0)
			{

				if($type == 'html')
				{
					echo '<ul>';
					if(isset($id) && !empty($id))
					{
						echo '<h1>'. $results[1]['name'] .'</h1>';
						echo '<p>'. $results[1]['description'] .'</p>';
						
					}
					else
					{
						foreach($results as $result)
					{
						echo '<li><a href="'. $result['id'] .'">'. $result['name'] .'</a></li>';
					}
						echo '</ul>';
					}
				}

				if($type == "xml")
				{
					header('Content-Type: text/xml');
					$xml = new SimpleXMLElement('<games/>');
			    foreach($results as $game):
						$xmlDish = $xml->addChild('game');
						$xmlDish->addAttribute('id', $game['id']);
						$xmlDish->addChild('name', $game['name']);
						$xmlDish->addChild('description', $game['description']);
			    endforeach;
					echo $xml->asXML();

				}

				if($type == "json")
				{
						header('Content-Type: text/json');
						echo json_encode($results);
						header('http/1.1 200 OK');
				}
			}
		break;
		case 'POST':
			if(isset($id) && !empty($id))
			{
				header('http/1.1 406 Not Acceptable');
			}
			else
			{
				$name = mysqli_real_escape_string($db, $_POST['name']);
				$description = mysqli_real_escape_string($db, $_POST['description']);

				if(!empty($name) && !empty($description)) 
				{ 
					$query = mysqli_query($db, "INSERT INTO games(name, description) VALUES($name, $description)"); 
					header('http/1.1 201 Created');
				}
				else 
				{ 
					header('http/1.1 406 Not Acceptable'); die("Variables are not set");
				}
			}
		break;
		case 'PUT':
			if(isset($id) && !empty($id))
			{
				if(!empty($name) && !empty($description)) 
				{ 
					//$updateQuery = mysqli_query($db, "UPDATE games SET name=`$name`, description=`$description` WHERE id=`$id`");
					header('http/1.1 200 OK');
				}
				else
				{
					header('http/1.1 406 Not Acceptable');
				}
			}
			else
			{
				header('http/1.1 406 Not Acceptable');
			}
		break;
		case 'DELETE':
			if(isset($id) && !empty($id))
			{
				$deleteQuery = mysqli_query($db, "DELETE from games where id=`$id`") or header('http/1.1 500 Internal Server Error');
				header('http/1.1 200 OK');
			}
			else
			{
				header('http/1.1 406 Not Acceptable');		
			}
		break;
	}

?>