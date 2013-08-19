/*
 Theme Name:     Editorial Custom
 Description:    Custom Editorial Styles
 Author:         Editorial Template
 Version:        2.3 (04/2013)
 Template:       <?php echo $this_theme_template, "\n"; ?>
*/

/*********** IMPORTANT!! DO NOT MODIFY THIS LINE **********/
@import url("../<?php echo $this_theme_name; ?>/style.css");
/**********************************************************/

/*

This file overwrites properties from original editorial.css. It's necesery to keep the code
in preseted order, othervise custom CSS wont work effective.

Here are some prepared options for customization.
Do search & replace (S&R) for all quoted ("") strings mentioned below in this file.

option 01 change colors:
- S&R "#fff" {background color}
- S&R "#333" {default color}
- S&R "#ddd" {dotted border color}
- S&R "#262626" {title color: title, link on title, content / default link, default widget text color etc.}
- S&R "#ccc" {image border color, tabs line, comment author without URL}
- S&R "#f2f2f2" {comment number, footer subscribe}
- S&R "#d00" {red color: post categories, selected category, required fields, colophon author title, widget titles etc.}
- S&R "#999" {gray color: author & publish date, image captions, form labels etc.}
- S&R "#000" {black color: footer links}
- S&R "#a5a5a5" {image desctiption, table footer}
- S&R "#e6e6e6" {table border}
- S&R "#f5f5f5" {table alternate color}
- S&R "#e7d6cc" {erros message title color}
- S&R "#e6dada" {errors border}
- S&R "#faf3ed" {errors backgorund}
- S&R "#dce6ca" {success message border}
- S&R "#f2f5e3" {success message background}
- S&R "#79a500" {green color: success message lead}
- S&R "#bfbfbf" {border color, gallery items counter}
- S&R "#f4f4f4" {lead quatation marks color}
- S&R "#d6d6d6" {content link border bottom color}
- S&R "#a6a6a6" {input border, tabs border}
- S&R "#d9d9d9" {inpiut border, title no-link}
- S&R "#595959" {switch layout text color}
- S&R "#7f7f7f" {featured description}

option 02 change font:
- S&R "minion-pro, georgia, serif" {default typography with Typekit}
- S&R "helvetica, arial, sans-serif" {form fields, image captions etc.}

option 03 change font size:
- go through this CSS and change sizes at each desirable declaration

option 04 disable hover transitions:
- search for "disable transitions"

*/

/* 1.ROOT ___________________________________________________________________________________________________________ */
/* 2.HEADINGS _______________________________________________________________________________________________________ */
/* 3.TYPOGRAPHY _____________________________________________________________________________________________________ */
/* 4.LINKS __________________________________________________________________________________________________________ */
/* 5.FIGURES & IMAGES  ______________________________________________________________________________________________ */
/* 6.TABLES _________________________________________________________________________________________________________ */
/* 7.FORMS __________________________________________________________________________________________________________ */
/* 8.BANNER _________________________________________________________________________________________________________ */
/* 9.NAVIGATION _____________________________________________________________________________________________________ */
/* 10.CONTENT _______________________________________________________________________________________________________ */
/* 11.MAIN __________________________________________________________________________________________________________ */
/* 12.COMPLIMENTARY _________________________________________________________________________________________________ */
/* 13.CONTENTINFO ___________________________________________________________________________________________________ */
/* 14.GLOBAL OBJECTS   ______________________________________________________________________________________________ */
/* 15.VENDOR-SPECIFIC _______________________________________________________________________________________________ */
/* 16.TEMPLATE SPECIFICS ____________________________________________________________________________________________ */
/* 17.MODERNIZR _____________________________________________________________________________________________________ */

/* 0.RESET __________________________________________________________________________________________________________ */
/* 1.ROOT ___________________________________________________________________________________________________________ */

html {
	background: #fff;
}

body {
	font-size: 100%;
	line-height: 20px;
	font-family: minion-pro, georgia, serif;
	color: #333;
}

#primary, .inside #single footer, .inside-portrait #single footer, #navigate ul, #embed, #paging,#widgets, .widget,
.entry-content blockquote {
	border-top-color: #ddd;
}

#rss, .notice, #tabs, #team li, #comments article, .layout-list .featured article, .entry-content
blockquote p, #widgets .group, #widgets .adapt {
	border-bottom-color: #ddd;
}

/* 2.HEADINGS _______________________________________________________________________________________________________ */

h1, h1 a, h2, h2 a, h3, h4, h5, h6 {
	color: #262626;
}

.home h1, .home-portrait h1, .featured h2, #comments cite, #paging .more, #embed h4, .message .lead, #common h2,
#team .fn, address .fn, .entry-content h2, #try {
	font-family: minion-pro, georgia, serif;
}

#comments cite, #paging .more {
	color: #ccc;
}

h1, h2 {
	font-size: 35px;
	line-height: 35px;
}

#common h2 {
	font-size: 22px;
	line-height: 30px;
}

h3 {
	font-size: 28px;
}

.entry-content h3 {
	font-size: 18px;
	line-height: 22px;
}

.entry-content h4 {
	font-size: 14px;
	line-height: 20px;
}

.notfound h1 {
	font-size: 60px;
	line-height: 70px;
}

.notfound h2 {
	font-size: 200px;
	color: #f2f2f2;
}

/* 3.TYPOGRAPHY _____________________________________________________________________________________________________ */

h1 em, #comments-form li em, .qa em, input, textarea, table {
	font-family: helvetica, arial, sans-serif;
}

h1 em, caption, #comments-form li em, .qa em, .message li, #errors .lead, #primary .selected a, .featured footer a,
#exposed footer a, .inside #single footer a, .inside-portrait #single footer a, .notice strong, #paging strong,
#navigate a, #team .title, footer h3, .notfound h1, .widget h4 {
	color: #d00;
}

p, ol, ul, dl, address {
	font-size: 16px;
}

.entry-content h2 {
	font-size: 20px;
	line-height: 22px;
}

.entry-content ul li:before {
	color: #d00;
}

.entry-content blockquote p {
	font-size: 24px;
	line-height: 30px;
	color: #999;
}


.submit input {
	border: 1px solid #fff;
	font-family: minion-pro, georgia, serif;
	font-size: 14px;
	color: #000;
}

/* 4.LINKS __________________________________________________________________________________________________________ */

a {
	color: #262626;
}

/* 5.FIGURES & IMAGES _______________________________________________________________________________________________ */

figcaption, figcaption p, figcaption h3, #media-elements h2 {
	font-size: 12px;
	line-height: 15px;
	font-family: helvetica, arial, sans-serif;
}

figcaption {
	color: #999;
}

figcaption h3 {
	color: #999;
}

figcaption p {
	color: #a5a5a5;
}

/* 6.TABLES _________________________________________________________________________________________________________ */

table {
	font-size: 12px;
}

caption {
	font-size: 10px;
}

th, tbody td {
	border-bottom-color: e6e6e6;
}

tfoot {
	color: #a5a5a5;
}

tbody tr:nth-child(2n+2) td {
	background: #f5f5f5;
}

/* 7.FORMS __________________________________________________________________________________________________________ */


#query, #embed-code, #comments-form .text input, #comments-form textarea, #comments-form .riddle input {
	border-color: #a6a6a6;
	border-right-color: #d9d9d9;
	border-bottom-color: #d9d9d9;
	font-size: 14px;
	line-height: 20px;
}

#query {
	font-size: 13px;
}

.notfound #search {
	background: #e6e6e6;
}

#comments-form li label, #layout p, #layout li, .qa em {
	font-size: 10px
	line-height: 14px;
	font-family: helvetica, arial, sans-serif;
	color: #999;
}

#comments-form .text input, #comments-form .area textarea, #comments-form .riddle strong {
	color: #333;
}

#comments-form .riddle span {
	font-size: 18px;
}

#comments-form .riddle input {
	font-size: 12px;
}

#comments-form .error input, .error textarea, comments-form .error textarea {
	border-color: #d00;
}

.message h3 {
	color: #e7d6cc;
}

.message p, .message li {
	font-size: 18px;
	line-height: 20px;
}

#errors {
	border-color: #e6dada;
	background: #faf3ed;
}

#success {
	border-color: #dce6ca;
	background: #f2f5e3;
}

#success h3 {
	color: #fff;
}

#success .lead {
	color: #79a500;
}

/* 8.BANNER _________________________________________________________________________________________________________ */

#header {
	border-bottom-color: #bfbfbf;
}

#try {
	font-size: 16px;
	line-height: 21px;
}

/* 9.NAVIGATION _____________________________________________________________________________________________________ */

#primary a:before, #primary a:after, #footer nav a:before, #footer nav a:after, #rss a:before,
#rss a:after, .widget li a:before, .widget li a:after, #tabs a:before, #tabs a:after {
	border-left-color: #fff;
}

#primary li, #rss li, #footer nav li, #tabs li {
	font-size: 13px;
	line-height: 15px;
	font-family: minion-pro, georgia, serif;
}

#primary a, #footer nav a, #rss a, .widget li a, #tabs a {
	border-left-color: #d00;
}

/* 10.CONTENT _______________________________________________________________________________________________________ */
/* 11.MAIN __________________________________________________________________________________________________________ */

.featured figure a, #exposed figure a, #to-gallery, .photo-adapt, #navigate img, #team img {
	border-color: #ccc;
}

/* disable transitions */
/*
.featured figure a, #exposed figure a, #to-gallery, .photo-adapt, #navigate img, #team img, #single .entry-content a {
	-moz-transition: none;
	-webkit-transition: none;
	transition: none;
}
*/

figure a:hover, .featured figure a:hover, #exposed figure a:hover, #to-gallery:hover {
	border-color: #d00;
}

.featured h2, #exposed h2 {
	font-size: 20px;
	line-height: 25px;
}

.featured footer, #exposed footer, .inside #single footer, .inside-portrait #single footer, #comments time, #paging p,
footer h3, .widget h4, #single footer em, #media-count {
	font-family: helvetica, arial, sans-serif;
}

.featured footer, #exposed footer, .inside #single footer, .inside-portrait #single footer, #comments time, #paging p,
footer h3, .widget h4 {
	font-size: 10px;
	line-height: 12px;
}

.featured time, #exposed time, #single time, .inside #single footer em, .inside-portrait #single footer em,
.inside #single footer em a, .inside-portrait #single footer em a, #paging p {
	color: #999;
}

#exposed {
	border-bottom-color: #bfbfbf;
}

#exposed p {
	font-size: 20px;
	line-height: 25px;
	color: #262626;
}

#intro .entry-summary {
	font-size: 20px;
	line-height: 26px;
}

#intro .entry-summary:before, #intro .entry-summary:after {
	font-size: 600px;
	line-height: 600px;
	color: #f4f4f4;
}

/* change quote */
/*
#intro .entry-summary:before {
	content: '“';
	left: -20px;
	top: -85px;
}

#intro .entry-summary:after {
	content: '”';
	right: -15px;
	bottom: -125px;
}
*/

#single .entry-content a, #comments .show a {
	border-bottom-color: #d6d6d6;
}

#single .entry-content a:hover {
	border-color: #d00;
}

#single .social li {
	font-size: 11px;
	color: #d9d9d9;;
}

#single .social li a {
	color: #000;
}

.gallery #single h1, .gallery-portrait #single h1, .feedback #single h1, .notice a {
	color: #d9d9d9;
}

#to-gallery, .photo-adapt, .mejs-container {
	font-size: 18px;
	background: #fff;
}

#media-count {
	font-size: 18px;
	color: #262626;
	background-color: #fff;
}

.notice {
	font-size: 22px;
	line-height: 25px;
	color: #262626;
}

.notice em {
	color: #262626;
}

#comments h2, .message h3 {
	font-size: 100px;
	line-height: 100px;
}

#comments h2 {
	color: #f2f2f2;
}

#comments cite {
	font-size: 18px;
	line-height: 25px;
	color: #999;
}

#comments cite a, #comments .no-link {
	color: #333;
}

.bad-comment, .trackback {
	color: #999;
}

#paging .more {
	font-size: 18px;
	line-height: 25px;
}

#tabs .selected a {
	border-color: #ddd;
}

#common .entry-content p {
	font-size: 18px;
	line-height: 25px;
}

#common address {
	font-size: 18px;
	line-height: 25px;
}

/* 12.COMPLIMENTARY _________________________________________________________________________________________________ */

.gallery aside h2, .gallery-portrait aside h2 {
	color: #bfbfbf;
}

#navigate li, .favorize .score, .favorize .coin {
	font-family: helvetica, arial, sans-serif;
}

#navigate li {
	font-size: 10px;
	line-height: 12px;
}

#navigate a {
	background: #fff;
}

#navigate .previous span, #navigate .next span {
	color: #7f7f7f;
}

#navigate .disabled strong {
	color: #7f7f7f;
}

#navigate .disabled strong em {
	font-family: helvetica, arial, sans-serif;
}

#navigate .is-video span {
	color: #d00;
}

#embed h4 {
	font-size: 22px;
	line-height: 25px;
}

#embed p {
	font-size: 16px;
	line-height: 20px;
	color: #7f7f7f;
}

#embed-code {
	color: #999;
}

.favorize label, .score, .coin {
	border-color: #bfbfbf;
}

.favorize label em {
	border-color: #fff;
}

.favorize .score, .favorize .coin {
	font-size: 11px;
	line-height: 19px;
}

.favorize .score {
	border-color: #d9d9d9;
	color: #79a500;
	background: #f5f5f5;
}

.favorize .negative {
	color: #d00;
}

.favorize .coin {
	border-color: #79a500;
	color: #fff;
	background: #79a500;
}

.favorize .coin.negative {
	border-color: #d00;
	background: #d00;
}

#team .title {
	font-family: minion-pro, georgia, serif;
	font-size: 22px;
	line-height: 24px;
}

#team .fn, #team .email {
	color: #333;
}

#team .fn {
	font-size: 22px;
	line-height: 24px;
}

/* 13.CONTENTINFO ___________________________________________________________________________________________________ */

.widget li {
	font-size: 13px;
	color: #262626;
}

.widget li a {
	font-family: minion-pro, georgia, serif;
}

/* calendar widget */
#wp-calendar caption {
	color: #333;
}

/* RSS widget */
.widget h4 .rsswidget {
	color: #d00;
}

.widget .rss-date, #copyright, #editorial {
	font-family: helvetica, arial, sans-serif;
}

.widget .rss-date {
	font-size: 10px;
	color: #333;
}

.widget .rssSummary, .widget cite {
	font-size: 16px;
}


#footer {
	border-top-color: #bfbfbf;
}

#rss li, #footer nav li, #footer .xoxo li, #tabs li {
	color: #d9d9d9;
}

#rss a, #footer nav a {
	color: #000;
}

#copyright {
	font-size: 11px;
	line-height: 15px;
	color: #999;
}

#copyright a {
	color: #999;
}

#footer .xoxo li {
	font-size: 11px;
}

/* 14.GLOBAL OBJECTS   ______________________________________________________________________________________________ */
/* 15.VENDOR-SPECIFIC _______________________________________________________________________________________________ */

a:link {
	-webkit-tap-highlight-color: #d00;
}

::-webkit-selection {
	background: #d00;
}

::-moz-selection {
	background: #d00;
	color: #fff;
}

::selection {
	background: #d00;
	color: #fff;
}

::-webkit-input-placeholder {
	color: #999;
}

input:-moz-placeholder {
	color: #999;
}

.ie6 .inside #media, .ie7 .inside #media {
	border-bottom-color: #fff;
}

/* 16.TEMPLATE SPECIFICS ____________________________________________________________________________________________ */
/* 17.MODERNIZR _____________________________________________________________________________________________________ */

/* MEDIA QUERIES */

/*Print _____________________________________________________________________________________________________________ */

@media print {

}

/*480px [iPhone 3G/3GS (landscape), Meizu M8 (portrait), Nexus one (portrait)] ______________________________________ */

@media only screen and (min-width:480px) {

/* 1.ROOT 480px _____________________________________________________________________________________________________ */
/* 2.HEADINGS 480px _________________________________________________________________________________________________ */

h1, h2 {
	font-size: 53px;
	line-height: 53px;
}

h3 {
	font-size: 36px;
	line-height: 36px;
}

.home h1, .home-portrait h1 {
	font-size: 48px;
	line-height: 48px;
}

/* 3.TYPOGRAPHY 480px _______________________________________________________________________________________________ */

.entry-content h2 {
	font-size: 22px;
	line-height: 25px;
}

.entry-content li {
	font-size: 18px;
	line-height: 25px;
}

/* 4.LINKS 480px ____________________________________________________________________________________________________ */
/* 5.FIGURES & IMAGES 480px _________________________________________________________________________________________ */
/* 6.TABLES 480px ___________________________________________________________________________________________________ */
/* 7.FORMS 480px ____________________________________________________________________________________________________ */
/* 8.BANNER 480px ___________________________________________________________________________________________________ */
/* 9.NAVIGATION 480px _______________________________________________________________________________________________ */
/* 10.CONTENT 480px _________________________________________________________________________________________________ */
/* 11.MAIN 480px ____________________________________________________________________________________________________ */

.featured h2, #exposed h2 {
	font-size: 22px;
}

#intro .entry-summary {
	font-size: 24px;
	line-height: 30px;
}

#single .entry-content p, #comments .show, #comments h4 {
	font-size: 18px;
	line-height: 25px;
}

#single .entry-content blockquote p {
	font-size: 24px;
	line-height: 30px;
}

#tabs ul {
	border-top-color: #a6a6a6;
}

#tabs a {
	border-color: #ccc;
	color: #333;
}

#tabs .selected a {
	border-color: #a6a6a6;
	background: #fff;
}

/* 12.COMPLIMENTARY 480px ___________________________________________________________________________________________ */
/* 13.CONTENTINFO 480px _____________________________________________________________________________________________ */
/* 14.GLOBAL OBJECTS 480px __________________________________________________________________________________________ */
/* 15.VENDOR-SPECIFIC 480px _________________________________________________________________________________________ */
/* 16.TEMPLATE SPECIFICS 480px ______________________________________________________________________________________ */
/* 17.MODERNIZR 480px _______________________________________________________________________________________________ */

}

/*640px [iPhone 4G (portrait),  Meizu M8 (landscape)] _______________________________________________________________ */

@media only screen and (min-width:640px) {

/* 1.ROOT 640px _____________________________________________________________________________________________________ */
/* 2.HEADINGS 640px _________________________________________________________________________________________________ */

.notfound h1 {
	font-size: 100px;
	line-height: 115px;
}

.notfound h2 {
	font-size: 420px;
}

/* 3.TYPOGRAPHY 640px _______________________________________________________________________________________________ */
/* 4.LINKS 640px ____________________________________________________________________________________________________ */
/* 5.FIGURES & IMAGES 640px _________________________________________________________________________________________ */
/* 6.TABLES 640px ___________________________________________________________________________________________________ */
/* 7.FORMS 640px ____________________________________________________________________________________________________ */
/* 8.BANNER 640px ___________________________________________________________________________________________________ */

#try {
	font-size: 22px;
	line-height: 25px;
}

/* 9.NAVIGATION 640px _______________________________________________________________________________________________ */
/* 10.CONTENT 640px _________________________________________________________________________________________________ */
/* 11.MAIN 640px ____________________________________________________________________________________________________ */

.featured p {
	font-size: 16px;
	line-height: 20px;
	color: #7f7f7f;
}

/* 12.COMPLIMENTARY 640px ___________________________________________________________________________________________ */
/* 13.CONTENTINFO 640px _____________________________________________________________________________________________ */
/* 14.GLOBAL OBJECTS 640px __________________________________________________________________________________________ */
/* 15.VENDOR-SPECIFIC 640px _________________________________________________________________________________________ */
/* 16.TEMPLATE SPECIFICS 640px ______________________________________________________________________________________ */
/* 17.MODERNIZR 640px _______________________________________________________________________________________________ */

}

/*768px [iPad (portrait), iPhone 4G (landscape), Nexus one (landscape)] _____________________________________________ */

@media only screen and (min-width:768px) {

/* 1.ROOT 768px _____________________________________________________________________________________________________ */
/* 2.HEADINGS 768px _________________________________________________________________________________________________ */
/* 3.TYPOGRAPHY 768px _______________________________________________________________________________________________ */
/* 4.LINKS 768px ____________________________________________________________________________________________________ */
/* 5.FIGURES & IMAGES 768px _________________________________________________________________________________________ */
/* 6.TABLES 768px ___________________________________________________________________________________________________ */
/* 7.FORMS 768px ____________________________________________________________________________________________________ */
/* 8.BANNER 768px ___________________________________________________________________________________________________ */
/* 9.NAVIGATION 768px _______________________________________________________________________________________________ */
/* 10.CONTENT 768px _________________________________________________________________________________________________ */
/* 11.MAIN 768px ____________________________________________________________________________________________________ */

.layout-grid .featured article {
	background-color: #fff;
}

#layout a {
	color: #595959;
	text-shadow: 1px 1px 0 #fff;
}

/* 12.COMPLIMENTARY 768px ___________________________________________________________________________________________ */
/* 13.CONTENTINFO 768px _____________________________________________________________________________________________ */

footer h3 {
	font-size: 80px;
	line-height: 80px;
	font-family: minion-pro, georgia, serif;
	color: #f2f2f2;
}

/* 14.GLOBAL OBJECTS 768px __________________________________________________________________________________________ */
/* 15.VENDOR-SPECIFIC 768px _________________________________________________________________________________________ */
/* 16.TEMPLATE SPECIFICS 768px ______________________________________________________________________________________ */
/* 17.MODERNIZR 768px _______________________________________________________________________________________________ */

}

/*992px (1015px) [iPad (landscape), desktop, some laptops] __________________________________________________________ */

@media only screen and (min-width:992px) {

/* 1.ROOT 992px _____________________________________________________________________________________________________ */
/* 2.HEADINGS 992px _________________________________________________________________________________________________ */

.notfound h1 {
	font-size: 120px;
	line-height: 135px;
}

.notfound h2 {
	font-size: 500px;
}

/* 3.TYPOGRAPHY 992px _______________________________________________________________________________________________ */
/* 4.LINKS 992px ____________________________________________________________________________________________________ */
/* 5.FIGURES & IMAGES 992px _________________________________________________________________________________________ */

#m-back b, #m-back em {
	font-size: 10px;
	line-height: 14px;
	font-family: helvetica, arial, sans-serif;
}

#m-back b {
	color: #999;
}

#m-back em {
	color: #fff;
}

/* 6.TABLES 992px ___________________________________________________________________________________________________ */
/* 7.FORMS 992px ____________________________________________________________________________________________________ */
/* 8.BANNER 992px ___________________________________________________________________________________________________ */
/* 9.NAVIGATION 992px _______________________________________________________________________________________________ */

/* disable transitions */
/*
#primary a, .entry-title a,.feedback #single h1 a, .widget a, #footer a {
	-moz-transition: none;
	-webkit-transition: none;
	transition: none;
}
*/

#primary a:hover, .entry-title a:hover,.feedback #single h1 a:hover, .widget a:hover, #footer a:hover {
	color: #d00;
}

/* 10.CONTENT 992px _________________________________________________________________________________________________ */
/* 11.MAIN 992px ____________________________________________________________________________________________________ */

.layout-grid .featured article {
	background-color: #fff;
}

/* 12.COMPLIMENTARY 992px ___________________________________________________________________________________________ */
/* 13.CONTENTINFO 992px _____________________________________________________________________________________________ */
/* 14.GLOBAL OBJECTS 992px __________________________________________________________________________________________ */
/* 15.VENDOR-SPECIFIC 992px _________________________________________________________________________________________ */

.ie6 .layout-list article, .ie7 .layout-list article {
	margin: 0;
	border-bottom-color: #fff;
}

.ie6 #team li, .ie7 #team li {
	border-bottom-color: #fff;
}

/* 16.TEMPLATE SPECIFICS 992px ______________________________________________________________________________________ */
/* 17.MODERNIZR 992px _______________________________________________________________________________________________ */

}

/*1382px [MacBook Pro 15 inches (1440px) ] __________________________________________________________________________ */

@media only screen and (min-width:1382px) {

/* 1.ROOT 1382px ____________________________________________________________________________________________________ */
/* 2.HEADINGS 1382px ________________________________________________________________________________________________ */
/* 3.TYPOGRAPHY 1382px ______________________________________________________________________________________________ */
/* 4.LINKS 1382px ___________________________________________________________________________________________________ */
/* 5.FIGURES & IMAGES 1382px ________________________________________________________________________________________ */
/* 6.TABLES 1382px __________________________________________________________________________________________________ */
/* 7.FORMS 1382px ___________________________________________________________________________________________________ */
/* 8.BANNER 1382px __________________________________________________________________________________________________ */
/* 9.NAVIGATION 1382px ______________________________________________________________________________________________ */
/* 10.CONTENT 1382px ________________________________________________________________________________________________ */
/* 11.MAIN 1382px ___________________________________________________________________________________________________ */
/* 12.COMPLIMENTARY 1382px __________________________________________________________________________________________ */
/* 13.CONTENTINFO 1382px ____________________________________________________________________________________________ */
/* 14.GLOBAL OBJECTS 1382px _________________________________________________________________________________________ */
/* 15.VENDOR-SPECIFIC 1382px ________________________________________________________________________________________ */
/* 16.TEMPLATE SPECIFICS 1382px _____________________________________________________________________________________ */
/* 17.MODERNIZR 1382px ______________________________________________________________________________________________ */

}

/*2x ________________________________________________________________________________________________________________ */

@media only screen and (-webkit-min-device-pixel-ratio:2), only screen and (min-device-pixel-ratio:2) {

/* 1.ROOT 2x ________________________________________________________________________________________________________ */
/* 2.HEADINGS 2x ____________________________________________________________________________________________________ */
/* 3.TYPOGRAPHY 2x __________________________________________________________________________________________________ */
/* 4.LINKS 2x _______________________________________________________________________________________________________ */
/* 5.FIGURES & IMAGES 2x ____________________________________________________________________________________________ */
/* 6.TABLES 2x ______________________________________________________________________________________________________ */
/* 7.FORMS 2x _______________________________________________________________________________________________________ */
/* 8.BANNER 2x ______________________________________________________________________________________________________ */
/* 9.NAVIGATION 2x __________________________________________________________________________________________________ */
/* 10.CONTENT 2x ____________________________________________________________________________________________________ */
/* 11.MAIN 2x _______________________________________________________________________________________________________ */
/* 12.COMPLIMENTARY 2x ______________________________________________________________________________________________ */
/* 13.CONTENTINFO 2x ________________________________________________________________________________________________ */
/* 14.GLOBAL OBJECTS 2x  ____________________________________________________________________________________________ */
/* 15.VENDOR-SPECIFIC 2x ____________________________________________________________________________________________ */
/* 16.TEMPLATE SPECIFICS 2x _________________________________________________________________________________________ */
/* 17.MODERNIZR 2x __________________________________________________________________________________________________ */

}