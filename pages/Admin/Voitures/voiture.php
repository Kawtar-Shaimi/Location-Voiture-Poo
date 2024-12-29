<?php 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\Voiture;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

if($_SERVER["REQUEST_METHOD"] == "GET"){

    if(isset($_GET['id'])){

        $id_voiture = (int) $_GET['id'];

        $voiture = new Voiture();
        $voiture_infos = $voiture->getVoiture($id_voiture);

    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['deleteVoiture'])){

        Validator::validateCsrf();

        $id_voiture = $_POST['id_voiture'];

        $voiture = new Voiture();
        $voiture->deleteVoiture($id_voiture);
        
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
            <div class='my-20'>
                <a href="./createVoiture.php" class='text-white font-bold bg-blue-600 rounded-lg p-5'>Ajouter un voiture</a>
            </div>
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4"><?php echo $voiture_infos['Marque'] . " " . $voiture_infos['Modele']?></h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">ID</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $voiture_infos['id_voiture']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Numéro d'immatriculation</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $voiture_infos['NumImmatriculation']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Marque</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $voiture_infos['Marque']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Modèle</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $voiture_infos['Modele']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Année</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $voiture_infos['Annee']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de création</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $voiture_infos['created_at']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de modification</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $voiture_infos['updated_at']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Actions</td>
                        <td class="flex gap-2 border border-gray-200 px-4 py-2">
                        <a href='./updateVoiture.php?id=<?php echo $voiture_infos['id_voiture']?>' class='text-blue-500 hover:text-blue-700 cursor-pointer'>Modifier</a>
                            <form method='POST'>
                                <input type='hidden' name='csrf_token' value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <input type='hidden' value="<?php echo $voiture_infos['id_voiture']?>" name='id_voiture'>
                                <button type='submit' name='deleteVoiture' class='text-red-500 hover:text-red-700 cursor-pointer'>
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>