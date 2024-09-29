<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once '../../config/Database.php';
include_once '../../models/Recipe.php'; 

// instantiate DB & connect
$database = new Database();
$db=$database->connect();

// instantiate blog post object
$recipe = new Recipe($db);

// get ID
$recipe->id = isset($_GET['id']) ? $_GET['id'] : die();


// calling read_single to get a recipe
$recipe->read_single();

// create array
$recipe_arr = array(
    'id' => $recipe->id,
    'user_id' => $recipe->user_id,
    'title'=> $recipe->title,
    'ingredients' => $recipe->ingredients,
    'servings'=> $recipe->servings,
    'instructions' => $recipe->instructions
);

// make json
print_r(json_encode($recipe));