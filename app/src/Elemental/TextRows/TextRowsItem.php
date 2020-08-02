<?php

namespace App\Elemental\TextRows;

use App\Elemental\BaseItem;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;

/**
 * Class Item
 *
 * @package App\Elemental\TextRows
 * @property string $Content
 * @method TextRowsBlock $Parent
 */
class TextRowsItem extends BaseItem\BaseItem
{
    /**
     * @var string
     */
    private static $table_name = 'TextRowsItem';

    /**
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Parent' => TextRowsBlock::class,
    ];

    /**
     * @var array
     */
    private static $owned_by = [
        'Parent',
    ];

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
                $contentField = HTMLEditorField::create('Content'),
            ]
        );

        $contentField->setDescription('Required');

        return $fields;
    }

    /**
     * @codeCoverageIgnore
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        $validator = parent::getCMSValidator();
        $validator->addRequiredField('Content');

        return $validator;
    }
}
