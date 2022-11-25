<?php
include  dirname(__DIR__ . '', 2) . '/models/Connection.php';
include_once dirname(__DIR__ . '', 2) . "/models/academicLevelReport/modelPrimaryBangHebrew.php";
include_once dirname(__DIR__ . '', 2) . "/models/generalModel.php";

set_time_limit('0');
date_default_timezone_set('America/Mexico_City');
$function = $_POST['fun'];
/* $function = 'getSchoolReportCardHebrewPrimaryMalesBangueolo'; */
$function();


function getLafMalesSpanHighSR()
{
    $response = array();

    $id_level_combination_span = $_POST['id_level_combination'];
    $id_group = $_POST['id_group'];
    $id_academic_area = $_POST['id_academic_area'];

    $periods_heb = array();
    $array_groups_heb = array();
    $periods_heb = array();
    $groups_span = array();

    $model_heb = new DataSchoolReportCardsHebrew;
    $gral_model = new generalModelReports;
    $current_school_year = $gral_model->getCurrentSchoolYear();

    $array_groups_heb = $gral_model->getBaseGroupInformation($id_group);
    $periods_heb = $gral_model->getAllPeriodsByLevelCombination($id_level_combination_span);
    $students = array();

    foreach ($array_groups_heb as $group_heb) {
        //--- --- ---//
        $order_by_lang = $_POST['order_by_lang'];
        $array_languages = $model_heb->getAssignmentByGroupLanguage($group_heb->id_group, $id_academic_area, $order_by_lang);
        $array_gral = $gral_model->getAssignmentByGroup($group_heb->id_group, $id_academic_area);
        $students = $gral_model->getListStudentsByIDgroup($group_heb->id_group);

        $assignments_grals = array();
        $array_conductual = array();
        foreach ($array_gral as $assignments_grales) {
            /*  if (($assignments_grales->id_subject == 382) OR ($assignments_grales->id_subject == 388) OR ($assignments_grales->id_subject == 385)) {
             $array_languages[]=$assignments_grales;
            }else{
                $assignments_grals[] = $assignments_grales;
            } */
        }

        $list_students = array();
        foreach ($students as $index) {
            $qualifications = array();
            $qualifications_lang = array();
            $qualifications_cond = array();

            $getSpecialityGroup = $model->getSpecialityGroup($index->id_student);

            foreach ($periods_span as $p_h) {
                /* CALIFICACIONES GENERALES POR PERIODO */
                $order_by_gral = $_POST['order_by_gral'];
                $array_spanish = $model->getQualificationsByStudentPeriodHighSchoolSpan($group_span->id_group, '1', $index->id_student, $p_h->no_period, $p_h->id_period_calendar, $order_by_gral);
                //$array_spanish_speciality = $model->getQualificationsByStudentPeriodHighSchoolSpanSpeciality($group_span->id_group, '1', $index->id_student, $p_h->no_period, $p_h->id_period_calendar, $order_by_gral);
                //$array_spanish[]= $array_spanish_speciality;
                $qualifications_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'spanish_period_qualifications' => $array_spanish
                );
                $qualifications[] = $qualifications_period;

                $order_by_cond_span = "ORDER BY FIELD(evaluation_name, 'Actitud hacia el Estudio','Comportamiento','Orden y Aseo','Respeto','Uniforme','Actitud en la Tefila') asc";
                $array_conductual = $model->getQualificationsCondByStudentPeriodSpan($group_span->id_group, $group_span->id_academic_area, $index->id_student, $p_h->id_period_calendar, $order_by_cond_span);


                $qualifications_cond_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'period_qualifications' => $array_conductual
                );
                $qualifications_cond[] = $qualifications_cond_period;
                /* CALIFICACIONES CONDUCTUAL     POR PERIODO */
            }

            $list_students[] =
                array(
                    'student' => $index,
                    'qualifications' => $qualifications,
                    'qualifications_lang' => $qualifications_lang,
                    'qualifications_cond' => $qualifications_cond,
                    'speciality' => $getSpecialityGroup
                );
            /* $students[] = $array_st; */
        }
        $group_span->students[] = $list_students;
        $group_span->assignments_conductual[] = $array_conductual;
        $group_span->assignments_gral[] = $array_gral;
        $group_span->assignments_lang[] = $array_languages;

        //--- --- ---//
        /* foreach ($array_assgn as $assgn) {
            $
            $group_span->assignments[] = $assgn;
        } */

        $groups_span[] = $group_span;
    }
    //--- --- ---//
    $response = array(
        'response' => true,
        'current_school_year' => $current_school_year,
        'periods_heb' => $periods_heb,
        'groups_heb' => $groups_heb
    );

    echo json_encode($response);
}

function getSchoolReportCardHebrewHighSchoolLafontine()
{
    $response = array();

    $id_level_combination_span = $_POST['id_level_combination_span'];

    $periods_heb = array();
    $array_groups_heb = array();
    $periods_heb = array();
    $groups_span = array();

    $model = new DataSchoolReportCardsLafontine;
    $current_school_year = $model->getCurrentSchoolYear();

    $array_groups_span = $model->getAllGroupsByIdLevelCombination($id_level_combination_span);

    $periods_span = $model->getAllPeriodsByLevelCombination($id_level_combination_span);

    $students = array();


    foreach ($array_groups_span as $group_span) {
        //--- --- ---//
        $order_by_lang = $_POST['order_by_lang'];
        $array_languages = $model->getAssignmentByGroupLanguage($group_span->id_group, $group_span->id_academic_area, $order_by_lang);
        $array_gral = $model->getAssignmentByGroup($group_span->id_group, $group_span->id_academic_area);
        $students = $model->getListStudentsByIDgroup($group_span->id_group);


        $array_conductual = array();


        $list_students = array();
        foreach ($students as $index) {
            $qualifications = array();
            $qualifications_lang = array();
            $qualifications_cond = array();

            $getSpecialityGroup = $model->getSpecialityGroup($index->id_student);

            foreach ($periods_span as $p_h) {
                /* CALIFICACIONES GENERALES POR PERIODO */
                $order_by_gral = $_POST['order_by_gral'];
                $array_spanish = $model->getQualificationsByStudentPeriodHighSchoolSpan($group_span->id_group, '1', $index->id_student, $p_h->no_period, $p_h->id_period_calendar, $order_by_gral);
                //$array_spanish_speciality = $model->getQualificationsByStudentPeriodHighSchoolSpanSpeciality($group_span->id_group, '1', $index->id_student, $p_h->no_period, $p_h->id_period_calendar, $order_by_gral);
                //$array_spanish[]= $array_spanish_speciality;
                $qualifications_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'spanish_period_qualifications' => $array_spanish
                );
                $qualifications[] = $qualifications_period;

                $order_by_cond_span = "ORDER BY FIELD(evaluation_name, 'Actitud hacia el Estudio','Comportamiento','Orden y Aseo','Respeto','Uniforme','Actitud en la Tefila') asc";
                $array_conductual = $model->getQualificationsCondByStudentPeriodSpan($group_span->id_group, $group_span->id_academic_area, $index->id_student, $p_h->id_period_calendar, $order_by_cond_span);


                $qualifications_cond_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'period_qualifications' => $array_conductual
                );
                $qualifications_cond[] = $qualifications_cond_period;
                /* CALIFICACIONES CONDUCTUAL     POR PERIODO */
            }

            $list_students[] =
                array(
                    'student' => $index,
                    'qualifications' => $qualifications,
                    'qualifications_lang' => $qualifications_lang,
                    'qualifications_cond' => $qualifications_cond,
                    'speciality' => $getSpecialityGroup
                );
            /* $students[] = $array_st; */
        }
        $group_span->students[] = $list_students;
        $group_span->assignments_conductual[] = $array_conductual;
        $group_span->assignments_gral[] = $array_gral;
        $group_span->assignments_lang[] = $array_languages;

        //--- --- ---//
        /* foreach ($array_assgn as $assgn) {
            $
            $group_span->assignments[] = $assgn;
        } */

        $groups_span[] = $group_span;
    }
    //--- --- ---//
    $response = array(
        'response' => true,
        'current_school_year' => $current_school_year,
        'periods_span' => $periods_span,
        'groups_span' => $groups_span

    );

    echo json_encode($response);
}
