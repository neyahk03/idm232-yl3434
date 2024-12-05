<?php
error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

require_once 'includes/database.php';

// Get the search and filter input (if any)
$search = $_GET['search'] ?? ''; // Default to empty string if not set
$filters = $_GET['filter'] ?? []; // Default to an empty array if not set

// Clean up empty filter values (if any)
$filters = array_filter($filters);

// Build the SQL query dynamically
$filterQueryParts = [];
if (!empty($filters)) {
    // Create LIKE conditions for each selected filter
    foreach ($filters as $filter) {
        $filterQueryParts[] = "protein LIKE '%{$filter}%'";
    }
    // Combine filter conditions with OR
    $filterQuery = implode(" OR ", $filterQueryParts);
}

// Prepare the SQL query based on whether search or filters are applied
if (!empty($search) && !empty($filterQuery)) {
    // If both search and filters are provided, combine them in the WHERE clause
    $query = "SELECT * FROM recipes_test_run WHERE (title LIKE ? OR ingredients LIKE ? OR protein LIKE ?) AND ($filterQuery)";
    $stmt = $connection->prepare($query);
    $searchParam = '%' . $search . '%';
    $stmt->bind_param('sss', $searchParam, $searchParam, $searchParam);
} elseif (!empty($search)) {
    // If only search is provided, filter by search term
    $query = "SELECT * FROM recipes_test_run WHERE title LIKE ? OR ingredients LIKE ? OR protein LIKE ?";
    $stmt = $connection->prepare($query);
    $searchParam = '%' . $search . '%';
    $stmt->bind_param('sss', $searchParam, $searchParam, $searchParam);
} elseif (!empty($filterQuery)) {
    // If only filters are provided, filter by protein type
    $query = "SELECT * FROM recipes_test_run WHERE " . $filterQuery;
    $stmt = $connection->prepare($query);
} else {
    // If no search or filters are provided, fetch all recipes
    $query = "SELECT * FROM recipes_test_run";
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
<form action="example-4.php" method="get" class="search-form">
    <input type="text" name="search" placeholder="Search for recipes..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<!-- Filter Buttons -->
<div class="filter-buttons">
    <form action="example-4.php" method="get">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"> <!-- Preserve search term -->
        <input type="hidden" name="filter[]" value="Fish">
        <button type="submit" class="filter-btn <?php echo in_array('Fish', $filters) ? 'active' : ''; ?>">Fish</button>
    </form>

    <form action="example-4.php" method="get">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"> <!-- Preserve search term -->
        <input type="hidden" name="filter[]" value="Beef">
        <button type="submit" class="filter-btn <?php echo in_array('Beef', $filters) ? 'active' : ''; ?>">Beef</button>
    </form>

    <form action="example-4.php" method="get">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"> <!-- Preserve search term -->
        <input type="hidden" name="filter[]" value="Vegetarian">
        <button type="submit" class="filter-btn <?php echo in_array('Vegetarian', $filters) ? 'active' : ''; ?>">Vegetarian</button>
    </form>

    <form action="example-4.php" method="get">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"> <!-- Preserve search term -->
        <input type="hidden" name="filter[]" value="Chicken">
        <button type="submit" class="filter-btn <?php echo in_array('Chicken', $filters) ? 'active' : ''; ?>">Chicken</button>
    </form>

    <!-- Reset Button -->
    <form action="example-4.php" method="get">
        <button type="submit" class="reset-button">Reset Filters</button>
    </form>
</div>

<!-- Recipe Cards -->
<div class="recipe-cards">
    <?php if (count($recipes) > 0): ?>
        <?php foreach ($recipes as $recipe): ?>
            <div class="recipe-card">
                <img src="images/<?php echo htmlspecialchars($recipe['main_image']); ?>" alt="Recipe Image" class="recipe-image">
                <h2 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h2>
                <h3 class="recipe-subtitle"><?php echo htmlspecialchars($recipe['subtitle']); ?></h3>
                <p><strong>Protein: </strong> <?php echo htmlspecialchars($recipe['protein']); ?></p>
                <p><strong>Calories:</strong> <?php echo htmlspecialchars($recipe['calories']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recipes found matching your criteria.</p>
    <?php endif; ?>
</div>

</body>
</html>
