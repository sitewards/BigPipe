Sitewards BigPipe
=================

Implements a BigPipe option to Magento, so a block can be marked as BigPipe an will be rendered after the first flush appeared.
Facebook uses that technique to avoid a blocking of the loading process by some slower components.

See https://www.facebook.com/note.php?note_id=389414033919 for an explanation by Facebook.

Manual
------------------
Simply define a bigpipe attribute on the block in your layout.xml. That's all!

Example to move the loading of breadcrumb to the end of your website, while most of the content is already delivered:
<block type="page/html_breadcrumbs" name="breadcrumbs" bigpipe="true"/>

The Loading block collects all called method and set data and transfers it later to the real target block, so even an ->addCrumb call in taht example is executed on the final block, too. That is currently not working for child blocks of the big pipe block.

Todos
------------------
* implement called methods wrapper for child blocks of bigpipe blocks

Ideas
------------------
* implement options for different templates on loading block, so each "Loading" dialog on your page can have a different template
* implement an order, so you can define which block should be loaded first, second ...
* Mashup with Houston to parallize rendering of blocks
* javascript callback to execute some code after your block was really loaded
* remove core_layout rewrite 

Contact
------------------
magento@sitewards.com

Contribution is appreciated!