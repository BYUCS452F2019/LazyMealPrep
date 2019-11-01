<?php

header("Access-Control-Allow-Origin: *");

//Post new recipes new(account_id, name, public, json array of ingredients)
//Post Update recipe update(recipe_id, account_id, json recipe data)
//Delete Recipe delete(recipe_id, account_id)
//Get specific recipe (and ingredients) get_one(recipe_id, account_id)
//Get recipes available to account get_all(account_id)


$conn = new DbConnection();

if (!empty($_POST)) {
    switch ($_POST['type']) {
        case 'new':
            if(!empty($_POST['account_id']) && !empty($_POST['name']) && !empty($_POST['public']) && !empty($_POST['ingredients'])){
                $recipe_query = <<<SQL
INSERT INTO recipe (account_id, name, public) VALUES(?,?,?);
SQL;
                $stmt = $conn->prepare($recipe_query);
                $stmt->execute([$_POST['account_id'], $_POST['name'], $_POST['public']]);
                $json = json_decode($_POST['ingredients']);
                $recipe_id_query = <<<SQL
SELECT id from recipe WHERE account_id = ? and name = ? ORDER BY id DESC LIMIT 1;
SQL;
                $stmt = $conn->prepare($recipe_id_query, [$_POST['account_id'], $_POST['name']]);
                $recipe_id = $stmt->fetchColumn();

                $ingredient_query = <<<SQL
INSERT INTO ingredient (name) VALUE ? ON DUPLICATE KEY UPDATE name = ?
SQL;
                $ingredient_names = [];
                $stmt = $conn->prepare($ingredient_query);
                foreach($json as $ingredient){
                    $stmt->execute([$ingredient['name']]);
                    $ingredient_names.array_push($ingredient['name']);
                }
                $ingredient_query = <<<SQL
SELECT * from ingredient WHERE name in ?;
SQL;
                $stmt = $conn->prepare($ingredient_query, [$ingredient_names]);
                $returned = $stmt->fetchAll();
                $ingredient_query = <<<SQL
INSERT INTO recipe_ingredient VALUES(recipe_id, ingredient_id, amount, unit);
SQL;
                $conn->beingTransaction();
                $stmt = $conn->prepare($ingredient_query);
                foreach ($json as $ingredient) {
                    $ingredient_id = $returned['id'][array_search($ingredient['name'], $returned)];
                    $stmt->execute([$recipe_id, $ingredient_id, $ingredient['amount'], $ingredient['unit']]);
                }
                $conn->commit();
                echo 0;


            }
            else{
                echo -1;
            }
            break;
        case 'update':
            if (!empty($_POST['recipe_id']) && !empty($_POST['account_id'])) {
                $query = <<<SQL
SELECT * FROM recipe where id = ? AND account_id = ?;
SQL;
                $stmt = $conn->prepare($query, [$_POST['recipe_id'], $_POST['account_id']]);
                if($stmt->rowCount() == 1){
                    $new_recipe = json_decode($_POST['recipe']);
                    if(!empty($new_recipe['name']) && !empty($new_recipe['public'])){
                        $query = <<<SQL
UPDATE recipe SET name = ?, public = ? WHERE id = ?;
SQL;
                        $stmt = $conn->prepare($query);
                        $stmt->execute([$new_recipe['name'], $new_recipe['public']]);
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
            if (!empty($_POST['recipe_id']) && !empty($_POST['account_id'])) {
                $query = <<<SQL
SELECT * FROM recipe where id = ? AND account_id = ?;
SQL;
                $stmt = $conn->prepare($query, [$_POST['recipe_id'], $_POST['account_id']]);
                if($stmt->rowCount() == 1){
                    $query = <<<SQL
DELETE FROM recipe WHERE id = ?;
SQL;
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$_POST['recipe_id']]);
                    echo 0;
                }
                else{
                    echo -1;
                }
            } else {
                echo -1;
            }
            break;
        case 'get_one':
            if(!empty($_POST['recipe_id']) && !empty($_POST['account_id'])){
                $query = <<<SQL
SELECT * from recipe where id = ? and (account_id = ? or public = TRUE);
SQL;
                $conn->beingTransaction();
                $stmt = $conn->prepare($query, [$_POST['recipe_id'], $_POST['account_id']]);
                $recipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $recipe_query = <<<SQL
SELECT * FROM recipe_ingredient WHERE recipe_id = ?;
SQL;
                $stmt = $conn->prepare($recipe_query, [$_POST['recipe_id']]);
                $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $conn->commit();
                $recipe['ingredients'] = $ingredients;
                $json = json_encode($recipe);
                echo $json;
            }
            else{
                echo -1;
            }
            break;
        case 'get_all':
            if(!empty($_POST['account_id'])){
                $query = <<<SQL
SELECT * from recipe where account_id = ? or public = TRUE;
SQL;
                $stmt = $conn->prepare($query, [$_POST['account_id']]);
                $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ortho_recipes = [];
                for($i = 0; $i < $stmt->rowCount(); $i++){
                    $recipe = [];
                    $recipe['name'] = $recipes['name'][$i];
                    $recipe['id'] = $recipes['id'][$i];
                    $recipe['public'] = $recipes['public'][$i];
                    $recipe['owned'] = $recipes['account_id'] == $_POST['account_id'];
                    $ortho_recipes.array_push($recipe);
                }
                $json = json_encode($ortho_recipes);
                echo $json;
            }
            else{
                echo -1;
            }
            break;
        default:
            echo -1;
    }
} else {
    echo -1;
}
