fetch('../logic/third-page.php').then(
    response => response.json()).then(
        data => {
            const username = data.username;
            const rankings = data.rankings;
            const results = data.results

            document.getElementById('username').innerText = username.userName;

            userScore = results.totalScore;
            userTime = results.totalTime;

            document.getElementById('user-score').innerText = userScore;
            document.getElementById('user-time').innerText = userTime;

            const rankTable = document.querySelector('#rank-table tbody');

            rankings.forEach(ranking => {
                const tRow = document.createElement('tr');

                const uName = document.createElement('td');
                uName.textContent = ranking.username;
                tRow.appendChild(uName);
                
                const tScore = document.createElement('td');
                tScore.textContent = ranking.totalScore;
                tRow.appendChild(tScore);

                const tTime = document.createElement('td');
                tTime.textContent = ranking.totalTime;
                tRow.appendChild(tTime);

                rankTable.appendChild(tRow);
            });

        }
    )
