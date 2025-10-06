# Branch Protection Setup Guide

This document provides step-by-step instructions for setting up branch protection rules for the `main` branch.

## 🔒 Branch Protection Rules

### Step 1: Access Repository Settings

1. Go to your GitHub repository
2. Click on **Settings** tab
3. In the left sidebar, click **Branches**

### Step 2: Add Branch Protection Rule

1. Click **Add rule** button
2. In **Branch name pattern**, enter: `main`

### Step 3: Configure Protection Settings

Enable the following options:

#### ✅ Required Status Checks
- [x] **Require status checks to pass before merging**
- [x] **Require branches to be up to date before merging**

**Required status checks to add:**
- `Code Quality (PHP 8.2)`
- `Code Quality (PHP 8.3)`
- `Security Audit`
- `Laravel Compatibility`

#### ✅ Pull Request Requirements
- [x] **Require a pull request before merging**
- [x] **Require approvals** (set to 1 minimum)
- [x] **Dismiss stale reviews when new commits are pushed**
- [x] **Require review from code owners** (if you have CODEOWNERS file)

#### ✅ Additional Restrictions
- [x] **Restrict pushes that create files larger than 100 MB**
- [x] **Require signed commits** (optional but recommended)
- [x] **Require linear history** (optional - prevents merge commits)

#### ✅ Administrative Settings
- [x] **Include administrators** (applies rules to admins too)
- [x] **Allow force pushes** - **UNCHECK THIS** (prevents force pushes)
- [x] **Allow deletions** - **UNCHECK THIS** (prevents branch deletion)

### Step 4: Save Protection Rule

Click **Create** to save the branch protection rule.

## 🛡️ Additional Security Measures

### Code Owners File (Optional)

Create a `.github/CODEOWNERS` file to require specific people to review changes:

```
# Global owners
* @wali

# Configuration files
config/ @wali
*.yml @wali
*.yaml @wali

# Core functionality
src/Services/ @wali
src/Support/ @wali
```

### Repository Security Settings

1. Go to **Settings** > **Security & analysis**
2. Enable:
   - [x] **Dependency graph**
   - [x] **Dependabot alerts**
   - [x] **Dependabot security updates**
   - [x] **Secret scanning**

### Webhook Configuration (Optional)

For additional notifications, you can set up webhooks:

1. Go to **Settings** > **Webhooks**
2. Add webhook for your team communication tools (Slack, Discord, etc.)

## 🚀 Workflow Integration

The GitHub Actions workflows will automatically:

1. **Run on every PR** to `main` branch
2. **Check code quality** with Pint and Rector
3. **Run security audits** 
4. **Test compatibility** with multiple PHP/Laravel versions
5. **Block merging** if any checks fail

## 📋 Merge Process

With these protections in place, the merge process will be:

1. **Create feature branch** from `main`
2. **Make changes** and commit
3. **Push branch** to GitHub
4. **Create Pull Request** to `main`
5. **Wait for CI checks** to pass
6. **Request review** from team members
7. **Address feedback** if needed
8. **Merge** once approved and all checks pass

## 🔧 Emergency Procedures

In case of emergency (hotfixes), administrators can:

1. **Temporarily disable** branch protection
2. **Push directly** to main (not recommended)
3. **Re-enable protection** immediately after

**Note:** It's better to create an emergency PR and use admin override if available.

## 📞 Support

If you need help with branch protection setup:

1. Check GitHub's [official documentation](https://docs.github.com/en/repositories/configuring-branches-and-merges-in-your-repository/defining-the-mergeability-of-pull-requests/about-protected-branches)
2. Contact repository administrators
3. Create an issue in the repository
