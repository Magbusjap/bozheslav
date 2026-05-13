#!/bin/bash
cd /var/www/bozheslav

if [ -d .git ]; then
    git pull
fi

# Обновляем Blade-шаблоны из вёрстки
cp /var/www/bozheslav/bozheslav.com/index.html /var/www/bozheslav/cms/resources/views/index.blade.php
cp /var/www/bozheslav/bozheslav.com/blog-page.html /var/www/bozheslav/cms/resources/views/blog.blade.php
cp /var/www/bozheslav/bozheslav.com/contacts-page.html /var/www/bozheslav/cms/resources/views/contacts.blade.php
cp /var/www/bozheslav/bozheslav.com/experience-page.html /var/www/bozheslav/cms/resources/views/experience.blade.php
cp /var/www/bozheslav/bozheslav.com/portfolio-page.html /var/www/bozheslav/cms/resources/views/portfolio.blade.php
cp /var/www/bozheslav/bozheslav.com/skills-page.html /var/www/bozheslav/cms/resources/views/skills.blade.php
cp /var/www/bozheslav/bozheslav.com/article-page.html /var/www/bozheslav/cms/resources/views/article.blade.php
cp /var/www/bozheslav/bozheslav.com/components/header.html /var/www/bozheslav/cms/resources/views/components/header.blade.php
cp /var/www/bozheslav/bozheslav.com/components/footer.html /var/www/bozheslav/cms/resources/views/components/footer.blade.php

cd /var/www/bozheslav/cms
php artisan view:clear
php artisan route:cache
echo "Деплой завершён!"
