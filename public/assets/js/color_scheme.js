function componentToHex(c)
{
    var hex = c.toString(16);
    return hex.length === 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b)
{
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function getUnlockedColors()
{
    let lockButtons = document.getElementsByClassName("lock-button");
    let colorInputs = [];

    for(let i = 0 ; i < lockButtons.length ; i++)
    {
        if(lockButtons[i].getAttribute('data-color' + [i]) === 'unlocked')
        {
            colorInputs.push(document.querySelector(".random-color" + [i]));
        }
    }

    return colorInputs;
}

async function getRandomColors()
{
    var myHeaders = new Headers();
    let unlockedColors = getUnlockedColors();
    

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
    let colorInputs = getUnlockedColors();

    const randomColors = await getRandomColors();

    for(let i = 0 ; i < colorInputs.length ; i++)
    {
        colorInputs[i].value = rgbToHex(
            randomColors.result[i][0],
            randomColors.result[i][1],
            randomColors.result[i][2]
        );
    }
}

function lockColor()
{
    let lockButtons = document.getElementsByClassName("lock-button");

    for(let i = 0 ; i < lockButtons.length ; i++)
    {
        lockButtons[i].onclick = function() {
            if (lockButtons[i].getAttribute('data-color' + [i]) === 'unlocked')
            {
                lockButtons[i].setAttribute('data-color' + [i], 'locked');
            }
            else
            {
                lockButtons[i].setAttribute('data-color' + [i], 'unlocked');
            }
        }
    }
}

lockColor();