function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length === 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function hexToRgb(hex) {
    hex = hex.substring(1);
    var bigint = parseInt(hex, 16);
    var r = (bigint >> 16) & 255;
    var g = (bigint >> 8) & 255;
    var b = bigint & 255;

    return [r, g, b];
}

function getModelInput()
{
    let lockButtons = document.getElementsByClassName("lock-button");
    let colorInputs = document.getElementsByClassName("random-color");
    let inputArray  = [];

    for(let i = 0 ; i < colorInputs.length ; i++)
    {
        // there are same number of lockButtons than colorInputs
        // so colorButtons[i] = lockButtons[i]
        if(lockButtons[i].getAttribute('data-color') === 'unlocked')
        {
            inputArray.push('N');
        }
        else
        {
            console.log('je passe dans le N');
            inputArray.push(hexToRgb(colorInputs[i].value));
        }
    }

    return inputArray;
}

async function getRandomColors()
{
    var myHeaders  = new Headers();
    let modelInput = getModelInput();

    let model = {
        'input': modelInput,
        'model': 'default'
    }

    var myInit = { method: 'POST',
        headers: myHeaders,
        mode: 'cors',
        cache: 'default',
        body: JSON.stringify(model)
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
            randomColors.result[i][0], // R
            randomColors.result[i][1], // G
            randomColors.result[i][2] // B
        );
    }
}

function lockColor()
{
    let lockButtons = document.getElementsByClassName("lock-button");

    for(let i = 0 ; i < lockButtons.length ; i++)
    {
        lockButtons[i].onclick = function()
        {
            if (lockButtons[i].getAttribute('data-color') === 'unlocked')
            {
                lockButtons[i].setAttribute('data-color', 'locked');
            }
            else
            {
                lockButtons[i].setAttribute('data-color', 'unlocked');
            }
        }
    }
}

lockColor();