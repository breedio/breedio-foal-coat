=== Breedio Foal Coat ===
Contributors: breedio, belnadris
Tags: horse, stallion, breeding, genetics, calculator
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Let visitors predict the coat of a foal from your stallions, right on your stud website, and book a breeding.

== Description ==

Mare owners want to know what their foal could look like before they book a stallion. Breedio Foal Coat puts that answer on your own website: you add your stallions once, visitors pick the coat of their mare and see the possible adult coats of the foal, with probabilities, then contact you to book.

= For the stud =

* Stallions are stored in your WordPress site, under Foal coat in the admin menu: coat, coat of the sire and dam, and genetic test results when you have them (Extension, Agouti, Cream, Champagne, Silver, Dun, Grey, Roan, Tobiano, Frame overo).
* Add the calculator with the "Foal coat calculator" block or the `[breedio_foal_coat]` shortcode.
* Link a page to a stallion: the calculator on that page opens with this stallion selected. `[breedio_foal_coat stallion="12"]` forces a stallion anywhere.
* A "Book a breeding" button under the result opens the booking page of the stallion if you set one. Otherwise it shows your phone, email and WhatsApp, with the simulation already written in the email and WhatsApp message.

= For the visitor =

* Predict mode: choose a stallion and the coat of the mare, and see every possible coat of the foal with its probability, plus what the genetic tests establish.
* Match mode (`[breedio_foal_coat mode="match"]`): choose the coat of the mare and the coat they want, and see the three stallions most likely to give it.
* Optional grandparents refine the result when the mare was never tested.
* The calculator speaks English, French, Spanish, Italian and German, following the language of your site (English for any other language).

= Breedio Foal Coat Pro =

The calculator, the stallions and the contact card are free and stay free. An optional paid add-on, sold on https://www.breedio.xyz/en/wordpress, adds a call back form with the simulation attached, breeding requests kept in WordPress and sent by email, and statistics per stallion.

== Installation ==

1. Install and activate the plugin from Plugins > Add New.
2. Under Foal coat > Settings, enter the phone, email or WhatsApp number shown under the "Book a breeding" button.
3. Under Foal coat > Add a stallion, enter the coat of each stallion, the coats of its sire and dam, and its genetic tests.
4. Add the "Foal coat calculator" block (or the `[breedio_foal_coat]` shortcode) to a page.

== Frequently Asked Questions ==

= Do my stallions leave my website? =

They stay in your WordPress database. When a page shows the calculator, the published stallions are passed to the Breedio iframe in the URL fragment (after the `#`), which browsers never send to a server. Breedio does not store them.

= Is the result reliable? =

It applies Mendelian inheritance to the known coat genes. A genetic test gives certainty on a locus; without one, the calculator uses what the coat, the sire and the dam allow, so it shows a range. It is an estimate, not a guarantee of the coat of a foal.

= Which coats are supported? =

Chestnut, bay, black, and their dilutions and patterns: cream (palomino, buckskin, smoky black, cremello, perlino), champagne, silver, dun, grey, roan, tobiano and frame overo.

= Can I show only one stallion on its page? =

Yes. Edit the page and choose the stallion in the "Foal coat calculator" panel, or use `[breedio_foal_coat stallion="ID"]`. The ID is shown in the Shortcode column of the stallion list.

= Does the calculator set cookies or collect visitor data? =

No. The iframe sets no cookie, and the free plugin collects no visitor data. A visitor who contacts you writes to you directly by phone, email or WhatsApp.

= Is there anything on my public pages that links to Breedio? =

No, unless you choose to. Under Foal coat > Settings, "Breedio credit" adds one discreet line under the calculator, "Calculator by Breedio", with a link to Breedio. It is off by default, and the calculator works the same either way. Studs that show it can be featured by Breedio (articles, social media), with a link to their website.

= Does it work with any theme? =

Yes. The calculator lives in an iframe that sizes itself to its content, so the theme styles do not change it.

== Screenshots ==

1. The calculator on a stallion page, with the possible coats of the foal and the contact card.
2. Match mode: the stallions most likely to give a grey foal with the visitor's mare.
3. A stallion in the admin: coat, coats of the sire and dam, genetic tests and booking page.
4. Contact settings shown under the "Book a breeding" button, the optional Breedio credit and the optional Pro add-on.
5. The stallion list, with the shortcode of each stallion.

== External service ==

The calculator is displayed in an iframe served by Breedio (https://embed.breedio.xyz), which runs the genetic calculation. To show it, the plugin passes your published stallions (name, coats, genetic test results, booking links) and your contact settings to that iframe in the URL fragment: the fragment stays in the visitor's browser and is never sent to the Breedio server. The iframe sets no cookie, and no visitor data is collected by the plugin.

* Terms: https://www.breedio.xyz/en/terms (French: https://www.breedio.xyz/fr/cgv)
* Privacy policy: https://www.breedio.xyz/en/privacy (French: https://www.breedio.xyz/fr/confidentialite)

== Changelog ==

= 0.1.0 =
* First version.
