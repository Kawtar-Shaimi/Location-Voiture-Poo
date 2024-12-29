<?php

namespace User\LocationPoo\Models;

use User\LocationPoo\DB\DataBase;
use User\LocationPoo\Models\Validator;
use Exception;

class Contrat
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function getContrats()
    {
        try {
            $sql = "SELECT * FROM contrats";
            $result = $this->db->conn->query($sql);
            return $result;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getContrat($id_contrat)
    {
        try {
            $sql = "SELECT * FROM contrats
                    INNER JOIN users ON contrats.id_user = users.id_user
                    INNER JOIN voitures ON contrats.id_voiture = voitures.id_voiture
                    WHERE id_contrat = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_contrat);
            $stmt->execute();
            $result = $stmt->get_result();
            $contrat = $result->fetch_assoc();
            $stmt->close();
            $this->db->conn->close();
            return $contrat;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createContrat($id_user, $id_voiture, $date_debut, $date_fin)
    {
        if (!Validator::validateContratInfo($id_user, $id_voiture, $date_debut, $date_fin)) {
            header("Location: /Location_POO/pages/Admin/Contrats/createContrat.php");
            exit;
        }

        try {
            $dateDebutObj = new \DateTime($date_debut);
            $dateFinObj = new \DateTime($date_fin);
            $difference = $dateDebutObj->diff($dateFinObj);
            $duree = $difference->days; 

            $sql = "INSERT INTO contrats (id_user, id_voiture, DateDebut, DateFin, Duree) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("iissi", $id_user, $id_voiture, $date_debut, $date_fin, $duree);
            if ($stmt->execute()) {
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "Contrat created successfully";
                header("Location: /Location_POO/pages/Admin/Contrats/index.php");
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function updateContrat($id_contrat, $id_user, $id_voiture, $date_debut, $date_fin)
    {
        if (!Validator::validateContratInfo($id_user, $id_voiture, $date_debut, $date_fin)) {
            header("Location: /Location_POO/pages/Admin/Contrats/updateContrat.php?id={$id_contrat}");
            exit;
        }

        try {
            $dateDebutObj = new \DateTime($date_debut);
            $dateFinObj = new \DateTime($date_fin);
            $difference = $dateDebutObj->diff($dateFinObj);
            $duree = $difference->days; 

            $sql = "UPDATE contrats SET id_user = ?, id_voiture = ?, DateDebut = ?, DateFin = ?, Duree = ? WHERE id_contrat = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("iissii", $id_user, $id_voiture, $date_debut, $date_fin, $duree, $id_contrat);
            if ($stmt->execute()) {
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "Contrat updated successfully";
                header("Location: /Location_POO/pages/Admin/Contrats/index.php");
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteContrat($id_contrat)
    {
        try {
            $sql = "DELETE FROM contrats WHERE id_contrat = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_contrat);
            if ($stmt->execute()) {
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "Contrat deleted successfully";
                header("Location: /Location_POO/pages/Admin/Contrats/index.php");
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}