# Mooti Platform Setup

This is the installer/updater for the [mooti platform](https://github.com/mooti/platform).

### Documentation

The documentation can be found at http://mooti.github.io/docs

### Getting Started

Mooti Platform Setup requires php 5.6 or above. Get the [latest version](https://github.com/mooti/mooti-platform-setup/releases/latest) of the application: `https://github.com/mooti/mooti-platform-setup/releases/latest`

You may have to make the file executable

```
$ sudo chmod +x mooti-setup.phar
```

You can run it globaly by copying the application to your global bin directory

```
$ sudo mv mooti-setup.phar /usr/local/bin/
```

To setup your project simply go into your project directory and run the application

```
$ cd my-project && mooti-setup.phar
```

### Clone the repo

To clone the repo. Use the following:

```
$ git clone git@github.com:mooti/mooti-platform-setup.git
```

### Run the tests

If you would like to run the tests. Use the following:

```
$ ./vendor/bin/phpunit -c config/phpunit.xml
```
