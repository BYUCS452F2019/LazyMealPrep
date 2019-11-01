var savedAccountID = "";
if (savedAccountID != "") document.getElementById("isLogged").innerHTML = "Logged in";
else document.getElementById("isLogged").innerHTML = "Logged out";
document.getElementById("accountIDDisplay").innerHTML = savedAccountID;

//create sample values, implement if statements to determine behavior based on switch
//print sample value to console when "sent"

document.getElementById("postNewAccountButton").addEventListener("click", postNewAccountRequest);
document.getElementById("postLoginButton").addEventListener("click", postLoginRequest);
document.getElementById("postDeleteAccountButton").addEventListener("click", postDeleteAccountRequest);
document.getElementById("postNewRecipeButton").addEventListener("click", postNewRecipeRequest);
document.getElementById("postDeleteRecipeButton").addEventListener("click", postDeleteRecipeRequest);
document.getElementById("getAllUserRecipesButton").addEventListener("click", getAllUserRecipesRequest);
document.getElementById("getOneRecipeButton").addEventListener("click", getOneRecipeRequest);
document.getElementById("postDeleteRecipeIngredientButton").addEventListener("click", postDeleteRecipeIngredientRequest);

async function postNewAccountRequest(event) {
  try {
    if (document.getElementById("sampleSwitch").checked === false) {
      const username = document.getElementById("postNewAccountInputUsername").value;
      const password = document.getElementById("postNewAccountInputPassword").value;
      const email = document.getElementById("postNewAccountInputEmail").value;
      const url = "http://lazymealprep.com/api/account.php";
      const response = await fetch(url, {
        method: 'POST',
        body: JSON.stringify({
        type: 'new',
        username: username,
        email: email,
        password: password}

    )
      });
      const data = await response.json();
      console.log(JSON.stringify(data));
      document.getElementById("postNewAccountTableAccountID").innerHTML = data.accountID;
      document.getElementById("postNewAccountTableStatus").innerHTML = "Success";
      savedAccountID = data.accountID;
    } else {
      document.getElementById("postNewAccountTableAccountID").innerHTML = postNewAccountSample.accountID;
      document.getElementById("postNewAccountTableStatus").innerHTML = "Success";
      savedAccountID = postNewAccountSample.accountID;
    }
    if (savedAccountID != "") document.getElementById("isLogged").innerHTML = "Logged in";
    else document.getElementById("isLogged").innerHTML = "Logged out";
    document.getElementById("accountIDDisplay").innerHTML = savedAccountID;

  } catch (error) {
    document.getElementById("postNewAccountTableStatus").innerHTML = "Failure";
    document.getElementById("postNewAccountTableAccountID").innerHTML = "";
    console.error(error);
  }
}

async function postLoginRequest(event) {
  try {
    if (document.getElementById("sampleSwitch").checked === false) {
      const username = document.getElementById("postLoginInputUsername").value;
      const password = document.getElementById("postLoginInputPassword").value;
      const url = "http://lazymealprep.com/api/account.php";
      const response = await fetch(url, {
        method: 'POST',
        type: 'login',
        username: username,
        password: password
      });
      const data = await response.json();
      console.log(JSON.stringify(data));
      document.getElementById("postLoginTableAccountID").innerHTML = data.accountID;
      document.getElementById("postLoginTableStatus").innerHTML = "Success";
      savedAccountID = data.accountID;
    } else {
      document.getElementById("postLoginTableAccountID").innerHTML = postLoginSample.accountID;
      document.getElementById("postLoginTableStatus").innerHTML = "Success";
      savedAccountID = postLoginSample.accountID;
    }
    if (savedAccountID != "") document.getElementById("isLogged").innerHTML = "Logged in";
    else document.getElementById("isLogged").innerHTML = "Logged out";
    document.getElementById("accountIDDisplay").innerHTML = savedAccountID;

  } catch (error) {
    document.getElementById("postLoginTableStatus").innerHTML = "Failure";
    document.getElementById("postLoginTableAccountID").innerHTML = "";
    console.error(error);
  }
}

async function postDeleteAccountRequest(event) {
  try {
    if (document.getElementById("sampleSwitch").checked === false) {
      const username = document.getElementById("postDeleteAccountInputUsername").value;
      const url = "http://lazymealprep.com/api/account.php";
      const response = await fetch(url, {
        method: 'POST',
        type: 'delete',
        username: username,
        accountID: savedAccountID
      });
      const data = await response.json();
      console.log(JSON.stringify(data));
      if (data.status === 'success') {
        document.getElementById("postDeleteAccountTableStatus").innerHTML = "Success";
        savedAccountID = "";
      }
      else document.getElementById("postDeleteAccountTableStatus").innerHTML = "Failure";
    } else {
      if (postDeleteAccountSample.status === 'success') {
        document.getElementById("postDeleteAccountTableStatus").innerHTML = "Success";
        savedAccountID = "";
      }
      else document.getElementById("postDeleteAccountTableStatus").innerHTML = "Failure";
    }
    if (savedAccountID != "") document.getElementById("isLogged").innerHTML = "Logged in";
    else document.getElementById("isLogged").innerHTML = "Logged out";
    document.getElementById("accountIDDisplay").innerHTML = savedAccountID;

  } catch (error) {
    document.getElementById("postDeleteAccountTableStatus").innerHTML = "Failure";
    console.error(error);
  }
}

async function postNewRecipeRequest(event) {
  try {
    if (document.getElementById("sampleSwitch").checked === false) {
      const recipeName = document.getElementById("postNewRecipeInputRecipeName").value;
      const ing1Name = document.getElementById("postNewRecipeIngredient1Name").value;
      const ing2Name = document.getElementById("postNewRecipeIngredient2Name").value;
      const ing3Name = document.getElementById("postNewRecipeIngredient3Name").value;
      const ing1Amount = document.getElementById("postNewRecipeIngredient1Amount").value;
      const ing2Amount = document.getElementById("postNewRecipeIngredient2Amount").value;
      const ing3Amount = document.getElementById("postNewRecipeIngredient3Amount").value;
      const ing1Unit = document.getElementById("postNewRecipeIngredient1Unit").value;
      const ing2Unit = document.getElementById("postNewRecipeIngredient2Unit").value;
      const ing3Unit = document.getElementById("postNewRecipeIngredient3Unit").value;
      var ingArray = [];
      if (ing1Name.length > 0 && ing1Amount.length > 0 && ing1Unit.length > 0) {
        ingArray.push( { name: ing1Name, amount: ing1Amount, unit: ing1Unit } );
      }
      if (ing2Name.length > 0 && ing2Amount.length > 0 && ing2Unit.length > 0) {
        ingArray.push( { name: ing2Name, amount: ing2Amount, unit: ing2Unit } );
      }
      if (ing3Name.length > 0 && ing3Amount.length > 0 && ing3Unit.length > 0) {
        ingArray.push( { name: ing3Name, amount: ing3Amount, unit: ing3Unit } );
      }

      const url = "http://lazymealprep.com/api/recipe.php";
      const response = await fetch(url, {
        method: 'POST',
        type: 'new',
        name: recipeName,
        accountID: savedAccountID,
        ingredients: ingArray
      });
      const data = await response.json();
      console.log(JSON.stringify(data));
      document.getElementById("postNewRecipeTableRecipeID").innerHTML = data.recipeID;
      document.getElementById("postNewRecipeTableStatus").innerHTML = "Success";
    } else {
      document.getElementById("postNewRecipeTableRecipeID").innerHTML = postNewRecipeSample.recipeID;
      document.getElementById("postNewRecipeTableStatus").innerHTML = "Success";
    }

  } catch (error) {
    document.getElementById("postNewRecipeTableStatus").innerHTML = "Failure";
    document.getElementById("postNewRecipeTableRecipeID").innerHTML = "";
    console.error(error);
  }
}

async function postDeleteRecipeRequest(event) {
  try {
    if (document.getElementById("sampleSwitch").checked === false) {
      const recipeName = document.getElementById("postDeleteRecipeInputRecipeName").value;
      const url = "http://lazymealprep.com/api/recipe.php";
      const response = await fetch(url, {
        method: 'POST',
        type: 'delete',
        name: recipeName,
        accountID: savedAccountID
      });
      const data = await response.json();
      console.log(JSON.stringify(data));
      if (data.status === 'success') {
        document.getElementById("postDeleteRecipeTableStatus").innerHTML = "Success";
      }
      else document.getElementById("postDeleteRecipeTableStatus").innerHTML = "Failure";
    } else {
      if (postDeleteRecipeSample.status === 'success') {
        document.getElementById("postDeleteRecipeTableStatus").innerHTML = "Success";
      }
      else document.getElementById("postDeleteRecipeTableStatus").innerHTML = "Failure";
    }

  } catch (error) {
    document.getElementById("postDeleteRecipeTableStatus").innerHTML = "Failure";
    console.error(error);
  }
}

async function getAllUserRecipesRequest(event) {
  try {
    if (document.getElementById("sampleSwitch").checked === false) {
      const url = "http://lazymealprep.com/api/recipe.php";
      const response = await fetch(url, {
        method: 'GET',
        type: 'all',
        accountID: savedAccountID
      });
      const data = await response.json();
      console.log(JSON.stringify(data));

      var i;
      var table = document.getElementById("dynTable");

      var existing = table.getElementsByTagName("tr").length;
      var j;
      for (j = 1; j < existing; j++) {
        table.deleteRow(1);
      }

      for (i = 0; i < data.recipes.length; i++) {
        var row = table.insertRow(i + 1);
        var cell3 = row.insertCell(0);
        var cell2 = row.insertCell(0);
        var cell1 = row.insertCell(0);
        var cell0 = row.insertCell(0);
        cell0.innerHTML = data.recipes[i].recipeID;
        cell1.innerHTML = data.recipes[i].accountID;
        cell2.innerHTML = data.recipes[i].name;
        if (data.recipes[i].public === true) cell3.innerHTML = "Public";
        else cell3.innerHTML = "Private";
      }

      document.getElementById("getAllUserRecipesStatus").innerHTML = "Success";
    } else {

      var i;
      var table = document.getElementById("dynTable");

      var existing = table.getElementsByTagName("tr").length;
      var j;
      for (j = 1; j < existing; j++) {
        table.deleteRow(1);
      }

      for (i = 0; i < getAllUserRecipesSample.recipes.length; i++) {
        var row = table.insertRow(i + 1);
        var cell3 = row.insertCell(0);
        var cell2 = row.insertCell(0);
        var cell1 = row.insertCell(0);
        var cell0 = row.insertCell(0);
        cell0.innerHTML = getAllUserRecipesSample.recipes[i].recipeID;
        cell1.innerHTML = getAllUserRecipesSample.recipes[i].accountID;
        cell2.innerHTML = getAllUserRecipesSample.recipes[i].name;
        if (getAllUserRecipesSample.recipes[i].public === true) cell3.innerHTML = "Public";
        else cell3.innerHTML = "Private";
      }

      document.getElementById("getAllUserRecipesStatus").innerHTML = "Success";
    }
  } catch (error) {
    document.getElementById("getAllUserRecipesStatus").innerHTML = "Failure";
    console.error(error);
  }
}

async function getOneRecipeRequest(event) {
  try {
    if (document.getElementById("sampleSwitch").checked === false) {
      const recipeID = document.getElementById("getOneRecipeInputRecipeID").value;
      const url = "http://lazymealprep.com/api/recipe.php";
      const response = await fetch(url, {
        method: 'GET',
        type: 'one',
        recipeID: recipeID,
        accountID: savedAccountID
      });
      const data = await response.json();
      console.log(JSON.stringify(data));

      document.getElementById("getOneRecipeTableRecipeID").innerHTML = data.recipeID;
      document.getElementById("getOneRecipeTableAccountID").innerHTML = data.accountID;
      document.getElementById("getOneRecipeTableName").innerHTML = data.name;
      if (data.public === true) document.getElementById("getOneRecipeTablePublic").innerHTML = "Public";
      else document.getElementById("getOneRecipeTablePublic").innerHTML = "Private";

      var i;
      var table = document.getElementById("ingTable");

      var existing = table.getElementsByTagName("tr").length;
      var j;
      for (j = 1; j < existing; j++) {
        table.deleteRow(1);
      }

      for (i = 0; i < data.ingredients.length; i++) {
        var row = table.insertRow(i + 1);
        var cell3 = row.insertCell(0);
        var cell2 = row.insertCell(0);
        var cell1 = row.insertCell(0);
        var cell0 = row.insertCell(0);
        cell0.innerHTML = data.ingredients[i].name;
        cell1.innerHTML = data.ingredients[i].ingredientID;
        cell2.innerHTML = data.ingredients[i].amount;
        cell3.innerHTML = data.ingredients[i].unit;
      }

      document.getElementById("getOneRecipeStatus").innerHTML = "Success";

    } else {
      document.getElementById("getOneRecipeTableRecipeID").innerHTML = getOneRecipeSample.recipeID;
      document.getElementById("getOneRecipeTableAccountID").innerHTML = getOneRecipeSample.accountID;
      document.getElementById("getOneRecipeTableName").innerHTML = getOneRecipeSample.name;
      if (getOneRecipeSample.public === true) document.getElementById("getOneRecipeTablePublic").innerHTML = "Public";
      else document.getElementById("getOneRecipeTablePublic").innerHTML = "Private";

      var i;
      var table = document.getElementById("ingTable");

      var existing = table.getElementsByTagName("tr").length;
      var j;
      for (j = 1; j < existing; j++) {
        table.deleteRow(1);
      }

      for (i = 0; i < getOneRecipeSample.ingredients.length; i++) {
        var row = table.insertRow(i + 1);
        var cell3 = row.insertCell(0);
        var cell2 = row.insertCell(0);
        var cell1 = row.insertCell(0);
        var cell0 = row.insertCell(0);
        cell0.innerHTML = getOneRecipeSample.ingredients[i].name;
        cell1.innerHTML = getOneRecipeSample.ingredients[i].ingredientID;
        cell2.innerHTML = getOneRecipeSample.ingredients[i].amount;
        cell3.innerHTML = getOneRecipeSample.ingredients[i].unit;
      }

      document.getElementById("getOneRecipeStatus").innerHTML = "Success";

    }

  } catch (error) {
    document.getElementById("getOneRecipeStatus").innerHTML = "Failure";
    console.error(error);
  }
}

async function postDeleteRecipeIngredientRequest(event) {
  try {
    if (document.getElementById("sampleSwitch").checked === false) {
      const recipeIngredientID = document.getElementById("postDeleteRecipeIngredientInputRecipeIngredientID").value;
      const url = "http://lazymealprep.com/api/recipe.php";
      const response = await fetch(url, {
        method: 'POST',
        type: 'delete',
        recipeIngredientID: recipeIngredientID,
        accountID: savedAccountID
      });
      const data = await response.json();
      console.log(JSON.stringify(data));
      if (data.status === 'success') {
        document.getElementById("postDeleteRecipeIngredientTableStatus").innerHTML = "Success";
      }
      else document.getElementById("postDeleteRecipeIngredientTableStatus").innerHTML = "Failure";
    } else {
      if (postDeleteRecipeIngredientSample.status === 'success') {
        document.getElementById("postDeleteRecipeIngredientTableStatus").innerHTML = "Success";
      }
      else document.getElementById("postDeleteRecipeIngredientTableStatus").innerHTML = "Failure";
    }

  } catch (error) {
    document.getElementById("postDeleteRecipeIngredientTableStatus").innerHTML = "Failure";
    console.error(error);
  }
}

var postNewAccountSample = JSON.parse('{ "accountID": "newAccountID12345" }');
var postLoginSample = JSON.parse('{ "accountID": "newAccountID12345" }');
var postDeleteAccountSample = JSON.parse('{ "status": "success" }');
var postNewRecipeSample = JSON.parse('{ "recipeID": "newRecipeID12345" }');
var postDeleteRecipeSample = JSON.parse('{ "status": "success" }');
var getAllUserRecipesSample = JSON.parse('{ "recipes": [ { "recipeID": "rec1", "accountID": "acc1", "name": "Recipe One", "public": true }, { "recipeID": "rec2", "accountID": "acc1", "name": "Recipe Two", "public": false }, { "recipeID": "rec3", "accountID": "acc2", "name": "Recipe Three", "public": true } ] }');
var getOneRecipeSample = JSON.parse(' { "recipeID": "recID12345", "accountID": "accID12345", "name": "recipe12345", "public": false, "ingredients": [ { "name": "ing1", "ingredientID": "ingID1", "amount": "4", "unit": "tons" }, { "name": "ing2", "ingredientID": "ingID2", "amount": "1", "unit": "lbs" }, { "name": "ing3", "ingredientID": "ingID3", "amount": "3", "unit": "kilos" } ] }');
var postDeleteRecipeIngredientSample = JSON.parse(' { "status": "success" } ');
