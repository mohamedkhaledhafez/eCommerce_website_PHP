$(function () {
  "use strict";

  // Switch Between Login & SignUp

  $(".login-page h1 span").click(function () {
    $(this).addClass("selected").siblings().removeClass("selected");
    $(".login-page form").hide();
    // console.log($(this).data("class"));
    $("." + $(this).data("class")).fadeIn(100);
  });

  // Fire / Trigger The Selectboxit
  $("select").selectBoxIt({
    autoWidth: false,
  });

  // Hide placeholder on focus :
  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));
      $(this).attr("placeholder", "");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });

  // Add * on required fields
  $("input").each(function () {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });

  // Confirm message on Delete button
  $(".confirm").click(function () {
    return confirm("Are you sure you want to Delete this item");
  });

  $('.live').keyup(function () {
    $($(this).data('class')).text($(this).val()); 
  });

});
