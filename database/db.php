<?php
//Connectie
class Database {
    private $conn;

    public function __construct() {
        $this->conn = new PDO("mysql:host=localhost;dbname=klm", "root", "");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection() {
        return $this->conn;
    }

    // Nieuw: voor SELECTs en dergelijke
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Nieuw: voor INSERT, UPDATE, DELETE
    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
}

class User {
    private $dbh;

    public function __construct(Database $dbh) {
        $this->dbh = $dbh;
    }

    public function signUp($name, $email, $demo, $department = null) {
        return $this->dbh->execute(
            "INSERT INTO sign_up (name, email, demo, department)
             VALUES (:name, :email, :demo, :department)",
            ["name" => $name, "email" => $email, "demo" => $demo, "department" => $department]
        );
    }

    public function getUserRegistrations($email) {
        return $this->dbh->query(
            "SELECT demo FROM sign_up WHERE email = :email",
            ["email" => $email]
        )->fetchAll(PDO::FETCH_COLUMN);
    }
}

class Contact {
    private $dbh;

    public function __construct(Database $dbh) {
        $this->dbh = $dbh;
    }

    public function contactUs($name, $email, $message) {
        return $this->dbh->execute(
            "INSERT INTO contact (name, email, message)
             VALUES (:name, :email, :message)",
            ["name" => $name, "email" => $email, "message" => $message]
        );
    }
}

require_once __DIR__ . '/mailer.php';

$dbh = new Database("klm");
?>
