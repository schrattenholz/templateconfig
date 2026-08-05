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

	/**
	 * Pfad des generierten Stylesheets, relativ zum Projekt-Root.
	 *
	 * Liegt bewusst unter public/assets/ und nicht mehr im Composer-Ordner
	 * (public/_resources/vendor/...): dorthin darf der Webserver-Benutzer nicht
	 * schreiben (Composer legt alles als root an), und jedes composer install
	 * wuerde die im CMS eingestellten Farben ueberschreiben.
	 */
	const STYLESHEET_PATH='public/assets/colorsets.css';

	public function onAfterWrite(){
		parent::onAfterWrite();
		ColorSet::writeStylesheet();
	}
	public function onAfterDelete(){
		parent::onAfterDelete();
		ColorSet::writeStylesheet();
	}
	/**
	 * Alle Sets einsammeln um das Stylesheet-File zu aktualisieren.
	 */
	public static function writeStylesheet(){
		$set="    ";
		foreach(ColorSet::get() as $cs){
				$set.=$cs->generateCSS();
		}
		$file=BASE_PATH."/".ColorSet::STYLESHEET_PATH;
		file_put_contents($file,$set);
		// Die Datei wird mal vom Webserver-Benutzer (Speichern im CMS), mal von
		// root (dev/build, Deploy-Skripte) geschrieben. Ohne das chmod sperrt der
		// jeweils andere sich selbst aus -- genau das war der urspruengliche
		// "Failed to open stream: Permission denied" im CMS.
		@chmod($file,0666);
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
		$css.=".colorSet".$this->ID." h1,.colorSet".$this->ID." h2,.colorSet".$this->ID." h3,.colorSet".$this->ID." h4,.colorSet".$this->ID." h5,.colorSet".$this->ID." h6{";
		if($this->HColor){
			$css.='color:#'.$this->HColor.' !important;';
		}else{
			$css.='color:#'.$this->FontColor.' !important;';
		}
		$css.='} ';
		$css.='.colorSet'.$this->ID.' a, .colorSet'.$this->ID.' .userform label, .colorSet'.$this->ID.' a:visited, .colorSet'.$this->ID.' a:hover, .colorSet'.$this->ID.' a:focus, .colorSet'.$this->ID.' a:active{color:#'.$this->FontColor.' !important;}';
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
