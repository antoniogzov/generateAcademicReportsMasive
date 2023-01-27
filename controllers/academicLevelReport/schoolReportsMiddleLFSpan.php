<?php
include  dirname(__DIR__ . '', 2) . '/models/Connection.php';
include_once dirname(__DIR__ . '', 2) . "/models/academicLevelReport/modelMiddleLafSpanish.php";
include_once dirname(__DIR__ . '', 2) . "/models/generalModel.php";

set_time_limit('0');
date_default_timezone_set('America/Mexico_City');
$function = $_POST['fun'];
/* $function = 'getSchoolReportCardHebrewPrimaryMalesBangueolo'; */
$function();


function getLafMalesSpanMiddleSR()
{
    $response = array();

    $id_level_combination_span = $_POST['id_level_combination'];
    $id_group = $_POST['id_group'];
    $id_academic_area = $_POST['id_academic_area'];

    $array_groups_span = array();
    $periods_span = array();
    $groups_span = array();

    $model_span = new DataSchoolReportCardsSecondaryMales;
    $gral_model = new generalModelReports;

    $current_school_year = $gral_model->getCurrentSchoolYear();

    $array_groups_span = $gral_model->getBaseGroupInformation($id_group);
    $periods_span = $gral_model->getAllPeriodsByLevelCombination($id_level_combination_span);
    $students = array();

    foreach ($array_groups_span as $group_span) {
        //--- --- ---//
        $array_gral = $model_span->getAssignmentByGroup($group_span->id_group, $id_academic_area, $group_span->id_level_grade);
        $students = $gral_model->getListStudentsByIDgroup($group_span->id_group);

        $group_span->students = $students;
        foreach ($array_gral as $assgn) {
            $assgn->averages[] = $model_span->getAveragesStudent($assgn->id_assignment);
            $group_span->assignments[] = $assgn;
        }
        $groups_span[] = $group_span;
    }

    $response = array(
        'response' => true,
        'current_school_year' => $current_school_year,
        'periods_span' => $periods_span,
        'groups_esp' => $groups_span
    );
    echo json_encode($response);
}
