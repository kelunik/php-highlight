# highlight

A PHP syntax highlighter. Highly unstable, use at your own risk.

```php
<?php

$hl = new Kelunik\Highlight\Highlighter(new HtmlFormatter("php"));

// <optional>
$hl->addPostProcessor(new Your\Custom\PostProcessor);
// </optional>

$hl->highlight("<?php print 'Hello World';");
```

**CLI**

```bash
bin/highlight /path/to/file.php > out.html
```