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

namespace Somnambulist\Doctrine\Enumerations\Constructors;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;
use Somnambulist\ValueObjects\Types\Money\Currency;
use Somnambulist\ValueObjects\Types\Money\CurrencyCode;

/**
 * Class CurrencyEnumeration
 *
 * Builds a Currency value object from the currency code enumeration.
 *
 * @package    Somnambulist\Doctrine\Enumerations\Constructors
 * @subpackage Somnambulist\Doctrine\Enumerations\Constructors\CurrencyEnumeration
 */
class CurrencyEnumeration
{

    /**
     * @param string           $value
     * @param string           $class
     * @param AbstractPlatform $platform
     *
     * @return Currency
     * @throws InvalidArgumentException
     */
    public function __invoke($value, $class, $platform)
    {
        if (is_null($value)) {
            return null;
        }

        if (CurrencyCode::hasValue($value)) {
            return Currency::create($value);
        }

        throw new InvalidArgumentException(sprintf('"%s" is not a valid value for "%s"', $value, Currency::class));
    }
}
