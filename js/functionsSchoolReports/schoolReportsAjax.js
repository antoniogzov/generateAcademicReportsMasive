$(document).ready(function () {
  $(".generateGroupReports").click(function () {
    loading();
    const id_level_combination = $("#id_level_combination").val();
    const id_group = $(this).attr("data-id-group");
    const id_academic_area = $("#slct_academic_area").val();
    console.log(id_level_combination);
    switch (id_level_combination) {
      case "6":
        console.log("getBangFemSpanPrimSR()");
        //--- --- ---//
        $.ajax({
          url: "controllers/academicLevelReport/schoolReportsPrimBangSpan.php",
          method: "POST",
          data: {
            fun: "getBangFemSpanPrimSR",
            id_level_combination: id_level_combination,
            id_group: id_group,
            id_academic_area: id_academic_area,
            order_by_lang: "ORDER BY sbj.name_subject",
            order_by_gral: "ORDER BY sbj.name_subject",
            installment: 1,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              var response_data = data;
              console.log(response_data);
              //--- --- ---//
              getBangFemSpanPrimSR(response_data);
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
        //console.log("getBangFemHebPrimSR()");
        //--- --- ---//
        $.ajax({
          url: "controllers/academicLevelReport/schoolReportsPrimBangHeb.php",
          method: "POST",
          data: {
            fun: "getBangFemHebPrimSR",
            id_level_combination: id_level_combination,
            id_group: id_group,
            id_academic_area: id_academic_area,
            order_by_lang: "ORDER BY sbj.name_subject",
            order_by_gral: "ORDER BY sbj.name_subject",
            installment: 1,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              var response_data = data;
              //console.log(response_data);
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
      case "20":
        //console.log("getBangFemHebPrimSR()");
        //--- --- ---//
        $.ajax({
          url: "controllers/academicLevelReport/schoolReportsHighBang.php",
          method: "POST",
          data: {
            fun: "getbangFemalesMixedHighSR",
            id_level_combination_heb: 21,
            id_level_combination_span: 20,
            id_group: id_group,
            id_academic_area: id_academic_area,
            order_by_lang: "ORDER BY sbj.name_subject",
            order_by_gral: "ORDER BY sbj.name_subject",
            installment: 1,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              var response_data = data;
              console.log(response_data);
              //--- --- ---//
              getBangHighMixedSR(response_data);

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
      case "21":
        //console.log("getBangFemHebPrimSR()");
        //--- --- ---//
        $.ajax({
          url: "controllers/academicLevelReport/schoolReportsHighBang.php",
          method: "POST",
          data: {
            fun: "getbangFemalesMixedHighSR",
            id_level_combination_heb: 21,
            id_level_combination_span: 20,
            id_group: id_group,
            id_academic_area: id_academic_area,
            order_by_lang: "ORDER BY sbj.name_subject",
            order_by_gral: "ORDER BY sbj.name_subject",
            installment: 1,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              var response_data = data;
              console.log(response_data);
              //--- --- ---//
              getBangHighMixedSR(response_data);

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

      case "22":
        //console.log("getBangFemHebPrimSR()");
        //--- --- ---//
        $.ajax({
          url: "controllers/academicLevelReport/schoolReportsHighLFSpan.php",
          method: "POST",
          data: {
            fun: "getLafMalesSpanHighSR",
            id_level_combination: id_level_combination,
            id_group: id_group,
            id_academic_area: id_academic_area,
            order_by_lang: "ORDER BY sbj.name_subject",
            order_by_gral: "ORDER BY sbj.name_subject",
            installment: 1,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              var response_data = data;
              console.log(response_data);
              //--- --- ---//
              getLFHighSpanSR(response_data);

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
