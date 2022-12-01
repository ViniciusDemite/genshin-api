<?php

namespace App\Requests;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class RequestValidator
{
  public function __construct(protected ValidatorInterface $validator)
  {
    $this->populate();
  }

  public function validate(): void
  {
    $errors = $this->validator->validate($this);

    $messages = ['message' => 'validation_failed', 'errors' => []];

    /** @var \Symfony\Component\Validator\ConstraintViolation  */
    foreach ($errors as $message) {
      $messages['errors'][] = [
        'property' => $message->getPropertyPath(),
        'value' => $message->getInvalidValue(),
        'message' => $message->getMessage(),
      ];
    }

    if (count($messages['errors']) > 0) {
      $response = new JsonResponse($messages);
      $response->send();

      exit;
    }
  }

  public function validated(): array
  {
    $this->validate();

    $fields = $this->objectToArray($this);
    unset($fields['validator']);

    return $fields;
  }

  private function objectToArray(object $object): array
  {
    $resultantArray = [];

    foreach ($object as $key => $value) {
      $resultantArray[$key] = (is_array($value) || is_object($value))
        ? $this->objectToArray($value)
        : $value;
    }

    return $resultantArray;
  }

  protected function getRequest(): Request
  {
    return Request::createFromGlobals();
  }

  protected function populate(): void
  {
    foreach ($this->getRequest()->toArray() as $property => $value) {
      if (property_exists($this, $property)) {
        $this->{$property} = $value;
      }
    }
  }
}
