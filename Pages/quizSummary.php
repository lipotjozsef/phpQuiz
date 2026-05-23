<?php
if(!defined('PROTECTED')){
  header("Location: ./index.php");
  die(404);
}
?>

<div class="card p-4">
    <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" class="form">
        <section class="text-center s-header">
            <div class="row">
                <div class="col-12">
                    <p class="fw-bold fs-3">Eredmények</p>
                </div>
            </div>
        </section>
        <section id="s-body">
            <div class="stat mb-3">
                <table class="table table-responsive">
                    <thead>
                        <tr class="table-secondary">
                            <th>#</th>
                            <th>Kérdés</th>
                            <th>Válasz</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        $statArray = $myQuiz->getStat();
                        foreach($questionOrder as $actualIndex):

                            $stat = $statArray[$actualIndex] ?? 0; 
                            $passedQuestion = ($stat == 1);
                            $question = $myQuiz->getQuestion($actualIndex);

                            $userChoice = $answers[$actualIndex] ?? null;
                            $answer = $myQuiz->getAnswer($actualIndex, $userChoice);

                            $emoji = $passedQuestion ? "✅" : "❌";
                        ?>
                        <tr class="table-<?= $passedQuestion ? "success" : "danger" ?>">
                            <td><?= ++$counter ?></td>
                            <td title="<?= $question ?>" class="s-question"><?= $question ?></td>
                            <td title="<?= $answer ?>" class="s-answer"><?= $answer ?></td>
                            <td><?= $emoji ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="text-center border">
                    <?php
                    $count = $myQuiz->getCount();
                    $points = array_sum($myQuiz->getStat());
                    $prec = round($points / $count * 100, 2);
                    ?>
                    <?= $count ?>/<?= $points ?> - <?= $prec ?>%
                </p>
            </div>
            <div class="operations d-flex justify-content-around">
                <button name="restart" type="submit" class="btn btn-outline-primary">Újrakezdés</button>
            </div>
        </section>
    </form>
</div>