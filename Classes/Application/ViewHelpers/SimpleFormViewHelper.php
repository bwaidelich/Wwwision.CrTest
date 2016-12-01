<?php
namespace Wwwision\CrTest\Application\ViewHelpers;

use Neos\FluidAdaptor\ViewHelpers\FormViewHelper;

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