<?php

use User\LocationPoo\Models\Auth;
use User\LocationPoo\Models\Validator;

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
<div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 min-h-screen p-6">
    <h2 class="text-2xl font-extrabold text-white text-center mb-6">Admin Dashboard</h2>
    <ul>
        <li><a href="http://localhost/Location_POO/pages/Admin/Users" class="block py-2 px-4 text-white hover:bg-blue-400 rounded-lg">Utilisateurs</a></li>
        <li><a href="http://localhost/Location_POO/pages/Admin/Contrats" class="block py-2 px-4 text-white hover:bg-blue-400 rounded-lg">Contrats</a></li>
        <li><a href="http://localhost/Location_POO/pages/Admin/Voitures" class="block py-2 px-4 text-white hover:bg-blue-400 rounded-lg">Voitures</a></li>
        <li>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"] ?>">
                <button name="logout" class="w-full text-left block py-2 px-4 text-white hover:bg-blue-400 rounded-lg">Logout</button>
            </form>
        </li>
    </ul>
</div>