<?php

namespace Beequeue\DependView\Test\Project\Vcs;

class VcsTestBase extends \PHPUnit_Framework_TestCase
{
    protected function getConfiguredHttpClientMock(array $exchanges)
    {
        $responseBodies = [];
        $requestParams = [];

        foreach ($exchanges as $exchange) {
            $responseBodies[] = $exchange['responseBody'];

            $request = [
                $this->equalTo($exchange['method']),
                $this->equalTo($exchange['url'])
            ];
            $requestParams[] = $request;
        }

        $response = $this->createMock('Psr\Http\Message\ResponseInterface');

        $response->method('getBody')
                 ->will(call_user_func_array(
                     [$this, 'onConsecutiveCalls'],
                     $responseBodies
                 ));

        $httpClient = $this->getMockBuilder('GuzzleHttp\Client')
                           ->disableOriginalConstructor()
                           ->getMock();

        $requestMethod = $httpClient->expects($this->exactly(count($exchanges)))
                                    ->method('request')
                                    ->will($this->returnValue($response));

        call_user_func_array([$requestMethod, 'withConsecutive'], $requestParams);

        return $httpClient;
    }
}