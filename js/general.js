$(document).ready(function () {
   function loading() {
    Swal.fire({
      title: "Cargando...",
      html: '<img src="imgs/loading.gif" width="300" height="175">',
      allowOutsideClick: false,
      allowEscapeKey: false,
      showCloseButton: false,
      showCancelButton: false,
      showConfirmButton: false,
    });
    }
});
