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
		// Wird erst beim ersten Speichern eines ColorSets erzeugt -- vorher nicht
		// einbinden, sonst laeuft jede Seite in einen 404.
		if(file_exists(BASE_PATH."/".ColorSet::STYLESHEET_PATH)){
			Requirements::css(ColorSet::STYLESHEET_PATH);
		}
	}
}