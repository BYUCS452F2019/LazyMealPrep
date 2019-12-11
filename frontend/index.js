var ID = getCookie("ID");
if (ID != "" && ID != null) window.location.href = 'main.html'
else {
  setCookie("ID", "", 10);
  setCookie("User", "", 10);
  window.location.href = 'login.html'
}
