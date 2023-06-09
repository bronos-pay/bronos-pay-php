<?php

namespace BronosPay\HttpClient;

use BronosPay\TestCase;

/**
 * @internal
 * @covers \BronosPay\HttpClient\CurlClient
 */
class CurlClientTest extends TestCase
{
    public function testTimeout()
    {
        $curl = new CurlClient();
        $this->assertSame(CurlClient::DEFAULT_TIMEOUT, $curl->getTimeout());
        $this->assertSame(CurlClient::DEFAULT_CONNECT_TIMEOUT, $curl->getConnectTimeout());

        $curl = $curl->setConnectTimeout(1)->setTimeout(10);
        $this->assertSame(1, $curl->getConnectTimeout());
        $this->assertSame(10, $curl->getTimeout());

        $curl->setTimeout(-1);
        $curl->setConnectTimeout(-999);
        $this->assertSame(0, $curl->getTimeout());
        $this->assertSame(0, $curl->getConnectTimeout());
    }

    public function testDefaultOptions()
    {
        // make sure options array loads/saves properly
        $optionsArray = [CURLOPT_PROXY => 'localhost:80'];
        $withOptionsArray = new CurlClient($optionsArray);
        $this->assertSame($withOptionsArray->getDefaultOptions(), $optionsArray);

        // make sure closure-based options work properly, including argument passing
        $ref = null;
        $withClosure = new CurlClient(function ($method, $absUrl, $headers, $params) use (&$ref) {
            $ref = func_get_args();

            return [];
        });

        $withClosure->request('get', 'https://httpbin.org/status/200', [], []);
        $this->assertSame($ref, ['get', 'https://httpbin.org/status/200', [], []]);

        // this is the last test case that will run, since it'll throw an exception at the end
        $withBadClosure = new CurlClient(function () {
            return 'thisShouldNotWork';
        });

        $this->expectException('BronosPay\Exception\UnexpectedValueException');
        $this->expectExceptionMessage('Non-array value returned by defaultOptions CurlClient callback');
        $withBadClosure->request('get', 'https://httpbin.org/status/200', [], []);
    }

    public function testSslOption()
    {
        // make sure options array loads/saves properly
        $optionsArray = [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1];
        $withOptionsArray = new CurlClient($optionsArray);
        $this->assertSame($withOptionsArray->getDefaultOptions(), $optionsArray);
    }
}
