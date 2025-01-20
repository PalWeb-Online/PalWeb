# PalWeb: the Web of Palestinian Arabic

[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2Fbdedd1f4-c30f-45be-8ab9-5762f3582b22%3Fdate%3D1&style=for-the-badge)](https://forge.laravel.com/servers/599230/sites/2318922)

[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/Z8Z754MZT)

<b>PalWeb</b> is the online hub for the study & research of the Palestinian dialects of Spoken Arabic. This README will
only discuss how to get your clone of the application up & running. You can refer to ...

- [PalWeb | Wiki: About](https://palweb.app/wiki/about) for general information about the
  project;
- [PalWeb | Wiki: User Guide](https://palweb.app/wiki/user-guide) for information about the sections, content &
  features of the site;
- [PalWeb | Wiki: Contributing](https://palweb.app/wiki/contributing) for technical information about the application &
  our current tasks.

If you'd like to contribute to the project, here's how you can get set up:

## Setting Up

Fork the repository via GitHub, then clone your fork.

```bash
git clone git@github.com:username/PalWeb.git
```

Make sure to add the upstream repository.

```bash
git remote add upstream git@github.com:PalWeb-Online/PalWeb.git
```

PalWeb is a Laravel application that uses Laravel Sail to easily manage Docker containers. While the Docker container
will automatically include the PHP & MySQL versions used by the application, you need to at least have PHP & its package
manager Composer installed to run Sail. It's best to go for the latest versions on the host machine, as otherwise you
may not meet the application's PHP version requirements.

You will also need Node.js installed to run Vite, an asset bundler, to compile Sass & Vue files.

On macOS (using Homebrew):

```bash
brew install php
```

```bash
brew install composer
```

```bash
brew install node
```

Once that's done, install the application's dependencies, including Laravel Sail (via Composer).

```bash
composer install
```

```bash
npm install
```

Now, install & run the Docker container. (Make sure you have Docker Desktop on your machine.) Sail will ask which
services you want to enable; select MySQL.

```bash
php artisan sail:install
```

Once Sail is installed, you're no longer dependent on the host machine for running Laravel commands. Use Sail (`./vendor/bin/sail`,
or configure an alias) to manage the app in Docker.

Start the app with the `up` command. (Use the `-d` option to run it in the background.)

```bash
sail up -d
```

You will now need to run the database migrations & the seeders that will populate the database with dummy data. You will
be asked to create the database `palweb`; confirm.

```bash
sail artisan migrate:fresh --seed
```

Finally, run the Vite development server.

```bash
npm run dev
```

Navigate to `localhost` in your web browser & you should now see your very own local version of PalWeb!
