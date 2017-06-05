<?php
namespace Nia\Form;

/**
 * Interface for translated form validation implementations.
 */
interface TranslatedFormInterface extends FormInterface
{

    /**
     * Returns a map of violation translations associated to the field name and violation id.
     *
     * @return mixed[] Map of violation translations associated to the field name and violation id.
     */
    public function getViolationTranslations(): array;
}
