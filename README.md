


# 68b Cardiff Earth

## Name
Cardiff EARTH

## Description
Web application for the Cardiff EARTH project. This is a system to view and share data from CHIL deployments of glacier tracking devices.

## Installation
### Pre-requisites
- [Install Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
- [Install Node.js 22.6 or Higher](https://nodejs.org/en/download/)
- [Install PHP 8.2 or Higher](https://www.php.net/downloads)
- [Install Composer (latest)](https://getcomposer.org/download/)

### Cloning the repository
- Open Git Bash (Windows) or Terminal (MacOS/Linux)
- Change the current working directory to the location where you want the cloned directory
- Run `git clone <url>` where `<url>` is the URL of the repository. 
You can get the URL by clicking on the blue "Code" button in the top right on the repository page and copying the URL 
under `Clone with HTTPS`.
- Change directory to `68b-cardiff-earth/diffEarth`

### Setting up the project for development
- Contact team for .env variables
- Set the following in your php.ini (to find, type `php --ini` in terminal)
- remove the `;` before: `extension=pdo_mysql` and `extension=fileinfo`
- Check for any other issues in [PHP Config](home/readme/phpconfig)
- Run `composer install`
- Run `npm install`

### Running the project
- Run `composer dev` to start the PHP server and vite server

### Testing development tools
* Run in git bash `composer install`
* Then `npm install`
* Add database source, setup with comsc database user and password
* Open db console, type `create database diffearth`
* Run in terminal `php artisan migrate:fresh --seed`
* Ensure database has tables including users table
* Start the project by running `composer dev`
* Go to `localhost:8000`
* Ensure home page renders
* Ensure in `/68b-cardiff-earth/.git/hooks` there is now a `pre-commit` containing a bash script to run linters (.git folder is hidden in windows explorer by default, go to view>hidden to see it)
* Run `composer format`
* Ensure php linter and formatter runs
* Run `npm run format`
* Ensure typescript formatter runs
* Run `npm run lint`
* Ensure eslint runs

## Authors and acknowledgment
### Developers
- Fergus Lesley
- Connor Jenkins
- Cole Pearson
- Beaumont Mogridge
- Diyorbek Sanaqulov