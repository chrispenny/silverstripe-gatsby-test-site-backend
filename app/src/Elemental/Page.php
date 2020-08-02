<?php

namespace App\Elemental;

use DNADesign\Elemental\Extensions\ElementalPageExtension;
use Page as BasePage;

/**
 * Class Page
 *
 * @package App\Elemental
 */
class Page extends BasePage
{
    /**
     * @var array
     */
    private static $extensions = [
        ElementalPageExtension::class,
    ];

    /**
     * @var string
     */
    private static $table_name = 'BlockPage';

    /**
     * @var string
     */
    private static $singular_name = 'Block page';

    /**
     * @var string
     */
    private static $plural_name = 'Block pages';

    /**
     * @var string
     */
    private static $description = 'Page which is built from blocks';
}
