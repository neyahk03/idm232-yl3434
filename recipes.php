<?php
error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

// require_once 'includes/database.php';

require_once 'includes/db.php';

// $statement = $connection->prepare('SELECT * FROM recipes_test_run');

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? ''; 


if (!empty($search)) {
    $statement = $connection->prepare('SELECT * FROM recipes_test_run WHERE title LIKE ? OR subtitle LIKE ? OR ingredients LIKE ? OR protein LIKE ? OR cuisine LIKE ? OR cook_time LIKE ?');
    $searchParam = '%' . $search . '%';
    $statement->bind_param('ssssss',$searchParam, $searchParam, $searchParam,$searchParam, $searchParam, $searchParam);
} elseif (!empty($filter)) {
    $statement = $connection->prepare('SELECT * FROM recipes_test_run WHERE protein = ?');
    $statement->bind_param('s', $filter);

} else {
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
    <title>The WOK - Recipes</title>
    <link rel="icon" href="images/logo.png" />
    <link rel="stylesheet" href="css/recipes.css">
    
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

    <div class="back-to-top" id="back-to-top">
        <img src="images/top-icon.png" alt="back to top button">
    </div>

    <div>
        <p class="question title">What are you feeling today?</p>

        <!-- search form -->
        <form action="recipes.php" method="get" class="search-bar">
            <input type="text" name="search" id="searchInput" placeholder="Search for recipes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button onclick="performSearch()">Search</button>
        </form>

        <!-- filter button -->

        <div class="filter-button">
            <form action="recipes.php" method="get">
                <input type="hidden" name="filter" value="Fish">
                <button type="submit">Fish</button>
            </form>

            <form action="recipes.php" method="get">
                <input type="hidden" name="filter" value="Beef">
                <button type="submit">Beef</button>
            </form>

            <form action="recipes.php" method="get">
                <input type="hidden" name="filter" value="Vegetarian">
                <button type="submit">Vegetarian</button>
            </form>

            <form action="recipes.php" method="get">
                    <input type="hidden" name="filter" value="Chicken" >
                    <button type="submit"> Chicken</button>
            </form>

            <form action="recipes.php" method="get">
                    <input type="hidden" name="filter" value="Turkey" >
                    <button type="submit"> Turkey</button>
            </form>

            <form action="recipes.php" method="get">
                    <input type="hidden" name="filter" value="Pork" >
                    <button type="submit">Pork</button>
            </form>

            <!-- Reset Button -->
            <form action="recipes.php" method="get">
                <button type="submit" class="reset-button">Reset Filters</button>
            </form>
        </div>
    </div>

    <h2>Try our recipes</h2>

    <div class="container">
        
    <?php foreach ($recipes as $recipe): ?>
        <a href="detail.php?id=<?php echo $recipe['id']; ?>">
            <div class="card">
            
                <!-- Recipe Image -->
                <img src="images/<?php echo ($recipe['main_image']); ?>" alt="Recipe Image" class="pic">

                <!-- Recipe Title and Subtitle -->
                <h2 class="recipe-title"><?php echo ($recipe['title']); ?></h2>
                <p class="recipe-subtitle"><?php echo ($recipe['subtitle']); ?></p>
            </div>
        </a>
        
    <?php endforeach; ?>

    <?php if (count($recipes) > 0): ?>
    <?php else: ?>
        <p class="error">No recipes found matching "<?php echo htmlspecialchars($search); ?>"</p>
    <?php endif; ?>

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