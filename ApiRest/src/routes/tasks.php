<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// GET all tasks

$app->get('/api/users', function(Request $request, Response $response){


    $sql = "SELECT * FROM users";
    try{
        $db = new db();
        $db = $db->conecctiondb();
        $resultado = $db->query($sql);
        if($resultado->rowCount() > 0){
            $clientes = $resultado->fetchAll(PDO::FETCH_OBJ);
                echo json_encode($clientes);
        }else{
            echo json_decode("no existen usuario en la  base de datos");
        }
        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMassage().'}';
    }
});

$app->post('/api/users/new', function(Request $request, Response $response){

    $names = $request->getParam('names');
    $lastname = $request->getParam('lastname');
    $id_number = $request->getParam('id_number');
    $birth = $request->getParam('birth');
    $city  = $request->getParam('city');
    $neighborhood = $request->getParam('neighborhood');
    $phone = $request->getParam('phone');




    $sql = "INSERT INTO users (names, lastname, id_number, birth, city, neighborhood, phone ) VALUES
            (:names, :lastname, :id_number, :birth, :city, :neighborhood, :phone )"; 
    try{
        $db = new db();
        $db = $db->conecctiondb();
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':names', $names);
        $resultado->bindParam(':lastname', $lastname);
        $resultado->bindParam(':id_number', $id_number);
        $resultado->bindParam(':birth', $birth);
        $resultado->bindParam(':city', $city);
        $resultado->bindParam(':neighborhood', $neighborhood);
        $resultado->bindParam(':phone', $phone);


        $resultado->execute();

        echo json_encode("users already Exists.");

        $resultado = null;
        $db = null;
    }catch(PDOException $e){ 
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
});

 function listCitas(){
    $citas=array("citas"=>$this->citas_model->listCitas());
    http_response_code(200);
    header('Content-type: application/json');
    echo json_encode($citas);
}


$app->put('/api/users/modificarusuario/{id}', function(Request $request, Response $response){
    $id_usuarios = $request->getAttribute('id');
    
    $names = $request->getParam('names');
    $lastname = $request->getParam('lastname');
    $id_number = $request->getParam('id_number');
    $birth = $request->getParam('birth');
    $city  = $request->getParam('city');
    $neighborhood = $request->getParam('neighborhood');
    $phone = $request->getParam('phone');

    $sql = "UPDATE users SET
            names = :names,
            lastname = :lastname,
            id_number = :id_number,
            birth = :birth,
            city = :city,
            neighborhood = :neighborhood,
            phone = :phone
            
            
            WHERE  id = $id_usuarios";
    
    try{
        $db = new db();
        $db = $db->conecctiondb();
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':names', $names);
        $resultado->bindParam(':lastname',$lastname);
        $resultado->bindParam(':id_number',$id_number);
        $resultado->bindParam(':birth',$birth);
        $resultado->bindParam(':city',$city);
        $resultado->bindParam(':neighborhood',$neighborhood);
        $resultado->bindParam(':phone',$phone);
        
        

        $resultado->execute();
        
        echo json_encode("Usuario modificado");


        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMassage().'}';
    }
});

$app->delete('/api/users/delete/{id}', function(Request $request, Response $response){
    $id_cliente = $request->getAttribute('id');
    $sql = "DELETE FROM users WHERE  id = $id_cliente";
    
    try{
        $db = new db();
        $db = $db->conecctiondb();
        $resultado = $db->prepare($sql);
        $resultado->execute();
        
        if($resultado->rowCount() > 0){
            echo json_encode("tasks removed");
        }else{
            echo json_encode("There is no tasks with this id");
        }
       
        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMassage().'}';
    }
});


