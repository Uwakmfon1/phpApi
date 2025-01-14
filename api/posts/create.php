<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With');


include_once '../../config/Database.php';
include_once '../../models/Recipe.php'; 


// instantiate DB & connect
$database = new Database();
$db=$database->connect();

// instantiate recipe post object
$recipe = new Recipe($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$recipe->user_id = $data->user_id;
$recipe->title = $data->title;
$recipe->ingredients = $data->ingredients;
$recipe->servings = $data->servings;
$recipe->instructions = $data->instructions;

// create Post
if($recipe->create()){
    echo json_encode(
        array('message'=>'Post Created')
    );
}else{
    echo json_encode(
        array('message'=>'Post not created')
    );
}