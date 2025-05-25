# Use Case DALAPA GAMAS (Mass Network Disruption)

This application is designed to verify work orders related to network disruptions that have been completed by technicians. The verification process is carried out via mobile devices. Currently, the application is still under development.

## Feature List

- Network Disruption Ticket List  
- Network Disruption Ticket Details  
- Network Disruption Ticket Verification (approved/rejected)  

## Planned Features (Coming Soon)

- API for Mobile Ticket Input  
- Access Control (Prepared but not yet completed)  
- CRUD for Material Assets  
- Admin Activity Log  
- Network Disruption Ticket Reports  
- Network Disruption Ticket Reports (PDF)  
- Network Disruption Ticket Reports (Excel)  


---

## Requirements

- PHP >= 7.3
- Composer
- Node.js & NPM
- MySQL or compatible database
- Git (optional)

---

## Installation Steps

### 1. Clone the Project

```bash
git clone <your-repository-url>
cd <your-project-folder>
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JS & CSS Dependencies

```bash
npm install
```

> Make sure you have `package.json` and `webpack.mix.js` already set up.

### 4. Set Up Environment

```bash
cp .env.dist .env
```

Then update `.env` with your DB and app credentials.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migration (and Seeder if any)

```bash
php artisan migrate
php artisan db:seed
```

### 7. Compile Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run prod
```

### 8. Serve the App

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

---

## Tips

- To auto-compile assets when editing:

```bash
npm run watch
```

---

## API Docs

See `/api/documentation` for API documentation. If empty just run: 

```bash
php artisan l5-swagger:generate
```

Then visit `/api/documentation`