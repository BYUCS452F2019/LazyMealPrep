<?php
header("Access-Control-Allow-Origin: *");


//Post New account (password, email, username)
//Post Login attempt (password, username)
//Post Delete Account (username, password)
//TODO: Fix to accept JSON for everything.
include_once __DIR__ . '/' . 'DbConnection.php';
$conn = new DbConnection(false);
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        switch ($data['type']) {
            case 'new':

                if (!empty($data['username']) && !empty($data['email']) && !empty($data['password'])) {
                    unset($data['type']);
                    $conn->put('account', json_encode($data));
                    echo json_encode(['accountID'=>$data['username']]);

                } else {
                    echo json_encode('username, email, or password missing');
                }
                break;
            case 'login':
                if (!empty($data['username']) && !empty($data['password'])) {
                    unset($data['type']);
                    $returned = json_decode($conn->get('account', json_encode($data)));
                    echo json_encode(['accountID'=>$data['username']]);
                } else {
                    echo json_encode('');
                }
                break;
            case 'delete':
                if (!empty($data['username']) && !empty($data['accountID'])) {
                    unset($data['type']);
                    $conn->delete('account', json_encode($data));
                    echo json_encode(['status'=>'success']);
                } else {
                    echo json_encode('missing username or accountID');
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
