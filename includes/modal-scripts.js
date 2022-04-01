window.addEventListener('load', init)

function init() {
    if (document.location.hash === '#open') {
        const modalInit = document.getElementById('modal')
        openModal(modalInit)
    }
}

const openModalButtons = document.querySelectorAll('[data-modal-target]')
const closeModalButtons = document.querySelectorAll('[data-close-button]')
const overlayModal = document.querySelector(".overlayModal")

openModalButtons.forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.querySelector(button.dataset.modalTarget)
        openModal(modal)
    })
})

overlayModal.addEventListener('click', () => {
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
    overlayModal.classList.add('showOverlayModal')
    document.location.hash = 'open'
}

function closeModal(modal) {
    if (modal == null) return
    modal.classList.remove('active')
    overlayModal.classList.remove('showOverlayModal')
    document.location.hash = ''
}