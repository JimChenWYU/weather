<?php

/*
 * This file is part of the jimchen/weather.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Weather\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use JimChen\Weather\Exceptions\HttpException;
use JimChen\Weather\Exceptions\InvalidArgumentException;
use JimChen\Weather\Weather;
use Mockery\Matcher\AnyArgs;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{
    public function testGetWeather()
    {
        //json
        // 创建模拟接口响应值。
        $response = new Response(200, [], '{"success": true}');

        // 创建模拟 http client。
        $client = \Mockery::mock(Client::class);

        // 指定将会产生的形为（在后续的测试中将会按下面的参数来调用）。
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '深圳',
                'output' => 'json',
                'extensions' => 'base',
            ],
        ])->andReturn($response);

        // 将 `getHttpClient` 方法替换为上面创建的 http client 为返回值的模拟方法。
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client); // $client 为上面创建的模拟实例。

        // 然后调用 `getWeather` 方法，并断言返回值为模拟的返回值。
        $this->assertSame(['success' => true], $w->getWeather('深圳'));

        // xml
        $response = new Response(200, [], '<hello>content</hello>');
        $client = \Mockery::mock(Client::class);
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '深圳',
                'output' => 'xml',
                'extensions' => 'all',
            ],
        ])->andReturn($response);

        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);

        $this->assertSame('<hello>content</hello>', $w->getWeather('深圳', 'all', 'xml'));
    }

    public function testGetWeatherWithGuzzleRuntimeException()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()
            ->get(new AnyArgs())
            ->andThrow(new \Exception('request timeout'));

        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');

        $w->getWeather('深圳');
    }

    public function testGetHttpClient()
    {
        $w = new Weather('mock-key');

        $this->assertInstanceOf(ClientInterface::class, $w->getHttpClient());
    }

    public function testSetGuzzleOptions()
    {
        $w = new Weather('mock-key');

        $this->assertNull($w->getHttpClient()->getConfig('timeout'));

        $w->setGuzzleOptions(['timeout' => 5000]);

        $this->assertSame(5000, $w->getHttpClient()->getConfig('timeout'));
    }

    public function testGetWeatherWithInvalidType()
    {
        $w = new Weather('mock-key');

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('Invalid type value(base/all): foo');

        $w->getWeather('深圳', 'foo');

        $this->fail('Faild to assert getWeather throw exception with invalid argument.');
    }

    public function testGetWeatherWithInvalidFormat()
    {
        $w = new Weather('mock-key');

        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);

        // 断言异常消息为 'Invalid response format: array'
        $this->expectExceptionMessage('Invalid response format: array');

        // 因为支持的格式为 xml/json，所以传入 array 会抛出异常
        $w->getWeather('深圳', 'base', 'array');

        // 如果没有抛出异常，就会运行到这行，标记当前测试没成功
        $this->fail('Faild to assert getWeather throw exception with invalid argument.');
    }

    public function testGetLiveWeather()
    {
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->expects()->getWeather('深圳', 'base', 'json')->andReturn(['success' => true]);

        $this->assertSame(['success' => true], $w->getLiveWeather('深圳'));
    }

    public function testGetForcastsWeather()
    {
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->expects()->getWeather('深圳', 'all', 'json')->andReturn(['success' => true]);

        $this->assertSame(['success' => true], $w->getForcastsWeather('深圳'));
    }
}
