<?php


require_once 'voiture.php';

require_once 'interface.php';
$card = new Voiture();
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // convert the value to an integer.
    $voiturej =$card->read($id);
    
    header('Content-Type: application/json');  //This informs the client (browser or API consumer) that the response is in JSON format.
  echo json_encode($voiturej);         //Converts the $voiture associative array to a JSON string and sends it as the response.
}
?>