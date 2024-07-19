<?php
include  dirname(__DIR__ . '', 2) . '/models/Connection.php';
include_once dirname(__DIR__ . '', 2) . "/models/academicLevelReport/modelHighBangSpanish.php";
include_once dirname(__DIR__ . '', 2) . "/models/academicLevelReport/modelHighBangHebrew.php";
include_once dirname(__DIR__ . '', 2) . "/models/generalModel.php";

set_time_limit('0');
date_default_timezone_set('America/Mexico_City');
$function = $_POST['fun'];
/* $function = 'getSchoolReportCardHebrewPrimaryMalesBangueolo'; */
$function();


function getbangFemalesMixedHighSR()
{
    $response = array();

    $id_level_combination_heb = $_POST['id_level_combination_heb'];
    $id_level_combination_span = $_POST['id_level_combination_span'];

    $id_group = $_POST['id_group'];
    $id_academic_area_heb = 2;
    $id_academic_area_span = 1;

    $periods_heb = array();
    $array_groups_heb = array();
    $periods_heb = array();
    $groups_span = array();

    $model_span = new DataSchoolReportCardsSpanish;
    $model_heb = new DataSchoolReportCardsHebrew;
    $gral_model = new generalModelReports;

    $current_school_year = $gral_model->getCurrentSchoolYear();

    $array_groups_heb = $gral_model->getBaseGroupInformation($id_group);
    $periods_heb = $gral_model->getAllPeriodsByLevelCombination($id_level_combination_heb);

    $array_groups_span = $gral_model->getBaseGroupInformation($id_group);
    $periods_span = $gral_model->getAllPeriodsByLevelCombination($id_level_combination_span);

    $students = array();

    foreach ($array_groups_heb as $group_heb) {
        //--- --- ---//
        $order_by_lang = $_POST['order_by_lang'];
        $array_languages = $gral_model->getAssignmentByGroupLanguage($group_heb->id_group, $id_academic_area_heb, $order_by_lang);
        $array_gral = $gral_model->getAssignmentByGroup($group_heb->id_group, $id_academic_area_heb);
        $students = $gral_model->getListStudentsByIDgroup($group_heb->id_group);

        $array_conductual = array();

        $list_students = array();
        
        foreach ($students as $index) {
            $qualifications = array();
            $qualifications_lang = array();
            $qualifications_cond = array();

            foreach ($periods_heb as $p_h) {
                /* CALIFICACIONES GENERALES POR PERIODO */
                $order_by_gral = $_POST['order_by_gral'];
                $array_hebrew = $model_heb->getQualificationsByStudentPeriodHighSchool($group_heb->id_group, $id_academic_area_heb, $index->id_student, $p_h->no_period, $p_h->id_period_calendar, $order_by_gral);
                $array_hebrew_mejanej = $model_heb->getQualificationsMejanejet($group_heb->id_group, $id_academic_area_heb, $index->id_student, $p_h->no_period, $p_h->id_period_calendar);

                $qualifications_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'hebrew_period_qualifications' => $array_hebrew,
                    'period_qualifications_mejanej' => $array_hebrew_mejanej
                );
                $qualifications[] = $qualifications_period;

                /* CALIFICACIONES GENERALES POR PERIODO */

                /* CALIFICACIONES GENERALES POR PERIODO */
                $order_by_lang = $_POST['order_by_lang'];
                $array_st = $model_heb->getQualificationsLangByStudentPeriod($group_heb->id_group, $id_academic_area_heb, $index->id_student, $p_h->id_period_calendar, $order_by_lang);

                $qualifications_lang_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'period_qualifications' => $array_st
                );
                $qualifications_lang[] = $qualifications_lang_period;
                /* CALIFICACIONES GENERALES POR PERIODO */

                /* EXTRAORDINARIOS EN BOLETA */
                /* $getExtraordinaryExams = $model_heb->getExtraordinaryExams($id_group, '1', $index->id_student, $p_h->no_period, $p_h->id_period_calendar); */
                

                /* CALIFICACIONES CONDUCTUAL POR PERIODO */
                $order_by_cond_heb = "AND (ep.id_evaluation_source = 15 OR ep.id_evaluation_source = 24 OR ep.id_evaluation_source = 8 OR ep.id_evaluation_source = 25 OR ep.id_evaluation_source = 11 OR ep.id_evaluation_source = 16) ORDER BY FIELD(ep.id_evaluation_source, 15, 24, 8, 25, 11, 16) ASC";
                $array_conductual = $model_heb->getQualificationsCondByStudentPeriod($group_heb->id_group, $id_academic_area_heb, $index->id_student, $p_h->id_period_calendar,$order_by_cond_heb);

                $qualifications_cond_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'period_qualifications' => $array_conductual
                );
                $qualifications_cond[] = $qualifications_cond_period;
                /* CALIFICACIONES CONDUCTUAL     POR PERIODO */
            }

            //$array_exra_qualif = $model->getExtraFinalQualificationByStudentHighSchool($index->id_student);
            
            $array_exra_qualif = $model_heb->getAditionalExams($index->id_student,$group_heb->id_group);
            $list_students[] =
                array(
                    'student' => $index,
                    'qualifications' => $qualifications,
                    'qualifications_lang' => $qualifications_lang,
                    'qualifications_cond' => $qualifications_cond,
                    'additional_exams' => $array_exra_qualif
                );
            /* $students[] = $array_st; */
        }
        $group_heb->students[] = $list_students;
        $group_heb->assignments_conductual[] = $array_conductual;
        $group_heb->assignments_gral[] = $array_gral;
        $group_heb->assignments_lang[] = $array_languages;

        //--- --- ---//
        /* foreach ($array_assgn as $assgn) {
            $
            $group_span->assignments[] = $assgn;
        } */

        $groups_heb[] = $group_heb;
    }
    foreach ($array_groups_span as $group_span) {
        //--- --- ---//
        $order_by_lang = $_POST['order_by_lang'];
        $array_languages = $gral_model->getAssignmentByGroupLanguage($group_span->id_group, $id_academic_area_span, $order_by_lang);
        $array_gral = $gral_model->getAssignmentByGroup($group_span->id_group, $id_academic_area_span);
        $students = $gral_model->getListStudentsByIDgroup($group_span->id_group);
        

        $array_conductual = array();
       

        $list_students = array();
        foreach ($students as $index) {
            $qualifications = array();
            $qualifications_lang = array();
            $qualifications_cond = array();
            $getSpecialGroup = $model_span->getSpecialGroup($index->id_student);
            foreach ($periods_span as $p_h) {
                /* CALIFICACIONES GENERALES POR PERIODO */
                $order_by_gral = $_POST['order_by_gral'];
                $array_spanish = $model_span->getQualificationsByStudentPeriodHighSchoolSpan($group_span->id_group, '1', $index->id_student, $p_h->no_period, $p_h->id_period_calendar, $order_by_gral);
                
                //$array_spanish_speciality = $model->getQualificationsByStudentPeriodHighSchoolSpanSpeciality($group_span->id_group, '1', $index->id_student, $p_h->no_period, $p_h->id_period_calendar, $order_by_gral);
                //$array_spanish[]= $array_spanish_speciality;
                $qualifications_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'spanish_period_qualifications' => $array_spanish
                );
                $qualifications[] = $qualifications_period;
                $order_by_cond_span = "AND (ep.id_evaluation_source = 15 OR ep.id_evaluation_source = 24 OR ep.id_evaluation_source = 8 OR ep.id_evaluation_source = 25 OR ep.id_evaluation_source = 11 OR ep.id_evaluation_source = 16) ORDER BY FIELD(ep.id_evaluation_source, 15, 24, 8, 25, 11, 16) ASC";
                $array_conductual = $model_span->getQualificationsCondByStudentPeriodSpan($group_span->id_group, $id_academic_area_span, $index->id_student, $p_h->id_period_calendar,$order_by_cond_span);
                

                $getExtraordinaryExams = $model_span->getExtraordinaryExams($id_group, '1', $index->id_student, $p_h->no_period, $p_h->id_period_calendar);

                $qualifications_cond_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'period_qualifications' => $array_conductual,
                    'getExtraordinaryExams' => $getExtraordinaryExams
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
                    'students_groups' => $getSpecialGroup,
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
        'groups_heb' => $groups_heb,
        'periods_span' => $periods_span,
        'groups_span' => $groups_span
      
    );

    echo json_encode($response);
}