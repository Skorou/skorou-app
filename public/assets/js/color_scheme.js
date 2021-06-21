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
    // const randomColors = await getRandomColors();

    let colorInputs = document.getElementsByClassName("random-color");

    for(let i = 0 ; i < colorInputs.length ; i++)
    {
        console.log(colorInputs[i]);
        // TODO: convert array to hex color
        // colorInputs[i].value = randomColors['result'][i];
    }
}