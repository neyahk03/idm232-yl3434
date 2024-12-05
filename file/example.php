<?php
error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);

require_once 'includes/database.php';

$statement = $connection->prepare('SELECT * FROM recipes_test_run');
$statement->execute();
$recipes = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Cards</title>
    <link rel="stylesheet" href="css/example.css">
</head>
<body>

    <div class="recipe-cards">
    <!-- Recipe Card 1 -->
    <div class="recipe-card">
        <img src="recipe1.jpg" alt="Recipe 1" class="recipe-image">
        <h2 class="recipe-title">Spaghetti Bolognese</h2>
        <h3 class="recipe-subtitle">A classic Italian dish</h3>
        <p><strong>Cooking Time:</strong> 30 minutes</p>
        <p><strong>Serving Size:</strong> 4 servings</p>
        <p><strong>Calories:</strong> 600 kcal</p>
        <p class="recipe-description">A delicious and hearty spaghetti bolognese made with ground beef, tomatoes, and Italian spices.</p>
        
        <h4>Ingredients:</h4>
        <ul class="ingredients-list">
            <li>400g spaghetti</li>
            <li>250g ground beef</li>
            <li>1 onion, chopped</li>
            <li>2 cloves garlic, minced</li>
            <li>400g canned tomatoes</li>
            <li>1 tbsp olive oil</li>
            <li>Italian herbs</li>
        </ul>

        <h4>Steps:</h4>
        <ol class="steps-list">
            <li>Cook spaghetti according to package instructions.</li>
            <li>In a pan, heat olive oil and sautÃ© onions and garlic.</li>
            <li>Add ground beef and cook until browned.</li>
            <li>Stir in tomatoes and Italian herbs, and simmer for 20 minutes.</li>
            <li>Serve sauce over spaghetti and enjoy!</li>
        </ol>
        </div>

        <!-- Recipe Card 2 -->
        <div class="recipe-card">
        <img src="recipe2.jpg" alt="Recipe 2" class="recipe-image">
        <h2 class="recipe-title">Chicken Caesar Salad</h2>
        <h3 class="recipe-subtitle">A fresh and light salad</h3>
        <p><strong>Cooking Time:</strong> 20 minutes</p>
        <p><strong>Serving Size:</strong> 2 servings</p>
        <p><strong>Calories:</strong> 350 kcal</p>
        <p class="recipe-description">A crispy Caesar salad topped with grilled chicken, Parmesan, and homemade dressing.</p>
        
        <h4>Ingredients:</h4>
        <ul class="ingredients-list">
            <li>2 chicken breasts</li>
            <li>4 cups romaine lettuce</li>
            <li>1/4 cup Parmesan cheese</li>
            <li>Caesar dressing</li>
            <li>Croutons</li>
        </ul>

        <h4>Steps:</h4>
        <ol class="steps-list">
            <li>Grill chicken breasts until fully cooked.</li>
            <li>Chop lettuce and place in a bowl.</li>
            <li>Slice the chicken and add to the salad.</li>
            <li>Top with Parmesan, croutons, and Caesar dressing.</li>
            <li>Toss everything together and serve!</li>
        </ol>
        </div>
    </div>

</body>
</html>