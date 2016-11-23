<?php
namespace Wwwision\CrTest\Application\ViewHelpers;

use TYPO3\Fluid\ViewHelpers\FormViewHelper;

class SimpleFormViewHelper extends FormViewHelper
{
    protected function renderHiddenReferrerFields()
    {
        return '';
    }

    protected function renderTrustedPropertiesField()
    {
        return '';
    }

}