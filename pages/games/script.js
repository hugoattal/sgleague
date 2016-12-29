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


function getAutocompletionForPseudo(pseudo, game) {
	return doRequest('api.php', {
		queryParams: {
			'type': 'search',
			'data': pseudo,
			'game': game
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


function createPlayerNode(parentNode, id, name, role="Joueur")
{
	const index = getIndexOfMember(parentNode);
	const playerCard = parentNode;
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

	playerCard.appendChild(playerName);
	playerCard.appendChild(playerType);
	playerCard.appendChild(hiddenInput);

	d3.select(parentNode).style("opacity", "0.5");

	return playerCard;	
}

function buildPrettyTextNode(login, school) {
	const loginNode = document.createTextNode(login);
	const schoolNode = document.createTextNode(school);

	const container = document.createElement('div');
	const loginContainer = document.createElement('span');
	const schoolContainer = document.createElement('span');
	schoolContainer.setAttribute('class', 'playerschool');

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

function addPlayer(textInput, player, parentNode) {
	return function () {
		const playerName = player.login;

		if (GLOBALS.existingPlayers.includes(playerName) && !getCurrentPlayersInTeam(parentNode).includes(playerName)) {
			console.log('Adding %s to the team.', playerName);
			var playerType = {2:"Joueur", 3:"Remplaçant"};
			createPlayerNode(parentNode, GLOBALS.mapPlayers[playerName].id, playerName, playerType[parentNode.dataset.type]);
			parentNode.removeChild(textInput);
			textInput = null;

			doRequest('api.php', {
				queryParams: {
					'type': 'team_add',
					'player': player.id,
					'game': parentNode.dataset.game,
					'ptype': parentNode.dataset.type
				}
			});

			setTimeout(() => {
				parentNode.removeChild(GLOBALS.currentDropdownSuggestionNode);
				GLOBALS.currentDropdownSuggestionNode = null;
			}, 0);	
		} else {
			// TODO: show error.
		}
	}
}

function selectCurrentSuggestion(player, textInput)
{
	return evt => {
		console.log(player);
		addPlayer(textInput, player, textInput.parentNode)();
	}
}

function getCurrentPlayersInTeam(teamNode)
{
	return Array.from(teamNode.getElementsByClassName('playercard')).map(child => child.firstChild.firstChild.data);
}

function createOrChangeCurrentSuggestions(parentNode, textInput, currentContent, players)
{
	const container = GLOBALS.currentDropdownSuggestionNode || document.createElement('ul');
	d3.select(container).classed("dropdown", true);
	const addedPlayers = getCurrentPlayersInTeam(parentNode);
	//console.log(addedPlayers);

	const nodes = players ? players.filter(player => !addedPlayers.includes(player.login)).map(player => {
		const node = document.createElement('li');
		node.setAttribute('data-id', player.id);
		node.appendChild(buildPrettyTextNode(player.login, player.school));
		node.onmousedown = selectCurrentSuggestion(player, textInput);
		return node
	}) : [];

	// Inject to parent.
	// Reset the HTML.
	container.innerHTML = "";
	if (currentContent !== "")
	{
		for (let i = 0 ; i < nodes.length ; i++)
		{
			container.appendChild(nodes[i]);
		}

		if (nodes.length == 0)
		{
			d3.select(container)
				.append("li")
				.append("div")
				.append("span")
				.text("Aucun joueur trouvé...");
		}
	}
	else
	{
		d3.select(container)
				.append("li")
				.append("div")
				.append("span")
				.text("Commencez à taper un pseudo ou un mail");
	}

	if (!GLOBALS.currentDropdownSuggestionNode)
	{
		parentNode.insertBefore(container, textInput.nextSibling);
	}

	GLOBALS.currentDropdownSuggestionNode = container;
}

function fillAutocompletion(getTextContent, game, parentNode, textInput) {
	return function ()
	{
		getAutocompletionForPseudo(getTextContent(), game).then(players => {
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

function morphIntoTextField(element, game)
{
	return function (evt)
	{
		const textInput = d3.select(element)
			.attr("onclick", null)
			.classed("buttoncard", false)
			.classed("playercard", true)
			.html("")
			.append("input")
			.attr("placeholder", "Joueur")
			.attr("onblur", "cancelAndRemove(this)")
			.node();

		textInput.focus();

		getText = () => textInput.value;

		autocomplete = debounce(fillAutocompletion(getText, game, textInput.parentNode, textInput), 300);

		textInput.addEventListener('keydown', autocomplete);
		textInput.addEventListener('focus', autocomplete);
	}
}

function cancelAndRemove(element)
{
	element.parentNode.removeChild(d3.selectAll(".dropdown")[0][0]);
	GLOBALS.currentDropdownSuggestionNode = null;
}