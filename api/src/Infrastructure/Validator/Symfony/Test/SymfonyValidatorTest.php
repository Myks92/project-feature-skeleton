<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Symfony\Test;

use App\Infrastructure\Validator\Error;
use App\Infrastructure\Validator\Symfony\SymfonyValidator;
use App\Infrastructure\Validator\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(SymfonyValidator::class)]
final class SymfonyValidatorTest extends TestCase
{
    public function testValid(): void
    {
        $command = new \stdClass();

        $origin = $this->createMock(ValidatorInterface::class);
        $origin->expects(self::once())->method('validate')
            ->with(self::equalTo($command))
            ->willReturn(new ConstraintViolationList());

        $validator = new SymfonyValidator($origin);

        $validator->validate($command);
    }

    public function testNotValid(): void
    {
        $command = new \stdClass();

        $origin = $this->createMock(ValidatorInterface::class);
        $origin->expects(self::once())->method('validate')
            ->with(self::equalTo($command))
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation(
                    'This value should not be blank.',
                    'This value should not be blank.',
                    ['{{ value }}' => ''],
                    'firstName',
                    'firstName',
                    '',
                ),
            ]));

        $validator = new SymfonyValidator($origin);

        try {
            $validator->validate($command);
            self::fail('Expected exception is not thrown.');
        } catch (\Exception $exception) {
            self::assertInstanceOf(ValidationException::class, $exception);
            self::assertCount(1, $errors = $exception->getErrors()->getErrors());
            self::assertInstanceOf(Error::class, $error = end($errors));
            self::assertSame('firstName', $error->getPropertyPath());
            self::assertSame('This value should not be blank.', $error->getMessage());
        }
    }
}
