
document.addEventListener('DOMContentLoaded', function() {
    var aLiens = document.querySelectorAll('a[href*="#"]');
    for(var i=0, len = aLiens.length; i<len; i++) {
        aLiens[i].onclick = function () {
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                var target = this.getAttribute("href").slice(1);
                if (target.length) {
                    scrollTo(document.getElementById(target).offsetTop, 1000);
                    return false;
                }
            }
        };
    }
});
//Exemple de : Forestrf
// http://jsfiddle.net/forestrf/tPQSv/2/
function scrollTo(element, duration) {
    var e = document.documentElement;
    if(e.scrollTop===0){
        var t = e.scrollTop;
        ++e.scrollTop;
        e = t+1===e.scrollTop--?e:document.body;
    }
    scrollToC(e, e.scrollTop, element, duration);
}

function scrollToC(element, from, to, duration) {
    if (duration < 0) return;
    if(typeof from === "object")from=from.offsetTop;
    if(typeof to === "object")to=to.offsetTop;
    scrollToX(element, from, to, 0, 1/duration, 20, easeOutCuaic);
}

function scrollToX(element, x1, x2, t, v, step, operacion) {
    if (t < 0 || t > 1 || v <= 0) return;
    element.scrollTop = x1 - (x1-x2)*operacion(t);
    t += v * step;
    setTimeout(function() {
        scrollToX(element, x1, x2, t, v, step, operacion);
    }, step);
}

function easeOutCuaic(t){
    t--;
    return t*t*t+1;
}

function linearTween(t){
    return t;
}

function easeInQuad(t){
    return t*t;
}

function easeOutQuad(t){
    return -t*(t-2);
}

function easeInOutQuad(t){
    t/=0.5;
    if(t<1)return t*t/2;
    t--;
    return (t*(t-2)-1)/2;
}

function easeInCuaic(t){
    return t*t*t;
}

function easeOutCuaic(t){
    t--;
    return t*t*t+1;
}

function easeInOutCuaic(t){
    t/=0.5;
    if(t<1)return t*t*t/2;
    t-=2;
    return (t*t*t+2)/2;
}

function easeInQuart(t){
    return t*t*t*t;
}

function easeOutQuart(t){
    t--;
    return -(t*t*t*t-1);
}

function easeInOutQuart(t){
    t/=0.5;
    if(t<1)return 0.5*t*t*t*t;
    t-=2;
    return -(t*t*t*t-2)/2;
}

function easeInQuint(t){
    return t*t*t*t*t;
}

function easeOutQuint(t){
    t--;
    return t*t*t*t*t+1;
}

function easeInOutQuint(t){
    t/=0.5;
    if(t<1)return t*t*t*t*t/2;
    t-=2;
    return (t*t*t*t*t+2)/2;
}

function easeInSine(t){
    return -Mathf.Cos(t/(Mathf.PI/2))+1;
}

function easeOutSine(t){
    return Mathf.Sin(t/(Mathf.PI/2));
}

function easeInOutSine(t){
    return -(Mathf.Cos(Mathf.PI*t)-1)/2;
}

function easeInExpo(t){
    return Mathf.Pow(2,10*(t-1));
}

function easeOutExpo(t){
    return -Mathf.Pow(2,-10*t)+1;
}

function easeInOutExpo(t){
    t/=0.5;
    if(t<1)return Mathf.Pow(2,10*(t-1))/2;
    t--;
    return (-Mathf.Pow(2,-10*t)+2)/2;
}

function easeInCirc(t){
    return -Mathf.Sqrt(1-t*t)-1;
}

function easeOutCirc(t){
    t--;
    return Mathf.Sqrt(1-t*t);
}

function easeInOutCirc(t){
    t/=0.5;
    if(t<1)return -(Mathf.Sqrt(1-t*t)-1)/2;
    t-=2;
    return (Mathf.Sqrt(1-t*t)+1)/2;
}