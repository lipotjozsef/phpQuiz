<?php

require_once __DIR__ . "/../Classes/Quiz.php";

if (session_status() != PHP_SESSION_ACTIVE)
    return;

if (!isset($_SESSION['user-data']))
{
    $_SESSION['user-data'] = [
        'step' => 1,
        'stat' => [],
        'answers' => [],
        'question_order' => []
    ];
}

$questions = loadQuestions();

if (empty($_SESSION['user-data']['question_order']) && !empty($questions)) {
    $indices = array_keys($questions);
    shuffle($indices); // Randomize the keys
    $_SESSION['user-data']['question_order'] = $indices;
}

$step = $_SESSION['user-data']['step'] ?? 1;
$stat = $_SESSION['user-data']['stat'] ?? [];
$answers = $_SESSION['user-data']['answers'] ?? [];
$questionOrder = $_SESSION['user-data']['question_order'];

$m = "question";
$myQuiz = new Quiz($questions, $stat);

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if (isset($_POST["restart"]))
    {
        unset($_SESSION['user-data']);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
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

$stepIndex = $step - 1;
$actualQuestionIndex = $questionOrder[$stepIndex] ?? $stepIndex;

$givenAnswer = $answers[$actualQuestionIndex] ?? -1;
$mainContent = $myQuiz->getQuestionHTML($actualQuestionIndex, $stepIndex, $givenAnswer);

function loadQuestions(): array
{
    $content = file_get_contents("./data/questions.json");
    $json = json_decode($content, true);
    return $json ?? [];
}

function processAnswer(): void
{
    global $stat, $step, $myQuiz, $answers, $questionOrder;
    $answerID = intval($_POST["answer"]);
    
    $stepIndex = $step - 1;
    $actualQuestionIndex = $questionOrder[$stepIndex] ?? $stepIndex;
    
    $stat = $myQuiz->givePoint($actualQuestionIndex, $answerID);
    $answers[$actualQuestionIndex] = $answerID;
}