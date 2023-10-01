<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Uid\Uuid;

class CustomerParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $request->attributes->set(
                $configuration->getName(),
                new Customer(
                    Uuid::fromString($data['uuid']),
                    new Person(
                        $data['person']['name'],
                        $data['person']['surname'],
                        \DateTimeImmutable::createFromFormat('d-m-Y', $data['person']['dateOfBirth']),
                        $data['person']['gender'],
                        $data['person']['nationalIdentificationNumber']
                    )
                )
            );

            return true;
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException('Invalid customer object');
        }
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Customer::class;
    }
}
