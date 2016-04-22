<?php
class Asignatura{

  public static $tablename = "asignaturas";
  private $asi_id = "";
  private $asi_nombre = "";
  private $asi_fecha_reg = "";
  private $asi_fecha_ult_mod = "";
  private $usu_id = "";
  private $are_id = "";
  private $ins_id = "";
  private $asignatura = array(
    '_id' => 'asi_id',
    't_nombre' => 'asi_nombre',
    't_fecha_ult' => 'asi_fecha_ult_mod',
    't_are_id' => 'are_id'
  );

  public function Asignatura(){
    $this->asi_id = "";
    $this->asi_nombre = "";
    $this->asi_fecha_reg = "";
    $this->asi_fecha_ult_mod = "";
    $this->usu_id = "";
    $this->are_id = "";
    $this->ins_id = "";
  }

  public function getDatos(){
    return $this->asignatura;
  }

  public function add($datos){

    $ins_id = $_SESSION['datos_usuario']['ins_id'];
    $usu_id = $_SESSION['datos_usuario']['id'];

    if ($ins_id == NULL || $ins_id == "" || $usu_id == NULL || $usu_id == "") return -1;

    $param = array();
    $sql = "INSERT INTO " . self::$tablename . " ( ";
    $datosR = "";

    foreach($datos as $clave => $valor){
      $sql.= $clave . "   , ";
      $datosR.= ":" . $clave . ",";
      $param[":" . $clave] = $valor;
    }

    $sql.= "asi_fecha_reg";
    $sql.= ",usu_id";
    $sql.= ",ins_id";
    $datosR.= "now()";
    $datosR.= "," . $usu_id;
    $datosR.= "," . $ins_id;
    $sql = $sql . ") VALUES (" . $datosR . ")";
    $resul = Ejecucion::insertar($sql, $param);

    return $resul;
  }

  public function modificar($datos){

    $ins_id = $_SESSION['datos_usuario']['ins_id'];

    if ($ins_id == NULL || $ins_id == "") return -1;

    $param = array();
    $sql = "UPDATE " . self::$tablename . " SET ";

    foreach($datos as $clave => $valor){
      $sql.= $clave . " = :" . $clave . " , ";
      $param[":" . $clave] = $valor;
    }

    $sql = substr($sql, 0, strlen($sql) - 2);
    $sql.= " WHERE  ins_id = :ins_id and asi_id = :asi_id  ";
    $param[":ins_id"] = $ins_id;
    $param[":asi_id"] = $datos['asi_id'];
    $resul = Ejecucion::insertar($sql, $param);
    return $resul;
  }

  public function getById($id){

    $ins_id = $_SESSION['datos_usuario']['ins_id'];

    if ($ins_id == NULL || $ins_id == "") return -1;

    $sql = "select * from " . self::$tablename . " where asi_id = :asi_id AND ins_id = :ins_id ";
    $resul = Ejecucion::buscar($sql, array(
      ':asi_id' => $id,
      ':ins_id' => $ins_id
    ));

    if (!($resul == null || $resul === "")){
      $resul = json_encode($resul);
    }

    return $resul;
  }

  public function getAll(){

    $ins_id = $_SESSION['datos_usuario']['ins_id'];
    
    if ($ins_id == NULL || $ins_id == "") return -1;
    $sql = "select * from " . self::$tablename . " WHERE ins_id =:ins_id";
    $resul = Ejecucion::buscar($sql, array(
      ':ins_id' => $ins_id
    ));
    if (!($resul == null || $resul === "")){
      $resul = json_encode($resul);
    }

    return $resul;
  }

  public function getAsignaturasDocente(){
    $sql = "SELECT * FROM asignatura_docente asign_doc
            INNER JOIN usuarios users ON users.usu_id = asign_doc.id_docente
            INNER JOIN asignaturas asign ON asign.asi_id = asign_doc.id_asignatura";

    $resul =Ejecucion::buscar($sql, array());
    if(!($resul == null || $resul === "" )){
      $resul = json_encode($resul);                         
    }
    return $resul; 
  }

  public function getAsignaturasPorDocente($idDocente){

    $sql = "SELECT a.asi_id, a.asi_nombre FROM colegio.asignatura_docente ad INNER JOIN usuarios u ON ad.id_docente = u.usu_id INNER JOIN asignaturas a ON ad.id_asignatura = a.asi_id  WHERE id_docente = ".$idDocente;

    $resul = Ejecucion::buscar($sql, array());
    if(!($resul == null || $resul === "" )){
      $resul = json_encode($resul);                         
    }
    return $resul; 
  }

  public function obtenerEstudiantesPorGrupo($idGrupo, $idDocente){

    #return 'idGrupo'. $idGrupo. ' idDoc'. $idDocente;

    $sql = "SELECT eg.id, eg.id_estudiante, us.usu_id, us.usu_nombre, us.usu_apellido1, us.usu_apellido2
    FROM estudiante_grupo eg 
    INNER JOIN grupos g ON eg.id_grupo = g.gru_id 
    INNER JOIN instituciones i ON g.ins_id = i.ins_id 
    INNER JOIN usuarios us ON eg.id_estudiante = us.usu_id 
    WHERE eg.id_grupo = '".$idGrupo."' GROUP BY eg.id_estudiante";

    $resul = Ejecucion::buscar($sql, array());

    if(!($resul == null || $resul === "" )){
      $resul = json_encode($resul);                         
    }

    return $resul; 
  }
  public function getGruposPorDocente($idDocente){

    $sql = "SELECT g.gru_id, g.gru_nombre FROM grupos g INNER JOIN instituciones i ON g.ins_id = i.ins_id INNER JOIN usuarios u ON u.ins_id = i.ins_id WHERE u.usu_id = ".$idDocente;

    $resul = Ejecucion::buscar($sql, array());
    if(!($resul == null || $resul === "" )){
      $resul = json_encode($resul);                         
    }
    return $resul; 
  }

  public function getCombo()
    {
    $ins_id = $_SESSION['datos_usuario']['ins_id'];
    if ($ins_id == NULL || $ins_id == "") return -1;
    $sql = "select asi.asi_id ,asi.asi_nombre,CASE  WHEN ar.are_id IS NULL THEN 0  ELSE ar.are_nombre END,CASE WHEN ar.are_id IS NULL THEN 'Sin Àrea' ELSE ar.are_nombre END from " . self::$tablename . " asi LEFT JOIN areas ar  ON ar.are_id=asi.are_id   WHERE asi.ins_id =:ins_id";
    $resul = Ejecucion::buscar($sql, array(
      ':ins_id' => $ins_id
    ));
    if (!($resul == null || $resul === ""))
      {
      $resul = json_encode($resul);
      }

    return $resul;
    }

  public function getLike($q)
    {
    $ins_id = $_SESSION['datos_usuario']['ins_id'];
    if ($ins_id == NULL || $ins_id == "") return -1;
    $sql = "select * from " . self::$tablename . " where asi_nombre like '%:busqueda%' AND ins_id=:ins_id";
    $resul = Ejecucion::buscar($sql, array(
      ':busqueda' => $q,
      ':ins_id' => $ins_id
    ));
    if (!($resul == null || $resul === ""))
      {
      $resul = json_encode($resul);
      }

    return $resul;
    }
  }

?>