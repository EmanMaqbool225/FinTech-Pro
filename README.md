# FinTech Pro — Billing & Cash Flow Management System

A full-stack web application built with PHP and MySQL for managing multi-item invoicing, client accounts, debt tracking, and online payment processing.

Production Instance: https://eman-pro.alwaysdata.net/

## Architecture & Core Modules

- Authentication & Access Control: Dual-portal authentication supporting standard credential login and Google OAuth 2.0 (`google_callback.php`), separating administrative operations (`dashboard.php`) from client views (`client_dashboard.php`).
- Invoicing Engine: Multi-item invoice generation (`create_invoice.php`), dynamic status updates (paid, pending, overdue), and printable invoice layouts (`print_invoice.php`).
- Payment Gateway Integration: Direct transaction processing and webhook/callback handling via PayPal API.
- Financial Tracking & Audit: Real-time cash flow aggregation (`finances.php`) and granular system activity logging.
- Client Directory: Relational customer records (`clients.php`, `add_client.php`) mapped to historical billing statements.

## Tech Stack

- Backend: PHP 8.x (Procedural / Modular structure)
- Database: MySQL / MariaDB (`billing_system.sql`)
- Frontend: HTML5, CSS3, Vanilla JavaScript
- External APIs: PayPal REST API, Google OAuth 2.0

## Directory Structure

```text
billing_system/
├── assets/               # Static stylesheets, client-side scripts, and media
├── includes/             # Shared layout templates, auth guards, and helpers
├── billing_system.sql    # Relational database schema
├── db.php                # PDO/MySQLi database connection handler
├── index.php             # Public entry point
├── dashboard.php         # Admin metrics and overview
├── client_dashboard.php  # Client-facing billing portal
├── create_invoice.php    # Invoice builder
├── invoices.php          # Invoice registry and filtering
├── view_invoice.php      # Single invoice inspection
├── print_invoice.php     # Print-ready invoice view
├── finances.php          # Cash flow and ledger summary
├── clients.php           # Customer management
├── google_callback.php   # OAuth 2.0 redirect handler
└── settings.php          # System and gateway configuration
