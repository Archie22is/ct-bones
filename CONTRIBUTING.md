# Dev Document

## Setup dev environment

```
npm install
composer install
```

## Dev & Build Assets

1. Configure proxy domain at `webpack.config.js`
2. Run `npm run watch` for dev (using BrowserSync) or `npm run build` for compiling to folder /assets/

## Test

1. Run `npm run test` for testing tab indent, CSS & JS coding standards.
2. Run `composer lint` for testing PHP Coding Standards.

## Autofix

1. Run `npm run fix` for automatically fix CSS, JS and tab indent.
2. Run `composer fix` for automatically fix PHP Coding Standards.

## Git Workflow

**Main branches**

- Development branch: `develop`
- Packaged branch: `master`
- Release branch: `production`

**Development branches**

- `bugfix/<name>` for bugfixes, should create from branch `develop`
- `feature/<name>` for new feature, should create from branch `develop`
- `hotfix/<name>` for hotfix, only from branch `master`

**develop**

- Contains latest build with commit **"Process styles and scripts [skip ci]"** using CI/CD

**master**

- Contains latest merge request from **develop**

**production**

- Contains latest merge request from **master**, but remove all sources such like webpack, package.json, src/ folder.
- Contains live commits, such as update WordPress, plugins.
- **Never push this branch to master**
