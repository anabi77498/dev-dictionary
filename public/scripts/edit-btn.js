

$(document).ready(function () {
  $('#edit-btn-id').addClass("hidden");
})

$("#btn-mode").click(function () {
  if ($("#edit-btn-id").hasClass("hidden")) {
    $('#edit-btn-id').removeClass("hidden");
  }
  else {
    $('#edit-btn-id').addClass("hidden");
  }
})
