<?php

declare(strict_types=1);

namespace App\Contracts\Filesystem;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface FilesystemInterface extends FilesystemReaderInterface, FilesystemWriterInterface
{
}
