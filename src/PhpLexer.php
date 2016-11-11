<?php

namespace Kelunik\Highlight;

use PhpParser;

final class PhpLexer implements Lexer {
    private $lexer;
    private $tokenTypeMap;
    private $literalTypeMap;

    public function __construct(PhpParser\Lexer $lexer = null) {
        $this->lexer = $lexer ?: new PhpParser\Lexer\Emulative;

        $classTokenMap = [
            Lexer::TYPE_CONTROL => [
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
            Lexer::TYPE_KEYWORD => [
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
                T_YIELD_FROM,
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
                T_EXTENDS,
                T_IMPLEMENTS,
                T_LIST,
                T_INSTEADOF,
                T_AS,
                T_VAR,
                T_CONST,
                T_RETURN,
            ],
            Lexer::TYPE_CAST => [
                T_UNSET_CAST,
                T_BOOL_CAST,
                T_OBJECT_CAST,
                T_ARRAY_CAST,
                T_STRING_CAST,
                T_DOUBLE_CAST,
                T_INT_CAST,
            ],
            Lexer::TYPE_STRING => [
                T_CONSTANT_ENCAPSED_STRING,
                T_ENCAPSED_AND_WHITESPACE,
            ],
            Lexer::TYPE_COMMENT => [
                T_COMMENT,
            ],
            Lexer::TYPE_DOC_COMMENT => [
                T_DOC_COMMENT,
            ],
            Lexer::TYPE_VARIABLE => [
                T_VARIABLE,
            ],
            Lexer::TYPE_NUMBER => [
                T_LNUMBER,
                T_DNUMBER,
            ],
            Lexer::TYPE_OPEN_CLOSE => [
                T_OPEN_TAG,
                T_OPEN_TAG_WITH_ECHO,
                T_CLOSE_TAG,
            ],
            Lexer::TYPE_IDENTIFIER => [
                T_STRING,
                T_NS_SEPARATOR,
                T_DIR,
                T_FILE,
                T_LINE,
                T_ARRAY,
                T_CALLABLE,
            ],
        ];

        foreach ($classTokenMap as $type => $tokens) {
            foreach ($tokens as $token) {
                $this->tokenTypeMap[$token] = $type;
            }
        }

        $classLiteralMap = [
            Lexer::TYPE_STRING => ['"'],
            Lexer::TYPE_SCREAM => ["@"],
        ];

        foreach ($classLiteralMap as $type => $literals) {
            foreach ($literals as $literal) {
                $this->literalTypeMap[$literal] = $type;
            }
        }
    }

    public function lex(string $source): array {
        $this->lexer->startLexing($source);
        $tokens = [];

        foreach ($this->lexer->getTokens() as $token) {
            if (is_array($token)) {
                $tokens[] = [
                    $this->tokenTypeMap[$token[0]] ?? Lexer::TYPE_LITERAL,
                    $token[1],
                ];
            } else if (is_string($token)) {
                $tokens[] = [
                    $this->literalTypeMap[$token] ?? Lexer::TYPE_LITERAL,
                    $token,
                ];
            } else {
                throw new \RuntimeException("Invalid token type: " . gettype($token));
            }
        }

        return $tokens;
    }
}

