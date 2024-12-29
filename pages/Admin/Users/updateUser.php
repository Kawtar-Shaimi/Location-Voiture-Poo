<?php 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\User;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

if(isset($_GET['id'])){

    $id_user = (int) $_GET['id'];

    $user = new User();
    $user_infos = $user->getUser($id_user);

}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['updateUser'])){

        Validator::validateCsrf();

        $id_user = $_POST['id_user'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $oldEmail = $user_infos['email'];
        $role = trim($_POST['role']);

        $user = new User();
        $user->updateUser($id_user, $name, $email, $oldEmail, $role);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$nameErr = $_SESSION["nameErr"] ?? null;
$emailErr = $_SESSION["emailErr"] ?? null;
$roleErr = $_SESSION["roleErr"] ?? null;
$message = $_SESSION["message"] ?? null;

unset($_SESSION["nameErr"],$_SESSION["emailErr"], $_SESSION["roleErr"],$_SESSION["message"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un user</title>
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
                    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Modifier un user</h1>
                    <form method="POST">

                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <input type="hidden" name="id_user" value="<?php if ($user_infos) echo $user_infos['id_user']; ?>">
                    
                        <div class="mb-4">
                            <label for="fullname" class="block text-sm font-medium text-gray-600">Nom</label>
                            <input type="text"  name="name" placeholder="Entrez votre nom complet" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" value="<?php if ($user_infos) echo $user_infos['name']; ?>">
                                <?php if($nameErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$nameErr</p><br>" ?>
                        </div>

                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="email" name="email" placeholder="Entrez votre email" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" value="<?php if ($user_infos) echo $user_infos['email']; ?>">
                            <?php if($emailErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$emailErr</p><br>" ?>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Nom de la category</label>
                            <select id="role" name="role" required class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option>-- Choisisez Un Role --</option>
                                <option <?php if ($user_infos) echo $user_infos['role'] == 'user' ? "selected" : null ?> value="user">User</option>
                                <option <?php if ($user_infos) echo $user_infos['role'] == 'admin' ? "selected" : null ?>  value="admin">Admin</option>
                            </select>
                            <?php if($roleErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$roleErr</p><br>" ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="updateUser"
                            class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-purple-300">
                                Modifier le user
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>

</html>
