<?php

namespace Kelunik\Highlight;

class JsonLexer implements Lexer {
    private $whitespace = [
        "\t" => true,
        "\n" => true,
        "\r" => true,
        " " => true,
    ];

    private $source;
    private $index;

    public function lex(string $source): array {
        $this->source = $source;
        $this->index = 0;

        $tokens = [];

        while ($this->index < strlen($this->source)) {
            $tokens[] = $this->lexStep();
        }

        return $tokens;
    }

    private function lexStep() {
        $length = strlen($this->source);
        $current = $this->source[$this->index];

        switch ($current) {
            case "\t":
            case "\n":
            case "\r":
            case " ":
                $value = $this->source[$this->index];

                while ($this->index < $length && isset($this->source[++$this->index]) && isset($this->whitespace[$this->source[$this->index]])) {
                    $value .= $this->source[$this->index];
                }

                return [
                    self::TYPE_LITERAL,
                    $value,
                ];

            case "{":
            case "}":
            case "[":
            case "]":
            case ":":
            case ",":
                return [
                    self::TYPE_TOKEN,
                    $this->source[$this->index++],
                ];

            case '"':
                $value = $this->source[$this->index++];
                $escaped = false;

                while ($this->index < $length) {
                    $char = $this->source[$this->index++];
                    $value .= $char;

                    if ($char === "\\" && !$escaped) {
                        $escaped = true;
                    } else if ($char === '"' && !$escaped) {
                        return [
                            Lexer::TYPE_STRING,
                            $value,
                        ];
                    } else {
                        $escaped = false;
                    }
                }

                // EOF, but return unclosed string
                return [
                    Lexer::TYPE_STRING,
                    $value,
                ];

            case "-":
            case "0":
            case "1":
            case "2":
            case "3":
            case "4":
            case "5":
            case "6":
            case "7":
            case "8":
            case "9":
                $this->source[$this->index];

                if ($result = preg_match("(^-?(?:0|[1-9][0-9]*)(?:\\.[0-9]+)?(?:[Ee](?:\\+|\\-)?[0-9]+)?)", substr($this->source, $this->index), $match, 0)) {
                    $this->index += strlen($match[0]);
                    return [
                        Lexer::TYPE_NUMBER,
                        $match[0],
                    ];
                } else {
                    return [
                        Lexer::TYPE_INVALID,
                        $this->source[$this->index++],
                    ];
                }

            default:
                $tmp = substr($this->source, $this->index, 4);

                if ($tmp === "true" || $tmp === "null") {
                    $this->index += 4;
                    return [
                        Lexer::TYPE_IDENTIFIER,
                        $tmp,
                    ];
                } else if ($tmp === "fals" && isset($this->source[$this->index + 4]) && $this->source[$this->index + 4] === "e") {
                    $this->index += 5;
                    return [
                        Lexer::TYPE_IDENTIFIER,
                        "false",
                    ];
                }

                return [
                    Lexer::TYPE_INVALID,
                    $this->source[$this->index++],
                ];
        }
    }
}