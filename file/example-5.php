<?php
error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

require_once 'includes/database.php';

// Get the search input (if any)
$search = $_GET['search'] ?? ''; // Default to empty string if not set

// Get the filter input (if any)
$filters = $_GET['filter'] ?? []; // Default to an empty array if not set
$filters = array_filter($filters); // Clean up any empty filters

// Build the SQL query dynamically
$query = "SELECT * FROM recipes_test_run";

// If search term is provided
if (!empty($search) && !empty($filters)) {
    // If both search and filters are provided, combine them in the WHERE clause
    $query .= " WHERE (title LIKE ? OR ingredients LIKE ? OR protein LIKE ?) AND (" . implode(" OR ", array_fill(0, count($filters), "protein LIKE ?")) . ")";
    $params = array_merge(
        array_fill(0, 3, '%' . $search . '%'),  // Search params for title, ingredients, and protein
        array_map(function ($filter) { return "%{$filter}%"; }, $filters)  // Filter params
    );
    $types = str_repeat('s', count($params));
    $stmt = $connection->prepare($query);
    $stmt->bind_param($types, ...$params);
} elseif (!empty($search)) {
    // If only search is provided, filter by search term
    $query .= " WHERE title LIKE ? OR ingredients LIKE ? OR protein LIKE ?";
    $stmt = $connection->prepare($query);
    $searchParam = '%' . $search . '%';
    $stmt->bind_param('sss', $searchParam, $searchParam, $searchParam);
} elseif (!empty($filters)) {
    // If only filters are provided, filter by protein category
    $query .= " WHERE " . implode(" OR ", array_fill(0, count($filters), "protein LIKE ?"));
    $params = array_map(function ($filter) { return "%{$filter}%"; }, $filters);
    $stmt = $connection->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
} else {
    // If no search term or filter, fetch all recipes
    $stmt = $connection->prepare($query);
}

// Execute the statement and get results
$stmt->execute();
$results = $stmt->get_result();
$recipes = $results->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Cards</title>
    <link rel="stylesheet" href="css/example-4.css">
</head>
<body>

<!-- Search Form -->
<form action="example-5.php" method="get" class="search-form">
    <input type="text" name="search" placeholder="Search for recipes..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<!-- Filter Buttons -->
<div class="filter-buttons">
    <form action="example-5.php" method="get">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"> <!-- Preserve search term -->
        <input type="hidden" name="filters" value="Fish">
        <button type="submit" class="filter-btn <?php echo in_array('Fish', $filters) ? 'active' : ''; ?>">Fish</button>
    </form>

    <form action="example-5.php" method="get">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"> <!-- Preserve search term -->
        <input type="hidden" name="filters" value="Beef">
        <button type="submit" class="filter-btn <?php echo in_array('Beef', $filters) ? 'active' : ''; ?>">Beef</button>
    </form>

    <form action="example-5.php" method="get">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"> <!-- Preserve search term -->
        <input type="hidden" name="filters" value="Vegetarian">
        <button type="submit" class="filter-btn <?php echo in_array('Vegetarian', $filters) ? 'active' : ''; ?>">Vegetarian</button>
    </form>

    <!-- Reset Button -->
    <form action="example-5.php" method="get">
        <button type="submit" class="reset-button">Reset Filters</button>
    </form>
</div>

<!-- Recipe Cards -->
<div class="recipe-cards">
    <?php if (count($recipes) > 0): ?>
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
    <?php else: ?>
        <p>No recipes found matching your criteria.</p>
    <?php endif; ?>
</div>

</body>
</html>

