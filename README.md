# highlight

A PHP syntax highlighter. Highly unstable, use at your own risk.

```php
<?php

print (new Kelunik\Highlight\DefaultHighlighter)->highlight("<?php print 'Hello World';") . PHP_EOL;
```