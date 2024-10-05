// const inputs = document.querySelectorAll('.otp-card-inputs input')
// const button = document.querySelector('.otp-card button')

// function sendOTP() {
//     const email = document.getElementById('email');
//     const otpverify = document.getElementsByClassName('otpverify')[0];
// }

// inputs.forEach(input => {
//     let lastInputStatus =0
//     input.onkeyup = (e) => {
//         const currentElement = e.target
//         const nextElement = input.nextElementSibling
//         const prevElement = input.previousElementSibling

//         if(prevElement && e.keyCode===8){
//             if (lastInputStatus==1){
//                 prevElement.value =''
//                 prevElement.focus()
//             }
//             button.setAttribute('disabled',true)
//             lastInputStatus=1
//         }else{
//             const reg = /^[0-9]+$/
//             if (!reg.test(currentElement.value)){
//                 currentElement.value= currentElement.value.replace(/\D/g, '')
//             }else if(currentElement.value){
//                 if (nextElement){
//                     nextElement.focus()

//                 }else {
//                     button.removeAttribute('disabled')
//                     lastInputStatus=0
//                 }
//             }
//         }
//     }

// })

function moveToNext(input) {
    if (input.nextElementSibling) {
        input.nextElementSibling.focus();
    }
}

document.querySelectorAll('.otp-card-inputs input').forEach((input) => {
    input.addEventListener('keydown', (event) => {
        if (event.key === 'Backspace' && !input.value) {
            input.previousElementSibling.focus();
        }
    });
});
