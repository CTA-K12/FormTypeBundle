<?php
namespace Mesd\FormTypeBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TimeStringToTimeStringTransformer implements DataTransformerInterface
{
    /**
     * Transforms a (string) time to a (string) time.
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($time)
    {
        if (null === $time)
        {
            return null;
        }
        elseif ($time instanceof \DateTime)
        {
            return $time->format('h:i A');
        }

        try
        {
            $dt = \DateTime::createFromFormat('H:i:s', $time);
        }
        catch (Exception $e)
        {
            throw new TransformationFailedException(sprintf(
                '%s is not a valid time string.',
                $time
            ));
        }

        return $dt->format('h:i A');
    }

    /**
     * Transforms a (string) time to a (string) time.
     *
     * @param  string $issueNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($time)
    {
        if (null === $time)
        {
            return null;
        }

        return \DateTime::createFromFormat('h:i A', $time);
    }
}