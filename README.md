# File Storage
Laravel based system for storing files.

## Pages
### Files

![2](https://github.com/mwgiorno/storage/assets/43139928/ba1c8a7e-9325-4aef-a8d0-fbb57883a93a)

### Create File

![1](https://github.com/mwgiorno/storage/assets/43139928/67f26ccb-fadd-4c20-b3b8-916ce50f8cbb)

### Edit File

![3](https://github.com/mwgiorno/storage/assets/43139928/f2e2a05b-205e-42e7-bc70-1ac64f661da3)

### Open Files In OnlyOffice Editor

![Peek 2024-02-04 15-56](https://github.com/mwgiorno/storage/assets/43139928/2253202b-f649-4ecf-8bd7-a684356be6ca)

![Peek 2024-02-04 15-58](https://github.com/mwgiorno/storage/assets/43139928/e15a15cd-3e4e-467b-8299-248647463a3c)

![Peek 2024-02-04 15-59](https://github.com/mwgiorno/storage/assets/43139928/579422aa-5e27-4318-8d7c-32ecdb28ff61)

### Convert File Into PDF and Download

![Peek 2024-02-04 16-00](https://github.com/mwgiorno/storage/assets/43139928/3a5f0542-19be-4a2d-b4e2-cb5c35330e97)

![Peek 2024-02-04 16-01](https://github.com/mwgiorno/storage/assets/43139928/bbb73eec-d4ea-4f72-b945-ce789b0f64e4)

## Technologies
Created with:
* Laravel
* TailwindCSS
* VueJS
* MySQL/PostgreSQL
* NodeJS
* InertiaJS
* OnlyOffice

## How To Run Locally
> [!NOTE]
> The following variables need to be added to .env file.

- DOCUMENT_SERVER_URL=http://localhost:8080
- STORAGE_SERVICE_URL=http://nginx
- DOCUMENT_SERVER_CONVERT_SERVICE_URL=http://document-server/ConvertService.ashx
- JWT_SECRET=secret
- JWT_ALGORITHM=HS256

```bash
# Clone this repository
$ git clone https://github.com/mwgiorno/storage.git

# Go into the repository
$ cd storage

# Create and configure .env file (You can also rename and modify the .env.example file)
$ touch .env

# Build the service
$ docker compose build app

# Create and start the containers
$ docker compose up -d

# Start an interactive shell in the app container
$ docker compose exec app bash

# Install the php dependencies
$ composer install

# Generate the application key
$ php artisan key:generate

# Run migrations
$ php artisan migrate

# Create a symbolic link 
$ php artisan storage:link

# Start an interactive shell in the node container
$ docker compose exec node sh

# Install the node dependencies
$ npm install

# Run dev command
$ npm run dev
```
