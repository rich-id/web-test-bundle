<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Resources\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DummyEntity
 *
 * @package    RichCongress\WebTestBundle\Tests\Resources\Entity
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @ORM\Entity(repositoryClass="RichCongress\WebTestBundle\Tests\Resources\Repository\DummyEntityRepository")
 */
class DummyEntity
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
}
