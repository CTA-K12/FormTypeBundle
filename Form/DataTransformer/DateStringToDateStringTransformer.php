<?php
namespace Mesd\FormTypeBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateStringToDateStringTransformer implements DataTransformerInterface
{
    /**
     * Transforms a (string) date to a (string) date.
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($date)
    {
        if (null === $date)
        {
            return null;
        }
        elseif ($date instanceof \DateTime)
        {
            return $date->format('m/d/Y');
        }

        try
        {
            $dt = \DateTime::createFromFormat('Y-m-d', $date);
        }
        catch (Exception $e)
        {
            throw new TransformationFailedException(sprintf(
                '%s is not a valid date string.',
                $date
            ));
        }

        return $dt->format('m/d/Y');
    }

    /**
     * Transforms a (string) date to a (string) date.
     *
     * @param  string $issueNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($date)
    {
        if (null === $date)
        {
            return null;
        }

        return \DateTime::createFromFormat('m/d/Y', $date);
    }
}