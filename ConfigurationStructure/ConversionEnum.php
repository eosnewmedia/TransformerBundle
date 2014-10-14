<?php


namespace Enm\TransformerBundle\ConfigurationStructure;

use Enm\TransformerBundle\Traits\EnumTrait;

class ConversionEnum
{

  use EnumTrait;

  const ARRAY_CONVERSION = 'array';

  const OBJECT_CONVERSION = 'object';

  const JSON_CONVERSION = 'json';

  const STRING_CONVERSION = 'string';
}
