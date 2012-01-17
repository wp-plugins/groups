=== Groups ===
Contributors: itthinx
Donate link: http://www.itthinx.com/plugins/groups
Tags: access, access control, capability, capabilities, content, group, groups, member, members, membership, permission, permissions
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.0.0-beta-2

Groups provides group-based user membership management, group-based capabilities and content access control.

== Description ==

Groups provides group-based user membership management, group-based capabilities and content access control.
It integrates standard WordPress capabilities and application-specific capabilities along with an extensive API.

#### Features ####

##### User groups #####

- Supports an unlimited number of groups
- Provides a Registered group which is automatically maintained
- Users can be assigned to any group
- Users are added automatically to the Registered group

##### Groups hierarchy #####

- Supports group hierarchies with capability inheritance

##### Group capabilities #####

- Integrates standard WordPress capabilities which can be assigned to groups and users
- Supports custom capabilities: allows to define new capabilities for usage in plugins and web applications
- Users inherit capabilities of the groups they belong to
- Groups inherit capabilities of their parent groups

##### Access control #####

- Built-in access control that allows to restrict access to posts, pages and custom content types to specific groups and users only
- control access to content by groups: shortcodes allow to control who can access content on posts, show parts to members of certain groups or to those who are not members
  Shortcodes: [groups_member], [groups_non_member]
- control access to content by capabilities: show (or do not show) content to users who have certain capabilities
  Shortcodes: [groups_can], [groups_can_not]

##### Easy user interface #####

- integrates nicely with the standard WordPress Users menu
- provides an intuitive Groups menu
- conceptually clean views showing the essentials
- quick filters
- bulk-actions where needed, for example apply capabilities to groups, bulk-add users to groups, bulk-remove users from groups

##### Sensible options #####

- administrator overrides can be turned off
- optional tree view for groups can be shown only when needed
- provides its own set of permissions
- cleans up after testing with a "delete all plugin data" option 

##### Access Control #####

Groups defines some capabilities of its own. The groups_read_post capability
is used to restrict access to certain posts or pages to groups (and users)
with that capability  only.

##### Framework #####

- Solid and sound data-model with a complete API that allows developers to create group-oriented web applications and plugins

##### Multisite #####

- All features are supported independently for each blog in multisite installations

### Your opinion counts ###

#### You & Groups ####

Beta-testers and developers who need to integrate group-based features in their plugins and web applications: please use it and provide your feedback.

#### Feedback ###

Feedback is welcome!

If you need help, have problems, want to leave feedback or want to provide constructive criticism, please do so here at the [Groups plugin page](http://www.itthinx.com/plugins/groups/).

Please try to solve problems there before you rate this plugin or say it doesn't work. There goes a _lot_ of work into providing you with free quality plugins! Please appreciate that and help with your feedback. Thanks!

#### Twitter ####

[Follow me on Twitter](http://twitter.com/itthinx) for updates on this and other plugins.

### Introduction ###

#### Content Access Control ####

##### Access restrictions on posts ####

On posts an pages (and custom content types) a new meta box titles *Access restrictions* appears.
By checking *Enforce read access*, you can restrict access to the post to groups and users who have the *groups_read_post* capability.
You need to assign this capability to a group and make users members of that group to allow them to see those posts.

#### Content visibility for members and non-members ####

The [groups_member] and [groups_non_member] shortcodes are used to limit visibility of content to users who *are* members of a group or users who *are not* members of a group. Multiple comma-separated groups can be specified.

Example: Limiting visibility of enclosed content to registered users.

[groups_member group="Registered"]

Only registered users can see this text.

[/groups_member]

#### Content visibility based on capabilities ####

The [groups_can] and [groups_can_not] shortcodes limit visibility of enclosed content to those users who *have* the capability or those who *do not have* it. Multiple capabilities can be given.

Example: Showing enclosed content to users who can edit_posts (standard WordPress capability).

[groups_can capability="edit_posts"]

You can see this only if you have the edit_posts capability.

[/groups_can]

### Integration in the 'Users' menu: ###

Users - group membership is managed from the standard Users admin view.
Users are automatically added to the _Registered_ group. You can add multiple users to other groups here and also remove them.

### Sections in the 'Groups' menu: ###

#### Groups ####

Here you can:

- add groups
- remove groups
- assign capabilities to groups

#### Capabilities ####

This is where you add, remove and manage capabilities.

Capabilities can be assigned to groups and users (1). These capabilities include
the *standard WordPress capabilities* but you can also define additional
capabilities for your web-application.

Groups defines some capabilities of its own. The *groups_read_post* capability
is used to restrict access to certain posts or pages to groups (and users)
with that capability  only.

(1) Assigning capabilities to users is not integrated in the user interface yet but can be done through API calls.

#### Options ####

##### Administrator override #####

Administrator overrides can be turned off.

##### Permissions #####

For each role these permissions can be set:

* Access Groups: see information related to Groups.
* Administer Groups: complete control over everything related to Groups.
* Administer Groups plugin options: grants access to make changes on the *Groups > Options* admin section.

##### Testing the plugin #####

A convenient option is provided to delete all data that has been stored by the Groups plugin.
This option is useful if you just need to start from fresh after you have been testing the plugin.

### Shortcodes ###

#### Limit content visibility ####

These shortcodes are used to limit the visibility of the content they enclose:

- [groups_member]
- [groups_non_member]
- [groups_can]
- [groups_can_not]

See above for examples and descriptions.

#### Show group information ####

- [groups_group_info]

This shortcode takes the following attributes to show information about a group:

- _group_ : (required) the group ID or name
- _show_ : (required) what to show, accepted values are: _name_, _description_, _count_
- _single_ : (optional) used when show="count" and there is 1 member in the group
- _plural_ : (optional) used when show="count" and there is more than 1 member in the group, must contain %d to show the number of members
 
Examples:

* [groups_group_info group="Registered" show="count"]

* There [groups_group_info group="1" show="count" single="is one member" plural="are %d members"] in the [groups_group_info group="1" show="name"] group.

== Installation ==

1. Upload or extract the `groups` folder to your site's `/wp-content/plugins/` directory. You can also use the *Add new* option found in the *Plugins* menu in WordPress.  
2. Enable the plugin from the *Plugins* menu in WordPress.

== Frequently Asked Questions ==

= Where is the documentation? =

The official Groups documentation root is at the [Groups Documentation](http://www.itthinx.com/documentation/groups/) page.
The documentation is a work in progress, if you don't find anything there yet but want to know about the API, please look at the code as it provides useful documentation on all functions.

= I have a question, where do I ask? =

Please contact me at [itthinx.com](http://www.itthinx.com/).

You can also leave a comment at the [Groups plugin page](http://www.itthinx.com/plugins/groups/). 

== Screenshots ==

See also [Groups](http://www.itthinx.com/plugins/groups/)

1. Groups - this is where you add and remove groups and assign capabilities to groups.
2. Capabilities - here you get an overview of the capabilities that are defined and you can add and remove capabilities as well.
3. Users - group membership is managed from the standard Users admin view.
4. Access restrictions meta box - on pages and posts (or custom content types) you can restrict access to users who are part of a group with the *groups_read_post* capability.
5. Usage of the [groups_member] and [groups_non_member] shortcodes to limit visibility of content to users who are members of a group or users who are not members of a group. Multiple comma-separated groups can be specified.
6. Usage of the [groups_can] and [groups_can_not] shortcodes. Limits visibility of enclosed content to those users who have the capability or those who do not. Multiple capabilities can be given.
7. Options - you can adjust the plugin's settings here.

== Changelog ==

= 1.0.0-beta-3 =
* Groups wouldn't activate due to a fatal error on WP <= 3.2.1 : is_user_member_of_blog() is defined in ms-functions.php
* Added [groups_group_info] shortcode 

= 1.0.0-beta-2 =
* Increased length of capability.capability, capability.class, capability.object columns to support long capabilities.
* Improved admin CSS.

= 1.0.0-beta-1 =
* This is the first public beta release.

== Upgrade Notice ==

= 1.0.0-beta-3 =
* New shortcode to show group info & WP <= 3.2.1 compatibility fix.

= 1.0.0-beta-2 =
* Increased length of capability.capability, capability.class and capability.object columns from to 255 => you need to update your DB manually if you want that updated.
* Improved some admin CSS.

= 1.0.0-beta-1 =
* This is the first public beta release.

== API ==

The Groups plugin provides an extensive framework to handle memberships, group-based capabilities and access control.
Read more on the official [Groups](http://www.itthinx.com/plugins/groups/) page and the [Groups documentation](http://www.itthinx.com/documentation/groups/) page.
