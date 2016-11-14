<?php

namespace Kelunik\Highlight;

interface Lexer {
    const TYPE_LITERAL = "literal";
    const TYPE_STRING = "string";
    const TYPE_NUMBER = "number";
    const TYPE_CONTROL = "control";
    const TYPE_KEYWORD = "keyword";
    const TYPE_CAST = "cast";
    const TYPE_COMMENT = "comment";
    const TYPE_DOC_COMMENT = "doc-comment";
    const TYPE_VARIABLE = "variable";
    const TYPE_OPEN_CLOSE = "open-close";
    const TYPE_IDENTIFIER = "identifier";
    const TYPE_SCREAM = "scream";
    const TYPE_TOKEN = "token";
    const TYPE_INVALID = "invalid";

    public function lex(string $source): array;
}