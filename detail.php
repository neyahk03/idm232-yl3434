<?php
error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

// require_once 'includes/database.php';

require_once 'includes/db.php';

// Check if an ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No recipe ID provided.";
    exit;
}

$recipe_id = intval($_GET['id']); // Ensure the ID is an integer

// Prepare and execute a SQL query to fetch recipe details
$statement = $connection->prepare('SELECT * FROM recipes_test_run WHERE id = ?');
$statement->bind_param('i', $recipe_id);
$statement->execute();

$recipe = $statement->get_result()->fetch_assoc();

// Check if the recipe exists
if (!$recipe) {
    echo "Recipe not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/detail.css">
    <link rel="icon" href="images/logo.png" />
    <title><?php echo $recipe['title']; ?> - Recipe Details</title>
</head>

<body>

    <!-- navbar -->
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


    <div class="back-to-top" id="back-to-top">
        <img src="images/top-icon.png" alt="back to top button">
    </div>

    

    <div class="overview">
        <!-- recipe image -->

        <div class="recipe-image">
            <img src="images/<?php echo $recipe['main_image']; ?>" alt="Recipe Image" class="recipe-image">
        </div>
            

            <div class="title-container">
                <h1 class="title"><?php echo $recipe['title']; ?></h1>
                <h3 class="subtitle"><?php echo $recipe['subtitle']; ?></h3>
            </div>
            

            <!-- Recipe Description -->
            <p class="description"><?php echo $recipe['description']; ?></p>

            <!-- Recipe Information -->

            <div class="info">
                <div class="cook-time">
                    <div class="icon"><img src="images/clock-orange-icon.png" alt="clock icon"></div>
                    <p><?php echo $recipe['cook_time']; ?></p>
                </div>

                <div class="serving-size">
                    <div class="icon"><img src="images/serving-orange-icon.png" alt="serving icon"></div>
                    <p><?php echo $recipe['serving_size']; ?></p>
                </div>

                <div class="protein">
                    <div class="icon"><img src="images/meat-orange-icon.png" alt="meat icon"></div>
                    <p><?php echo $recipe['protein']; ?></p>
                </div>

                <div class="calories">
                    <div class="icon"><img src="images/calories-orange-icon.png" alt="calories icon"></div>
                    <p><?php echo $recipe['calories']; ?></p>

                </div>
            
            </div>

            
            

    </div>


    <div class="ingredients">
        <h2>Ingredients</h2>

        <div class="ingredients-image">
            <img src="images/<?php echo $recipe['ingredients_image']; ?>" alt="Ingredient image">

        </div>

        <!-- Ingredients List -->

        <ul>
            <?php
            $ingredients = explode('*', $recipe['ingredients']);

            foreach ($ingredients as $ingredient) {
                echo '<li>' . $ingredient . '</li>';
            }
            ?>
        </ul>
    </div>

    

    <div class="steps-detail">

        <!-- Steps List -->
        <h2>Instruction</h2>


        <?php
        $steps = explode('*', $recipe['steps']);
        $steps_images = explode('*', $recipe['steps_image']);

        foreach ($steps as $index => $step) {
            $stepParts = explode('^^', $step);

         // Display the image for the current step
        echo '<div class="steps-image">';
        if (isset($steps_images[$index])) {
        echo '<img src="images/' . htmlspecialchars($steps_images[$index]) . '" alt="Step Image ' . ($index + 1) . '">';
        }
        echo '</div>';

        // Display the instruction for the current step
        if (count($stepParts) == 2) {
            echo '<div class="instruction">';
            echo '<h3>' . ($index + 1). '. ' . trim($stepParts[0]) . ':</h3>';
            echo '<p>' . trim($stepParts[1]) . '</p>';
            echo '</div>';
        }
    }
    ?>


                    


   
    </div>

    <footer>
        <div>
        <p>&copy; 2024 Yen Luong - The WOK</p>
        </div>
    </footer>

    <script src="script/index.js"></script>
    <script src="script/help.js"></script>

</body>
</html>