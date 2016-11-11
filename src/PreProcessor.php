<?php

namespace Kelunik\Highlight;

interface PreProcessor {
    public function preProcess(string $rawSource): string;
}