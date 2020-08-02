<?php

namespace App\Testimonial;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\Versioned\Versioned;

/**
 * Class Model
 *
 * @package App\Testimonial
 * @property string $Content
 * @property int $ImageID
 * @property string $Title
 * @method Image Image()
 * @mixin Versioned
 */
class Testimonial extends DataObject implements PermissionProvider
{
    const PERMISSION_TESTIMONIAL_CREATE = 'PERMISSION_TESTIMONIAL_CREATE';
    const PERMISSION_TESTIMONIAL_DELETE = 'PERMISSION_TESTIMONIAL_DELETE';
    const PERMISSION_TESTIMONIAL_EDIT = 'PERMISSION_TESTIMONIAL_EDIT';
    const PERMISSION_TESTIMONIAL_VIEW = 'PERMISSION_TESTIMONIAL_VIEW';

    /**
     * @var array
     */
    private static $extensions = [
        Versioned::class,
    ];

    /**
     * @var array
     */
    private static $db = [
        'Content' => 'Text',
        'Title' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Image' => Image::class,
    ];

    /**
     * @var string
     */
    private static $table_name = 'Testimonial';

    /**
     * @var string
     */
    private static $singular_name = 'Testimonial';

    /**
     * @var string
     */
    private static $plural_name = 'Testimonials';

    /**
     * @var array
     */
    private static $owns = [
        'Image',
    ];

    /**
     * @codeCoverageIgnore
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        // Remove scaffolded fields so that we can add them again in the correct order.
        $fields->removeByName([
            'Content',
            'Title',
            'Image',
        ]);

        $fields->addFieldsToTab(
            'Root.Main',
            [
                $titleField = TextField::create('Title'),
                $contentField = TextareaField::create('Content'),
                $imageField = UploadField::create('Image', 'Image'),
            ]
        );

        $titleField->setDescription('Required. Do not include surrounding quotation marks.');
        $contentField->setDescription('Required');
        $imageField
            ->setDescription('Optional')
            ->setAllowedFileCategories('image');

        return $fields;
    }

    /**
     * @codeCoverageIgnore
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create([
            'Title',
            'Content',
        ]);
    }

    /**
     * @return array
     */
    public function providePermissions(): array
    {
        return [
            self::PERMISSION_TESTIMONIAL_CREATE => 'Testimonials - create',
            self::PERMISSION_TESTIMONIAL_DELETE => 'Testimonials - delete',
            self::PERMISSION_TESTIMONIAL_EDIT => 'Testimonials - edit',
            self::PERMISSION_TESTIMONIAL_VIEW => 'Testimonials - view',
        ];
    }

    /**
     * @param mixed $member
     * @param mixed $context
     * @return bool
     */
    public function canCreate($member = null, $context = []): bool
    {
        return (bool) Permission::check(self::PERMISSION_TESTIMONIAL_CREATE, 'any', $member);
    }

    /**
     * @param mixed $member
     * @return bool
     */
    public function canDelete($member = null): bool
    {
        return (bool) Permission::check(self::PERMISSION_TESTIMONIAL_DELETE, 'any', $member);
    }

    /**
     * @param mixed $member
     * @return bool
     */
    public function canEdit($member = null): bool
    {
        return (bool) Permission::check(self::PERMISSION_TESTIMONIAL_EDIT, 'any', $member);
    }

    /**
     * @param mixed $member
     * @return bool
     */
    public function canView($member = null): bool
    {
        return (bool) Permission::check(self::PERMISSION_TESTIMONIAL_VIEW, 'any', $member);
    }
}
