const descriptionDesktop = document.getElementsByClassName('description-desktop');
const descriptionMobile = document.getElementsByClassName('description-mobile');
const infoDesktop = document.getElementsByClassName('info-desktop');
const infoMobile = document.getElementsByClassName('info-mobile');

const screenWidth = window.innerWidth;


function checkWidthAndChange() {
    if (screenWidth < 950) {
      infoMobile.style.display = "block";

    } else if (screenWidth >=  && screenWidth < 950) {
      // Do something for medium screens (e.g., tablets)
      
    } else {
      // Do something for large screens (e.g., desktops)
      
    }
  }
  
  // Call the function on page load and when the window is resized
  checkWidthAndChange(); 
  window.addEventListener('resize', checkWidthAndChange);