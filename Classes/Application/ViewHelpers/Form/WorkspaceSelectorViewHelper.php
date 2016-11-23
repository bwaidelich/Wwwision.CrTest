<?php
namespace Wwwision\CrTest\Application\ViewHelpers\Form;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper;
use Wwwision\CrTest\Projection\Workspace\Workspace;
use Wwwision\CrTest\Projection\Workspace\WorkspaceFinder;

class WorkspaceSelectorViewHelper extends SelectViewHelper
{
    /**
     * @Flow\Inject
     * @var WorkspaceFinder
     */
    protected $workspaceFinder;

    /**
     * Initialize arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('name', 'string', 'Name of input tag');
        $this->registerArgument('value', 'mixed', 'Value of input tag');
        $this->registerArgument('property', 'string', 'Name of Object Property. If used in conjunction with <f:form object="...">, "name" and "value" properties will be ignored.');
        $this->registerTagAttribute('multiple', 'string', 'if set, multiple select field');
        $this->registerTagAttribute('size', 'string', 'Size of input field');
        $this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');
        $this->registerArgument('selectAllByDefault', 'boolean', 'If specified options are selected if none was set before.', false, false);
        $this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this ViewHelper', false, 'f3-form-error');
        $this->registerArgument('prependOptionLabel', 'string', 'If specified, will provide an option at first position with the specified label.');
        $this->registerArgument('prependOptionValue', 'string', 'If specified, will provide an option at first position with the specified value. This argument is only respected if prependOptionLabel is set.');
        $this->registerArgument('excludeId', 'string', 'If specified, the workspace with the given id will be skipped.');
    }

    protected function getOptions()
    {
        $excludeId = $this->hasArgument('excludeId') ? $this->arguments['excludeId'] : null;
        return array_reduce($this->workspaceFinder->findAll()->toArray(), function(array $result, Workspace $workspace) use ($excludeId) {
            if ($workspace->getId() !== $excludeId) {
                $result[$workspace->getId()] = $workspace->getId();
            }
            return $result;
        }, []);
    }
}