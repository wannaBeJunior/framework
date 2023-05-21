let count = document.getElementById("buttonCountNumber");

document.getElementById("buttonCountPlus").onclick = function() {
    let countPlus = count.value;
    if(+countPlus < 100){
        count.value++;
    }
}

document.getElementById("buttonCountMinus").onclick = function() {
    let countMinus = count.value;
    if(+countMinus > 1){
        count.value--;
    }
}