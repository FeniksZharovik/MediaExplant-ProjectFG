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

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```
├── app
│   ├── Console
│   ├── Exceptions
│   │   └── Handler.php
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── API
│   │   │   │   ├── SignInController.php
│   │   │   │   ├── SignUpController.php
│   │   │   │   └── VerifikasiAkunController.php
│   │   │   ├── Admin
│   │   │   │   └── AdminUserController.php
│   │   │   ├── Author
│   │   │   │   ├── BeritaController.php
│   │   │   │   ├── DraftController.php
│   │   │   │   ├── KaryaController.php
│   │   │   │   ├── ProdukController.php
│   │   │   │   └── TagController.php
│   │   │   ├── News
│   │   │   │   ├── AgendaNewsController.php
│   │   │   │   ├── DiskusiNewsController.php
│   │   │   │   ├── HomeNewsController.php
│   │   │   │   ├── OpiniNewsController.php
│   │   │   │   ├── RisetNewsController.php
│   │   │   │   ├── SiaranPersNewsController.php
│   │   │   │   └── WawancaraNewsController.php
│   │   │   ├── Profile
│   │   │   │   └── ProfileController.php
│   │   │   ├── Setting
│   │   │   │   └── SettingController.php
│   │   │   ├── UserAuth
│   │   │   │   ├── ChangePasswordController.php
│   │   │   │   ├── CreatePasswordController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── LogoutController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── VerifikasiAkunController.php
│   │   │   ├── Controller.php
│   │   │   └── HomeController.php
│   │   └── Middleware
│   │       ├── AuthSanctumMiddleware.php
│   │       ├── Authenticate.php
│   │       ├── EncryptCookies.php
│   │       ├── PreventRequestsDuringMaintenance.php
│   │       ├── RedirectIfAuthenticated.php
│   │       ├── TrimStrings.php
│   │       ├── TrustHosts.php
│   │       ├── TrustProxies.php
│   │       ├── ValidateSignature.php
│   │       └── VerifyCsrfToken.php
│   ├── Kernel.php
│   └── Models
│       ├── Admin
│       │   └── AdminUser.php
│       ├── Author
│       │   ├── Berita.php
│       │   ├── DraftMedia.php
│       │   ├── Karya.php
│       │   ├── Produk.php
│       │   └── Tag.php
│       ├── News
│       │   ├── AgendaNews.php
│       │   ├── DiskusiNews.php
│       │   ├── HomeNews.php
│       │   ├── OpiniNews.php
│       │   ├── RisetNews.php
│       │   ├── SiaranPersNews.php
│       │   └── WawancaraNews.php
│       └── User.php
├── Providers
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── BroadcastServiceProvider.php
│   ├── EventServiceProvider.php
│   └── RouteServiceProvider.php
├── bootstrap
│   └── cache
│       ├── app.php
│       └── providers.php
├── config
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── cors.php
│   ├── database.php
│   ├── filesystems.php
│   ├── hashing.php
│   ├── logging.php
│   ├── mail.php
│   ├── permission.php
│   ├── queue.php
│   ├── sanctum.php
│   ├── services.php
│   ├── session.php
│   └── view.php
├── database
│   ├── factories
│   │   └── UserFactory.php
│   ├── migrations
│   └── seeders
├── public
│   ├── assets
│   │   ├── dev-64.png
│   │   └── ukpm-explant-ic.png
│   ├── css
│   │   └── scrollbar.css
│   ├── .htaccess
│   ├── favicon.ico
│   ├── index.php
│   └── robots.txt
├── resources
│   ├── css
│   │   └── app.css
│   ├── js
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views
│       ├── authors
│       │   ├── create-product.blade.php
│       │   ├── create.blade.php
│       │   ├── creation.blade.php
│       │   ├── draft.blade.php
│       │   └── index.blade.php
│       ├── dashboard-admin
│       │   ├── components
│       │   │   ├── sidebar.blade.php
│       │   │   ├── stats.blade.php
│       │   │   ├── events.blade.php
│       │   │   └── index.blade.php
│       │   └── header-footer
│       │       ├── footer-menu
│       │       │   ├── explantContributor.blade.php
│       │       │   ├── kode-etik.blade.php
│       │       │   ├── pusatBantuan.blade.php
│       │       │   ├── strukturOrganisasi.blade.php
│       │       │   └── tentangKami.blade.php
│       │       ├── footer.blade.php
│       │       └── header.blade.php
│       ├── kategori
│       │   ├── agenda.blade.php
│       │   ├── buletin.blade.php
│       │   ├── desain-grafis.blade.php
│       │   ├── diskusi.blade.php
│       │   ├── fotografi.blade.php
│       │   ├── majalah.blade.php
│       │   ├── news-detail.blade.php
│       │   ├── opini.blade.php
│       │   ├── pantun.blade.php
│       │   ├── puisi.blade.php
│       │   ├── riset.blade.php
│       │   ├── siaranPers.blade.php
│       │   ├── syair.blade.php
│       │   └── wawancara.blade.php
│       ├── layouts
│       │   ├── admin-layouts.blade.php
│       │   ├── app.blade.php
│       │   └── auth-layout.blade.php
│       ├── profile
│       │   └── mainProfile.blade.php
│       ├── settings
│       │   └── umum.blade.php
│       └── user-auth
│           ├── 404.blade.php
│           ├── home.blade.php
│           └── welcome.blade.php
├── routes
│   ├── api.php
│   ├── console.php
│   └── web.php
├── storage
│   ├── app
│   │   └── .gitignore
│   ├── framework
│   │   ├── cache
│   │   ├── data
│   │   ├── .gitignore
│   │   ├── sessions
│   │   │   └── .gitignore
│   │   ├── testing
│   │   │   └── .gitignore
│   │   └── views
│   │       └── .gitignore
│   └── logs
│       └── .gitignore
├── tests
│   ├── Feature
│   └── Unit
│       └── TestCase.php
├── .editorconfig
├── .env.example
├── .gitattributes
├── .gitignore
├── LICENSE
├── README.md
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
├── postcss.config.js
├── tailwind.config.js
└── vite.config.js
```
