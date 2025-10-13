window.addEventListener("DOMContentLoaded", () => {//menunggu slruh struktur html slsai d muat
  document.body.classList.add("fade-in");// menjalankan tiap baris stlh dimuat 
});

// FADE OUT + TRANSISI UNTUK LINK & BUTTON
function addFadeTransition(selector) { //mendefinisikan  
  document.querySelectorAll(selector).forEach(el => { //nyari elemen yg cocok dgn selector
    el.addEventListener("click", function(e) { //mnambah event listener klik
      let href = this.getAttribute("href"); //ambil nilai atribut href dr elemen yg d klik

      // Cek tombol yang pakai data-href
      if (!href && this.dataset.href) {
        href = this.dataset.href;  
      }

      if (href && href.endsWith(".html")) { //d cek apkah ada href d link berakhiran .html
        e.preventDefault();  //biar gk default atau pindah dl
        document.body.classList.remove("fade-in");
        document.body.classList.add("fade-out");
        setTimeout(() => {
          window.location.href = href;
        }, 400); // tunggu animasi 0.4 detik
      }
    });
  });
}
addFadeTransition("a, button");