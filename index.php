<?php

require_once  './vendor/autoload.php';

session_start();

use User\LocationPoo\Models\Auth;
use User\LocationPoo\Models\User;
use User\LocationPoo\Models\Voiture;
use User\LocationPoo\Models\Validator;

Validator::validateLogedInUser();
$voiture = new Voiture();
$result = $voiture->getVoitures();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['logout'])){

        Validator::validateCsrf();

        $auth = new Auth();
        $auth->logout();
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Voitures</title>
</head>
<body class="bg-gradient-to-r from-gray-100 to-gray-300 min-h-screen">

  <!-- Header -->
    <header class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-4">
          <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">Welcome!</h1>
            <p class="text-lg mt-2">Here's your activity history:</p>
          </div>


          <form  method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <button name="logout" type="submit" class="w-full bg-white-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:scale-105">Logout</button>
          </form>
          
        </div>
    </header>


  <main class="container mx-auto py-10 flex flex-wrap justify-center gap-6">

      <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='bg-white rounded-lg shadow-lg p-6 w-80'>";
                echo "<h3 class='text-lg font-bold text-gray-800'>Voiture ID : {$row['id_voiture']}</h3>";
                echo "<p class='text-sm text-gray-500 mt-2'>Numéro d'immatriculation : <span class='font-medium'>{$row['NumImmatriculation']}</span></p>";
                echo "<p class='text-sm text-gray-500 mt-1'>Marque : <span class='font-medium'>{$row['Marque']}</span></p>";
                echo "<p class='text-sm text-gray-500 mt-1'>Modèle : <span class='font-medium'>{$row['Modele']}</span></p>";
                echo "<p class='text-sm text-gray-500 mt-1'>Année : <span class='font-medium'>{$row['Annee']}</span></p>";
                echo "</div>";
            }
        } else {
            echo "<p class='text-center text-gray-500'>Aucune voiture trouvée.</p>";
        }
        ?>

  </main>
</body>
</html>
