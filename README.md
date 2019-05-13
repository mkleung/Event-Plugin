WordPress Event Plugin
======================

Please create a simple plugin that registers a custom post (ex. Events), a corresponding category (ex. Event Types), registers a metabox with a couple fields (ex. Start Date and End Date). The plugin should also create a shortcode that can be used to display a listing of all the posts of the created post type.

- [x] Create Events plugin
- [x] Register Events custom post
- [x] Enable Event category
- [x] Create Metabox for start and end date
- [x] Shortcode [list-events] that lists all events


<img src="assets/Capture.PNG" alt="drawing" width="300"/>

<img src="assets/plugin.PNG" alt="drawing" width="300"/>


### Installation Instructions

* Download the zip file into a local folder
* From your WordPress dashboard, visit plugins > Upload Plugin
* Click install
* To add an event, click on the events tab on the left and add new event
* Add start date and end date under the content box
* Go to a post and add a block and enter [list-events] shortcode

Requires at least: 4.9.8
Tested up to: 5.1.2
Gutenberg compatible: yes


### Any decisions you made that weren't straightforward

None

### Any unforeseen issues you encountered

- Under the old editor, I am able to quickly create a custom button on the editor which allows the user to quickly add shortcodes automatically. The new Gutenburg block editor makes this hard. 

### Any items left unfinished, and/or a wishlist for improvements

**Wishlists**
- [ ] Add a button into the Gutenburg editor that when clicked, will add the shortcode automatically. Currently user has to add a block and type in the shortcode manually
- [x] Save date entered into metabox as unix timestamp instead of string (to enable sorting)
- [ ] Add validation for date end (End date must be after start day)
- [ ] Add a feature where deleting the whole plugin will delete all the metavalues and posts related to the Events plugin
- [ ] Add attributes to the shortcode like sortby
