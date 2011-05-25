<?php
/**
 * Attachment page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// id depends on the type of the first posts image
$EditorialId = 'gallery';
$EditorialClass = 'clear';
@include('header.php');
?>
<div class="content clear" role="main">
    <article id="single" class="hentry">
        <header>
            <h1 class="entry-title"><a href="/" rel="prev">Syncing wheel reinvented</a></h1>
        </header>
        <section id="media">
            <figure>
                <span><img src="images/_temp/news-photo-01.jpg" class="photo" alt="Syncing wheel"></span>
                <figcaption>
                    <h3>Futuristic bicycle concept</h3>
                    <p>Curabitur sit amet ligula non elit consectetur faucibus non et nulla. Duis eleifend, leo vel suscipit tempus,
                    justo massa sollicitudin felis, vel mollis lorem magna id dolor.</p>
                </figcaption>
            </figure>
        </section>
        <aside role="complementary">
            <header>
                <h2>1/4</h2>
            </header>
            <nav id="navigate" role="navigation">
                <ul>
                    <li class="previous">
                        <a href="/" rel="prev">
                            <img src="images/_temp/thumb-01.jpg" alt="Media thumbnail">
                            Previous image
                        </a>
                    </li>
                    <li class="next">
                        <a href="/" rel="next">
                            <img src="images/_temp/thumb-02.jpg" alt="Media thumbnail">
                            Next image
                        </a>
                    </li>
                </ul>
            </nav>
            <fieldset id="embed">
                <h4><label for="embed-code">Embed code</label></h4>
                <p>There’s no need for downloading and uploading it to your blog/website when you can easily embed it.</p>
                <input id="embed-code" value="&lt;script type=&quot;text/javascript&quot; src=&quot;http://use.typekit.c&quot;&gt;">
            </fieldset>
        </aside>
    </article>
    <nav id="tabs" role="navigation">
        <ul>
            <li><a href="/">Article</a></li>
            <li class="selected"><a href="/">Image gallery</a></li>
            <li><a href="/">Feedback <em>128</em></a></li>
        </ul>
    </nav>
    <section class="featured">
        <header>
            <h3>You might also enjoy</h3>
        </header>
        <article class="f1 hentry">
            <figure>
                <a href="/" rel="bookmark"><img src="images/_temp/article-thumb-01.jpg" alt="Image description"></a>
            </figure>
            <div class="info">
                <footer>
                    <a href="/styling/" rel="tag">Styling</a>
                    <time class="published" pubdate datetime="2011-06-01T00:00">
                        <span class="value-title" title="2011-06-01T00:00"> </span>
                        1ST June
                    </time>
                    <em class="v-hidden author vcard">Written by <a href="/" class="fn n url">Natan Nikolič</a></em>
                </footer>
                <header>
                    <h2 class="entry-title">
                        <a href="/" rel="bookmark">Tilt-Shift Photography (Miniature Faking)</a>
                    </h2>
                </header>
            </div>
            <p class="entry-summary">Few months ago, I got a Kindle. It's a fascinating
            device, unlike almost any other launched by a significant tech company.</p>
        </article>
        <article class="f2 hentry">
            <figure>
                <a href="/" rel="bookmark"><img src="images/_temp/article-thumb-02.jpg" alt="Image description"></a>
            </figure>
            <div class="info">
                <footer>
                    <a href="/toyshop/" rel="tag">Toyshop</a>
                    <time class="published" pubdate datetime="2011-06-01T00:00">
                        <span class="value-title" title="2011-06-01T00:00"> </span>
                        1ST June
                    </time>
                    <em class="v-hidden author vcard">Written by <a href="/" class="fn n url">Natan Nikolič</a></em>
                </footer>
                <header>
                    <h2 class="entry-title">
                        <a href="/" rel="bookmark">LG Prada — igrača za šminkerje</a>
                    </h2>
                </header>
            </div>
            <p class="entry-summary">Amazing : An organic sculptural landmark that responds
            to human interaction and expresses context awareness using hundreds of sensors
            and over 15,000 individually addressable optical fibers.</p>
        </article>
        <article class="f3 hentry">
            <figure>
                <a href="/" rel="bookmark"><img src="images/_temp/article-thumb-03.jpg" alt="Image descriptione"></a>
            </figure>
            <div class="info">
                <footer>
                    <a href="/machinery/" rel="tag">Machinery</a>
                    <time class="published" pubdate datetime="2011-06-01T00:00">
                        <span class="value-title" title="2011-06-01T00:00"> </span>
                        1ST June
                    </time>
                    <em class="v-hidden author vcard">Written by <a href="/" class="fn n url">Natan Nikolič</a></em>
                </footer>
                <header>
                    <h2 class="entry-title">
                        <a href="/" rel="bookmark">Breakbeat Science Gear launched!</a>
                    </h2>
                </header>
            </div>
            <p class="entry-summary">With the economy looking like it may be fucked for a while,
            Breakbeat Science Gear is launching a new limited edition, artist driven, street-wear
            line, with a no-bullshit price tag!</p>
        </article>
        <article class="f4 hentry">
            <figure>
                <a href="/" rel="bookmark"><img src="images/_temp/article-thumb-04.jpg" alt="Image description"></a>
            </figure>
            <div class="info">
                <footer>
                    <a href="/" rel="tag">Designed</a>
                    <time class="published" pubdate datetime="2011-06-01T00:00">
                        <span class="value-title" title="2011-06-01T00:00"> </span>
                        1ST June
                    </time>
                    <em class="v-hidden author vcard">Written by <a href="/" class="fn n url">Natan Nikolič</a></em>
                </footer>
                <header>
                    <h2 class="entry-title">
                        <a href="/" rel="bookmark">Sexy Limited Edition Prints</a>
                    </h2>
                </header>
            </div>
            <p class="entry-summary">A new limited edition set of prints by Igor Vasiliadis
            - shot in Hong Kong and featuring Moscow based DJ project C.L.U.M.B.A and Maria Korabelnikova.</p>
        </article>
    </section>
</div>
<?php @include('footer.php'); ?>