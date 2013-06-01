Sitewards BigPipe
=================

Implements a BigPipe option to Magento, so a block can be marked as BigPipe an will be rendered after the first flush appeared.
Facebook uses that technique to avoid a blocking of the loading process by some slower components.

See https://www.facebook.com/note.php?note_id=389414033919 for an explanation by Facebook.

See a demo for the breadcrumb at: http://bigpipe.sitewards.net/index.php/sony-vaio-vgn-txn27n-b-11-1-notebook-pc.html.
The breadcrumb was on purpose delayed for 3 seconds, so you can see the effect.

Manual
------------------
Simply define a bigpipe attribute on the block in your layout.xml. That's all!

Example to move the loading of breadcrumb to the end of your website, while most of the content is already delivered:
<block type="page/html_breadcrumbs" name="breadcrumbs" bigpipe="true"/>

The Loading block collects all called method and set data and transfers it later to the real target block, so even an ->addCrumb call in that example is executed on the final block, too.

It's possible to define an order which bigpipe block should be rendered first:
<block type="page/html_breadcrumbs" name="breadcrumbs" bigpipe="true" bigpipe-order="20" />
<block type="foo/bar" name="foo" bigpipe="true" bigpipe-order="10" />
In that case foo will be rendered, flushed and after that breadcrumb will be rendered and flushed

You have to disable gzip: http://stackoverflow.com/questions/4870697/php-flush-that-works-even-in-nginx

Ideas
------------------
* implement options for different templates on loading block, so each "Loading" dialog on your page can have a different template
* Mashup with Houston to parallelize rendering of blocks
* javascript callback to execute some code after your block was really loaded (should be implemented using prototype event/observer)
* remove core_layout rewrite
* Check for disabled gzip
* add an optional flush after </head> so css and js can be loaded while server is still processing the site (after first research we decided this will be a new module and is not related to BigPipe)
* add feature for a big pipe block in a big pipe, which will be rendered after the parent was rendered
* add chunk to small blocks, so flush is forced even when chunk size configured by webserver is not already reached

Contact
------------------
magento@sitewards.com

License: OSL 3.0

Contribution is appreciated!