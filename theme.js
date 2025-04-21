document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.container');
    const body = document.querySelector('body');
    const themeButtons = document.querySelectorAll('.theme-icon');

    // Appliquer un thème en fonction du bouton cliqué
    themeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const theme = button.getAttribute('data-theme');
            container.className = 'container'; // Réinitialiser les classes
            container.classList.add(`theme-${theme}`);

            // Changer le fond de la page
            switch (theme) {
                case 'violet':
                    body.style.background = 'linear-gradient(135deg,rgb(84, 7, 155), #8a2be2)';
                    break;
                case 'rose':
                    body.style.background = 'linear-gradient(135deg,rgb(193, 11, 102), #ff69b4)';
                    break;
                case 'vert':
                    body.style.background = 'linear-gradient(135deg,rgb(20, 145, 20), #32cd32)';
                    break;
                case 'orange':
                    body.style.background = 'linear-gradient(135deg,rgb(255, 94, 0), #ffa500)';
                    break;
                case 'jaune':
                    body.style.background = 'linear-gradient(135deg,rgb(255, 233, 32), #ffd700)';
                    break;
                case 'bleu':
                    body.style.background = 'linear-gradient(135deg,rgb(0, 34, 255),  #007bff)';
                    break;
                case 'cyan':
                    body.style.background = 'linear-gradient(135deg,rgb(255, 0, 0),rgb(245, 43, 43))';
                    break;
                case 'magenta':
                    body.style.background = 'linear-gradient(135deg,rgb(163, 27, 79),rgb(184, 16, 97))';
                    break;
                case 'noir':
                    body.style.background = 'linear-gradient(135deg, #000000, #1c1c1c)';
                    break;
                case 'blanc':
                    body.style.background = 'linear-gradient(135deg, #ffffff, #f0f0f0)';
                    break;
                default:
                    body.style.background = 'linear-gradient(135deg, #ff7f50, #9370db, #ff69b4, #32cd32, #ffd700)';
            }
        });
    });
});