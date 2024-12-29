<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\Voiture;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

$voitures = new Voiture();
$result = $voitures->getVoitures();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteVoiture'])) {

        Validator::validateCsrf();

        $id_voiture = $_POST['id_voiture'];
        
        $voiture = new Voiture();
        $voitures->deleteVoiture($id_voiture);
        
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = $_SESSION["message"] ?? null;
unset($_SESSION["message"]);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <?php include_once "../../layouts/sidebar.php";?>

        <div class="flex-1 p-6">
            <?php include_once "../../layouts/statics.php";?>
            <?php 
            if($message){
                echo "
                    <div class='max-w-3xl mx-auto bg-green-600 rounded-lg my-5 py-5 ps-5'>
                        <p class='text-white font-bold'>$message</p>
                    </div>
                ";
            }
            ?>
            <div class='my-20'>
                <a href="./createVoiture.php" class='text-white font-bold bg-blue-600 rounded-lg p-5 w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg text-lg font-semibold hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-purple-300'>Ajouter un voiture</a>
            </div>
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Liste des voitures</h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">ID</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Numéro d'immatriculation</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Marque</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Modèle</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Année</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td class='py-3 px-6 text-sm text-gray-800'><a class='cursor-pointer hover:text-blue-500 hover:decoration-underline' href='./voiture.php?id={$row['id_voiture']}'>{$row['id_voiture']}</a></td>
                                        <td class='py-3 px-6 text-sm text-gray-800'>{$row['NumImmatriculation']}</td>
                                        <td class='py-3 px-6 text-sm text-gray-800'>{$row['Marque']}</td>
                                        <td class='py-3 px-6 text-sm text-gray-800'>{$row['Modele']}</td>
                                        <td class='py-3 px-6 text-sm text-gray-800'>{$row['Annee']}</td>
                                        <td class='flex gap-2 px-2 py-2'>
                                            <a href='./updateVoiture.php?id={$row['id_voiture']}' class='text-blue-500 hover:text-blue-700 cursor-pointer'>Modifier</a>
                                            <form method='POST'>
                                                <input type='hidden' name='csrf_token' value='".htmlspecialchars($_SESSION['csrf_token'])."'>
                                                <input type='hidden' name='id_voiture' value='{$row['id_voiture']}'>
                                                <button type='submit' name='deleteVoiture' class='text-red-500 hover:text-red-700 cursor-pointer'>Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                ";
                            }
                        }
                        ?>
                </table>
            </div>
        </div>
    </div>

</body>

</html>