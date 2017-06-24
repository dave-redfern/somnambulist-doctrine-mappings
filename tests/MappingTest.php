<?php

namespace Somnambulist\Tests\Doctrine;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Somnambulist\Collection\Collection;
use Somnambulist\Doctrine\Bootstrapper;
use Somnambulist\Doctrine\Types\DateTimeType;
use Somnambulist\Doctrine\Types\DateTimeTzType;
use Somnambulist\Doctrine\Types\DateType;
use Somnambulist\Doctrine\Types\JsonCollectionType;
use Somnambulist\Doctrine\Types\TimeType;
use Somnambulist\Tests\Entities\Order;
use Somnambulist\Tests\Entities\ValueObjects\Purchaser;
use Somnambulist\ValueObjects\Types\DateTime\DateTime;
use Somnambulist\ValueObjects\Types\DateTime\TimeZone;
use Somnambulist\ValueObjects\Types\Geography\Country;
use Somnambulist\ValueObjects\Types\Identity\EmailAddress;
use Somnambulist\ValueObjects\Types\Identity\Uuid;
use Somnambulist\ValueObjects\Types\Money\Currency;
use Somnambulist\ValueObjects\Types\Money\CurrencyCode;
use Somnambulist\ValueObjects\Types\Money\Money;

/**
 * Class DomainEventPublisherTest
 *
 * @package    Somnambulist\Tests\DomainEvents\Publishers\Doctrine
 * @subpackage Somnambulist\Tests\DomainEvents\Publishers\Doctrine\DomainEventPublisherTest
 */
class MappingTest extends TestCase
{

    /**
     * @var EntityManager
     */
    protected $em;

    protected function setUp()
    {
        $conn = [
            'driver'   => $GLOBALS['DOCTRINE_DRIVER'],
            'memory'   => $GLOBALS['DOCTRINE_MEMORY'],
            'dbname'   => $GLOBALS['DOCTRINE_DATABASE'],
            'user'     => $GLOBALS['DOCTRINE_USER'],
            'password' => $GLOBALS['DOCTRINE_PASSWORD'],
            'host'     => $GLOBALS['DOCTRINE_HOST'],
        ];

        $driver = new YamlDriver([
            __DIR__ . '/_data/mappings',
            __DIR__ . '/../config/doctrine',
        ]);
        $config = new Configuration();
        $config->setMetadataCacheImpl(new ArrayCache());
        $config->setQueryCacheImpl(new ArrayCache());
        $config->setProxyDir(sys_get_temp_dir());
        $config->setProxyNamespace('Somnambulist\Tests\Doctrine\Proxies');
        $config->setMetadataDriverImpl($driver);

        Bootstrapper::registerEnumerations();
        Bootstrapper::registerTypes();

        $em = EntityManager::create($conn, $config);

        $schemaTool = new SchemaTool($em);

        try {
            $schemaTool->createSchema([
                $em->getClassMetadata(Order::class),
            ]);
        } catch (\Exception $e) {
            if (
                $GLOBALS['DOCTRINE_DRIVER'] != 'pdo_mysql' ||
                !($e instanceof \PDOException && strpos($e->getMessage(), 'Base table or view already exists') !== false)
            ) {
                throw $e;
            }
        }

        $this->em = $em;
    }

    protected function tearDown()
    {
        $this->em = null;
    }



    /**
     * @group doctrine
     */
    public function testCanPersistAndRestoreValueObjectsAndEnumerations()
    {
        $entity = new Order(
            $uuid = new Uuid(\Ramsey\Uuid\Uuid::uuid4()),
            new Purchaser('Foo Bar', new EmailAddress('foo.bar@example.com'), Country::create('CAN')),
            new Money(34.56, Currency::create('CAD')),
            DateTime::parse('now', new TimeZone('America/Toronto'))
        );
        $entity->properties()->set('items', [
            ['name' => 'test one',],
            ['name' => 'test two',],
            ['name' => 'test three',],
        ])
        ;

        $this->em->persist($entity);
        $this->em->flush();

        $entity = null;
        unset($entity);

        /** @var Order $loaded */
        $loaded = $this->em->getRepository(Order::class)->findAll()[0];

        $this->assertInstanceOf(Order::class, $loaded);
        $this->assertInstanceOf(Collection::class, $loaded->properties());
        $this->assertInstanceOf(Purchaser::class, $loaded->purchaser());
        $this->assertInstanceOf(Money::class, $loaded->total());
        $this->assertInstanceOf(DateTime::class, $loaded->createdAt());

        $this->assertCount(1, $loaded->properties());
        $this->assertArrayHasKey('items', $loaded->properties());

        $this->assertTrue($uuid->equals($loaded->orderRef()));
        $this->assertEquals('Foo Bar', $loaded->purchaser()->name());
        $this->assertEquals('foo.bar@example.com', (string)$loaded->purchaser()->email());
        $this->assertTrue(Country::create('CAN')->equals($loaded->purchaser()->country()));

        $this->assertEquals(34.56, $loaded->total()->amount());
        $this->assertTrue(CurrencyCode::CAD()->equals($loaded->total()->currency()->code()));
    }
}
