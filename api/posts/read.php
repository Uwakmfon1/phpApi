<?php 

header('Access-Control-Allow-Origin');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Recipe.php'; //models\Recipe.php

// instantiate DB & connect
$database = new Database();
$db=$database->connect();

// instantiate blog post object
$recipe = new Recipe($db);

// recipe Query
$result = $recipe->read();

// get row count
$num = $result->rowCount();

// check if any recipe
if($num > 0)
{
    $recipes_arr = array();
    $recipes_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $recipes_item = array(
            'id'=>$id,
            'title'=>$title,
            'ingredients'=>$ingredients,
            'servings'=>$servings,
            'instructions'=>$instructions
        );

        // push to "data"
        array_push($recipes_arr['data'], $recipes_item);
    
    }
    echo json_encode($recipes_arr);

} else {
    // no posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}

