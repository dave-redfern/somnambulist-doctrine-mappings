<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Somnambulist\Doctrine;

use Doctrine\DBAL\Types\Type;
use Somnambulist\Doctrine\Enumerations\Constructors\CountryEnumeration;
use Somnambulist\Doctrine\Enumerations\Constructors\CurrencyEnumeration;
use Somnambulist\Doctrine\Enumerations\Constructors\GenericEloquentEnumeration;
use Somnambulist\Doctrine\Enumerations\Serializers\CountrySerializer;
use Somnambulist\Doctrine\Enumerations\Serializers\CurrencySerializer;
use Somnambulist\Doctrine\Types;
use Somnambulist\DoctrineEnumBridge\EnumerationBridge;
use Somnambulist\ValueObjects\Types\Geography;
use Somnambulist\ValueObjects\Types\Measure;
use Somnambulist\ValueObjects\Types\Money;

/**
 * Class Bootstrapper
 *
 * @package    Somnambulist\Doctrine
 * @subpackage Somnambulist\Doctrine\Bootstrapper
 */
class Bootstrapper
{

    /**
     * Registers the default enumeration handlers
     */
    public static function registerEnumerations()
    {
        $constructor = new GenericEloquentEnumeration();

        EnumerationBridge::registerEnumTypes([
            Geography\CountryCode::class => $constructor,
            Geography\Srid::class        => $constructor,
            Measure\AreaUnit::class      => $constructor,
            Measure\DistanceUnit::class  => $constructor,
            Money\CurrencyCode::class    => $constructor,

            Geography\Country::class => [new CountryEnumeration(), new CountrySerializer()],
            Money\Currency::class    => [new CurrencyEnumeration(), new CurrencySerializer()],
        ]);
    }

    /**
     * Registers the custom Doctrine types
     */
    public static function registerTypes()
    {
        Type::hasType('json') ?: Type::addType('json', Types\JsonCollectionType::class);
        Type::hasType('jsonb') ?: Type::addType('jsonb', Types\JsonCollectionType::class);
        Type::hasType('json_collection') ?: Type::addType('json_collection', Types\JsonCollectionType::class);
        Type::overrideType('date', Types\DateType::class);
        Type::overrideType('datetime', Types\DateTimeType::class);
        Type::overrideType('datetimetz', Types\DateTimeTzType::class);
        Type::overrideType('time', Types\TimeType::class);
    }
}
