<?php


namespace Schrattenholz\TemplateConfig;


use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TabSet;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Assets\Image;

use TractorCow\Colorpicker\Color;
use TractorCow\Colorpicker\Forms\ColorField;
use SilverStripe\Security\Permission;
use SilverStripe\ORM\ValidationException;
use Psr\Log\LoggerInterface;
use SilverStripe\Core\Injector\Injector;
class ColorSet extends DataObject{
	private static $table_name="colorset";
	private static $db=[
		'Title'=>'Varchar(255)',
		'BgColor'=>Color::class,
		'BgIsTransparent'=>'Boolean',
		'FontColor'=>Color::class,
		'HColor'=>Color::class,
		'MarkupBefore'=>'HTMLText',
		'MarkupAfter'=>'HTMLText',
		'Class'=>'Text'
	];
	public function getCMSFields(){
		$title=new TextField('Title','Bezeichnung');
		$bgColor=	new ColorField('BgColor','Hintergrundfarbe');
		$bgIsTransparent=	new CheckboxField('BgIsTransparent','Hintergrund transparent');
		$fontColor=	new ColorField('FontColor','Schriftfarbe');
		$hColor=	new ColorField('HColor','Farbe für Überschriften');
		$markupBefore=new HTMLEditorField("MarkupBefore","MarkupBefore");
		$markupAfter=new HTMLEditorField("MarkupAfter","MarkupAfter");
		$class=new TextField("Class","CSS-Klasse");
		return new FieldList(
			array(
				$title,
				$bgColor,
				$bgIsTransparent,
				$fontColor,
				$hColor,
				$markupBefore,
				$markupAfter,
				$class
			)
		);
	}

	public function onAfterWrite(){
		parent::onAfterWrite();
		// Alle Set einsammeln um das Stylesheet-File zu aktualisieren
		$colorSets=ColorSet::get();
		$set="    ";
		foreach($colorSets as $cs){
				$set.=$cs->generateCSS();
		}
		Injector::inst()->get(LoggerInterface::class)->error('ColorSep.php onAfterWrite PATH='.BASE_PATH);
		$file=BASE_PATH."/public/_resources/vendor/schrattenholz/templateconfig/css/colorsets.css";
		file_put_contents($file,$set);
	}
	public function generateCSS(){
		//Stylesheet generieren
		$css=".colorSet".$this->ID."{";
		if($this->BgIsTransparent){
			$css.='background-color:transparent !important;';
		}else{
			$css.='background-color:#'.$this->BgColor.' !important;';
		}
		$css.='color:#'.$this->FontColor.' !important;';
		$css.='} ';
		$css.=".colorSet".$this->ID." h1,.colorSet".$this->ID." h2,.colorSet".$this->ID." h3,.colorSet".$this->ID." h4{";
		if($this->HColor){
			$css.='color:#'.$this->HColor.' !important;';
		}else{
			$css.='color:#'.$this->FontColor.' !important;';
		}
		$css.='} ';
		return $css;
	}
	 public function canView($member = null) 
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canEdit($member = null) 
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null) 
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = []) 
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }
}
