<?php
    
    use User\LocationPoo\Models\User;
    use User\LocationPoo\Models\Voiture;
    use User\LocationPoo\Models\Contrat;

    $users = new User();
    $usersCount = $users->getUsers()->num_rows;

    $voitures = new Voiture();
    $voituresCount = $voitures->getVoitures()->num_rows;

    $contrats = new Contrat();
    $contratsCount = $contrats->getContrats()->num_rows;

?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Utilisateurs</h3>
            <p class="text-2xl font-bold text-blue-600"><?php echo $usersCount?></p>
        </div>
        <div class="p-4 bg-blue-100 rounded-full text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5"></path>
            </svg>
        </div>
    </div>

    
    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Voiture</h3>
            <p class="text-2xl font-bold text-green-600"><?php echo $voituresCount?></p>
        </div>
        <div class="p-4 bg-green-100 rounded-full text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"></path>
            </svg>
        </div>
    </div>


    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Contrat</h3>
            <p class="text-2xl font-bold text-yellow-600"><?php echo $contratsCount?></p>
        </div>
        <div class="p-4 bg-yellow-100 rounded-full text-yellow-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5"></path>
            </svg>
        </div>
    </div>
</div>