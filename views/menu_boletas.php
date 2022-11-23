<?php
//--- --- ---//
/* include 'php/models/helpers.php';
include 'php/models/assignments.php';
include 'php/models/groups.php';
include 'php/models/attendance.php'; */
include 'models/Connection.php';
include 'models/academicLevelViewModel.php';


$academic_levels_view = new academicLevelsView();
if (isset($_GET['module'])) {
    $module = $_GET['module'];
    switch ($submodule) {


        case 'academic_level':
            $include_file = 'php/views/students/teachers/reports/attendance/attendance_teacher_report.php';
            $info_header_module = array(
                'module_name'     => 'Reportes de asistencia',
                'link_module'     => 'reports.php',
                'sub_module_name' => 'Reportes',
                'some_text' => 'Reporte Asistencia / Ausencia'
            );
            break;


        default:
            $include_file = 'views/boletas.php';
            break;
    }
} else {
    $include_file = 'views/academicLevelReport/boletas.php';
}

include $include_file;
