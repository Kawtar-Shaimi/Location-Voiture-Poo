<?php 

namespace User\LocationPoo\Models;

use User\LocationPoo\DB\DataBase;
use User\LocationPoo\Models\Validator;
use Exception;

class Voiture
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getVoitures()
    {
        try {
            $sql = "SELECT * FROM voitures";
            $result = $this->db->conn->query($sql);
            return $result;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getVoiture($id_voiture)
    {
        try {
            $sql = "SELECT * FROM voitures WHERE id_voiture = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_voiture);
            $stmt->execute();
            $result = $stmt->get_result();
            $voiture = $result->fetch_assoc();
            $stmt->close();
            $this->db->conn->close();
            return $voiture;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createVoiture($numImmatriculation, $marque, $modele, $annee)
    {
        
        if (!Validator::validateCreateVoiture($numImmatriculation, $marque, $modele, $annee, $this->db->conn)) {
            header("Location: /Location_POO/pages/Admin/Voitures/createVoiture.php");
            exit;
        }

       try {
            $sql = "INSERT INTO voitures (NumImmatriculation, Marque, Modele, Annee) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssi", $numImmatriculation, $marque, $modele, $annee);
            if ($stmt->execute()) {
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "Voiture ajoutée avec succès";
                header("Location: /Location_POO/pages/Admin/Voitures/index.php");
                exit;
            }else{
                $_SESSION["message"] = "Erreur lors de l'ajout de la voiture";
                header("Location: /Location_POO/pages/Admin/Voitures/createVoiture.php");
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function updateVoiture($id_voiture, $numImmatriculation, $oldNumImmatriculation, $marque, $modele, $annee)
    {

        if (!Validator::validateUpdateVoiture($id_voiture, $numImmatriculation, $marque, $modele, $annee, $oldNumImmatriculation, $this->db->conn)) {
            header("Location: /Location_POO/pages/Admin/Voitures/updateVoiture.php?id=$id_voiture");
            exit;
        }

        try {
            $sql = "UPDATE voitures SET NumImmatriculation = ?, Marque = ?, Modele = ?, Annee = ? WHERE id_voiture = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssii", $numImmatriculation, $marque, $modele, $annee, $id_voiture);
            if ($stmt->execute()) {
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "Voiture modifiée avec succès";
                header("Location: /Location_POO/pages/Admin/Voitures/index.php");
                exit;
            } else {
                $_SESSION["message"] = "Erreur lors de la modification de la voiture";
                header("Location: /Location_POO/pages/Admin/Voitures/updateVoiture.php?id=$id_voiture");
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteVoiture($id_voiture)
    {
        try {
            $sql = "DELETE FROM voitures WHERE id_voiture = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_voiture);
            if ($stmt->execute()) {
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "Voiture supprimée avec succès";
                header("Location: /Location_POO/pages/Admin/Voitures/index.php");
                exit;
            } else {
                $_SESSION["message"] = "Erreur lors de la suppression de la voiture";
                header("Location: /Location_POO/pages/Admin/Voitures/index.php");
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getAvailableVoituresIds()
    {
        try {
            $sql = "SELECT v.id_voiture
                    FROM voitures v
                    LEFT JOIN contrats c ON v.id_voiture = c.id_voiture
                    GROUP BY v.id_voiture
                    HAVING COUNT(c.id_voiture) = 0 OR MAX(c.DateFin) < CURDATE();";
            $result = $this->db->conn->query($sql);
            return $result;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getVoituresIds()
    {
        try {
            $sql = "SELECT DISTINCT id_voiture FROM voitures;";
            $result = $this->db->conn->query($sql);
            return $result;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}