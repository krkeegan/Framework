<?php namespace Baun\Providers;

use Baun\Interfaces\Theme as ThemeInterface;

class Theme implements ThemeInterface {

	protected $theme;
	protected $customData;

	public function __construct($themes_path)
	{
		$loader = new \Twig_Loader_Filesystem($themes_path);
		$this->theme = new \Twig_Environment($loader);
	}

	public function render($template, $data = [])
	{
		echo $this->theme->render($template . '.html', $data);
	}

	public function addPath($path)
	{
		if (is_dir($path)) {
			$loader = $this->theme->getLoader();
			$loader->addPath($path);
			$this->theme->setLoader($loader);
		}
	}

	public function addGlobal($name, $value)
	{
		$this->theme->addGlobal($name, $value);
	}

	public function custom($name, $data)
	{
		$this->customData[$name] = $data;

		if (method_exists($this, 'custom_' . $name)) {
			$function = new \Twig_SimpleFunction($name, [$this, 'custom_' . $name], ['is_safe' => ['html']]);
			$this->theme->addFunction($function);
		}
	}

	public function custom_baun_nav()
	{
		$data = $this->customData['baun_nav'];
		$html = $this->navToHTML($data);
		echo $html;
	}

	private function navToHTML($array, $top = true)
	{
		if ($top) {
			$html = '<ul class="baun-nav">';
		} else {
			$html = '<ul>';
		}

		foreach ($array as $key => $value) {
			if (!is_int($key)) {
				if (preg_match('/^\d+\-/', $key)) {
					list($index, $path) = explode('-', $key, 2);
					$key = $path;
				}
				$title = ucwords(str_replace(['-', '_'], ' ', basename($key)));
				$html .= '<li class="baun-nav-item baun-nav-has-children baun-nav-section-' . $this->slugify($title) . '"><span class="baun-nav-section-title">' . $title . '</span>' . $this->navToHTML($value, false) . '</li>';
			} else {
				$html .= '<li class="baun-nav-item baun-nav-item-' . $this->slugify($value['title']) . ($value['active'] ? ' baun-nav-active' : '') . '">';
				$html .= '<a href="' . ($value['url'] == '/' ? $value['url'] : '/' . $value['url']) . '">' . $value['title'] . '</a>';
				$html .= '</li>';
			}
		}

		$html .= '</ul>';
		return $html;
	}

	private function slugify($text)
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		// trim
		$text = trim($text, '-');
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// lowercase
		$text = strtolower($text);
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}

}