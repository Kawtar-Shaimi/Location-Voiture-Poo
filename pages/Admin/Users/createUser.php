<?php 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\User;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['createUser'])){

        Validator::validateCsrf();

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $role = trim($_POST['role']);
        $pass = trim($_POST['password']);
        $confirmPass = trim($_POST['confirm_password']);

        $user = new User();
        $user->createUser($name, $email, $pass, $confirmPass, $role);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$nameErr = $_SESSION["nameErr"] ?? null;
$emailErr = $_SESSION["emailErr"] ?? null;
$roleErr = $_SESSION["roleErr"] ?? null;
$passErr = $_SESSION["passErr"] ?? null;
$confirmPassErr = $_SESSION["confirmPassErr"] ?? null;
$message = $_SESSION["message"] ?? null;
unset($_SESSION["nameErr"],$_SESSION["emailErr"], $_SESSION["roleErr"],$_SESSION["passErr"],$_SESSION["confirmPassErr"],$_SESSION["message"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un user</title>
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
                    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Ajouter un user</h1>
                    <form method="POST">

                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    
                        <div class="mb-4">
                            <label for="fullname" class="block text-sm font-medium text-gray-600">Nom</label>
                            <input type="text"  name="name" placeholder="Entrez votre nom complet" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" >
                                <?php if($nameErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$nameErr</p><br>" ?>
                        </div>

                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="email" name="email" placeholder="Entrez votre email" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105">
                            <?php if($emailErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$emailErr</p><br>" ?>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Nom de la category</label>
                            <select id="role" name="role" required class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option>-- Choisisez Un Role --</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <?php if($roleErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$roleErr</p><br>" ?>
                        </div>

                        
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-600">Mot de passe</label>
                            <input type="password"  name="password" placeholder="Entrez votre mot de passe" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105">
                            <?php if($passErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$passErr</p><br>" ?>
                        </div>

                        
                        <div class="mb-6">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-600">Confirmez le mot de passe</label>
                            <input type="password"  name="confirm_password" placeholder="Confirmez votre mot de passe" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105">
                            <?php if($confirmPassErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$confirmPassErr</p><br>" ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="createUser"
                            class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-purple-300">
                                Ajouter le user
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


</body>

</html>
