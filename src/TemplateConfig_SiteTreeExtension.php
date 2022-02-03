<?php

namespace Schrattenholz\TemplateConfig;

use Silverstripe\ORM\DataExtension;
use SilverStripe\View\Requirements;
use Silverstripe\Forms\FieldList;
use Silverstripe\Forms\DropdownField;
class TemplateConfig_SiteTreeExtension extends DataExtension {
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
		Requirements::css("public/_resources/vendor/schrattenholz/templateconfig/css/colorsets.css");
		/* 
		//Funktioniert nicht wird direkt im template eingebunden
		Requirements::css("public/resources/vendor/schrattenholz/templateconfig/css/colorsets.css");
		*/
	}
}