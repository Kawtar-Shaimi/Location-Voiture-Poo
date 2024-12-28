<?php
include("BaseCrud.php");

class Voiture extends BaseCrud {
    public function __construct() {
        parent::__construct('voitures'); // Pass the 'voitures' table name
    }

    public function creatvoiture($NumImmatriculation, $Marque, $Modele, $Annee, $image) {
        // Correct the typo in the key 'NumImmatriculation'
        $data = array(
            "NumImmatriculation" => $NumImmatriculation,
            "Marque" => $Marque,
            "Modele" => $Modele,
            "Annee" => $Annee,
            "image" => $image
        );

        $this->create($data); // Call the parent `create` method
    }
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id_voiture = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
    
        return $stmt->rowCount();
    }

    
    public function updateVoiture($id, $NumImmatriculation, $Marque, $Modele, $Annee, $image) {
        $data = array(
            "NumImmatriculation" => $NumImmatriculation,
            "Marque" => $Marque,
            "Modele" => $Modele,
            "Annee" => $Annee,
            "image" => $image
        );

        return $this->update($id, $data);
    }
}

// Test the creatvoiture function

// $voiture->creatvoiture('ABC123', 'Toyota', 'Corolla', 2020, 'car_image.jpg');




?>
