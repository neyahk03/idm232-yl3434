<?php

error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

require_once 'includes/database.php';

// Get the search input (if any)
$search = $_GET['search'] ?? ''; // Default to empty string if not set

// Prepare a SQL query with a WHERE clause for filtering
if (!empty($search)) {
    // Prepare SQL query with LIKE for partial matching
    $statement = $connection->prepare('SELECT * FROM recipes_test_run WHERE title LIKE ? OR ingredients LIKE ? OR protein LIKE ?');
    $searchParam = '%' . $search . '%'; // Add wildcards for partial matching
    $statement->bind_param('sss', $searchParam, $searchParam, $searchParam); // Bind the search term
} else {
    // If no search term, fetch all recipes
    $statement = $connection->prepare('SELECT * FROM recipes_test_run');
}

$statement->execute();
$recipes = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing</title>
    <link rel="stylesheet" href="css/testing.css">
</head>
<body>

    <!-- Search Form -->
    <form class="search" action="example-3.php" method="get" class="search-form">
        <input type="text" name="search" placeholder="Search for recipes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>

    <div class="container">
        <?php if (count($recipes) > 0): ?>
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <div class="pic">
                        <!-- Optionally, add an image here if your data contains image paths -->
                    </div>
                    <img src="" alt="">
                    <h2><?php echo $recipe['title']; ?></h2>
                    <p><?php echo $recipe['subtitle']; ?></p>
                    <p><strong>Protein: </strong> <?php echo htmlspecialchars($recipe['protein']); ?> </p>
                    <a href="recipe.php?id=<?php echo $recipe['id']; ?>"> View Recipe</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No recipes found matching "<?php echo htmlspecialchars($search); ?>".</p>
        <?php endif; ?>
    </div>

</body>
</html>
