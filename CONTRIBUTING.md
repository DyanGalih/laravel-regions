# Contributing to laravel-region

First off, thank you for considering contributing to `laravel-region`! It's people like you that make the open-source community such a great place.

## High-Integrity Engineering Standards

This project follows a **governed engineering workflow** to ensure production-grade stability and architectural consistency.

### 1. Follow the Constitutions
Before making any changes, please read our project's core constitutions located in `.specify/memory/`:
- **[Project Constitution](.specify/memory/constitution.md)**: Our engineering philosophy and general standards.
- **[Architecture Constitution](.specify/memory/architecture_constitution.md)**: Mandatory rules for layering, caching, and query abstraction.
- **[Security Constitution](.specify/memory/security_constitution.md)**: Trust boundaries and data isolation rules.

These standards are enforced via **[Memory-Hub](https://github.com/DyanGalih/spec-kit-memory-hub)**, **[Security-Review](https://github.com/DyanGalih/spec-kit-security-review)**, and **[Architecture-Guard](https://github.com/DyanGalih/spec-kit-architecture-guard)**.

### 2. Key Architectural Rules
To ensure your PR is accepted, please adhere to these specific patterns:
- **Thin Controllers**: All controllers MUST be invokable single-action classes.
- **Centralized Queries**: Use protected factory methods (e.g., `villageQuery()`) in the `RegionService` instead of ad-hoc Eloquent calls.
- **Intelligent Caching**: All detail lookups must use the versioned, self-healing `remember()` helper.
- **Data Transfer Objects**: Use `spatie/laravel-data` for all API responses.

## Development Setup

1. **Install Dependencies**:
   ```bash
   composer install
   ```

2. **Run Tests**:
   We use **Pest** for testing. Please ensure all tests pass before submitting a PR.
   ```bash
   ./vendor/bin/pest
   ```

3. **Check Architecture Compliance**:
   If you are using the **Spec-Kit** AI agent, run the architecture review before submitting:
   ```bash
   /speckit-architecture-guard-architecture-review
   ```

## Pull Request Process

1. Create a new branch for your feature or bugfix.
2. Implement your changes following the architectural standards.
3. Add or update tests as necessary.
4. Update the documentation if you've added new features or changed existing ones.
5. Submit your PR!

## License

By contributing, you agree that your contributions will be licensed under its [MIT License](LICENSE.md).
