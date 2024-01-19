<?php

declare(strict_types=1);

namespace App\Shared\Mailer;

use App\Contracts\Mailer\FileInterface;

final readonly class File implements FileInterface
{
    /**
     * @var string the file ID
     */
    private string $id;

    /**
     * @param string|null $name the name that should be used to attach the file
     * @param string|null $path the full path to the file
     * @param string|null $content the content that should be used to attach the file
     * @param string|null $contentType MIME type that should be used to attach the file
     *
     * @throws \Exception {@see https://www.php.net/manual/en/function.random-bytes.php}
     */
    private function __construct(
        private ?string $name,
        private ?string $path,
        private ?string $content,
        private ?string $contentType,
        private bool $embed = false,
    ) {
        $this->id = bin2hex(random_bytes(16));
    }

    /**
     * Creates a new file instance from the specified content.
     *
     * @param string $content the content that should be used to attach the file
     * @param string|null $name the name that should be used to attach the file
     * @param string|null $contentType MIME type that should be used to attach the file
     *
     * @throws \Exception {@see https://www.php.net/manual/en/function.random-bytes.php}
     */
    public static function fromContent(string $content, ?string $name = null, ?string $contentType = null): self
    {
        return new self($name, null, $content, $contentType, false);
    }

    /**
     * Creates a new file instance from the specified full path to the file.
     *
     * @param string $path the full path to the file
     * @param string|null $name the name that should be used to attach the file
     * @param string|null $contentType MIME type that should be used to attach the file
     *
     * @throws \RuntimeException if the specified file does not exist
     * @throws \Exception {@see https://www.php.net/manual/en/function.random-bytes.php}
     */
    public static function fromPath(string $path, ?string $name = null, ?string $contentType = null): self
    {
        if (!is_file($path)) {
            throw new \RuntimeException("The file {$path} does not exist.");
        }

        return new self($name, $path, null, $contentType, true);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCid(): string
    {
        return "cid:{$this->getId()}";
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function isEmbed(): bool
    {
        return $this->embed;
    }
}
