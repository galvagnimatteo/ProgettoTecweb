document.getElementById("prev_slide").addEventListener("click", moveSlidesLeft);
document.getElementById("next_slide").addEventListener("click", moveSlidesRight);

document.getElementById("slide_1").addEventListener("click", showSlideOne);
document.getElementById("slide_2").addEventListener("click", showSlideTwo);
document.getElementById("slide_3").addEventListener("click", showSlideThree);

var index = 1;

function moveSlidesRight() {
    showSlides(index += 1);
}

function moveSlidesLeft() {
    showSlides(index -= 1);
}

function showSlideOne(){
    showSlideByIndex(1);
}

function showSlideTwo(){
    showSlideByIndex(2);
}

function showSlideThree(){
    showSlideByIndex(3);
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