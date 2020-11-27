# draggable-nested-list
A Laravel/VueJs based demo app that creates and saves a reactive **nested list**.

* Elements can be arranged/nested via drag-n-drop
* Unlimited nesting levels
* Persistent data 
* Data stored as a **linked list**

## Frameworks
* Frontend: [VueJs](https://github.com/vuejs)
* Backend: [Laravel](https://github.com/laravel/laravel)

## Dependecies
* [Vue.Draggable](https://github.com/SortableJS/Vue.Draggable)
* [Vue-Easy-Notify](https://github.com/AhmadWaleed/vue-easy-notify)

## Installation
1. Clone this repository
2. ```composer install ```
3. ```npm install ```
4. ```cp .env.example .env```
5. Edit ```.env``` file adding your database credentials
6. ```php artisan key:generate```
7. ```php artisan migrate```

## Usage
1. ```npm run dev```
2. ```php artisan serve```
3. Go to ```http://localhost:8000```

## License
* MIT
