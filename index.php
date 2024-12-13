<?php
error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

// require_once 'includes/database.php';

require_once 'includes/db.php';

$randomStatement = $connection->prepare('SELECT * FROM recipes_test_run ORDER BY RAND() LIMIT 3');
$randomStatement->execute();
$randomRecipes = $randomStatement->get_result()->fetch_all(MYSQLI_ASSOC); 


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The WOK - Homepage</title>
    <link rel="icon" href="images/logo.png" />
    <link rel="stylesheet" href="css/index.css">

    
</head>

<body>

    <nav>
        <div class="top">
            <h1>The</h1>
            <a class="logo-container" href="index.php">
            <img class="logo" src="images/logo.png" alt="logo">
            </a>
        </div>
        
        <div class="btn_menu">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
        </div>

        

        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="recipes.php">Recipes</a>
            <a href="help.php">Help</a>
        </div>
        

    </nav>

    <div class="menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="help.php">Help</a></li>
        </ul>
    </div> 

    <h1 class="title">Welcome to our home</h1>
    <h3>Try our most popular recipes</h3>


    <div class="card-container">
        <?php if (!empty($randomRecipes)): ?>
            <?php foreach ($randomRecipes as $randomRecipe): ?>
                <a href="detail.php?id=<?php echo ($randomRecipe['id']); ?>">
                    <div class="card">
                        <!-- Recipe Image -->
                        <img src="images/<?php echo ($randomRecipe['main_image']); ?>" alt="Recipe Image" class="pic">
                        <div class="name">
                            <!-- Recipe Title and Subtitle -->
                        <h2 class="recipe-title"><?php echo ($randomRecipe['title']); ?></h2>
                        <p class="recipe-subtitle"><?php echo ($randomRecipe['subtitle']); ?></p>
                        </div>
                        
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No recipes found.</p>
        <?php endif; ?>
    </div>

    <a class="view-all" href="recipes.php">View all recipes</a>


    <script src="script/help.js"></script>
</body>
</html>