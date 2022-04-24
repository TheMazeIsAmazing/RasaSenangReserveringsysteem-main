window.addEventListener('load', init)
const page = document.body

function init() {
    const toggleButton = document.getElementById('menuDarkModeToggle')
    toggleButton.addEventListener('click', toggleDarkMode)
    if (localStorage.getItem('dark-mode') !== null) {
        if (localStorage.getItem('dark-mode') ===  'false') {
            page.classList.remove("dark-mode");
            const logo = document.getElementById('logo')
            logo.src = 'https://stud.hosted.hr.nl/1028473/RasaSenangReserveringsysteem-main/data/logo-light-mode.png'
        } else {
            const logo = document.getElementById('logo')
            logo.src = 'https://stud.hosted.hr.nl/1028473/RasaSenangReserveringsysteem-main/data/logo-dark-mode.png'
        }
    } else {
        page.classList.remove("dark-mode");
    }
    let styleLink = document.getElementById('style-rel-head')
    styleLink.href += 'includes/style.css'
}

function toggleDarkMode() {
    if (localStorage.getItem('dark-mode') ===  'true') {
        localStorage.setItem('dark-mode', 'false')
        page.classList.toggle("dark-mode");
        const logo = document.getElementById('logo')
        logo.src = 'https://stud.hosted.hr.nl/1028473/RasaSenangReserveringsysteem-main/data/logo-light-mode.png'
    } else {
        localStorage.setItem('dark-mode', 'true')
        page.classList.toggle("dark-mode");
        const logo = document.getElementById('logo')
        logo.src = 'https://stud.hosted.hr.nl/1028473/RasaSenangReserveringsysteem-main/data/logo-dark-mode.png'
    }
}