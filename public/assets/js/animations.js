function slideNavbar()
{
    let navbar      = document.querySelector(".side-navbar");
    let menuButton  = document.querySelector(".menu-button");
    let closeButton = document.querySelector(".close.menu-button");

    navbar.setAttribute("visible", 'false');
    let visible = navbar.getAttribute("visible");

    menuButton.onclick = function ()
    {
        if(visible === 'false')
        {
            navbar.style.transform = "translateX(0)";
            navbar.setAttribute("visible", 'true');
        }
        else
        {
            navbar.style.transform = "translateX(-100%)";
            navbar.setAttribute("visible", 'false');
        }
    };

    closeButton.onclick = function ()
    {
        navbar.style.transform = "translateX(-100%)";
        navbar.setAttribute("visible", 'false');
    };
}

window.onload = function()
{
    slideNavbar();
};
