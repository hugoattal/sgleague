<?php

// TODO : add 404 and connection needed

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

		if (isset($need_connection) && (!isset($_SESSION["sgl_id"])))
		{
			$this->page = "403";

			unset($page_head);
			unset($page_script);
		}
		else if (isset($min_access))
		{
			if ($_SESSION["sgl_type"] < $min_access)
			{
				$this->page = "403";

				unset($page_head);
				unset($page_script);
			}
		}

		define("CURRENT_PAGE", $this->page);

		include("./template/header.php");
		include("./pages/".$this->page."/content.php");
		include("./template/footer.php");
	}
}
?>