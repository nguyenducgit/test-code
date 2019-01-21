[bd] Widget Framework
People love widgets

[FEATURES]
1. Add any widgets to any pages of XenForo or other add-ons.
2. Create new pages consists of widgets (built-in widgets or widgets from other add-ons)
3. Set Widget Page as Index

[INSTALLATION]
1. Upload files and directories inside `upload` to XenForo's root directory. Select "Merge" for everything if you are asked
2. Import the .xml file using XenForo add-on importer
3. Enjoy
*. [For Devs] Enable debug mode: add `$config['wfDebug'] = true;` to config.php

[WIDGETS]
Currently, the add-on is packed with the following widgets:
- Clear Sidebar
- Birthday (since 1.1)
- Feed Reader (since 2.1)
- Forum Statistics
- Share This Page
- Thread with Poll (since 1.0)
- Threads. 4 modes: new threads, new replies, most viewed, most replied, most liked, polls
- User Recent Status (since 1.0.9)
- Users. 3 order by: register_date, message_count, resource_count
- User Online Now, User Online Now (Staff)
- Users: Find (since 1.5)
- Visitor Panel (since 1.0.9)
- XFRM: Resources. 4 modes: new, updated, highest rating, most downloaded
- HTML, HTML (without wrapper)
- PHP Callback (since 1.0.10)
- Template, Template (without wrapper) (since 1.2)

[PRE-CONFIGURED WIDGETS]
This add-on comes with some pre-configured widgets. You can optionally choose to import them by going to AdminCP > Appearance > Widget Framework > Import Widgets. They are used to support other add-ons (display other add-ons sidebar block as a widget within the framework)
1. [xml-to-import/TaigaChat.xml] Add a TaigaChat widget to the forum_list (the index page) of your forums. You must have TaigaChat add-on installed for this to work. Also, due to a problem with the TaigaChat add-on itself, you must NOT have 2 instances of the chat box in a page.

[WHY A FRAMEWORK?]
I have seen many people releasing sidebar related add-on on xenforo.com. The idea is the same: adding something to a page's sidebar. With the hook, developer can easily modify XenForo's sidebar, no big deal. The problem is each developer prefers a different way to do the job and forums administrator will have difficulties to manage all the stuff on their sidebar. With this framework, that problem is gone. Administrator can manage all the widgets in one place, temporary disable one widget is simple as a click. The order can also be changed (well, not support drag and drop yet, sorry for that).

Also, anyone who wants to develop widget to use with the framework can do it in just a few steps. The framework will take care of everything and developers only need to focus on getting the business done. There are 2 things to do: listen to code event "widget_framework_ready", push your class name in to the array to register your widget renderer. After doing so, you can start coding your renderer by extending the base class "WidgetFramework_WidgetRenderer"
[php]class MyWidget_WidgetA extends WidgetFramework_WidgetRenderer {
}[/php]
There are some abstract methods you will have to implement to make it work, just dig into the code and figure it out. If you got lost, feel free to contact me: pony@xfrocks.com

One last thing, the framework really care about performance. In perfect conditions, no matter how many widgets you have, the framework only execute 1 additional query. I tried to cache as much as possible. The widget list is stored in XenForo's simple cache system while the rendered widgets are stored with  Data Registry. Each renderer doesn't have to handle caching, the framework does that for all widgets. All templates are preloaded with the main page template also. Headache no more ;)

XenForo rocks!