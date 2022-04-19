<?php

namespace Faibl\MailjetBundle\Model;

class MailjetAttachment
{
    private ?string $contentType = null;
    private ?string $filename = null;
    private ?string $content = null;

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): MailjetAttachment
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): MailjetAttachment
    {
        $this->filename = $filename;

        return $this;
    }

    public function getContent(): string
    {
        return base64_encode($this->content);
    }

    public function setContent(string $content): MailjetAttachment
    {
        $this->content = $content;

        return $this;
    }
}
