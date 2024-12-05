<?php
error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

require_once 'includes/database.php';

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
            <a class="logo-container" href="index.html">
            <img class="logo" src="images/logo.png" alt="logo">
            </a>
        </div>
        

        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="recipes.php">Recipes</a>
            <a href="help.php">Help</a>
        </div>
        

    </nav>

    <div class="recipe-detail">
        <!-- Recipe Title -->
        <h1><?php echo $recipe['title']; ?></h1>

        <!-- Recipe Image -->
        <img src="../images/<?php echo $recipe['main_image']; ?>" alt="Recipe Image" class="recipe-image">

        <!-- Recipe Information -->
        <p><strong>Subtitle:</strong> <?php echo $recipe['subtitle']; ?></p>
        <p><strong>Cooking Time:</strong> <?php echo $recipe['cook_time']; ?></p>
        <p><strong>Serving Size:</strong> <?php echo $recipe['serving_size']; ?></p>
        <p><strong>Protein:</strong> <?php echo $recipe['protein']; ?></p>
        <p><strong>Calories:</strong> <?php echo $recipe['calories']; ?></p>

        <!-- Recipe Description -->
        <p><strong>Description:</strong> <?php echo $recipe['description']; ?></p>

        <!-- Ingredients List -->
        <h2>Ingredients</h2>
        <ul>
            <?php
            $ingredients = explode('*', $recipe['ingredients']);
            foreach ($ingredients as $ingredient) {
                echo '<li>' . $ingredient . '</li>';
            }
            ?>
        </ul>

        <!-- Steps List -->
        <h2>Steps</h2>
        <div class="steps-container">
            <?php
            $steps = explode('*', $recipe['steps']);
            $images = explode('*', $recipe['steps_image']);

            foreach ($steps as $index => $step) {
                $stepParts = explode('^^', $step);

                

                if (count($stepParts) == 2) {
                    echo '<div class="step">';
                    echo '<strong>' . trim($stepParts[0]) . ':</strong> ';
                    echo '<p>' . trim($stepParts[1]) . '</p>';

                    if (isset($images[$index])) {
                        // Ensure that the image URL is valid
                        echo '<img src="images/' . $images[$index] . '" alt="Step Image' . ($index + 1) . 'image" class="step-image">';
                    }

                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>