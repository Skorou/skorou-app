function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length === 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

async function getRandomColors()
{
    var myHeaders = new Headers();

    var myInit = { method: 'POST',
        headers: myHeaders,
        mode: 'cors',
        cache: 'default',
        body: '{"model":"default"}'
    };

    try {
        const response = await fetch('http://colormind.io/api/', myInit);

        return response.json();

    } catch (error) {
        alert(error);
    }

}

async function setRandomColors()
{
    const randomColors = await getRandomColors();

    let colorInputs = document.getElementsByClassName("random-color");

    for(let i = 0 ; i < colorInputs.length ; i++)
    {
        colorInputs[i].value = rgbToHex(
            randomColors.result[i][0],
            randomColors.result[i][1],
            randomColors.result[i][2]);
    }
}