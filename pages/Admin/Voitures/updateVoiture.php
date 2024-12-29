<?php 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\Voiture;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

if(isset($_GET['id'])){

    $id_voiture = (int) $_GET['id'];

    $voiture = new Voiture();
    $voiture_infos = $voiture->getVoiture($id_voiture);

}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['updateVoiture'])){

        Validator::validateCsrf();

        $id_voiture = (int) $_POST['id_voiture'];
        $numImmatriculation = trim($_POST['numImmatriculation']);
        $oldNumImmatriculation = $voiture_infos['NumImmatriculation'];
        $marque = trim($_POST['marque']);
        $modele = trim($_POST['modele']);
        $annee = (int) $_POST['annee'];
        $voiture = new Voiture();
        $voiture->updateVoiture($id_voiture, $numImmatriculation, $oldNumImmatriculation, $marque, $modele, $annee);

    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$numImmatriculationErr = $_SESSION["numImmatriculationErr"] ?? null;
$marqueErr = $_SESSION["marqueErr"] ?? null;
$modeleErr = $_SESSION["modeleErr"] ?? null;
$anneeErr = $_SESSION["anneeErr"] ?? null;
$message = $_SESSION["message"] ?? null;
unset($_SESSION["numImmatriculationErr"],$_SESSION["marqueErr"], $_SESSION["modeleErr"],$_SESSION["anneeErr"],$_SESSION["message"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un voiture</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 overflow-x-hidden">

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

            <div class="mt-6 flex justify-center items-center">
               <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-xl">
                    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Modifier un voiture</h1>
                    <form method="POST">

                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <input type="hidden" name="id_voiture" value="<?php if ($voiture_infos) echo $voiture_infos['id_voiture']; ?>">
                    
                        <div class="mb-4">
                            <label for="numImmatriculation" class="block text-sm font-medium text-gray-600">Numéro d'immatriculation</label>
                            <input type="text" name="numImmatriculation" placeholder="Entrez le numéro d'immatriculation" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" value="<?php if ($voiture_infos) echo $voiture_infos['NumImmatriculation']; ?>">
                            <?php if($numImmatriculationErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$numImmatriculationErr</p><br>" ?>
                        </div>

                        <div class="mb-4">
                            <label for="marque" class="block text-sm font-medium text-gray-600">Marque</label>
                            <input type="text" name="marque" placeholder="Entrez la marque" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" value="<?php if ($voiture_infos) echo $voiture_infos['Marque']; ?>">
                            <?php if($marqueErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$marqueErr</p><br>" ?>
                        </div>

                        <div class="mb-4">
                            <label for="modele" class="block text-sm font-medium text-gray-600">Modèle</label>
                            <input type="text" name="modele" placeholder="Entrez le modèle" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" value="<?php if ($voiture_infos) echo $voiture_infos['Modele']; ?>">
                            <?php if($modeleErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$modeleErr</p><br>" ?>
                        </div>

                        <div class="mb-4">
                            <label for="annee" class="block text-sm font-medium text-gray-600">Année</label>
                            <input type="number" name="annee" placeholder="Entrez l'année" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" value="<?php if ($voiture_infos) echo $voiture_infos['Annee']; ?>">
                            <?php if($anneeErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$anneeErr</p><br>" ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="updateVoiture"
                            class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-purple-300">
                                Modifier la voiture
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>



</body>

</html>
