<?php

namespace Kelunik\Highlight;

class AnsiFormatter implements TokenFormatter {
    private $typeCodeMap = [
        Lexer::TYPE_KEYWORD => "0;35", // magenta
        Lexer::TYPE_STRING => "0;32", // green
        Lexer::TYPE_VARIABLE => "0;34", // blue
        Lexer::TYPE_DOC_COMMENT => "0;33", // yellow
        Lexer::TYPE_CAST => "0;33", // yellow
        Lexer::TYPE_NUMBER => "0;31", // red
        Lexer::TYPE_COMMENT => "0;37", // dark grey
        Lexer::TYPE_OPEN_CLOSE => "0;37", // dark grey
    ];

    public function formatToken(string $type, string $text): string {
        if ($type === Lexer::TYPE_LITERAL) {
            return $text;
        }

        return "\e[" . ($this->typeCodeMap[$type] ?? 0) . "m" . $text . "\e[0m";
    }
}