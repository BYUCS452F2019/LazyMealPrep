<?php
//Post New account requires password, email, username
//Post Login attempt
//Post New password request? (Maybe if we have time)
//Post Delete Account

$conn = new DbConnection();

if(!empty($_POST)){
    switch($_POST['type']){
        case 'new':
            if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                $query = <<<SQL
INSERT INTO account (username, email, password) VALUES (?,?,?);
SQL;
                $stmt = $conn->prepare($query, [$_POST['username'], $_POST['email'], $_POST['password']]);
                $stmt->execute();
            }
            else{
                echo -1;
            }
            break;
        case 'login':
            if(!empty($_POST['username']) && !empty($_POST['password'])) {
                $query = <<<SQL
SELECT id from account where username = ? and password = ?;
SQL;
                $stmt = $conn->prepare($query, [$_POST['username'], $_POST['password']]);
                if($stmt->rowCount() == 1){
                    echo $stmt->fetchColumn();
                }
                else{
                    echo -1;
                }
            }
            else{
                echo -1;
            }
            break;
        case 'delete':
            if(!empty($_POST['username']) && !empty($_POST['password'])) {
                $query = <<<SQL
SELECT id from account where username = ? and password = ?;
SQL;
                $stmt = $conn->prepare($query, [$_POST['username'], $_POST['password']]);
                if($stmt->rowCount() == 1){
                    $query = <<<SQL
DELETE FROM account WHERE id = ?;
SQL;
                    $id = $stmt->fetchColumn();
                    $stmt = $conn->prepare($query, [$id]);
                    $stmt->execute();
                    echo 0;
                }
                else{
                    echo -1;
                }
            }
            else{
                echo -1;
            }
            break;
        default:
            echo -1;
    }
}
else{
    echo -1;
}
