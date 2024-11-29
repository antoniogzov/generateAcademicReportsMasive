$(document).ready(function () {
  $("#slct_academic_area").change(function () {
    $("#slct_academic_level").prop("disabled", false);
    $("#slct_academic_level").val("");
    $("#slct_campus").html(
      '<option value="0" selected disabled>Elija una opción</option>'
    );
    $("#slct_section").html(
      '<option value="0" selected disabled>Elija una opción</option>'
    );
  });
  //--- --- ---//
  $("#slct_academic_level").change(function () {
    loading();
    var id_academic_level = $(this).val();
    var id_academic_area = $("#slct_academic_area").val();
    $("#slct_section").html(
      '<option value="0" selected disabled>Elija una opción</option>'
    );
    $.ajax({
      url: "controllers/academicLevelReport/academicLevelReportController.php",
      method: "POST",
      data: {
        mod: "getCampusByAcademicLevel",
        id_academic_area: id_academic_area,
        id_academic_level: id_academic_level,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          html_options = "";
          html_options +=
            '<option value="0" selected disabled>Elija una opción</option>';
          for (let i = 0; i < data.data.length; i++) {
            html_options += `<option value="${data.data[i].id_campus}">${data.data[i].campus_name}</option>`;
          }
          $("#slct_campus").html(html_options);
          Swal.close();
        } else {
        }
        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {});
  });
  //--- --- ---//
  $("#slct_campus").change(function () {
    $("#slct_section").html(
      '<option value="0" selected disabled>Elija una opción</option>'
    );
    loading();
    var id_campus = $(this).val();
    var id_academic_level = $("#slct_academic_level").val();
    var id_academic_area = $("#slct_academic_area").val();
    $.ajax({
      url: "controllers/academicLevelReport/academicLevelReportController.php",
      method: "POST",
      data: {
        mod: "getSectionsByCampus",
        id_academic_area: id_academic_area,
        id_academic_level: id_academic_level,
        id_campus: id_campus,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          html_options = "";
          html_options +=
            '<option value="0" selected disabled>Elija una opción</option>';
          for (let i = 0; i < data.data.length; i++) {
            html_options += `<option value="${
              data.data[i].id_section
            }">${data.data[i].section.toUpperCase()}</option>`;
          }
          $("#slct_section").html(html_options);
          Swal.close();
        } else {
        }
        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {});
  });
  //--- --- ---//
  $("#slct_section").change(function () {
    loading();
    var id_section = $(this).val();
    var id_campus = $("#slct_campus").val();
    var id_academic_level = $("#slct_academic_level").val();
    var id_academic_area = $("#slct_academic_area").val();

    $.ajax({
      url: "controllers/academicLevelReport/academicLevelReportController.php",
      method: "POST",
      data: {
        mod: "getPeriods",
        id_academic_area: id_academic_area,
        id_academic_level: id_academic_level,
        id_campus: id_campus,
        id_section: id_section,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          html_options = "";
          html_options +=
            '<option value="0" selected disabled>Elija una opción</option>';
          for (let i = 0; i < data.data.length; i++) {
            html_options += `<option value="${data.data[i].id_period_calendar}">${data.data[i].no_period}</option>`;
          }
          $("#slct_period").html(html_options);
          Swal.close();
        } else {
        }
        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {});
  });
  //--- --- ---//
  //--- --- ---//
  $("#slct_period").change(function () {
    loading();
    var id_period = $(this).val();
    var no_period = $(this).find("option:selected").text();
    var id_section = $("#slct_section").val();
    var id_campus = $("#slct_campus").val();
    var id_academic_level = $("#slct_academic_level").val();
    var id_academic_area = $("#slct_academic_area").val();
    window.location.search = `?id_academic_area=${id_academic_area}&id_academic_level=${id_academic_level}&id_campus=${id_campus}&id_section=${id_section}&id_period=${id_period}&no_period=${no_period}`;
  });
  //--- --- ---//
  if (
    document.getElementById("groupsTable") != "undefined" &&
    document.getElementById("groupsTable") != null
  ) {
    var tf = new TableFilter(document.querySelector("#groupsTable"), {
      base_path: "assets/tablefilter/",
      paging: {
        results_per_page: ["Records: ", [10, 25, 50, 100]],
      },
      grid_layout: {
        width: "100%",
      },
      rows_counter: true,
      btn_reset: true,
      col_1: "select",
      col_3: "none",
    });
    tf.init();
  }
  //--- --- ---//
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
