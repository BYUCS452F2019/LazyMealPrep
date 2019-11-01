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
        $data = json_decode($json);
        echo $data['type'];
        echo $_POST['type'];

        foreach ($_POST as $key => $value) {
            echo "<tr>";
            echo "<td>";
            echo $key;
            echo "</td>";
            echo "<td>";
            echo $value;
            echo "</td>";
            echo "</tr>";
        }
        switch ($_POST['type']) {
            case 'new':
                if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                    $query = <<<SQL
INSERT INTO account (username, email, password) VALUES (?,?,?);
SQL;
                    $stmt = $conn->prepare($query, [$_POST['username'], $_POST['email'], $_POST['password']]);
                    $stmt->execute();
                } else {
                    echo json_encode('');
                }
                break;
            case 'login':
                if (!empty($_POST['username']) && !empty($_POST['password'])) {
                    $query = <<<SQL
SELECT id from account where username = ? and password = ?;
SQL;
                    $stmt = $conn->prepare($query, [$_POST['username'], $_POST['password']]);
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
                if (!empty($_POST['username']) && !empty($_POST['password'])) {
                    $query = <<<SQL
SELECT id from account where username = ? and password = ?;
SQL;
                    $stmt = $conn->prepare($query, [$_POST['username'], $_POST['password']]);
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
