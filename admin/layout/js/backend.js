$(function () {
  "use strict";

  // Dashboard

  $(".toggle-info").click(function () {
    $(this)
      .toggleClass("selected")
      .parent()
      .next(".panel-body")
      .fadeToggle(200);

    if ($(this).hasClass("selected")) {
      $(this).html('<i class="fa fa-minus fa-lg"></i>');
    } else {
      $(this).html('<i class="fa fa-plus fa-lg"></i>');
    }
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

  // Convert Password Field To Text Field On Hover

  let passFiled = $(".password");

  $(".show-pass").hover(
    function () {
      passFiled.attr("type", "text");
    },
    function () {
      passFiled.attr("type", "password");
    }
  );

  // Confirm message on Delete button
  $(".confirm").click(function () {
    return confirm("Are you sure you want to Delete this item");
  });

  // Category View Option
  $(".cat h3").click(function () {
    $(this).next(".full-view").fadeToggle(300);
  });

  $(".options span").click(function () {
    $(this).addClass("active").siblings("span").removeClass("active");

    if ($(this).data("view") === "full") {
      $(".cat .full-view").fadeIn(200);
    } else {
      $(".cat .full-view").fadeOut(200);
    }
  });

  // Show Delete Button On Child Categories
  $(".child-cat").hover(
    function () {
      $(this).find(".show-delete").fadeIn();
    },
    function () {
      $(this).find(".show-delete").fadeOut();
    }
  );
});
