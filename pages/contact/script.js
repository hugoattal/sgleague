function loadMail()
{
	d3.selectAll(".contact")
		.each(function(d, i) {
			d3.select(this)
				.attr("href", "mailto:"+this.dataset.mail+"@sgnw.fr")
				.select(".cmail")
				.text(this.dataset.mail+"@sgnw.fr");
			});
}