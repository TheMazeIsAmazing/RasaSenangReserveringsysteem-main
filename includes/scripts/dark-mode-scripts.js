window.addEventListener('load', init)
const page = document.body


function init() {
    const toggleButton = document.getElementById('menuDarkModeToggle')
    toggleButton.addEventListener('click', toggleDarkMode)
    if (localStorage.getItem('dark-mode') !== null) {
        if (localStorage.getItem('dark-mode') ===  'false') {
            page.classList.remove("dark-mode");
        }
    } else {
        page.classList.remove("dark-mode");
    }
    let styleLink = document.getElementById('style-rel-head')
    styleLink.href += '/includes/style.css'
}

function toggleDarkMode() {
    if (localStorage.getItem('dark-mode') ===  'true') {
        localStorage.setItem('dark-mode', 'false')
        page.classList.toggle("dark-mode");
    } else {
        localStorage.setItem('dark-mode', 'true')
        page.classList.toggle("dark-mode");
    }
}