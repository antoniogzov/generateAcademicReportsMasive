<?php

class DataSchoolReportCardsLafontine extends Connection
{

    private $conn;
    public function __construct()
    {
        $this->conn = $this->db_conn();
    }

    public function getSpecialityGroup($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT groups.*
        	FROM school_control_ykt.inscriptions AS insc
            INNER JOIN school_control_ykt.groups AS groups ON insc.id_group = groups.id_group
        	WHERE insc.id_student = '$id_student'  AND groups.group_type_id = 2");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getListStudentsByIDgroup($group_id)
    {

        $results = array();

        $query = $this->conn->query(" SELECT student.id_student, student.student_code, student.hebrew_name, CONCAT(student.lastname , ' ', student.name) AS student_name
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            WHERE inscription.id_group = '$group_id' AND student.status = '1'
            ORDER BY student.lastname LIMIT 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentByGroup($id_group, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query(
            "SELECT assgn.*, sbj.*, colb.nombre_hebreo AS hebrew_name_teacher,  CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
            FROM school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            WHERE assgn.id_group = '$id_group' AND sbj.id_subject !=323 AND sbj.id_academic_area = '$id_academic_area' AND assgn.print_school_report_card = 1 AND sbj.id_subject != 416 AND sbj.id_subject != 417 AND sbj.id_subject != 418  ORDER BY sbj.name_subject ASC"
        );

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentByGroupLanguage($id_group, $id_academic_area, $order_by)
    {
        $results = array();

        $query = $this->conn->query(
            "SELECT assgn.*, sbj.*,
              CASE 
        WHEN sbj.hebrew_name IS NULL THEN ' '
        ELSE sbj.hebrew_name
        END
        AS 'sbj_hebrew_name',
             colb.nombre_hebreo AS hebrew_name_teacher,  CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
            FROM school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            WHERE assgn.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic_area' AND assgn.print_school_report_card = 1 AND sbj.subject_type_id = '3' AND sbj.id_subject != 416
            $order_by"
        );

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentByGroupConductual($id_group, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query(
            "SELECT assgn.*, sbj.*, colb.nombre_hebreo AS hebrew_name_teacher,  CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
        	FROM school_control_ykt.assignments AS assgn
        	INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
        	INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject AND sbj.subject_type_id = 2
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            LEFT JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS asscassglmp ON assgn.id_assignment = asscassglmp.id_assignment
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
        	WHERE assgn.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic_area' AND assgn.print_school_report_card = 1 ORDER BY sbj.name_subject ASC"
        );

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentByGroupGeneral($id_group, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query(
            "SELECT assgn.*, sbj.*, colb.nombre_hebreo AS hebrew_name_teacher,  CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
        	FROM school_control_ykt.assignments AS assgn
        	INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
        	INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject AND sbj.subject_type_id = 1
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            LEFT JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS asscassglmp ON assgn.id_assignment = asscassglmp.id_assignment
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
        	WHERE assgn.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic_area' AND assgn.print_school_report_card = 1 ORDER BY sbj.name_subject ASC"
        );

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsByStudent($id_group, $id_academic_area, $id_student)
    {
        $results = array();
        $sql = "SELECT assgn.id_assignment,
                        asscassglmp.id_final_grade,
                        sbj.name_subject,
                        sbj.hebrew_name,
                         CASE 
                         WHEN grape.grade_period IS NULL THEN '-' 
                         END
                         AS 'calificacion',
                        grape.no_period,
                        colb.nombre_hebreo AS hebrew_name_teacher,
                        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
                        sbj_tp.subject_type
                FROM school_control_ykt.assignments AS assgn
                    INNER JOIN school_control_ykt.inscriptions AS insc
                    INNER JOIN school_control_ykt.groups AS groups
                        ON groups.id_group = insc.id_group
                    INNER JOIN school_control_ykt.subjects AS sbj
                        ON assgn.id_subject = sbj.id_subject AND sbj.subject_type_id = 1
                    INNER JOIN school_control_ykt.subjects_types AS sbj_tp
                        ON sbj.subject_type_id = sbj_tp.subject_type_id
                    LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
                        ON assgn.id_assignment = asscassglmp.id_assignment
                    LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
                        ON grape.id_final_grade = asscassglmp.id_final_grade
                    INNER JOIN colaboradores_ykt.colaboradores AS colb
                        ON assgn.no_teacher = colb.no_colaborador
                WHERE assgn.id_group = $id_group
                    AND sbj.id_academic_area = $id_academic_area
                    AND assgn.print_school_report_card = 1
                    AND insc.id_student = $id_student
                    AND sbj.id_subject !=323
                ORDER BY no_period";

        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsByStudentPeriodMiddle($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar, $order_by_gral)
    {
        $results = array();
        $sql = "SELECT 
        assgn.id_assignment,
        asscassglmp.id_final_grade,
        percal.no_period,
        CASE 
        WHEN quesslm.evaluation  IS NULL THEN '-' 
        ELSE quesslm.evaluation 
        END
        AS commit_mda,
        CASE 
        WHEN fincom.comments1 IS NULL THEN '-'
        ELSE fincom.comments1
        END
        AS 'comentarios_finales',
        sbj.name_subject,
        CASE 
        WHEN asscassglmp.final_grade IS NULL THEN '-'
        ELSE asscassglmp.final_grade
        END
        AS 'promedio_final',
        CASE 
        WHEN extrexam.grade_extraordinary_examen  IS NULL THEN '-' 
        ELSE extrexam.grade_extraordinary_examen 
        END
       AS 'grade_extraordinary_examen',
       
       CASE 
        WHEN grape.grade_period IS NULL THEN '-'
        ELSE grape.grade_period
        END
        AS 'calificacion',
        (SELECT grec.grade_evaluation_criteria_teacher AS ausencias
        FROM iteach_grades_quantitatives.grades_evaluation_criteria AS grec
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evpl ON grec.id_evaluation_plan = evpl.id_evaluation_plan
        WHERE `id_final_grade` = asscassglmp.id_final_grade AND evpl.id_evaluation_source = 5 AND id_grade_period = grape.id_grade_period limit 1)
		AS ausencias,
        percal.no_period,
        grape.id_grade_period,
        CASE 
        WHEN sbj.hebrew_name IS NULL THEN ''
        ELSE sbj.hebrew_name
        END
        AS 'hebrew_name',
        colb.nombre_hebreo AS hebrew_name_teacher,
        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
        CONCAT(mejan.nombre_corto) AS mejanejet_name_teacher,
        sbj_tp.subject_type
         FROM school_control_ykt.assignments AS assgn
        INNER JOIN school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS groups
        ON groups.id_group = insc.id_group
        INNER JOIN school_control_ykt.subjects AS sbj
        ON assgn.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.subjects_types AS sbj_tp
        ON sbj.subject_type_id = sbj_tp.subject_type_id
        INNER JOIN colaboradores_ykt.colaboradores AS colb
        ON assgn.no_teacher = colb.no_colaborador
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal
        ON percal.id_period_calendar = $id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extrexam ON extrexam.id_final_grade = asscassglmp.id_final_grade AND extrexam.id_grade_period = grape.id_grade_period 
        INNER JOIN iteach_grades_qualitatives.learning_maps AS lm
        INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc ON lm.id_learning_map = assc.id_learning_map 
        LEFT JOIN iteach_grades_qualitatives.final_comments AS fincom ON fincom.ascc_lm_assgn = assc.ascc_lm_assgn and fincom.id_student = $id_student
        LEFT JOIN iteach_grades_qualitatives.learning_maps_log  AS learlog ON learlog.ascc_lm_assgn=assc.ascc_lm_assgn AND learlog.id_student = $id_student AND learlog.no_installment = $no_period
        LEFT JOIN iteach_grades_qualitatives.questions_log_learning_maps AS quesslm ON quesslm.id_historical_learning_maps = learlog.id_historical_learning_maps
        LEFT JOIN colaboradores_ykt.colaboradores AS mejan
        ON fincom.no_teacher_fill = mejan.no_colaborador
        WHERE assc.id_assignment = assgn.id_assignment AND  learning_map_types_id = 3
         AND assgn.id_group = $id_group

        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area 
        AND sbj.id_subject != 416
        AND sbj.name_subject != 'HEBREO'  AND sbj.subject_type_id != '3' AND sbj.id_subject != 416 AND sbj.id_subject != 417 AND sbj.id_subject != 418  $order_by_gral";

        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsByStudentPeriodHighSchool($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar, $order_by_gral)
    {
        $results = array();
        $sql = "SELECT DISTINCT
        assgn.id_assignment,
        sbj.id_subject,
        asscassglmp.id_final_grade,
        percal.no_period,
        sbj.name_subject,
        CASE
        WHEN ext_exam.`grade_extraordinary_examen` IS NULL THEN '-'
        ELSE ext_exam.`grade_extraordinary_examen`
        END
        AS 'extraordinary',
        
       CASE
        WHEN grape.grade_period IS NULL THEN '-'
        ELSE grape.grade_period
        END
        AS 'calificacion',
        percal.no_period,
        grape.id_grade_period,
        CASE 
        WHEN sbj.hebrew_name IS NULL THEN ''
        ELSE sbj.hebrew_name
        END
        AS 'hebrew_name',
        colb.nombre_hebreo AS hebrew_name_teacher,
        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
        sbj_tp.subject_type
         FROM school_control_ykt.assignments AS assgn
        INNER JOIN school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS groups
        ON groups.id_group = insc.id_group
        INNER JOIN school_control_ykt.subjects AS sbj
        ON assgn.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.subjects_types AS sbj_tp
        ON sbj.subject_type_id = sbj_tp.subject_type_id
        INNER JOIN colaboradores_ykt.colaboradores AS colb
        ON assgn.no_teacher = colb.no_colaborador
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal
        ON percal.id_period_calendar = $id_period_calendar
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student AND insc.id_inscription = asscassglmp.id_inscription
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS ext_exam ON ext_exam.`id_grade_period` = grape.`id_grade_period`
         WHERE assgn.id_group = $id_group
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area
        AND sbj.subject_type_id != 4
         AND sbj.id_subject != 30
        AND sbj.id_subject != 309
        AND sbj.id_subject != 324
        AND sbj.id_subject != 431
        AND sbj.id_subject != 417
        AND sbj.id_subject != 27
        AND sbj.id_subject != 341
        AND sbj.id_subject != 345
        AND sbj.id_subject != 346
        AND sbj.id_subject != 347
        AND sbj.id_subject != 418
        AND assgn.print_school_report_card != 0";
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsByStudentPeriodHighSchoolSpan($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar, $order_by_gral)
    {
        $results = array();
        $sql = "SELECT DISTINCT
        assgn.id_assignment,
        sbj.id_subject,
        percal.no_period,
        sbj.name_subject,
        CASE
        WHEN ext_exam.`grade_extraordinary_examen` IS NULL THEN '-'
        ELSE ext_exam.`grade_extraordinary_examen`
        END
        AS 'extraordinary',
        groups.group_type_id,
       CASE
        WHEN grape.grade_period IS NULL THEN '-'
        ELSE grape.grade_period
        END
        AS 'calificacion',
        percal.no_period,
        CASE 
        WHEN sbj.hebrew_name IS NULL THEN ''
        ELSE sbj.hebrew_name
        END
        AS 'hebrew_name',
        (SELECT COUNT(*) FROM attendance_records.attendance_index AS ati
                INNER JOIN attendance_records.attendance_record AS atr ON ati.id_attendance_index = atr.id_attendance_index AND atr.id_student = $id_student
                 WHERE ati.id_assignment = assgn.id_assignment AND DATE(ati.apply_date) BETWEEN percal.start_date AND percal.end_date
                AND ati.obligatory = 1 AND ati.valid_assistance = 1
                 ) AS 'pases_lista',
        (SELECT COUNT(*) FROM attendance_records.attendance_index AS ati
        INNER JOIN attendance_records.attendance_record AS atr ON ati.id_attendance_index = atr.id_attendance_index AND atr.id_student = $id_student
        INNER JOIN attendance_records.incidents_attendance as iat ON atr.incident_id = iat.incident_id
        WHERE ati.id_assignment = assgn.id_assignment AND DATE(ati.apply_date) BETWEEN percal.start_date AND percal.end_date
        AND ati.obligatory = 1 AND ati.valid_assistance = 1 AND atr.attend = 0 
        ) AS 'faltas',
        colb.nombre_hebreo AS hebrew_name_teacher,
        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
        sbj_tp.subject_type
         FROM school_control_ykt.assignments AS assgn
        INNER JOIN school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS groups
        ON groups.id_group = insc.id_group
        INNER JOIN school_control_ykt.subjects AS sbj
        ON assgn.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.subjects_types AS sbj_tp
        ON sbj.subject_type_id = sbj_tp.subject_type_id
        INNER JOIN colaboradores_ykt.colaboradores AS colb
        ON assgn.no_teacher = colb.no_colaborador
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal
        ON percal.id_period_calendar = $id_period_calendar
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student AND asscassglmp.id_inscription = insc.id_inscription
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS ext_exam ON ext_exam.`id_grade_period` = grape.`id_grade_period`
        WHERE assgn.id_group = insc.id_group
         AND assgn.print_school_report_card = 1
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area
        AND sbj.id_subject != 417
        AND sbj.id_subject != 418
        AND (assgn.show_list_teacher = 0 OR assgn.show_list_teacher = 1)
        AND assgn.print_school_report_card != 0
        ORDER BY group_type_id, sbj.name_subject ASC";


        $query = $this->conn->query($sql);
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getExtraFinalQualificationByStudentHighSchool($id_student)
    {
        $results = array();
        $sql = "SELECT sbj.name_subject, gec.id_evaluation_plan, evs.evaluation_name, evs.hebrew_name, gec.grade_evaluation_criteria_teacher 
        FROM school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = stud.id_student
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group=insc.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.subject_type_id = 4
        INNER JOIN school_control_ykt.assignments AS asg ON gps.id_group = asg.id_group AND asg.id_subject = sbj.id_subject
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON asg.id_assignment = fga.id_assignment AND fga.id_student = stud.id_student
        LEFT JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON gec.id_final_grade  = fga.id_final_grade
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON gec.id_evaluation_plan = evp.id_evaluation_plan
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS evs ON evp.id_evaluation_source = evs.id_evaluation_source
        WHERE stud.id_student = $id_student
        ";


        //echo $sql2;
        $query = $this->conn->query($sql);

        /*  while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        } */
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        return $results;
    }
    public function getAditionalExams($id_student, $id_group)
    {
        $additional_exams = array();
        //--- OBTENEMOS LA CALIFICACIÓN DE LOS EXAMENES EXTRAS ---//
        $query = $this->conn->query("SELECT assgn.id_assignment, sbj.name_subject, sbj.hebrew_name
            FROM school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.inscriptions AS insc ON assgn.id_group = insc.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            WHERE insc.id_student = $id_student AND assgn.id_group = $id_group AND sbj.subject_type_id = 4 AND assgn.assignment_active = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $id_assignment = $row->id_assignment;
            //--- --- ---//
            $query3 = $this->conn->query("SELECT evp.id_evaluation_plan, gec.id_grades_evaluation_criteria, gec.grade_evaluation_criteria_teacher,
                CASE
                WHEN evp.id_evaluation_source != 1 THEN evs.hebrew_name
                WHEN evp.id_evaluation_source = 1 AND (evp.manual_name = '' OR evp.manual_name = NULL) THEN 'SIN INFORMACIÓN'
                ELSE evp.manual_name
                END AS name_exam
                FROM school_control_ykt.assignments AS assgn
                INNER JOIN school_control_ykt.inscriptions AS insc ON assgn.id_group = insc.id_group
                INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON assgn.id_assignment = evp.id_assignment
                INNER JOIN iteach_grades_quantitatives.evaluation_source AS evs ON evp.id_evaluation_source = evs.id_evaluation_source
                INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON evp.id_evaluation_plan = gec.id_evaluation_plan
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON gec.id_final_grade = fga.id_final_grade AND insc.id_inscription = fga.id_inscription
                WHERE insc.id_student = $id_student AND assgn.id_assignment = $id_assignment AND gec.grade_evaluation_criteria_teacher != ''
                ");

            while ($row3 = $query3->fetch(PDO::FETCH_OBJ)) {
                //--- --- ---//
                $row->criterias[] = $row3;
                //--- --- ---//
            }
            //--- --- ---//
            $additional_exams[] = $row;
            //--- --- ---//
        }

        return $additional_exams;
    }

    public function getQualificationsByStudentPeriodHighSchoolSpanSpeciality($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar, $order_by_gral)
    {
        $results = array();
        $sql = "SELECT DISTINCT
        assgn.id_assignment,
        sbj.id_subject,
        asscassglmp.id_final_grade,
        percal.no_period,
        sbj.name_subject,
        
       CASE
        WHEN grape.grade_period IS NULL THEN '-'
        ELSE grape.grade_period
        END
        AS 'calificacion',
        percal.no_period,
        grape.id_grade_period,
        CASE 
        WHEN sbj.hebrew_name IS NULL THEN ''
        ELSE sbj.hebrew_name
        END
        AS 'hebrew_name',
        colb.nombre_hebreo AS hebrew_name_teacher,
        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
        sbj_tp.subject_type
        FROM school_control_ykt.groups AS groups
          INNER JOIN school_control_ykt.assignments AS assgn
          ON assgn.id_group = groups.id_group
        INNER JOIN school_control_ykt.inscriptions AS insc
        
        INNER JOIN school_control_ykt.subjects AS sbj
        ON assgn.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.subjects_types AS sbj_tp
        ON sbj.subject_type_id = sbj_tp.subject_type_id
        INNER JOIN colaboradores_ykt.colaboradores AS colb
        ON assgn.no_teacher = colb.no_colaborador
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal
        ON percal.id_period_calendar = 69
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_inscription = insc.id_inscription
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
         WHERE insc.id_group != 82
        AND insc.id_student = 1424
        AND sbj.id_academic_area = 1
         AND sbj.id_subject != 30
        AND sbj.id_subject != 309
        AND sbj.id_subject != 324
        AND sbj.id_subject != 431
        AND sbj.id_subject != 417
        AND sbj.id_subject != 27
        AND sbj.id_subject != 341
        AND sbj.id_subject != 345
        AND sbj.id_subject != 346
        AND sbj.id_subject != 347
        AND sbj.id_subject != 418
        AND asscassglmp.id_final_grade != 23577
        AND sbj.name_subject LIKE 'M3:%'
        ORDER BY sbj.name_subject
        ";
        echo $sql;
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsByStudentPeriodPrimary($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar, $order_by_gral)
    {
        $results = array();
        $sql = "SELECT 
        assgn.id_assignment,
        asscassglmp.id_final_grade,
        percal.no_period,
        sbj.name_subject,
        CASE 
        WHEN asscassglmp.final_grade IS NULL THEN '-'
        ELSE asscassglmp.final_grade
        END
        AS 'promedio_final',
       CASE 
        WHEN grape.grade_period IS NULL THEN '-'
        ELSE grape.grade_period
        END
        AS 'calificacion',
        percal.no_period,
        grape.id_grade_period,
        CASE 
        WHEN sbj.hebrew_name IS NULL THEN ''
        ELSE sbj.hebrew_name
        END
        AS 'hebrew_name',
        colb.nombre_hebreo AS hebrew_name_teacher,
        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher
         FROM school_control_ykt.assignments AS assgn
        INNER JOIN school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS groups
        ON groups.id_group = insc.id_group
        INNER JOIN school_control_ykt.subjects AS sbj
        ON assgn.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.subjects_types AS sbj_tp
        ON sbj.subject_type_id = sbj_tp.subject_type_id
        INNER JOIN colaboradores_ykt.colaboradores AS colb
        ON assgn.no_teacher = colb.no_colaborador
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal
        ON percal.id_period_calendar = $id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        WHERE asscassglmp.id_assignment = assgn.id_assignment
         AND assgn.id_group = $id_group
        AND sbj.subject_type_id !=3
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area 
        AND sbj.id_subject != 416
        AND sbj.id_subject !=323
            $order_by_gral";
        //echo $sql;
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsByStudentPeriod($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar, $order_by_gral)
    {
        $results = array();
        $sql = "SELECT 
        assgn.id_assignment,
        asscassglmp.id_final_grade,
        percal.no_period,
        CASE 
        WHEN quesslm.evaluation  IS NULL THEN '-' 
        ELSE quesslm.evaluation 
        END
        AS commit_mda,
        CASE 
        WHEN fincom.comments1 IS NULL THEN '-'
        ELSE fincom.comments1
        END
        AS 'comentarios_finales',
        sbj.name_subject,
        CASE 
        WHEN asscassglmp.final_grade IS NULL THEN '-'
        ELSE asscassglmp.final_grade
        END
        AS 'promedio_final',
        CASE 
        WHEN extrexam.grade_extraordinary_examen  IS NULL THEN '-' 
        ELSE extrexam.grade_extraordinary_examen 
        END
       AS 'grade_extraordinary_examen',
       
       CASE 
        WHEN grape.grade_period IS NULL THEN '-'
        ELSE grape.grade_period
        END
        AS 'calificacion',
        CASE 
		WHEN (SELECT grec.grade_evaluation_criteria_teacher 
        AS ausencias  
        FROM iteach_grades_quantitatives.grades_evaluation_criteria AS grec
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evpl ON grec.id_evaluation_plan = evpl.id_evaluation_plan
        WHERE `id_final_grade` = asscassglmp.id_final_grade AND evpl.id_evaluation_source = 5 AND id_grade_period = grape.id_grade_period) IS NULL THEN '-'
        		ELSE (SELECT grec.grade_evaluation_criteria_teacher 
        AS ausencias  
        FROM iteach_grades_quantitatives.grades_evaluation_criteria AS grec
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evpl ON grec.id_evaluation_plan = evpl.id_evaluation_plan
        WHERE `id_final_grade` = asscassglmp.id_final_grade AND evpl.id_evaluation_source = 5 AND id_grade_period = grape.id_grade_period)
		END
		AS ausencias,
        percal.no_period,
        grape.id_grade_period,
        CASE 
        WHEN sbj.hebrew_name IS NULL THEN ''
        ELSE sbj.hebrew_name
        END
        AS 'hebrew_name',
        colb.nombre_hebreo AS hebrew_name_teacher,
        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
        CONCAT(mejan.nombre_corto) AS mejanejet_name_teacher,
        sbj_tp.subject_type
         FROM school_control_ykt.assignments AS assgn
        INNER JOIN school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS groups
        ON groups.id_group = insc.id_group
        INNER JOIN school_control_ykt.subjects AS sbj
        ON assgn.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.subjects_types AS sbj_tp
        ON sbj.subject_type_id = sbj_tp.subject_type_id
        INNER JOIN colaboradores_ykt.colaboradores AS colb
        ON assgn.no_teacher = colb.no_colaborador
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal
        ON percal.id_period_calendar = $id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extrexam ON extrexam.id_final_grade = asscassglmp.id_final_grade AND extrexam.id_grade_period = grape.id_grade_period 
        INNER JOIN iteach_grades_qualitatives.learning_maps AS lm
        INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc ON lm.id_learning_map = assc.id_learning_map 
        LEFT JOIN iteach_grades_qualitatives.final_comments AS fincom ON fincom.ascc_lm_assgn = assc.ascc_lm_assgn and fincom.id_student = $id_student
        LEFT JOIN iteach_grades_qualitatives.learning_maps_log  AS learlog ON learlog.ascc_lm_assgn=assc.ascc_lm_assgn AND learlog.id_student = $id_student AND learlog.no_installment = $no_period
        LEFT JOIN iteach_grades_qualitatives.questions_log_learning_maps AS quesslm ON quesslm.id_historical_learning_maps = learlog.id_historical_learning_maps
        LEFT JOIN colaboradores_ykt.colaboradores AS mejan
        ON fincom.no_teacher_fill = mejan.no_colaborador
        WHERE assc.id_assignment = assgn.id_assignment AND  learning_map_types_id = 3
         AND assgn.id_group = $id_group

        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area 
        AND sbj.id_subject != 416
        AND sbj.id_subject !=323
        AND sbj.name_subject != 'HEBREO' sbj.name_subject != 'SIJOT' AND sbj.subject_type_id != '3' AND sbj.id_subject != 416 AND sbj.id_subject != 417 AND sbj.id_subject != 418  $order_by_gral";

        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsMejanejet($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar)
    {
        $results = array();
        $sql = "SELECT 
        
        CASE 
        WHEN fincom.comments1 IS NULL THEN '-'
        ELSE fincom.comments1
        END
        AS 'comentarios_finales',
       
        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
        CONCAT(mejan.nombre_corto) AS mejanejet_name_teacher,
        sbj_tp.subject_type
         FROM school_control_ykt.assignments AS assgn
        INNER JOIN school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS groups
        ON groups.id_group = insc.id_group
        INNER JOIN school_control_ykt.subjects AS sbj
        ON assgn.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.subjects_types AS sbj_tp
        ON sbj.subject_type_id = sbj_tp.subject_type_id
        INNER JOIN colaboradores_ykt.colaboradores AS colb
        ON assgn.no_teacher = colb.no_colaborador
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal
        ON percal.id_period_calendar = $id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extrexam ON extrexam.id_final_grade = asscassglmp.id_final_grade AND extrexam.id_grade_period = grape.id_grade_period 
        INNER JOIN iteach_grades_qualitatives.learning_maps AS lm
        INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc ON lm.id_learning_map = assc.id_learning_map 
        LEFT JOIN iteach_grades_qualitatives.final_comments AS fincom ON fincom.ascc_lm_assgn = assc.ascc_lm_assgn and fincom.id_student = $id_student
        LEFT JOIN iteach_grades_qualitatives.learning_maps_log  AS learlog ON learlog.ascc_lm_assgn=assc.ascc_lm_assgn AND learlog.id_student = $id_student AND learlog.no_installment = $no_period
        LEFT JOIN iteach_grades_qualitatives.questions_log_learning_maps AS quesslm ON quesslm.id_historical_learning_maps = learlog.id_historical_learning_maps
        LEFT JOIN colaboradores_ykt.colaboradores AS mejan
        ON fincom.no_teacher_fill = mejan.no_colaborador
        WHERE assc.id_assignment = assgn.id_assignment AND  learning_map_types_id = 3
         AND assgn.id_group = $id_group

        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area 
        AND sbj.id_subject =309";
        //echo $sql;

        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsLangByStudentPeriod($id_group, $id_academic_area, $id_student, $id_period_calendar, $order_by)
    {
        $results = array();
        $sql = "SELECT 
        assgn.id_assignment,
        asscassglmp.id_final_grade,
        percal.no_period,
        sbj.name_subject,
        CASE 
        WHEN sbj.hebrew_name IS NULL THEN ' '
        ELSE sbj.hebrew_name
        END
        AS 'hebrew_name',
        CASE 
        WHEN asscassglmp.final_grade IS NULL THEN '-'
        ELSE asscassglmp.final_grade
        END
        AS 'promedio_final',
        CASE 
        WHEN grape.grade_period IS NULL THEN '-'
        ELSE grape.grade_period
        END
        AS 'calificacion',
        percal.no_period,
        id_grade_period,
        colb.nombre_hebreo AS hebrew_name_teacher,
        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
        sbj_tp.subject_type
         FROM school_control_ykt.assignments AS assgn
        INNER JOIN school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS groups
        ON groups.id_group = insc.id_group
        INNER JOIN school_control_ykt.subjects AS sbj
        ON assgn.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.subjects_types AS sbj_tp
        ON sbj.subject_type_id = sbj_tp.subject_type_id
        INNER JOIN colaboradores_ykt.colaboradores AS colb
        ON assgn.no_teacher = colb.no_colaborador
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal
        ON percal.id_period_calendar = $id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
         WHERE assgn.id_group = $id_group
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area AND sbj.subject_type_id = '3' AND sbj.id_subject != 416
        $order_by
        ";

        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsCondByStudentPeriodPrimary($id_group, $id_academic_area, $id_student, $id_period_calendar, $order_by_cond_heb)
    {
        $results = array();
        $sql = "SELECT DISTINCT  sbj.name_subject,
        CASE 
        WHEN esou.evaluation_name = 'Asignación libre' THEN ep.manual_name
        ELSE esou.evaluation_name
        END AS evaluation_name,
        ep.manual_name, 
        ep.id_period_calendar,
        fga.id_final_grade,
        CASE 
        WHEN gec.grade_evaluation_criteria_teacher  IS NULL THEN '-'
        ELSE gec.grade_evaluation_criteria_teacher
        END 
        AS 'grade_evaluation_criteria_teacher',
          CASE 
        WHEN esou.hebrew_name  IS NULL THEN ' '
        ELSE esou.hebrew_name
        END
        AS 'eval_hebrew_name'
        FROM school_control_ykt.subjects AS sbj
        INNER JOIN school_control_ykt.assignments AS asg ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.students AS stud ON stud.id_student = '$id_student'
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON  ep.id_assignment = asg.id_assignment
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou ON esou.id_evaluation_source = ep.id_evaluation_source 
       INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON ep.id_evaluation_plan = gec.id_evaluation_plan
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga oN gec.id_final_grade = fga.id_final_grade AND asg.id_assignment = fga.id_assignment 
        WHERE ep.id_period_calendar = '$id_period_calendar' 
        AND sbj.id_subject = 416
        AND ep.manual_name != 'Baile'
        AND ep.manual_name != 'Canto'
        AND fga.id_student = '$id_student'
        $order_by_cond_heb
        ";
        //echo $sql;
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {

            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsCondByStudentPeriod($id_group, $id_academic_area, $id_student, $id_period_calendar, $order_by_cond_heb)
    {
        $results = array();
        $sql = "SELECT DISTINCT  sbj.name_subject,
        CASE 
        WHEN esou.evaluation_name = 'Asignación libre' THEN ep.manual_name
        ELSE esou.evaluation_name
        END AS evaluation_name,
        ep.manual_name, 
        ep.id_period_calendar,
        fga.id_final_grade,
        CASE 
        WHEN gec.grade_evaluation_criteria_teacher  IS NULL THEN '-'
        ELSE gec.grade_evaluation_criteria_teacher
        END 
        AS 'grade_evaluation_criteria_teacher',
          CASE 
        WHEN esou.hebrew_name  IS NULL THEN ' '
        ELSE esou.hebrew_name
        END
        AS 'eval_hebrew_name'
        FROM school_control_ykt.subjects AS sbj
        INNER JOIN school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.assignments AS asg ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = stud.id_student
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON  ep.id_assignment = asg.id_assignment
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou ON esou.id_evaluation_source = ep.id_evaluation_source 
        LEFT JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON ep.id_evaluation_plan = gec.id_evaluation_plan
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS fga oN gec.id_final_grade = fga.id_final_grade AND asg.id_assignment = fga.id_assignment AND insc.id_inscription = fga.id_inscription
        WHERE ep.id_period_calendar = '$id_period_calendar'
        AND sbj.id_subject = 418
        AND ep.manual_name != 'Baile'
        AND ep.manual_name != 'Canto'
        AND stud.id_student= '$id_student'
        AND fga.id_student = '$id_student'
        $order_by_cond_heb
        ";
        //echo $sql;
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {

            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationsCondByStudentPeriodSpan($id_group, $id_academic_area, $id_student, $id_period_calendar, $order_by_cond_span)
    {
        $results = array();
        $sql = "SELECT DISTINCT  sbj.name_subject,
        CASE 
        WHEN esou.evaluation_name = 'Asignación libre' THEN ep.manual_name
        ELSE esou.evaluation_name
        END AS evaluation_name,
        ep.manual_name, 
        ep.id_period_calendar,
        fga.id_final_grade,
        CASE 
        WHEN gec.grade_evaluation_criteria_teacher  IS NULL THEN '-'
        ELSE gec.grade_evaluation_criteria_teacher
        END 
        AS 'grade_evaluation_criteria_teacher',
          CASE 
        WHEN esou.hebrew_name  IS NULL THEN ' '
        ELSE esou.hebrew_name
        END
        AS 'eval_hebrew_name'
        FROM school_control_ykt.subjects AS sbj
        INNER JOIN school_control_ykt.assignments AS asg ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.students AS stud ON stud.id_student = '$id_student'
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON  ep.id_assignment = asg.id_assignment
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou ON esou.id_evaluation_source = ep.id_evaluation_source 
        LEFT JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON ep.id_evaluation_plan = gec.id_evaluation_plan
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS fga oN gec.id_final_grade = fga.id_final_grade AND asg.id_assignment = fga.id_assignment AND fga.id_student = '$id_student'
        WHERE sbj.id_subject = 417
        AND ep.manual_name != 'Baile'
        AND ep.manual_name != 'Canto'
        AND ep.id_period_calendar = '$id_period_calendar'
        AND stud.id_student= '$id_student'
        AND fga.id_student = '$id_student'
        $order_by_cond_span ";
        //echo $sql;
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {

            $results[] = $row;
        }

        return $results;
    }
    public function getAveragesStudent($id_assignment)
    {
        $results = array();

        $query = $this->conn->query("
                    SELECT ins.id_inscription, student.id_student, student.student_code
                    FROM school_control_ykt.assignments AS assignment
                    INNER JOIN school_control_ykt.inscriptions AS ins ON assignment.id_group = ins.id_group
                    INNER JOIN school_control_ykt.students AS student ON ins.id_student = student.id_student
                    WHERE assignment.id_assignment = '$id_assignment'
                    ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $id_inscription = $row->id_inscription;
            //--- --- ---//
            $query1 = $this->conn->query("
                    SELECT pc.id_period_calendar, gp.id_grade_period, gp.grade_period, pc.no_period, fg.id_assignment, gec.grade_evaluation_criteria_teacher, evp.id_evaluation_source
                    FROM iteach_grades_quantitatives.grades_period AS gp
                    INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
                    INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON gp.id_period_calendar = pc.id_period_calendar
                    LEFT JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON fg.id_assignment = evp.id_assignment AND evp.id_evaluation_source = 5 OR evp.id_evaluation_source = 6
                    LEFT JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON evp.id_evaluation_plan = gec.id_evaluation_plan AND gp.id_grade_period = gec.id_grade_period
                    WHERE fg.id_inscription = '$id_inscription' AND fg.id_assignment = '$id_assignment'
                    ORDER BY pc.no_period
                    ");

            if ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {
                //--- --- ---//
                $row->grades[] = $row1;
                //--- --- ---//
                $results[] = $row;
                //--- --- ---//
            }
        }

        return $results;
    }

    public function getCurrentSchoolYear()
    {

        $results = '';

        $query = $this->conn->query("
            SELECT school_year
            FROM school_control_ykt.current_school_year
            WHERE current_school_year = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row->school_year;
        }

        return $results;
    }

    //--- --- ---//
    //--- MDA ---//
    public function getLearningMaps($id_assignment)
    {

        $results = array();

        $query = $this->conn->query("
        SELECT lm.*, assc.*
        FROM iteach_grades_qualitatives.learning_maps AS lm
        INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc ON lm.id_learning_map = assc.id_learning_map
        WHERE assc.id_assignment = '$id_assignment'
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupsQuestionsMPA($lmpID)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*
        FROM iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa
        INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assasgmpa.id_learning_map = lmp.id_learning_map
        INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
        WHERE lmp.id_learning_map = $lmpID AND assasgmpa.id_learning_map = $lmpID
        ");

        //print_r($query);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getDataFormMPA($assc_mpa_id)
    {
        $questions = array();
        $evaluations = array();

        //--- PREGUNTAS ---//
        $query = $this->conn->query("
        SELECT qb.*
        FROM iteach_grades_qualitatives.associate_lm_eg_eq AS assc
        INNER JOIN iteach_grades_qualitatives.match_question_group_questions AS gq ON assc.id_question_group = gq.id_question_group
        INNER JOIN iteach_grades_qualitatives.question_bank AS qb ON gq.id_question_bank = qb.id_question_bank
        WHERE assc.assc_mpa_id = '$assc_mpa_id'
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $questions[] = $row;
        }

        //--- RESPUESTAS ---//
        $query = $this->conn->query("
        SELECT eb.*
        FROM iteach_grades_qualitatives.associate_lm_eg_eq AS assc
        INNER JOIN iteach_grades_qualitatives.match_evaluation_group_evaluations AS ge ON assc.id_evaluation_group = ge.id_evaluation_group
        INNER JOIN iteach_grades_qualitatives.evaluation_bank AS eb ON ge.id_evaluation_bank = eb.id_evaluation_bank
        WHERE assc.assc_mpa_id = '$assc_mpa_id'
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $evaluations[] = $row;
        }

        $results = array(
            'questions' => $questions,
            'evaluations' => $evaluations
        );

        return $results;
    }

    public function getAnswersMPAByGroup($assc_mpa_id, $ascc_lm_assgn, $no_installment, $id_group)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT log_lmp.*, log_questions.*, bq.colorHTML AS bckg
        FROM iteach_grades_qualitatives.learning_maps_log AS log_lmp 
        INNER JOIN iteach_grades_qualitatives.questions_log_learning_maps AS log_questions ON log_lmp.id_historical_learning_maps = log_questions.id_historical_learning_maps
        INNER JOIN school_control_ykt.inscriptions AS insc ON log_lmp.id_student = insc.id_student
        LEFT JOIN iteach_grades_qualitatives.evaluation_bank AS bq ON log_questions.id_evaluation_bank = bq.id_evaluation_bank
        WHERE log_lmp.ascc_lm_assgn = $ascc_lm_assgn AND log_lmp.assc_mpa_id = $assc_mpa_id AND log_lmp.no_installment = $no_installment AND insc.id_group = $id_group
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getFinalCommentsMPA($ascc_lm_assgn, $no_installment, $id_group)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT fnl_comm.id_comments, fnl_comm.id_student, fnl_comm.comments1, fnl_comm.comments2, fnl_comm.directors_comment
        FROM iteach_grades_qualitatives.final_comments AS fnl_comm
        INNER JOIN school_control_ykt.inscriptions AS insc ON fnl_comm.id_student = insc.id_student
        INNER JOIN school_control_ykt.groups AS groups ON insc.id_group = groups.id_group
        WHERE fnl_comm.ascc_lm_assgn = '$ascc_lm_assgn' AND fnl_comm.no_installment = '$no_installment' AND groups.id_group = '$id_group' AND (fnl_comm.comments1 != '' OR fnl_comm.comments2 != '' OR fnl_comm.directors_comment != '');
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
