<?php

namespace Daemon\FilestorageBundle\Component\Data\Enum;

use Daemon\SimplifyBundle\Component\Enum\Enum;

abstract class FileType extends Enum {

    const APPLICATION = 'application';
    const AUDIO       = 'audio';
    const EXAMPLE     = 'example';
    const IMAGE       = 'image';
    const MESSAGE     = 'message';
    const MULTIPART   = 'multipart';
    const TEXT        = 'text';
    const VIDEO       = 'video';
    const UNKNOWN     = 'unknown';
}