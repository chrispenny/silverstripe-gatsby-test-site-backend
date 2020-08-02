<?php

namespace App\Elemental\ImageWithText;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;

/**
 * Class ImageWithTextBlock
 *
 * @package App\Elemental\ImageWithText
 * @property string $Content
 * @property string $HeadingStyle
 * @property int $ImageID
 * @method Image Image()
 */
class ImageWithTextBlock extends BaseElement
{
    /**
     * @var string
     */
    private static $icon = 'font-icon-news';

    /**
     * @var string
     */
    private static $table_name = 'ImageWithTextBlock';

    /**
     * @var string
     */
    private static $singular_name = 'Image with text block';

    /**
     * @var string
     */
    private static $plural_name = 'Image with text blocks';

    /**
     * @var string
     */
    private static $description = 'A basic block with an image and text';

    /**
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
        'HeadingStyle' => "Enum('Bold,Regular', 'Regular')",
        'ImageAlignment' => "Enum('left,right', 'left')",
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Image' => Image::class,
    ];

    /**
     * @var array
     */
    private static $owns = [
        'Image',
    ];

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getType(): string
    {
        return 'Image with Text';
    }

    /**
     * @codeCoverageIgnore
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        // Remove fields so that they can be re-added in the correct order
        $fields->removeByName([
            'Content',
            'ImageID',
        ]);

        $fields->addFieldsToTab(
            'Root.Main',
            [
                DropdownField::create(
                    'HeadingStyle',
                    'Heading Style',
                    $this->dbObject('HeadingStyle')->enumValues()
                ),
                $contentField = HTMLEditorField::create('Content'),
                UploadField::create('Image'),
                DropdownField::create(
                    'ImageAlignment',
                    'Image Alignment',
                    $this->dbObject('ImageAlignment')->enumValues()
                ),
            ]
        );

        return $fields;
    }

    /**
     * @codeCoverageIgnore
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create([
            'Content',
            'Image',
        ]);
    }
}
