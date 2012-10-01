# Cookie Law

Since June 2012, the law of the Netherlands states that if your site sets third-party or tracking-cookies (like Google
Analytics for example), you have to explicit ask the user to opt-in to set this cookie.

This extension tries to make the process of opting-in a bit simpler.

## Installation

Just enable the extension and enter the specific fields in the preferences page (or choose one of the existing presets).

Then, in the pages where you want to give to opportunity to opt-in (usually all your pages), place the following XSL
right before the `</body>`-tag. Make sure you've got the [HTML Manipulation utility](http://getsymphony.com/download/xslt-utilities/view/20035/)
included on these pages:

    <xsl:apply-templates select="data/cookie-law/html/*" mode="html" />

If you've choosen to use the default styling, add this before your `</head>`-tag:

    <style type="text/css">
        <xsl:value-of select="data/cookie-law/styling"/>
    </style>

That's it! You're all set and done now!

## What does it do?

The extensions sets a cookie called `cookie_accept`, which value can be `1` or `0`. When the value is `1`, the
Javascript that is entered in the preferences page is executed. This is usually the Google Analytics tracking code, but
it could also be used for your own custom Javascript. When the value is `0`, this is obviously not done.

A nice little detail to 'push' people into accepting the cookie: when the cookie is declined, the `cookie_accept`-cookie
will expire at the end of the browsing session, meaning that the next time the user comes to your website, they are
confronted with the same question. However... when the user accepts the cookie, it is valid until December 31st, 2999.

## Localisation

The texts for the cookie message are stored in the `lang`-folder. So if you want to change these texts, you can change
the text for the corresponding language.