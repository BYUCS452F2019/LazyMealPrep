<?php

//Post new Ingredient (account_id, json ingredient data)
//Post Update Ingredient (account_id, json ingredient data)
//Post Delete Ingredient (ingredient_id, account_id)

include_once 'DbConnection.php';


$conn = new DbConnection();

if (!empty($_POST)) {
    switch ($_POST['type']) {
        case 'new':
            if(!empty($_POST['account_id']) && !empty($_POST['ingredient'])) {
                $ingredient = json_decode($_POST['ingredient']);
                $query = <<<SQL
SELECT * FROM recipe WHERE id = ? and account_id = ?;
SQL;
                $stmt = $conn->prepare($query, [$_POST['account_id'], $ingredient['recipe_id']]);
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
                    echo -1;
                }
            }
            else{
                echo -1;
            }
            break;
        case 'update':
            if (!empty($_POST['account_id']) && !empty($_POST['ingredient'])) {
                $ingredient = json_decode($_POST['ingredient']);
                $query = <<<SQL
SELECT * FROM recipe_ingredient LEFT JOIN recipe r on recipe_ingredient.recipe_id = r.id where id = ? AND recipe_id = ?
AND r.account_id = ?;
SQL;
                $stmt = $conn->prepare($query, [$ingredient['id'], $ingredient['recipe_id'], $_POST['account_id']]);
                if($stmt->rowCount() == 1){
                    if(!empty($ingredient['amount']) && !empty($ingredient['unit']) && !empty($ingredient['ingredient_id'])){
                        $query = <<<SQL
UPDATE recipe_ingredient SET amount = ?, unit = ?, recipe_id = ? WHERE id = ?;
SQL;
                        $stmt = $conn->prepare($query);
                        $stmt->execute([$ingredient['amount'], $ingredient['unit'], $ingredient['recipe_id'], $ingredient['ingredient_id']]);
                        echo 0;
                    }
                    else{
                        echo -1;
                    }
                }
                else{
                    echo -1;
                }

            } else {
                echo -1;
            }
            break;
        case 'delete':
            if (!empty($_POST['ingredient_id']) && !empty($_POST['account_id'])) {
                $query = <<<SQL
SELECT r.account_id FROM recipe_ingredient join recipe r on recipe_ingredient.recipe_id = r.id where id = ?;
SQL;
                $stmt = $conn->prepare($query, [$_POST['ingredient_id'], $_POST['account_id']]);
                if($stmt->rowCount() == 1 && $stmt->fetchColumn() == $_POST['account_id']){
                    $query = <<<SQL
DELETE FROM recipe_ingredient WHERE id = ?;
SQL;
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$_POST['ingredient_id']]);
                    echo 0;
                }
                else{
                    echo -1;
                }
            } else {
                echo -1;
            }
            break;
        default:
            echo -1;
    }
} else {
    echo -1;
}