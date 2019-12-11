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
        case 'new':
            if(!empty($data['accountID']) && !empty($data['name']) && !empty($data['ingredients'])){
                unset($data['type']);
                $response = $conn->put('recipes', json_encode($data));
                echo json_encode(['recipeID'=>$data['name']]);
            }
            else{
                echo json_encode('');
            }
            break;
        case 'update':
            if (!empty($data['recipe_id']) && !empty($data['accountID'])) {
                unset($data['type']);
                $conn->update('recipes', json_encode($data));
                echo 0;
            } else {
                echo json_encode('');
            }
            break;
        case 'delete':
            if (!empty($data['name']) && !empty($data['accountID'])) {
                unset($data['type']);
                $conn->delete('recipes', json_encode($data));
            } else {
                echo json_encode('missing name or accountID');
            }
            break;
        default:
            echo json_encode('');
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
    switch ($_GET['type']){
        case 'one':
            if(!empty($_GET['recipeID']) && !empty($_GET['accountID'])){
                $recipe = $conn->get('recipes', json_encode(['recipeID'=>$_GET['recipeID'], 'accountID'=>$_GET['accountID']]));
                $json = json_encode($recipe);
                echo $json;
            }
            else{
                echo json_encode('');
            }
            break;
        case 'all':
            if(!empty($_GET['accountID'])){
                $recipes = $conn->get('recipes', json_encode(['accountID'=>$_GET['accountID']]));
                $json = json_encode(['recipes'=>$recipes]);
                echo $json;
            }
            else{
                echo json_encode('no accountID');
            }
            break;
    }
}

else {
    echo json_encode('');
}
