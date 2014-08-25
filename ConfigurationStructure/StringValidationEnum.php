<?php


namespace ENM\TransformerBundle\ConfigurationStructure;

use ENM\TransformerBundle\Enumeration\BaseEnumeration;

class StringValidationEnum extends BaseEnumeration
{

  const EMAIL = 'email';

  const URL = 'url';

  const IP = 'ip';
}