<?php
namespace Mesd\FormTypeBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeStringToDateTimeStringTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($dateTime)
    {
        if (null === $dateTime)
        {
            return null;
        }
        elseif ($dateTime instanceof \DateTime)
        {
            return $dateTime->format('m/d/Y h:i A');
        }

        try
        {
            $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        }
        catch (Exception $e)
        {
            throw new TransformationFailedException(sprintf(
                '%s is not a valid datetime string.',
                $dateTime
            ));
        }

        return $dt->format('m/d/Y h:i A');
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $issueNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($dateTime)
    {
        if (null === $dateTime)
        {
            return null;
        }

        #$dt = \DateTime::createFromFormat('m/d/Y h:i A', $dateTime);

        #return $dt->format('Y-m-d H:i:s');
        return \DateTime::createFromFormat('m/d/Y h:i A', $dateTime);
    }
}