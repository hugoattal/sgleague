<?php
class Template
{
	public $page = "home";

	public function __construct()
	{
		if (isset($_GET["page"]))
		{
			$this->page = ctype_alpha($_GET["page"]) ? strtolower($_GET["page"]) : 'home';
			$this->page = file_exists("./page/".$this->page) ? $this->page : 'home';
		}
		
		include("./pages/".$this->page."/config.php");
	}

	public function header()
	{
		include("./template/header.php");
	}

	public function content()
	{
		include("./pages/".$this->page."/content.php");
	}

	public function footer()
	{
		include("./template/footer.php");
	}
}
?>