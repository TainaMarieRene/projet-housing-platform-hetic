<?php

namespace ProductPage;

use App\Database;
use PDO;
use PDOException;

class ProductPage
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function getHousing($housing_id)
    {
        try {
            $sql = "SELECT * FROM housing WHERE housing_id = :housing_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':housing_id', $housing_id, PDO::PARAM_INT);
            $stmt->execute();

            $housing = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$housing) {
                return null;
            }

            $housing = [
                'id' => $housing['housing_id'],
                'name' => $housing['name'],
                'capacity' => $housing['capacity'],
                'price' => $housing['price'],
                'description' => $housing['description'],
                'note' => $housing['note'],
                'instruction' => $housing['instruction'],
                'number_pieces' => $housing['number_pieces'],
                'number_rooms' => $housing['number_rooms'],
                'number_bathrooms' => $housing['number_bathrooms'],
                'exterior' => $housing['exterior'],
                'car_park' => $housing['car_park'],
                'area' => $housing['area'],
            ];
            
            return $housing;
            

            return $housing;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getHousingImages($housing_id)
    {
        try {
            $sql = "SELECT * FROM housing_images WHERE housing_id = :housing_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':housing_id', $housing_id, PDO::PARAM_INT);
            $stmt->execute();

            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $housing_images;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getHousingServices($housing_id)
    {
        try {
            $sql = "SELECT s.* FROM housing_services hs
                    INNER JOIN services s ON hs.service_id = s.service_id
                    WHERE hs.housing_id = :housing_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':housing_id', $housing_id, PDO::PARAM_INT);
            $stmt->execute();

            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $services;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getOpinions($housing_id)
    {
        try {
            $sql = "SELECT * FROM opinions WHERE housing_id = :housing_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':housing_id', $housing_id, PDO::PARAM_INT);
            $stmt->execute();

            $opinions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $opinions_images;
        } catch (PDOException $e) {
            
            return [];
        }
    }
    public function housing_unavailability($housing_id)
    {
        try {
           $sql = "SELECT * FROM housing_availability WHERE housing_id = :housing_id";
           $stmt = $this->conn->prepare($sql);
           $stmt->bindParam(':housing_id', $housing_id, PDO::PARAM_INT);
           $stmt->execute();

           $availabilityData = $stmt->fetchAll(PDO::FETCH_ASSOC);

           return $housing_unavailability;
        } catch (PDOException $e) {
        
          return [];
        }
    }
}