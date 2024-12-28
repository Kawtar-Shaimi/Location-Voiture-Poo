<?php

include("voiture.php");

$message = "";
$messageType = "";
// var_dump($_SERVER);
$car = new Voiture();
// var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'ajouter':
                if (!isset($_POST['immatriculation'], $_POST['marque'], $_POST['modele'], $_POST['year'], $_POST['img'])) {
                    echo "Some form fields are missing!";
                    exit;
                }else{
                    $car->creatvoiture($_POST['immatriculation'], $_POST['marque'], $_POST['modele'], $_POST['year'], $_POST['img']);
                }
                



             
                break;

            case 'supprimer':
        
                $car->delete($_POST['id_voiture']);
                $message = "Voiture supprimée avec succès";
                $messageType = "success";
                break;
                case 'premodifier':
                    if (isset($_GET['id'])) {
                        $id = intval($_GET['id']);  // convert the value to an integer.
                        $voiturej =$card->read($id);
                        
                         //This informs the client (browser or API consumer) that the response is in JSON format.
                      echo json_encode($voiturej);         //Converts the $voiture associative array to a JSON string and sends it as the response.
                    }
                    break;
            case 'modifier':
               
                
                $car->updateVoiture($_POST['id_voiture'],$_POST['immatriculation'], $_POST['marque'], $_POST['modele'], $_POST['year'], $_POST['img']);
                break;
        }
    }
}


$voitures = $car->read();

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Voitures</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
           <a href="./index.php"> <h1 class="text-2xl font-bold">CarRental</h1> </a>
            <div class="space-x-4">
                <a href="./client.php" class="hover:text-gray-200">Clients</a>
                <a href="./voitures.php" class="hover:text-gray-200">Voitures</a>
                <a href="./location.php" class="hover:text-gray-200">Locations</a>
            </div>
        </div>
    </nav>
    <?php if ($message): ?>
    <div class="container mx-auto px-4 mb-4">
        <div class="<?= $messageType === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700' ?> 
                    px-4 py-3 rounded relative border" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($message) ?></span>
        </div>
    </div>
<?php endif; ?>
    <div class="container mx-auto px-4">
        <!-- Formulaire d'ajout -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Ajouter une nouvelle voiture</h2>
            <form action="" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="ajouter">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Marque</label>
                        <input type="text" name="marque" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Modèle</label>
                        <input type="text" name="modele" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Immatriculation</label>
                        <input type="text" name="immatriculation" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Année</label>
                        <input type="number" name="year" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">car image </label>
                        <input type="texte" name="img" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                   
                  
                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="statut" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="disponible">Disponible</option>
                            <option value="louée">Louée</option>
                        </select>
                    </div> -->
                </div>
                <div class="text-right">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Ajouter la voiture
                    </button>
                    
                </div>
            </form>
        </div>

        <!-- Liste des voitures -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Liste des voitures</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Voiture marque
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                model
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                  matricule
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                annee
                            </th>
                          
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($voitures as $voiture): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($voiture['Marque'])  ?>
                                </div>
                               
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($voiture['Modele']) ?>
                                </div>
                              
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                            <?= htmlspecialchars($voiture['NumImmatriculation']) ?>
                            </td>
                            <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($voiture['Annee']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <button onclick="editVoiture(<?= $voiture['id_voiture'] ?>)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Modifier
                                </button>
                                <form action="" method="POST" class="inline">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="id_voiture" value="<?= $voiture['id_voiture'] ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
function editVoiture(id) {
    // window.alert("heeeeeeeeeeeeeey");
    // Fetch car data
    fetch(`get_voiture.php?id=${id}`)
        .then(response => response.json())
        .then(voiturej => {
           
            // Create modal with edit form
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full';
            modal.innerHTML = `
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <h3 class="text-lg font-bold mb-4">Modifier la voiture</h3>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="modifier">
                        <input type="hidden" name="id_voiture" value="${voiturej.id_voiture}">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Marque</label>
                                <input type="text" name="marque" value="${voiturej.marque}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Modèle</label>
                                <input type="text" name="modele" value="${voiture.modele}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Immatriculation</label>
                                <input type="text" name="immatriculation" value="${voiture.immatriculation}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Année</label>
                                <input type="number" name="annee" value="${voiture.annee}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="text-right">
                                <button type="button" onclick="this.closest('.fixed').remove()"
                                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);
        });
}
</script>
</body>
</html>