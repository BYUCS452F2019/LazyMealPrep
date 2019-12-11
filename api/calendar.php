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
                $recipe_query = <<<SQL
INSERT INTO calendar (recipe_id, meal, date, account_id) VALUES(?,?,?,?);
SQL;
                $stmt = $conn->prepare($recipe_query);
                $stmt->execute([$data['recipeID'], $data['meal'], $data['date'], $data['accountID']]);
                echo json_encode(['status'=>'success']);
            }
            else{
                echo json_encode('missing data for calendar recipe add');
            }
            break;
        case 'delete':
            if (!empty($data['mealID']) && !empty($data['accountID'])) {
                $query = <<<SQL
SELECT * FROM calendar where id = ? AND account_id = ?;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->execute([$data['mealID'], $data['accountID']]);
                if($stmt->rowCount() == 1){
                    $mealID = $stmt->fetchColumn();
                    $query = <<<SQL
DELETE FROM calendar WHERE id = ?;
SQL;
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$mealID]);
                    echo json_encode(['status' => 'success']);
                }
                else{
                    echo json_encode(['more than one meal found', $stmt->rowCount()]);
                }
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
        $query = <<<SQL
SELECT recipe.id as recipeID, name, public, recipe.account_id as accountID from recipe left join calendar c on recipe.id = c.recipe_id 
where c.date >= ? and c.date <= ?;
SQL;
        $stmt = $conn->prepare($query);
        $stmt->execute([$_GET['startDate'], $_GET['endDate']]);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
