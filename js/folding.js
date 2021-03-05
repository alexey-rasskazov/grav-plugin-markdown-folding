document.addEventListener("DOMContentLoaded", function(event){ 
  document.querySelectorAll(".collapse-switch").forEach(function(el) { 
    let cont = el.nextSibling;
    let collapsed = el.getAttribute('data-collapsed') === 'true';
    cont.addEventListener("transitionend", () => {
        if (!collapsed) {
            cont.style.height = "auto"
        }
    });
    el.addEventListener("click", function(ev) {
        collapsed = !collapsed;
        el.setAttribute('data-collapsed', collapsed ? 'true' : 'false');
        if (!collapsed) {
            cont.style.height = `${ cont.scrollHeight }px`
        } else {
            cont.style.height = `${ cont.scrollHeight }px`;
            window.getComputedStyle(cont, null).getPropertyValue("height");
            cont.style.height = "0";
        }
    })
  })
})
