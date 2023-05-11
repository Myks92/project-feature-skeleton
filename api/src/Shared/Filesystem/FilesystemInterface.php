<?php

declare(strict_types=1);

namespace App\Shared\Filesystem;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface FilesystemInterface extends FilesystemReaderInterface, FilesystemWriterInterface
{
}