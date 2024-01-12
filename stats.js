document.addEventListener("DOMContentLoaded", function () {
    // Récupérer l'ID de la question depuis l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const questionId = urlParams.get('question_id');

    // Vérifier si l'ID de la question est défini
    if (questionId) {
        // Effectuer une requête AJAX pour récupérer les statistiques
        const request = new XMLHttpRequest();
        request.open('GET', `public/stats.php?question_id=${questionId}`, true);
        request.onload = function () {
            if (request.status === 200) {
                try {
                    const data = JSON.parse(request.responseText);
                    displayStats(data);
                } catch (error) {
                    console.error("Erreur lors de l'analyse JSON:", error);
                }
            } else {
                console.error("Erreur de requête:", request.statusText);
            }
        };
        request.onerror = function () {
            console.error("Erreur de réseau");
        };
        request.send();
    } else {
        console.error("L'ID de la question n'est pas spécifié dans l'URL.");
    }

    function displayStats(data) {
        // Afficher le nombre total d'utilisateurs
        const totalUsersElement = document.createElement('div');
        totalUsersElement.textContent = `Nombre total d'utilisateurs: ${data.total_users}`;
        document.getElementById('stats-container').appendChild(totalUsersElement);

        // Afficher la question
        const questionElement = document.createElement('div');
        questionElement.classList.add('question');
        questionElement.textContent = data.question;
        document.getElementById('stats-container').appendChild(questionElement);

        // Afficher les réponses avec la barre de remplissage
        data.answers.forEach((answer, index) => {
            const answerContainer = document.createElement('div');
            answerContainer.classList.add('answer-container');

            const answerElement = document.createElement('div');
            answerElement.classList.add('answer');
            answerElement.textContent = `${String.fromCharCode(65 + index)}. ${answer.answer}`;

            const barElement = document.createElement('div');
            barElement.classList.add('bar');

            const fillBarElement = document.createElement('div');
            fillBarElement.classList.add('fill-bar');
            fillBarElement.style.width = `${answer.percentage}%`;

            const percentageElement = document.createElement('div');
            percentageElement.classList.add('percentage');
            percentageElement.textContent = `${answer.percentage}%`;

            barElement.appendChild(fillBarElement);
            answerElement.appendChild(barElement);
            answerElement.appendChild(percentageElement);
            answerContainer.appendChild(answerElement);

            document.getElementById('stats-container').appendChild(answerContainer);
        });
    }
});