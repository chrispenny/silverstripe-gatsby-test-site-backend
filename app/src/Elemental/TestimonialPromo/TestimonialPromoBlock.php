<?php

namespace App\Elemental\TestimonialPromo;

use App\Promo;
use App\Testimonial;
use DNADesign\Elemental\Models\BaseElement;
use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;

/**
 * Class TestimonialPromoBlock
 *
 * @package App\Elemental\TestimonialPromo
 * @property string $PromoContent
 * @property int $PromoImageID
 * @property int $PromoLinkID
 * @property string $PromoTitle
 * @method Image PromoImage()
 * @method Link PromoLink()
 */
class TestimonialPromoBlock extends BaseElement
{
    /**
     * @var string
     */
    private static $icon = 'font-icon-p-post';

    /**
     * @var string
     */
    private static $table_name = 'TestimonialPromoBlock';

    /**
     * @var string
     */
    private static $singular_name = 'Testimonial & promo block';

    /**
     * @var string
     */
    private static $plural_name = 'Testimonial & promo blocks';

    /**
     * @var string
     */
    private static $description = 'Randomised testimonials & a promo';

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
     * @var array
     */
    private static $cascade_deletes = [
        'PromoLink',
    ];

    /**
     * @var array
     */
    private static $cascade_duplicates = [
        'PromoLink',
    ];

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getType(): string
    {
        return 'Testimonial & Promo';
    }

    /**
     * Cached Testimonial - so that we're not fetching a different one in the same request
     *
     * @var Testimonial\Testimonial|null
     */
    private $testimonial;

    /**
     * @codeCoverageIgnore
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        // We don't need/want the Title/ShowTitle fields for this Block
        $fields->removeByName([
            'Title',
            'ShowTitle',
        ]);

        $notice = 'You can override the default promo by filling in these fields.';

        $fields->addFieldsToTab(
            'Root.Main',
            [
                LiteralField::create(
                    'ItemsMessage',
                    sprintf('<p class="message notice">%s</p>', $notice)
                ),
                HeaderField::create('CustomPromo', 'Custom Promo', 3),
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

        return $fields;
    }

    public function populateDefaults(): void
    {
        parent::populateDefaults();

        $this->Title = $this->getType();
    }

    /**
     * @return DataObject|Testimonial\Testimonial|null
     */
    public function getTestimonial(): ?Testimonial\Testimonial
    {
        if ($this->testimonial === null) {
            $this->testimonial = Testimonial\Testimonial::get()->sort('RAND()')->first();
        }

        return $this->testimonial;
    }

    /**
     * @return ArrayData|null
     */
    public function getPromo(): ?ArrayData
    {
        // This Block does not have a custom Promo, so, attempt to return the Promo from SiteConfig
        if (!$this->hasPromo()) {
            /** @var SiteConfig|Promo\Extension $siteConfig */
            $siteConfig = SiteConfig::current_site_config();

            // This will either return the Promo, or null (if one hasn't been added)
            return $siteConfig->getPromo();
        }

        return ArrayData::create([
            'Content' => $this->PromoContent,
            'Image' => $this->PromoImage(),
            'Link' => $this->PromoLink(),
            'Title' => $this->PromoTitle,
        ]);
    }

    /**
     * @return bool
     */
    public function hasPromo(): bool
    {
        if (!$this->PromoTitle) {
            return false;
        }

        if (!$this->PromoContent) {
            return false;
        }

        return true;
    }

    /**
     * Determine the source for the Promo, and then generate a cache key
     *
     * @return string
     */
    public function getPromoCacheKey(): string
    {
        $source = $this->ClassName;
        $lastEdited = $this->LastEdited;

        if (!$this->hasPromo()) {
            /** @var SiteConfig|Promo\Extension $siteConfig */
            $siteConfig = SiteConfig::current_site_config();

            $source = $siteConfig->ClassName;
            $lastEdited = $siteConfig->LastEdited;
        }

        return implode('-', [
            $source,
            $this->ID,
            $lastEdited,
        ]);
    }
}
