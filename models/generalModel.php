<?php

class generalModelReports extends Connection
{

    private $conn;
    public function __construct()
    {
        $this->conn = $this->db_conn();
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

    public function getAllPeriodsByLevelCombination($id_level_combination)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getBaseGroupInformation($id_group)
    {
        $results = array();

        $query = $this->conn->query("SELECT groups.id_group, groups.letter, groups.group_code,groups.hebrew_group, aclvg.id_level_grade, aclvg.degree,CONCAT (colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS tutor_name
        	FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.academic_levels_grade AS aclvg ON groups.id_level_grade = aclvg.id_level_grade
        	INNER JOIN school_control_ykt.academic_levels AS aclv ON aclvg.id_academic_level = aclv.id_academic_level
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = groups.no_tutor
        	WHERE groups.id_group = '$id_group' AND groups.group_type_id = 1  LIMIT 1");

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
            WHERE assgn.id_group = '$id_group' AND sbj.id_subject !=323 AND sbj.id_academic_area = '$id_academic_area' AND assgn.print_school_report_card = 1 AND assgn.assignment_active = 1 AND sbj.id_subject != 416 AND sbj.id_subject != 417 AND sbj.id_subject != 418  ORDER BY sbj.name_subject ASC"
        );

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
            WHERE inscription.id_group = '$group_id' 
            ORDER BY student.lastname
            ");

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
    
}
