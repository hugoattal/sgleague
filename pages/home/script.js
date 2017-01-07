function lightGames()
{
	document.getElementById("games").scrollIntoView({behavior: 'smooth'});
	document.getElementById("games").style["boxShadow"] = "0px 0px 50px rgba(0, 0, 0, 0.5);";

	var darkdiv = d3.select("body")
		.append("div")
		.classed("darkbg", true)
		.attr("onclick", "destroyDark()");

	window.getComputedStyle(darkdiv.node()).opacity;

	darkdiv.style("opacity", "0.6");
}

function destroyDark()
{
	d3.selectAll(".darkbg").remove();
}