$.ajax({
  url: "http://localhost:8000/admin/comment/count",
  method: 'GET'
}).done(function(result) {
  $("#validcom span").html(result);
});
