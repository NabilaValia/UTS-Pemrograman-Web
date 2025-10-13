// === FADE IN ===
// === SAAT HALAMAN SELESAI DIMUAT ===
$(document).ready(function() {
  $("body").addClass("fade-in");

  // === FADE OUT + TRANSISI UNTUK LINK & BUTTON ===
  $("a, button").on("click", function(e) {
    let href = $(this).attr("href") || $(this).data("href");

    if (href && href.endsWith(".html")) {
      e.preventDefault();

      $("body")
        .removeClass("fade-in")
        .addClass("fade-out");

      setTimeout(function() {
        window.location.href = href;
      }, 400); // tunggu animasi 0.4 detik
    }
  });
});
