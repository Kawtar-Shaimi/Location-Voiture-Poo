<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\Contrat;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

$contrats = new Contrat();
$result = $contrats->getContrats();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteContrat'])) {

        Validator::validateCsrf();

        $id_contrat = $_POST['id_contrat'];
        
        $contrat = new Contrat();
        $contrats->deleteContrat($id_contrat);
        
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
                <a href="./createContrat.php" class='text-white font-bold bg-blue-600 rounded-lg p-5 w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg text-lg font-semibold hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-purple-300'>Ajouter un contrat</a>
            </div>
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Liste des contrats</h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">ID</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Date de debut</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Date de fin</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Duree</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">ID Voiture</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">ID Client</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td class='py-3 px-6 text-sm text-gray-800'><a class='cursor-pointer hover:text-blue-500 hover:decoration-underline' href='./contrat.php?id={$row['id_contrat']}'>{$row['id_contrat']}</a></td>
                                        <td class='py-3 px-6 text-sm text-gray-800'>{$row['DateDebut']}</td>
                                        <td class='py-3 px-6 text-sm text-gray-800'>{$row['DateFin']}</td>
                                        <td class='py-3 px-6 text-sm text-gray-800'>{$row['Duree']}</td>
                                        <td class='py-3 px-6 text-sm text-gray-800'><a class='cursor-pointer hover:text-blue-500 hover:decoration-underline' href='../Voitures/index.php?id={$row['id_voiture']}'>{$row['id_voiture']}</a></td>
                                        <td class='py-3 px-6 text-sm text-gray-800'><a class='cursor-pointer hover:text-blue-500 hover:decoration-underline' href='../Users/user.php?id={$row['id_user']}'>{$row['id_user']}</a></td>
                                        <td class='flex gap-2 px-2 py-2'>
                                            <a href='./updateContrat.php?id={$row['id_contrat']}' class='text-blue-500 hover:text-blue-700 cursor-pointer'>Modifier</a>
                                            <form method='POST'>
                                                <input type='hidden' name='csrf_token' value='".htmlspecialchars($_SESSION['csrf_token'])."'>
                                                <input type='hidden' name='id_contrat' value='{$row['id_contrat']}'>
                                                <button type='submit' name='deleteContrat' class='text-red-500 hover:text-red-700 cursor-pointer'>Supprimer</button>
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