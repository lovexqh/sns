var myScroll;
function loaded() {
    myScroll = new iScroll('wrapper', { zoom:true });
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

document.addEventListener('DOMContentLoaded', loaded, false)