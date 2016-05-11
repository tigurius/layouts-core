<?php

namespace Netgen\BlockManager\Serializer\Normalizer;

use Netgen\BlockManager\Serializer\Values\ValueInterface;
use Netgen\BlockManager\Serializer\Values\ValueArray;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ValueArrayNormalizer extends SerializerAwareNormalizer implements NormalizerInterface
{
    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param \Netgen\BlockManager\Serializer\Values\ValueArray $object
     * @param string $format
     * @param array $context
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $data = array();

        foreach ($object->getValue() as $key => $value) {
            if ($value instanceof ValueInterface) {
                $dataItem = $this->serializer->normalize($value);
            } elseif (is_array($value)) {
                $dataItem = $this->serializer->normalize(new ValueArray($value));
            } else {
                $dataItem = $value;
            }

            $data[$key] = $dataItem;
        }

        return $data;
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed $data
     * @param string $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ValueArray && is_array($data->getValue());
    }
}
