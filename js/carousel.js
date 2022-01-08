var index = 1;

function moveSlides(n) {
    showSlides(index += n); //Mostra la slide con indice index +/- n, serve con +1 e -1 per spostarsi a destra o a sinistra.
}

function showSlideByIndex(n) {
    index = n
    showSlides(n);
}

function showSlides(n) {

    var slides = document.getElementsByClassName("slide");
    var circles = document.getElementsByClassName("circle");

    if (n > slides.length){
        index = 1
    }

    if (n < 1){
        index = slides.length
    }

    for (var i = 0; i < slides.length; i++) {

        slides[i].className = "js slide";

    }

    for (var i = 0; i < circles.length; i++) {

        circles[i].className = circles[i].className.replace(" active", "");

    }

    slides[index-1].className += " not-hidden";
    circles[index-1].className += " active";

}