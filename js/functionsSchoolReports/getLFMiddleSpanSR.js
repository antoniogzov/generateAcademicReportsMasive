var global_a = 0;
async function getSchoolReportCardSpanishSecondaryMalesLFT(imgLogo, data) {
  //--- --- ---//
  console.log("hereeeeeeeeeeee");
  var a = global_a;
  //--- --- ---//
  console.log(data);
  if (a < data.groups_esp.length) {
    console.log("Grupo : " + data.groups_esp[a].group_code);
    for (var b = 0; b < data.groups_esp[a].students.length; b++) {
      //--- --- ---//
      var student_name =
        data.groups_esp[a].students[b].student_name.toUpperCase();
      var group_name = data.groups_esp[a].group_code;
      const student_code = data.groups_esp[a].students[b].student_code;
      var school_cycle = data.current_school_year;
      //--- EVALUACIONES ESPAÑOL ---//
      var data_school_report = [];
      var averages = [];
      //--- --- ---//
      var avg_final_period1 = 0;
      var cont_p1 = 0;
      var avg_final_period2 = 0;
      var cont_p2 = 0;
      var avg_final_period3 = 0;
      var cont_p3 = 0;
      var avg_final_period4 = 0;
      var cont_p4 = 0;
      var avg_final_period5 = 0;
      var cont_p5 = 0;
      //--- --- ---//
      var data_school_report_absence = [];
      var arr_total_absences = [];
      var total_absences1 = 0;
      var total_absences2 = 0;
      var total_absences3 = 0;
      var total_absences4 = 0;
      var total_absences5 = 0;
      //--- --- ---//
      if (data.groups_esp[a].assignments.length > 0) {
        //--- ASIGNATURAS ESPAÑOL ---//
        if (data.groups_esp[a].assignments.length > 0) {
          //--- --- ---//
          for (var c = 0; c < data.groups_esp[a].assignments.length; c++) {
            //--- --- ---//
            var assgn_name_spa = data.groups_esp[a].assignments[c].name_subject;
            console.log("Asignatura : " + assgn_name_spa);
            var avg_period1 = 0;
            var absence1 = 0;
            //--- --- ---//
            var avg_period2 = 0;
            var absence2 = 0;
            //--- --- ---//
            var avg_period3 = 0;
            var absence3 = 0;
            //--- --- ---//
            var avg_period4 = 0;
            var absence4 = 0;
            //--- --- ---//
            var avg_period5 = 0;
            var absence5 = 0;
            //--- --- ---//
            var cont_final_average_assg = 0;
            var average_final_assg = 0;
            var final_absensence_assg = 0;
            //--- --- ---//
            for (var d = 0; d < data.periods_span.length; d++) {
              //--- --- ---//
              for (
                var e = 0;
                e < data.groups_esp[a].assignments[c].averages.length;
                e++
              ) {
                for (
                  var g = 0;
                  g < data.groups_esp[a].assignments[c].averages[e].length;
                  g++
                ) {
                  //--- --- ---//
                  if (
                    data.groups_esp[a].students[b].id_student ==
                    data.groups_esp[a].assignments[c].averages[e][g].id_student
                  ) {
                    for (
                      var f = 0;
                      f <
                      data.groups_esp[a].assignments[c].averages[e][g].grades
                        .length;
                      f++
                    ) {
                      //--- PRIMER PERIODO --//
                      if (
                        data.periods_span[d].id_period_calendar ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].id_period_calendar &&
                        data.periods_span[d].no_period ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].no_period &&
                        data.periods_span[d].no_period == "1" &&
                        data.groups_esp[a].assignments[c].averages[e][g].grades[
                          f
                        ].no_period == "1"
                      ) {
                        //--- AUSENCIAS ---//
                        absence1 =
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences;
                        total_absences1 += parseInt(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        final_absensence_assg += parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        //--- CALIFICACIONES ---//
                        avg_period1 = parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].grade_period
                        );
                        if (avg_period1 > 6) {
                          avg_period1 = Math.round(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        } else {
                          avg_period1 = Math.floor(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        }
                        if (
                          parseInt(
                            data.groups_esp[a].assignments[c].subject_type_id
                          ) == 1
                        ) {
                          if (
                            avg_period1 > 0 &&
                            avg_period1 &&
                            avg_period1 != "NaN"
                          ) {
                            avg_final_period1 += avg_period1;
                            cont_p1++;
                            //--- --- ---//
                            average_final_assg += avg_period1;
                            cont_final_average_assg++;
                            //--- --- ---//
                          }
                        }
                        //--- --- ---//
                      }
                      //--- --- ---//
                      //--- SEGUNDO PERIODO --//
                      if (
                        data.periods_span[d].id_period_calendar ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].id_period_calendar &&
                        data.periods_span[d].no_period ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].no_period &&
                        data.periods_span[d].no_period == "2" &&
                        data.groups_esp[a].assignments[c].averages[e][g].grades[
                          f
                        ].no_period == "2"
                      ) {
                        //--- AUSENCIAS ---//
                        absence2 =
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences;
                        total_absences2 += parseInt(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        final_absensence_assg += parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        //--- CALIFICACIONES ---//
                        avg_period2 = parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].grade_period
                        );
                        if (avg_period2 > 6) {
                          avg_period2 = Math.round(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        } else {
                          avg_period2 = Math.floor(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        }
                        if (
                          parseInt(
                            data.groups_esp[a].assignments[c].subject_type_id
                          ) == 1
                        ) {
                          if (
                            avg_period2 > 0 &&
                            avg_period2 &&
                            avg_period2 != "NaN"
                          ) {
                            avg_final_period2 += avg_period2;
                            cont_p2++;
                            //--- --- ---//
                            average_final_assg += avg_period2;
                            cont_final_average_assg++;
                            //--- --- ---//
                          }
                        }
                        //--- --- ---//
                      }
                      //--- --- ---//
                      //--- TERCER PERIODO --//
                      if (
                        data.periods_span[d].id_period_calendar ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].id_period_calendar &&
                        data.periods_span[d].no_period ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].no_period &&
                        data.periods_span[d].no_period == "3" &&
                        data.groups_esp[a].assignments[c].averages[e][g].grades[
                          f
                        ].no_period == "3"
                      ) {
                        //--- AUSENCIAS ---//
                        absence3 =
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences;
                        total_absences3 += parseInt(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        final_absensence_assg += parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        //--- CALIFICACIONES ---//
                        avg_period3 = parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].grade_period
                        );
                        if (avg_period3 > 6) {
                          avg_period3 = Math.round(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        } else {
                          avg_period3 = Math.floor(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        }
                        if (
                          parseInt(
                            data.groups_esp[a].assignments[c].subject_type_id
                          ) == 1
                        ) {
                          if (
                            avg_period3 > 0 &&
                            avg_period3 &&
                            avg_period3 != "NaN"
                          ) {
                            avg_final_period3 += avg_period3;
                            cont_p3++;
                            //--- --- ---//
                            average_final_assg += avg_period3;
                            cont_final_average_assg++;
                            //--- --- ---//
                          }
                        }
                        //--- --- ---//
                      }
                      //--- --- ---//
                      //--- CUARTO PERIODO --//
                      if (
                        data.periods_span[d].id_period_calendar ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].id_period_calendar &&
                        data.periods_span[d].no_period ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].no_period &&
                        data.periods_span[d].no_period == "4" &&
                        data.groups_esp[a].assignments[c].averages[e][g].grades[
                          f
                        ].no_period == "4"
                      ) {
                        //--- AUSENCIAS ---//
                        absence4 =
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences;
                        total_absences4 += parseInt(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        final_absensence_assg += parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        //--- CALIFICACIONES ---//
                        avg_period4 = parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].grade_period
                        );
                        if (avg_period4 > 6) {
                          avg_period4 = Math.round(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        } else {
                          avg_period4 = Math.floor(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        }
                        if (
                          parseInt(
                            data.groups_esp[a].assignments[c].subject_type_id
                          ) == 1
                        ) {
                          if (
                            avg_period4 > 0 &&
                            avg_period4 &&
                            avg_period4 != "NaN"
                          ) {
                            avg_final_period4 += avg_period4;
                            cont_p4++;
                            //--- --- ---//
                            average_final_assg += avg_period4;
                            cont_final_average_assg++;
                            //--- --- ---//
                          }
                        }
                        //--- --- ---//
                      }
                      //--- QUINTO PERIODO --//
                      if (
                        data.periods_span[d].id_period_calendar ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].id_period_calendar &&
                        data.periods_span[d].no_period ==
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].no_period &&
                        data.periods_span[d].no_period == "5" &&
                        data.groups_esp[a].assignments[c].averages[e][g].grades[
                          f
                        ].no_period == "5"
                      ) {
                        //--- AUSENCIAS ---//
                        absence5 =
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences;
                        total_absences5 += parseInt(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        final_absensence_assg += parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].absences
                        );
                        //--- CALIFICACIONES ---//
                        avg_period5 = parseFloat(
                          data.groups_esp[a].assignments[c].averages[e][g]
                            .grades[f].grade_period
                        );
                        if (avg_period5 > 6) {
                          avg_period5 = Math.round(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        } else {
                          avg_period5 = Math.floor(
                            data.groups_esp[a].assignments[c].averages[e][g]
                              .grades[f].grade_period
                          );
                        }
                        if (
                          parseInt(
                            data.groups_esp[a].assignments[c].subject_type_id
                          ) == 1
                        ) {
                          if (
                            avg_period5 > 0 &&
                            avg_period5 &&
                            avg_period5 != "NaN"
                          ) {
                            avg_final_period5 += avg_period5;
                            cont_p5++;
                            //--- --- ---//
                            average_final_assg += avg_period5;
                            cont_final_average_assg++;
                            //--- --- ---//
                          }
                        }
                        //--- --- ---//
                      }
                    }
                    //--- --- ---//
                  }
                }
                //--- --- ---//
              }
              //--- --- ---//
            }
            //--- --- --//
            //--- --- --//
            if (avg_period1 > 0) {
              avg_period1 = avg_period1.toFixed(1);
            } else {
              avg_period1 = "";
            }
            //--- --- --//
            if (avg_period2 > 0) {
              avg_period2 = avg_period2.toFixed(1);
            } else {
              avg_period2 = "";
            }
            //--- --- --//
            if (avg_period3 > 0) {
              avg_period3 = avg_period3.toFixed(1);
            } else {
              avg_period3 = "";
            }
            //--- --- --//
            if (avg_period4 > 0) {
              avg_period4 = avg_period4.toFixed(1);
            } else {
              avg_period4 = "";
            }
            //--- --- --//
            if (avg_period5 > 0) {
              avg_period5 = avg_period5.toFixed(1);
            } else {
              avg_period5 = "";
            }
            //--- --- --//
            average_final_assg = average_final_assg / cont_final_average_assg;
            average_final_assg = average_final_assg.toFixed(1);
            //--- --- ---//
            var info = [
              assgn_name_spa,
              avg_period1,
              avg_period2,
              avg_period3,
              avg_period4,
              average_final_assg,
            ];
            data_school_report.push(info);
            //--- --- ---//
            var info_absences = [
              assgn_name_spa,
              absence1,
              absence2,
              absence3,
              absence4,
              final_absensence_assg,
            ];
            data_school_report_absence.push(info_absences);
            //--- --- ---//
          }
          //--- --- ---//
          let final_average_span1 = "";
          let final_average_span2 = "";
          let final_average_span3 = "";
          let final_average_span4 = "";
          let final_average_span5 = "";
          //--- --- ---//
          if (avg_final_period1 > 0) {
            final_average_span1 = avg_final_period1 / cont_p1;
            final_average_span1 = final_average_span1.toFixed(1);
          }
          //--- --- ---//
          if (avg_final_period2 > 0) {
            final_average_span2 = avg_final_period2 / cont_p2;
            final_average_span2 = final_average_span2.toFixed(1);
          }
          //--- --- ---//
          if (avg_final_period3 > 0) {
            final_average_span3 = avg_final_period3 / cont_p3;
            final_average_span3 = final_average_span3.toFixed(1);
          }
          //--- --- ---//
          if (avg_final_period4 > 0) {
            final_average_span4 = avg_final_period4 / cont_p4;
            final_average_span4 = final_average_span4.toFixed(1);
          }
          //--- --- ---//
          if (avg_final_period5 > 0) {
            final_average_span5 = avg_final_period5 / cont_p5;
            final_average_span5 = final_average_span5.toFixed(1);
          }
          //--- --- ---//
          var info_av = [
            "PROMEDIO",
            final_average_span1,
            final_average_span2,
            final_average_span3,
            final_average_span4,
          ];
          averages.push(info_av);
          //--- --- ---//
          //--- --- ---//
          arr_total_absences.push([
            "TOTALES",
            total_absences1,
            total_absences2,
            total_absences3,
            total_absences4,
          ]);
          //--- --- ---//
        }
        //--- --- ---//
        window.jsPDF = window.jspdf.jsPDF;
        var doc = new jsPDF("landscape");
        //--- --- ---//
        //console.log(imgLogo);
        doc.addImage(imgLogo, "JPG", 230, 10, 38, 40);
        doc.setFontSize(14);
        doc.text(55, 19, "REPORTE DE CALIFICACIONES");
        doc.text(75, 31, "SECUNDARIA");
        doc.text(64, 37, '"ALBERTO EINSTEIN"');
        //--- --- ---//
        doc.setFontSize(8);
        doc.text(40, 48, "NOMBRE DEL ALUMNO: " + student_name);
        doc.text(40, 52, "GRUPO: " + group_name);
        doc.text(71, 52, "CÓDIGO: " + student_code);
        doc.text(101, 52, "CICLO ESCOLAR: " + school_cycle);
        //--- TABLA IZQUIERDA ---//
        doc.autoTable({
          startY: 58,
          tableWidth: 160,
          headStyles: {
            fillColor: [255, 255, 255],
            textColor: [0, 0, 0],
            lineWidth: 0.1,
            lineColor: [0, 0, 0],
            fontSize: 6,
            valign: "middle",
            halign: "center",
          },
          bodyStyles: {
            fontSize: 8,
            halign: "center",
          },
          footStyles: {
            fontSize: 8,
            halign: "center",
            valign: "middle",
            fillColor: [255, 255, 255],
            textColor: [0, 0, 0],
            fontSize: 7,
          },
          head: [
            [
              {
                content: "ASIGNATURAS",
                rowSpan: 3,
                styles: {
                  halign: "center",
                },
              },
              {
                content: "EVALUACIONES",
                colSpan: 4,
                styles: {
                  halign: "center",
                },
              },
              {
                content: "CALIFICACIÓN FINAL",
                rowSpan: 3,
                styles: {
                  halign: "center",
                },
              },
            ],
            //--- --- ---//
            [
              "PERIODO I",
              "PERIODO II",
              "PERIODO III",
              "PERIODO IV",
            ],
            ["CALIF", "CALIF", "CALIF", "CALIF"],
            //--- --- ---//
          ],
          body: data_school_report,
          foot: averages,
        });
        //--- TABLA DERECHA ---//
        doc.autoTable({
          startY: 58,
          tableWidth: 105,
          margin: {
            left: 180,
          },
          headStyles: {
            fillColor: [255, 255, 255],
            textColor: [0, 0, 0],
            lineWidth: 0.1,
            lineColor: [0, 0, 0],
            fontSize: 6,
            valign: "middle",
            halign: "center",
          },
          bodyStyles: {
            fontSize: 8,
            halign: "center",
          },
          footStyles: {
            fontSize: 8,
            halign: "center",
            valign: "middle",
            fillColor: [255, 255, 255],
            textColor: [0, 0, 0],
            fontSize: 7,
          },
          head: [
            [
              {
                content: "ASIGNATURAS",
                rowSpan: 3,
                styles: {
                  halign: "center",
                },
              },
              {
                content: "INASISTENCIAS",
                colSpan: 4,
                styles: {
                  halign: "center",
                },
              },
              {
                content: "TOTAL",
                rowSpan: 2,
                styles: {
                  halign: "center",
                },
              },
            ],
            //--- --- ---//
            ["I", "II", "III", "IV"],
            //--- --- ---//
            ["FALT", "FALT", "FALT", "FALT", "FALT"],
            //--- --- ---//
          ],
          body: data_school_report_absence,
        });
        //foot: arr_total_absences,
        //--- FIRMA DIRECTOR TÉCNICO ---//
        doc.setFontSize(7);
        doc.setDrawColor(0, 0, 0);
        doc.text(76, 176, "MOSHE SHABOT KASSIN");
        doc.line(63, 177, 120, 177);
        doc.text(85, 180, "DIRECTOR");
        //--- FIRMA TUTOR ---//
        doc.line(168, 177, 248, 177);
        doc.text(180, 180, "NOMBRE Y FIRMA DEL PADRE, MADRE O TUTOR");
        //--- --- ---//
        doc.text(
          20,
          200,
          "**Los alumnos que presenten asignaturas evaluadas, con calificación menor a 6 tendran derecho a realizar actividades de recuperación para pasar el periodo correspondiente."
        );
        //--- --- ---//
        doc.save(student_code + ".pdf");
        /*var string = doc.output('datauristring');
                var embed = "<embed width='100%' height='100%' src='" + string + "'/>"
                var x = window.open();
                x.document.open();
                x.document.write(embed);
                x.document.close();*/
        //--- --- ---//
        await timer(3000);
        //--- --- ---//
      }
      //--- --- ---//
      setTimeout(function () {
        global_a++;
        getSchoolReportCardSpanishSecondaryMalesLFT(imgLogo, data);
      }, 4000);
      //--- --- ---//
    }
  }
}
