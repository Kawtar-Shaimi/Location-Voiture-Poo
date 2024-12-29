<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use User\LocationPoo\Models\User;
use User\LocationPoo\Models\Validator;

Validator::validateAdmin();

$users = new User();
$result = $users->getUsers();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteUser'])) {

        Validator::validateCsrf();

        $id_user = $_POST['id_user'];
        
        $user = new User();
        $users->deleteUser($id_user);
        
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
                <a href="./createUser.php" class='text-white font-bold bg-blue-600 rounded-lg p-5 w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg text-lg font-semibold hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-purple-300'>Ajouter un user</a>
            </div>
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Liste des utilisateurs</h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Id user</th>
                            <th class="py-3 px-7 text-left text-sm font-medium text-gray-600">Nom</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Email</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Password</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Role</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr class='border-b'>";
                                    echo "<td class='py-3 px-4 text-sm text-gray-800'> <a class='cursor-pointer hover:text-blue-500 hover:decoration-underline' href='./user.php?id={$row['id_user']}'>{$row['id_user']}</a> </td>";
                                    echo "<td class='py-3 px-7 text-sm text-gray-800'> {$row['name']} </td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'> {$row['email']}</td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'>". substr($row['password'],0,17) ."...</td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'>{$row['role']}</td>";
                                    echo "<td class='flex gap-2 px-2 py-2'>
                                            <a href='./updateUser.php?id={$row['id_user']}' class='text-blue-500 hover:text-blue-700 cursor-pointer'>Modifier</a>
                                            <form method='POST'>
                                                <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                                <input type='hidden' value='". htmlspecialchars($row["id_user"]) . "' name='id_user'>
                                                <button type='submit' name='deleteUser' class='text-red-500 hover:text-red-700 cursor-pointer'>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-2'>No users found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>