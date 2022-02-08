const sideNav = document.querySelector(".sideNav")
const overlay = document.querySelector(".overlay")
const ham = document.querySelector(".ham")
const menuX = document.querySelector("#menuX")
const menuItems = document.querySelectorAll(".menuLink")

menuItems.forEach(menuItem => {
    menuItem.addEventListener("click", toggleHamburger)
})

ham.addEventListener("click", toggleHamburger)
menuX.addEventListener("click", toggleHamburger)
overlay.addEventListener("click", toggleHamburger)

console.log(menuX);

function toggleHamburger() {
    console.log('dss')
    overlay.classList.toggle("showOverlay")
    sideNav.classList.toggle("showNav")
}


const openModalButtons = document.querySelectorAll('[data-modal-target]')
const closeModalButtons = document.querySelectorAll('[data-close-button]')
const overlaymodal = document.querySelector(".overlaymodal")

openModalButtons.forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.querySelector(button.dataset.modalTarget)
        openModal(modal)
    })
})

overlaymodal.addEventListener('click', () => {
    const modals = document.querySelectorAll('.modal.active')
    modals.forEach(modal => {
        closeModal(modal)
    })
})

closeModalButtons.forEach(button => {
    button.addEventListener('click', () => {
        const modal = button.closest('.modal')
        closeModal(modal)
    })
})

function openModal(modal) {
    if (modal == null) return
    modal.classList.add('active')
    overlaymodal.classList.add('showOverlaymodal')
}

function closeModal(modal) {
    if (modal == null) return
    modal.classList.remove('active')
    overlaymodal.classList.remove('showOverlaymodal')
}