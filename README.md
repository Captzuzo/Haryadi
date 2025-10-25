# Haryadi Framework 🚀

<div align="center">

# <img src="/public//assets/images/haryadi/ht.png" alt="Haryadi Framework" width="80" height="80" />

**Belajar membuat sebuah Framework. Masih banyak kekurangannya**
Sebuah CSS framework modern yang ringan dan mudah dikustomisasi untuk pengembangan web yang cepat dan responsif.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

</div>

## ✨ Fitur Utama

- 🎨 **Multiple Themes** - Support light, dark, dan custom themes
- 📱 **Fully Responsive** - Mobile-first approach
- 🏗️ **Component-Based** - Komponen siap pakai
- 🎨 **Customizable** - Mudah dikustomisasi dengan CSS variables

### Prerequisites

- PHP 8.1 atau lebih tinggi
- Composer
- Web server (Apache/Nginx)

## Commands

# 1. Command Migration

```
php haryadi buat:migration create_users_tabel
```

# 2. Command Migrate

```
php haryadi migrate
```

# 3. Command Controller

```
php haryadi buat:controller UserController
```

# 4. Command Model

```
php haryadi buat:model User
```

# 5. Command Seeder

```
php haryadi buat:Seeder UserSeeder
```

# 6. Command Serve

```
php haryadi layanan
```

# 7. Command generate key

```
php haryadi key:generate
```

# 7. Command Bantuan

```
php haryadi bantuan
```

## 🚀 Instalasi

### Metode 1: CDN

```html
<!DOCTYPE html>
<html data-theme="light">
  <head>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/gh/your-repo/haryadi-framework@latest/dist/haryadi.min.css"
    />
  </head>
</html>
```

### Metode 2: NPM

`npm install haryadi-framework`

`import 'haryadi-framework/dist/haryadi.css';`

### Metode 3: Download

Download file CSS dari releases page dan include di project Anda.

```bash
# Clone repository
git clone https://github.com/Captzuzo/Haryadi.git
```

#### Install dependencies

```
composer install
```

## 🎨 Themes

## Default Themes

`````<!-- Light Theme (Default) -->
<html data-theme="light">

<!-- Dark Theme -->
<html data-theme="dark">

<!-- Blue Theme -->
<html data-theme="blue">

<!-- Green Theme -->
<html data-theme="green">````
`````

## Custom Theme

````:root {
  --primary: #your-color;
  --bg-primary: #your-bg-color;
  /* Tambahkan custom variables lainnya */
}```
````

## 📦 Komponen Tersedia

# Button

````<button class="btn btn-primary">Primary Button</button>
<button class="btn btn-secondary">Secondary Button</button>
<button class="btn btn-success">Success Button</button>
<button class="btn btn-danger">Danger Button</button>
<button class="btn btn-outline">Outline Button</button>```
````

# Card

````
<div class="card">
  <div class="card-header">
    <h3>Card Title</h3>
  </div>
  <div class="card-body">
    <p>Card content goes here</p>
  </div>
  <div class="card-footer">
    <button class="btn btn-primary">Action</button>
  </div>
</div>    ```
````

# Form Elements

````<div class="form-group">
  <label for="email" class="form-label">Email</label>
  <input type="email" id="email" class="form-input" placeholder="Enter your email">
</div>

<div class="form-group">
  <label class="checkbox">
    <input type="checkbox">
    <span class="checkmark"></span>
    Remember me
  </label>
</div>

<div class="form-group">
  <label class="radio">
    <input type="radio" name="option">
    <span class="radiomark"></span>
    Option 1
  </label>
</div> ```
````

# Grid System

```
<div class="container">
  <div class="row">
    <div class="col-12 col-md-6 col-lg-4">
      <!-- Content -->
    </div>
    <div class="col-12 col-md-6 col-lg-4">
      <!-- Content -->
    </div>
    <div class="col-12 col-md-6 col-lg-4">
      <!-- Content -->
    </div>
  </div>
</div>
```

### Build Custom Version

```git clone https://github.com/your-repo/haryadi-framework
cd haryadi-framework
npm install
npm run build-custom
```

### 👥 Tim Pengembang

Haryadi - Initial work - Haryadi

### 🙏 Acknowledgments

Inspired by Tailwind CSS, Bootstrap, and other modern CSS frameworks

Thanks to all contributors

Icons from Heroicons

### 📞 Support

Jika Anda menemukan bug atau memiliki pertanyaan.

````
<div align="center">
Dibuat dengan ❤️ oleh Haryadi

</div> ```
````
