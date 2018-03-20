<?php

/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 10.12.2015
 * Time: 8:54
 */
class Page
{

    public $title;
    public $desctiption;
    public $metakey;
    public $metadesc;
    public $page_body = '';

    public $page_head = array();

    public function __construct()
    {

    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function addHead($tag)
    {
        if (!in_array($tag, $this->page_head)) {
            // $this->page_head[] = $tag;
            echo $tag;
        } else {
            $this->page_head[] = $tag;
        }

        return $this;
    }

    public function addHeadCSS($src)
    {
        return $this->addHead('<link href="' . $src . '" rel="stylesheet" type="text/css" />');
    }

    public function addHeadJs($src)
    {
        return $this->addHead('<script type="text/javascript" src="/' . $src . '"></script>');
    }


    public function printHead()
    {
        global $config;
        ob_start();
        if (!$GLOBALS['page_title']) {
            $GLOBALS['page_title'] =$config->sitename;
        }

        if (!@$GLOBALS['page_keys']) {
            $GLOBALS['page_keys'] = $config->metakeys;
        }
        if (!@$GLOBALS['page_desc']) {
            $GLOBALS['page_desc'] = $config->metadesc;
        }
        ?>
            <meta name="author" content="namles.uz">
            <title><?php echo htmlspecialchars($GLOBALS['page_title']) ?></title>
            <meta name="keywords" content="<?php echo  htmlspecialchars($GLOBALS['page_keys']) ?>" />
            <meta name="description" content="<?php  echo htmlspecialchars($GLOBALS['page_desc']) ?>"/>
            <meta name="generator" content="namles.uz"/>
            <meta property="og:locale" content="en_US" />
            <meta property="og:type" content="article" />
            <meta property="og:title" content="<?php echo htmlspecialchars($GLOBALS['page_title']); ?>">
            <meta property="og:description" content="<?php echo htmlspecialchars($GLOBALS['page_desc']) ?>">
            <meta property="og:site_name" content="namles.uz">
            <?php
        if ($GLOBALS['site_url']) {
            ?>
            <meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST'] . $GLOBALS['site_url'] ?>"/>
            <?php
        }
        ?>
            <meta property="fb:admins" content="664589600" />
        <?php
        if ($GLOBALS['page_soc_image']) {
            ?>
            <meta property="og:image" content="<?php echo $GLOBALS['page_soc_image']; ?>"/>
            <meta property="og:image:url" content="<?php echo $GLOBALS['page_soc_image']; ?>"/>
            <?php
              }
        ?>
           <meta property="og:image:width" content="680">
            <meta property="og:image:height" content="510">

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:description" content="<?php echo htmlspecialchars($GLOBALS['page_desc']) ?>" />
        <meta name="twitter:title" content="<?php echo htmlspecialchars($GLOBALS['page_title']); ?>" />
        <meta name="twitter:site" content="@vodiymedia_uz" />
        <?php
        if ($GLOBALS['page_soc_image']) {
        ?>
        <meta name="twitter:image" content="<?php echo $GLOBALS['page_soc_image']; ?>" />
            <?php } ?>
        <meta name="twitter:creator" content="@muhsin_uz" />

        <?php
        //jQuery library
//        echo '<script type="text/javascript" src="/includes/jquery/jquery.js"></script>';
        //Core JS library
//        echo '<script type="text/javascript" src="/include/js/common.js"></script>' . "\n";
        //Blend page transitions
//    if (@$config['page_fx']) {
//    echo '<META HTTP-EQUIV="Page-Exit" CONTENT="blendTrans(Duration=0.5)">' . "\n";
//    echo '<META HTTP-EQUIV="Page-Enter" CONTENT="blendTrans(Duration=0.5)">' . "\n";
//    }
        foreach ($GLOBALS['page_head'] as $key => $value) {
            echo $GLOBALS['page_head'][$key] . "\n";
        }
        echo ob_get_clean();
    }

    public function printSitename()
    {
        return Registry::get("Config")->sitename;
    }

    public function printComponent()
    {

        $this->page_body = Core::getCallEvent("PRINT_PAGE_BODY", $this->page_body);
        echo $this->page_body;
    }
}

?>