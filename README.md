Sitewards BigPipe
=================

Implements a BigPipe option to Magento, so a block can be marked as BigPipe an will be rendered after the first flush appeared.
Facebook uses that technique to avoid a blocking of the loading process by some slower components.

There is also a really great [explanation by Facebook](https://www.facebook.com/note.php?note_id=389414033919).

See a demo for the breadcrumb at our [showroom](http://bigpipe.sitewards.net/index.php/sony-vaio-vgn-txn27n-b-11-1-notebook-pc.html).
The breadcrumb was on purpose delayed for 3 seconds, so you can see the effect.

Manual
------------------
Simply define a bigpipe attribute on the block in your layout.xml. That's all!

Example to move the loading of breadcrumb to the end of your website, while most of the content is already delivered:

    <block type="page/html_breadcrumbs" name="breadcrumbs" bigpipe="true"/>

The Loading block collects all called method and set data and transfers it later to the real target block, so even an ->addCrumb call in that example is executed on the final block, too.

You have to disable gzip. [StackOverflow-Thread](http://stackoverflow.com/questions/4870697/php-flush-that-works-even-in-nginx) for details.
Also check your system.log, because you'll get a log message when zlib.output_compression is enabled.

Define order of blocks
----------------------
It's possible to define an order which bigpipe block should be rendered first:

    <block type="page/html_breadcrumbs" name="breadcrumbs" bigpipe="true" bigpipe-order="20" />
    <block type="foo/bar" name="foo" bigpipe="true" bigpipe-order="10" />

In that case foo will be rendered, flushed and after that breadcrumb will be rendered and flushed

Define buffer size
------------------
Depending on your server configuration you can configure the module to output whitespaces, until buffer size limit is reached. If the block which should be outputted is smaller than the buffer size you'll have a delay in the flush and have to wait until more blocks are rendered or script is ended to output the block. Go to "System/Configuration/Sitewards BigPipe". Default: 4096

Call a javascript callback on each block load
---------------------------------------------
To execute some javascript when a block was loaded we implemented some kind of basic observer. Just implement a function with the name bigpipeObserver, that will be triggered after the block was loaded to his desired target location.

    /**
     * example for observer
     * @param element
     */
    function bigpipeObserver(element) {
        alert(element.innerHTML);
    }

Define custom loading file to be displayed
------------------------------------------
It's also possible to define your own loading template files. You set them in the layout.xml, so the loading-dialog can be individually styled:

    <block type="page/html_breadcrumbs" name="breadcrumbs" bigpipe="true">
        <action method="setLoadingTemplate"><file>sitewards/myLoadingFile.phtml</file></action>
    </block>

There are no requirements what has to be inside that template file. All the magic is done doing a wrapper block who is wrapped around your template file. Standard template file just looks like this:

    /* template/sitewards/loading.phtml */
    <?php echo $this->__('Loading ...'); ?>

Ideas
------------------
* Mashup with [Houston](https://github.com/airbone42/Houston) or any other multi-threading framework to parallelize rendering of blocks
* remove core_layout rewrite
* add an optional flush after head-tag so css and js can be loaded while server is still processing the site (after first research we decided this will be a new module and is not related to BigPipe)
* add feature for a big pipe block in a big pipe, which will be rendered after the parent was rendered

Contact
------------------
magento@sitewards.com

License: OSL 3.0

Contribution is appreciated, even new issues!