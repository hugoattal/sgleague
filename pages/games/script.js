if (!window.GLOBALS) {
    const GLOBALS = {
        currentDropdownSuggestionNode: null,
        existingPlayers: [],
        mapPlayers: {}
    };
    window.GLOBALS = GLOBALS;
}

function queryParams(params) {
    return Object.keys(params)
        .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
        .join('&');
}

function doRequest(url, options={}) {
    options = Object.assign({}, {
        credentials: 'same-origin',
        redirect: 'error'
    }, options);

    if (options.queryParams) {
        url += (url.indexOf('?') == -1 ? '?' : '&') + queryParams(options.queryParams);
        delete options.queryParams;
    }

    return fetch(url, options);
}


function getAutocompletionForPseudo(pseudo) {
    return doRequest('api.php', {
        queryParams: {
            'type': 'search',
            'data': pseudo
        }
    })
    .then(response => response.json())
}

function debounce(func, wait=0, immediate=false) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        }, wait);
        if (immediate && !timeout) func.apply(context, args);
    };
}


function createPlayerNode(index, id, name, role="Membre") {
    const playerCard = document.createElement('div');
    const hiddenInput = document.createElement('input');
    const playerName = document.createElement('span');
    const playerType = document.createElement('span');

    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('value', id);
    hiddenInput.setAttribute('name', 'members[' + index + ']');
    hiddenInput.setAttribute('data-index', index);

    playerName.appendChild(document.createTextNode(name));
    playerName.classList.add('playername');

    playerType.appendChild(document.createTextNode('(' + role + ')'));
    playerType.classList.add('playertype');

    playerCard.classList.add('playercard');
    playerCard.appendChild(playerName);
    playerCard.appendChild(playerType);
    playerCard.appendChild(hiddenInput);

    return playerCard;    
}

function buildPrettyTextNode(login, school) {
    const loginNode = document.createTextNode(login);
    const schoolNode = document.createTextNode(school);

    const container = document.createElement('div');
    const loginContainer = document.createElement('span');
    const schoolContainer = document.createElement('span');

    loginContainer.appendChild(loginNode);
    schoolContainer.appendChild(schoolNode);

    container.appendChild(loginContainer);
    container.appendChild(schoolContainer);

    return container;
}

function getIndexOfMember(textInput) {
    if (textInput.previousSibling) {
        const prevNode = textInput.previousSibling;
        if (prevNode.classList.contains('playercard') && prevNode.lastChild.nodeName === 'INPUT') {
            return +prevNode.lastChild.getAttribute('data-index') + 1;
        }
    }

    return 0;
}

function addPlayer(textInput, getPlayerName, parentNode) {
    return function () {
        const playerName = getPlayerName();

        if (GLOBALS.existingPlayers.includes(playerName) && !getCurrentPlayersInTeam(parentNode).includes(playerName)) {
            console.log('Adding %s to the team.', playerName);
            const cardNode = createPlayerNode(getIndexOfMember(textInput), GLOBALS.mapPlayers[playerName].id, playerName);
            parentNode.insertBefore(cardNode, textInput);
            parentNode.removeChild(textInput);
            textInput = null;

            setTimeout(() => {
                parentNode.removeChild(GLOBALS.currentDropdownSuggestionNode);
                GLOBALS.currentDropdownSuggestionNode = null;
            }, 0);    
        } else {
            // TODO: show error.
        }
    }
}

function selectCurrentSuggestion(player, textInput) {
    return evt => {
        addPlayer(textInput, () => player.login, textInput.parentNode)();
    }
}

function getCurrentPlayersInTeam(teamNode) {
    return Array.from(teamNode.getElementsByClassName('playercard')).map(child => child.firstChild.firstChild.data);
}

function createOrChangeCurrentSuggestions(parentNode, textInput, currentContent, players) {
    const container = GLOBALS.currentDropdownSuggestionNode || document.createElement('ul');
    const addedPlayers = getCurrentPlayersInTeam(parentNode);
    console.log(addedPlayers);

    const nodes = players ? players.filter(player => !addedPlayers.includes(player.login)).map(player => {
        const node = document.createElement('li');
        node.setAttribute('data-id', player.id);
        node.appendChild(buildPrettyTextNode(player.login, player.school));
        node.onclick = selectCurrentSuggestion(player, textInput);
        return node
    }) : [];

    // Inject to parent.
    // Reset the HTML.
    container.innerHTML = "";
    if (currentContent !== "") {
        for (let i = 0 ; i < nodes.length ; i++) {
            container.appendChild(nodes[i]);    
        }
    }

    if (!GLOBALS.currentDropdownSuggestionNode) {
        parentNode.insertBefore(container, textInput.nextSibling);
    }

    GLOBALS.currentDropdownSuggestionNode = container;
}

function fillAutocompletion(getTextContent, parentNode, textInput) {
    return function () {
        getAutocompletionForPseudo(getTextContent()).then(players => {
            const playersNames = players.map(player => player.login);

            for (let index = 0; index < players.length ; index++) {
                if (!GLOBALS.existingPlayers.includes(players[index].login)) {
                    GLOBALS.existingPlayers.push(players[index].login);
                }
                GLOBALS.mapPlayers[players[index].login] = players[index];
            }

            createOrChangeCurrentSuggestions(parentNode, textInput, getTextContent(), players);
        });
    }
}


function handleControlForText(addPlayer, cancelAndRemove) {
    return evt => {
        if (evt.key === "Enter") {
            addPlayer();
        } else if (evt.key === "Escape") {
            cancelAndRemove();
        }
    }
}

function morphIntoTextField(parent) {
    return function (evt) {
        const textInput = document.createElement("input");
        const buttonNode = evt.target;
        const parentNode = buttonNode.parentNode;

        textInput.type = "text";
        textInput.className = "textcard";

        getText = () => textInput.value

        autocomplete = debounce(fillAutocompletion(getText, parentNode, textInput), 300);

        textInput.addEventListener('keydown', autocomplete);
        textInput.addEventListener('keypress', handleControlForText(addPlayer(textInput, getText, parentNode), () => parentNode.removeChild(textInput)));

        parentNode.insertBefore(textInput, buttonNode);
    }
}

function bindAddPlayerButtons() {
    const anchors = document.getElementsByClassName("buttoncard");
    for (let i = 0 ; i < anchors.length ; i++) {
        const elem = anchors[i];
        elem.addEventListener('click', morphIntoTextField(elem.parent));
    }
}

document.addEventListener("DOMContentLoaded", event => {
    bindAddPlayerButtons();
});

bindAddPlayerButtons();
