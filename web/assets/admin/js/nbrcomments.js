$.ajax({
  url: "http://localhost:8000/admin/admin_count_comment",
  method: 'GET'
}).done(function(result) {
  $("#validcom").html(result);
});
