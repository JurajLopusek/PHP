
function vymazat() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            swal({
                title: "Chcete vymazať všetky dáta?",
                icon: "warning",
                buttons: ["Nie", "Áno"],
                dangerMode: true,
            })
                .then(() => {
                    location.reload();
                });
        }
    };
    xhttp.open("DELETE", "vymazat.php", true);
    xhttp.send();
}
function zobraz() {
    swal({
        title: "Chcete zobraziť rozparsované dáta?",
        icon: "warning",
        buttons: ["Nie", "Áno"],
        dangerMode: true,
    })
        .then((willDisplay) => {
            if (willDisplay) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("vysledok").innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "zobraz.php", true);
                xhttp.send();
            }
        });
}