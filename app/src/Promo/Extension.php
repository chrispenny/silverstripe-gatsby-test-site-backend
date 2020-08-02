<?php

namespace App\Promo;

use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;

/**
 * Class Extension
 *
 * @package App\Promo
 * @property SiteConfig|$this $owner
 * @property string $PromoContent
 * @property int $PromoImageID
 * @property int $PromoLinkID
 * @property string $PromoTitle
 * @method Image PromoImage()
 * @method Link PromoLink()
 */
class Extension extends DataExtension
{
    /**
     * @var array
     */
    private static $db = [
        'PromoContent' => 'Varchar(255)',
        'PromoTitle' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'PromoImage' => Image::class,
        'PromoLink' => Link::class,
    ];

    /**
     * @var array
     */
    private static $owns = [
        'PromoImage',
        'PromoLink',
    ];

    /**
     * @codeCoverageIgnore
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldsToTab(
            'Root.Promo',
            [
                $titleField = TextField::create('PromoTitle', 'Title'),
                $contentField = TextField::create('PromoContent', 'Content'),
                $imageField = UploadField::create('PromoImage', 'Image'),
                $linkField = LinkField::create('PromoLinkID', 'Call to action'),
            ]
        );

        $titleField->setDescription('Required');
        $contentField->setDescription('Required');
        $imageField
            ->setDescription('Optional')
            ->setAllowedFileCategories('image');
        $linkField->setDescription('Optional');
    }

    /**
     * @return ArrayData|null
     */
    public function getPromo(): ?ArrayData
    {
        if (!$this->hasPromo()) {
            return null;
        }

        return ArrayData::create([
            'Content' => $this->owner->PromoContent,
            'Image' => $this->owner->PromoImage(),
            'Link' => $this->owner->PromoLink(),
            'Title' => $this->owner->PromoTitle,
        ]);
    }

    /**
     * @return bool
     */
    public function hasPromo(): bool
    {
        if (!$this->owner->PromoTitle) {
            return false;
        }

        if (!$this->owner->PromoContent) {
            return false;
        }

        return true;
    }
}
