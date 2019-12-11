<?php

header("Access-Control-Allow-Origin: *");

//Post new recipes new(account_id, name, public, json array of ingredients)
//Post Update recipe update(recipe_id, account_id, json recipe data)
//Delete Recipe delete(recipe_id, account_id)
//Get specific recipe (and ingredients) get_one(recipe_id, account_id)
//Get recipes available to account get_all(account_id)
include_once __DIR__ . '/' . 'DbConnection.php';

$conn = new DbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    switch ($data['type']) {
        case 'add':
            if(!empty($data['accountID']) && !empty($data['recipeID']) && !empty($data['meal']) && !empty($data['date'])){
                unset($data['type']);
                $response = $conn->put('calendars', json_encode($data));
                echo json_encode(['status'=>'success']);
            }
            else{
                echo json_encode('missing data for calendar recipe add');
            }
            break;
        case 'delete':
            if (!empty($data['mealID']) && !empty($data['accountID'])) {
                unset($data['type']);
                $conn->delete('recipes', json_encode($data));
            } else {
                echo json_encode('missing id or accountID');
            }
            break;
        default:
            echo json_encode('');
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(!empty($_GET['accountID']) && !empty($_GET['startDate']) && !empty($_GET['endDate'])){
        $recipes = $conn->get('calendars', json_encode(['accountID'=>$_GET['accountID']]));
        //TODO:Filter based off of assigned date
        $json = json_encode(['recipes'=>$recipes]);
        echo $json;
    }
    else{
        echo json_encode('no accountID, startDate, or endDate');
    }
}

else {
    echo json_encode('');
}
