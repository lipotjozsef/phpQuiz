const answeredCount = Number(document.querySelector("#answered-count").value) ?? -1;
const quizCount = Number(document.querySelector("#quiz-count").value) ?? -1;
const thisAnswered = document.querySelector("#this-answered").value == "1" ? true : false ?? -1;
const submitButton = document.querySelector("#btn-submit") ?? null;

if (submitButton && answeredCount != quizCount)
{
    submitButton.disabled = true;
}

if (submitButton)
{
    document.querySelectorAll("input").forEach(element =>
    {
        element.addEventListener("click", () =>
        {
            if (thisAnswered == false && (answeredCount+1) == quizCount)
            {
                submitButton.disabled = false;
                console.log("now you can submit!");
            }
        })
    });
}