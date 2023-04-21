<?php
include  dirname(__DIR__ . '', 2) . '/models/Connection.php';
include_once dirname(__DIR__ . '', 2) . "/models/academicLevelReport/modelPrimaryBangHebrew.php";
include_once dirname(__DIR__ . '', 2) . "/models/generalModel.php";

set_time_limit('0');
date_default_timezone_set('America/Mexico_City');
$function = $_POST['fun'];
/* $function = 'getSchoolReportCardHebrewPrimaryMalesBangueolo'; */
$function();


function getBangFemHebPrimSR()
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

            foreach ($periods_heb as $p_h) {
                /* CALIFICACIONES GENERALES POR PERIODO */
                $order_by_gral = $_POST['order_by_gral'];
                $array_st = $model_heb->getQualificationsByStudentPeriod($group_heb->id_group, $id_academic_area, $index->id_student, $p_h->no_period, $p_h->id_period_calendar, $order_by_gral);

                $qualifications_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'period_qualifications' => $array_st
                );
                $qualifications[] = $qualifications_period;

                $commentary = "";
                $mejanejet_name_teacher = "";
                /* COMENTARIO EN BOLETA */
                $getCommentaryReport = $model_heb->getCommentarySchoolReport($id_group, $id_academic_area, $index->id_student, $p_h->no_period, $p_h->id_period_calendar);
                if (!empty($getCommentaryReport)) {
                    $commentary = $getCommentaryReport[0]->comentarios_finales;
                    $mejanejet_name_teacher = $getCommentaryReport[0]->spanish_name_teacher;
                }

                /* COMENTARIO EN BOLETA */
                $getAbsencesStudent = $model_heb->getAbsencesByStudentPeriod($id_group, $id_academic_area, $index->id_student, $p_h->no_period, $p_h->id_period_calendar);
                if (!empty($getAbsencesStudent)) {
                    $absences = $getAbsencesStudent[0]->calificacion;
                }else{
                    $absences = "-";
                }

                /* CALIFICACIONES GENERALES POR PERIODO */
                $order_by_lang = $_POST['order_by_lang'];
                $array_st = $model_heb->getQualificationsLangByStudentPeriod($group_heb->id_group, $id_academic_area, $index->id_student, $p_h->id_period_calendar, $order_by_lang);

                $qualifications_lang_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'period_qualifications' => $array_st
                );
                $qualifications_lang[] = $qualifications_lang_period;
                /* CALIFICACIONES GENERALES POR PERIODO */
                /* CALIFICACIONES CONDUCTUAL POR PERIODO */
                $order_by_cond_heb = 'ORDER BY esou.evaluation_name';
                $array_conductual = $model_heb->getQualificationsCondByStudentPeriodPrimary($group_heb->id_group, $id_academic_area, $index->id_student, $p_h->id_period_calendar, $order_by_cond_heb);

                $qualifications_cond_period = array(
                    'id_period_calendar' => $p_h->id_period_calendar,
                    'no_period' => $p_h->no_period,
                    'period_qualifications' => $array_conductual,
                    'comments' => $commentary,
                    'mejanejet_name' => $mejanejet_name_teacher,
                    'absences' => $absences
                );
                $qualifications_cond[] = $qualifications_cond_period;
                /* CALIFICACIONES CONDUCTUAL     POR PERIODO */
            }


            $list_students[] =
                array(
                    'student' => $index,
                    'qualifications' => $qualifications,
                    'qualifications_lang' => $qualifications_lang,
                    'qualifications_cond' => $qualifications_cond
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
    //--- --- ---//
    $response = array(
        'response' => true,
        'current_school_year' => $current_school_year,
        'periods_heb' => $periods_heb,
        'groups_heb' => $groups_heb
    );

    echo json_encode($response);
}
