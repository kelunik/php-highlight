<?php

namespace Kelunik\Highlight;

interface TokenFormatter {
    public function formatToken(string $type, string $text): string;
}