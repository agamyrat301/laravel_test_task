# Developer Portfolio API

A production-ready Laravel 10 backend service for a developer's landing page. Handles contact form submissions with full validation, AI-powered analysis via Anthropic Claude, dual email notifications, structured file-based logging, and rate limiting.

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
| `ANTHROPIC_API_KEY` | Anthropic API key for AI analysis | *(empty — AI skipped)* |
| `ANTHROPIC_MODEL` | Claude model to use | `claude-haiku-4-5-20251001` |
| `AI_ENABLED` | Toggle AI on/off globally | `true` |
| `AI_TIMEOUT` | HTTP timeout for AI calls (seconds) | `30` |
| `OWNER_EMAIL` | Email address that receives contact notifications | *(empty)* |
| `OWNER_NAME` | Display name for owner email | `Portfolio Owner` |
| `MAIL_MAILER` | Mail transport (`smtp`, `log`, `array`) | `smtp` |
| `MAIL_HOST` | SMTP host | `mailpit` |
| `MAIL_PORT` | SMTP port | `1025` |
| `RATE_LIMIT_CONTACT` | Max contact submissions per minute per IP | `5` |

> **Local development tip:** set `MAIL_MAILER=log` to write emails to `storage/logs/laravel.log` instead of sending them.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.1 |
| Framework | Laravel 10 |
| HTTP Client | GuzzleHTTP 7 (bundled with Laravel) |
| AI Provider | Anthropic Claude (`claude-haiku-4-5-20251001`) |
| Email | Laravel Mailer + Blade templates (Tailwind CSS via CDN) |
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
                    ├── AiService          ← Anthropic API + graceful fallback
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

---

## API Reference

Base URL: `http://localhost:8000/api`
Full interactive docs: `http://localhost:8000/docs/index.html`

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
    "ai":      { "ok": true, "enabled": true, "key_set": true, "model": "claude-haiku-4-5-20251001" }
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
   - `ANTHROPIC_API_KEY`
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
curl -X POST http://localhost:8000/api/contact \
  -H "Content-Type: application/json" \
  -d '{
    "name":    "Jane Smith",
    "phone":   "+998901234567",
    "email":   "jane@example.com",
    "comment": "I want to discuss a collaboration project with you."
  }'

# Health check
curl http://localhost:8000/api/health

# Metrics
curl http://localhost:8000/api/metrics
```

---

## AI Integration

**Provider:** Anthropic Claude (`claude-haiku-4-5-20251001`)
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

**Graceful fallback** — if AI is disabled, the key is missing, or the Anthropic API returns an error, the service continues without interruption:
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
- `AiService` — Anthropic API integration, prompt engineering, JSON sanitisation, fallback logic
- All boilerplate (Mail classes, Form Requests, Middleware, Blade email templates)
- OpenAPI 3.0 specification (`public/docs/openapi.yaml`)
- This README

**Manual decisions and overrides:**
- Tailwind CSS specified for email templates (replaced initial plain-CSS output)
- Phone validation regex adjusted for international number formats
- File-locking strategy in `MetricsRepository` reviewed and confirmed

**Prompts used** were conversational, iterating from the full task brief. No code was copied from external sources; everything was generated and reviewed inline.
