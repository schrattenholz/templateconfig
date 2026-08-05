<?php

namespace Schrattenholz\TemplateConfig;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;
class TemplateConfig_SiteTreeExtension extends Extension {
	private static $has_one=[
		"ColorSet"=>ColorSet::class
	];
	public function updateCMSFields(FieldList $fields) {
		$colorSet=new DropdownField("ColorSetID","Farbschema",ColorSet::get()->map('ID', 'Title'));
		$colorSet->setEmptyString('(Optionles Farbschema)');
		
		$fields->addFieldToTab("Root.Main",$colorSet,"Content");
		
	}
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