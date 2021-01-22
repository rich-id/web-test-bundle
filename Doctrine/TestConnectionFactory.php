<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Doctrine;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use RichCongress\WebTestBundle\Doctrine\Driver\StaticDriver;

/**
 * Class TestConnectionFactory
 *
 * @package   RichCongress\WebTestBundle\Doctrine
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TestConnectionFactory extends ConnectionFactory
{
    /**
     * @var ConnectionFactory
     */
    private $decoratedFactory;

    /**
     * TestConnectionFactory constructor.
     *
     * @param ConnectionFactory $decoratedFactory
     */
    public function __construct(ConnectionFactory $decoratedFactory)
    {
        parent::__construct([]);

        $this->decoratedFactory = $decoratedFactory;
    }

    /**
     * Create a connection by name.
     *
     * @param array              $params
     * @param Configuration|null $config
     * @param EventManager|null  $eventManager
     * @param array              $mappingTypes
     *
     * @return Connection
     * @throws DBALException
     */
    public function createConnection(array $params, Configuration $config = null, EventManager $eventManager = null, array $mappingTypes = []): Connection
    {
        $parameters = $this->processParameters($params);

        // Force static driver
        StaticDriver::setKeepStaticConnections(true);
        $parameters['dama.keep_static'] = true;

        return $this->decoratedFactory->createConnection($parameters, $config, $eventManager, $mappingTypes);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function processParameters(array $params): array
    {
        $testToken = getenv('TEST_TOKEN');
        $params['dbname'] = $params['dbname'] ?? 'dbTest';

        if (\is_string($testToken) && $testToken !== '') {
            $params['dbname'] .= '_' . $testToken;
        }

        $params['path'] = \str_replace('__DBNAME__', $params['dbname'], $params['path'] ?? '');

        return $params;
    }
}
