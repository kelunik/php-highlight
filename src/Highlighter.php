<?php

namespace Kelunik\Highlight;

final class Highlighter {
    private $formatter;
    private $lexer;
    private $preProcessors = [];
    private $postProcessors = [];

    public function __construct(TokenFormatter $formatter, Lexer $lexer = null) {
        $this->formatter = $formatter;
        $this->lexer = $lexer ?: new PhpLexer;
    }

    public function addPreProcessor(PreProcessor $preProcessor) {
        $this->preProcessors[] = $preProcessor;
    }

    public function addPostProcessor(PostProcessor $postProcessor) {
        $this->postProcessors[] = $postProcessor;
    }

    public function highlight(string $source): string {
        foreach ($this->preProcessors as $preProcessor) {
            $source = $preProcessor->preProcess($source);
        }

        if ($this->formatter instanceof PreProcessor) {
            $source = $this->formatter->preProcess($source);
        }

        $tokens = $this->lexer->lex($source);
        $output = "";

        foreach ($tokens as list($tokenId, $tokenValue)) {
            $output .= $this->formatter->formatToken($tokenId, $tokenValue);
        }

        if ($this->formatter instanceof PostProcessor) {
            $output = $this->formatter->postProcess($output);
        }

        foreach ($this->postProcessors as $postProcessor) {
            $output = $postProcessor->postProcess($output);
        }

        return $output;
    }
}