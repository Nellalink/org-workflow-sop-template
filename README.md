# Github Repository, Folder and File Naming  👀

## Overview

This policy outlines the repository naming conventions for all projects under our organization. Adhering to this naming convention ensures consistency, clarity, and ease of management across our repositories. Non-compliance with this policy may result in restricted actions or notifications for remediation.

---

## Naming Convention

Each repository name must follow the pattern:

```
[SUFFIX]-[PROJECT]-[MODULE]

```



### Definitions:

- **SUFFIX**: Specifies the category or layer of the application. Must be one of the following:
    - `backend`
    - `middleware`
    - `frontend`
    - `mobileapp`
- **PROJECT**: Represents the project name. Must be one of the following:
    - `middey`
    - `rimplenet`
    - `nellalinksbs`
    - `nellalinktest`
- **MODULE**: Identifies the specific functionality or module. Must be one of the following:
    - `user-app`
    - `admin-app`
    - `kyc-verification`
    - `validate-transaction`

### Example Valid Names

- `backend-middey-user-app`
- `middleware-nellalinksbs-kyc-verification`
- `frontend-nellalinktest-admin-app`
- `mobileapp-middey-android`
- `mobileapp-middey-ios`

--

## Enforced Validation

The naming convention is enforced using a GitHub Action workflow. The workflow validates the repository name whenever a repository is created or a push is made. If the name does not follow the required pattern:

1. The workflow will fail.
2. A Slack notification will be sent to alert the relevant team;

---

## Validation Workflow Details

### Trigger Events:

- Repository creation (`create` event).
- Code push (`push` event).

### Validation Steps:

1. Extract the repository name components.
2. Validate each component against the defined lists:
    - `SUFFIX` must match one of the valid suffixes.
    - `PROJECT` must match one of the valid project names.
    - `MODULE` must match one of the valid module names.
3. If validation fails, the workflow:
    - Outputs the specific error.
    - Sends a Slack notification with details of the failure.

### Example Slack Notification:

```
The repository name 'invalid-repo-name' does not follow the valid naming convention. Please update the repository name to follow the required pattern: [SUFFIX]-[PROJECT]-[MODULE].

```

---

## Developer Actions for Compliance

### When creating a new repository:

- Ensure the repository name adheres to the naming convention.
- Use the provided lists for valid suffixes, projects, and modules.

### If the workflow fails:

1. Check the error message for details on which part of the name is invalid.
2. Rename the repository to comply with the naming convention.
3. Push changes or retry actions after renaming.

---

## Reference Implementation (Draft Not Tested)

The validation is implemented as part of the GitHub Action workflow:

```yaml
name: Enforce Valid Repository Name

# on:
#   push:
#     branches:
#       - "**"
#     tags:
#       - "**"
on:
  workflow_call:
    inputs:
      slack-token:
        required: false
        type: string
      channel-id:
        required: false
        type: string

jobs:
  validate-name:
    runs-on: ubuntu-latest
    steps:
      - name: Validate Repository Name
        shell: bash
        env:
          REPO_NAME: ${{ github.event.repository.name }}
         
        run: |
          # Define valid suffixes
          VALID_SUFFIXES=("backend" "middleware" "frontend" "mobileapp" "org")
          
          # Define valid project names
          VALID_PROJECTS=("middey" "nellalinksbs" "nellalinktest" "rimplenet" "workflow")
          
          # Define valid modules
          VALID_MODULES=("user-app" "admin-app" "kyc-verification" "validate-transaction" "sop-templates" "cryptoengine")
          
          # Extract parts of the repository name
          SUFFIX=$(echo "$REPO_NAME" | cut -d- -f1)
          PROJECT=$(echo "$REPO_NAME" | cut -d- -f2)
          MODULE=$(echo "$REPO_NAME" | cut -d- -f3-)
          
          # Check if SUFFIX is valid
          if [[ ! " ${VALID_SUFFIXES[@]} " =~ " $SUFFIX " ]]; then
            echo "Error: Invalid suffix '$SUFFIX'. Must be one of: ${VALID_SUFFIXES[@]}."
            exit 1
          fi
          
          # Check if PROJECT is valid
          if [[ ! " ${VALID_PROJECTS[@]} " =~ " $PROJECT " ]]; then
            echo "Error: Invalid project name '$PROJECT'. Must be one of: ${VALID_PROJECTS[@]}."
            exit 1
          fi
          
          # Check if MODULE is valid
          if [[ ! " ${VALID_MODULES[@]} " =~ " $MODULE " ]]; then
            echo "Error: Invalid module name '$MODULE'. Must be one of: ${VALID_MODULES[@]}."
            exit 1
          fi
          
          echo "Repository name '$REPO_NAME' is valid."

      - name: Send Slack Notification on Invalid Repository Name
        if: failure()  
        uses: distributhor/workflow-webhook@v3
        with:
          webhook_url: https://api.jubbytech.com/webhook/
          webhook_secret: '4544af'
          data: '{"title": "Invalid Repository Name Detected","text": "The repository name does not follow the valid naming convention.","repo": "${{ github.event.repository.name }}","format": "[SUFFIX]-[PROJECT]-[MODULE]"}'

```

---

# Proposed Naming conventions/Patterns for:

1. **Files/Folders** 
2. **Branching**
3. **Commit Messages**

---

### **1. Naming Conventions**

### **Files and Folders**

- Use lowercase with hyphens for file and folder names.
    - Example:
        - `user-profile.js`
        - `order-details/`
- Group files logically by feature or module.
    - Example: `features/user-management/`, `components/header/`

### **Database Tables**

- Use snake_case for table and column names.
    - Example: `user_profiles`, `created_at`

---

### **2. Standardized Branching Strategy**

### **Git Flow Example**

- **Main Branches**:
    - `main`: Always stable, contains production-ready code.
    - `develop`: Integration branch for ongoing development.
- **Feature Branches**:
    - Naming: `feature/<feature-name>`
        - Example: `feature/add-user-authentication`
- **Bugfix Branches**:
    - Naming: `bugfix/<ticket-id>-<bug-description>`
        - Example: `bugfix/1234-fix-login-error`
- **Release Branches**:
    - Naming: `release/<version-number>`
        - Example: `release/1.2.0`
- **Hotfix Branches**:
    - Naming: `hotfix/<ticket-id>-<description>`
        - Example: `hotfix/5678-critical-payment-bug`

---

### **3. Writing Meaningful Commit Messages**

### **Structure:**

```
<type>: <short description>

<body>

```

### **Types**:

- `feat`: A new feature.
- `fix`: A bug fix.
- `refactor`: Code change that does not fix a bug or add a feature.
- `docs`: Changes to documentation.
- `test`: Adding or modifying tests.
- `chore`: Routine task (e.g., updating dependencies).

### **Examples**:

1. `feat: add user authentication`
    - Body: "Implemented login, registration, and logout functionality using OAuth."
2. `fix: resolve null pointer exception in OrderService`
    - Body: "Added null checks to avoid crashes when order ID is invalid."
