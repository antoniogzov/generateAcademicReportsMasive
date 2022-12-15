<?php

class DataSchoolReportCardsSpanish extends Connection
{

    private $conn;
    public function __construct()
    {
        $this->conn = $this->db_conn();
    }
    public function getQualificationsByStudentPeriodHighSchoolSpan($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar, $order_by_gral)
    {
        $results = array();
        $sql = "SELECT DISTINCT
        assgn.id_assignment,
        sbj.id_subject,
        percal.no_period,
        sbj.name_subject AS name_subject_original,
        sbj1.name_subject,
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
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student AND asscassglmp.id_inscription = insc.id_inscription
        INNER JOIN school_control_ykt.subjects AS sbj1
        ON sbj.id_subject_group = sbj1.id_subject
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS ext_exam ON ext_exam.`id_grade_period` = grape.`id_grade_period`
        WHERE assgn.id_group = insc.id_group
        AND assgn.print_school_report_card = 1
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area
        AND sbj.id_subject != 417
        AND sbj.id_subject != 418
        AND sbj.name_subject LIKE 'M1%'
        AND assgn.print_school_report_card != 0
        ORDER BY sbj1.name_subject
        ";

        //echo $sql;

        $sql2 = "SELECT DISTINCT
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
        WHEN ext_exam.`grade_extraordinary_examen` IS NULL THEN '-'
        ELSE ext_exam.`grade_extraordinary_examen`
        END
        AS 'extraordinary',
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
        ON percal.id_period_calendar = $id_period_calendar
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp
        ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student AND asscassglmp.id_inscription = insc.id_inscription    
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape
        ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS ext_exam ON ext_exam.`id_grade_period` = grape.`id_grade_period`
         WHERE insc.id_group != $id_group
         AND assgn.print_school_report_card = 1
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area
         AND sbj.id_subject != 30
        AND sbj.id_subject != 309
        AND sbj.id_subject != 324
        AND sbj.id_subject != 431
        AND sbj.id_subject != 417
        AND sbj.id_subject != 27
        AND sbj.id_subject != 341
        AND grape.grade_period IS NOT NULL 
        AND sbj.id_subject != 345
        AND sbj.id_subject != 346
        AND sbj.id_subject != 347
        AND sbj.id_subject != 418
        AND sbj.name_subject LIKE 'M1:%'
        ORDER BY sbj.name_subject
        ";
        //echo $sql2;
        $query = $this->conn->query($sql);
        $query2 = $this->conn->query($sql2);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        while ($row2 = $query2->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row2;
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
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON gec.id_final_grade = fga.id_final_grade AND asg.id_assignment = fga.id_assignment AND fga.id_student = '$id_student'
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
}
