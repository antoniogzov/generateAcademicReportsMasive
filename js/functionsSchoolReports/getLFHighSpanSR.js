async function getLFHighSpanSR(imgLogo, data) {
    for (let i_group = 0; i_group < data.groups_span.length; i_group++) {
        var school_cycle = data.current_school_year;
        console.log(data);
        var group_name = data.groups_span[i_group].group_code;
        var letter = data.groups_span[i_group].letter;
        var name_hebrew_group = data.groups_span[i_group].hebrew_group;
        var level_degree = data.groups_span[i_group].degree;
        var grade = data.groups_span[0].degree.toUpperCase();
        var technical_director = "Morá Ruthy Jasqui";
        for (let ind_std = 0; ind_std < data.groups_span[i_group].students[0].length; ind_std++) {
            var valid_structure = 0;
            var data_school_assignments = [];
            var data_school_assignments_span = [];
            var data_school_averages_heb = [];
            var data_school_averages_span = [];
            var prom_1 = 0;
            var prom_2 = 0;
            var prom_3 = 0;
            var prom_final = 0;
            var valid_prom_1 = 0;
            var valid_prom_2 = 0;
            var valid_prom_3 = 0;
            var valid_prom_final = 0;
            var data_school_conductual = [];
            /* console.log(data.groups_span[i_group].students[0][ind_std]); */
            var student_code = data.groups_span[i_group].students[0][ind_std].student.student_code;
            var student_name = data.groups_span[i_group].students[0][ind_std].student.student_name;
            var student_hebrew_name = data.groups_span[i_group].students[0][ind_std].student.hebrew_name;
            var array_all_periods_heb_qualifications_cond = data.groups_span[i_group].students[0][ind_std].qualifications_cond;
            var array_all_periods_heb_qualifications_span = data.groups_span[i_group].students[0][ind_std].qualifications_cond;
            var array_all_periods_span_qualifications = data.groups_span[i_group].students[0][ind_std].qualifications;
            var speciality = data.groups_span[i_group].students[0][ind_std].speciality[0].comment_group;
            console.log(array_all_periods_span_qualifications);
            ///-- nombre de materia y profesor -- ///
            for (let assignment_period = 0; assignment_period <= 0; assignment_period++) {
                assignment = [];
                for (let period_qualifs = 0; period_qualifs < array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications.length; period_qualifs++) {
                    name_subject = array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].name_subject;
                    spanish_name_teacher = array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].spanish_name_teacher;
                    var subject_type = "GENERAL";
                    if (array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].group_type_id == 2) {
                        subject_type = "ESPECIALIDAD";
                    }
                    var pases_lista = array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].pases_lista;
                    var faltas = array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].faltas;
                    data_school_assignments.push([
                        name_subject,
                        subject_type,
                    ]);
                    /* data_school_assignments.push([
                      name_subject,
                      subject_type,
                      pases_lista,
                      faltas,
                    ]); */
                }
            }
            ///-- CALIFICACIONES -- ///
            for (let assignment_period = 0; assignment_period <= 0; assignment_period++) {
                assignment = [];
                for (let period_qualifs = 0; period_qualifs < array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications.length; period_qualifs++) {
                    console.log(array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].no_period);
                    var calificacion = "-";
                    if (array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].calificacion != "" && array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].calificacion != undefined && !isNaN(array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].calificacion)) {
                        calificacion = array_all_periods_span_qualifications[assignment_period].spanish_period_qualifications[period_qualifs].calificacion;
                        calificacion = calificacion;
                        data_school_assignments[period_qualifs].push(calificacion);
                        valid_prom_1++;
                        prom_1 = prom_1 + parseFloat(calificacion);
                        console.log(prom_1);
                    }
                }
                if (prom_1 > 0) {
                    prom_1 = prom_1 / valid_prom_1;
                    prom_1 = prom_1.toFixed(1);
                } else {
                    prom_1 = "-";
                }
                data_school_averages_span = [
                    [{
                        colSpan: 2,
                        content: "PROMEDIO: ",
                        styles: {
                            fontStyle: "bold",
                            halign: "right"
                        },
                    }, {
                        content: prom_1,
                        styles: {
                            fontStyle: "bold",
                            halign: "left"
                        },
                    }, ],
                ];
            }
            console.log(data_school_assignments);
            /////////////////////////////////////////////////////////////////////////
            /* Isntancia JsPDF */
            window.jsPDF = window.jspdf.jsPDF;
            var doc = new jsPDF("portrait");
            //--- --- ---//
            doc.addFileToVFS("assets/fonts/coolvetica-rg.ttf");
            doc.addFileToVFS("assets/fonts/VarelaRound-Regular.ttf");
            doc.addFont("assets/fonts/coolvetica-rg.ttf", "coolvetica-rg", "normal");
            //MADEAvenue
            doc.addFont("assets/fonts/VarelaRound-Regular.ttf", "VarelaRound-Regular", "normal");
            doc.setFont("VarelaRound-Regular"); // set font
            doc.setFontSize(18);
            doc.text(53, 33, "REPORTE DE CALIFICACIONES");
            doc.setFont("coolvetica-rg"); // set font
            doc.setFontSize(12);
            doc.text(61, 38, "NUEVA PREPARATORIA DE ESPECIALIDADES");
            doc.setFontSize(10);
            //  doc.text(119, 10, reverse("בנות כתר תורה")); // ESCUELA DE NIÑAS KETER TORÁ
            doc.setFontSize(14);
            //doc.text(124, 16, reverse("שנת לימודים תשפ''ב"));
            //doc.text(110, 15, " בית ספר בנות כתר תורה"); // ESCUELA DE NIÑAS KETER TORÁ
            //doc.text(117, 15, " בית ספר גברים כתר תורה"); // ESCUELA DE NIÑOS KETER TORÁ
            doc.setR2L(false);
            doc.setFontSize(10);
            doc.setFontSize(7);
            /* doc.text(261, 8, "MÉXICO"); */
            //doc.text(262, 11, reverse("מקסיקו"));
            /* 
     doc.text(232, 11, student_code);
     doc.text(230, 15, group_name); */
            /*  doc.text(37, 8, "SECUNDARIA");*/
            //doc.text(23, 21, reverse("תיכון וסמינר"));
            let date = new Date();
            let output = String(date.getDate()).padStart(2, "0") + "/" + String(date.getMonth() + 1).padStart(2, "0") + "/" + date.getFullYear();
            /* doc.text(245, 22, reverse(student_hebrew_name)); */
            doc.setFont("VarelaRound-Regular"); // set font
            doc.setFontSize(9);
            doc.text(235, 28, grade + " | ");
            doc.text(270, 28, school_cycle);
            /* doc.text(4, 200, grade + " | ");
                    doc.text(37, 200, school_cycle); */
            /* TABLA ESPAÑOL */
            doc.autoTable({
                theme: "plain",
                startY: 55,
                margin: {
                    left: 8,
                },
                headStyles: {
                    halign: "left",
                    valign: "middle",
                    font: "VarelaRound-Regular",
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                    fontSize: 10,
                },
                bodyStyles: {
                    font: "VarelaRound-Regular",
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                },
                columnStyles: {
                    0: {
                        cellWidth: 130,
                    },
                    2: {
                        cellWidth: 130,
                    },
                },
                body: [
                    [{
                        content: "Nombre del alumno: " + student_name.toUpperCase(),
                        styles: {
                            halign: "left"
                        },
                    }, {
                        content: "Código del alumno: " + student_code,
                        styles: {
                            halign: "right"
                        },
                    }, ],
                    [{
                        content: "Grado y grupo: " + level_degree.toUpperCase() + " | GRUPO " + letter,
                        styles: {
                            halign: "left"
                        },
                    }, {
                        content: "Especialidad: " + speciality,
                        styles: {
                            halign: "right"
                        },
                    }, ],
                    [{
                        content: "Ciclo escolar: " + school_cycle,
                        styles: {
                            halign: "left"
                        },
                    }, {
                        content: "Módulo: 1",
                        styles: {
                            halign: "right"
                        },
                    }, ],
                ],
            });
            doc.autoTable({
                startY: 80,
                tableWidth: 190,
                margin: {
                    left: 8,
                },
                headStyles: {
                    halign: "left",
                    valign: "middle",
                    font: "VarelaRound-Regular",
                    fillColor: [34, 73, 135],
                    textColor: [255, 255, 255],
                    lineColor: [34, 73, 135],
                    fontSize: 8,
                },
                bodyStyles: {
                    fontSize: 9,
                    halign: "left",
                    valign: "middle",
                    lineColor: [255, 255, 255],
                    font: "VarelaRound-Regular",
                },
                footStyles: {
                    fontSize: 9,
                    halign: "left",
                    valign: "middle",
                    fillColor: [150, 178, 255],
                    textColor: [0, 0, 0],
                    lineColor: [150, 178, 255],
                    font: "VarelaRound-Regular",
                },
                columnStyles: {
                    2: {
                        cellWidth: 20,
                    },
                    3: {
                        cellWidth: 20,
                    },
                    4: {
                        cellWidth: 20,
                    },
                },
                head: [
                    [{
                        content: "Materia",
                        styles: {
                            minCellHeight: 10,
                        },
                    }, {
                        content: "Tipo de materia",
                    }, {
                        content: "Calificación M1",
                    }, ],
                    //--- --- ---//
                ],
                body: data_school_assignments,
                foot: data_school_averages_span,
            });
            /*  {
               content: "Materia",
               styles: {
                 minCellHeight: 10,
               },
             },
             {
               content: "Tipo de materia",
             },
             {
               content: "N° de sesiones",
             },
             {
               content: "Inastencias",
             },
             {
               content: "Calificación M1",
             }, */
            /* MATERIAS PENDIENTES ESPAÑOL */
            doc.setFontSize(11);
            doc.text(100, 160, "Observaciones:");
            doc.setFontSize(7);
            doc.setTextColor(255, 0, 0);
            doc.text(60, 199, "");
            doc.setTextColor(0, 0, 0);
            doc.autoTable({
                startY: 164,
                tableWidth: 100,
                margin: {
                    left: 100,
                },
                headStyles: {
                    halign: "left",
                    valign: "middle",
                    font: "VarelaRound-Regular",
                    fillColor: [255, 216, 171],
                    textColor: [0, 0, 0],
                    lineColor: [255, 216, 171],
                    fontSize: 9,
                },
                bodyStyles: {
                    fontSize: 8,
                    halign: "left",
                    valign: "middle",
                    lineColor: [255, 255, 255],
                    font: "VarelaRound-Regular",
                },
                footStyles: {
                    fontSize: 8,
                    halign: "left",
                    valign: "middle",
                    fillColor: [156, 171, 173],
                    textColor: [0, 0, 0],
                    lineColor: [156, 171, 173],
                    font: "VarelaRound-Regular",
                },
                head: [
                    [{
                        content: "",
                        styles: {
                            minCellHeight: 25,
                        },
                    }, ],
                    //--- --- ---//
                ],
            });
            doc.setFontSize(11);
            doc.setLineWidth(0.5);
            doc.setDrawColor(0, 0, 0);
            doc.line(55, 220, 150, 220); // horizontal line
            doc.text(76, 225, "LIC. IRENE ESSES MOCHON");
            doc.text(79, 230, "DIRECTORA DE ESPAÑOL");
            doc.line(45, 245, 170, 245); // horizontal line
            doc.text(60, 250, "NOMBRE Y FIRMA DEL PADRE, MADRE O TUTOR");
            doc.autoTable({
                theme: "plain",
                startY: 260,
                tableWidth: 190,
                margin: {
                    left: 8,
                },
                headStyles: {
                    halign: "left",
                    valign: "middle",
                    font: "VarelaRound-Regular",
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                    fontSize: 10,
                },
                bodyStyles: {
                    font: "VarelaRound-Regular",
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                    fontSize: 8,
                },
                columnStyles: {
                    0: {
                        cellWidth: 190,
                    },
                },
                body: [
                    [{
                        content: "*Para pasar a 5° de preparatoria no deberás acumular más de 4 materias reprobadas pues de hacerlo tendrás que repetir año. \n Recuerda que de reprobar Español también repruebas Hebreo.",
                        styles: {
                            halign: "center"
                        },
                    }, ],
                ],
            });
            //doc.addImage(getLafontineLogoBase64(), "png", 13, 13, 27, 27);
            doc.addImage(imgLogo, "jpg", 13, 13, 27, 27);
            doc.addImage(getLafontineLogo2Base64(), "png", 165, 13, 27, 27);
            doc.setFontSize(7);
            doc.text(90, 290, "Código de alumno: " + student_code + "  |  Grupos iTeach: " + group_name + "  |  Fecha de emisión: " + output);
            doc.text(145, 293, speciality);
            doc.setLineWidth(1.5);
            doc.save(student_code + ".pdf");
            await timer(2000);
        }
    }
}