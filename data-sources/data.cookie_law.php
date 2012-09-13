<?php

require_once(TOOLKIT . '/class.datasource.php');
if(!class_exists('StaticXMLDatasource'))
{
	// S2.2.5-
	// Create a dummy:
	Class StaticXMLDatasource extends Datasource {}
}

Class datasourcecookie_law extends StaticXMLDatasource
{
	public $dsParamROOTELEMENT = 'cookie-law';
	// S2.2.5-: 	function __construct(&$parent, $env=NULL, $process_params=true)
	// S2.3: 		public function __construct($env = NULL, $process_params = true)
	public function __construct($param1 = null, $param2 = null, $param3 = true)
	{
		$version = class_exists('Frontend') ? Frontend::Configuration()->get('version', 'symphony') : Administration::Configuration()->get('version', 'symphony');
		if(version_compare($version, '2.2.5', '>'))
		{
			// S2.3+
			parent::__construct($param1, $param2);
		} else {
			// S2.2.5-
			parent::__construct($param1, $param2, $param3);
		}

		$this->_dependencies = array();
	}

	public function about()
	{
		return array(
			'name' => 'Cookie Law',
			'author' => array(
				'name' => 'Giel Berkers',
				'website' => 'http://www.gielberkers.com',
				'email' => 'info@gielberkers.com'),
			'version' => '1.0',
			'release-date' => '2012-09-04T10:02:43+00:00'
		);
	}

	public function getSource()
	{
		return 'static_xml';
	}

	public function allowEditorToParse()
	{
		return false;
	}

	public function execute(&$param_pool)
	{
		$result = new XMLElement($this->dsParamROOTELEMENT);


		$html = sprintf('
<script type="text/javascript">
	function cookie_action(){
			%s
	}
	if(!/cookie_accept=/.test(document.cookie))
	{
		document.write(unescape("%%3Cdiv id=\'cookies_bar\'%%3E" +
				"%s" +
				"%%3Ca href=\'#\' id=\'cookies_accept\'%%3E%s%%3C/a%%3E" +
				"%%3Ca href=\'#\' id=\'cookies_decline\'%%3E%s%%3C/a%%3E" +
				"%%3Cdiv id=\'cookies_disclaimer_box\'%%3E" +
				"%s" +
				"%%3C/div%%3E" +
				"%%3C/div%%3E"));
		setTimeout(function(){document.getElementById(\'cookies_bar\').className = \'enabled\';}, 1000);
		document.getElementById(\'cookies_disclaimer_box\').style.left = document.getElementById(\'cookies_disclaimer\').offsetLeft - 10 + \'px\';
		document.getElementById(\'cookies_accept\').onclick = function()
		{
			document.cookie = \'cookie_accept=1; expires=Tue, 31 Dec 2999 23:59:59 UTC; path=/\';
			document.getElementById(\'cookies_bar\').className = \'\';
			cookie_action();
			return false;
		};
		document.getElementById(\'cookies_decline\').onclick = function()
		{
			document.cookie = \'cookie_accept=0; path=/\';
			document.getElementById(\'cookies_bar\').className = \'\';
			return false;
		}
		document.getElementById(\'cookies_disclaimer\').onclick = function(){ return false; }
		document.getElementById(\'cookies_disclaimer\').onmouseover = function(){

			document.getElementById(\'cookies_disclaimer_box\').style.display = \'block\';
	 	}
		document.getElementById(\'cookies_disclaimer\').onmouseout = function(){
			document.getElementById(\'cookies_disclaimer_box\').style.display = \'none\';
	 	}
	}
	if (/cookie_accept=1/.test(document.cookie))
	{
		cookie_action();
	}
</script>',
			Symphony::Configuration()->get('javascript', 'cookie_law'),
			str_replace('{', '%3Ca href=\'#\' id=\'cookies_disclaimer\'%3E',
				str_replace('}', '%3C/a%3E', Symphony::Configuration()->get('text', 'cookie_law'))),
      Symphony::Configuration()->get('accept', 'cookie_law'),
      Symphony::Configuration()->get('decline', 'cookie_law'),
			str_replace(array("\r", "\n"), array('', '<br />'), Symphony::Configuration()->get('disclaimer', 'cookie_law'))
		);

		$result->appendChild(new XMLElement('html', $html));
		if (Symphony::Configuration()->get('default_styling', 'cookie_law') == 'yes') {
			$result->appendChild(new XMLElement('styling', '
				#cookies_bar { position: fixed; left: 0; bottom: -28px; z-index: 1000; background: #000; line-height: 14px; vertical-align: middle; background: rgba(0, 0, 0, .8); width: 100%; height: 12px; padding: 8px 0; text-align: center; color: #fff; font-size: 12px; -webkit-transition: bottom 1000ms; -moz-transition: bottom 1000ms; -ms-transition: bottom 1000ms; -o-transition: bottom 1000ms; transition: bottom 1000ms; }
				#cookies_bar.enabled { bottom: 0px; }
				#cookies_accept { border-radius: 3px; background: #0c0; border: 1px solid #080; margin-left: 10px; color: #fff; text-shadow: 0 1px 2px #000; text-decoration: none; padding: 1px 3px; }
				#cookies_decline { color: #666; margin-left: 10px; text-decoration: none; }
				#cookies_disclaimer { color: #fff; }
				#cookies_disclaimer_box { color: #fff; position: absolute; bottom: 30px; width: 300px; background: #000; background: rgba(0, 0, 0, .8); text-align: left; padding: 10px; border-radius: 3px; display: none; }
			'));
		}
		return $result;
	}

	// For S2.2.5-:
	function grab(&$param_pool){
		return $this->execute($param_pool);
	}
}
