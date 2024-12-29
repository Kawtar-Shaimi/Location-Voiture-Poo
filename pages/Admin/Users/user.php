<?php 

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\User;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

if($_SERVER["REQUEST_METHOD"] == "GET"){

    if(isset($_GET['id'])){

        $id_user = (int) $_GET['id'];

        $user = new User();
        $user_infos = $user->getUser($id_user);

    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['deleteUser'])){

        Validator::validateCsrf();

        $id_user = $_POST['id_user'];

        $user = new User();
        $user->deleteUser($id_user);
        
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
                <a href="./createUser.php" class='text-white font-bold bg-blue-600 rounded-lg p-5'>Ajouter un user</a>
            </div>
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4"><?php echo $user_infos['name']?></h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Nom</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $user_infos['name']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Email</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $user_infos['email']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Password</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $user_infos['password']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Role</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $user_infos['role']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de création</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $user_infos['created_at']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de modification</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $user_infos['updated_at']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Actions</td>
                        <td class="flex gap-2 border border-gray-200 px-4 py-2">
                        <a href='./updateUser.php?id=<?php echo $user_infos['id_user']?>' class='text-blue-500 hover:text-blue-700 cursor-pointer'>Modifier</a>
                            <form method='POST'>
                                <input type='hidden' name='csrf_token' value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <input type='hidden' value="<?php echo $user_infos['id_user']?>" name='id_user'>
                                <button type='submit' name='deleteUser' class='text-red-500 hover:text-red-700 cursor-pointer'>
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