<?php

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\Auth;
use User\LocationPoo\Models\Validator;

Validator::validateLogedOutUser();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    Validator::validateCsrf();

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    $confirmPass = trim($_POST['confirm_password']);
    $remember = isset($_POST['remember']);

    $auth = new Auth();
    $auth->signup($name, $email, $pass, $confirmPass, $remember);
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$nameErr = $_SESSION["nameErr"] ?? null;
$emailErr = $_SESSION["emailErr"] ?? null;
$passErr = $_SESSION["passErr"] ?? null;
$confirmPassErr = $_SESSION["confirmPassErr"] ?? null;
unset($_SESSION["nameErr"],$_SESSION["emailErr"],$_SESSION["passErr"],$_SESSION["confirmPassErr"])
?>

<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 min-h-screen flex justify-center items-center">

   
    
    <div class="bg-white p-10 rounded-lg shadow-2xl w-full max-w-lg">
        
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">S'inscrire</h2>
            
            <form method="POST">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                
                <div class="mb-4">
                    <label for="fullname" class="block text-sm font-medium text-gray-600">Nom</label>
                    <input type="text"  name="name" placeholder="Entrez votre nom complet" class="w-full p-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" class="w-full p-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" >
                        <?php if($nameErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$nameErr</p><br>" ?>
                </div>

                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input type="email" name="email" placeholder="Entrez votre email" class="w-full p-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" class="w-full p-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <?php if($emailErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$emailErr</p><br>" ?>
                </div>

                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-600">Mot de passe</label>
                    <input type="password"  name="password" placeholder="Entrez votre mot de passe" class="w-full p-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" class="w-full p-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <?php if($passErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$passErr</p><br>" ?>
                </div>

                
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-600">Confirmez le mot de passe</label>
                    <input type="password"  name="confirm_password" placeholder="Confirmez votre mot de passe" class="w-full p-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" class="w-full p-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <?php if($confirmPassErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$confirmPassErr</p><br>" ?>
                </div>


                <div class="mb-6 flex items-center justify-between">
                    <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                </div>

                
                <button type="submit"
                class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-purple-300">
                    S'inscrire
                </button>
            </form>

            <div class="text-center mt-4">Déjà un compte ?
                <a href="./login.php" class="text-purple-500 font-medium hover:underline"> Connectez-vous</a>
            </div>
        

    </div>

</body>

</html>