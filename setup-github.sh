#!/bin/bash

# GitHub Repository Setup Script
# This script helps set up the repository with proper branch protection and workflows

echo "🚀 GitHub Repository Setup"
echo "=========================="
echo ""

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    echo "❌ Error: Not in a git repository"
    exit 1
fi

# Check if GitHub CLI is installed
if ! command -v gh &> /dev/null; then
    echo "⚠️  GitHub CLI (gh) is not installed."
    echo "   Please install it from: https://cli.github.com/"
    echo "   Or continue with manual setup using the BRANCH_PROTECTION_SETUP.md guide"
    echo ""
fi

echo "📋 Setup Checklist:"
echo "==================="
echo ""

echo "1. 🌿 Push current branch to GitHub:"
echo "   git push origin feature/stage1-custom-headers-footers"
echo ""

echo "2. 🔀 Create Pull Request:"
echo "   - Go to your GitHub repository"
echo "   - Click 'Compare & pull request'"
echo "   - Fill out the PR template"
echo "   - Assign reviewers"
echo ""

echo "3. 🛡️  Set up branch protection (IMPORTANT):"
echo "   - Follow the detailed guide in: BRANCH_PROTECTION_SETUP.md"
echo "   - Or use GitHub CLI (if installed):"
if command -v gh &> /dev/null; then
    echo "   gh api repos/:owner/:repo/branches/main/protection \\"
    echo "     --method PUT \\"
    echo "     --field required_status_checks='{}' \\"
    echo "     --field enforce_admins=true \\"
    echo "     --field required_pull_request_reviews='{}' \\"
    echo "     --field restrictions=null"
fi
echo ""

echo "4. ⚙️  Enable repository features:"
echo "   - Go to Settings > General"
echo "   - Enable: Issues, Pull Requests, Discussions"
echo "   - Go to Settings > Security & analysis"
echo "   - Enable: Dependency graph, Dependabot alerts, Secret scanning"
echo ""

echo "5. 🏷️  Create first release (after merging to main):"
echo "   git tag v1.0.0"
echo "   git push origin v1.0.0"
echo ""

echo "6. 👥 Add collaborators (if needed):"
echo "   - Go to Settings > Manage access"
echo "   - Click 'Invite a collaborator'"
echo ""

echo "✅ Files created:"
echo "=================="
echo "📁 .github/workflows/"
echo "   ├── ci.yml (Continuous Integration)"
echo "   ├── release.yml (Automated releases)"
echo "   └── dependabot-auto-merge.yml (Dependency updates)"
echo ""
echo "📁 .github/"
echo "   ├── CODEOWNERS (Required reviewers)"
echo "   ├── dependabot.yml (Dependency update config)"
echo "   ├── pull_request_template.md (PR template)"
echo "   └── ISSUE_TEMPLATE/ (Bug reports & feature requests)"
echo ""
echo "📄 BRANCH_PROTECTION_SETUP.md (Detailed setup guide)"
echo ""

echo "🎯 Next Steps:"
echo "=============="
echo "1. Push this branch to GitHub"
echo "2. Set up branch protection using the guide"
echo "3. Create and merge the PR"
echo "4. Test the CI workflows"
echo ""

echo "Need help? Check the BRANCH_PROTECTION_SETUP.md file for detailed instructions!"
