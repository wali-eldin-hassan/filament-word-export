# Contributing to Filament Word Export

Thank you for considering contributing to the Filament Word Export plugin! This document outlines our development workflow and branching strategy.

## Git Branching Strategy

We follow a structured branching workflow to ensure code quality and proper testing before changes reach the main branch.

### Branch Types

#### 1. **Feature Branches** (`feature/description-of-feature`)
Use for new functionality or major additions:
- `feature/template-export` - Adding template-based export functionality
- `feature/watermark-support` - Adding document watermark capabilities
- `feature/multi-format-export` - Supporting multiple export formats

#### 2. **Enhancement Branches** (`enhancement/description-of-enhancement`)
Use for improvements to existing functionality:
- `enhancement/improve-table-styling` - Better table formatting options
- `enhancement/performance-optimization` - Speed improvements
- `enhancement/filament-3x-4x-compatibility` - Version compatibility updates

#### 3. **Fix Branches** (`fix/description-of-issue`)
Use for bug fixes and issue resolution:
- `fix/header-alignment-bug` - Fixing header positioning issues
- `fix/memory-leak-large-exports` - Resolving memory issues
- `fix/ci-compatibility-tests` - Fixing CI pipeline problems

#### 4. **Documentation Branches** (`docs/description`)
Use for documentation updates:
- `docs/update-installation-guide` - Improving setup instructions
- `docs/add-api-examples` - Adding usage examples
- `docs/update-compatibility-matrix` - Version support documentation

### Workflow Process

#### 1. **Create Branch from Main**
Always create new branches from the latest `main` branch:

```bash
git checkout main
git pull origin main
git checkout -b feature/your-feature-name
```

#### 2. **Development**
- Make your changes in focused, logical commits
- Follow the commit message guidelines (no emojis, imperative mood)
- Test your changes locally using `composer lint:test` and `composer refactor:dry`

#### 3. **Push and Create Pull Request**
```bash
git push origin feature/your-feature-name
```

Then create a Pull Request on GitHub using the provided link.

#### 4. **CI Validation**
All branches are automatically tested by GitHub Actions CI:
- **Code Quality**: Laravel Pint formatting and Rector static analysis
- **Security**: Composer audit for vulnerabilities
- **Compatibility**: Multiple Laravel (11.x, 12.x) and Filament (3.x, 4.x) versions
- **PHP Version**: PHP 8.3+ compatibility

#### 5. **Review and Merge**
- Wait for CI to pass (all green checks)
- Address any CI failures or review feedback
- Once approved and CI passes, merge via GitHub Pull Request
- Delete the feature branch after successful merge

### Branch Protection Rules

The `main` branch is protected with the following rules:
- **Require pull request reviews** before merging
- **Require status checks to pass** before merging
- **Require branches to be up to date** before merging
- **Restrict pushes** that create files larger than 100MB
- **Require linear history** (no merge commits)

### Commit Message Guidelines

Use clear, professional commit messages:

**Good Examples:**
```
Add Filament 3.x and 4.x compatibility support
Fix header alignment in landscape orientation
Update README with new installation requirements
Remove deprecated configuration options
```

**Avoid:**
- Emojis (🔧, ✨, 🚀, etc.)
- Vague messages ("fix stuff", "updates")
- Past tense ("Added", "Fixed" - use imperative: "Add", "Fix")

### Local Development Setup

1. **Clone the repository:**
```bash
git clone https://github.com/wali-eldin-hassan/filament-word-export.git
cd filament-word-export
```

2. **Install dependencies:**
```bash
composer install
```

3. **Run quality checks:**
```bash
composer lint:test    # Check code formatting
composer refactor:dry # Check for code improvements
```

4. **Fix code issues:**
```bash
composer lint         # Auto-fix formatting
composer refactor     # Apply code improvements
```

### Testing Strategy

Our CI pipeline tests multiple combinations:
- **Laravel 11.x + Filament 3.x** (PHP 8.3)
- **Laravel 11.x + Filament 4.x** (PHP 8.3)
- **Laravel 12.x + Filament 4.x** (PHP 8.3)

This ensures broad compatibility across the Laravel and Filament ecosystem.

### Release Process

1. **Version Tagging**: Use semantic versioning (v1.0.0, v1.1.0, v2.0.0)
2. **Release Notes**: Document changes, new features, and breaking changes
3. **Packagist**: Automatic updates via GitHub webhook

## Questions?

If you have questions about the contribution process, please:
1. Check existing GitHub Issues and Discussions
2. Create a new GitHub Discussion for general questions
3. Open a GitHub Issue for bug reports or feature requests

Thank you for contributing to making Filament Word Export better! 🙏
