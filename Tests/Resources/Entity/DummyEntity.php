<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Resources\Entity;

use Doctrine\ORM\Mapping as ORM;
use RichCongress\WebTestBundle\Tests\Resources\Repository\DummyEntityRepository;

/**
 * Class DummyEntity
 *
 * @package    RichCongress\WebTestBundle\Tests\Resources\Entity
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
#[ORM\Entity(repositoryClass: DummyEntityRepository::class)]
class DummyEntity
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;
}
