<?php

namespace Schrattenholz\TemplateConfig;

use SilverStripe\Core\Extension; 
use SilverStripe\View\Requirements;
class TemplateConfigExtension extends Extension {
	private static $allowed_actions = array (
		'TemplateConfigExtensionTest'
	);
	public function TemplateConfigExtensionTest(){
		return "TemplateConfigExtensionTest";
	}
	public function onAfterInit () {
		//Requirements::css("public/_resources/vendor/schrattenholz/templateconfig/css/colorsets.css");
		/* 
		//Funktioniert nicht wird direkt im template eingebunden
		Requirements::css("public/resources/vendor/schrattenholz/templateconfig/css/colorsets.css");
		*/
	}
}