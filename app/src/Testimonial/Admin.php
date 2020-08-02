<?php

namespace App\Testimonial;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldImportButton;
use SilverStripe\Forms\GridField\GridFieldPrintButton;

/**
 * Class Admin
 *
 * @codeCoverageIgnore
 * @package App\Testimonial
 */
class Admin extends ModelAdmin
{
    /**
     * @var array
     */
    private static $managed_models = [
        Testimonial::class => ['title' => 'Testimonials'],
    ];

    /**
     * @var string
     */
    private static $menu_title = 'Testimonials';

    /**
     * @var string
     */
    private static $url_segment = 'testimonials';

    /**
     * @var string
     */
    private static $menu_icon_class = 'font-icon-comment';

    /**
     * @param int|null $id
     * @param FieldList|null $fields
     * @return Form
     */
    public function getEditForm($id = null, $fields = null): Form
    {
        $form = parent::getEditForm($id, $fields);

        /** @var GridField $gridField */
        $gridField = $form->Fields()->fieldByName('App-Testimonial-Model');

        if ($gridField) {
            $config = $gridField->getConfig();

            $config->removeComponentsByType([
                GridFieldImportButton::class,
                GridFieldFilterHeader::class,
                GridFieldPrintButton::class,
                GridFieldExportButton::class,
            ]);
        }

        return $form;
    }
}
