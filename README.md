# Framework
Framework for [Baun](https://bauncms.com).

## Contribute

Want to contribute to the Baun framework? That's great. Here is where you should go:

* [Submit a bug report or feature request](https://github.com/BaunCMS/Framework/issues)
* [Submit a pull request](https://github.com/BaunCMS/Framework/pulls)

Note that pull requests should be limited to one feature/fix per request. Pull requests with multiple
features/fixes will be rejected.

## Credits

Baun was originally created by Gilbert Pellegrom from and is currently maintained by [Mark Southard](http://marksouthard.co). Released under the MIT license.

## KRKeegan Fork

This is a fork from the original Baun Framework.  I have added things that I
wanted and found useful.  This includes:

* Enabled HTML formated Blog excerpts.  The main blog index page will now show
formatted excerpts from your entries.  Note that within your template you now
need to change {{ post.excerpt }} to {{ post.excerpt|raw }}
