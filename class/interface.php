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
                if (!isset($_POST['immatriculation'], $_POST['marque'], $_POST['modele'], $_POST['year'])) {

                    echo "Some form fields are missing!";
                    exit;
                }else{

                    if ($_FILES["img"]["error"] === 4) {
                        echo "<script>alert('img Does Not Exist');</script>";
                    } else {
                        // Get img details
                        $fileName = $_FILES["img"]["name"];
                        $fileSize = $_FILES["img"]["size"];
                        $tmpName = $_FILES["img"]["tmp_name"];
                
                        // Define valid image extensions
                        $validImageExtension = ['jpg', 'jpeg', 'png'];
                        $imageExtension = explode('.', $fileName);
                        $imageExtension = strtolower(end($imageExtension));
                
                        // Validate image extension
                        if (!in_array($imageExtension, $validImageExtension)) {
                            echo "<script>alert('Invalid Image Extension');</script>";
                        }
                        // Validate image size
                        elseif ($fileSize > 1000000) { // 1MB limit
                            echo "<script>alert('Image Size Is Too Large');</script>";
                        } else {
                            // Generate a unique name for the image
                            $newImageName = uniqid();
                            $newImageName .= '.' . $imageExtension;
                
                            // Move the uploaded file to the 'img/' directory
                            move_uploaded_file($tmpName, 'uploads/' . $newImageName);
                
                            // Insert the data into the database using PDO
                            $car->creatvoiture($_POST['immatriculation'], $_POST['marque'], $_POST['modele'], $_POST['year'], $newImageName);
                            echo "<script>
                            alert('Successfully Added');
                            
                          </script>";
                            
                        }
                    }
                  
                }
                



             
                break;

            case 'supprimer':
        
                $car->delete($_POST['id_voiture']);
                $message = "Voiture supprimée avec succès";
                $messageType = "success";
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
    <title>CarRental - Gestion des Voitures</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .car-card {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="./index.php" class="flex items-center space-x-2">
                <i class="fas fa-car-side text-3xl"></i>
                <h1 class="text-2xl font-bold">CarRental</h1>
            </a>
            <div class="space-x-6">
                <a href="./client.php" class="hover:text-gray-200 transition-colors inline-flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    <span>Clients</span>
                </a>
                <a href="./voitures.php" class="hover:text-gray-200 transition-colors inline-flex items-center">
                    <i class="fas fa-car mr-2"></i>
                    <span>Voitures</span>
                </a>
                <a href="./location.php" class="hover:text-gray-200 transition-colors inline-flex items-center">
                    <i class="fas fa-key mr-2"></i>
                    <span>Locations</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Add Car Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex items-center mb-6">
                <i class="fas fa-plus-circle text-blue-600 text-2xl mr-3"></i>
                <h2 class="text-2xl font-bold text-gray-800">Ajouter une nouvelle voiture</h2>
            </div>
            <form action="" method="POST"  enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="action" value="ajouter">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-trademark mr-2"></i>Marque
                        </label>
                        <input type="text" name="marque" placeholder="Ex: Toyota" required
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-car-side mr-2"></i>Modèle
                        </label>
                        <input type="text" name="modele" placeholder="Ex: Corolla" required
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag mr-2"></i>Immatriculation
                        </label>
                        <input type="text" name="immatriculation" placeholder="Ex: AB-123-CD" required
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2"></i>Année
                        </label>
                        <input type="number" name="year" placeholder="Ex: 2024" required
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2"></i>Image URL
                        </label>
                        <input type="file" name="img" placeholder="URL de l'image" required
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter la voiture
                    </button>
                </div>
            </form>
        </div>

        <!-- Cars Grid -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-car-side text-blue-600 mr-3"></i>
                Notre flotte de véhicules
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($voitures as $voiture): ?>
            <div class="car-card rounded-xl shadow-lg overflow-hidden">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                    <img src="<?= htmlspecialchars($voiture['image']) ?>" 
                         alt="<?= htmlspecialchars($voiture['Marque'] . ' ' . $voiture['Modele']) ?>"
                         class="w-full h-48 object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        <?= htmlspecialchars($voiture['Marque'] . ' ' . $voiture['Modele']) ?>
                    </h3>
                    <div class="space-y-2 text-gray-600">
                        <p class="flex items-center">
                            <i class="fas fa-hashtag w-6"></i>
                            <?= htmlspecialchars($voiture['NumImmatriculation']) ?>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-calendar w-6"></i>
                            <?= htmlspecialchars($voiture['Annee']) ?>
                        </p>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button onclick="editVoiture(<?= $voiture['id_voiture'] ?>)"
                                class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="" method="POST" class="inline">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="id_voiture" value="<?= $voiture['id_voiture'] ?>">
                            <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')"
                                    class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    function editVoiture(id) {
        fetch(`get_voiture.php?id=${id}`)
            .then(response => response.json())
            .then(voiture => {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-800">Modifier la voiture</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form action="" method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="modifier">
                            <input type="hidden" name="id_voiture" value="${voiture.id_voiture}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Marque</label>
                                    <input type="text" name="marque" value="${voiture.Marque}" required
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Modèle</label>
                                    <input type="text" name="modele" value="${voiture.Modele}" required
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Immatriculation</label>
                                    <input type="text" name="immatriculation" value="${voiture.NumImmatriculation}" required
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                                    <input type="number" name="year" value="${voiture.Annee}" required
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                                    <input type="text" name="img" value="${voiture.image}" required
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" onclick="this.closest('.fixed').remove()"
                                        class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                `;
                document.body.appendChild(modal);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la récupération des données de la voiture.');
            });
    }
    </script>
</body>
</html>