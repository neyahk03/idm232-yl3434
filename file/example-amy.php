<?php
error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

require_once 'includes/database.php';

$statement = $connection->prepare('SELECT * FROM recipes_test_run');
$statement->execute();
$recipes = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

// Get the search input (if any)
$search = $_GET['search'] ?? ''; // Default to empty string if not set
$filter = $_GET['filter'] ?? ''; // Default to empty string if not set


// Prepare a SQL query with a WHERE clause for filtering
// if (!empty($search)) {
//     // Prepare SQL query with LIKE for partial matching
//     $statement = $connection->prepare('SELECT * FROM recipes_test_run WHERE title LIKE ? OR ingredients LIKE ? OR protein LIKE ?');
//     $searchParam = '%' . $search . '%'; // Add wildcards for partial matching
//     $statement->bind_param('sss', $searchParam, $searchParam, $searchParam); // Bind the search term
// } else {
//     // If no search term, fetch all recipes
//     $statement = $connection->prepare('SELECT * FROM recipes_test_run');
// }

// Prepare a SQL query with a WHERE clause for filtering
if (!empty($search) && !empty($filter)) {
    // Filter by both search term and category
    $statement = $connection->prepare('SELECT * FROM recipes_test_run WHERE (title LIKE ? OR ingredients LIKE ? OR protein LIKE ?) AND protein = ?');
    $searchParam = '%' . $search . '%'; // Add wildcards for partial matching
    $statement->bind_param('ssss', $searchParam, $searchParam, $searchParam, $filter);
} elseif (!empty($search)) {
    // Filter by search term only
    $statement = $connection->prepare('SELECT * FROM recipes_test_run WHERE title LIKE ? OR ingredients LIKE ? OR protein LIKE ?');
    $searchParam = '%' . $search . '%';
    $statement->bind_param('sss', $searchParam, $searchParam, $searchParam);
} elseif (!empty($filter)) {
    // Filter by protein category only
    $statement = $connection->prepare('SELECT * FROM recipes_test_run WHERE protein = ?');
    $statement->bind_param('s', $filter);
} else {
    // If no search term or filter, fetch all recipes
    $statement = $connection->prepare('SELECT * FROM recipes_test_run');
}


// Execute the statement
$statement->execute();

// Fetch the result
$recipes = $statement->get_result()->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Cards</title>
    <link rel="stylesheet" href="css/example-amy.css">
</head>
<body>

<!-- Search Form -->
<form action="example-amy.php" method="get" class="search-form">
    <input type="text" name="search" placeholder="Search for recipes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<!-- filter button -->
<div class="filter-button">
    <form action="example-amy.php" method="get">
        <input type="hidden" name="filter" value="Fish">
        <button type="submit">Fish</button>
    </form>

    <form action="example-amy.php" method="get">
        <input type="hidden" name="filter" value="Beef">
        <button type="submit">Beef</button>
    </form>

    <form action="example-amy.php" method="get">
        <input type="hidden" name="filter" value="Vegetarian">
        <button type="submit">Vegetarian</button>
    </form>

    <form action="example-amy.php" method="get">
            <input type="hidden" name="filter" value="Chicken" >
            <button type="submit"> Chicken</button>
    </form>

    <form action="example-amy.php" method="get">
            <input type="hidden" name="filter" value="Turkey" >
            <button type="submit"> Turkey</button>
    </form>

    <form action="example-amy.php" method="get">
            <input type="hidden" name="filter" value="Pork" >
            <button type="submit">Pork</button>
    </form>

    <!-- Reset Button -->
    <form action="example-amy.php" method="get">
        <button type="submit" class="reset-button">Reset Filters</button>
    </form>
</div>

<div class="recipe-cards">
    <?php foreach ($recipes as $recipe): ?>
        <div class="recipe-card">
            <!-- Recipe Image -->
            <img src="images/<?php echo ($recipe['main_image']); ?>" alt="Recipe Image" class="recipe-image">

            <!-- Recipe Title and Subtitle -->
            <h2 class="recipe-title"><?php echo ($recipe['title']); ?></h2>
            <h3 class="recipe-subtitle"><?php echo ($recipe['subtitle']); ?></h3>
            <a href="recipe.php?id=<?php echo $recipe['id']; ?>"> View Recipe</a>

            <!-- Recipe Information -->
            <p><strong>Cooking Time:</strong> <?php echo ($recipe['cook_time']); ?></p>
            <p><strong>Serving Size:</strong> <?php echo ($recipe['serving_size']); ?></p>
            <p><strong>Protein: </strong> <?php echo ($recipe['protein']); ?> </p>
            <p><strong>Calories:</strong> <?php echo htmlspecialchars($recipe['calories']); ?> </p>

            <!-- Recipe Description -->
            <p class="recipe-description"><?php echo ($recipe['description']); ?></p>

            <!-- Ingredients List -->
            <h4>Ingredients:</h4>
            <ul class="ingredients-list">
                <?php
                $ingredients = explode('*', $recipe['ingredients']); // Assuming ingredients are stored as a comma-separated list
                foreach ($ingredients as $ingredient):
                ?>
                    <li><?php echo ($ingredient); ?></li>
                <?php endforeach; ?>
            </ul>

            <!-- Steps List -->
            <h4>Steps:</h4>
            <div class="steps-container">
                <?php
                // Exploding the steps by '*' to get each individual step
                $steps = explode('*', $recipe['steps']);
                
                // Loop through each step
                foreach ($steps as $step):
                    if (trim($step)): // Ensure there are no empty steps
                        // Splitting the title and description by '^^'
                        $stepParts = explode('^^', $step);
                        
                        // Check if we have both a title and a description
                        if (count($stepParts) == 2):
                            $stepTitle = trim($stepParts[0]); // Title part of the step
                            $stepDescription = trim($stepParts[1]); // Description part of the step
                            ?>
                            <div class="step">
                                <strong><?php echo ($stepTitle); ?>:</strong> 
                                <p><?php echo ($stepDescription); ?></p>
                            </div>
                            <?php
                        endif;
                    endif;
                endforeach;
                ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (count($recipes) > 0): ?>
    <!-- Display recipes as before -->
    <?php else: ?>
        <p>No recipes found matching your search criteria.</p>
    <?php endif; ?>
</div>

</body>
</html>