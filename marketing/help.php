<?php
/**
 * Template Name: Help
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

get_header(); ?>

<div class="content" role="main">
	<article class="main">
		<h1><em>Help</em> &amp; support</h1>
		<section class="level">
			<h2><em>Frequently asked questions about Editorial</em></h2>
			<div class="group">
				<h3>Theme features and compatibiliy</h3>
				<ol class="questions">
					<li><a href="/">What are the “Your global feeds” on the Dashboard?</a></li>
					<li><a href="/">Can I create sub-projects in Basecamp (i.e. a project within another project)?</a></li>
					<li><a href="/">What is iCal(endar) and how do I use it with Basecamp?</a></li>
					<li><a href="/">What are the different levels of access for people in my company or people in
					other companies (clients, freelancers, contractors, etc)?</a></li>
				</ol>
			</div>
			<div class="group">
				<h3>Installation and setting-up</h3>
				<ol class="questions">
					<li><a href="/">Can I move items from one Basecamp project to another? </a></li>
					<li><a href="/">Can we export our data if we don’t want to use Basecamp anymore? What format is the export in? </a></li>
					<li><a href="/">What is a private to-do list? What does it mean to make something private?</a></li>
					<li><a href="/">Why would I want to put my logo in a white box? </a></li>
					<li><a href="/">Can I create a project template so I don’t have to start from scratch each time?</a></li>
					<li><a href="/">What is an administrator (aka admin)? Can we have multiple admins? How do I give someone admin access?</a></li>
					<li><a href="/">How do I use Basecamp to-do lists to track time?</a></li>
				</ol>
			</div>
			<div class="group">
				<h3>Troubleshooting</h3>
				<ol class="questions">
					<li><a href="/">How can we format (bold, bullets, italics, etc) our messages or comments? </a></li>
					<li><a href="/">How do I add a person or company to a project?</a></li>
					<li><a href="/">Can I email a message to Basecamp?</a></li>
					<li><a href="/">How do I delete a project?</a></li>
					<li><a href="/">What’s the difference between to-dos and milestones?</a></li>
					<li><a href="/">What is the purpose of the To-dos, Milestones, and Time tabs on the Dashboard?</a></li>
					<li><a href="/">Is Basecamp reliable, secure, and confidential? Is our data safe? Where is the data hosted?</a></li>
					<li><a href="/">Can I keep certain people/clients from seeing specific projects? How do we set permissions? </a></li>
					<li><a href="/">Can I attach a file to a to-do or milestone?</a></li>
				</ol>
			</div>
		</section>
		<section class="level">
			<?php the_post(); ?>
			<?php the_content(); ?>
		</section>
	</article>
</div>

<?php get_footer(); ?>