# PHP DotEnv Generator
This is a tool for generating `.env` file which is a very common solution for configuration. [What's phpdotenv?](https://github.com/vlucas/phpdotenv)

Phpdotenv package is used in several php frameworks. Ex.:_laravel_. When dotenv is working, it requires a config file `.env` (or you can set those shell constants by hand, but it is obviously not handy enough, ex.: if you are going to add `API_PREFIX=api` which is a common situation you are doing REST app).

And also, in nowadays, everything is working in an automatic way. So this repository is taking care of those `.env` files.

## Use Case
There is a `laravel` project you've done. And now you are going to deploy it into several cloud servers. Of course, it is easy by pulling down the source through git...Then what about the `.env` files?  
Of cause you can copy and modify your local one then upload it, but it is not very handy, no?

So you add some config in your project's `composer.json` file like this:
```
  "extra": {
    "phpdotenv-parameters": {
      "warning": "false",
      "project": {
        "source": "./.env.ini",
        "dist": "./"
      }
    }
  }
```

Then you add a `.env.ini` like normal `ini` file. (Whether you put the `.env.ini` into git repository is controlled by you).  
The `ini` file's content may be as the following:
```
[default]
APP_ENV=local
APP_DEBUG=true
APP_KEY=
APP_TIMEZONE=UTC

[dev]
APP_KEY=dev

[test]
APP_KEY=test

[prod]
APP_KEY=prod
```

So, if you want to generate `dev` environment config, you just type `PHPDOTENV=dev phpdotenv .`. And even handy, you can also config composer to run the install for you.
```
  "scripts": {
    "post-install-cmd": [
      "Yarco\\PHPDotEnvGenerator\\ScriptHandler::generate"
    ],
    "post-update-cmd": [
      "Yarco\\PHPDotEnvGenerator\\ScriptHandler::generate"
    ]
  }
```
Of cause, in this case, you should `composer require yarco/phpdotenv-generator` this package first.

## Extra Features
* if `"warning":true`, it will generate warning messages into sys log
* you can use `projects` to deal with lots projects 