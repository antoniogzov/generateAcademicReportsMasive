<?php
class Connection
{
    private $db_name = "families_ykt";

    /*private $db_host = "192.168.1.251";*/
    /* Server en tiempo real */
   private $db_port = 3306;
    private $db_host = "servykt.homeip.net";
    private $db_user = "usuario";
    private $db_pass = "UsuarioRemoto2020";
     /*
      private $db_host = "localhost";
    private $db_user = "root";
    private $db_pass = "Admon2022a*";
    */

    /* Server de pruebas */
    /* private $db_port = 3307;
    private $db_user = "developer";
    private $db_pass = "Ykt2021a";
    private $db_host = "servykt.homeip.net:3307"; */

    private $db_conn;

    public function db_conn()
    {
        try {
            $this->db_conn = new PDO("mysql:host={$this->db_host}; dbname={$this->db_name}; charset=utf8", $this->db_user, $this->db_pass);
            $this->db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected";
        } catch (PDOException $e) {
            echo "ERROR" . $e->getMessage();
        }
        return $this->db_conn;
    }
}