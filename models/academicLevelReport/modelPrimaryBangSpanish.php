<?php

class DataSchoolReportCardsSpanish extends Connection
{

    private $conn;
    public function __construct()
    {
        $this->conn = $this->db_conn();
    }


    public function getLearningMaps($id_assignment)
    {
        // Recibe principalmente el id del MDA correspondiente según assignment 
        $results = array();
        $sql = "SELECT DISTINCT lm.id_learning_map, assc.ascc_lm_assgn, assasgmpa.assc_mpa_id, qb.*
                FROM iteach_grades_qualitatives.learning_maps AS lm
                    INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc 
                        ON lm.id_learning_map = assc.id_learning_map
                    LEFT JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa
                        ON assasgmpa.id_learning_map = lm.id_learning_map
                    LEFT JOIN iteach_grades_qualitatives.question_groups AS qg 
                        ON assasgmpa.id_question_group = qg.id_question_group
                    INNER JOIN iteach_grades_qualitatives.match_question_group_questions AS gq 
                        ON assasgmpa.id_question_group = gq.id_question_group
                    INNER JOIN iteach_grades_qualitatives.question_bank AS qb 
                        ON gq.id_question_bank = qb.id_question_bank
                WHERE assc.id_assignment = '$id_assignment'";
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getQualificationsByStudentPeriod($id_group, $id_academic_area, $id_student, $id_period_calendar)
    {
        $results = array();
        $sql = "SELECT assgn.id_assignment,
                        asscassglmp.id_final_grade,
                        percal.no_period,
                        sbj.name_subject,
                    (CASE
                        WHEN asscassglmp.final_grade IS NULL THEN '-'
                        ELSE asscassglmp.final_grade
                    END) AS 'promedio_final',
                    (CASE 
                        WHEN grape.grade_period IS NULL THEN '-'
                        ELSE grape.grade_period
                    END) AS 'calificacion',
                        percal.no_period,
                        id_grade_period,
                        CONCAT(colb.apellido_paterno_colaborador,' ',colb.nombres_colaborador) AS spanish_name_teacher,
                        sbj_tp.subject_type
                FROM school_control_ykt.assignments AS assgn
                    INNER JOIN school_control_ykt.inscriptions_old AS insc
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
                    AND sbj.id_academic_area = $id_academic_area
                    AND sbj.id_subject != 417
                    AND sbj.subject_type_id != '3'
                    AND assgn.print_school_report_card = 1";
        $query = $this->conn->query($sql);
        //echo $sql;
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAnswersMPAByGroup($assc_mpa_id, $ascc_lm_assgn, $no_installment, $id_student)
    {
        $results = array();
        $sql = "SELECT log_lmp.id_historical_learning_maps,
                        log_questions.question,log_questions.symbol,log_questions.evaluation
                FROM iteach_grades_qualitatives.learning_maps_log AS log_lmp 
                    INNER JOIN iteach_grades_qualitatives.questions_log_learning_maps AS log_questions 
                        ON log_lmp.id_historical_learning_maps = log_questions.id_historical_learning_maps
                    INNER JOIN school_control_ykt.inscriptions_old AS insc 
                        ON log_lmp.id_student = insc.id_student
                    LEFT JOIN iteach_grades_qualitatives.evaluation_bank AS bq 
                        ON log_questions.id_evaluation_bank = bq.id_evaluation_bank
                WHERE log_lmp.ascc_lm_assgn = $ascc_lm_assgn
                    AND log_lmp.assc_mpa_id = $assc_mpa_id
                    AND log_lmp.no_installment = $no_installment
                    AND insc.id_student = $id_student";
        $query = $this->conn->query($sql);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getQualificationsCondByStudentPeriod($id_group, $id_student, $id_period_calendar)
    {
        $results = array();
        $sql = "SELECT DISTINCT sbj.name_subject,
                       CASE 
                            WHEN esou.evaluation_name IS NULL THEN ep.manual_name
                            WHEN esou.evaluation_name = 'Asignación libre' THEN ep.manual_name
                            WHEN esou.id_evaluation_source = 1 THEN ep.manual_name
                            ELSE esou.evaluation_name
                        END AS 'evaluation_name',
                        ep.manual_name,
                        ep.id_period_calendar,
                        fga.id_final_grade,
                        CASE 
                            WHEN gec.grade_evaluation_criteria_teacher  IS NULL THEN '-'
                            ELSE gec.grade_evaluation_criteria_teacher
                        END AS 'grade_evaluation_criteria_teacher',
                        CASE 
                            WHEN esou.hebrew_name  IS NULL THEN ' '
                            ELSE esou.hebrew_name
                        END AS 'eval_hebrew_name'
                FROM school_control_ykt.subjects AS sbj
                INNER JOIN school_control_ykt.assignments AS asg 
                    ON sbj.id_subject = asg.id_subject
                INNER JOIN school_control_ykt.students AS stud 
                    ON stud.id_student = '$id_student'
                LEFT JOIN iteach_grades_quantitatives.evaluation_plan AS ep 
                    ON  ep.id_assignment = asg.id_assignment
                INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou 
                    ON esou.id_evaluation_source = ep.id_evaluation_source 
                LEFT JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec 
                    ON ep.id_evaluation_plan = gec.id_evaluation_plan
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga 
                    ON gec.id_final_grade = fga.id_final_grade AND asg.id_assignment = fga.id_assignment AND fga.id_student = '$id_student'
                WHERE sbj.id_subject = 417
                    AND ep.id_period_calendar = '$id_period_calendar'
                    AND stud.id_student= '$id_student'";
        $query = $this->conn->query($sql);
        if ($query->rowCount() == 0) {
            $sql2 = "SELECT DISTINCT sbj.name_subject,
                                     CASE 
                            WHEN esou.evaluation_name IS NULL THEN ep.manual_name
                            WHEN esou.evaluation_name = 'Asignación libre' THEN ep.manual_name
                            WHEN esou.id_evaluation_source = 1 THEN ep.manual_name
                            ELSE esou.evaluation_name
                        END AS 'evaluation_name',
                        ep.manual_name,
                                    ep.manual_name,
                                    ep.id_period_calendar,
                                    gec.grade_evaluation_criteria_teacher,
                                    CASE 
                                        WHEN esou.hebrew_name  IS NULL THEN ' '
                                        ELSE esou.hebrew_name
                                    END AS 'eval_hebrew_name'
                    FROM school_control_ykt.subjects AS sbj
                    INNER JOIN school_control_ykt.assignments AS asg 
                        ON sbj.id_subject = asg.id_subject
                    INNER JOIN school_control_ykt.students AS stud 
                        ON stud.id_student = '$id_student'
                    LEFT JOIN iteach_grades_quantitatives.evaluation_plan AS ep 
                        ON  ep.id_assignment = asg.id_assignment
                    INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou 
                        ON esou.id_evaluation_source = ep.id_evaluation_source 
                    LEFT JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec 
                        ON ep.id_evaluation_plan = gec.id_evaluation_plan
                    LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS fga 
                        ON gec.id_final_grade = fga.id_final_grade AND asg.id_assignment = fga.id_assignment AND fga.id_student = '$id_student'
                    WHERE sbj.id_subject = 417
                        AND ep.id_period_calendar = '$id_period_calendar'
                        AND stud.id_student= '$id_student'";
            $query = $this->conn->query($sql2);
        }

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
