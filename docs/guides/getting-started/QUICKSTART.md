# ⚡ DÉMARRAGE RAPIDE - Framework BMVC v1.0.0

**Lancez-vous avec BMVC en 5 minutes!** 🚀

---

## 🎯 Installation (2 min)

### Option 1: Composer (Recommandé)

```bash
composer require bmvc/framework
```

### Option 2: Cloner depuis GitHub

```bash
git clone https://github.com/bmvc/framework.git
cd framework
composer install
```

---

## 👋 Hello World (1 min)

### 1. Créer un Contrôleur

**Fichier:** `app/Controllers/HelloController.php`

```php
<?php
namespace App\Controllers;

class HelloController {
    public function index() {
        return "Bonjour, BMVC!";
    }
}
```

### 2. Ajouter une Route

**Fichier:** `routes/web.php`

```php
$router->get('/', 'HelloController@index');
```

### 3. Lancer le Serveur

```bash
php -S localhost:8000
```

### 4. Visit

```
http://localhost:8000
```

✅ **Done!** You'll see: `Hello, BMVC!`

---

## 🔥 Common Tasks (2 min)

### Create Blog Post Model

**File:** `app/Models/Post.php`

```php
<?php
namespace App\Models;
use Core\Modele;

class Post extends Modele {
    protected $table = 'posts';
}
```

### Create Posts Controller

**File:** `app/Controllers/PostController.php`

```php
<?php
namespace App\Controllers;
use App\Models\Post;
use Core\APIResponse;

class PostController {
    // List all posts
    public function index() {
        $posts = Post::all();
        return APIResponse::succes($posts);
    }

    // Get single post
    public function show($id) {
        $post = Post::find($id);
        return APIResponse::succes($post);
    }

    // Create post
    public function store() {
        $post = Post::create([
            'title' => $_POST['title'],
            'content' => $_POST['content']
        ]);
        return APIResponse::succes($post, 'Post created', 201);
    }
}
```

### Add Routes

**File:** `routes/web.php`

```php
// GET /posts → show all
$router->get('/posts', 'PostController@index');

// GET /posts/{id} → show one
$router->get('/posts/{id}', 'PostController@show')
    ->where('id', '[0-9]+');

// POST /posts → create
$router->post('/posts', 'PostController@store');
```

### Test

```bash
# Get all posts
curl http://localhost:8000/posts

# Get post 1
curl http://localhost:8000/posts/1

# Create post
curl -X POST http://localhost:8000/posts \
  -d "title=My Post" \
  -d "content=Post content"
```

---

## 🧪 Testing (1 min)

### Run All Tests

```bash
composer test
```

### Expected Output

```
PHPUnit 9.5.x

35 tests, 0 failures, 0 errors ✅
Code Coverage: 85%+
```

### Run Specific Tests

```bash
# Unit tests only
composer test:unit

# Functional tests only
composer test:functional

# With coverage report
composer test:coverage
```

---

## 🛠️ Configuration

### Setup Environment

**File:** `.env`

```env
APP_NAME=BMVC
APP_ENV=production
APP_DEBUG=false

DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=bmvc

LANGUAGE=fr
```

### Database Setup

```sql
-- Create database
CREATE DATABASE bmvc DEFAULT CHARSET utf8mb4;

-- Create posts table (example)
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 📚 Key Concepts

### Routing

```php
// GET route
$router->get('/home', 'HomeController@index');

// POST route
$router->post('/users', 'UserController@store');

// With parameters
$router->get('/posts/{id}', 'PostController@show');

// With constraints
$router->get('/posts/{id}', 'PostController@show')
    ->where('id', '[0-9]+');

// Named route
$router->get('/about', 'PageController@about')
    ->name('about');
```

### Models (ORM)

```php
use App\Models\Post;

// Get all
$posts = Post::all();

// Get one
$post = Post::find(1);

// Where clause
$posts = Post::where('author', 'John')->get();
$post = Post::where('id', 1)->first();

// Create
$post = Post::create([
    'title' => 'My Post',
    'content' => 'Content here'
]);

// Update
$post->update(['title' => 'New Title']);

// Delete
$post->delete();
```

### Validation

```php
use Core\Validation;

$data = [
    'email' => 'user@example.com',
    'password' => 'secret123'
];

$rules = [
    'email' => 'required|email',
    'password' => 'required|min:6'
];

Validation::validate($data, $rules);
// Returns true if valid, throws exception if not
```

### Translations (i18n)

```php
use Core\Traduction;

// Load language
Traduction::chargerLangue('fr');

// Get translation
$message = Traduction::obtenir('messages.welcome');

// With variables
$greeting = Traduction::obtenir('messages.hello',
    ['name' => 'John']
);

// Change language
Traduction::chargerLangue('en');
```

### REST API

```php
use Core\APIResponse;

// Success response
return APIResponse::succes(
    ['user' => $user],
    'User created',
    201
);

// Error response
return APIResponse::erreur('Invalid email', 400);

// 401 Unauthorized
return APIResponse::nonAuthentifie('Login required');

// 403 Forbidden
return APIResponse::nonAutorise('Not allowed');

// 404 Not Found
return APIResponse::nonTrouve('User not found');
```

---

## 🎯 Next Steps

### Learn More

1. 📖 Read [GUIDE_UTILISATION.md](GUIDE_UTILISATION.md) - Complete guide (30 min)
2. 📋 See [EXEMPLE_BLOG_COMPLET.md](EXEMPLE_BLOG_COMPLET.md) - Full app example (30 min)
3. 🧪 Learn [GUIDE_TESTS_EXECUTION.md](GUIDE_TESTS_EXECUTION.md) - Testing guide (30 min)

### Build Something

1. Create a model
2. Add routes
3. Write tests
4. Deploy!

### Get Help

1. 📚 [INDEX_DOCUMENTATION_COMPLETE.md](INDEX_DOCUMENTATION_COMPLETE.md) - Master index
2. 🔍 Search documentation
3. 📖 Check examples

---

## 💡 Pro Tips

### Use CLI Generator

```bash
# Generate Gallery module with routes
php bmvc -cmd Gallery

# Creates:
# - app/Controllers/GalleryController.php
# - app/Models/Gallery.php
# - Routes for CRUD operations
```

### Enable Debug Mode

```env
# .env
APP_DEBUG=true
```

### View All Routes

```bash
# View registered routes
php bmvc -cmd routes

# Output: List of all routes
```

### Generate API Response

```php
use Core\APIResponse;

// Return JSON automatically
return APIResponse::succes(['data' => $data]);
```

### Handle Errors

```php
try {
    $user = User::find($id);
} catch (Exception $e) {
    return APIResponse::erreur($e->getMessage(), 500);
}
```

---

## 🚀 Deploy to Production

### Quick Deploy

```bash
# 1. Install without dev dependencies
composer install --no-dev --optimize-autoloader

# 2. Configure environment
cp .env.example .env
# Edit .env with production settings

# 3. Set permissions
chmod 755 storage/
chmod 755 storage/cache/
chmod 755 storage/logs/

# 4. Done! Your app is ready
```

### Verify Deployment

```bash
# Run tests
composer test

# Should show: 35 tests, 0 failures ✅
```

---

## 📋 Cheat Sheet

### Routes

```php
$router->get($path, $controller@$method);
$router->post($path, $controller@$method);
$router->put($path, $controller@$method);
$router->delete($path, $controller@$method);
```

### Database

```php
Model::all();
Model::find($id);
Model::where($column, $value)->get();
Model::create($data);
Model::update($data);
Model::delete();
```

### Validation

```php
'email' => 'required|email'
'name' => 'required|min:3|max:100'
'age' => 'required|numeric|min:18'
'password' => 'required|min:6|confirmed'
```

### Translations

```php
Traduction::chargerLangue('fr');
Traduction::obtenir('messages.key');
Traduction::chargerLangue('en');
```

### API Response

```php
APIResponse::succes($data, $message, 200);
APIResponse::erreur($message, 400);
APIResponse::nonAuthentifie($message);
APIResponse::nonAutorise($message);
APIResponse::nonTrouve($message);
```

---

## 🆘 Troubleshooting

### "Class not found" error

```
Solution: Run composer dump-autoload
```

### Routes not working

```
Solution: Check .htaccess or nginx.conf
Verify routes/web.php syntax
```

### Database connection failed

```
Solution: Check .env DB_* settings
Verify database exists
Check MySQL running
```

### Tests failing

```
Solution: Run: composer test
Check test output for details
Review tests/bootstrap.php
```

### Permission denied

```
Solution: chmod 755 storage/
Check file ownership
```

---

## 📞 Support

### Quick Links

- 📖 [Main README](README.md)
- 📚 [Complete Documentation Index](INDEX_DOCUMENTATION_COMPLETE.md)
- 💻 [Usage Guide](GUIDE_UTILISATION.md)
- 📋 [Blog Example](EXEMPLE_BLOG_COMPLET.md)
- 🧪 [Testing Guide](GUIDE_TESTS_EXECUTION.md)
- 📦 [Deployment Guide](DEPLOYMENT_CHECKLIST.md)

### Documentation Structure

- **Getting Started:** GUIDE_RAPIDE_INDEX.md (this file is shorter)
- **Learning:** GUIDE_UTILISATION.md
- **Examples:** EXEMPLE_BLOG_COMPLET.md
- **Testing:** GUIDE_TESTS_EXECUTION.md
- **Deployment:** DEPLOYMENT_CHECKLIST.md

---

## 🎉 You're Ready!

```
✅ Framework installed
✅ Hello World running
✅ Routes working
✅ Database connected
✅ Tests passing
✅ Ready to build!
```

### Build Something Amazing! 🚀

```
Now that you know the basics:

1. Create your models
2. Write your routes
3. Build your controllers
4. Write tests
5. Deploy to production
6. Celebrate! 🎊
```

---

**BMVC Quick Start**

**Time to first route:** < 5 minutes  
**Time to production:** < 1 hour  
**Status:** ✅ Ready!

**Start building now!** 🚀
