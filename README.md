# PalWeb

[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2Fbdedd1f4-c30f-45be-8ab9-5762f3582b22%3Fdate%3D1&style=for-the-badge)](https://forge.laravel.com/servers/599230/sites/2318922)

[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/Z8Z754MZT)

Learn Palestinian Arabic</b> is an educational project focusing on the documentation of the Palestinian dialects of
Spoken Arabic. The <b>Learn Palestinian Arabic</b> website includes several features of both educational & linguistic
value:

- [A learning curriculum organized into several Levels](https://abdulbaha.xyz/lessons).
- [Annotated Transcripts of real language content](https://abdulbaha.xyz/texts).
- [A documentation section discussing the dialects on a technical level](https://abdulbaha.xyz/docs).
- [A highly detailed dictionary integrated with the entire site](https://abdulbaha.xyz/dictionary).

<b>Learn Palestinian Arabic</b> is an ambitious project; nothing with its scope exists for any variety of Levantine
Arabic — possibly for any variety of Arabic. Once the project reaches more advanced stages, others are encouraged to
create clones of the site for other languages & language varieties.

## Environment Variables

To run this project, you will need to add the following environment variables to your .env file. These are used to
access remote files in the s3 bucket.

`AWS_ACCESS_KEY_ID`

`AWS_SECRET_ACCESS_KEY`

`AWS_DEFAULT_REGION`

`AWS_BUCKET`

`AWS_ENDPOINT`

`AWS_USE_PATH_STYLE_ENDPOINT`

## Run Locally

Clone the project

```bash
  git clone git@bitbucket.org:andrewfreddin/learnarabic.git
```

Go to the project directory

```bash
  cd learnarabic
```

Compose Up docker container

```bash
  docker compose up -d
```

Install dependencies

```bash
  composer install
```

```bash
  npm install
```

Migrate database fresh

```bash
  php artisan migrate:fresh
```

Source in database backup to sql docker container

```bash
  mysql -h mysql -P 3306 -u root -p
```

```sql
  mysql> use learnarabic;
```

```sql
  mysql> source arabic23may2023.sql;
```

```sql
  exit
```

Seed file table and upload any new audio files in /public/audio to s3 bucket

```bash
  php artisan audio:upload
```

Start the server

```bash
  npm run dev
```

## Documentation

Below is a detailed explanation of custom defined php artisan commands

### file:upload

```bash
  php artisan file:upload
```

`file:upload` will go through the audio directory and upload any files missing in the s3 bucket

### file:link

```bash
  php artisan file:link
```

`file:link` will go through sentences, inflections, and pronunciations, and create a database entry on the file table
for each one of them

### file:migrate

```bash
  php artisan file:migrate
```

`file:migrate` will call both `file:upload` and `file:link`

### file:upload --force and file:migrate --force

```bash
  php artisan file:upload --force
```

```bash
  php artisan file:migrate --force
```

`file:upload --force` and `file:migrate --force` will upload files to the s3 bucket in forced mode, doing so will not 
check if a file already exists in the bucket, instead it will upload the file no matter what, if a file being uploaded
already exists, then it will overwrite the existing file in the s3 bucket

## Contributing

Thank you for considering contributing to the Learn Palestinian Arabic project! There are several ways to contribute:

- Create a new term in the Dictionary.
- Leave a comment in the Documentation if you find incomplete, inaccurate or incorrect information.

