<?php

namespace Kelunik\Highlight;

interface Highlighter {
    public function highlight(string $source): string;
}