<?php
header("Access-Control-Allow-Origin: *");


//Post New account (password, email, username)
//Post Login attempt (password, username)
//Post Delete Account (username, password)
//TODO: Fix to accept JSON for everything.
include_once __DIR__ . '/' . 'DbConnection.php';
$conn = new DbConnection();
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        echo $data['type'];
        switch ($data['type']) {
            case 'new':
                if (!empty($data['username']) && !empty($data['email']) && !empty($data['password'])) {
                    $query = <<<SQL
INSERT INTO account (username, email, password) VALUES (?,?,?);
SQL;
                    $stmt = $conn->prepare($query);
                    echo $stmt->execute([$data['username'], $data['email'], $data['password']]);
                    $query = <<<SQL
SELECT id from account WHERE username = ?;
SQL;
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$data['username']]);
                    echo json_encode(['accountID'=>$stmt->fetchColumn(0)]);

                } else {
                    echo json_encode('username, email, or password missing');
                }
                break;
            case 'login':
                if (!empty($data['username']) && !empty($data['password'])) {
                    $query = <<<SQL
SELECT id from account where username = ? and password = ?;
SQL;
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$data['username'], $data['password']]);
                    if ($stmt->rowCount() == 1) {
                        echo json_encode(['accountID'=>$stmt->fetchColumn()]);
                    } else {
                        echo json_encode('');
                    }
                } else {
                    echo json_encode('');
                }
                break;
            case 'delete':
                if (!empty($data['username'])) {
                    $query = <<<SQL
SELECT id from account where username = ?;
SQL;
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$data['username'], $data['password']]);
                    if ($stmt->rowCount() == 1) {
                        $query = <<<SQL
DELETE FROM account WHERE id = ?;
SQL;
                        $id = $stmt->fetchColumn();
                        $stmt = $conn->prepare($query);
                        $stmt->execute([$id]);
                        echo json_encode(['status'=>'success']);
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
