function showInfo(layer) {
    const info = document.getElementById("info");
    let text = "";
    switch(layer) {
        case "troposfera":
            text = "La troposfera es la capa más baja de la atmósfera, donde ocurren los fenómenos meteorológicos.";
            break;
        case "estratosfera":
            text = "La estratosfera contiene la capa de ozono, que protege a la Tierra de los rayos ultravioleta.";
            break;
        case "mesosfera":
            text = "En la mesosfera se queman la mayoría de los meteoritos que entran a la atmósfera.";
            break;
        case "termosfera":
            text = "La termosfera es una capa muy caliente donde ocurren las auroras polares.";
            break;
        case "exosfera":
            text = "La exosfera es la capa más externa, donde los gases se dispersan en el espacio.";
            break;
    }
    info.innerHTML = `<p>${text}</p>`;
    info.style.display = "block";
}
x