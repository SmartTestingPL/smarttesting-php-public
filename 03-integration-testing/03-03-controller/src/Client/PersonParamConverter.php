<?php

declare(strict_types=1);

namespace SmartTesting\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Uid\Uuid;

class PersonParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $request->attributes->set(
                $configuration->getName(),
                new Person(
                    Uuid::fromString($data['uuid']),
                    $data['name'],
                    $data['surname'],
                    \DateTimeImmutable::createFromFormat('Y-m-d', $data['dateOfBirth']),
                    $data['gender'],
                    $data['nationalIdentificationNumber']
                )
            );

            return true;
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException('Invalid person object');
        }
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Person::class;
    }
}
