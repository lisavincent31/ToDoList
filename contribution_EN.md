# Contributing to the development of the application

Dear developers, the purpose of this document is to present you with the key steps for contributing to the improvement and development of the ToDo&Co application.

## Create issues

Before starting to develop new features, I strongly suggest that you create a new issue on GitHub. 

The name of the issue should be numbered and bear the name of the branch you are going to create. 

Example: **8. branch_name**

Each issue should include a list of tasks to be carried out in order to achieve your objective. The tasks should be clear and quick to carry out. I recommend that you commit after completing a task.

Example: **Create a new controller**, **Create a feature**.

## New branch

You should never develop directly on the main **master** branch. We recommend that you create a new branch by following the instructions below: 

1. Check that you are on the most advanced branch of the project or on **master** :

```bash
git branch
git checkout master
```

2. Create your new branch using the following nomenclature: **modification_type/purpose_date_name**

Example: If you need to create a user dashboard

**feature/user_dashboard_VINCENT_092024**

Here are the different possible types:
- tests: implementation of tests
- feature: implementation of new functions
- bugfix: bug fixes to the application
- doc : modify readme, add documentation

```bash
git checkout -b branch_name
```

## Saving changes

Every day, you should save your work on GitHub by making a commit.
Each commit should make unique and logical changes. Your commit message should describe your changes clearly.

To do this, type the following commands in your terminal:

```bash
git status
git add filename
git commit -m ‘changes made
git push -u origin branch_name
```

Every morning, we recommend that you retrieve all the backups made on the various branches. Simply type the command :

```bash
git pull
```

## Pull Request

At the end of your modifications, you must make a pull request from your branch to the **master** branch. The description of your pull request should be precise about the changes you have made and the tests to be run. Don't hesitate to mention the name of the issue linked to your modifications.

At least one other developer must check your pull request before the branches are merged. 

Make sure that all the tests pass before the two branches are merged.