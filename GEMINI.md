# Integrated Health Post NG (Posyandu New Generation)

Welcome to the Integrated Health Post NG project. This is a comprehensive management system for Indonesian "Posyandu" (Integrated Health Posts), designed to track and monitor the health of children, mothers, and the elderly.

## Project Overview

-   **Purpose**: Digitalization of Posyandu records, providing accurate growth monitoring (Z-scores), pregnancy tracking, and health screenings for the elderly.
-   **Main Technologies**:
    -   **Backend**: PHP 8.2+, Laravel 12.0
    -   **Frontend**: Tailwind CSS 4.0, Vite, Chart.js (for KMS charts)
    -   **Features**: Anthropometry calculations using WHO LMS method, Queue management, Excel data export.
-   **Architecture**: Standard Laravel MVC with modularized routes and dedicated service layers for complex logic (e.g., `AnthropometryService`, `ExcelExportService`).

## Core Domain Models

-   **`Children`**: Stores child profile data, linked to growth monitoring and birth records.
-   **`Mother`**: Profile data for mothers, linked to children and pregnancy records.
-   **`Elderly`**: Health monitoring for senior citizens.
-   **`GrowthMonitoring`**: Records of height/weight measurements for children.
-   **`AnthropometryStandard`**: WHO/Ministry of Health reference data for nutritional status calculations.
-   **`Queue`**: Manages the flow of patients/citizens during Posyandu service days.
-   **`Staff`**: Profiles for "Kader" (health post volunteers/workers).

## Building and Running

### Prerequisites
-   PHP 8.2 or higher
-   Composer
-   Node.js & NPM
-   SQLite or MySQL

### Installation & Setup
The project includes a convenient setup script:
```powershell
composer run setup
```
This command installs dependencies, creates the `.env` file, generates the app key, runs migrations, and builds frontend assets.

### Development Workflow
To start the development environment (includes PHP server, Vite, Queue listener, and Pail logs):
```powershell
composer run dev
```

### Testing
To run the test suite:
```powershell
composer run test
```

## Development Conventions

-   **Route Organization**: Routes are separated by domain into files within `routes/web/` and required in `routes/web.php`.
-   **Business Logic**: Complex calculations (like Z-scores) should reside in the `app/Services` directory to keep controllers lean.
-   **Styling**: This project uses **Tailwind CSS 4.0**. Prefer utility classes for styling.
-   **Data Standards**: Nutritional status calculations follow the Indonesian Ministry of Health (Permenkes) standards based on WHO growth charts.
-   **Migrations**: Always use migrations for schema changes. Reference data for anthropometry is typically seeded via `AnthropometryStandardSeeder`.

## Key Files & Directories

-   `app/Services/AnthropometryService.php`: Core logic for calculating Z-scores.
-   `app/Services/ExcelExportService.php`: Handles data exports to Excel.
-   `resources/js/kms-chart.js`: Implementation of the Kartu Menuju Sehat (KMS) visualization using Chart.js.
-   `routes/web/`: Modular route definitions.
-   `config/app_settings.php`: Custom application-wide settings management.
