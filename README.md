# ✈️ Flight Search Aggregator

## Overview

**Flight Search Aggregator** is a backend service built with Laravel that aggregates flight data from multiple providers, normalizes different response formats, removes duplicates, and returns a unified flight search result.

The system is designed as a backend engineering exercise focusing on:

- Multi-provider flight data aggregation
- Data normalization across different schemas
- Flight deduplication logic
- Sorting and filtering support
- Booking creation and retrieval using a stable flight identifier

This project demonstrates clean backend architecture, separation of concerns, and scalable system design principles.

---

## Tech Stack

- PHP 8+
- Laravel Framework
- MySQL

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/fahim27/flight-search-aggregator.git
```

### 2. Move into project directory

```bash
cd flight-search-aggregator
```

### 3. Install dependencies

```bash
composer install
```

### 4. Setup environment file

```bash
cp .env.example .env
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Configure database

```bash
DB_DATABASE=flight_search
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Run database migrations

```bash
php artisan migrate
```

### 8. Start development server

```bash
php artisan serve
```

### 9. Your application will be available at:

```bash
http://127.0.0.1:8000
```
