<?php

namespace Faibl\MailjetBundle\Model;

class MailjetTemplateMail extends MailjetMail
{
    private $templateId;
    private $variables = [];

    public function __construct(int $templateId)
    {
        $this->templateId = $templateId;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function setVariables(array $variables): self
    {
        $this->variables = $variables;

        return $this;
    }

    public function getTemplateId(): int
    {
        return $this->templateId;
    }
}
