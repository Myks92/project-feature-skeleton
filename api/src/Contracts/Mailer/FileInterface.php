<?php

declare(strict_types=1);

namespace App\Contracts\Mailer;

/**
 * `File` is a data object that stores data for attaching a file to a mail message.
 */
interface FileInterface
{
    /**
     * Returns the file ID.
     *
     * @return string the file ID
     */
    public function getId(): string;

    /**
     * Returns the file CID source.
     *
     * @return string the file CID source
     */
    public function getCid(): string;

    /**
     * Returns the name that should be used to attach the file.
     *
     * @return string|null the name that should be used to attach the file
     */
    public function getName(): ?string;

    /**
     * Returns the full path to the file.
     *
     * @return string|null the full path to the file
     */
    public function getPath(): ?string;

    /**
     * Returns the content that should be used to attach the file.
     *
     * @return string|null the content that should be used to attach the file
     */
    public function getContent(): ?string;

    /**
     * Returns the MIME type that should be used to attach the file.
     *
     * @return string|null MIME type that should be used to attach the file
     */
    public function getContentType(): ?string;

    /**
     * Returns the file is embeddable.
     */
    public function isEmbed(): bool;
}
