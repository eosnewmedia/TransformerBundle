<?php


namespace Enm\TransformerBundle\ConfigurationStructure;

use Enm\TransformerBundle\Enumeration\BaseEnumeration;

class StringValidationEnum extends BaseEnumeration
{

  const EMAIL = 'email';

  const URL = 'url';

  const IP = 'ip';
}