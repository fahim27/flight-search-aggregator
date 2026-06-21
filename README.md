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

## Architecture Overview

This project is designed using a **clean Service–Repository architecture** with a focus on separation of concerns, scalability, and maintainability. The system is divided into clear layers that handle specific responsibilities.

## Core Components

### 1. Controller Layer

The controller is responsible for:

- Handling incoming HTTP requests
- Validating request payloads
- Delegating business logic to services
- Returning formatted API responses

Controllers are intentionally kept **thin** to maintain clean separation of concerns.

### 2. Service Layer

The service layer contains the core business logic of the system.

#### Responsibilities:

- Aggregating flight data from multiple providers
- Normalizing different provider response formats
- Removing duplicate flights
- Applying filtering and sorting logic
- Handling booking validation and rules

#### Key Services:

- `Flight/FlightService`
- `Flight/FlightProviderService`
- `Flight/Providers/ProviderServiceA`
- `Flight/Providers/ProviderServiceB`
- `Flight/Providers/ProviderServiceC`
- `Booking/BookingService`

This layer acts as the **brain of the application**.

### 3. Repository Layer

The repository layer is responsible for:

- Abstracting database operations
- Handling persistence logic
- Providing a clean interface between services and data sources

#### Example:

- `BookingRepository`

This ensures that database logic is not tightly coupled with business logic.

### 4. Caching Layer

Laravel Cache is used to:

- Store search results temporarily (30 minutes)
- Validate booking requests against previous search results
- Improve performance and reduce repeated provider calls

#### Cache Key Structure

The application uses Laravel Cache to temporarily store flight search results for booking validation.

#### Cache Description

- `search_id` → A unique UUID generated for each flight search request
- Used to isolate each user's search session
- Ensures booking validation is performed against the correct search results

#### Example: `flight_search_550e8400-e29b-41d4-a716-446655440000`

#### Cache Expiration

- TTL: **30 minutes**
- After expiration, the cache is automatically invalidated
- Bookings using expired search sessions will be rejected

---

## Installation

Follow the steps below to set up and run the project locally in a development environment.

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

---

## API Documentation

This section provides details of all available API endpoints, including request parameters and usage guidelines for integrating with the Flight Search Aggregator service.

---

## Search Flights

This endpoint searches flights from multiple providers, merges results, removes duplicates, and returns a unified response. It also supports filtering and sorting.

#### Endpoint

```http
GET /api/flights/search
```

| Parameter  | Type    | Required | Description                                                           |
| ---------- | ------- | -------- | --------------------------------------------------------------------- |
| from       | string  | Yes      | Origin airport code (e.g. DAC)                                        |
| to         | string  | Yes      | Destination airport code (e.g. DXB)                                   |
| date       | string  | Yes      | Travel date (YYYY-MM-DD)                                              |
| passengers | integer | No       | Number of passengers (for context only)                               |
| sort       | string  | No       | Sorting option (price_asc, price_desc, departure_asc, departure_desc) |
| carrier    | string  | No       | Filter by airline code                                                |
| stops      | integer | No       | Filter by number of stops                                             |
| min_price  | number  | No       | Minimum price filter                                                  |
| max_price  | number  | No       | Maximum price filter                                                  |

#### Notes

- Flights are aggregated from multiple providers
- Duplicate flights are removed using flight_id
- Results are cached for 10 minutes
- passengers is used for context only (no availability validation)

## Create Booking

This endpoint creates a flight booking using a selected flight from search results. It validates the flight against the cached search session and stores passenger details.

#### Endpoint

```http
POST /api/flights/bookings
```

| Parameter       | Type   | Required | Description                                                |
| --------------- | ------ | -------- | ---------------------------------------------------------- |
| flight_id       | string | Yes      | Selected flight identifier from search results             |
| search_id       | string | Yes      | Unique search session ID used to validate flight selection |
| passenger_name  | string | Yes      | Name of the passenger                                      |
| passenger_email | string | Yes      | Email address of the passenger                             |
| passenger_phone | string | No       | Phone number of the passenger                              |

#### Notes

- `flight_id` must exist in the cached search results
- `search_id` is used to validate booking integrity
- Booking will fail if search session is expired or invalid
- Each booking generates a unique `reference ID` internally

## Get Booking by Reference

This endpoint retrieves a previously created booking using its unique reference ID.

#### Endpoint

```http
GET /api/flights/bookings/{reference}
```

| Parameter | Type   | Required | Description                                    |
| --------- | ------ | -------- | ---------------------------------------------- |
| reference | string | Yes      | Unique booking reference ID (e.g. BK-8F3K2L9P) |

#### Notes

- Returns booking details if reference is valid
- Returns error if booking not found
- Reference is generated automatically during booking creation

---

## Conclusion

This Flight Search Aggregator demonstrates a scalable backend architecture designed to handle multi-provider data aggregation, normalization, and booking workflows in a clean and maintainable way.

The system is built with a strong focus on:

- Separation of concerns using Service and Repository layers
- Extensible provider-based architecture for easy integration of new flight sources
- Efficient data processing with deduplication, filtering, and sorting
- Stateless search design with temporary caching for booking validation
- Clean and consistent API structure

Overall, the project reflects real-world backend engineering principles and can be extended further into a production-grade flight booking system with minimal architectural changes.
