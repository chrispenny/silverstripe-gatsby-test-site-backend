<?php

namespace App\Elemental\BaseItem;

use App\Elemental;
use DNADesign\Elemental\Forms\TextCheckboxGroupField;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Versioned\Versioned;

/**
 * Class BaseItem
 *
 * This is a base class for items (objects nested within blocks that aren't blocks).
 *
 * Common functionality and extensions can be applied on this class so we won't need to apply this to all items
 * individually.
 *
 * This class should be treated as abstract class.
 * When extending this class, you should define the parent Block as `Parent`.
 *
 * @package App\Models\Item
 * @property int $ParentID
 * @property int $ShowTitle
 * @property int $Sort
 * @property string $Title
 * @method BaseElement Parent()
 * @mixin Versioned
 */
class BaseItem extends DataObject
{
    /**
     * @var array
     */
    private static $extensions = [
        Versioned::class,
    ];

    /**
     * @var string
     */
    private static $table_name = 'BaseItem';

    /**
     * @var array
     */
    private static $db = [
        'ShowTitle' => 'Boolean',
        'Sort' => 'Int',
        'Title' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $default_sort = 'Sort';

    /**
     * @var bool
     */
    private static $hide_publish_button = true;

    /**
     * Most Item classes require a Title field be filled in. These classes therefor *cannot* use the combined
     * Title/ShowTitle field (as that field type does not have validation message support). So, instead, Items that
     * require a Title, will simply use the standard TextField.
     *
     * @var bool
     */
    private static $title_field_required = true;

    /**
     * @var Elemental\Page|null
     */
    private $blockPage;

    /**
     * @codeCoverageIgnore
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        // Remove all default fields but keep all tabs
        foreach (['db', 'has_one'] as $fieldType) {
            $fieldData = static::config()->get($fieldType);

            if (!is_array($fieldData)) {
                continue;
            }

            $fieldsToRemove = array_keys($fieldData);

            if ($fieldType === 'has_one') {
                foreach ($fieldData as $name => $value) {
                    $fieldsToRemove[] = $name . 'ID';
                }
            }

            $fields->removeByName($fieldsToRemove);
        }

        static::config()->get('title_field_required')
            ? $titleField = TextField::create('Title')->setDescription('Required')
            : $titleField = TextCheckboxGroupField::create()->setName('Title');

        $fields->addFieldToTab(
            'Root.Main',
            $titleField
        );

        return $fields;
    }

    /**
     * @return BaseItem
     */
    public function populateDefaults(): BaseItem
    {
        parent::populateDefaults();

        $this->ShowTitle = 1;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        if (!static::config()->get('title_field_required')) {
            return RequiredFields::create([]);
        }

        return RequiredFields::create([
            'Title',
        ]);
    }

    /**
     * @param mixed $member
     * @param mixed $context
     * @return bool
     */
    public function canCreate($member = null, $context = []): bool
    {
        return (bool) $this->Parent()->canCreate($member, $context);
    }

    /**
     * @param mixed $member
     * @return bool
     */
    public function canDelete($member = null): bool
    {
        return (bool) $this->Parent()->canDelete($member);
    }

    /**
     * @param mixed $member
     * @return bool
     */
    public function canEdit($member = null): bool
    {
        return (bool) $this->Parent()->canEdit($member);
    }

    /**
     * @param mixed $member
     * @return bool
     */
    public function canView($member = null): bool
    {
        return (bool) $this->Parent()->canView($member);
    }

    public function onBeforeWrite(): void
    {
        parent::onBeforeWrite();

        if (!static::config()->get('title_field_required')) {
            $this->setDefaultTitle();
        }

        $this->setDefaultSort();
    }

    /**
     * @return SiteTree|Elemental\Page|null
     * @throws ValidationException
     */
    public function getBlockPage(): ?Elemental\Page
    {
        if ($this->blockPage !== null) {
            return $this->blockPage;
        }

        // Assumes that you have defined the Parent() relationship for this Item.
        if (!$this->hasField('ParentID')) {
            return null;
        }

        if (!$this->ParentID) {
            return null;
        }

        if (!$this->Parent() instanceof BaseElement) {
            return null;
        }

        $this->blockPage = $this->Parent()->getPage();

        return $this->blockPage;
    }

    protected function setDefaultTitle(): void
    {
        // A Title has already been set, so we don't need to do anything here
        if ($this->Title) {
            return;
        }

        $this->ShowTitle = 0;
        $this->Title = 'Untitled Item';
    }

    protected function setDefaultSort(): void
    {
        // A Sort has already been set, so we don't need to do anything here
        if ($this->Sort) {
            return;
        }

        // Set the Sort value of this Item to the max for the table. It doesn't matter that these default Sort values
        // are not going to be in sequence. The next time the GridField is ordered, they will go back to 1, 2, 3, etc.
        // In the meantime, we just need to make sure new items are added "at the bottom".
        $this->Sort = static::get()->max('Sort') + 1;
    }
}
