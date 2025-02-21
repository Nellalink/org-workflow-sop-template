# API DEVELOPMENT GUIDE

## HTTP and Status Codes

When developing API endpoints, it is important to align the HTTP status codes with the response's `status_code` to maintain clarity and consistency. Below are the **valid HTTP status codes** and their corresponding `status_code` values that developers should use:

- **200 OK**: Successful operation, data returned in the `data` object.
- **201 Created**: The resource was successfully created.
- **400 Bad Request**: The request was invalid. There might be missing or incorrect parameters.
- **401 Unauthorized**: The request is missing authentication or the authentication is invalid.
- **404 Not Found**: The requested resource does not exist.
- **422 Unprocessable Entity**: The request was well-formed, but the server could not process it due to semantic errors.

## Success and Error Response Format

In the following sections, we show examples of how **successful** and **error** responses should be structured, including the usage of `data` and `error` fields.

---

### Success Response Example

A successful response should contain the following structure:

- `status`: Always `true` for successful responses.
- `status_code`: HTTP status code (200 or 201).
- `message`: A descriptive message explaining the success.
- `data`: The actual data returned by the API. If there is a collection of items, this should be an object containing each item by ID (e.g., for a list of orders).

**Example 1 (Success):**

```json
{
  "status": true,
  "status_code": 200,
  "message": "Success message",
  "data": {
    "1": {
      "id": "1",
      "text": "Hello World 1",
      "userId": "1"
    },
    "2": {
      "id": "2",
      "text": "Hello World 2",
      "userId": "2"
    }
  }
}
```

In this example, `data` contains multiple records identified by their IDs. 

**Example 2 (Success) - data by Array:**

```json
{
  "status": true,
  "status_code": 200,
  "message": "Success message",
  "data": [
      {
      "id": "1",
      "text": "Hello World 1",
      "userId": "1"
      },
      {
      "id": "2",
      "text": "Hello World 2",
      "userId": "2"
      }
  ]
}
```

In this example, `data` contains multiple records grouped in array. 

Note : For large collections (such as orders), it is acceptable to return data as a list or an object with identifiers as keys.

---

### Error Response Example

Error responses follow a similar structure, but with key differences:

- `status`: Always `false` for error responses.
- `status_code`: Corresponds to the appropriate error code (400, 401, 404, 422).
- `message`: A brief description of the error.
- `error`: A detailed object that contains:
  - `error_code`: A unique code identifying the specific error.
  - `message`: A detailed message explaining the error.
  - `recommendation`: Suggested action for the client (e.g., contact admin, provide a different input).
  - `key_name`: Optional, identifies the key related to the failure.

**Example (Error):**

```json
{
  "status": false,
  "status_code": 422,
  "message": "Error message",
  "error": {
    "error_code": 1002,
    "message": "Testing other key",
    "recommendation": "Please contact administrator or something use different email address",
    "key_name": "failure"
  }
}
```

In this error response:
- `error_code` provides a unique identifier for the error (1002).
- `message` offers a detailed explanation of the issue.
- `recommendation` suggests actions the user can take to resolve the problem (e.g., changing an email address).
- `key_name` identifies the specific field or key that caused the error, which is optional.

---

## How to Structure Your API Responses

### Success Responses

For successful API responses:
1. Ensure that the `status` field is `true`.
2. Use `status_code` values of 200 for success or 201 for resource creation.
3. The `data` field should always contain the actual data being returned. If you have multiple items, return them as an object where each item is identified by a unique key (e.g., `id`).

#### Example (Single Item):

```json
{
  "status": true,
  "status_code": 200,
  "message": "Operation completed successfully.",
  "data": {
    "id": "1",
    "text": "Hello World 1",
    "userId": "1"
  }
}
```

#### Example (Multiple Items):

```json
{
  "status": true,
  "status_code": 200,
  "message": "Successfully fetched data.",
  "data": {
    "1": {
      "id": "1",
      "text": "Item 1",
      "userId": "1"
    },
    "2": {
      "id": "2",
      "text": "Item 2",
      "userId": "2"
    }
  }
}
```
#### Example (Delete Item):

```json
{
  "status": true,
  "status_code": 204,
  "message": "Item deleted successfully.",
  "data": null
}
```
Notice the use of `"status": true` and `"status_code": 204` for deletion event

### Error Responses

For error responses:
1. Always set `status` to `false`.
2. Use the appropriate `status_code` (e.g., 400, 401, 404, 422).
3. Provide a meaningful `message` explaining the error.
4. The `error` field should provide an object with an `error_code`, `message`, `recommendation`, and `key_name`.

#### Example (Invalid Input):

```json
{
  "status": false,
  "status_code": 400,
  "message": "Invalid input provided.",
  "error": {
    "error_code": 1001,
    "message": "The provided email address is already in use.",
    "recommendation": "Please provide a different email address.",
    "error_data": "your own data, you can even make it json"
  }
}
```

#### Example (Unauthorized):

```json
{
  "status": false,
  "status_code": 401,
  "message": "Unauthorized access.",
  "error": {
    "error_code": 2001,
    "message": "Token missing or invalid.",
    "recommendation": "Please ensure you provide a valid token in the Authorization header.",
    "error_data": {
      "key1": "value1"
    },
    "key_name": "key_value" //other info as you deem fit
  }
}
```


### API TEST CASES

Developers are advised to implement code level test cases using JEST, PHPunit or according to the programming

For more broader devs or QA can conduct API testing using tools include Amazon API Gateway, Google Apigee, Kong Insomnia, Microsoft Azure API Management, Postman API Platform or Newgen, SmartBear ReadyAPI and SmartBear SwaggerHub.

For scalability test, we recommend JMeter.

Available for use as open source and free API testing tools include Apache JMeter, SoapUI and Rest-Assured. 

---
By following this guide, you will ensure that all API responses are consistent, easy to understand, and properly formatted, both for success and error cases.

# Standard Operating Procedure (SOP) for Deployment

**Purpose:**  
This document outlines the process and timeline for code-level and production-level deployments to ensure smooth and efficient releases with minimal disruptions.

---

### 1. **Code-Level Deployment (Branch Merging to Main)**

**Objective:**  
1. **Developers** can continually commit to their branch at anytime.
2. Developers at milestones should merge their individual branch to **`develop`** branch by creating a pull request, this pull request is accepted if all checks passed, developers should avoid merging or creating a pull request directly to **`main`** .
3. Merging of code from **`develop`** branch to the **`main`** branch is termed **DEPLOYMENT** to keep the main branch up to date and ready for production deployment, this should be done by team lead by creating a pull request  from **`develop`** to **`main`** and subsequently acceptance if all checks passed.
**N/B:** Any branches that is behind the `main` branch is not eligible for pull request and should take steps to fix the behind count to 0.

**SOFT FIX FOR BRANCH THAT ARE BEHIND THE MAIN BRANCH**
  ```
  git checkout YOUR_BRANCH_NAME_HERE
  git pull origin main
  ```

For e.g if your branch is named `sample`
To update your local `sample` branch with the latest changes from main, you can do the following:

**1 .Switch to `sample` branch in terminal by running command below:**

```
git checkout sample
```

**2. Pull (merge) changes from main into develop:**
 ```
git pull origin main
```
This above command fetches the latest changes from the remote `main` branch and merges them into your current branch `sample`.

Push your commit to your branch, it should fix the behind count to 0

**Deployment Days:**

- **Monday (Recommended):**  
  The ideal day to perform code merging. This ensures the week starts with the latest changes and allows time to resolve any issues before the end of the week.
  
- **Tuesday (If Monday’s Merge Fails):**  
  If merging didn’t happen on Monday due to any unforeseen reason, Tuesday is the second-best option to complete the task. Any delays beyond Tuesday could impact the week’s work.

- **Wednesday (Late Deployment):**  
  Merging on Wednesday is considered late and should be avoided if possible. Delays can cause a backlog in tasks and result in fewer available days to address any arising issues.

- **Thursday (Not Recommended, Except for Critical Reasons):**  
  Avoid merging on Thursday unless there is a critical update that must be applied. Any issues arising from Thursday merges may not be addressed before the weekend, risking weekend downtime.

- **Friday (No Deployment):**  
  No code-level deployment should be done on Fridays. Developers typically leave earlier for the weekend, and any deployment issues could lead to prolonged downtimes. Reserve Fridays for planning or fixing minor bugs if needed.

**Developer Standby:**  
During the deployment process, **all developers** are expected to remain on standby until the process is successfully completed. Developers should be ready to troubleshoot and address any issues that arise during the deployment.

---

### 2. **Production Level Deployment**

**Objective:**  
Deploy code to live servers, ensuring the system is up and running smoothly for end users. This also involves handling critical bugs or urgent fixes that need to be deployed immediately.

**Deployment Process:**

- **Not Done by Developers:**  
  Production-level deployment is **not performed by developers** directly. Instead, this responsibility is handled by the designated team, typically the **DevOps team** or a similar group with the necessary access and expertise to deploy to live servers.

- **Critical or Urgent Fixes:**  
  In the case of critical bugs or urgent issues affecting the live system, the DevOps team is notified immediately, and the deployment is prioritized. This may happen outside of regular deployment schedules but requires careful coordination with the development team to ensure that the fix is correctly implemented.

---

### General Guidelines for Deployment:

1. **Communication:**  
   Always inform the team about the scheduled deployment days, especially if there are any changes. Clear communication helps ensure that everyone is on the same page.

2. **Backups:**  
   Before initiating any deployment, ensure that proper backups of databases and key services are in place. This is crucial for recovery if anything goes wrong during the deployment.

3. **Testing:**  
   Code should be thoroughly tested in staging or pre-production environments before being merged or deployed to production. Any known issues should be resolved in advance to avoid unnecessary delays.

4. **Post-Deployment Monitoring:**  
   Once the deployment is completed, ensure that the system is monitored closely for any errors or issues that might arise. Developers should be on standby for any hotfixes or adjustments needed. API Test for scalability can be performed at this time.

---

By following this SOP, we ensure that code deployments are done in a structured, organized, and efficient manner, minimizing risks and maximizing team productivity.

---

# Organizational Recommended Workflow - Github Actions

Developers are expected to create a workflow in their repo preferable in `.github/workflows/main.yml`
and add the following content

```
The sample content will be here when available

```

By adding the above content, we ensure that the following checks are applied to your repo as available  - `https://github.com/Nellalink/org-workflow-sop-template/tree/main/.github/workflows`
1. Enforce Repository Rules
2. Enforce Repository Naming Convention
3. Security Checks on code  are performed

Developers are advised to automate the following
4. Workflow for Documentation especially API Docs
5. Workflow for Deployment to Servers especially test servers


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
- **PROJECT**: Represents the project name. example of valid project includes but not limited to:
    - `nellalink`
    - `middey`
    - `rimplenet`
    - `nellalinksbs`
    - `nellalinktest`
- **MODULE**: Identifies the specific functionality or module. example of valid project includes  but not limited to:
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

The workflow file at `https://github.com/Nellalink/org-workflow-sop-template/blob/main/.github/workflows/enforce-repository-name.yml` holds the current naming convention
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
    - Sends a notification webhook with details of the failure.

### Example Slack Notification:

```
The repository name 'invalid-repo-name' does not follow the valid naming convention. Please update the repository name to follow the required pattern: [SUFFIX]-[PROJECT]-[MODULE].

```

---

## Developer Actions for Compliance

### When creating a new repository:

- Ensure the repository name adheres to the naming convention.
- Use the approved provided lists for valid suffixes, projects, and modules.

### If the workflow fails:

1. Check the error message for details on which part of the name is invalid.
2. Rename the repository to comply with the naming convention.
3. Push changes or retry actions after renaming.

---

## Reference Implementation (Draft Not Tested)

The validation is implemented as part of the GitHub Action workflow:

Details of the workflow enforcing github repo naming is available at

https://github.com/Nellalink/org-workflow-sop-template/blob/main/.github/workflows/enforce-repository-name.yml

---

# RECOMMENDED : Proposed naming conventions/patterns for:

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

## Code Level Testing

Recommended testing frameworks for programming language:

1. **Go** → [`testing`](https://pkg.go.dev/testing) (built-in) or [Testify](https://github.com/stretchr/testify)  
2. **Node.js** → [Jest](https://jestjs.io/) , [Mocha](https://mochajs.org/) + [Chai](https://www.chaijs.com/), or [AVA](https://github.com/avajs/ava)  
3. **JavaScript (Frontend)** → [Jest](https://jestjs.io/), [Mocha](https://mochajs.org/), [Jasmine](https://jasmine.github.io/)  
4. **Kotlin** → [JUnit](https://junit.org/) (for JVM-based Kotlin), [KotlinTest/Kotest](https://kotest.io/)  
5. **Flutter (Dart)** → [`flutter_test`](https://api.flutter.dev/flutter/flutter_test/flutter_test-library.html) (built-in), [Mockito](https://pub.dev/packages/mockito) for mocking  
6. **Android (Java)** → [JUnit](https://junit.org/), [Espresso](https://developer.android.com/training/testing/espresso) for UI tests 
7. **PHPUnit** ([phpunit.de](https://phpunit.de/)).
8. **Pest PHP** ([pestphp.com](https://pestphp.com/)) → Simpler syntax, inspired by Jest.
9. **Codeception** ([codeception.com](https://codeception.com/)) → Good for functional and acceptance testing.
10. **Behat** ([behat.org](https://behat.org/)) → For behavior-driven development (BDD).  


## We wish you an amazing working experience.
