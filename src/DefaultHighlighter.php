<?php

namespace Kelunik\Highlight;

use PhpParser\Lexer;
use PhpParser\ParserFactory;

class DefaultHighlighter implements Highlighter {
    private $lexer;
    private $tokenClassMap;

    public function __construct() {
        $this->lexer = new Lexer\Emulative;
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $this->lexer);

        $classTokenMap = [
            "control" => [
                T_IF,
                T_ELSEIF,
                T_ELSE,
                T_ENDIF,
                T_DO,
                T_WHILE,
                T_ENDWHILE,
                T_FOR,
                T_ENDFOR,
                T_FOREACH,
                T_ENDFOREACH,
                T_SWITCH,
                T_ENDSWITCH,
                T_CASE,
                T_CONTINUE,
                T_BREAK,
                T_GOTO,
                T_TRY,
                T_CATCH,
                T_FINALLY,
            ],
            "keyword" => [
                T_REQUIRE,
                T_REQUIRE_ONCE,
                T_INCLUDE,
                T_INCLUDE_ONCE,
                T_INSTANCEOF,
                T_CLONE,
                T_NEW,
                T_EXIT,
                T_DEFAULT,
                T_YIELD,
                T_THROW,
                T_GLOBAL,
                T_UNSET,
                T_ISSET,
                T_EMPTY,
                T_PRINT,
                T_ECHO,
                T_PUBLIC,
                T_PROTECTED,
                T_PRIVATE,
                T_STATIC,
                T_ABSTRACT,
                T_FINAL,
                T_CLASS,
                T_INTERFACE,
                T_TRAIT,
                T_FUNCTION,
                T_USE,
                T_NAMESPACE,
            ],
            "cast" => [
                T_UNSET_CAST,
                T_BOOL_CAST,
                T_OBJECT_CAST,
                T_ARRAY_CAST,
                T_STRING_CAST,
                T_DOUBLE_CAST,
                T_INT_CAST,
            ],
            "string" => [
                T_CONSTANT_ENCAPSED_STRING,
                T_ENCAPSED_AND_WHITESPACE,
            ],
            "comment" => [
                T_COMMENT,
            ],
            "doc-comment" => [
                T_DOC_COMMENT,
            ],
            "variable" => [
                T_VARIABLE,
            ],
            "number" => [
                T_LNUMBER,
                T_DNUMBER,
            ],
        ];

        foreach ($classTokenMap as $class => $tokens) {
            foreach ($tokens as $token) {
                $this->tokenClassMap[$token] = $class;
            }
        }
    }

    public function highlight(string $source): string {
        $this->parser->parse($source);
        $output = "";

        foreach ($this->lexer->getTokens() as $token) {
            if (is_string($token)) {
                if ($token === '"') {
                    $output .= "<span class='string'>\"</span>";
                } else {
                    $output .= $this->escapeHtml($token);
                }
                continue;
            }

            if (isset($this->tokenClassMap[$token[0]])) {
                $output .= "<span class='" . $this->escapeHtml($this->tokenClassMap[$token[0]]) . "'>";
            }

            $output .= $this->escapeHtml($token[1]);

            if (isset($this->tokenClassMap[$token[0]])) {
                $output .= "</span>";
            }
        }

        return "<pre><code>{$output}</code></pre>";
    }

    private function escapeHtml(string $str): string {
        return htmlspecialchars($str, ENT_QUOTES, "utf-8");
    }
}

