$(document).ready(function () {
  $(".generateGroupReports").click(function () {
    loading();
    const id_level_combination = $("#id_level_combination").val();
    const id_group = $(this).attr("data-id-group");
    const id_academic_area = $("#slct_academic_area").val();

    switch (id_level_combination) {
      case "6":
        console.log("getBangFemSpanPrimSR()");
        //--- --- ---//
        $.ajax({
          url: "schoolReports.php",
          method: "POST",
          data: {
            fun: "getSchoolReportCardHebrewPrimaryBangueolo",
            id_level_combination: "7",
            order_by_lang:
              "ORDER BY FIELD(sbj.id_subject, '358', '370','373', '365', '371','361','356') DESC",
            order_by_gral:
              "ORDER BY FIELD(sbj.id_subject, '372', '357','342') DESC",
            installment: 1,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              var response_data = data;
              //--- --- ---//
              getBangFemSpanPrimSR(images, response_data);
              //--- --- ---//
            } else {
              //--- --- ---//
              //--- --- ---//
            }
          })
          .fail(function (message) {
            alert("Ocurrió un error");
          });
        //--- --- ---//
        break;

      case "7":
        console.log("getBangFemHebPrimSR()");
        //--- --- ---//
        $.ajax({
          url: "controllers/academicLevelReport/schoolReportsPrimBang.php",
          method: "POST",
          data: {
            fun: "getBangFemHebPrimSR",
            id_level_combination: id_level_combination,
            id_group: id_group,
            id_academic_area: id_academic_area,
            order_by_lang:
              "ORDER BY sbj.name_subject",
            order_by_gral:
              "ORDER BY sbj.name_subject",
            installment: 1,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              var response_data = data;
              console.log(response_data);
              //--- --- ---//
              getBangFemHebPrimSR(response_data);

              Swal.close();
              //--- --- ---//
            } else {
              //--- --- ---//
              //--- --- ---//
            }
          })
          .fail(function (message) {
            alert("Ocurrió un error");
          });
        //--- --- ---//
        break;

      default:
        break;
    }
  });

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
