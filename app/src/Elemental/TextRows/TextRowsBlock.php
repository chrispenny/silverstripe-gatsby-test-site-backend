<?php

namespace App\Elemental\TextRows;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\HasManyList;
use SilverStripe\Versioned\Versioned;
use SilverStripe\View\ArrayData;

/**
 * Class TextRowsBlock
 *
 * @package App\Elemental\TextRows
 * @method HasManyList|TextRowsItem[] Items()
 * @mixin Versioned
 */
class TextRowsBlock extends BaseElement
{
    /**
     * @var array
     */
    private static $db = [
        'HeadingStyle' => "Enum('Bold,Regular', 'Regular')",
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Items' => TextRowsItem::class,
    ];

    /**
     * @var array
     */
    private static $owns = [
        'Items',
    ];

    /**
     * @var array
     */
    private static $cascade_deletes = [
        'Items',
    ];

    /**
     * @var array
     */
    private static $cascade_duplicates = [
        'Items',
    ];

    /**
     * @var string
     */
    private static $icon = 'font-icon-p-gallery';

    /**
     * @var string
     */
    private static $table_name = 'TextRowsBlock';

    /**
     * @var string
     */
    private static $singular_name = 'Text rows block';

    /**
     * @var string
     */
    private static $plural_name = 'Text rows blocks';

    /**
     * @var string
     */
    private static $description = 'Rows of text paragraphs';

    /**
     * This Block cannot be edited inline as it contains a GridField
     *
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getType(): string
    {
        return 'Text Rows';
    }

    /**
     * @codeCoverageIgnore
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab(
            'Root.Main',
            [
                DropdownField::create(
                    'HeadingStyle',
                    'Heading Style',
                    $this->dbObject('HeadingStyle')->enumValues()
                ),
            ]
        );

        $itemsTab = $fields->findOrMakeTab('Root.Items');

        // Grab the "Items" GridField
        /** @var GridField $items */
        $items = $itemsTab->fieldByName('Items');

        // The only time this would be null, is if this Form is somehow called for inline editing. We can just return
        // the fields as is, if that's the case
        if ($items === null) {
            $fields->addFieldToTab(
                'Root.Main',
                LiteralField::create(
                    'ItemsMessage',
                    '<p class="message notice">You can add Items once you have saved a title for this block</p>'
                )
            );

            return $fields;
        }

        if ($this->Items()->count() < 1) {
            $fields->unshift(
                LiteralField::create(
                    'ItemsMessage',
                    '<p class="message error">You must have at least 1 items added for this block to be displayed</p>'
                )
            );
        }

        // Remove the Items tab.
        $fields->removeByName('Items');

        // Set our configuration
//        $items
//            ->orderableConfig()
//            ->nonFilterableConfig()
//            ->noSharedObjectsConfig();

        // Add the Items GridField back to the Main tab.
        $fields->addFieldToTab('Root.Main', $items);

        return $fields;
    }

    /**
     * @return bool
     */
    public function getIsValid(): bool
    {
        return $this->Items()->count() > 0;
    }

    /**
     * @return bool
     */
    public function checkNeedPublishNestedObjects(): bool
    {
        foreach ($this->Items() as $item) {
            if (!$item->isPublished()) {
                return true;
            }

            if ($item->stagesDiffer()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ArrayList
     */
    public function getGroupedItems(): ArrayList
    {
        $itemsGrouped = ArrayList::create();
        $chunks = array_chunk($this->Items()->toArray(), 2);

        foreach ($chunks as $chunk) {
            $itemsGrouped->add(
                ArrayData::create([
                    'Items' => ArrayList::create($chunk),
                ])
            );
        }

        return $itemsGrouped;
    }
}
