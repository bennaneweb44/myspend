<?php

namespace App\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DateTimeNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $object->format(\DateTime::ISO8601);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \DateTime;
    }
}