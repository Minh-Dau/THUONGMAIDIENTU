function giam() {
    var soluong = parseInt(document.getElementById("so"));
    if (soluong > 1) {
        var trusl = soluong - 1;
        document.getElementById("so").value = trusl;
    }
}

function tang() {
    var soluong = parseInt(document.getElementById("so"));
    var congsl = soluong + 1;
    document.getElementById("so").value = congsl;
}
function nhan(clickedId){
    var radios=document.getElementById("name").value;
   for( var i=0;i<radios.lenght;i++){
    if(radios[i].id!==clickedId){
        radios[i].checked=false;
    }
   }
}