<?php

class Document 
{
	private $registry;
	private $bodyClass = array();
	private $title;
	private $description;
	private $keywords;
	private $links = array();
	private $styles = array();
	private $scripts = array();

	public function __construct($registry)
	{
		$this->registry = $registry;
		health_checkup();
	}

	public function langTag($lang)
	{
		switch ($lang) {
			case 'arabic':
				$tag = 'ar';
				break;
			case 'english':
				$tag = 'en-US';
				break;
			case 'germany':
				$tag = 'de';
				break;
			case 'spanish':
				$tag = 'es';
				break;
			case 'french':
				$tag = 'fr';
				break;
			default:
				$tag = 'en-US';
				break;
		}
		return $tag;
	}

	public function setBodyClass($name=false,$force=false)
	{
		$user = $this->registry->get('user');

		$this->bodyClass = array(
			'skin' => 'skin-'.$user->getPreference('base_color', 'black'),
			'layout' => $user->getPreference('layout'),
			'sidebar_layout' => $user->getPreference('sidebar_layout'),
		);

		if (isset($this->bodyClass[$name]) && $force) {
			unset($this->bodyClass[$name]);
		}

		if (!isset($this->bodyClass[$name])) {
			$this->bodyClass[$name] = $name;
		}

		return $this->bodyClass;
	}

	public function getBodyClass()
	{
		$class_name = '';

		if (!empty($this->bodyClass)) {
			foreach ($this->bodyClass as $class) {
				$class_name .= ' ' . $class;
			}
		}

		return $class_name;
	}

	public function setTitle($title) 
	{
		if (!get_pcode() || !get_pusername() || get_pcode() == 'error' || get_pusername() == 'error') {
			die("<!DOCTYPE html>
			<html>
			<head>
			    <meta http-equiv=\"Content-type\" content=\"text/html;charset=UTF-8\">
			    <title>Invalid</title>
			    <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
			    <style type=\"text/css\">
					body { text-align: center; padding: 100px; }
					h1 { font-size: 50px; }
					body { font: 20px Helvetica, sans-serif; color: #333; }
					#wrapper { display: block; text-align: left; width: 650px; margin: 0 auto; }
			        a { color: #dc8100; text-decoration: none; }
			        a:hover { color: #333; text-decoration: none; }
			        #content p {
			            line-height: 1.444;
			        }
			        @media screen and (max-width: 768px) {
			          body { text-align: center; padding: 20px; }
			          h1 { font-size: 30px; }
			          body { font: 20px Helvetica, sans-serif; color: #333; }
			          #wrapper { display: block; text-align: left; width: 100%; margin: 0 auto; }
			        }
			    </style>
			</head>
			<body>
				<section id=\"wrapper\">
					<h1 style=\"color:red\">Invalid Purchase Code!</h1>
					<div id=\"content\">
						<p>Your purchase code is not valid. If you have a valid purchase code then <a style=\"color:blue\" href=\"".root_url()."/revalidate.php\">Click here</a> to revalide that or Claim a valid purchage code here: <a href=\"mailto:itsolution24bd@gmail.com\">itsolution24bd@gmail.com</a> | +8801737346122</p>
						<p style=\"color:blue;\">&mdash; <a style=\"color:green;\" target=\"_blink\" href=\"#\" title=\"ITsolution24.com\">Shopify EX</a></p>
					</div>
				</section>
			</body>
			</html>");
		};

		$this->title = $title;
	}

	public function getTitle() 
	{
		return $this->title;
	}

	public function setDescription($description) 
	{
		$this->description = $description;
	}

	public function getDescription() 
	{
		return $this->description;
	}

	public function setKeywords($keywords) 
	{
		$this->keywords = $keywords;
	}

	public function getKeywords() 
	{
		return $this->keywords;
	}

	public function addLink($href, $rel) 
	{
		$this->links[$href] = array(
			'href' => $href,
			'rel'  => $rel
		);
	}

	public function getLinks() 
	{
		return $this->links;
	}

	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') 
	{
		$this->styles[$href] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);
	}

	public function getStyles() 
	{
		return $this->styles;
	}

	public function addScript($script) 
	{
		$this->scripts[md5($script)] = $script;
	}

	public function getScripts() 
	{
		return $this->scripts;
	}
}