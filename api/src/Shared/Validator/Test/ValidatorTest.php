<?php

declare(strict_types=1);

namespace App\Shared\Validator\Test;

use App\Shared\Validator\Error;
use App\Shared\Validator\ValidationException;
use App\Shared\Validator\Validator;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Validator::class)]
final class ValidatorTest extends TestCase
{
    public function testValid(): void
    {
        $command = new stdClass();

        $origin = $this->createMock(ValidatorInterface::class);
        $origin->expects(self::once())->method('validate')
            ->with(self::equalTo($command))
            ->willReturn(new ConstraintViolationList());

        $validator = new Validator($origin);

        $validator->validate($command);
    }

    public function testNotValid(): void
    {
        $command = new stdClass();

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
                    ''
                ),
            ]));

        $validator = new Validator($origin);

        try {
            $validator->validate($command);
            self::fail('Expected exception is not thrown.');
        } catch (Exception $exception) {
            self::assertInstanceOf(ValidationException::class, $exception);
            self::assertCount(1, $errors = $exception->getErrors()->getErrors());
            self::assertInstanceOf(Error::class, $error = end($errors));
            self::assertSame('firstName', $error->getPropertyPath());
            self::assertSame('This value should not be blank.', $error->getMessage());
        }
    }
}
