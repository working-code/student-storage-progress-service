<?php

namespace App\Symfony;

use App\Exception\ValidationException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MainParamConvertor implements ParamConverterInterface
{
    public const MAIN_CONVERTOR = 'main_convertor';
    public const GROUPS = 'groups';
    public const VALIDATE = 'validate';
    public const VALIDATION_ERRORS_ARGUMENT = 'validationErrorsArgument';

    private const DEFAULT_VALIDATE = true;

    public function __construct(
        private readonly ValidatorInterface  $validator,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $options = (array)$configuration->getOptions();

        $format = method_exists(Request::class, 'getContentTypeFormat')
            ? $request->getContentTypeFormat()
            : $request->getContentType();

        if ($format === null) {
            return $this->throwException(new UnsupportedMediaTypeHttpException(), $configuration);
        }

        $groups = $options[static::GROUPS] ?? [];
        $object = $this->serializer->deserialize(
            $request->getContent(),
            $configuration->getClass(),
            $format,
            [
                static::GROUPS => $groups,
            ]
        );


        $request->attributes->set($configuration->getName(), $object);

        $options[static::VALIDATE] = $options[static::VALIDATE] ?? static::DEFAULT_VALIDATE;

        if ($options[static::VALIDATE]) {
            $errors = $this->validator->validate($object, null, $groups);

            if ($errors->count() <= 0) {
                $errors = $this->validator->validate($object);
            }

            if (
                isset($options[static::VALIDATION_ERRORS_ARGUMENT])
                && $options[static::VALIDATION_ERRORS_ARGUMENT]
            ) {
                $request->attributes->set($options[static::VALIDATION_ERRORS_ARGUMENT], $errors);
            } elseif (count($errors)) {
                throw new ValidationException($errors);
            }
        }

        return true;
    }

    /**
     * @throws Exception
     */
    private function throwException(Exception $exception, ParamConverter $configuration): bool
    {
        if ($configuration->isOptional()) {
            return false;
        }

        throw $exception;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() !== null
            && $configuration->getConverter() === static::MAIN_CONVERTOR;
    }
}
