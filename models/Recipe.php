<?php 
class Recipe{
    private $conn;
    private $table='recipes';

    public $id;
    public $user_id;
    public $title;

    public $ingredients;
    public $servings;
    public $instructions;
    public $created_at;
    public $updated_at;

// constructor with db
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function read()
    {
        // create query
        $query = 'SELECT
            r.id,
            r.title,
            r.ingredients,
            r.servings,
            r.instructions,
            r.created_at
            FROM 
            '.$this->table.' r
            ORDER BY 
            r.created_at DESC
        ';

        // create prepared statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt; 
    }

// get single Recipes
public function read_single()
{
      // create query
      $query = 'SELECT 
        r.id, 
        r.user_id,
        r.title,
        r.ingredients, 
        r.servings, 
        r.instructions, 
        r.created_at
        FROM 
      '.$this->table.' r
      LEFT JOIN
        users u ON r.user_id = u.id
      WHERE 
      r.id = ?
      LIMIT 0,1
  ';

//   prepare statement
$stmt = $this->conn->prepare($query);

// Bind ID
$stmt->bindParam(1, $this->id);

   // Execute query
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$this->user_id = $row['user_id'];
$this->title = $row['title'];
$this->ingredients = $row['ingredients'];
$this->servings = $row['servings'];
$this->instructions = $row['instructions'];

}

// create post
public function create()
{
    $query = 'INSERT INTO ' .$this->table.'
    SET
    user_id = :user_id,
    title = :title,
    ingredients=:ingredients,
    servings=:servings,
    instructions = :instructions,
    created_at = NOW(),
    updated_at = NOW()';

    // prepared statement
    $stmt = $this->conn->prepare($query);


    // sanitize data
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->ingredients=htmlspecialchars(strip_tags($this->ingredients));
    $this->servings=htmlspecialchars(strip_tags($this->servings));
    $this->instructions=htmlspecialchars(strip_tags($this->instructions));
    // $this->created_at=htmlspecialchars(strip_tags());
    // $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));

    // replace the comma separated value
    $this->ingredients = str_replace(',',' | ',$this->ingredients );

    // Bind data
    $stmt->bindParam(':user_id',$this->user_id);
    $stmt->bindParam(':title',$this->title);
    $stmt->bindParam(':ingredients',$this->ingredients);
    $stmt->bindParam(':servings',$this->servings);
    $stmt->bindParam(':instructions',$this->instructions);

    // Execute Query
    if($stmt->execute()){
        return true;
    }

    // print error if something went wrong
    printf("Error: %s.\n",$stmt->error);
    return false;

}

}