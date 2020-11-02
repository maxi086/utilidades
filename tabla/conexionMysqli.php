<?php 

Class Conexion{
    private $host = 'localhost'; 
    private $user = 'root'; 
    private $pass = ''; 
    private $data = 'test';

    /**
     * Recibe una query y los parametros y devuelve un array con los resultados
     *
     * @param [String] $query Esla cuery armada con signos '?'
     * @param [array] $parametros con los valores {nombre,tipo,dato} el tipo {s,i}
     * @return $respuesta["codigo","mensaje","resultado"]
     */
    public function selectSqlToArray($query,$tipos, $parametros){
        $respuesta["codigo"] = 0;
        $respuesta["mensaje"] = "";
        $respuesta["resultado"] = null;

        try{
            $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->data); 
            /* check connection */ 
            if (mysqli_connect_errno()) { 
                $respuesta["codigo"] = 1;
                $respuesta["mensaje"] = mysqli_connect_error();
               
            }else{
                // "select nombre,apellido from usuarios where id = ?"
                if ($stmt = $mysqli->prepare($query)) { 
                    
                    // BIND BASICO
                    //$r = $stmt->bind_param('ss', $parametro1,$parametro2);

                    //BIND DINAMICO                     
                    if(count($parametros) > 0)
                        if($tipos&&$parametros)
                        {
                            $bind_names[] = $tipos;
                            for ($i=0; $i<count($parametros);$i++) 
                            {
                                $bind_name = 'bind' . $i;
                                $$bind_name = $parametros[$i];
                                $bind_names[] = &$$bind_name;
                            }
                            $return = call_user_func_array(array($stmt,'bind_param'),$bind_names);
                           
                        }

                    
                    $r=$stmt->execute(); 
                    //var_dump($r);
                    
                    //BIND DINAMICO DEL RESULTADO
                    $meta = $stmt->result_metadata(); 
                    while ($field = $meta->fetch_field()) 
                    { 
                        $params[] = &$row[$field->name]; 
                    } 
            
                    call_user_func_array(array($stmt, 'bind_result'), $params); 
            
                    while ($stmt->fetch()) { 
                        foreach($row as $key => $val) 
                        { 
                            $c[$key] = $val; 
                        } 
                        $result[] = $c; 
                    } 
            
                    $stmt->close(); 
                }else{
                    $respuesta["codigo"] = "1";
                    $respuesta["mensaje"] = "Error de sintaxis";
                }
                $mysqli->close();

            }
        
        }catch(Exception $e){
            $respuesta["codigo"] = 1;
            $respuesta["mensaje"] = "Excepcion no controlada";
            $respuesta["resultado"] = isset($result)?$result:null;
            
        }

        $respuesta["resultado"] = isset($result)?$result:null;
        return  $respuesta;

    }

    /**
     * insertar un Registro
     *
     * @param [String] $query
     * @param [String] $tipos {isdb}
     * @param [array] $parametros
     * @return $respuesta["codigo","mensaje","id_insertado"]
     */
    public function insertSimple($query,$tipos,$parametros){
        $respuesta["codigo"] = 0;
        $respuesta["mensaje"] = "";
        $respuesta["id_insertado"] = null;

        try{
            $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->data); 
            /* check connection */ 
            if (mysqli_connect_errno()) { 
                $respuesta["codigo"] = 1;
                $respuesta["mensaje"] = mysqli_connect_error();
               
            }else{
                // "select nombre,apellido from usuarios where id = ?"
                if ($stmt = $mysqli->prepare($query)) { 
                    
                    // BIND DINAMICO DE TIPOS Y PARAMETROS
                    if($tipos&&$parametros)
                    {
                        $bind_names[] = $tipos;
                        for ($i=0; $i<count($parametros);$i++) 
                        {
                            $bind_name = 'bind' . $i;
                            $$bind_name = $parametros[$i];
                            $bind_names[] = &$$bind_name;
                        }
                        $return = call_user_func_array(array($stmt,'bind_param'),$bind_names);
                    }
                    
                    $r=$stmt->execute(); 
                    $id_insertado=$stmt->insert_id;
                    $respuesta["mensaje"] = "Exito Filas Afectadas: ".$stmt->affected_rows;

           
                    $stmt->close(); 
                }else{
                    $respuesta["codigo"] = 1;
                    $respuesta["mensaje"] = "Error de sintaxis";
                }
                $mysqli->close();

            }
        
        }catch(Exception $e){
            $respuesta["codigo"] = 1;
            $respuesta["mensaje"] = "Excepcion no controlada";
            $respuesta["id_insertado"] = isset($id_insertado)?$id_insertado:null;
            
        }

        $respuesta["id_insertado"] = isset($id_insertado)?$id_insertado:null;
        return  $respuesta;

    }

}
