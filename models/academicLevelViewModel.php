<?php

class academicLevelsView extends Connection
{

    private $conn;
    public function __construct()
    {
        $this->conn = $this->db_conn();
    }

    public function getAcademicLevels()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM school_control_ykt.academic_levels WHERE id_academic_level !=6 ORDER BY id_academic_level ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAcademicAreas()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM school_control_ykt.academic_areas ORDER BY name_academic_area ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getCampusByAcademicLevel($id_academic_level)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT camp.* 
        FROM school_control_ykt.level_combinations AS lc 
        INNER JOIN school_control_ykt.campus AS camp ON lc.id_campus = camp.id_campus
        WHERE lc.id_academic_level = $id_academic_level");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getSectionsByCampus($id_campus, $id_academic_level)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sct.*
        FROM school_control_ykt.campus AS camp
        INNER JOIN school_control_ykt.groups AS grp ON camp.id_campus = grp.id_campus
        INNER JOIN school_control_ykt.academic_levels_grade AS al ON grp.id_level_grade = al.id_level_grade
        INNER JOIN school_control_ykt.sections AS sct ON sct.id_section = grp.id_section
        WHERE camp.id_campus = $id_campus AND al.id_academic_level = $id_academic_level");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getLevelCombinations($id_academic_area, $id_academic_level, $id_campus, $id_section)
    {
        $results = array();

        $query = $this->conn->query("SELECT * 
        FROM school_control_ykt.level_combinations
        WHERE id_academic_area = $id_academic_area AND id_academic_level = $id_academic_level AND id_campus = $id_campus AND id_section = $id_section");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getGroupsByLevelCombination($id_level_combination)
    {
        $results = array();

        $query = $this->conn->query("SELECT groups.id_group,groups.group_code, groups.letter, groups.hebrew_group,lvl_comb.id_academic_area, aclvg.degree,CONCAT (colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS tutor_name
        FROM school_control_ykt.level_combinations AS lvl_comb
        INNER JOIN school_control_ykt.groups AS groups ON lvl_comb.id_campus = groups.id_campus AND lvl_comb.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels AS aclv ON lvl_comb.id_academic_level = aclv.id_academic_level
        INNER JOIN school_control_ykt.academic_levels_grade AS aclvg ON groups.id_level_grade = aclvg.id_level_grade AND lvl_comb.id_academic_level = aclvg.id_academic_level
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = groups.no_tutor
        WHERE lvl_comb.id_level_combination = '$id_level_combination'  AND groups.group_type_id = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getPeriodsByLevelCombination($id_level_combination)
    {
        $results = array();

        $query = $this->conn->query("SELECT percal.*
        FROM school_control_ykt.level_combinations AS lvc 
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_level_combination = lvc.id_level_combination
        WHERE 
        lvc.id_level_combination = '$id_level_combination'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    
}
