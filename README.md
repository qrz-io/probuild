[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/qrz-io/probuild/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/qrz-io/probuild/?branch=master)

# Probuild

A simple CLI application to help manage projects. This is useful when working with applications that doesn't support composer or when working on new code for composer modules locally, for testing with other applications or modules.

Personally, I use it for working with Magento 1.x.

# Installation
To install simply download the phar, make it executable and optionally move it to a system wide available location.
```
wget http://qrz-io.github.io/probuild/probuild.phar
chmod +x ./probuild.phar
mv probuild.phar /usr/local/bin/probuild
```

# How to use
Create a yaml file with specifying how to build your project. It should look like so:

```
target-dir: "/path/to/build"
clean: true                     #Removes current content from the target directory
clean-exceptions:               #Files or directories that should be kept during cleaning (local to target-dir)
  - "path/to/file"
  - "path/to/directory/"

link-dir-paths:                 #List of directories to hard link into target directory
  - "/path/to/dir1"
  - "/path/to/dir2"

copy-dir-paths:                 #List of directories to copy into target directory
  - "path/to/dir3"
  - "path/to/dir4"

composer: true                  #Should composer be run
update: false                   #If true, composer update will be run. If false, composer install will be run.
no-dev: false                   #Should composer be run with --no-dev
post-composer-dir-paths:        #List of directories to symlink into target directory after running composer
  - "/post/composer/path1"
  - "/post/composer/path2"

grunt: true                     #Should grunt be run
grunt-tasks:
  - "task1"
  - "task2"
  - "task3"
```

You can then run commands with it.

- To run execute everything:

    ```probuild make config.yaml```

- To create links only:

    ```probuild link config.yaml```

- To run composer only:

    ```probuild composer config.yaml```

- To run grunt only:

    ```probuild grunt config.yaml```

*Tip:* if you name your yaml file `.probuild.yaml`, you don't need to specify the config, so you could just run `probuild make` in the folder where the yaml exists.
