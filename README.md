# Laravel Job Application Project

Projekt zaliczeniowy na ZTPAI, który umożliwia publikację ofert pracy oraz aplikowanie na nie. Aplikacja została zbudowana z wykorzystaniem Laravel, z dokumentacją API w Swagger, z bazą danych MySQL oraz obsługą kontenerów przez Docker. Dodatkowo dołączono phpMyAdmin do zarządzania danymi oraz Swagger UI do podglądu i testowania endpointów API.

## Spis treści
1. [Opis projektu](#1-opis-projektu)
2. [Technologie i narzędzia](#2-technologie-i-narzędzia)
3. [Wymagania wstępne](#3-wymagania-wstępne)
4. [Struktura i konfiguracja Dockera](#4-struktura-i-konfiguracja-dockera)
5. [Instalacja i uruchomienie](#5-instalacja-i-uruchomienie)
6. [Migracje bazy danych](#6-migracje-bazy-danych)
7. [Dokumentacja API (Swagger)](#7-dokumentacja-api-swagger)
8. [Struktura projektu (Laravel)](#8-struktura-projektu-laravel)
9. [Kluczowe funkcjonalności](#9-kluczowe-funkcjonalności)
10. [ERD](#10-erd)
11. [Wymagania projektu](#11-wymagania-projektu)
12. [Uzasadnienie doboru architektury](#12-uzasadnienie-doboru-architektury)

---

## 1. Opis projektu

Aplikacja pozwala użytkownikom tworzyć oferty pracy oraz na nie aplikować.

- **Organizacje** (role_id = 1) mają możliwość publikacji ofert.
- **Zwykli użytkownicy** (role_id = 2) mogą przeglądać dostępne oferty i aplikować, dołączając swoje CV w formie pliku PDF zakodowanego w Base64.

Projekt stanowi zaliczenie w ramach przedmiotu ZTPAI.

---

## 2. Technologie i narzędzia
- **Laravel** – framework PHP (wymagana wersja >= 8.1)
- **MySQL** – baza danych
- **Docker & Docker Compose** – konteneryzacja aplikacji, bazy danych, phpMyAdmin oraz Swagger UI
- **Swagger (OpenAPI 3.0)** – do dokumentacji REST API
- **phpMyAdmin** – interfejs do zarządzania bazą MySQL
- **HTML, CSS, JS** – warstwa frontendowa projektu

---

## 3. Wymagania wstępne
- **Zainstalowany Docker** oraz docker-compose w wersji wspierającej format plików Compose v2.
- **Git** do pobrania repozytorium.
- **(Opcjonalnie)** Composer i npm/yarn, jeśli chcesz budować aplikację lokalnie poza Dockerem.
- Wersja PHP: Zgodna z wymaganiami frameworka (zazwyczaj >= 8.1).

---

## 4. Struktura i konfiguracja Dockera

W pliku `docker-compose.yml` znajdują się następujące serwisy:

- **app**
    - Buduje kontener na podstawie `Dockerfile`.
    - Montuje kod źródłowy do `/var/www/html`.
    - Polega na serwisie `db`.

- **nginx**
    - Używa obrazu `nginx:latest`.
    - Odpowiada za serwowanie aplikacji na porcie 8000.
    - Montuje plik konfiguracyjny `nginx.conf` oraz katalog z kodem aplikacji.

- **db (MySQL)**
    - Używa obrazu `mysql:8.0`.
    - Zdefiniowane zmienne środowiskowe:
      ```
      MYSQL_DATABASE: laravel
      MYSQL_USER: laravel
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: rootsecret
      ```
    - Przechowuje dane w volume `db_data`.

- **swagger-ui**
    - Używa obrazu `swaggerapi/swagger-ui`.
    - Udostępnia interfejs Swaggera na porcie 8888.

- **phpmyadmin**
    - Używa obrazu `phpmyadmin/phpmyadmin`.
    - Dostępne na porcie 8080.

**Volume**:
```
volumes:
db_data:
```

---

## 5. Instalacja i uruchomienie

1. Sklonuj repozytorium (lub pobierz paczkę `.zip`):
   ```
   git clone https://github.com/bvtlk/ztpai.git
   cd ztpai
   ```

2. Skonfiguruj plik `.env` w projekcie:
   ```
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=laravel
   DB_PASSWORD=secret
   ```

3. Uruchom Docker Compose:
   ```
   docker compose up -d
   ```

4. Sprawdź działanie:
    - Laravel: [http://localhost:8000](http://localhost:8000)
    - phpMyAdmin: [http://localhost:8080](http://localhost:8080)
    - Swagger UI: [http://localhost:8888](http://localhost:8888)

---

## 6. Migracje bazy danych

Jeśli korzystasz z mechanizmu migracji Laravela, uruchom w kontenerze `app`:
```
docker-compose exec app php artisan migrate
```

---

## 7. Dokumentacja API (Swagger)

Swagger UI jest dostępny pod adresem: [http://localhost:8888](http://localhost:8888).  
Zdefiniowane endpointy:
- **POST** `/register` – rejestracja użytkownika
- **POST** `/jobs` – dodanie nowej oferty pracy
- **POST** `/jobs/filter` – filtrowanie ofert pracy
- **GET** `/jobs/{id}` – pobranie szczegółów oferty pracy
- **POST** `/apply` – aplikowanie na ofertę pracy
- **GET** `/resume/{id}` – pobranie CV

---

## 8. Struktura projektu (Laravel)

Najważniejsze katalogi i pliki:
- `app/Http/Controllers/` – kontrolery
- `app/Models/` – modele Eloquent
- `routes/` – definicje tras (`web.php`, `api.php`)
- `resources/views/` – pliki widoków Blade
- `public/` – pliki publiczne
- `database/migrations/` – migracje bazy danych
- `docker-compose.yml` – definicje usług Docker
- `Dockerfile` – konfiguracja obrazu aplikacji
- `swagger.yaml` – dokumentacja API

---

## 9. Kluczowe funkcjonalności

1. **Rejestracja / logowanie (role)**:
    - role_id = 1 – organizacja
    - role_id = 2 – zwykły użytkownik

2. **Publikowanie ofert**:
    - Formularz: tytuł, opis, firma, lokalizacja, widełki płacowe, tagi.

3. **Przeglądanie i filtrowanie ofert**:
    - Filtrowanie po lokalizacji, wynagrodzeniu, sortowaniu, wyszukiwaniu.

4. **Aplikowanie na oferty**:
    - Upload CV (Base64), zapis w bazie, podgląd w panelu organizacji.

5. **Panel organizacji**:
    - Przegląd aplikacji, wgląd w CV kandydatów (przechowywane w formie `longblob`).

## 10. ERD

<img width="828" alt="image" src="https://github.com/user-attachments/assets/2674306c-6791-4ca3-8ca8-a88ce8df8450" />

## 11. Wymagania projektu
Poniżej znajduje się lista wymagań projektu oraz status ich realizacji:

| Wymaganie                           | Status          |
|-------------------------------------|-----------------|
| **README.MD**                       | ✅ Zrealizowano  |
| **ERD**                             | ✅ Zrealizowano  |
| **Złożoność bazy danych**           | ✅ Zrealizowano  |
| **GIT**                             | ✅ Zrealizowano  |
| **Realizacja tematu**               | ✅ Zrealizowano  |
| **Wybór frameworka**                | ✅ Zrealizowano  |
| **Uzasadnienie doboru architektury**| ✅ Zrealizowano  |
| **Design**                          | ✅ Zrealizowano  |
| **Responsywność**                   | ✅ Zrealizowano  |
| **Autoryzacja użytkownika**         | ✅ Zrealizowano  |
| **ORM/JPA/ODM**                     | ✅ Zrealizowano  |
| **REST API / GraphQL**              | ✅ Zrealizowano  |
| **Użycie API przez aplikację kliencką** | ✅ Zrealizowano  |
| **Brak replikacji kodu**            | ✅ Zrealizowano  |
| **Czystość i przejrzystość kodu**   | ✅ Zrealizowano  |
| **RabbitMQ, Kafka, Redis**          | ✅ Zrealizowano  |
| **Kopia bazy danych / Migracje**    | ✅ Zrealizowano  |
| **Dokumentacja Swaggera**           | ✅ Zrealizowano  |

Legenda:  
- ✅ - Wymaganie zostało zrealizowane  
- ❌ - Wymaganie nie zostało zrealizowane

## 12. Uzasadnienie doboru architektury
Architektura projektu została zaprojektowana w oparciu o najlepsze praktyki tworzenia nowoczesnych aplikacji webowych. Poniżej przedstawiono kluczowe elementy i uzasadnienie ich wyboru:

---

#### 1. **Model MVC (Model-View-Controller)**  
Architektura MVC (Model-View-Controller) pozwala na:
- Rozdzielenie logiki biznesowej (Model), logiki kontrolera (Controller) oraz warstwy prezentacji (View).
- Łatwiejsze zarządzanie kodem i rozwój aplikacji w przyszłości.
- Czytelność i modularność kodu.

Laravel jako framework naturalnie wspiera architekturę MVC, co pozwala na sprawne budowanie aplikacji.

---

#### 2. **REST API**  
REST API zostało zaimplementowane w celu:
- Zapewnienia łatwej komunikacji między różnymi komponentami aplikacji (np. frontendem i backendem).
- Umożliwienia wykorzystania API w przyszłości przez inne aplikacje klienckie (np. mobilne, desktopowe).
- Wsparcia standardowych metod HTTP (GET, POST, PUT, DELETE), co ułatwia integrację z zewnętrznymi systemami.

---

#### 3. **ORM (Eloquent)**  
Eloquent ORM został wykorzystany do obsługi bazy danych:
- Umożliwia mapowanie obiektowo-relacyjne, co upraszcza zarządzanie danymi.
- Ułatwia wykonywanie operacji CRUD (Create, Read, Update, Delete).
- Zapewnia możliwość wykorzystania relacji między tabelami (hasOne, hasMany, belongsTo, itp.).

---

#### 4. **Docker i konteneryzacja**  
Docker został użyty w celu:
- Izolacji środowiska aplikacji, co eliminuje problemy związane z różnicami w konfiguracji na różnych urządzeniach deweloperów.
- Łatwego skalowania aplikacji w środowisku produkcyjnym.
- Automatyzacji procesu budowania i uruchamiania aplikacji.

---

#### 5. **Swagger (OpenAPI)**  
Swagger został wybrany jako narzędzie do dokumentacji API, ponieważ:
- Umożliwia automatyczne generowanie i testowanie endpointów API.
- Poprawia czytelność i dostępność dokumentacji dla deweloperów i testerów.
- Integruje się z procesami CI/CD, zapewniając aktualność specyfikacji API.

---

#### 6. **Autoryzacja i role użytkowników**  
Wdrożono system ról (organizacja i użytkownik), co:
- Zapewnia kontrolę dostępu do funkcji aplikacji.
- Pozwala na rozszerzalność w przyszłości (np. dodanie nowych ról).

---

#### 7. **Przyjęte dobre praktyki**  
- **Czystość kodu**: Każda funkcja lub klasa realizuje jedną odpowiedzialność (SRP – Single Responsibility Principle).
- **Brak replikacji kodu**: Powtarzalne elementy zostały wyodrębnione w komponenty lub helpery.
- **Skalowalność**: Aplikacja została zaprojektowana tak, aby w przyszłości umożliwiać łatwe dodawanie nowych funkcji i integracji.

---
