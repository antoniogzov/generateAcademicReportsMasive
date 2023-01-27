<?php
ini_set('max_execution_time', 0);

class DataSchoolReportCardsSecondaryMales extends Connection{

	private $conn;
    public function __construct() {
        $this->conn = $this->db_conn();
    }

    public function getAllPeriodsByLevelCombination($id_level_combination){
        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination' AND (no_period = 1 OR no_period = 2 OR no_period = 3 OR no_period = 4 OR no_period = 5)");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAllGroupsByIdLevelCombination($id_level_combination){
        $results = array();

        $query = $this->conn->query("SELECT groups.*, lvl_comb.id_academic_area, aclvg.degree
            FROM school_control_ykt.level_combinations AS lvl_comb
            INNER JOIN school_control_ykt.groups AS groups ON lvl_comb.id_campus = groups.id_campus AND lvl_comb.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels AS aclv ON lvl_comb.id_academic_level = aclv.id_academic_level
            INNER JOIN school_control_ykt.academic_levels_grade AS aclvg ON groups.id_level_grade = aclvg.id_level_grade AND lvl_comb.id_academic_level = aclvg.id_academic_level
            WHERE lvl_comb.id_level_combination = '$id_level_combination' AND groups.group_code = 'SC2-LFV-B'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getListStudentsByIDgroup($group_id) {

        $results = array();

        $query = $this->conn->query("
            SELECT student.id_student, student.student_code, student.name, student.lastname, CONCAT(UPPER(student.lastname), ' ', UPPER(student.name)) AS student_name
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            WHERE inscription.id_group = '$group_id' AND student.status = 1 
            ORDER BY student.lastname LIMIT 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;

    }

    public function getAssignmentByGroup($id_group, $id_academic_area){
        $results = array();

        $order_subject_3 = [17, 307, 35, 12, 28, 359, 15, 6, 14];
        $order_subject_2 = [17, 306, 35, 11, 28, 359, 15, 6, 14];
        $order_subject_1 = [17, 305, 35, 10, 28, 18, 359, 15, 6, 14];

        $query = $this->conn->query("SELECT assgn.*, sbj.*, colb.nombre_hebreo AS hebrew_name_teacher,  CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
            FROM school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            WHERE assgn.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic_area' AND assgn.print_school_report_card = 1 ORDER BY FIELD(sbj.id_subject, 17, 305, 35, 10, 28, 18, 359, 15, 6, 14)
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAveragesStudent($id_assignment){
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
            $id_student = $row->id_student;
            //--- --- ---//
            //--- INASISTENCIAS ---//
            $query1 = $this->conn->query("
                SELECT pc.id_period_calendar, gp.id_grade_period, pc.start_date, pc.end_date, pc.no_period, fg.id_assignment,
                CASE 
                WHEN gp.grade_period_calc != '' THEN gp.grade_period_calc
                ELSE gp.grade_period 
                END AS grade_period
                FROM iteach_grades_quantitatives.grades_period AS gp
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
                INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON gp.id_period_calendar = pc.id_period_calendar 
                WHERE fg.id_inscription = '$id_inscription' AND fg.id_assignment = '$id_assignment' AND (pc.no_period = 1)
                ORDER BY pc.no_period
                ");

            while ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {
                //--- INASISTENCIAS ---//
                $id_period_calendar = $row1->id_period_calendar;
                $start_date = $row1->start_date;
                $end_date = $row1->end_date;
                //--- --- ---//
                $absences = 0;
                $AttendanceIndex = $this->getAttendanceIndexTeacherReport($id_assignment,  $start_date, $end_date);
                $totalAttendance = count($AttendanceIndex);
                if ($totalAttendance > 0) {
                    $total_classes_student_attended = 0;
                    foreach ($AttendanceIndex AS $att_index) {
                        $id_attendance_index = $att_index->id_attendance_index;
                        $getStudentAttendance = $this->getStudentAttendanceByTypes($id_attendance_index, $id_student);

                        if($getStudentAttendance[0]->attendance > 0){
                            $total_classes_student_attended += $getStudentAttendance[0]->attendance;
                        }
                    }

                    $absences = $totalAttendance - $total_classes_student_attended;
                }

                $row1->absences = $absences;
                //--- --- ---//
                $row->grades[] = $row1;
                //--- --- ---//
            }
            //--- --- ---//
            $results[] = $row;
            //--- --- ---//

        }

        return $results;
    }

    public function getAttendanceIndexTeacherReport($id_assignment,  $fechaInicio, $fechaMaxima) {

        $results = array();

        $query = $this->conn->query("
            SELECT t1.* 
            FROM attendance_records.attendance_index AS t1
            WHERE DATE(apply_date) >= '$fechaInicio' AND DATE(apply_date) <= '$fechaMaxima' AND t1.obligatory = '1' AND t1.valid_assistance = 1 AND t1.id_assignment = $id_assignment AND id_attendance_index = 
            (SELECT id_attendance_index
               FROM attendance_records.attendance_index AS t2
               WHERE t1.id_assignment = t2.id_assignment AND DATE(t2.apply_date) = DATE(t1.apply_date) AND t1.class_block = t2.class_block
               ORDER BY t2.id_attendance_index DESC LIMIT 1)
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getStudentAttendanceByTypes($id_att_index, $id_student){
        $results = array();

        $query = $this->conn->query("
            SELECT COUNT(*) AS attendance FROM attendance_records.attendance_record WHERE id_attendance_index = $id_att_index AND id_student = $id_student AND (attend = 1 OR (attend = 0 AND apply_justification = 1));
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
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
}