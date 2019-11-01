<?php

//Post new Ingredient (account_id, json ingredient data)
//Post Update Ingredient (account_id, json ingredient data)
//Post Delete Ingredient (ingredient_id, account_id)

include_once __DIR__ . '/' . 'DbConnection.php';


$conn = new DbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    switch ($data['type']) {
        case 'new':
            if(!empty($data['accountID']) && !empty($data['ingredient'])) {
                $ingredient = json_decode($data['ingredient']);
                $query = <<<SQL
SELECT * FROM recipe WHERE id = ? and account_id = ?;
SQL;
                $stmt = $conn->prepare($query, [$data['accountID'], $ingredient['recipe_id']]);
                if ($stmt->rowCount() == 1) {
                    $ingredient_query = <<<SQL
INSERT INTO ingredient (name) VALUE ? ON DUPLICATE KEY UPDATE name = ?
SQL;
                    $stmt = $conn->prepare($ingredient_query);
                    $stmt->execute([$ingredient['name']]);

                    $ingredient_query = <<<SQL
SELECT * from ingredient WHERE name = ?;
SQL;
                    $stmt = $conn->prepare($ingredient_query, [$ingredient['name']]);
                    $returned = $stmt->fetchAll();
                    $ingredient_query = <<<SQL
INSERT INTO recipe_ingredient VALUES(recipe_id, ingredient_id, amount, unit);
SQL;
                    $stmt = $conn->prepare($ingredient_query);
                    $ingredient_id = $returned['id'][array_search($ingredient['name'], $returned)];
                    $stmt->execute([$ingredient['recipe_id'], $ingredient_id, $ingredient['amount'], $ingredient['unit']]);
                    echo 0;
                } else {
                    echo json_encode('');
                }
            }
            else{
                echo json_encode('');
            }
            break;
        case 'update':
            if (!empty($data['accountID']) && !empty($data['ingredient'])) {
                $ingredient = json_decode($data['ingredient']);
                $query = <<<SQL
SELECT * FROM recipe_ingredient LEFT JOIN recipe r on recipe_ingredient.recipe_id = r.id where id = ? AND recipe_id = ?
AND r.account_id = ?;
SQL;
                $stmt = $conn->prepare($query, [$ingredient['id'], $ingredient['recipe_id'], $data['accountID']]);
                if($stmt->rowCount() == 1){
                    if(!empty($ingredient['amount']) && !empty($ingredient['unit']) && !empty($ingredient['recipeIngredientID'])){
                        $query = <<<SQL
UPDATE recipe_ingredient SET amount = ?, unit = ?, recipe_id = ? WHERE id = ?;
SQL;
                        $stmt = $conn->prepare($query);
                        $stmt->execute([$ingredient['amount'], $ingredient['unit'], $ingredient['recipe_id'], $ingredient['recipeIngredientID']]);
                        echo 0;
                    }
                    else{
                        echo json_encode('');
                    }
                }
                else{
                    echo json_encode('');
                }

            } else {
                echo json_encode('');
            }
            break;
        case 'delete':
            if (!empty($data['recipeIngredientID']) && !empty($data['accountID'])) {
                $query = <<<SQL
SELECT r.account_id FROM recipe_ingredient left join recipe r on recipe_ingredient.recipe_id = r.id where id = ? and r.account_id = ?;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->execute([$data['recipeIngredientID'], $data['accountID']]);
                if($stmt->rowCount() == 1){
                    $query = <<<SQL
DELETE FROM recipe_ingredient WHERE id = ?;
SQL;
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$data['recipeIngredientID']]);
                    echo json_encode(['status' => 'success']);
                }
                else{
                    echo json_encode('');
                }
            } else {
                echo json_encode('');
            }
            break;
        default:
            echo json_encode('');
    }
} else {
    echo json_encode('');
}