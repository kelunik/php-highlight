<?php

namespace Kelunik\Highlight;

final class HtmlFormatter implements PreProcessor, TokenFormatter, PostProcessor {
    private $prefix;
    private $start;
    private $end;
    private $lineOpen;
    private $lineClose;

    public function __construct(string $prefix = "", string $start = "<pre><code class='source-php'>", string $end = "</code></pre>") {
        $this->prefix = $prefix === "" ? "" : $prefix . "-";

        $this->start = $start;
        $this->end = $end;

        $this->lineOpen = "<span class='" . $this->escapeHtml($this->prefix . "line") . "'>";
        $this->lineClose = "</span>";
    }

    public function preProcess(string $source): string {
        $source = str_replace(["\r\n", "\r"], "\n", $source);

        if (substr($source, -1) === "\n") {
            $source = substr($source, 0, -1);
        }

        return $source;
    }

    public function formatToken(string $type, string $text): string {
        if ($type === Lexer::TYPE_LITERAL) {
            return implode($this->lineClose . "\n" . $this->lineOpen, explode("\n", $this->escapeHtml($text)));
        }

        $tokenOpen = "<span class='" . $this->escapeHtml($this->prefix . $type) . "'>";
        $tokenClose = "</span>";

        return $tokenOpen . implode($tokenClose . $this->lineClose . "\n" . $this->lineOpen . $tokenOpen, explode("\n", $this->escapeHtml($text))) . $tokenClose;
    }

    public function postProcess(string $highlightOutput): string {
        return $this->start . $this->lineOpen . $highlightOutput . $this->lineClose . $this->end;
    }

    private function escapeHtml(string $str): string {
        return htmlspecialchars($str, ENT_QUOTES, "utf-8");
    }
}