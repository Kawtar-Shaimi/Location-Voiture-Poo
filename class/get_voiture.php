<?php


require_once 'voiture.php';

if (isset($_GET['id'])) {
    $car = new Voiture();
    $id = intval($_GET['id']);
    $voiture = $car->read($id);
    
    header('Content-Type: application/json');
    echo json_encode($voiture);
}
?>