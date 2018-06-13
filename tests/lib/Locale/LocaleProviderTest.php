<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Locale;

use Netgen\BlockManager\Locale\LocaleProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class LocaleProviderTest extends TestCase
{
    /**
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::__construct
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::getAvailableLocales
     */
    public function testGetAvailableLocales()
    {
        $localeProvider = new LocaleProvider(['en', 'hr']);

        $this->assertEquals(
            [
                'en' => 'English',
                'hr' => 'Croatian',
            ],
            $localeProvider->getAvailableLocales()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::__construct
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::getAvailableLocales
     */
    public function testGetAvailableLocalesWithNonExistingLocales()
    {
        $localeProvider = new LocaleProvider(['en', 'hr_NON_EXISTING']);

        $this->assertEquals(
            [
                'en' => 'English',
            ],
            $localeProvider->getAvailableLocales()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::__construct
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::getAvailableLocales
     */
    public function testGetAvailableLocalesWithDefaultLocales()
    {
        $localeProvider = new LocaleProvider();

        $this->assertNotEmpty($localeProvider->getAvailableLocales());
    }

    /**
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::getRequestLocales
     */
    public function testGetRequestLocales()
    {
        $localeProvider = new LocaleProvider();

        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->any())
            ->method('getLocale')
            ->will($this->returnValue('en'));

        $this->assertEquals(['en'], $localeProvider->getRequestLocales($requestMock));
    }

    /**
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::getRequestLocales
     */
    public function testGetRequestLocalesWithEnabledLocales()
    {
        $localeProvider = new LocaleProvider(['en', 'hr']);

        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->any())
            ->method('getLocale')
            ->will($this->returnValue('en'));

        $this->assertEquals(['en'], $localeProvider->getRequestLocales($requestMock));
    }

    /**
     * @covers \Netgen\BlockManager\Locale\LocaleProvider::getRequestLocales
     */
    public function testGetRequestLocalesWithNonEnabledLocale()
    {
        $localeProvider = new LocaleProvider(['en', 'hr']);

        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->any())
            ->method('getLocale')
            ->will($this->returnValue('de'));

        $this->assertEquals([], $localeProvider->getRequestLocales($requestMock));
    }
}
