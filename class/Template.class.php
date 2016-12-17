<?php
class Template
{
	public $page = "home";

	public function __construct()
	{
		if (isset($_GET["page"]))
		{
			$this->page = ctype_alpha($_GET["page"]) ? strtolower($_GET["page"]) : 'home';
			$this->page = file_exists("./pages/".$this->page) ? $this->page : 'home';
		}
		
		include("./pages/".$this->page."/config.php");

		include("./template/header.php");

		include("./pages/".$this->page."/content.php");

		include("./template/footer.php");
	}
}
?>