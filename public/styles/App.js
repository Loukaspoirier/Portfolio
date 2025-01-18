const body = document.body;
const themeBtn = document.getElementById('themeBtn');

// Récupérer le thème actuel
const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
const profileDiv = document.getElementById('profile');

if (currentTheme) {
    body.classList.add(currentTheme);
    profileDiv.classList.add(currentTheme);
}

// Basculer entre les thèmes
themeBtn.addEventListener('click', () => {
    if (body.classList.contains('dark-mode')) {
        body.classList.remove('dark-mode');
        profileDiv.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light-mode');
    } else {
        body.classList.add('dark-mode');
        profileDiv.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark-mode');
    }
});