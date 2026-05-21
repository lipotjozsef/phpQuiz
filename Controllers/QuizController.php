<?php

require_once __DIR__ . "/../Classes/Quiz.php";

if (session_status() != PHP_SESSION_ACTIVE)
    return;

$step = $_SESSION['user-data']['step'] ?? 1;
$stat = $_SESSION['user-data']['stat'] ?? [];
$answers = $_SESSION['user-data']['answers'] ?? [];

$m = "question";

$questions = loadQuestions();
$myQuiz = new Quiz($questions, $stat);

if (!isset($_SESSION['user-data']))
{
    $_SESSION['user-data']['step'] = 1;
    $_SESSION['user-data']['stat'] = [];
    $_SESSION['user-data']['answers'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if (isset($_POST["restart"]))
    {
        unset($_SESSION['user-data']);
        unset($step);
        unset($stat);
        unset($answers);
        header("Location: " . $_SERVER["PHP_SELF"]);
    }


    if (isset($_POST["answer"]))
    {
        processAnswer();
    }

    if (isset($_POST["send"]))
    {
        processAnswer();
        $m = "results";
    }

    $_SESSION['user-data']['stat'] = $myQuiz->getStat();
    $_SESSION['user-data']['answers'] = $answers;

    if (isset($_POST["next"]))
    {
        $step = ($step < $myQuiz->getCount() ? $step + 1 : $myQuiz->getCount());
    }

    if (isset($_POST["back"]))
    {
        $step = max(1, $step - 1);
    }

    $_SESSION['user-data']['step'] = $step;
}

$index = $step - 1;
$givenAnswer = $answers[$index] ?? -1;
$mainContent = $myQuiz->getQuestionHTML($index, $givenAnswer);

function loadQuestions(): array
{
    $content = file_get_contents("./data/questions.json");
    $json = json_decode($content, true);
    return $json ?? [];
}

function processAnswer(): void
{
    global $stat, $step, $myQuiz, $answers;
    $answerID = intval($_POST["answer"]);
    $index = $step - 1;
    $stat = $myQuiz->givePoint($index, $answerID);
    $answers[$index] = $answerID;
}