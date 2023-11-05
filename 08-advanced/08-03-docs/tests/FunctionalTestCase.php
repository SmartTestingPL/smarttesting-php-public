<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\RequestValidator;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FunctionalTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected PsrHttpFactory $psrHttpFactory;
    protected static RequestValidator $requestValidator;
    protected static ResponseValidator $responseValidator;

    public static function setUpBeforeClass(): void
    {
        $validatorBuilder = (new ValidatorBuilder())->fromJsonFile('docs/openapi.json');
        self::$requestValidator = $validatorBuilder->getRequestValidator();
        self::$responseValidator = $validatorBuilder->getResponseValidator();
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $psr17Factory = new Psr17Factory();
        $this->psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
    }

    protected function validateRequest(Request $request): void
    {
        self::$requestValidator->validate($this->psrHttpFactory->createRequest($request));
    }

    protected function validateResponse(Request $request, Response $response): void
    {
        self::$responseValidator->validate(
            new OperationAddress($request->getUri(), strtolower($request->getMethod())),
            $this->psrHttpFactory->createResponse($response)
        );
    }
}
