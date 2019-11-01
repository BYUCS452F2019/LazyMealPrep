<?php
header("Access-Control-Allow-Origin: *");


//Post New account (password, email, username)
//Post Login attempt (password, username)
//Post Delete Account (username, password)
//TODO: Fix to accept JSON for everything.
include_once __DIR__ . '/' . 'DbConnection.php';
$conn = new DbConnection();
try {
    if (!empty($_POST)) {
        $json = json_decode($_POST);
        switch ($json['type']) {
            case 'new':
                if (!empty($json['username']) && !empty($json['email']) && !empty($json['password'])) {
                    $query = <<<SQL
INSERT INTO account (username, email, password) VALUES (?,?,?);
SQL;
                    $stmt = $conn->prepare($query, [$json['username'], $json['email'], $json['password']]);
                    $stmt->execute();
                } else {
                    echo json_encode('');
                }
                break;
            case 'login':
                if (!empty($json['username']) && !empty($json['password'])) {
                    $query = <<<SQL
SELECT id from account where username = ? and password = ?;
SQL;
                    $stmt = $conn->prepare($query, [$json['username'], $json['password']]);
                    if ($stmt->rowCount() == 1) {
                        echo json_encode($stmt->fetchColumn());
                    } else {
                        echo json_encode('');
                    }
                } else {
                    echo json_encode('');
                }
                break;
            case 'delete':
                if (!empty($json['username']) && !empty($json['password'])) {
                    $query = <<<SQL
SELECT id from account where username = ? and password = ?;
SQL;
                    $stmt = $conn->prepare($query, [$json['username'], $json['password']]);
                    if ($stmt->rowCount() == 1) {
                        $query = <<<SQL
DELETE FROM account WHERE id = ?;
SQL;
                        $id = $stmt->fetchColumn();
                        $stmt = $conn->prepare($query, [$id]);
                        $stmt->execute();
                        echo json_encode('success');
                    } else {
                        echo json_encode('');
                    }
                } else {
                    echo json_encode('');
                }
                break;
            default:
                echo json_encode('type not found');
        }
    } else {
        echo json_encode('post was empty');
    }
}
catch (\Exception $e){
    echo $e->getMessage();
}
