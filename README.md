# Developer Portfolio API

A production-ready Laravel 10 backend service for a developer's landing page. Handles contact form submissions with full validation, AI-powered analysis via Google Gemini, dual email notifications, structured file-based logging, and rate limiting.

---

## Live Demo

| Resource | URL |
|---|---|
| Landing page | http://13.140.130.66/ |
| API base | http://13.140.130.66/api |
| Swagger UI | http://13.140.130.66/docs/index.html |
| Health check | http://13.140.130.66/api/health |
| Metrics | http://13.140.130.66/api/metrics |

---

## Quick Start

### Requirements
- PHP 8.1+
- Composer
- (Optional) SMTP credentials for live email delivery

### Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# 3. Set your credentials in .env (see Configuration below)

# 4. Start the development server
php artisan serve
```

The API is now available at `http://localhost:8000/api`.
Swagger UI is at `http://localhost:8000/docs/index.html`.

---

## Configuration (`.env`)

| Variable | Description | Default |
|---|---|---|
| `GEMINI_API_KEY` | Google Gemini API key for AI analysis | *(empty — AI skipped)* |
| `GEMINI_MODEL` | Gemini model to use | `gemini-2.0-flash` |
| `AI_ENABLED` | Toggle AI on/off globally | `true` |
| `AI_TIMEOUT` | HTTP timeout for AI calls (seconds) | `30` |
| `OWNER_EMAIL` | Email address that receives contact notifications | *(empty)* |
| `OWNER_NAME` | Display name for owner email | `Portfolio Owner` |
| `MAILTRAP_API_KEY` | Mailtrap sending API key (get it from mailtrap.io) | *(empty — emails skipped)* |
| `MAIL_FROM_ADDRESS` | Sender address (must be a verified domain in Mailtrap) | `noreply@yourdomain.com` |
| `RATE_LIMIT_CONTACT` | Max contact submissions per minute per IP | `5` |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.1 |
| Framework | Laravel 10 |
| HTTP Client | GuzzleHTTP 7 (bundled with Laravel) |
| AI Provider | Google Gemini (`gemini-2.0-flash`) |
| Email | Mailtrap PHP SDK + Blade templates (Tailwind CSS via CDN) |
| Rate Limiting | Laravel RateLimiter (file cache driver) |
| Storage | File system — JSON metrics, JSONL logs |
| Documentation | OpenAPI 3.0 + Swagger UI 5 |

---

## Architecture

The project follows a strict layered architecture — HTTP concerns never bleed into business logic.

```
routes/api.php
    └── Http/Controllers/Api/        ← receive request, return response
            └── Services/            ← orchestrate business logic
                    ├── AiService          ← Gemini API + graceful fallback
                    ├── MailService        ← owner + user email notifications
                    └── ContactService     ← coordinates all of the above
                            └── Repositories/    ← file I/O only
                                    ├── MetricsRepository    → storage/app/metrics.json
                                    └── ContactLogRepository → storage/logs/contact_requests.log
```

**Patterns used:**
- **Service layer** — business logic isolated from the HTTP layer
- **Repository pattern** — all file-based data access behind a dedicated class
- **Form Request** — validation and authorization decoupled from controllers
- **Graceful degradation** — `AiService` falls back to safe defaults when the AI API is unavailable

**Why these technology choices:**
- **Laravel 10** over Slim/Lumen: the assignment scope (middleware, rate limiting, mail, validation, logging) maps naturally onto Laravel's built-in features. Using a micro-framework would mean rebuilding what Laravel provides out of the box, adding complexity with no benefit.
- **Google Gemini (`gemini-2.0-flash`)** over OpenAI: generous free tier, low latency, and reliable structured JSON output for the prompt used. No credit card required to get started, which matters for a portfolio project that reviewers need to run locally.
- **GuzzleHTTP** for the AI HTTP client: already bundled with Laravel — no extra Composer dependency needed.
- **File-based storage** over a database: the assignment explicitly permits it, and it eliminates the need for a running database during evaluation. `flock()` ensures write safety without a transaction layer. If the project scaled, swapping `MetricsRepository` and `ContactLogRepository` for DB-backed implementations would be a small, isolated change.
- **Static OpenAPI YAML + Swagger UI** over a code-generation package: the spec lives next to the code, is human-readable, and requires no annotations scattered across controllers. It is version-controlled and fully accurate without a build step.

---

## API Reference

Base URL (live): `http://13.140.130.66/api`
Base URL (local): `http://localhost:8000/api`
Full interactive docs: `http://13.140.130.66/docs/index.html`

### `POST /api/contact`

Submit a contact form.

**Request body (JSON):**
```json
{
  "name":    "Jane Smith",
  "phone":   "+998901234567",
  "email":   "jane@example.com",
  "comment": "I'd like to discuss a potential project collaboration."
}
```

**Validation rules:**

| Field | Rules |
|---|---|
| `name` | required, string, 2–255 chars |
| `phone` | required, 7–20 chars, digits and `+ ( ) - space` |
| `email` | required, valid RFC email format |
| `comment` | required, string, 10–2000 chars |

**Success `200`:**
```json
{
  "success": true,
  "message": "Your message has been received. We will get back to you shortly.",
  "data": {
    "auto_response": "Thank you for reaching out, Jane! We'd be happy to discuss a collaboration...",
    "request_type":  "partnership",
    "sentiment":     "positive"
  }
}
```

**Validation error `422`:**
```json
{
  "success": false,
  "message": "Validation failed.",
  "errors": {
    "email": ["Please provide a valid email address."]
  }
}
```

**Rate limited `429`:**
```json
{
  "success": false,
  "message": "Too many requests. Please wait before submitting another message."
}
```

---

### `GET /api/health`

Returns the operational status of all service components.

**`200` all healthy / `207` degraded:**
```json
{
  "success": true,
  "status":  "healthy",
  "version": "1.0.0",
  "checks": {
    "cache":   { "ok": true, "driver": "file" },
    "storage": { "ok": true, "writable": true },
    "mail":    { "ok": true, "mailer": "smtp" },
    "ai":      { "ok": true, "enabled": true, "provider": "gemini", "key_set": true, "model": "gemini-2.0-flash" }
  }
}
```

---

### `GET /api/metrics`

Aggregate statistics from `storage/app/metrics.json`.

```json
{
  "success": true,
  "data": {
    "total_requests": 42,
    "successful_requests": 40,
    "failed_requests": 2,
    "sentiment_breakdown":    { "positive": 25, "neutral": 12, "negative": 5 },
    "request_type_breakdown": { "general_inquiry": 15, "partnership": 12, "complaint": 3, "other": 2 },
    "ai_failures":    1,
    "email_failures": 0,
    "last_request_at": "2026-06-20T14:35:00+00:00"
  }
}
```

---

## Deployment

### Live Server

The API is deployed and running at **http://13.140.130.66/**

Server: Ubuntu VPS — Nginx + PHP 8.1 + file-based storage (no database).

---

### Option 1 — ngrok (fastest, local machine)

```bash
# Terminal 1: start the Laravel server
php artisan serve --port=8000

# Terminal 2: expose it to the internet
ngrok http 8000
```

ngrok gives you a public URL like `https://abc123.ngrok.io`.  
Update `APP_URL` in `.env` and share `https://abc123.ngrok.io/api`.

---

### Option 2 — Railway

1. Push this repo to GitHub.
2. Go to [railway.app](https://railway.app) → **New Project** → **Deploy from GitHub repo**.
3. Set the following environment variables in the Railway dashboard (copy from `.env.example`):
   - `APP_KEY` (run `php artisan key:generate --show` locally)
   - `GEMINI_API_KEY`
   - `OWNER_EMAIL`
   - `MAIL_MAILER=log` (until you wire up a real SMTP provider)
4. Railway auto-detects PHP and runs `php artisan serve`.

---

### Option 3 — Docker

```bash
# Build and run
docker build -t portfolio-api .
docker run -p 8000:8000 --env-file .env portfolio-api
```

> See `Dockerfile` in the project root.

---

## cURL Examples

```bash
# Submit contact form
curl -X POST http://13.140.130.66/api/contact \
  -H "Content-Type: application/json" \
  -d '{
    "name":    "Jane Smith",
    "phone":   "+998901234567",
    "email":   "jane@example.com",
    "comment": "I want to discuss a collaboration project with you."
  }'

# Health check
curl http://13.140.130.66/api/health

# Metrics
curl http://13.140.130.66/api/metrics
```

---

## AI Integration

**Provider:** Google Gemini (`gemini-2.0-flash`)
**Triggered on:** every `POST /api/contact`

**What the AI does in a single API call:**
1. **Sentiment analysis** — classifies the message as `positive`, `neutral`, or `negative` with a numeric score (0.0–1.0)
2. **Request classification** — categorises the inquiry into one of: `general_inquiry`, `technical_support`, `partnership`, `complaint`, `job_application`, `other`
3. **Auto-response generation** — writes a professional 2–3 sentence reply in the same language as the original comment

**Prompt used** (`app/Services/AiService.php::buildPrompt()`):

```
You are an assistant analyzing contact form submissions for a developer's portfolio website.

Analyze the submission below and return ONLY a valid JSON object with these exact keys:
- "sentiment": one of "positive", "neutral", "negative"
- "sentiment_score": float 0.0 (most negative) to 1.0 (most positive)
- "request_type": one of "general_inquiry", "technical_support", "partnership", "complaint", "job_application", "other"
- "auto_response": a professional, friendly 2-3 sentence reply to send to the user
  (write it in the same language as the comment)

Contact form submission:
Name: {name}
Email: {email}
Phone: {phone}
Comment: {comment}

Respond with ONLY the JSON object. No markdown fences, no explanations.
```

The returned JSON is validated field-by-field; any unexpected or out-of-range values are replaced with safe defaults before being stored or returned.

**Graceful fallback** — if AI is disabled, the key is missing, or the Gemini API returns an error, the service continues without interruption:
```json
{
  "sentiment":       "neutral",
  "sentiment_score": 0.5,
  "request_type":    "general_inquiry",
  "auto_response":   "Thank you for reaching out! We have received your message and will get back to you as soon as possible.",
  "ai_enabled":      false
}
```
Failures are logged to `storage/logs/contact_requests.log`; the contact submission still succeeds.

---

## Data Storage

All storage is file-based — no database required.

| File | Purpose | Format |
|---|---|---|
| `storage/app/metrics.json` | Aggregate counters (totals, sentiment, request types) | JSON with exclusive file lock on write |
| `storage/logs/contact_requests.log` | One record per contact submission (includes AI result) | JSON Lines (JSONL) |
| `storage/logs/api_requests.log` | One entry per API request (method, path, status, duration) | Monolog daily |
| `storage/logs/laravel.log` | Framework and application errors | Monolog stack |

Rate limiting uses Laravel's **file cache** driver — no Redis or Memcached needed.

---

## What Was Built With AI Assistance

This project was developed with **Claude (claude-sonnet-4-6)** as an AI pair-programmer via Claude Code.

**AI-assisted parts:**
- Architecture design (layered structure, repository pattern decisions)
- `AiService` — Gemini API integration, prompt engineering, JSON sanitisation, fallback logic
- All boilerplate (Mail classes, Form Requests, Middleware, Blade email templates)
- OpenAPI 3.0 specification (`public/docs/openapi.yaml`)
- This README

**Key prompts used:**

*Initial architecture prompt:*
> "Build a Laravel 10 backend service for a developer portfolio landing page. Requirements: POST /api/contact with validation (name, phone, email, comment), AI analysis (sentiment + classification + auto-response), dual email notifications (owner + user), file-based logging, rate limiting via env variable, GET /api/health, GET /api/metrics. Layered architecture: Controllers → Services → Repositories. No database — file storage only."

*AiService prompt engineering:*
> "Write a single Gemini API call that returns sentiment (positive/neutral/negative with 0–1 score), request_type (general_inquiry/technical_support/partnership/complaint/job_application/other), and auto_response in the same language as the comment. Return ONLY a JSON object — no markdown. Add a sanitize() method that validates each field and replaces unexpected values with safe defaults."

*Frontend prompt:*
> "Build a dark portfolio landing page using Tailwind CSS v4 and Alpine.js. Sections: navbar, hero, skills, featured projects, contact form. The contact form must POST to /api/contact and display the AI-generated auto_response on success."

**What had to be corrected manually:**
- Switched AI provider from Anthropic to Gemini (initial output used Anthropic; changed all config, service, and docs references)
- Phone validation regex loosened to accept international formats with spaces and parentheses
- Removed a duplicate `RateLimiter::for('api')` that existed in both `AppServiceProvider` and `RouteServiceProvider`
- `MetricsRepository` file-locking approach reviewed and confirmed correct (`flock` + `rewind` + `ftruncate` pattern)
