<?php

class DataSchoolReportCardsHebrew extends Connection
{

    private $conn;
    public function __construct()
    {
        $this->conn = $this->db_conn();
    }

    public function getQualificationsByStudentPeriod($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar, $order_by_gral)
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
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area 
        AND sbj.id_subject != 416
        AND sbj.id_subject !=323
        AND assgn.print_school_report_card = 1
            $order_by_gral";
        //echo $sql;
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAbsencesByStudentPeriod($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar)
    {
        $results = array();
        $id_period_calendar = $id_period_calendar-4;
        $sql = "SELECT DISTINCT
        CASE 
         WHEN gec.grade_evaluation_criteria_teacher IS NULL THEN '-'
         ELSE gec.grade_evaluation_criteria_teacher
         END
         AS 'calificacion',
         percal.no_period
         FROM school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student= stud.id_student
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = insc.id_group
        INNER JOIN  school_control_ykt.assignments AS assgn ON assgn.id_group = groups.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assgn.id_assignment = fga.id_assignment AND fga.id_student = stud.id_student
        INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON gec.id_grade_period = grape.id_grade_period
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evplan ON gec.id_evaluation_plan = gec.id_evaluation_plan
        WHERE percal.id_period_calendar = $id_period_calendar
         AND assgn.id_group = $id_group
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = 1
        AND evplan.id_evaluation_source = 53
        AND sbj.id_subject = 417
        ";
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
        $sql = "SELECT  sbj.name_subject,
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
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_group = gps.id_group
        INNER JOIN school_control_ykt.students AS stud ON stud.id_student = insc.id_student
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON  asg.id_assignment = fga.id_assignment  AND fga.id_student = stud.id_student
        INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON  grape.id_final_grade = fga.id_final_grade 
       INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON gec.id_grade_period = grape.id_grade_period
         INNER JOIN  iteach_grades_quantitatives.evaluation_plan AS ep ON gec.id_evaluation_plan = ep.id_evaluation_plan AND ep.id_assignment = asg.id_assignment AND ep.id_period_calendar = grape.id_period_calendar 
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou ON esou.id_evaluation_source = ep.id_evaluation_source 
        WHERE grape.id_period_calendar = '$id_period_calendar' 
        AND sbj.id_subject = 416
        AND gps.id_group = $id_group
        AND stud.id_student = $id_student  
        $order_by_cond_heb
        ";
        //echo $sql;
        $query = $this->conn->query($sql);

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

    public function getCommentarySchoolReport($id_group, $id_academic_area, $id_student, $no_period, $id_period_calendar)
    {
        $results = array();
        $sql = "SELECT 
        learning_map_types_id,
        id_comments,
        CASE 
        WHEN fincom.comments1 IS NULL THEN '-'
        ELSE fincom.comments1
        END
        AS 'comentarios_finales',
       fincom.no_installment,
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
        LEFT JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp ON assgn.id_assignment = asscassglmp.id_assignment AND asscassglmp.id_student = $id_student
        LEFT JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = asscassglmp.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        INNER JOIN iteach_grades_qualitatives.learning_maps AS lm
        INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc ON lm.id_learning_map = assc.id_learning_map 
        LEFT JOIN iteach_grades_qualitatives.final_comments AS fincom ON fincom.ascc_lm_assgn = assc.ascc_lm_assgn and fincom.id_student = $id_student
        LEFT JOIN iteach_grades_qualitatives.learning_maps_log  AS learlog ON learlog.ascc_lm_assgn=assc.ascc_lm_assgn AND learlog.id_student = $id_student AND learlog.no_installment = $no_period
        LEFT JOIN iteach_grades_qualitatives.questions_log_learning_maps AS quesslm ON quesslm.id_historical_learning_maps = learlog.id_historical_learning_maps
        LEFT JOIN colaboradores_ykt.colaboradores AS mejan ON fincom.no_teacher_fill = mejan.no_colaborador
        WHERE assc.id_assignment = assgn.id_assignment AND lm.id_learning_map = 24
         AND assgn.id_group = $id_group
        AND insc.id_student = $id_student
        AND sbj.id_academic_area = $id_academic_area
        AND fincom.no_installment = $no_period
        ";
        //echo $sql;

        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
