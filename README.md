# highlight

A (PHP) syntax highlighter written in PHP.

```php
<?php

$hl = new Kelunik\Highlight\Highlighter(new HtmlFormatter("php"));

// <optional>
$hl->addPreProcessor(new Your\Custom\PreProcessor);
$hl->addPostProcessor(new Your\Custom\PostProcessor);
// </optional>

print $hl->highlight("<?php print 'Hello World';");
```

**CLI**

These CLI scripts should give you some hints on how to use the API.

```bash
bin/highlight /path/to/file.php
```

```bash
bin/highlight-html /path/to/file.php > out.html
```

**Supported Languages**

Currently, only PHP is supported. But you could just add another `Lexer`.