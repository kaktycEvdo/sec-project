let modal = document.querySelector('.modal');
if (modal){
    modal.addEventListener('click', (e)=>{
        modal.classList.add('mhidden');
    });
    setTimeout(() => {
        modal.classList.add('mhidden');
    }, 5000);
}