<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```
Xuping-Afa
├─ .editorconfig
├─ .npmrc
├─ app
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ AuthController.php
│  │  │  ├─ BannerController.php
│  │  │  ├─ CategoryController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ DashboardController.php
│  │  │  ├─ ProductController.php
│  │  │  ├─ ProfileController.php
│  │  │  ├─ SettingController.php
│  │  │  ├─ UserController.php
│  │  │  └─ WebsiteController.php
│  │  └─ Middleware
│  │     └─ PreventBackHistory.php
│  ├─ Models
│  │  ├─ Banner.php
│  │  ├─ Category.php
│  │  ├─ Product.php
│  │  ├─ ProductImage.php
│  │  ├─ Setting.php
│  │  └─ User.php
│  └─ Providers
│     └─ AppServiceProvider.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ permission.php
│  ├─ queue.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ factories
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2026_08_01_163015_create_permission_tables.php
│  │  ├─ 2026_08_01_163926_create_banners_table.php
│  │  ├─ 2026_08_01_164238_create_categories_table.php
│  │  ├─ 2026_08_01_164325_create_products_table.php
│  │  ├─ 2026_08_01_164514_create_product_images_table.php
│  │  ├─ 2026_08_10_044315_add_avatar_to_users_table.php
│  │  └─ 2026_08_10_063930_create_settings_table.php
│  └─ seeders
│     ├─ DatabaseSeeder.php
│     └─ RolePermissionSeeder.php
├─ package-lock.json
├─ package.json
├─ phpunit.xml
├─ public
│  ├─ .htaccess
│  ├─ favicon.ico
│  ├─ index.php
│  └─ robots.txt
├─ README.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  └─ app.js
│  └─ views
│     ├─ auth
│     │  └─ login.blade.php
│     ├─ components
│     │  ├─ alert.blade.php
│     │  ├─ dashboard
│     │  │  ├─ navbar.blade.php
│     │  │  └─ sidebar.blade.php
│     │  └─ website
│     │     ├─ footer.blade.php
│     │     └─ navbar.blade.php
│     ├─ dashboard
│     │  ├─ categories
│     │  │  ├─ index.blade.php
│     │  │  └─ _table.blade.php
│     │  ├─ index.blade.php
│     │  ├─ products
│     │  │  ├─ create.blade.php
│     │  │  ├─ edit.blade.php
│     │  │  ├─ index.blade.php
│     │  │  ├─ show.blade.php
│     │  │  └─ _table.blade.php
│     │  ├─ profile
│     │  │  ├─ edit.blade.php
│     │  │  └─ index.blade.php
│     │  ├─ settings
│     │  │  └─ index.blade.php
│     │  └─ users
│     │     ├─ create.blade.php
│     │     ├─ edit.blade.php
│     │     ├─ index.blade.php
│     │     └─ _table.blade.php
│     ├─ layouts
│     │  ├─ app.blade.php
│     │  ├─ dashboard.blade.php
│     │  ├─ guest.blade.php
│     │  └─ website.blade.php
│     └─ website
│        ├─ about.blade.php
│        ├─ index.blade.php
│        └─ products
│           ├─ index.blade.php
│           └─ show.blade.php
├─ routes
│  ├─ console.php
│  └─ web.php
├─ storage
│  ├─ app
│  │  ├─ private
│  │  └─ public
│  │     ├─ products
│  │     │  ├─ 6D5yi7UWWcq5kH0gwLPK6jHyJ5N19Rr7EPEX6Q0z.jpg
│  │     │  ├─ 7ydXqp7jA4if0mmg8LSbJogbaY9DZDci055YHG3W.jpg
│  │     │  ├─ 9ei1YdiVJ6mLPzXc7domAYGjNsc9r7fhhb6vMSLc.jpg
│  │     │  ├─ brmXb7ukomEAnnfFwp8OSgUqYoolNcKvqOaNuCQm.jpg
│  │     │  ├─ hYMy25kovjMSMDuuQxXqdDW1LeDGoX7GKyzB6OlE.jpg
│  │     │  ├─ IIlUn7NHnbGfVZFPCJjasJ5PPzC9gAvGbQnuGn75.jpg
│  │     │  ├─ iLeRPddQwNCUwM7jgsYKldD5kSVm7miMxA20WwYQ.jpg
│  │     │  ├─ lV85MHd7j9SFg9Q3aCbPT8dExZd6uKeG38CWfZq9.jpg
│  │     │  └─ zSo1HDBKN5ETowp4jx9FsMjSsZLyzybRhLqkmxTF.jpg
│  │     └─ settings
│  │        ├─ 1YbMcbjvpDiYBcYwPeHQuH6HxUteVe0kzCKeobJ4.jpg
│  │        ├─ Tfc9a1BhhfsIqqVQyVkEcj5yy3wPE6HrNBUweZTB.jpg
│  │        ├─ TNTAZpZxEC6CcCF05NbPgmh6Nbx7HVoOkveWuAjz.jpg
│  │        └─ W06vMbGPC3RzUgQtXNwiml4Wi5cioQx1di9JXu9B.jpg
│  ├─ framework
│  │  ├─ cache
│  │  │  └─ data
│  │  ├─ sessions
│  │  ├─ testing
│  │  └─ views
│  │     ├─ 023e816352e61e07413c746602b9c10b.php
│  │     ├─ 069051a84525fbeb503ab574dacdccd0.php
│  │     ├─ 0e34a4cb0a68029c664d5406a7f44348.php
│  │     ├─ 10d0cbbe1d588ab2b28a00ffdd73e275.php
│  │     ├─ 12757eac97c9277e17aee28b2ee449a6.php
│  │     ├─ 13847e1793082a8fd158135dae1c283d.php
│  │     ├─ 148cd1f2e5d549c485b8a78c7006d39b.php
│  │     ├─ 174825e0abd5546a043c5796c82e7cc7.php
│  │     ├─ 1bcf23dff072bdd0514fd0b37c8eda86.php
│  │     ├─ 1e1fe1ce0b33fc17b42ef7044a2b8818.php
│  │     ├─ 1f3435466c3561c1ffdec2909e6bf614.php
│  │     ├─ 20734cfc1caafe2cb4bd37946d427fa3.php
│  │     ├─ 22d41f06e43049964e2573432eaae52a.php
│  │     ├─ 28bccf717fb5a1dabfd1b438a10d0e10.php
│  │     ├─ 2bcb3f403af85e1ec4eeefb0745b623e.php
│  │     ├─ 2df254ffc2cd952197dc66703d532d32.php
│  │     ├─ 333d8fc8086377492f6a3a88f5cb1334.php
│  │     ├─ 384c7fc835236a5f2fdaa169c4fd19bc.php
│  │     ├─ 38ee142ff491394604245e6aa2596ada.php
│  │     ├─ 3dbdc679a6fa0fc44961b808850ee06d.php
│  │     ├─ 40743947e71d580bb89b6135e8a052fc.php
│  │     ├─ 494d9c5138508250d74e4611e5207f62.php
│  │     ├─ 4b59c89a8ce916225a59221c0f6848a1.php
│  │     ├─ 4d4e9e223bbff658f5d4775778a61620.php
│  │     ├─ 4d827c58e83fe8f53d93303ce7ef7625.php
│  │     ├─ 55ba5c14fe2a57d2f4b44be304239182.php
│  │     ├─ 58ce4e30b410744c9bdfa6c1b4a65be7.php
│  │     ├─ 59159b598a4142a76a6eea7b234f3ba5.php
│  │     ├─ 5b39cae8dc0324999b7f6e6df8bdfd28.php
│  │     ├─ 5cd99f2f9932dd3974e558eef55c6226.php
│  │     ├─ 5cedec36f1f09f5c5f61941674c8bc2d.php
│  │     ├─ 5f591e60ff37ec52ace1f9541bc87754.php
│  │     ├─ 63a1a003a446498243eb6fec14235a7b.php
│  │     ├─ 6915dc014487db84b714d672dc517dd0.php
│  │     ├─ 6fd00d22a9a2ec454325ff964731a2ad.php
│  │     ├─ 70d89577dc6c0ba93703654f81242d12.php
│  │     ├─ 74be31e8d38981416607483b39f2a98d.php
│  │     ├─ 7a6c871b5ab1c273cd8100dcb5b9a529.php
│  │     ├─ 84f4ca3b71b8bb82c3b6b57bb8d0939e.php
│  │     ├─ 858f8ea47403a2c9b61af2c12574a9f8.php
│  │     ├─ 871fe1cb408754b306e571df27d5c42b.php
│  │     ├─ 8892e6822ad5b79889e31158d5a32184.php
│  │     ├─ 8a117b073f6621251ab62a93da91af49.php
│  │     ├─ 90d8f7cb8e942b9990897014c678a1ad.php
│  │     ├─ 932240f7ddb436daad7fff546f428e7d.php
│  │     ├─ 99a42acc6936ba77a95ff8d5186f632e.php
│  │     ├─ 9a38cb940ee959836985ed33c954b8a6.php
│  │     ├─ 9d60153211b77351f029dece1d614f2d.php
│  │     ├─ 9e022f107eb80aea9ec19a458991a83a.php
│  │     ├─ a0db362cd99bb675dfe03ebcac64688a.php
│  │     ├─ a1287b3c0bad307d08fece1e356968f7.php
│  │     ├─ a1c528731313a47332e7650583f4b190.php
│  │     ├─ a4720f842d4623ed9ac80fb952113418.php
│  │     ├─ b651811039b199d6ccd0aa08ac79df26.php
│  │     ├─ bae9845c9047533977542a8fd4676b54.php
│  │     ├─ bdfe16f2e54e1d3a3a52c2fd16f20740.php
│  │     ├─ c53fac84670c555e1eb55c0ae1e8a5f5.php
│  │     ├─ c819508fb68b2497cc7e0fa85a401da5.php
│  │     ├─ c9b8d58f1e73663ef49c1196e78843b4.php
│  │     ├─ d3f42af7a9ef9da3bebf422083f1bb0c.php
│  │     ├─ d641cea9894701f6de34db037fd4aa6a.php
│  │     ├─ d8507b28ce4539a8fdcf223b13836fe2.php
│  │     ├─ e8d19a443cd27acb884c80534e0d1059.php
│  │     ├─ e8fecdaa4f31cbe9ec0f209a0303a970.php
│  │     ├─ f677e843e28dc776e7c16a68dfcacfab.php
│  │     ├─ f68c8951289cd6db4daa35fd68d958c5.php
│  │     ├─ f84be7e243cf96392529125351f2c564.php
│  │     ├─ fc5c882916eb1755b16066d85173b3d4.php
│  │     └─ fdd93a3e70437a4ba5345c4ef139c57e.php
│  └─ logs
├─ test
│  └─ Strukture.html
├─ tests
│  ├─ Feature
│  │  └─ ExampleTest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
└─ vite.config.js

```
```
Xuping-Afa
├─ .editorconfig
├─ .npmrc
├─ app
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ AuthController.php
│  │  │  ├─ BannerController.php
│  │  │  ├─ CategoryController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ DashboardController.php
│  │  │  ├─ ProductController.php
│  │  │  ├─ ProfileController.php
│  │  │  ├─ SettingController.php
│  │  │  ├─ UserController.php
│  │  │  └─ WebsiteController.php
│  │  └─ Middleware
│  │     └─ PreventBackHistory.php
│  ├─ Models
│  │  ├─ Banner.php
│  │  ├─ Category.php
│  │  ├─ Product.php
│  │  ├─ ProductImage.php
│  │  ├─ Setting.php
│  │  └─ User.php
│  └─ Providers
│     └─ AppServiceProvider.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ permission.php
│  ├─ queue.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ factories
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2026_08_01_163015_create_permission_tables.php
│  │  ├─ 2026_08_01_163926_create_banners_table.php
│  │  ├─ 2026_08_01_164238_create_categories_table.php
│  │  ├─ 2026_08_01_164325_create_products_table.php
│  │  ├─ 2026_08_01_164514_create_product_images_table.php
│  │  ├─ 2026_08_10_044315_add_avatar_to_users_table.php
│  │  └─ 2026_08_10_063930_create_settings_table.php
│  └─ seeders
│     ├─ DatabaseSeeder.php
│     └─ RolePermissionSeeder.php
├─ package-lock.json
├─ package.json
├─ phpunit.xml
├─ public
│  ├─ .htaccess
│  ├─ favicon.ico
│  ├─ index.php
│  └─ robots.txt
├─ README.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  └─ app.js
│  └─ views
│     ├─ auth
│     │  └─ login.blade.php
│     ├─ components
│     │  ├─ alert.blade.php
│     │  ├─ dashboard
│     │  │  ├─ navbar.blade.php
│     │  │  └─ sidebar.blade.php
│     │  └─ website
│     │     ├─ footer.blade.php
│     │     └─ navbar.blade.php
│     ├─ dashboard
│     │  ├─ categories
│     │  │  ├─ index.blade.php
│     │  │  └─ _table.blade.php
│     │  ├─ index.blade.php
│     │  ├─ products
│     │  │  ├─ create.blade.php
│     │  │  ├─ edit.blade.php
│     │  │  ├─ index.blade.php
│     │  │  ├─ show.blade.php
│     │  │  └─ _table.blade.php
│     │  ├─ profile
│     │  │  ├─ edit.blade.php
│     │  │  └─ index.blade.php
│     │  ├─ settings
│     │  │  └─ index.blade.php
│     │  └─ users
│     │     ├─ create.blade.php
│     │     ├─ edit.blade.php
│     │     ├─ index.blade.php
│     │     └─ _table.blade.php
│     ├─ layouts
│     │  ├─ app.blade.php
│     │  ├─ dashboard.blade.php
│     │  ├─ guest.blade.php
│     │  └─ website.blade.php
│     └─ website
│        ├─ about.blade.php
│        ├─ index.blade.php
│        └─ products
│           ├─ index.blade.php
│           └─ show.blade.php
├─ routes
│  ├─ console.php
│  └─ web.php
├─ storage
│  ├─ app
│  │  ├─ private
│  │  └─ public
│  │     ├─ products
│  │     │  ├─ 6D5yi7UWWcq5kH0gwLPK6jHyJ5N19Rr7EPEX6Q0z.jpg
│  │     │  ├─ 7ydXqp7jA4if0mmg8LSbJogbaY9DZDci055YHG3W.jpg
│  │     │  ├─ 9ei1YdiVJ6mLPzXc7domAYGjNsc9r7fhhb6vMSLc.jpg
│  │     │  ├─ brmXb7ukomEAnnfFwp8OSgUqYoolNcKvqOaNuCQm.jpg
│  │     │  ├─ hYMy25kovjMSMDuuQxXqdDW1LeDGoX7GKyzB6OlE.jpg
│  │     │  ├─ IIlUn7NHnbGfVZFPCJjasJ5PPzC9gAvGbQnuGn75.jpg
│  │     │  ├─ iLeRPddQwNCUwM7jgsYKldD5kSVm7miMxA20WwYQ.jpg
│  │     │  ├─ lV85MHd7j9SFg9Q3aCbPT8dExZd6uKeG38CWfZq9.jpg
│  │     │  └─ zSo1HDBKN5ETowp4jx9FsMjSsZLyzybRhLqkmxTF.jpg
│  │     └─ settings
│  │        ├─ 1YbMcbjvpDiYBcYwPeHQuH6HxUteVe0kzCKeobJ4.jpg
│  │        ├─ Tfc9a1BhhfsIqqVQyVkEcj5yy3wPE6HrNBUweZTB.jpg
│  │        ├─ TNTAZpZxEC6CcCF05NbPgmh6Nbx7HVoOkveWuAjz.jpg
│  │        └─ W06vMbGPC3RzUgQtXNwiml4Wi5cioQx1di9JXu9B.jpg
│  ├─ framework
│  │  ├─ cache
│  │  │  └─ data
│  │  ├─ sessions
│  │  ├─ testing
│  │  └─ views
│  │     ├─ 023e816352e61e07413c746602b9c10b.php
│  │     ├─ 069051a84525fbeb503ab574dacdccd0.php
│  │     ├─ 0e34a4cb0a68029c664d5406a7f44348.php
│  │     ├─ 10d0cbbe1d588ab2b28a00ffdd73e275.php
│  │     ├─ 12757eac97c9277e17aee28b2ee449a6.php
│  │     ├─ 13847e1793082a8fd158135dae1c283d.php
│  │     ├─ 148cd1f2e5d549c485b8a78c7006d39b.php
│  │     ├─ 174825e0abd5546a043c5796c82e7cc7.php
│  │     ├─ 1bcf23dff072bdd0514fd0b37c8eda86.php
│  │     ├─ 1e1fe1ce0b33fc17b42ef7044a2b8818.php
│  │     ├─ 1f3435466c3561c1ffdec2909e6bf614.php
│  │     ├─ 20734cfc1caafe2cb4bd37946d427fa3.php
│  │     ├─ 22d41f06e43049964e2573432eaae52a.php
│  │     ├─ 28bccf717fb5a1dabfd1b438a10d0e10.php
│  │     ├─ 2bcb3f403af85e1ec4eeefb0745b623e.php
│  │     ├─ 2df254ffc2cd952197dc66703d532d32.php
│  │     ├─ 333d8fc8086377492f6a3a88f5cb1334.php
│  │     ├─ 384c7fc835236a5f2fdaa169c4fd19bc.php
│  │     ├─ 38ee142ff491394604245e6aa2596ada.php
│  │     ├─ 3dbdc679a6fa0fc44961b808850ee06d.php
│  │     ├─ 40743947e71d580bb89b6135e8a052fc.php
│  │     ├─ 494d9c5138508250d74e4611e5207f62.php
│  │     ├─ 4b59c89a8ce916225a59221c0f6848a1.php
│  │     ├─ 4d4e9e223bbff658f5d4775778a61620.php
│  │     ├─ 4d827c58e83fe8f53d93303ce7ef7625.php
│  │     ├─ 55ba5c14fe2a57d2f4b44be304239182.php
│  │     ├─ 58ce4e30b410744c9bdfa6c1b4a65be7.php
│  │     ├─ 59159b598a4142a76a6eea7b234f3ba5.php
│  │     ├─ 5b39cae8dc0324999b7f6e6df8bdfd28.php
│  │     ├─ 5cd99f2f9932dd3974e558eef55c6226.php
│  │     ├─ 5cedec36f1f09f5c5f61941674c8bc2d.php
│  │     ├─ 5f591e60ff37ec52ace1f9541bc87754.php
│  │     ├─ 63a1a003a446498243eb6fec14235a7b.php
│  │     ├─ 6915dc014487db84b714d672dc517dd0.php
│  │     ├─ 6fd00d22a9a2ec454325ff964731a2ad.php
│  │     ├─ 70d89577dc6c0ba93703654f81242d12.php
│  │     ├─ 74be31e8d38981416607483b39f2a98d.php
│  │     ├─ 7a6c871b5ab1c273cd8100dcb5b9a529.php
│  │     ├─ 84f4ca3b71b8bb82c3b6b57bb8d0939e.php
│  │     ├─ 858f8ea47403a2c9b61af2c12574a9f8.php
│  │     ├─ 871fe1cb408754b306e571df27d5c42b.php
│  │     ├─ 8892e6822ad5b79889e31158d5a32184.php
│  │     ├─ 8a117b073f6621251ab62a93da91af49.php
│  │     ├─ 90d8f7cb8e942b9990897014c678a1ad.php
│  │     ├─ 932240f7ddb436daad7fff546f428e7d.php
│  │     ├─ 99a42acc6936ba77a95ff8d5186f632e.php
│  │     ├─ 9a38cb940ee959836985ed33c954b8a6.php
│  │     ├─ 9d60153211b77351f029dece1d614f2d.php
│  │     ├─ 9e022f107eb80aea9ec19a458991a83a.php
│  │     ├─ a0db362cd99bb675dfe03ebcac64688a.php
│  │     ├─ a1287b3c0bad307d08fece1e356968f7.php
│  │     ├─ a1c528731313a47332e7650583f4b190.php
│  │     ├─ a4720f842d4623ed9ac80fb952113418.php
│  │     ├─ b651811039b199d6ccd0aa08ac79df26.php
│  │     ├─ bae9845c9047533977542a8fd4676b54.php
│  │     ├─ bdfe16f2e54e1d3a3a52c2fd16f20740.php
│  │     ├─ c53fac84670c555e1eb55c0ae1e8a5f5.php
│  │     ├─ c819508fb68b2497cc7e0fa85a401da5.php
│  │     ├─ c9b8d58f1e73663ef49c1196e78843b4.php
│  │     ├─ d3f42af7a9ef9da3bebf422083f1bb0c.php
│  │     ├─ d641cea9894701f6de34db037fd4aa6a.php
│  │     ├─ d8507b28ce4539a8fdcf223b13836fe2.php
│  │     ├─ e8d19a443cd27acb884c80534e0d1059.php
│  │     ├─ e8fecdaa4f31cbe9ec0f209a0303a970.php
│  │     ├─ f677e843e28dc776e7c16a68dfcacfab.php
│  │     ├─ f68c8951289cd6db4daa35fd68d958c5.php
│  │     ├─ f84be7e243cf96392529125351f2c564.php
│  │     ├─ fc5c882916eb1755b16066d85173b3d4.php
│  │     └─ fdd93a3e70437a4ba5345c4ef139c57e.php
│  └─ logs
├─ test
│  └─ Strukture.html
├─ tests
│  ├─ Feature
│  │  └─ ExampleTest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
└─ vite.config.js

```