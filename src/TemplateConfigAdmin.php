<?php

namespace Schrattenholz\TemplateConfig;

use SilverStripe\Admin\ModelAdmin;

class TemplateConfigAdmin extends ModelAdmin
{

    private static $menu_title = 'TemplateConfig';

    private static $url_segment = 'templateconfig';

    private static $managed_models = [
        ColorSet::class
    ];
}