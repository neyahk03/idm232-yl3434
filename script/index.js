const backtotop = document.getElementById('back-to-top');


window.addEventListener('scroll', () => {
  if(window.scrollY > 140) {
    backtotop.classList.add('active');
  } else {
    backtotop.classList.remove('active');
  }
});

backtotop.addEventListener('click', () => {
  window.scrollTo({
      top: 0,
      behavior: 'smooth' 
  });
});