window.addEventListener('load', init)
let restaurant_opened_checkbox = document.getElementById('restaurant_opened_checkbox')
restaurant_opened_checkbox.addEventListener('click', toggleHandler)
let hiddenElement = document.getElementById('hide-if-restaurant-closed')
let timesOrTimeslots = document.getElementById('times_or_timeslots')
let hiddenIfTimesIsSelected = document.getElementById('hiddenIfTimesIsSelected');
let hiddenIfTimeslotsIsSelected = document.getElementById('hiddenIfTimeslotsIsSelected');
timesOrTimeslots.addEventListener('change', toggleHandlerTimesOrSlots)


function init() {
    toggleHandler()
    toggleHandlerTimesOrSlots()
}

function toggleHandlerTimesOrSlots() {
    let currentItem = timesOrTimeslots.selectedIndex
    if (currentItem === 0) {
        hiddenIfTimeslotsIsSelected.style.display = 'block'
        hiddenIfTimesIsSelected.style.display = 'none'
    } else if (currentItem === 1) {
        hiddenIfTimeslotsIsSelected.style.display = 'none'
        hiddenIfTimesIsSelected.style.display = 'block'
    }
}

function toggleHandler() {
    if (restaurant_opened_checkbox.checked) {
        hiddenElement.style.display = 'block'
    } else {
        hiddenElement.style.display = 'none'
    }
}