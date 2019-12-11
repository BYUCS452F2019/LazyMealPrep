var ID = getCookie("ID");
var User = getCookie("User");
var data = [];
var iin = 1;
console.log("logged in as " + User + " with id " + ID);
if (ID === "") window.location.href = 'login.html'
getAllUserRecipesRequest();

window.onload = function(){
  document.getElementById("accountButton").appendChild(document.createTextNode(User));
  document.getElementById("accountButton").addEventListener("click", expandUser);
  document.getElementById("logoutButton").addEventListener("click", logout);
  document.getElementById("newRecipeButton").addEventListener("click", expandNewRecipeForm);
}

async function sendNewRecipeRequest(event) {
  try {
    var j = iin;
    var ingArray = []
    if (document.getElementById("newRecipeName").value === "") {
      console.log(null.value);
    }
    for (var i = 0; i < j; i++) {
      if (document.getElementById("IN" + i).value === "") {
        console.log(null.value);
      }
      if (document.getElementById("A" + i).value === "") {
        console.log(null.value);
      }
      if (document.getElementById("U" + i).value === "") {
        console.log(null.value);
      }
    }

    for (var i = 0; i < j; i++) {
      var ingName = document.getElementById("IN" + i).value;
      var aamount = document.getElementById("A" + i).value;
      var uunit = document.getElementById("U" + i).value;
      ingArray.push( { name: ingName, amount: aamount, unit: uunit });
    }

    const url = "http://lazymealprep.com/api/recipe.php";
    const response = await fetch(url, {
      method: 'POST',
      body: JSON.stringify({
        type: 'new',
        name: document.getElementById("newRecipeName").value,
        accountID: ID,
        ingredients: ingArray
      })
    });
    const data = await response.json();
    getAllUserRecipesRequest();

    document.getElementById("CNRMessage").innerHTML = "";
  } catch (error) {
    try {
      document.getElementById("CNRMessage").innerHTML = "Please fill in all values";
    } catch (error) {}
    console.error(error);
  }
}

function expandNewRecipeForm(event) {
  var modal = document.getElementById("modalBackground");
  modal.style.display = "block";
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
      const n = document.getElementById("dynModal");
      iin = 1;
      while (n.firstChild) {
        n.removeChild(n.firstChild);
      }
    }
  }

  CNRM = document.createElement("div");
  CNRM.setAttribute("id", "CNRMessage");
  document.getElementById("dynModal").appendChild(CNRM);
  document.getElementById("dynModal").appendChild(document.createElement("br"));
  postButton = document.createElement("button");
  postButton.setAttribute("id", "postNewRecipeButton");
  postButton.appendChild(document.createTextNode("Finished - Add Recipe"));
  postButton.addEventListener("click", function() {
    sendNewRecipeRequest();
  });
  document.getElementById("dynModal").appendChild(postButton);
  document.getElementById("dynModal").appendChild(document.createElement("br"));
  document.getElementById("dynModal").appendChild(document.createElement("br"));

  var newDiv = document.createElement("label");
  newDiv.appendChild(document.createTextNode("Recipe Name"));
  document.getElementById("dynModal").appendChild(newDiv);
  document.getElementById("dynModal").appendChild(document.createElement("br"));
  var newElement = document.createElement("input");
  newElement.setAttribute("type", "text");
  newElement.setAttribute("placeholder", "Name");
  newElement.setAttribute("id", "newRecipeName");
  document.getElementById("dynModal").appendChild(newElement);
  document.getElementById("dynModal").appendChild(document.createElement("br"));
  document.getElementById("dynModal").appendChild(document.createElement("br"));

  newDiv = document.createElement("div");
  newDiv.setAttribute("id", "F0");
  newDiv.setAttribute("class", "ingredientForm");

  newElement = document.createElement("label");
  newElement.appendChild(document.createTextNode("Ingredient Name"));
  newElement.appendChild(document.createElement("br"));
  var newElement2 = document.createElement("input");
  newElement2.setAttribute("type", "text");
  newElement2.setAttribute("placeholder", "Name");
  newElement2.setAttribute("id", "IN0");
  newDiv.setAttribute("class", "ingredientName");
  newElement.appendChild(newElement2);
  newElement.appendChild(document.createElement("br"));
  newDiv.appendChild(newElement);

  newElement = document.createElement("label");
  newElement.appendChild(document.createTextNode("Amount"));
  newElement.appendChild(document.createElement("br"));
  var newElement2 = document.createElement("input");
  newElement2.setAttribute("type", "text");
  newElement2.setAttribute("placeholder", "Amount");
  newElement2.setAttribute("id", "A0");
  newDiv.setAttribute("class", "ingredientAmount");
  newElement.appendChild(newElement2);
  newDiv.appendChild(newElement);

  newElement = document.createElement("label");
  newElement.appendChild(document.createTextNode("Units"));
  newElement.appendChild(document.createElement("br"));
  var newElement2 = document.createElement("input");
  newElement2.setAttribute("type", "text");
  newElement2.setAttribute("placeholder", "Units");
  newElement2.setAttribute("id", "U0");
  newDiv.setAttribute("class", "ingredientUnits");
  newElement.appendChild(newElement2);
  newDiv.appendChild(newElement);

  document.getElementById("dynModal").appendChild(newDiv);
  document.getElementById("dynModal").appendChild(newElement);
  document.getElementById("dynModal").appendChild(document.createElement("br"));
  document.getElementById("dynModal").appendChild(document.createElement("br"));

  ( function() {
    newButton = document.createElement("button");
    newButton.setAttribute("id", "newIngredientFormButton");
    newButton.appendChild(document.createTextNode("Add another ingredient"));
    newButton.addEventListener("click", function() {
      var ingNameLabel = document.createElement("label");
      ingNameLabel.appendChild(document.createTextNode("Ingredient " + (iin + 1) + " Name"));
      var ingNameForm = document.createElement("input")
      ingNameForm.setAttribute("type", "text");
      ingNameForm.setAttribute("placeholder", "Name");
      ingNameForm.setAttribute("id", "IN" + iin);
      var amLabel = document.createElement("label");
      amLabel.appendChild(document.createTextNode("Amount"));
      var amForm = document.createElement("input")
      amForm.setAttribute("type", "text");
      amForm.setAttribute("placeholder", "Units");
      amForm.setAttribute("id", "A" + iin);
      var unLabel = document.createElement("label");
      unLabel.appendChild(document.createTextNode("Units"));
      var unForm = document.createElement("input")
      unForm.setAttribute("type", "text");
      unForm.setAttribute("placeholder", "Units");
      unForm.setAttribute("id", "U" + iin);

      var dyn = document.getElementById("dynModal");
      dyn.appendChild(ingNameLabel);
      dyn.appendChild(document.createElement("br"));
      dyn.appendChild(ingNameForm);
      dyn.appendChild(document.createElement("br"));
      dyn.appendChild(amLabel);
      dyn.appendChild(document.createElement("br"));
      dyn.appendChild(amForm);
      dyn.appendChild(document.createElement("br"));
      dyn.appendChild(unLabel);
      dyn.appendChild(document.createElement("br"));
      dyn.appendChild(unForm);
      dyn.appendChild(document.createElement("br"));
      dyn.appendChild(document.createElement("br"));
      iin++;
      dyn.removeChild(document.getElementById("newIngredientFormButton"));
      dyn.appendChild(newButton);
      dyn.removeChild(document.getElementById("newIngredientFormButton"));
      dyn.appendChild(newButton);
    });
    document.getElementById("dynModal").appendChild(newButton);
    }());
    //button, when pressed, create new ingredient field and add behind button
}

function logout(event) {
  setCookie("ID", "", 10);
  setCookie("User", "", 10);
  window.location.href = 'login.html';
}

function expandUser(event) {
  var modal = document.getElementById("modalBackground");
  modal.style.display = "block";
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
      const n = document.getElementById("dynModal");
      while (n.firstChild) {
        n.removeChild(n.firstChild);
      }
    }
  }
  document.getElementById("dynModal").appendChild(document.createTextNode("Username: " + User));
  document.getElementById("dynModal").appendChild(document.createElement("br"));
  var del = document.createElement("button");
  del.appendChild(document.createTextNode("Delete Account"));
  del.setAttribute("id", "accountDeleteButton");
  del.addEventListener("click", postDeleteAccountRequest);
  document.getElementById("dynModal").appendChild(del);
}

async function postDeleteAccountRequest(event) {
  try {
    const url = "http://lazymealprep.com/api/account.php";
    const response = await fetch(url, {
      method: 'POST',
      body: JSON.stringify({
        type: 'delete',
        username: User,
        accountID: ID
      })
    });
    const data = await response.json();
    if (data.status === 'success') {
      setCookie("ID", "", 10);
      setCookie("User", "", 10);
      window.location.href = 'login.html';
    }
  } catch (error) {
    console.error(error);
  }
}

async function getAllUserRecipesRequest(event) {
  try {
    const url = "http://lazymealprep.com/api/recipe.php" + "?type=all&accountID=" + ID;
    var response = await fetch(url, {
      method: 'GET'
    });
    data = await response.json();
    const n = document.getElementById("listBody");
    while (n.firstChild) {
      n.removeChild(n.firstChild);
    }
    for (var i = 0; i < data.recipes.length; i++) {
      (function () {
        var j = i;
        var newDiv = document.createElement("div");
        newDiv.setAttribute("i", j);
        newDiv.setAttribute("class", "dynRecipe");
        newDiv.appendChild(document.createTextNode(data.recipes[j].name));
        document.getElementById("listBody").appendChild(newDiv);
        newDiv.addEventListener("click", function(){expandRecipeFromList(j)});

        var deleteButton = document.createElement("button");
        deleteButton.setAttribute("i", j);
        deleteButton.setAttribute("class", "deleteButtonList");
        deleteButton.appendChild(document.createTextNode("Delete"));
        document.getElementById("listBody").appendChild(deleteButton);
        deleteButton.addEventListener("click", function(){deleteRecipeFromList(j)});
      }());
    }
  }
  catch (error) {
    console.error(error);
  }
}

async function getIngredients(rid) {
  try {
    const url = "http://lazymealprep.com/api/recipe.php" + "?type=one&recipeID=" + rid + "&accountID=" + ID;
    const response = await fetch(url, {
      method: 'GET'
    });
    const data = await response.json();
    var ingredients = []
    for (var i = 0; i < data.ingredients.length; i++) {
      ingredients.push(data.ingredients[i]);
    }
    return ingredients;

  } catch (error) {
    console.error(error);
    return null;
  }
}

async function deleteRecipeFromList(i) {
  try {
    const url = "http://lazymealprep.com/api/recipe.php";
    const response = await fetch(url, {
      method: 'POST',
      body: JSON.stringify({
        type: 'delete',
        ID: data.recipes[i].recipeID,
        accountID: ID
      })
    });
    const D = await response.json();
    if (D.status === 'success') {
      data.recipes.splice(i);
      getAllUserRecipesRequest();
    }
    else {
      null.value;
    }
  } catch (error) {
    console.error(error);
  }
}

async function expandRecipeFromList(i) {
  try {
    var modal = document.getElementById("modalBackground");
    modal.style.display = "block";
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
        const n = document.getElementById("dynModal");
        while (n.firstChild) {
          n.removeChild(n.firstChild);
        }
      }
    }
    var rid = data.recipes[i].recipeID;
    var rname = data.recipes[i].name;
    var ingredients = await getIngredients(rid);
    for (var a = 0; a < ingredients.length; a++) {
      (function () {
        var b = a;
        var newDiv = document.createElement("div");
        newDiv.setAttribute("i", b);
        newDiv.setAttribute("class", "dynIng");
        if (a == 0) {
          newDiv.appendChild(document.createTextNode(data.recipes[i].name));
          newDiv.appendChild(document.createElement("br"));
          newDiv.appendChild(document.createElement("br"));
        }
        newDiv.appendChild(document.createTextNode("Ingredient: " + ingredients[b].name));
        newDiv.appendChild(document.createElement("br"));
        newDiv.appendChild(document.createTextNode("Amount: " + ingredients[b].amount + " " + ingredients[b].unit + "(s)"));
        newDiv.appendChild(document.createElement("br"));
        newDiv.appendChild(document.createElement("br"));
        document.getElementById("dynModal").appendChild(newDiv);
      }());
    }
  } catch (error) {
    console.error(error);
  }
}
