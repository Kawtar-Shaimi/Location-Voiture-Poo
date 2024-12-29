<?php 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\Contrat;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

if($_SERVER["REQUEST_METHOD"] == "GET"){

    if(isset($_GET['id'])){

        $id_contrat = $_GET['id'];

        $contrat = new Contrat();
        $contrat_infos = $contrat->getContrat($id_contrat);

    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['deleteContrat'])){

        Validator::validateCsrf();

        $id_contrat = $_POST['id_contrat'];

        $contrat = new Contrat();
        $contrat->deleteContrat($id_contrat);
        
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
                <a href="./createContrat.php" class='text-white font-bold bg-blue-600 rounded-lg p-5'>Ajouter un Contrat</a>
            </div>
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Contart Num: <?php echo $contrat_infos['id_contrat']?></h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">ID</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['id_contrat']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de début</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['DateDebut']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de fin</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['DateFin']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Durée</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['Duree']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Client</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['name']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Client ID</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['id_user']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Client Email</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['email']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Voiture</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['Marque'] . " " . $contrat_infos['Modele'] . " " . $contrat_infos['Annee']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Voiture NumImmatriculation</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['NumImmatriculation']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">ID Voiture</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['id_voiture']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de création</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['created_at']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de modification</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $contrat_infos['updated_at']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Actions</td>
                        <td class="flex gap-2 border border-gray-200 px-4 py-2">
                        <a href='./updateContrat.php?id=<?php echo $contrat_infos['id_contrat']?>' class='text-blue-500 hover:text-blue-700 cursor-pointer'>Modifier</a>
                            <form method='POST'>
                                <input type='hidden' name='csrf_token' value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <input type='hidden' value="<?php echo $contrat_infos['id_contrat']?>" name='id_contrat'>
                                <button type='submit' name='deleteContrat' class='text-red-500 hover:text-red-700 cursor-pointer'>
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