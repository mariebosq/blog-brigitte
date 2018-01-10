$.ajax({
  url: "admin_count_comment",
  method: 'GET'
}).done(function(result) {
  $("#validcom").html(result);
});
