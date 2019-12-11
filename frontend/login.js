$('#loginContent').css("display", "flex");
window.onload = function() {
  document.getElementById("loginButton").addEventListener("click", postLoginRequest);
  document.getElementById("regButton").addEventListener("click", postNewAccountRequest);
  document.getElementById("registerSwitch").addEventListener("click", regswitch);
  document.getElementById("loginSwitch").addEventListener("click", logswitch);
}

function regswitch() {
  $('#registerContent').css("display", "flex");
  $('#loginContent').css("display", "none");

}

function logswitch() {
  $('#registerContent').css("display", "none");
  $('#loginContent').css("display", "flex");
  document.getElementById("message").innerHTML = "";
  document.getElementById("regmessage").innerHTML = "";
}

async function postLoginRequest(event) {
  try {
    const username = document.getElementById("loginUser").value;
    const password = document.getElementById("loginPassword").value;
    const url = "http://lazymealprep.com/api/account.php";
    const response = await fetch(url, {
      method: 'POST',
      body: JSON.stringify({
        type: 'login',
        username: username,
        password: password
      })
    });
    const data = await response.json();
    setCookie("ID", data.accountID, 10);
    setCookie("User", username, 10);
  } catch (error) {
    document.getElementById("message").innerHTML = "Error - bad API call";
    console.error(error);
  }
  if (getCookie("ID") === "") {
    document.getElementById("message").innerHTML = "Invalid username or password";
  } else window.location.href = 'main.html'
}

async function postNewAccountRequest(event) {
  try {
    const username = document.getElementById("regUser").value;
    const email = document.getElementById("regEmail").value;
    const password = document.getElementById("regPassword").value;
    const url = "http://lazymealprep.com/api/account.php";
    const response = await fetch(url, {
      method: 'POST',
      body: JSON.stringify({
        type: 'new',
        username: username,
        email: email,
        password: password})
    });
    const data = await response.json();
    if (JSON.stringify(data) === "\"username, email, or password missing\"") document.getElementById("regmessage").innerHTML = "Missing username, password, or email";
    else {
      setCookie("ID", data.accountID, 10);
      setCookie("User", username, 10);
      window.location.href = 'main.html'
    }
  } catch (error) {
    document.getElementById("regmessage").innerHTML = "Error - bad API call";
    console.error(error);
  }
}
