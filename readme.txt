=== Advertise World ===
Contributors: devisd
Tags: ads, advertising, ad blocker, banner advertising, banner ads, display advertising, display ads, image advertising, image ads, responsive ads, responsive advertising
Requires at least: 3.1
Tested up to: 4.7.1
Stable tag: 1.3.7
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Advertise World allows website owners to generate ad revenue by displaying ads that are immune to Ad Blockers.

== Description ==

Beat the Ad Blockers using the World's first unblockable image banner display advertising network.

After working through the setup steps found below, this plugin will allow you to create ad spaces that will act as containers for ads served through the Advertise World platform. These ad space containers can be placed in a variety of locations based on your chosen WordPress theme.

= Setup Steps =
Step 1 - Sign up as a publisher through the Advertise World Publisher Portal by navigating to the Publisher Sign Up link on the Advertise World website (www.advertiseworld.com).

Step 2 - Create your preferences relating to the ads you would like to receive by generating an ad space and targeting profile through the portal.

Step 3 - Install and Activate the WordPress Advertise World plugin.

Step 4 - Input the email address used to sign up to the Advertise World Publisher Portal into the account settings section of the Advertise World WordPress plugin. This will link the plugin to your Advertise World Publisher account.

Step 5 - Create a new Ad Space and link it to a previously created space generated using the Advertise World Publisher Portal.

Step 6 - Position the ad space using the plugin options provided.

If all six steps are followed correctly. Ads will begin to appear in the designated Ad Spaces created using the plugin.

Enjoy Advertise World’s unblockable ads and beat the Ad Blockers once and for all!

== Installation ==

Install Advertise World via the plugin directory, or by uploading the files manually to your server. After activating Advertise World, link the plugin to your Advertise World Publisher Portal account. This can be done using the email field found on the Advertise World plugin Account Settings screen.

== Frequently Asked Questions ==

= 1. How do ad blockers work and why are they important? =

Ad blockers usually come in the form of web browser plugins that get installed on user machines to prevent ads from displaying while a person browses the internet. They do this by either preventing ads from loading from a list of known ad servers worldwide, or they match patterns that are associated with serving ads such as image file names or banner dimensions.

The problem with ad blocking is that many website owners depend on advertising income to make a living, and advertisers cannot sell their products and services if they cannot present them to potential customers. So it might be preferable for a website viewer to block ads for a cleaner website experience, however, in effect, they are actually stealing the content without compensating the author of the content being consumed.

= 2. How many ads can I put on a page? =

There is no limit on how many ads you can place on a page, however, it is good practice to limit the number of ads to no more than three per page. Too many ads detract from the actual content of the page, and of course, the more images that are displayed on your web pages, whether they are ads or not, will increase the overall page weight.

Increased page weights mean slower download times and longer page rendering times. Web pages that are slow to load can be frustrating for website visitors and result in higher abandonment rates. So it is never a good idea to overload your pages with ads.

Some good practices to consider are to try to keep your ads above the fold (visible to the viewer on initial page load without requiring them to scroll), and using larger, preferably horizontal ads where possible, as they generally perform better when it comes to exposure and click through rates.

= 3. Can I create one ad space and use it throughout my site? =

When creating ad spaces for your ads using the Advertise World Publisher Portal, you may be tempted to create one ad space and use this space in several, if not all locations, throughout your site. This may seem like a good idea at first, but becomes a problem when you wish to examine the performance and effectiveness of each ad space.

It is a far better idea to create many ad spaces for the different areas of your pages. For example, you may wish to create an ad space for your website sidebar, or maybe even go a step further and create an ad space for your home page sidebar. That way, when you measure the effectiveness of the ad, you can actually tell how well the home page sidebar performs against a sidebar ad placed on another page of your site.

Of course, depending on how your website has been built, it may not be possible to have such granular control over the placement of your ad spaces. If you wish to use one ad space in an area that is shared site-wide, such as a website header or footer, then you a free to do so, but it will be difficult to determine which pages are generating most of your ad clicks/leads.

= 4. How do I get paid for displaying ads? How do I make payment for displaying ads? =

As a Publisher you might be wondering how you will get paid for displaying ads on your site. During the sign up process you will be required to enter your international bank details. We will then use these details to pay you on a monthly basis depending on the amount of ads you have displayed and the number of clicks received on these ads.

= 5. Can I be both an advertiser and a publisher? =

We have many clients that wish to act as both an advertiser and a publisher. Picture this scenario, you have a product that you would like to market to the world. The obvious choice is to set up an advertiser account with Advertise World. However, you are a little strapped for cash and don't have much of an advertising budget. Why not monetise your website traffic by signing up as a publisher first. The money that you make through displaying ads on your site can then be used to market your own products/services as an advertiser.

== Changelog ==

= 1.0 =
* Initial Release

= 1.0.1 =
* Fixed issue with the way we were curling the ad server and generating impression count statistics.

= 1.0.2 =
* Changed minimum Wordpress compatibility to 3.1
* Minor fixes to remove PHP warnings related to earlier version of Wordpress.

= 1.0.3 =
* Added the option for user to choose Shortest / Medium / Tallest ad (when choosing a responsive ad).

= 1.0.4 =
* Added support for Geo Location targeting, Advertisers can target by Long / Lat / Radius

= 1.0.5 =
* Updated the method for emailing support to be more reliable if the Wordpress install isn't setup for email correctly.
* System automatically notifies Advertise World support if there is any issue trying to get the browser's IP and User-Agent.
* Any support or automated email will now include Wordpress and Plugin version information.

= 1.0.6 =
* Security Update

= 1.1.0 =
* Updated to use inline javascript and css to increase the reliability of the plugin for a small number of users.

= 1.2.0 =
* Security Update

= 1.2.1 =
* Fix plugin so the php-curl is not a requirement and the system will work without it.

= 1.2.2 =
* Turned of curl option CURLOPT_FOLLOWLOCATION as it caused problems with safe_mode

= 1.2.3 =
* Updated stable tag

= 1.2.4 =
* Update the code to retrieve the browsers IP

= 1.2.5 =
* Stability update

= 1.2.6 =
* Introduced logging of information when server has settings that create minor ad serving issues

= 1.2.7 =
* User-Agent is required for ad impressions. This is to avoid crawlers and stuff.

= 1.2.8 =
* Updated css to that it doesn't impact other plugins

= 1.3.0 =
* Retrieving XHTML ads

= 1.3.1 =
* Reduced the number of available ad sizes

= 1.3.5 =
* Updates to help our rtb fallback ad use the correct size ad
* Allows users to choose fixed size ads.

= 1.3.7 =
* Forces the use of Fixed Size ads. If responsive was chosen before this update, then 300x250 ads will be used until
another fixed size is chosen.

== Upgrade Notice ==

= 1.0 =
Initial Release

= 1.0.1 =
Fixes impression count statistics. Upgrade to ensure correct impression count.

= 1.0.2 =
Upgrade if you are using an earlier version of Wordpress (< 4.0) and you are experiencing issues with the interface.

= 1.0.3 =
Upgrade if to need to display taller ads in your ad space(s).

= 1.0.4 =
Upgrading of you want to support Geo Location targeting.

= 1.0.5 =
Recommend this update for all users.

= 1.0.6 =
This update is recommended for security.

= 1.1.0 =
Recommend this update for user experiencing issues with the images not loading properly.

= 1.2.0 =
Recommended for all users.

= 1.2.1 =
Only recommended for users who don't have php-curl module installed. This doesn't impact any current users.

= 1.2.2 =
Recommended for servers that have Safe Mode turned on in their PHP.ini

= 1.2.3 =
Updated stable tag

= 1.2.4 =
Recommended to servers with certain proxy setups

= 1.2.5 =
Recommended for all web servers

= 1.2.6 =
Recommended for new publishers

= 1.2.7 =
Recommended for everyone

= 1.2.8 =
Recommended for everyone

= 1.3.0 =
This release will be a must for everyone. Previous versions will be discontinued.

= 1.3.1 =
Recommended for everyone

= 1.3.5 =
Recommended for anyone wanting to use fixed size ads.

= 1.3.7 =
Recommended for everyone to use fixed size ads now.

== Screenshots ==
