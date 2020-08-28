<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle;

use RichCongress\BundleToolbox\Configuration\AbstractBundle;
use RichCongress\WebTestBundle\DependencyInjection\CompilerPass\OverrideServicesPass;

/**
 * Class RichCongressWebTestBundle
 *
 * @package   RichCongress\WebTestBundle
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class RichCongressWebTestBundle extends AbstractBundle
{
    public const COMPILER_PASSES = [OverrideServicesPass::class];
}
