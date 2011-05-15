<?php
/**
*   Template Name: Index
**/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://purl.org/uF/2008/03/">
    <?php @include('header.php'); ?>
</head>
<body id="www-lukeandphill-com" class="home">
<div id="header">
    <h1 class="vcard">
        <a href="<?php echo (defined('WP_SITEURL'))? WP_SITEURL: get_bloginfo('url'); ?>" rel="home" class="url">
            <img class="fn org logo" src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/lukeandphill-logo.png" width="356" height="70" alt="<?php bloginfo('name'); ?>" />
            <?php bloginfo('name'); ?>
        </a>
    </h1>
    <div class="skip">
        <p>Skoči do: <a href="#main">vsebine</a>, <a href="#primary">navigacije</a>,
        <a href="#search">iskanja</a>, <a href="#footer">noge strani</a></p>
    </div>
    <form action="" id="search" method="post">
        <fieldset>
            <legend>Iskanje</legend>
            <label for="query">Iskani niz:</label>
            <input type="text" class="input" name="query" id="query" value="Vnesite iskani pojem" />
            <input type="image" class="submit" src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/ico/search.gif" alt="Išči" title="Išči" />
        </fieldset>
    </form>
    <ul class="nav" id="primary">
        <li><a href="/" rel="tag">Strojarna</a></li>
        <li><a href="/" rel="tag">Stilizem</a></li>
        <li><a href="/" rel="tag">Oblika</a></li>
        <li><a href="/" rel="tag">Pojedina</a></li>
        <li><a href="/" rel="tag">Domovanje</a></li>
        <li><a href="/" rel="tag">Ravnovesje</a></li>
    </ul>
</div>

<div id="main">
    <ul class="news">
        <li class="exposed hentry">
            <a href="/" rel="bookmark"><img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/_temp/home-01.png" width="593" height="444" alt="Opis slike" /></a>
            <h2 class="entry-title"><a href="/" rel="bookmark">Avto iz blaga, ki spreminja obliko</a></h2>
            <dl class="entry-info">
                <dt>Objavljeno</dt>
                <dd class="published">
                    <span class="value-title" title="2010-01-28T00:00+0100"> </span>
                    28. Januar 2010
                </dd>
                <dt>Avtor</dt>
                <dd class="author vcard"><em class="fn">Natan &quot;Phill&quot; Nikolič</em></dd>
                <dt>Fotografija</dt>
                <dd><a href="http://designyoutrust.com">http://designyoutrust.com</a></dd>
            </dl>
            <p class="entry-summary">Concept cars give automotive designers a chance to let their imaginations run wild, often
            with outlandish results. But even by that measure, BMW has come up with something as strange as it is innovative —
            a shape-shifting car covered with fabric.</p>
        </li>
        <li class="hentry">
            <h3 class="entry-title">
                <a href="/" rel="bookmark"><img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/_temp/home-small-01.png" width="218" height="218" alt="Opis slike" />
                Tilt-Shift Photography (Miniature Faking)</a>
            </h3>
            <dl class="entry-info">
                <dt>Objavljeno</dt>
                <dd class="published">
                    <span class="value-title" title="2010-01-28T00:00+0100"> </span>
                    28. Januar 2010
                </dd>
                <dt>Avtor</dt>
                <dd class="author vcard"><em class="fn">Natan &quot;Phill&quot; Nikolič</em></dd>
            </dl>
            <p class="entry-summary">Few months ago, I got a Kindle. It's a fascinating device, unlike almost any other
            launched by a significant tech company.</p>
        </li>
        <li class="hentry">
            <h3 class="entry-title">
                <a href="/" rel="bookmark"><img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/_temp/home-small-02.png" width="218" height="218" alt="Opis slike" />
                LG Prada — igrača za šminkerje</a>
            </h3>
            <dl class="entry-info">
                <dt>Objavljeno</dt>
                <dd class="published">
                    <span class="value-title" title="2010-01-28T00:00+0100"> </span>
                    28. Januar 2010
                </dd>
                <dt>Avtor</dt>
                <dd class="author vcard"><em class="fn">Natan &quot;Phill&quot; Nikolič</em></dd>
            </dl>
            <p class="entry-summary">Amazing : An organic sculptural landmark that responds to human interaction and expresses
            context awareness using hundreds of sensors and over 15,000 individually addressable optical fibers.</p>
        </li>
        <li class="hentry">
            <h3 class="entry-title">
                <a href="/" rel="bookmark"><img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/_temp/home-small-03.png" width="218" height="218" alt="Opis slike" />
                Breakbeat Science Gear launched!</a>
            </h3>
            <dl class="entry-info">
                <dt>Objavljeno</dt>
                <dd class="published">
                    <span class="value-title" title="2010-01-28T00:00+0100"> </span>
                    28. Januar 2010
                </dd>
                <dt>Avtor</dt>
                <dd class="author vcard"><em class="fn">Natan &quot;Phill&quot; Nikolič</em></dd>
            </dl>
            <p class="entry-summary">With the economy looking like it may be fucked for a while, Breakbeat Science Gear is
            launching a new limited edition, artist driven, street-wear line, with a no-bullshit price tag!</p>
        </li>
        <li class="last hentry">
            <h3 class="entry-title">
                <a href="/" rel="bookmark"><img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/_temp/home-small-04.png" width="218" height="218" alt="Opis slike" />
                Sexy Limited Edition Prints</a>
            </h3>
            <dl class="entry-info">
                <dt>Objavljeno</dt>
                <dd class="published">
                    <span class="value-title" title="2010-01-28T00:00+0100"> </span>
                    28. Januar 2010
                </dd>
                <dt>Avtor</dt>
                <dd class="author vcard"><em class="fn">Natan &quot;Phill&quot; Nikolič</em></dd>
            </dl>
            <p class="entry-summary">A new limited edition set of prints by Igor Vasiliadis - shot in Hong Kong and featuring
            Moscow based DJ project C.L.U.M.B.A and Maria Korabelnikova.</p>
        </li>
    </ul>
</div>
<?php @include('footer.php'); ?>
</body>
</html>