<?php

include_once dirname(__DIR__ . '', 2) . "/models/petitionsAjax.php";

date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}

function getCampusByAcademicLevel()
{
    $queries = new Queries;

    $id_academic_level = $_POST['id_academic_level'];

    $getCamps = "SELECT DISTINCT camp.* 
                FROM school_control_ykt.level_combinations AS lc 
                INNER JOIN school_control_ykt.campus AS camp ON lc.id_campus = camp.id_campus
                WHERE lc.id_academic_level = $id_academic_level";

    $getCampus = $queries->getData($getCamps);
    //--- --- ---//
    //$last_id = $getInfoRequest['last_id'];
    if (!empty($getCampus)) {

        $data = array(
            'response' => true,
            'data' => $getCampus
        );
        //--- --- ---//

    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message' => 'No se encontraron campus'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function getSectionsByCampus()
{
    $queries = new Queries;

    $id_campus = $_POST['id_campus'];
    $id_academic_level = $_POST['id_academic_level'];

    $getCamps = "SELECT DISTINCT sct.*
                FROM school_control_ykt.campus AS camp
                INNER JOIN school_control_ykt.groups AS grp ON camp.id_campus = grp.id_campus
                INNER JOIN school_control_ykt.academic_levels_grade AS al ON grp.id_level_grade = al.id_level_grade
                INNER JOIN school_control_ykt.sections AS sct ON sct.id_section = grp.id_section
                WHERE camp.id_campus = $id_campus AND al.id_academic_level = $id_academic_level";

    $getCampus = $queries->getData($getCamps);
    //--- --- ---//
    //$last_id = $getInfoRequest['last_id'];
    if (!empty($getCampus)) {

        $data = array(
            'response' => true,
            'data' => $getCampus
        );
        //--- --- ---//

    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message' => 'No se encontraron campus'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getPeriods()
{
    $queries = new Queries;

    
    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    $id_campus = $_POST['id_campus'];
    $id_section = $_POST['id_section'];


    $sqlGetPeriods = "SELECT percal.*
    FROM school_control_ykt.level_combinations AS lvc 
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_level_combination = lvc.id_level_combination
    WHERE 
        lvc.id_academic_area = $id_academic_area
        AND lvc.id_academic_level = $id_academic_level
        AND lvc.id_campus = $id_campus
        AND lvc.id_section = $id_section
    ";

    $getPeriods = $queries->getData($sqlGetPeriods);
    //--- --- ---//
    //$last_id = $getInfoRequest['last_id'];
    if (!empty($getPeriods)) {

        $data = array(
            'response' => true,
            'data' => $getPeriods
        );
        //--- --- ---//

    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message' => 'No se encontraron campus'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
