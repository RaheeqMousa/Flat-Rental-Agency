let slideIndex = 0;
let slides = document.querySelectorAll(".slide");

function showSlide(index){

    if(index >= slides.length){
        slideIndex = 0;
    }

    if(index < 0){
        slideIndex= slides.length- 1;
    }

    for(let i= 0; i<slides.length; i++){
        slides[i].style.display = "none";
    }

    slides[slideIndex].style.display = "block";
}

function nextSlide(){
    slideIndex++;
    showSlide(slideIndex);
}

function prevSlide(){
    slideIndex--;
    showSlide(slideIndex);
}

window.onload = function(){
    showSlide(slideIndex);
};
