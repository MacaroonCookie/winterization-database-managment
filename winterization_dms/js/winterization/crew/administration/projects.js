function loadProjectsJS() {
  $("window").load(function() {
    alert("It works"); });
  $("a#add_project").click(function() {
    $("tr#winter-hidden-add").toggle();
    });
}
