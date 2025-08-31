<?php

declare(strict_types=1);

use Src\Infrastructure\External\Http\Exception\HeaderNormalizationException;
use Src\Infrastructure\External\Http\Exception\UrlNormalizationException;

test('should normalize a valid url', function () {
    $sut = getRestClient('http://api.test');
    expect($sut->baseUrl)->toBe('http://api.test/');

    $sut = getRestClient('http://api.test/');
    expect($sut->baseUrl)->toBe('http://api.test/');
});

test('should throw exception on handling an invalid url', function () {
    expect(fn() => getRestClient('invalid url string'))
        ->toThrow(UrlNormalizationException::class);
});

test('should normalize headers', function () {
    $headers = [
        'Content-Type' => 'application/json',
        'CUSTOM-HEADER' => 'value'
    ];

    $sut = getRestClient('http://api.test', headers: $headers);

    expect($sut->headers)
        ->toHaveKey('content-type', 'application/json')
        ->toHaveKey('custom-header', 'value');
});

test('should throw exception on normalizing invalid headers', function () {
    expect(fn() => getRestClient('http://api.test', ['invalid' => new StdClass()]))
        ->toThrow(HeaderNormalizationException::class);
});

test('should execute GET requests', function () {
    // Arrange
    $pendingRequest = getClientBaseMock();
    $uri = 'endpoint';
    $query = ['param' => 'value'];
    $headers = ['header' => 'value'];
    $response = getResponseMock();
    $pendingRequest->shouldReceive('withHeaders')->with($headers);
    $pendingRequest
        ->shouldReceive('get')
        ->once()
        ->with('endpoint', $query, $headers)
        ->andReturn($response); // Assert

    $sut = getRestClient('http://api.test', client: $pendingRequest);

    // Act
    $sut->get($uri, $query, $headers);
});

test('should execute POST requests', function () {
    // Arrange
    $pendingRequest = getClientBaseMock();
    $uri = 'endpoint';
    $query = ['param' => 'value'];
    $body = ['property' => 'value'];
    $headers = ['header' => 'value'];
    $response = getResponseMock();
    $pendingRequest->shouldReceive('withHeaders')->with($headers);
    $pendingRequest
        ->shouldReceive('post')
        ->once()
        ->with('endpoint', $query, $body)
        ->andReturn($response); // Assert

    $sut = getRestClient('http://api.test', client: $pendingRequest);

    // Act
    $sut->post($uri, $query, $body, $headers);
});

test('should execute PUT requests', function () {
    // Arrange
    $pendingRequest = getClientBaseMock();
    $uri = 'endpoint';
    $query = ['param' => 'value'];
    $body = ['property' => 'value'];
    $headers = ['header' => 'value'];
    $response = getResponseMock();
    $pendingRequest->shouldReceive('withHeaders')->with($headers);
    $pendingRequest
        ->shouldReceive('put')
        ->once()
        ->with('endpoint', $query, $body)
        ->andReturn($response); // Assert

    $sut = getRestClient('http://api.test', client: $pendingRequest);

    // Act
    $sut->put($uri, $query, $body, $headers);
});

test('should execute PATCH requests', function () {
    // Arrange
    $pendingRequest = getClientBaseMock();
    $uri = 'endpoint';
    $query = ['param' => 'value'];
    $body = ['property' => 'value'];
    $headers = ['header' => 'value'];
    $response = getResponseMock();
    $pendingRequest->shouldReceive('withHeaders')->with($headers);
    $pendingRequest
        ->shouldReceive('patch')
        ->once()
        ->with('endpoint', $query, $body)
        ->andReturn($response); // Assert

    $sut = getRestClient('http://api.test', client: $pendingRequest);

    // Act
    $sut->patch($uri, $query, $body, $headers);
});

test('should execute DELETE requests', function () {
    // Arrange
    $pendingRequest = getClientBaseMock();
    $uri = 'endpoint';
    $query = ['param' => 'value'];
    $headers = ['header' => 'value'];
    $response = getResponseMock();
    $pendingRequest->shouldReceive('withHeaders')->with($headers);
    $pendingRequest
        ->shouldReceive('delete')
        ->once()
        ->with('endpoint', $query, $headers)
        ->andReturn($response); // Assert

    $sut = getRestClient('http://api.test', client: $pendingRequest);

    // Act
    $sut->delete($uri, $query, $headers);
});

test('should execute a pool of requests', function () {
    // Arrange
    $pendingRequest = getClientBaseMock();
    $pendingRequest->shouldReceive('pool')->once()->andReturn([]); // Assert
    $sut = getRestClient('http://api.test', client: $pendingRequest);

    // Act
    $sut->pool(function ($pool) {
        return [
            $pool->get('1'),
            $pool->get('2'),
        ];
    });
});
