$(function () {
  $("#current_emails").text($("#example_email").val());
  $("#example_email").multiple_emails();
  $("#example_email").change(function () {
    $("#current_emails").text($(this).val());
  });
});
