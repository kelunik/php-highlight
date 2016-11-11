<?php

namespace Kelunik\Highlight;

interface PostProcessor {
    public function postProcess(string $highlightOutput): string;
}