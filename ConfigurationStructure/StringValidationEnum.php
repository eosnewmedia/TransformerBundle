<?php


namespace Enm\TransformerBundle\ConfigurationStructure;

use Enm\TransformerBundle\Traits\EnumTrait;

class StringValidationEnum
{

  use EnumTrait;

  const EMAIL = 'email';

  const URL = 'url';

  const IP = 'ip';
}
