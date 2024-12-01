    let options = ["A", "B", "C", "D"];
    let answer, totalScore = 0;
    let timeInterval;
    let seconds = 0;    
    let timeSnap = null;
    let timeLimit = 1800;

    fetch('../logic/second-page.php').then(
        response => response.json()).then(
            data => {
                const questionArray = data.questions;
                const nickname = data.username;

                document.getElementById('nickname').innerText = nickname.userName;
                document.getElementById('nicknameID').value = nickname.userId;

                function time(seconds){
                    let minutes = Math.floor(seconds/60);
                    let remainingsecs = seconds % 60;
                    return `${String(minutes).padStart(2,'0')}:${String(remainingsecs).padStart(2, '0')}`;
                }

                function displayTimer(){
                    document.getElementById('timer').innerText = time(seconds);
                }

                function startQuiz(){
                    timeInterval = setInterval(() => {
                        seconds++;
                        if(seconds >= timeLimit){
                            finishExam();
                        }
                        displayTimer();
                    }, 1000);
                }
        
                function finishExam(){
                    clearInterval(timeInterval); 
                    timeInterval = null;
                    timeSnap = seconds;
                }

            startQuiz();
                
                function randomQuestIndex(start, end){
                    return Math.floor(Math.random() * (end - start) + start);
                }            
                
                function questionData(){
                    index = randomQuestIndex(0, questionArray.length);
                    
                    questionText = questionArray[index].questionText;
                    questionChoices = questionArray[index].choices.split("|");
                    finalAnswer = questionArray[index].answer;

                    document.getElementById('question').innerText = questionText;

                    let htmlChoices = '';

                    questionChoices.forEach((choice, index) => {
                                htmlChoices += `
                                    <input type="radio" class="radio-choice" name="choice-index" id="${index}" value="${options[index]}">
                                    <label for="${index}">${choice}</label><br>
                                `;});
                            
                    document.getElementById('choices').innerHTML = htmlChoices;

                }

                function checkAnswer(){
                    const selectedChoice =  document.querySelector('input[name="choice-index"]:checked'); 
                    if(selectedChoice.value == finalAnswer){
                        questionArray.splice(index, 1);
                        totalScore++;
                    }
                    else{
                        questionArray.splice(index, 1);

                    }
                    if(questionArray.length > 0){
                        questionData();
                    }
                   else{
                    finishExam();
                    document.getElementById('nxt-btn').style.display = 'none';
                    document.getElementById('submit-btn').style.display = 'block';
                    document.getElementById('total-score').value = totalScore;
                    document.getElementById('total-time').value = timeSnap;
                   }
                }

                document.getElementById('nxt-btn').addEventListener('click', checkAnswer);
        
                questionData();
            }
        )

                


