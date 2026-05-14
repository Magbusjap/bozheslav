# Translation Structure

Translations are grouped by locale and by source file type.

## Blade Text

Text that is written in Blade templates belongs in `*-blade.php` files.

```text
lang/{locale}/common-blade.php
lang/{locale}/header-blade.php
lang/{locale}/footer-blade.php
lang/{locale}/index-blade.php
lang/{locale}/blog-page-blade.php
lang/{locale}/article-page-blade.php
lang/{locale}/contact-page-blade.php
lang/{locale}/page-blade.php
lang/{locale}/skills-blade.php
lang/{locale}/experience-blade.php
lang/{locale}/portfolio-blade.php
lang/{locale}/errors-blade.php
lang/{locale}/legal-page-blade.php
```

Examples:

```php
{{ __('skills-blade.title') }}
{{ __('experience-blade.actions.show_more') }}
{{ __('common-blade.edit_page') }}
```

Large old Russian-text replacements are grouped inside the `blade` key:

```php
'blade' => [
    'hero' => [
        'Русский текст из Blade' => 'Translated text',
    ],
    'entries' => [
        'Русский текст карточки' => 'Translated card text',
    ],
],
```

## JavaScript Text

Text that is inserted by JavaScript belongs in `*-js.php` files.

```text
lang/{locale}/modal-skills-js.php     Text for skills-page.js modals.
lang/{locale}/header-js.php           Text used by components.js header logic.
lang/{locale}/experience-page-js.php  Text for experience-page.js.
lang/{locale}/not-found-js.php        Text for 404.js.
lang/{locale}/cookie-banner-js.php    Text for cookie-banner.js.
lang/{locale}/skill-levels-js.php     Skill level labels used by skills-page.js.
```

The browser receives these under `window.SITE_I18N.js`.

Examples:

```js
window.SITE_I18N.js.modalSkills
window.SITE_I18N.js.header
window.SITE_I18N.js.experiencePage
window.SITE_I18N.js.notFound
window.SITE_I18N.js.cookieBanner
window.SITE_I18N.js.skillLevels
```

New page text goes to `*-blade.php`.
New JavaScript text goes to `*-js.php`.
