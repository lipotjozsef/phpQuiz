<?php

require_once "./Classes/Question.php";

class Quiz
{
    private int $quizLength = -1;
    private array $questions = [];
    public array $stat = [];

    public function __construct(array $questionsJSON, array $existingStats = [])
    {
        foreach ($questionsJSON as $question)
        {
            $this->questions[] = new Question($question);
        }

        $this->stat = $existingStats;

        $this->quizLength = count($this->questions);
    }

    public function givePoint(int $index, int $answerID): array
    {
        if ($this->questions[$index]->isCorrect($answerID))
        {
            $this->stat[$index] = 1;
            return $this->stat;
        }
        else
        {
            $this->stat[$index] = 0;
            return $this->stat;
        }
    }

    public function getStat(): array
    {
        return $this->stat;
    }

    public function getCount(): int
    {
        return $this->quizLength;
    }

    public function getQuestion(int $index): string
    {
        return $this->questions[$index]->getQuestion();
    }

    public function getQuestionHTML(int $index, int $stepIndex, int $givenAnswer): string
    {
        $isLastQuesiton = $stepIndex == ($this->quizLength-1);
        $completedAllQuestion = count($this->stat) == $this->quizLength;
        return $this->questions[$index]->formatQuestionHTML($givenAnswer, $isLastQuesiton, $completedAllQuestion);
    }

    public function getAnswer(int $questionIndex, int $answerID): string
    {
        return $this->questions[$questionIndex]->getAnswer($answerID);
    }
}