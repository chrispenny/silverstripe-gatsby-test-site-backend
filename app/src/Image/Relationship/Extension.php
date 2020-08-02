<?php

namespace App\Image\Relationship;

use App\Elemental\ImageWithText;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\HasManyList;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Class Extension
 *
 * @package App\Image\Relationship
 * @property Image|$this $owner
 * @method HasManyList|ImageWithText\ImageWithTextBlock[] ImageWithTextBlocks()
 */
class Extension extends DataExtension
{
    /**
     * @var array
     */
    private static $has_one = [
        'SiteConfigPromo' => SiteConfig::class . '.PromoImage',
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'ImageWithTextBlocks' => ImageWithText\ImageWithTextBlock::class . '.Image',
    ];

    /**
     * @var array
     */
    private static $owned_by = [
        'ImageWithTextBlocks',
    ];
}
