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

document.addEventListener("DOMContentLoaded", function () {
    slides = document.querySelectorAll(".slide");
    showSlide(slideIndex);   // show first image
    console.log(slideIndex);
});

/*..................Toast....................*/

let toast = document.querySelector(".toast");
function showToast(){
    if(!toast)
        return;

    toast.classList.remove("display-none");

    setTimeout(function(){
        toast.classList.add("display-none");
    }, 2000);
}


window.addEventListener("DOMContentLoaded",()=>{
    showToast();
});

/*............................................................*/

const addBtn = document.getElementById('add-preview');
if(addBtn){
addBtn.addEventListener('click', () => {
    const container = addBtn.closest('.row.flex-direction-column');
    const firstRow = container.querySelector('.row.gap-16.width-100');
    const clone = firstRow.cloneNode(true);

    // Clear the input values
    clone.querySelectorAll('input').forEach(input => input.value = '');
    container.insertBefore(clone, addBtn.parentElement);
});}