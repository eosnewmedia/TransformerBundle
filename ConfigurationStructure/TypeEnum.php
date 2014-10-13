<?php


namespace Enm\TransformerBundle\ConfigurationStructure;

use Enm\TransformerBundle\Traits\EnumTrait;

class TypeEnum
{

  use EnumTrait;

  const INTEGER_TYPE = 'integer';

  const FLOAT_TYPE = 'float';

  const STRING_TYPE = 'string';

  const BOOL_TYPE = 'bool';

  const ARRAY_TYPE = 'array';

  const COLLECTION_TYPE = 'collection';

  const DATE_TYPE = 'date';

  const OBJECT_TYPE = 'object';

  const INDIVIDUAL_TYPE = 'individual';
}
