<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Resources\FormType;

use Symfony\Component\Form\AbstractType;

/**
 * Class DummyFormType
 *
 * @package    RichCongress\WebTestBundle\Tests\Resources\FormType
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class DummyFormType extends AbstractType
{
    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'dummy_form_type';
    }
}
