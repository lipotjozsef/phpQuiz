<?php

class Question
{
    private string $question;
    private array $answers;
    private int $answerID;

    public function __construct(array $questionJSON)
    {
        $this->question = $questionJSON['question'];
        $this->answers = $questionJSON['answers'];
        $this->answerID = $questionJSON['answerID'];
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function getAnswer(int $index): string
    {
        return $this->answers[$index];
    }

    public function isCorrect(int $index): bool
    {
        return $this->answerID == $index;
    }

    public function formatQuestionHTML(int $givenAnswer, bool $isLast, bool $completedAll): string
    {
        $action = $_SERVER["PHP_SELF"];
        $option = function($index) { return $this->answers[$index]; };
        $answer = function($cond) { 
            return $cond ? 'checked' : '';
        };

        $nextButton = !$isLast ?
        '<button name="next" type="submit" class="btn btn-outline-secondary">Következő &rarr;</button>' :
        '<button name="send" id="btn-submit" type="submit" onclick="return confirm(\'Biztosan le szeretné adni a válaszait?\');" class="btn btn-outline-secondary">Leadás</button>';

        $html = <<<HTML
            <div class="card p-4">
            <form action="$action" method="post" class="form">
                <section class="text-center q-header">
                    <div class="row">
                        <div class="col-12">
                            <p class="fw-bold fs-3">$this->question</p>
                        </div>
                    </div>
                </section>
                <section id="q-body">
                        <div class="quiz">
                            <div class="mb-3 row">
                                <div class="col-6 text-center">
                                    <input type="radio" class="btn-check" name="answer" value="0" id="option1" autocomplete="off" {$answer(0 == $givenAnswer)}>
                                    <label class="btn btn-outline-primary w-100" for="option1">{$option(0)}</label>
                                </div>
                                <div class="col-6 text-center">
                                    <input type="radio" class="btn-check" name="answer" value="1" id="option2" autocomplete="off" {$answer(1 == $givenAnswer)}>
                                    <label class="btn btn-outline-success w-100" for="option2">{$option(1)}</label>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-6 text-center">
                                    <input type="radio" class="btn-check" name="answer" value="2" id="option3" autocomplete="off" {$answer(2 == $givenAnswer)}>
                                    <label class="btn btn-outline-danger w-100" for="option3">{$option(2)}</label>
                                </div>
                                <div class="col-6 text-center">
                                    <input type="radio" class="btn-check" name="answer" value="3" id="option4" autocomplete="off" {$answer(3 == $givenAnswer)}>
                                    <label class="btn btn-outline-warning w-100" for="option4">{$option(3)}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="operations d-flex justify-content-around">
                            <button name="back" type="submit" class="btn btn-outline-secondary">&larr; Előző</button>
                            $nextButton
                        </div>
                </section>
            </form>
            </div>
        HTML;

        return $html;
    }
}