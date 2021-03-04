<?php


namespace App\Service;


use App\Entity\Item;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ItemValidationService
{
    public function validatePostItemRequest(array $request): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            'name' => [
                new Assert\NotBlank(['message' => 'name_required']),
                new Assert\Type(['type' => 'string']),
                new Assert\Length(['max' => Item::MAX_NAME_LENGTH])
            ],
            'description' => new Assert\Optional([
                new Assert\Type(['type' => 'string']),
            ]),
        ]);

        return $validator->validate($request, $constraint);
    }
}
