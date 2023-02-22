<?php
include  dirname(__DIR__ . '', 2) . '/models/Connection.php';
include_once dirname(__DIR__ . '', 2) . "/models/academicLevelReport/modelPrimaryBangSpanish.php";
include_once dirname(__DIR__ . '', 2) . "/models/generalModel.php";

set_time_limit('0');
date_default_timezone_set('America/Mexico_City');
$function = $_POST['fun'];
/* $function = 'getSchoolReportCardHebrewPrimaryMalesBangueolo'; */
$function();

function getBangFemSpanPrimSR()
{
    $response = array();

    $id_level_combination_span = $_POST['id_level_combination'];
    $id_group = $_POST['id_group'];
    $id_academic_area = $_POST['id_academic_area'];


    $periods_span = array();
    $array_groups_span = array();
    $periods_span = array();
    $groups_span = array();

    $model_span = new DataSchoolReportCardsSpanish;
    $gral_model = new generalModelReports;
    $current_school_year = $gral_model->getCurrentSchoolYear();

    $array_groups_span = $gral_model->getBaseGroupInformation($id_group);
    $periods_span = $gral_model->getAllPeriodsByLevelCombination($id_level_combination_span);
    $students = array();

    foreach ($array_groups_span as $group_span) {
        
        //--- --- ---//
        /* $array_conductual = $model->getAssignmentByGroupConductual($group_span->id_group, $group_span->id_academic_area); */
        $array_gral = $gral_model->getAssignmentByGroup($group_span->id_group, $id_academic_area);
        $students = $gral_model->getListStudentsByIDgroup($group_span->id_group);
        /* Declara los arreglos necesarios para obtener los MDA del alumno */
        $assignments_grals = array();
        $getMDAE = array();
        foreach ($array_gral as $assignments_grales) {
            
            $assignments_grals[] = $assignments_grales; // Este arreglo contiene las asignaturas del grupo
            /* MDA esp */
            $mdaEsp = $model_span->getLearningMaps($assignments_grales->id_assignment);
            if (!empty($mdaEsp)) {
                array_push($getMDAE, $mdaEsp);
            }
        }
        

        /* Preguntas */
        $dataQ = array();
        foreach ($getMDAE as $lm) {
            foreach ($lm as $q) {
                $dataQ[] = array(
                    'id_learning_map' => $q->id_learning_map,
                    'ascc_lm_assgn' => $q->ascc_lm_assgn,
                    'assc_mpa_id' => $q->assc_mpa_id,
                    'id_question_bank' => $q->id_question_bank,
                    'question' => $q->question
                );
            }
        }
        

        /* OBTENER RESULTADOS MDA POR GRUPOS */
        $list_students = array();
        foreach ($students as $index) {
            $qualifications = [];
            $qualifications_cond = [];
            $mda_array = [];
            foreach ($periods_span as $p_s) {
                $array_st = $model_span->getQualificationsByStudentPeriod($group_span->id_group, $id_academic_area, $index->id_student, $p_s->id_period_calendar);
                /* Calificaciones MDA Español*/
                $q_d_MDAE = $model_span->getAnswersMPAByGroup(
                    $dataQ[4]['assc_mpa_id'],
                    $dataQ[4]['ascc_lm_assgn'],
                    $p_s->no_period,
                    $index->id_student
                );
                /* Calificaciones MDA Inglés*/
                $q_d_MDAI = $model_span->getAnswersMPAByGroup(
                    $dataQ[0]['assc_mpa_id'],
                    $dataQ[0]['ascc_lm_assgn'],
                    $p_s->no_period,
                    $index->id_student
                );
                if (empty($q_d_MDAI)) {
                    $var = [];
                    for ($x = 0; $x <= 3; $x++) {
                        $var[] = array(
                            "question"  => $dataQ[$x]['question'],
                            "symbol"    => "-"
                        );
                    }
                    $q_d_MDAI = $var;
                }

                $qualifications_mda = array(
                    'id_period_calendar'    => $p_s->id_period_calendar,
                    'no_period'             => $p_s->no_period,
                    'mda_sp'                => $q_d_MDAE,
                    'mda_ing'               => $q_d_MDAI
                );
                $mda_array[] = $qualifications_mda;
                /* Calificaciones generales */
                $qualifications_period = array(
                    'id_period_calendar'    => $p_s->id_period_calendar,
                    'no_period'             => $p_s->no_period,
                    'period_qualifications' => $array_st
                );
                $qualifications[] = $qualifications_period;

                /* Conductuales */
                $array_conductual = $model_span->getQualificationsCondByStudentPeriod($group_span->id_group, $index->id_student, $p_s->id_period_calendar);
                $qualifications_cond_period = array(
                    'id_period_calendar'    => $p_s->id_period_calendar,
                    'no_period'             => $p_s->no_period,
                    'period_qualifications' => $array_conductual
                );
                $qualifications_cond[] = $qualifications_cond_period;
            }

            $list_students[] =
                array(
                    'student'               => $index,
                    'qualifications'        => $qualifications,
                    'qualifications_cond'   => $qualifications_cond,
                    'qualifications_mda'    => $mda_array
                );
        }
        $group_span->students[] = $list_students;
        //$group_span->assignments_conductual[] = $array_conductual;
        $group_span->assignments_gral[] = $array_gral;

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
