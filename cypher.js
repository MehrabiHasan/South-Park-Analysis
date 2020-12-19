$("#reload").click(function() {

  var cypher = $("#cypher").val();

  if (cypher.length > 3) {
    viz.renderWithCypher(cypher);
  } else {
    console.log("reload");
    viz.reload();

  }

});

$("#stabilize").click(function() {
  viz.stabilize();
})
