window.addEventListener('load', init);
window.addEventListener('scroll', scrollFunction);

let topButton = document.getElementById('top')
topButton.addEventListener('click', scrollUp)

function init() {
    topButton.style.display = "none";
}

function scrollUp() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function scrollFunction() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        topButton.style.display = "block";
    } else {
        topButton.style.display = "none";
    }
}