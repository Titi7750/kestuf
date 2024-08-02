## Explanation of the project

Kestuf is a web application developed as part of the validation of my Concepteur Développeur d'Applications (CDA) certification. The aim of the site is to list Lyon's unique and unusual places, offering young people the chance to discover hidden gems and experience new things.

## Features

• Interactive Map: Explore Lyon with an interactive map that lists all the unique and unusual locations.

• Advanced Filtering: Easily find places based on your preferences (type of location, distance, etc.).

• Reviews and Comments: Leave reviews and read comments from other users to get a better sense of each location.

• Favorites: Save your favorite spots for future visits.

## Installation

Follow these steps to install and configure the Symfony project :

1. Clone the project from GitHub
```
git clone https://github.com/Titi7750/kestuf.git
```
## Command Installation

1. Install the required dependencies with Composer :
```
composer install
```

2. Copy the .env.example file and rename it .env :
```
cp .env.example .env
```

3. Configure your database details in the .env file.

4. Create your database :
```
symfony console doctrine:database:create
```

5. Run database migrations :
```
symfony console doctrine:migrations:migrate
```

6. Install JavaScript dependencies with :
```
npm install
```

## Server startup :

1. To start the Symfony server :
```
symfony server:start
```
Or, to run it in the background
```
symfony serve -d
```
