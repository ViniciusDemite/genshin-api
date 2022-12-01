<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CharacterRequest extends RequestValidator
{
  #[Type('string')]
  #[NotBlank()]
  protected $name;

  #[Type('string')]
  #[NotBlank()]
  protected $slug;

  #[Type('string')]
  protected $description;

  #[Type('string')]
  #[NotBlank()]
  protected $gender;

  #[Type('string')]
  #[NotBlank()]
  protected $birthday;
}
