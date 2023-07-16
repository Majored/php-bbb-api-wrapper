<?php

namespace Majored\PhpBbbApiWrapper;

/** Holds declarations for different request types. */
class RequestType {
    /** @var int An integer value representing the read endpoints (ie. GET). */
    public const READ = 0;

    /** @var int An integer value representing the write endpoints (ie. POST, PATCH, & DELETE). */
    public const WRITE = 1;
}