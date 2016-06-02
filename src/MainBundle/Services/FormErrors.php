<?php

namespace MainBundle\Services;

use Symfony\Component\Form\Form;

class FormErrors
{
    /**
     * Provides form errors as an array
     *
     * @param Form $form
     * @return array
     */
    public function getFormErrors(Form $form)
    {
        $errors = array();

        // Global
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }

        // Fields
        foreach ($form as $child) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}