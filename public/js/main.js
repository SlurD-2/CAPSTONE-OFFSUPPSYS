// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .fa-solid.fa-bars');
const sidebar = document.getElementById('sidebar');
const labels = document.querySelectorAll('#nav-text, #header-text');

const nav = document.querySelectorAll('#nav-container')



menuBar.addEventListener('click', function (){
    sidebar.classList.toggle('hide');

    const displayStyle = sidebar.classList.contains('hide') ? 'none' : 'block';

    labels.forEach(label => {
        label.style.display = displayStyle;
    });
    
   

    
})




// document.addEventListener('DOMContentLoaded', function () {
//     const canvas = document.getElementById('signatureCanvas');
//     const signatureInput = document.getElementById('signatureInput');
//     const clearButton = document.getElementById('clearSignature');

//     // Adjust canvas for device pixel ratio
//     function resizeCanvas() {
//         const ratio = Math.max(window.devicePixelRatio || 1, 1);
//         canvas.width = canvas.offsetWidth * ratio;
//         canvas.height = canvas.offsetHeight * ratio;
//         canvas.getContext('2d').scale(ratio, ratio);
//     }
//     resizeCanvas();

//     // Initialize Signature Pad
//     const signaturePad = new SignaturePad(canvas);

//     // Save signature data to hidden input on form submit
//     const form = document.querySelector('.request-form');
//     form.addEventListener('submit', function () {
//         if (!signaturePad.isEmpty()) {
//             signatureInput.value = signaturePad.toDataURL();
//         } else {
//             alert('Please provide a signature before submitting.');
//             event.preventDefault();
//         }
//     });

//     // Clear the signature
//     clearButton.addEventListener('click', function () {
//         signaturePad.clear();
//     });

//     // Resize canvas on window resize
//     window.addEventListener('resize', resizeCanvas);
// });


// Drop down profile and logout function 
document.addEventListener("DOMContentLoaded", function () {
    const dropdownToggle = document.getElementById("dropdownToggle");
    const dropdownMenu = document.getElementById("dropdownMenu");
    const dropdownArrow = document.getElementById("dropdownArrow");

    dropdownToggle.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent event from propagating to document
        dropdownMenu.classList.toggle("hidden");
        dropdownArrow.classList.toggle("rotate-180");
    });

    document.addEventListener("click", function (event) {
        if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add("hidden");
            dropdownArrow.classList.remove("rotate-180");
        }
    });
});
