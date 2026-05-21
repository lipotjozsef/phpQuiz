<!DOCTYPE html>

<?php
define("PROTECTED", "yes");
session_start();

require_once __DIR__ . "\Controllers\QuizController.php";

?>

<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kvíz</title>
    <link rel="stylesheet" href="./modules/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./styles/style.css">

</head>

<body class="bg-light">
    <header class="bg-white text-center border-bottom py-2">
        <h1 class="display-4">Kvíz</h1>
    </header>

    <main class="w-75 mx-auto my-3">
        <?php
        switch($m)
        {
            case "question":
                echo $mainContent;
                break;
            case "results":
                include_once __DIR__ . "\Pages\quizSummary.php";
                break;
        }
        ?>
        
    </main>

    <footer class="text-center">
        <a href="https://github.com/lipotjozsef" target="_blank">&copy;gyula</a>
        <span> - <?= getdate()["year"] ?></span>
    </footer>


    <div>
        <input type="hidden" id="answered-count" value="<?= count($answers) ?>">
        <input type="hidden" id="quiz-count" value="<?= ($myQuiz->getCount()) ?>">
        <input type="hidden" id="this-answered" value="<?= isset($answers[$index]) ? 1 : 0 ?>">
        <script src="./scripts/checkForm.js"></script>
    </div>
</body>

</html>