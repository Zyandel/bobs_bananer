window.onscroll = function() {stickyNavbar()};
var navbar = document.getElementById("navBar");
var sticky = navbar.offsetTop;
function stickyNavbar() 
{
  if (window.pageYOffset >= sticky) 
  {
    navbar.classList.add("sticky")
  } 
  else 
  {
    navbar.classList.remove("sticky");
  }
}


var menu = document.getElementById("navBar");
var showMenuIcon = document.getElementById("showMenuIcon");
var hideMenuIcon = document.getElementById("hideMenuIcon");
var mobileClass = "mobile";

showMenuIcon.addEventListener("click", handleIconClick); 
hideMenuIcon.addEventListener("click", handleIconClick); 


function handleIconClick (e) 
{    
        
        if(e.target === showMenuIcon) 
        {                    
            menu.classList.add(mobileClass);
            menu.style.display ="block";             
            showMenuIcon.style.visibility = "hidden";
        }
    
        if(e.target === hideMenuIcon) 
        {        
            menu.classList.remove(mobileClass); 
            menu.style.display ="none"; 
            showMenuIcon.style.visibility = "visible";            
        }    
}





var mediumScreenMQ = window.matchMedia('(min-width: 426px)');

mediumScreenMQ.addEventListener("change", handleScreenChange); 

function handleScreenChange() 
{
    //If the screen matches the media query "mediumScreenMQ", show the normal menu and hide the menu button.
    if(mediumScreenMQ.matches) 
    {
        menu.style.display ="block";
        menu.classList.remove("mobile");
        showMenuIcon.style.visibility = "hidden";
    }
    //Otherwise hide the menu completely and show the menu button, so that the point of origin will be as if you're yet to click.'
    else 
    {
        menu.style.display = "none";         
        showMenuIcon.style.visibility = "visible";
    }
}